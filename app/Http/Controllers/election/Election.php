<?php

namespace App\Http\Controllers\Election;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Kreait\Firebase\Factory;
use Carbon\Carbon;

class Election extends Controller
{
    public function connect()
    {
        $firebase = (new Factory)
                    ->withServiceAccount(base_path(env('FIREBASE_CREDENTIALS')))
                    ->withDatabaseUri(env("FIREBASE_DATABASE_URL"));
                    
        return $firebase->createDatabase();
    }

    public function showElectionForm($id = null)
    {
        $election = null;

        if ($id) {
            $election = $this->connect()->getReference('elections/' . $id)->getValue();
        }

        return view('content.election.add-election', compact('election', 'id'));
    }

    public function storeElection(Request $request)
    {
        // Validasi data yang diterima
        $request->validate([
            'election_id' => 'required|string|max:255',
            'date' => 'required|date|after_or_equal:today',
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'status' => 'required|string|in:Active,Inactive,Complete',
        ]);

        // Cek duplikasi election_id di Firebase, kecuali jika sedang mengedit
        $elections = $this->connect()->getReference('elections')->getValue();
        foreach ($elections as $key => $election) {
            if ($election['election_id'] === $request->input('election_id') && $key !== $request->input('id')) {
                return redirect()->back()->withErrors(['election_id' => 'ID already exists']);
            }
        }

        // Validasi status dan tanggal
        $date = Carbon::parse($request->input('date'));
        if ($request->input('status') === 'Active' && $date->isFuture()) {
            return redirect()->back()->withErrors(['status' => 'Status cannot be Active before the date']);
        }

        // Tambahkan atau perbarui data di Firebase
        if ($request->input('id')) {
            // Update
            $this->connect()->getReference('elections/' . $request->input('id'))->update([
                'election_id' => $request->input('election_id'),
                'name' => $request->input('name'),
                'date' => $request->input('date'),
                'description' => $request->input('description'),
                'status' => $request->input('status'),
            ]);
        } else {
            // Tambahkan baru
            $newElectionRef = $this->connect()->getReference('elections')->push();
            $newElectionRef->set([
                'election_id' => $request->input('election_id'),
                'name' => $request->input('name'),
                'date' => $request->input('date'),
                'description' => $request->input('description'),
                'status' => $request->input('status'),
            ]);
        }

        return redirect()->route('elections')->with('success', 'Election saved successfully!');
    }

    public function showElections()
    {
        // Ambil data dari Firebase
        $elections = $this->connect()->getReference('elections')->getValue();

        return view('content.election.election', ['elections' => $elections]);
    }

    public function updateElection(Request $request, $id)
    {
        $request->validate([
            'election_id' => 'required|string|max:255',
            'date' => 'required|date|after_or_equal:today',
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'status' => 'required|string|in:Active,Inactive,Complete',
        ]);

        $date = Carbon::parse($request->input('date'));
        if ($request->input('status') === 'Active' && $date->isFuture()) {
            return redirect()->back()->withErrors(['status' => 'Status cannot be Active before the date']);
        }

        $this->connect()->getReference('elections/' . $id)->update([
            'election_id' => $request->input('election_id'),
            'name' => $request->input('name'),
            'date' => $request->input('date'),
            'description' => $request->input('description'),
            'status' => $request->input('status'),
        ]);

        return redirect()->route('elections')->with('success', 'Election updated successfully!');
    }

    public function deleteElection($id)
    {
        $this->connect()->getReference('elections/' . $id)->remove();
        return redirect()->route('elections')->with('success', 'Election deleted successfully!');
    }
}
