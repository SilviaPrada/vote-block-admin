<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Kreait\Firebase\Factory;
use GuzzleHttp\Client;

class Dashboard extends Controller
{
    public function connect()
    {
        $firebase = (new Factory)
                    ->withServiceAccount(base_path(env('FIREBASE_CREDENTIALS')))
                    ->withDatabaseUri(env("FIREBASE_DATABASE_URL"));

        return $firebase->createDatabase();
    }

    public function index()
    {
        // Create a new Guzzle client
        $client = new Client();

        try {
            // Fetch votes for each election
            $response = $client->get('http://localhost:3000/getAllVotes');
            $votes = json_decode($response->getBody(), true);
        } catch (\Exception $e) {
            // Handle any errors
            $votes = [];
        }

        // Fetch elections and candidates from Firebase
        $database = $this->connect();
        $elections = $database->getReference('elections')->getValue();
        $candidates = $database->getReference('candidates')->getValue();
        $voters = $database->getReference('voters')->getValue();

        // Create a mapping of IDs to names
        $electionNames = [];
        foreach ($elections as $key => $election) {
            $electionNames[$election['election_id']] = $election['name'];
        }

        $candidateNames = [];
        foreach ($candidates as $key => $candidate) {
            $candidateNames[$candidate['candidate_id']] = $candidate['name'];
        }

        // Calculate votes per election
        $electionVotes = [];
        foreach ($votes as $voteSet) {
            $electionId = hexdec($voteSet[0]['hex']);
            if (!isset($electionVotes[$electionId])) {
                $electionVotes[$electionId] = 0;
            }
            $electionVotes[$electionId]++;
        }

        // Fetch total voters for each election from Firebase
        $totalVoters = [];
        foreach ($elections as $key => $election) {
            $electionId = $election['election_id'];
            $totalVoters[$electionId] = 0;

            foreach ($voters as $voter) {
                if (isset($voter['elections']) && in_array($electionId, $voter['elections'])) {
                    $totalVoters[$electionId]++;
                }
            }
        }

        // Pass the data to the view, including the name mappings
        return view('content.dashboard.dashboard', [
            'votes' => $votes,
            'electionNames' => $electionNames,
            'candidateNames' => $candidateNames,
            'electionVotes' => $electionVotes,
            'totalVoters' => $totalVoters
        ]);
    }
}
