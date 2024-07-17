<?php

namespace App\Http\Controllers\Election;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Kreait\Firebase\Factory;
use GuzzleHttp\Client;

class Voter extends Controller
{
    public function connect()
    {
        $factory = (new Factory)
                    ->withServiceAccount(base_path(env('FIREBASE_CREDENTIALS')))
                    ->withDatabaseUri(env("FIREBASE_DATABASE_URL"));

        return [
            'database' => $factory->createDatabase(),
            'auth' => $factory->createAuth(),
        ];
    }

    public function fetchVotes()
    {
        $client = new Client();
        try {
            $response = $client->get('http://localhost:3000/getAllVotes');
            return json_decode($response->getBody(), true);
        } catch (\Exception $e) {
            return [];
        }
    }

    public function showAddVoterForm()
    {
        $connections = $this->connect();
        $elections = $connections['database']->getReference('elections')->getValue();
        return view('content.voter.add-voter', ['elections' => $elections, 'voter' => null]);
    }

    public function storeVoter(Request $request)
    {
        $request->validate([
            'voter_id' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'email' => 'nullable|email',
            'password' => 'required|string|min:6',
            'elections' => 'required|array',
        ]);

        $connections = $this->connect();
        $database = $connections['database'];
        $auth = $connections['auth'];
        $voter_id = $request->input('voter_id');
        $election_ids = $request->input('elections');

        $existingVoters = $database->getReference('voters')->getValue();
        foreach ($existingVoters as $voter) {
            if ($voter['voter_id'] == $voter_id && !array_diff($election_ids, $voter['elections'])) {
                return redirect()->back()->withErrors(['Voter already exists for the selected elections.']);
            }
        }

        // Create Auth Account
        try {
            $auth->createUserWithEmailAndPassword($request->input('email'), $request->input('password'));
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['Error creating authentication account: ' . $e->getMessage()]);
        }

        // Store Voter in Database
        $newVoterRef = $database->getReference('voters')->push();
        $newVoterRef->set([
            'voter_id' => $voter_id,
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => $request->input('password'),
            'elections' => $election_ids,
        ]);

        return redirect()->route('voters')->with('success', 'Voter added successfully!');
    }

    public function editVoterForm($id)
    {
        $connections = $this->connect();
        $database = $connections['database'];
        $voter = $database->getReference('voters/' . $id)->getValue();
        $elections = $database->getReference('elections')->getValue();

        return view('content.voter.add-voter', ['elections' => $elections, 'voter' => $voter, 'voterId' => $id]);
    }

    public function updateVoter(Request $request, $id)
    {
        $request->validate([
            'voter_id' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'email' => 'nullable|email',
            'password' => 'required|string|min:6',
            'elections' => 'required|array',
        ]);

        $connections = $this->connect();
        $database = $connections['database'];
        $auth = $connections['auth'];

        // Update Auth Account
        try {
            $auth->updateUser($id, [
                'email' => $request->input('email'),
                'password' => $request->input('password'),
                'displayName' => $request->input('name'),
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['Error updating authentication account: ' . $e->getMessage()]);
        }

        // Update Voter in Database
        $database->getReference('voters/' . $id)->update([
            'voter_id' => $request->input('voter_id'),
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => $request->input('password'),
            'elections' => $request->input('elections'),
        ]);

        return redirect()->route('voters')->with('success', 'Voter updated successfully!');
    }

    public function deleteVoter($id)
    {
        $connections = $this->connect();
        $database = $connections['database'];
        $auth = $connections['auth'];

        // Delete Auth Account
        try {
            $auth->deleteUser($id);
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['Error deleting authentication account: ' . $e->getMessage()]);
        }

        // Delete Voter in Database
        $database->getReference('voters/' . $id)->remove();

        return redirect()->route('voters')->with('success', 'Voter deleted successfully!');
    }

    public function showVoters()
    {
        $connections = $this->connect();
        $database = $connections['database'];
        $voters = $database->getReference('voters')->getValue();
        $elections = $database->getReference('elections')->getValue();
        $votes = $this->fetchVotes();

        $votesMapping = [];
        foreach ($votes as $vote) {
            $electionId = hexdec($vote[0]['hex']);
            $voterId = hexdec($vote[2]['hex']);
            $votesMapping[$voterId][$electionId] = true;
        }

        return view('content.voter.voter', [
            'voters' => $voters,
            'elections' => $elections,
            'votesMapping' => $votesMapping
        ]);
    }
}
