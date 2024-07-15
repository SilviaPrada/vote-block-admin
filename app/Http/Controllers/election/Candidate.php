<?php

namespace App\Http\Controllers\Election;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Kreait\Firebase\Factory;

class Candidate extends Controller
{
    public function connect()
    {
        $firebase = (new Factory)
                    ->withServiceAccount(base_path(env('FIREBASE_CREDENTIALS')))
                    ->withDatabaseUri(env("FIREBASE_DATABASE_URL"));
                    
        return $firebase->createDatabase();
    }

    public function showAddCandidateForm()
    {
        // Get elections for the form
        $elections = $this->connect()->getReference('elections')->getValue();
        return view('content.candidate.add-candidate', ['elections' => $elections]);
    }

    public function storeCandidate(Request $request)
    {
        // Validasi data yang diterima
        $request->validate([
            'candidate_id' => 'required|int',
            'name' => 'required|string|max:255',
            'vision' => 'required|string',
            'mission' => 'required|string',
            'elections' => 'required|array',
        ]);

        // Save candidate data in Firebase Realtime Database
        $newCandidateRef = $this->connect()->getReference('candidates')->push();
        $newCandidateRef->set([
            'candidate_id' => $request->input('candidate_id'),
            'name' => $request->input('name'),
            'vision' => $request->input('vision'),
            'mission' => $request->input('mission'),
            'elections' => $request->input('elections'),
        ]);

        return redirect()->route('candidates')->with('success', 'Candidate added successfully!');
    }

    public function showCandidates()
    {
        // Ambil data dari Firebase Realtime Database
        $candidates = $this->connect()->getReference('candidates')->getValue();
        $elections = $this->connect()->getReference('elections')->getValue();

        return view('content.candidate.candidate', ['candidates' => $candidates, 'elections' => $elections]);
    }
}
