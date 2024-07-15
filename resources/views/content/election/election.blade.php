@extends('layouts/contentNavbarLayout')

@section('title', 'Tables - Basic Tables')

@section('content')
    <h4 class="py-3 mb-4"><span class="text-muted fw-light">Tables /</span> Basic Tables</h4>

    <!-- Hoverable Table rows -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Hoverable rows</h5>
            <a href="{{ route('add-election') }}" class="btn rounded-pill btn-primary">
                <span class="tf-icons mdi mdi-checkbox-marked-circle-outline me-1"></span> Add Election
            </a>
        </div>
        <div class="table-responsive text-nowrap">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Date</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @if($elections)
                        @foreach($elections as $id => $election)
                            <tr>
                                <td>{{ $election['election_id'] }}</td>
                                <td>{{ $election['date'] }}</td>
                                <td>{{ $election['name'] }}</td>
                                <td>{{ $election['description'] }}</td>
                                <td>
                                    <span class="badge rounded-pill 
                                        @if($election['status'] == 'Active')
                                            bg-primary
                                        @elseif($election['status'] == 'Inactive')
                                            bg-danger
                                        @else
                                            bg-success
                                        @endif
                                    ">
                                        {{ $election['status'] }}
                                    </span>
                                </td>
                                <td>
                                    <div class="dropdown">
                                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                            <i class="mdi mdi-dots-vertical"></i>
                                        </button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" href="javascript:void(0);"><i class="mdi mdi-pencil-outline me-1"></i> Edit</a>
                                            <a class="dropdown-item" href="javascript:void(0);"><i class="mdi mdi-trash-can-outline me-1"></i> Delete</a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="5" class="text-center">No elections found</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
    <!--/ Hoverable Table rows -->
@endsection
