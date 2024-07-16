@extends('layouts/contentNavbarLayout')

@section('title', $voter ? 'Edit Voter' : 'Add Voter')

@section('content')
    <h4 class="py-3 mb-4">
        <span class="text-muted fw-light">{{ $voter ? 'Edit' : 'Add' }} Voter</span>
    </h4>

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
            <h5 class="mb-0">{{ $voter ? 'Edit' : 'Add' }} Voter</h5>
        </div>
        <div class="card-body">
            <form action="{{ $voter ? route('update-voter', $voterId) : route('store-voter') }}" method="POST">
                @csrf
                @if($voter)
                    @method('PUT')
                @endif

                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label" for="voter_id">ID</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="voter_id" name="voter_id" value="{{ old('voter_id', $voter['voter_id'] ?? '') }}" required />
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label" for="name">Name</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $voter['name'] ?? '') }}" required />
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label" for="email">Email (Optional)</label>
                    <div class="col-sm-10">
                        <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $voter['email'] ?? '') }}" />
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label" for="password">Password</label>
                    <div class="col-sm-10">
                        <input type="password" class="form-control" id="password" name="password" value="{{ old('password', $voter['password'] ?? '') }}" required />
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label" for="elections">Elections</label>
                    <div class="col-sm-10">
                        @foreach($elections as $election)
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="{{ $election['election_id'] }}" id="election-{{ $election['election_id'] }}" name="elections[]" 
                                    @if(isset($voter) && in_array($election['election_id'], $voter['elections'])) checked @endif>
                                <label class="form-check-label" for="election-{{ $election['election_id'] }}">
                                    {{ $election['name'] }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-10 offset-sm-2">
                        <button type="submit" class="btn btn-primary">{{ $voter ? 'Update' : 'Add' }} Voter</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
