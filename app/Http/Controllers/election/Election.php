<?php

namespace App\Http\Controllers\Election;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Kreait\Firebase\Factory;

class Election extends Controller
{
  	public function connect()
    {
        $firebase = (new Factory)
                    ->withServiceAccount(base_path(env('FIREBASE_CREDENTIALS')))
                    ->withDatabaseUri(env("FIREBASE_DATABASE_URL"));
                    
        return $firebase->createDatabase();
    }

	public function showAddElectionForm()
    {
        return view('content.election.add-election');
    }

    public function storeElection(Request $request)
    {
        // Validasi data yang diterima
        $request->validate([
            'election_id' => 'required|string|max:255',
            'date' => 'required|date',
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'status' => 'required|string|in:Active,Inactive,Complete',
        ]);

        // Tambahkan data ke Firebase
        $newElectionRef = $this->connect()->getReference('elections')->push();
        $newElectionRef->set([
            'election_id' => $request->input('voter_id'),
            'name' => $request->input('name'),
            'date' => $request->input('date'),
            'description' => $request->input('description'),
            'status' => $request->input('status'),
        ]);

        return redirect()->route('elections')->with('success', 'Election added successfully!');
    }

    public function showElections()
    {
        // Ambil data dari Firebase
        $elections = $this->connect()->getReference('elections')->getValue();

        return view('content.election.election', ['elections' => $elections]);
    }
}