@extends('layouts/contentNavbarLayout')

@section('title', isset($election) ? 'Edit Election' : 'Add Election')

@section('content')
<h4 class="py-3 mb-4"><span class="text-muted fw-light">Forms /</span> {{ isset($election) ? 'Edit Election' : 'Add Election' }}</h4>

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

<div class="row">
    <div class="col-xxl">
        <div class="card mb-4">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="mb-0">{{ isset($election) ? 'Edit Election' : 'Add Election' }}</h5>
                <small class="text-muted float-end">Merged input group</small>
            </div>
            <div class="card-body">
                <form action="{{ route('store-election') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id" value="{{ $id ?? '' }}">
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label" for="election_id">ID</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="election_id" name="election_id"
                                value="{{ old('election_id', $election['election_id'] ?? '') }}" required />
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label" for="basic-icon-default-date">Date</label>
                        <div class="col-sm-10">
                            <div class="input-group input-group-merge">
                                <span id="basic-icon-default-date2" class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                                <input type="date" class="form-control" id="basic-icon-default-date" name="date"
                                    value="{{ old('date', $election['date'] ?? '') }}" required />
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label" for="basic-icon-default-name">Name</label>
                        <div class="col-sm-10">
                            <div class="input-group input-group-merge">
                                <span id="basic-icon-default-name2" class="input-group-text"><i class="mdi mdi-account-outline"></i></span>
                                <input type="text" id="basic-icon-default-name" class="form-control" name="name"
                                    value="{{ old('name', $election['name'] ?? '') }}" required />
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label" for="basic-icon-default-description">Description</label>
                        <div class="col-sm-10">
                            <div class="input-group input-group-merge">
                                <span class="input-group-text"><i class="mdi mdi-text"></i></span>
                                <input type="text" id="basic-icon-default-description" class="form-control"
                                    name="description" value="{{ old('description', $election['description'] ?? '') }}" required />
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-2 form-label" for="basic-icon-default-status">Status</label>
                        <div class="col-sm-10">
                            <div class="form-check form-check-inline mt-3">
                                <input class="form-check-input" type="radio" name="status" id="inlineRadio1"
                                    value="Active" {{ old('status', $election['status'] ?? '') == 'Active' ? 'checked' : '' }} />
                                <label class="form-check-label" for="inlineRadio1">Active</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="status" id="inlineRadio2"
                                    value="Inactive" {{ old('status', $election['status'] ?? '') == 'Inactive' ? 'checked' : '' }} />
                                <label class="form-check-label" for="inlineRadio2">Inactive</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="status" id="inlineRadio3"
                                    value="Complete" {{ old('status', $election['status'] ?? '') == 'Complete' ? 'checked' : '' }} />
                                <label class="form-check-label" for="inlineRadio3">Complete</label>
                            </div>
                        </div>
                    </div>
                    <div class="row justify-content-end">
                        <div class="col-sm-10">
                            <button type="submit" class="btn btn-primary">{{ isset($election) ? 'Update' : 'Add' }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
