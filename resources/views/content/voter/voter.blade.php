@extends('layouts/contentNavbarLayout')

@section('title', 'Voters')

@section('content')
    <h4 class="py-3 mb-4"><span class="text-muted fw-light">Tables/</span> Voters</h4>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Voters List</h5>
            <a href="{{ route('add-voter') }}" class="btn rounded-pill btn-primary">
                <span class="tf-icons mdi mdi-checkbox-marked-circle-outline me-1"></span> Add Voter
            </a>
        </div>
        <div class="table-responsive text-nowrap">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Elections</th>
                        <th>Has Voted</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @if($voters)
                        @foreach($voters as $id => $voter)
                            <tr>
                                <td>{{ $voter['voter_id'] }}</td>
                                <td>{{ $voter['name'] }}</td>
                                <td>{{ $voter['email'] }}</td>
                                <td>
                                    @if(isset($voter['elections']))
                                        <ul>
                                            @foreach($voter['elections'] as $electionId)
                                                @foreach($elections as $election)
                                                    @if($election['election_id'] == $electionId)
                                                        <li><span class="badge bg-label-primary">{{ $election['name'] }}</span></li>
                                                    @endif
                                                @endforeach
                                            @endforeach
                                        </ul>
                                    @endif
                                </td>
                                <td>
                                    @if(isset($voter['elections']))
                                        <ul>
                                            @foreach($voter['elections'] as $electionId)
                                                @php
                                                    $voterId = $voter['voter_id'];
                                                    $hasVoted = isset($votesMapping[$voterId][$electionId]);
                                                @endphp
                                                <li>
                                                    @if($hasVoted)
                                                        <i class="mdi mdi-check-circle-outline text-success"></i> Yes
                                                    @else
                                                        <i class="mdi mdi-close-circle-outline text-danger"></i> No
                                                    @endif
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </td>
                                <td>
                                    <div class="dropdown">
                                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                            <i class="mdi mdi-dots-vertical"></i>
                                        </button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" href="{{ route('edit-voter', $id) }}"><i class="mdi mdi-pencil-outline me-1"></i> Edit</a>
                                            <a class="dropdown-item" href="{{ route('delete-voter', $id) }}"><i class="mdi mdi-trash-can-outline me-1"></i> Delete</a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="6" class="text-center">No voters found</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
@endsection
