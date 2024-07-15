@extends('layouts/contentNavbarLayout')

@section('title', ' Horizontal Layouts - Forms')

@section('content')
    <h4 class="py-3 mb-4"><span class="text-muted fw-light">Forms/</span> Horizontal Layouts</h4>

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

    <!-- Basic Layout & Basic with Icons -->
    <div class="row">
        <!-- Basic with Icons -->
        <div class="col-xxl">
            <div class="card mb-4">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="mb-0">Basic with Icons</h5> <small class="text-muted float-end">Merged input group</small>
                </div>
                <div class="card-body">
                    <form action="{{ url('/add-election') }}" method="POST">
                        @csrf
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="election_id">ID</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="election_id" name="election_id"
                                    placeholder="1234567890" required />
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="basic-icon-default-date">Date</label>
                            <div class="col-sm-10">
                                <div class="input-group input-group-merge">
                                    <span id="basic-icon-default-date2" class="input-group-text"><i
                                            class="mdi mdi-calendar"></i></span>
                                    <input type="date" class="form-control" id="basic-icon-default-date" name="date"
                                        placeholder="Select Date" aria-label="Select Date"
                                        aria-describedby="basic-icon-default-date2" required />
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="basic-icon-default-name">Name</label>
                            <div class="col-sm-10">
                                <div class="input-group input-group-merge">
                                    <span id="basic-icon-default-name2" class="input-group-text"><i
                                            class="mdi mdi-account-outline"></i></span>
                                    <input type="text" id="basic-icon-default-name" class="form-control" name="name"
                                        placeholder="John Doe" aria-label="John Doe"
                                        aria-describedby="basic-icon-default-name2" required />
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="basic-icon-default-description">Description</label>
                            <div class="col-sm-10">
                                <div class="input-group input-group-merge">
                                    <span class="input-group-text"><i class="mdi mdi-text"></i></span>
                                    <input type="text" id="basic-icon-default-description" class="form-control"
                                        name="description" placeholder="Description" aria-label="Description"
                                        aria-describedby="basic-icon-default-description2" required />
                                </div>
                                <div class="form-text"> Provide a brief description </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-sm-2 form-label" for="basic-icon-default-status">Status</label>
                            <div class="col-sm-10">
                                <div class="form-check form-check-inline mt-3">
                                    <input class="form-check-input" type="radio" name="status" id="inlineRadio1"
                                        value="Active" required />
                                    <label class="form-check-label" for="inlineRadio1">Active</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="status" id="inlineRadio2"
                                        value="Inactive" required />
                                    <label class="form-check-label" for="inlineRadio2">Inactive</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="status" id="inlineRadio3"
                                        value="Complete" required />
                                    <label class="form-check-label" for="inlineRadio3">Complete</label>
                                </div>
                            </div>
                        </div>
                        <div class="row justify-content-end">
                            <div class="col-sm-10">
                                <button type="submit" class="btn btn-primary">Send</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
