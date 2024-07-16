@extends('layouts/contentNavbarLayout')

@section('title', isset($candidate) ? 'Edit Candidate' : 'Add Candidate')

@section('content')
<h4 class="py-3 mb-4"><span class="text-muted fw-light">Forms /</span> {{ isset($candidate) ? 'Edit Candidate' : 'Add Candidate' }}</h4>

@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<!-- Add/Edit Candidate Form -->
<div class="row">
  <div class="col-xxl">
    <div class="card mb-4">
      <div class="card-header d-flex align-items-center justify-content-between">
        <h5 class="mb-0">{{ isset($candidate) ? 'Edit Candidate' : 'Add Candidate' }}</h5>
      </div>
      <div class="card-body">
        <form method="POST" action="{{ isset($candidate) ? route('update-candidate', ['id' => $id]) : route('store-candidate') }}">
          @csrf
          @if(isset($candidate)) @method('PUT') @endif
          <div class="row mb-3">
            <label class="col-sm-2 col-form-label" for="candidate_id">Number</label>
            <div class="col-sm-10">
              <div class="input-group input-group-merge">
                <span class="input-group-text"><i class="mdi mdi-numeric"></i></span>
                <input type="number" name="candidate_id" class="form-control" id="candidate_id" placeholder="Candidate Number" required min="1" value="{{ old('candidate_id', $candidate ? $candidate['candidate_id'] : '') }}" />
              </div>
            </div>
          </div>
          <div class="row mb-3">
            <label class="col-sm-2 col-form-label" for="name">Name</label>
            <div class="col-sm-10">
              <div class="input-group input-group-merge">
                <span class="input-group-text"><i class="mdi mdi-account-outline"></i></span>
                <input type="text" name="name" class="form-control" id="name" placeholder="Candidate Name" required value="{{ old('name', $candidate ? $candidate['name'] : '') }}" />
              </div>
            </div>
          </div>
          <div class="row mb-3">
            <label class="col-sm-2 col-form-label" for="vision">Vision</label>
            <div class="col-sm-10">
              <div class="input-group input-group-merge">
                <span class="input-group-text"><i class="mdi mdi-eye-outline"></i></span>
                <textarea name="vision" class="form-control" id="vision" placeholder="Candidate Vision" rows="5">{{ old('vision', $candidate ? $candidate['vision'] : '') }}</textarea>
              </div>
            </div>
          </div>
          <div class="row mb-3">
            <label class="col-sm-2 col-form-label" for="mission">Mission</label>
            <div class="col-sm-10">
              <div class="input-group input-group-merge">
                <span class="input-group-text"><i class="mdi mdi-target"></i></span>
                <textarea name="mission" class="form-control" id="mission" placeholder="Candidate Mission" rows="5" required>{{ old('mission', $candidate ? $candidate['mission'] : '') }}</textarea>
              </div>
            </div>
          </div>
          <div class="row mb-3">
            <label class="col-sm-2 col-form-label" for="elections">Elections</label>
            <div class="col-sm-10">
              @foreach($elections as $electionId => $election)
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="elections[]" id="election{{ $electionId }}" value="{{ $electionId }}" @if(in_array($electionId, old('elections', $candidate ? $candidate['elections'] : []))) checked @endif>
                  <label class="form-check-label" for="election{{ $electionId }}">
                    {{ $election['name'] }}
                  </label>
                </div>
              @endforeach
            </div>
          </div>
          <div class="row justify-content-end">
            <div class="col-sm-10">
              <button type="submit" class="btn btn-primary">{{ isset($candidate) ? 'Update' : 'Add' }} Candidate</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
