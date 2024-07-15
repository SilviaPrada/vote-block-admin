<?php

namespace App\Http\Controllers\Election;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Kreait\Firebase\Factory;

class Voter extends Controller
{
    public function connect()
    {
        $firebase = (new Factory)
                    ->withServiceAccount(base_path(env('FIREBASE_CREDENTIALS')))
                    ->withDatabaseUri(env("FIREBASE_DATABASE_URL"));
                    
        return $firebase->createDatabase();
    }

    public function showAddVoterForm()
    {
        // Get elections for the form
        $elections = $this->connect()->getReference('elections')->getValue();
        return view('content.voter.add-voter', ['elections' => $elections]);
    }

    public function storeVoter(Request $request)
    {
        // Validasi data yang diterima
        $request->validate([
            'voter_id' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'email' => 'nullable|email',
            'password' => 'required|string|min:6',
            'elections' => 'required|array',
            'hasVoted' => 'required|boolean',
        ]);

        // Get election_ids from the form input
        $election_ids = $request->input('elections');

        // Save voter data in Firebase Realtime Database
        $newVoterRef = $this->connect()->getReference('voters')->push();
        $newVoterRef->set([
            'voter_id' => $request->input('voter_id'),
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => $request->input('password'),
            'elections' => $election_ids,
            'hasVoted' => $request->input('hasVoted'),
        ]);

        return redirect()->route('voters')->with('success', 'Voter added successfully!');
    }

    public function showVoters()
    {
        // Ambil data dari Firebase Realtime Database
        $voters = $this->connect()->getReference('voters')->getValue();
        $elections = $this->connect()->getReference('elections')->getValue();

        return view('content.voter.voter', ['voters' => $voters, 'elections' => $elections]);
    }
}
