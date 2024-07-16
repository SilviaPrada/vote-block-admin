<?php

namespace App\Http\Controllers\Election;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Kreait\Firebase\Factory;
use Illuminate\Validation\ValidationException;

class Candidate extends Controller
{
    public function connect()
    {
        $firebase = (new Factory)
                    ->withServiceAccount(base_path(env('FIREBASE_CREDENTIALS')))
                    ->withDatabaseUri(env("FIREBASE_DATABASE_URL"));
                    
        return $firebase->createDatabase();
    }

    public function showCandidateForm($id = null)
    {
        $candidate = null;

        if ($id) {
            $candidate = $this->connect()->getReference('candidates/' . $id)->getValue();
        }

        $elections = $this->connect()->getReference('elections')->getValue();

        return view('content.candidate.add-candidate', compact('candidate', 'id', 'elections'));
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

        // Cek duplikasi candidate_id dan elections di Firebase
        $candidates = $this->connect()->getReference('candidates')->getValue();
        foreach ($candidates as $key => $candidate) {
            if ($candidate['candidate_id'] == $request->input('candidate_id') && $candidate['elections'] == $request->input('elections')) {
                return redirect()->back()->withErrors(['candidate_id' => 'The candidate with this ID and elections already exists.']);
            }
        }

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

    public function updateCandidate(Request $request, $id)
    {
        $request->validate([
            'candidate_id' => 'required|int',
            'name' => 'required|string|max:255',
            'vision' => 'required|string',
            'mission' => 'required|string',
            'elections' => 'required|array',
        ]);

        // Cek duplikasi candidate_id dan elections di Firebase
        $candidates = $this->connect()->getReference('candidates')->getValue();
        foreach ($candidates as $key => $candidate) {
            if ($candidate['candidate_id'] == $request->input('candidate_id') && $candidate['elections'] == $request->input('elections') && $key !== $id) {
                return redirect()->back()->withErrors(['candidate_id' => 'The candidate with this ID and elections already exists.']);
            }
        }

        $this->connect()->getReference('candidates/'.$id)->update([
            'candidate_id' => $request->input('candidate_id'),
            'name' => $request->input('name'),
            'vision' => $request->input('vision'),
            'mission' => $request->input('mission'),
            'elections' => $request->input('elections'),
        ]);

        return redirect()->route('candidates')->with('success', 'Candidate updated successfully!');
    }

    public function deleteCandidate($id)
    {
        $this->connect()->getReference('candidates/'.$id)->remove();
        return redirect()->route('candidates')->with('success', 'Candidate deleted successfully!');
    }


    public function showCandidates()
    {
        // Ambil data dari Firebase Realtime Database
        $candidates = $this->connect()->getReference('candidates')->getValue();
        $elections = $this->connect()->getReference('elections')->getValue();

        return view('content.candidate.candidate', ['candidates' => $candidates, 'elections' => $elections]);
    }
}
