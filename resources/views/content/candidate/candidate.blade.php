@extends('layouts/contentNavbarLayout')

@section('title', 'Candidates')

@section('content')
<h4 class="py-3 mb-4"><span class="text-muted fw-light">Tables/</span> Candidates</h4>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Candidates List</h5>
        <a href="{{ route('add-candidate') }}" class="btn rounded-pill btn-primary">
            <span class="tf-icons mdi mdi-plus-circle-outline me-1"></span> Add Candidate
        </a>
    </div>
    <div class="table-responsive text-nowrap">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Number</th>
                    <th>Name</th>
                    <th>Vision</th>
                    <th>Mission</th>
                    <th>Elections</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody class="table-border-bottom-0">
                @if ($candidates)
                    @foreach ($candidates as $id => $candidate)
                        <tr>
                            <td>{{ $candidate['candidate_id'] }}</td>
                            <td>{{ $candidate['name'] }}</td>
                            <td>{{ Str::limit($candidate['vision'], 30, '...') }}</td>
                            <td>{{ Str::limit($candidate['mission'], 30, '...') }}</td>
                            <td>
                                @if (isset($candidate['elections']))
                                    @foreach ($candidate['elections'] as $electionId)
                                        @if (isset($elections[$electionId]))
                                            <span class="badge" style="background-color: {{ sprintf('#%06X', mt_rand(0, 0xFFFFFF)) }}">{{ $elections[$electionId]['name'] }}</span>
                                        @endif
                                    @endforeach
                                @endif
                            </td>
                            <td>
                                <div class="dropdown">
                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                        data-bs-toggle="dropdown">
                                        <i class="mdi mdi-dots-vertical"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="{{ route('add-candidate', ['id' => $id]) }}">
                                            <i class="mdi mdi-pencil-outline me-1"></i> Edit
                                        </a>
                                        <form action="{{ route('delete-candidate', $id) }}" method="POST"
                                            style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button class="dropdown-item" type="submit"
                                                onclick="return confirm('Are you sure you want to delete this candidate?')">
                                                <i class="mdi mdi-trash-can-outline me-1"></i> Delete
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="6" class="text-center">No candidates found</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>
@endsection
