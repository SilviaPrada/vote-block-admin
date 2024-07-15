@extends('layouts/contentNavbarLayout')

@section('title', 'Add Voter')

@section('content')
    <h4 class="py-3 mb-4"><span class="text-muted fw-light">Forms/</span> Add Voter</h4>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card mb-4">
        <div class="card-header d-flex align-items-center justify-content-between">
            <h5 class="mb-0">Add Voter</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('store-voter') }}" method="POST">
                @csrf
                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label" for="voter_id">ID</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="voter_id" name="voter_id" placeholder="1234567890" required />
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label" for="name">Name</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="name" name="name" placeholder="John Doe" required />
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label" for="email">Email (Optional)</label>
                    <div class="col-sm-10">
                        <input type="email" class="form-control" id="email" name="email" placeholder="john.doe@example.com" />
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label" for="password">Password</label>
                    <div class="col-sm-10">
                        <input type="password" class="form-control" id="password" name="password" placeholder="******" required />
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label" for="elections">Elections</label>
                    <div class="col-sm-10">
                        @foreach($elections as $election)
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="{{ $election['election_id'] }}" id="election-{{ $election['election_id'] }}" name="elections[]">
                                <label class="form-check-label" for="election-{{ $election['election_id'] }}">
                                    {{ $election['name'] }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label">Has Voted</label>
                    <div class="col-sm-10">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="hasVoted" id="hasVotedYes" value="1" required>
                            <label class="form-check-label" for="hasVotedYes">Yes</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="hasVoted" id="hasVotedNo" value="0" required>
                            <label class="form-check-label" for="hasVotedNo">No</label>
                        </div>
                    </div>
                </div>
                <div class="row justify-content-end">
                    <div class="col-sm-10">
                        <button type="submit" class="btn btn-primary">Add Voter</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
