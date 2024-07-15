@extends('layouts/contentNavbarLayout')

@section('title', 'Add Candidate')

@section('content')
<h4 class="py-3 mb-4"><span class="text-muted fw-light">Forms/</span> Add Candidate</h4>

<!-- Add Candidate Form -->
<div class="row">
  <div class="col-xxl">
    <div class="card mb-4">
      <div class="card-header d-flex align-items-center justify-content-between">
        <h5 class="mb-0">Add Candidate</h5>
      </div>
      <div class="card-body">
        <form method="POST" action="{{ route('store-candidate') }}">
          @csrf
          <div class="row mb-3">
            <label class="col-sm-2 col-form-label" for="candidate_id">Number</label>
            <div class="col-sm-10">
              <div class="input-group input-group-merge">
                <span class="input-group-text"><i class="mdi mdi-numeric"></i></span>
                <input type="number" name="candidate_id" class="form-control" id="candidate_id" placeholder="Candidate Number" required />
              </div>
            </div>
          </div>
          <div class="row mb-3">
            <label class="col-sm-2 col-form-label" for="name">Name</label>
            <div class="col-sm-10">
              <div class="input-group input-group-merge">
                <span class="input-group-text"><i class="mdi mdi-account-outline"></i></span>
                <input type="text" name="name" class="form-control" id="name" placeholder="Candidate Name" required />
              </div>
            </div>
          </div>
          <div class="row mb-3">
            <label class="col-sm-2 col-form-label" for="vision">Vision</label>
            <div class="col-sm-10">
              <div class="input-group input-group-merge">
                <span class="input-group-text"><i class="mdi mdi-eye-outline"></i></span>
                <input type="text" name="vision" class="form-control" id="vision" placeholder="Candidate Vision" />
              </div>
            </div>
          </div>
          <div class="row mb-3">
            <label class="col-sm-2 col-form-label" for="mission">Mission</label>
            <div class="col-sm-10">
              <div class="input-group input-group-merge">
                <span class="input-group-text"><i class="mdi mdi-target"></i></span>
                <input type="text" name="mission" class="form-control" id="mission" placeholder="Candidate Mission" required />
              </div>
            </div>
          </div>
          <div class="row mb-3">
            <label class="col-sm-2 col-form-label" for="elections">Elections</label>
            <div class="col-sm-10">
              <select name="elections[]" class="form-control" id="elections" multiple required>
                @foreach($elections as $id => $election)
                  <option value="{{ $id }}">{{ $election['name'] }}</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="row justify-content-end">
            <div class="col-sm-10">
              <button type="submit" class="btn btn-primary">Add Candidate</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
