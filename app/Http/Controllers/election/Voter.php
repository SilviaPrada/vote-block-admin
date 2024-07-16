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
        $firebase = (new Factory)
                    ->withServiceAccount(base_path(env('FIREBASE_CREDENTIALS')))
                    ->withDatabaseUri(env("FIREBASE_DATABASE_URL"));

        return $firebase->createDatabase();
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
        $elections = $this->connect()->getReference('elections')->getValue();
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

        $database = $this->connect();
        $voter_id = $request->input('voter_id');
        $election_ids = $request->input('elections');

        $existingVoters = $database->getReference('voters')->getValue();
        foreach ($existingVoters as $voter) {
            if ($voter['voter_id'] == $voter_id && !array_diff($election_ids, $voter['elections'])) {
                return redirect()->back()->withErrors(['Voter already exists for the selected elections.']);
            }
        }

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
        $database = $this->connect();
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

        $database = $this->connect();
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
        $this->connect()->getReference('voters/' . $id)->remove();

        return redirect()->route('voters')->with('success', 'Voter deleted successfully!');
    }

    public function showVoters()
    {
        $database = $this->connect();
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
