@extends('layouts/contentNavbarLayout')

@section('title', 'Dashboard - Analytics')

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/apex-charts/apex-charts.css') }}">
@endsection

@section('vendor-script')
    <script src="{{ asset('assets/vendor/libs/apex-charts/apexcharts.js') }}"></script>
@endsection

@section('page-script')
    <script src="{{ asset('assets/js/dashboard.js') }}"></script>
@endsection

@section('content')
    <div class="row gy-4">

        <!-- Transactions -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center justify-content-between">
                        <h4 class="card-title m-0 me-2">Pemilihan Ketua OSIS</h4>
                        <div class="btn-group">
                            <button type="button" class="btn btn-primary btn-icon rounded-pill dropdown-toggle hide-arrow"
                                data-bs-toggle="dropdown" aria-expanded="false"><i
                                    class="mdi mdi-dots-vertical"></i></button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="javascript:void(0);">Action</a></li>
                                <li><a class="dropdown-item" href="javascript:void(0);">Another action</a></li>
                                <li><a class="dropdown-item" href="javascript:void(0);">Something else here</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item" href="javascript:void(0);">Separated link</a></li>
                            </ul>
                        </div>
                    </div>
                    <p class="mt-2"><span class="fw-medium">Pemilihan dilakukan untuk menentukan ketua OSIS
                            selanjutnya</span></p>
                    <div class="small mb-1">Senin, 23 Juli 2024</div>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-3 col-6">
                            <div class="d-flex align-items-center">
                                <div class="avatar">
                                    <div class="avatar-initial bg-primary rounded shadow">
                                        <i class="mdi mdi-vote-outline mdi-24px"></i>
                                    </div>
                                </div>
                                <div class="ms-3">
                                    <div class="small mb-1">Votes</div>
                                    <h5 class="mb-0">245k</h5>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="d-flex align-items-center text-align-center">
                                <div class="avatar">
                                    <div class="avatar-initial bg-success rounded shadow">
                                        <i class="mdi mdi-account-multiple mdi-24px"></i>
                                    </div>
                                </div>
                                <div class="ms-3">
                                    <div class="small mb-1">Voter</div>
                                    <h5 class="mb-0">12.5k</h5>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="d-flex align-items-center">
                                <div class="avatar">
                                    <div class="avatar-initial bg-warning rounded shadow">
                                        <i class="mdi mdi-account-check-outline mdi-24px"></i>
                                    </div>
                                </div>
                                <div class="ms-3">
                                    <div class="small mb-1">Candidate</div>
                                    <h5 class="mb-0">1.54k</h5>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="d-flex flex-column align-items-end justify-content-end h-100">
                                <span class="badge rounded-pill bg-label-success me-1 mb-1">
                                    Active
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <img src="{{ asset('assets/img/icons/misc/triangle-light.png') }}"
                    class="scaleX-n1-rtl position-absolute bottom-0 end-0" width="166" alt="triangle background">
            </div>
        </div>
        <!--/ Transactions -->

        <!-- Pie Chart for Vote Results -->
        <div class="col-md-12 col-lg-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-2">Vote Result</h5>
                    <div class="d-flex justify-content-center align-items-center h-100">
                        <div id="pieChartVoteResults"></div>
                    </div>
                </div>
            </div>
        </div>
        <!--/ Pie Chart for Vote Results -->


        <!-- Total Votes -->
        <div class="col-xl-4 col-md-6">
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="card-title m-0 me-2">Total Votes</h5>
                </div>
                <div class="card-body">
                    <ul class="p-0 m-0">
                        @foreach ($electionVotes as $electionId => $votesCount)
                            @php
                                $electionName = $electionNames[$electionId] ?? 'Unknown';
                                $votersCount = $totalVoters[$electionId] ?? 0;
                                $percentage = $votersCount > 0 ? ($votesCount / $votersCount) * 100 : 0;
                            @endphp
                            <li class="d-flex mb-4 pb-md-2">
                                <div>
                                    <span
                                        class="badge badge-center rounded-pill bg-label-primary">{{ $electionId }}</span>
                                </div>
                                <div class="d-flex ms-3 w-100 flex-wrap align-items-center justify-content-between gap-2">
                                    <div class="me-2">
                                        <h6 class="mb-0">{{ $electionName }}</h6>
                                        <small>{{ $votesCount }} from {{ $votersCount }} voters already voted</small>
                                    </div>
                                    <div class="w-100">
                                        <div class="progress bg-label-primary" style="height: 4px;">
                                            <div class="progress-bar bg-primary" style="width: {{ $percentage }}%"
                                                role="progressbar" aria-valuenow="{{ $percentage }}" aria-valuemin="0"
                                                aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        <!--/ Total Votes -->



        <!-- Weekly Overview Chart -->
        <div class="col-xl-4 col-md-6">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between">
                        <h5 class="mb-1">Vote Percentage</h5>
                    </div>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-center align-items-center h-100">
                        <div id="weeklyOverviewChart"></div>
                    </div>
                    <div class="mt-1 mt-md-3">
                        <div class="d-flex align-items-center gap-3">
                            <p class="mb-0">Percentage of voters who have voted</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--/ Weekly Overview Chart -->


        <!-- Four Cards -->
        <div class="col-xl-4 col-md-6">
            <!-- Total Profit line chart -->
            <div class="card">
                <div class="card-header pb-0">
                    <h4 class="mb-0">Election time graph</h4>
                </div>
                <div class="card-body">
                    <div id="totalProfitLineChart" class="mb-3"></div>
                    <h6 class="text-center mb-0">Total Profit</h6>
                </div>
            </div>
        </div>
    </div>
    <!--/ Total Profit line chart -->

    <!-- Data Tables -->
    <div class="col-12 mt-4">
        <div class="card">
            <div class="table-responsive">
                <table class="table">
                    <thead class="table-light">
                        <tr>
                            <th class="text-truncate">Date</th>
                            <th class="text-truncate">Transaction Hash</th>
                            <th class="text-truncate">Block Number</th>
                            <th class="text-truncate">Election Name</th>
                            <th class="text-truncate">Candidate Name</th>
                            <th class="text-truncate">Vote Count</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (isset($votes) && !empty($votes))
                            @foreach ($votes as $voteSet)
                                @php
                                    $electionId = hexdec($voteSet[0]['hex']);
                                    $candidateId = hexdec($voteSet[1]['hex']);
                                @endphp
                                <tr>
                                    <td class="text-truncate">
                                        {{ \Carbon\Carbon::createFromTimestamp(hexdec($voteSet[4]['hex']))->toDateTimeString() }}
                                    </td>
                                    <td class="text-truncate">{{ Str::limit($voteSet[5], 10, '...') }}</td>
                                    <td class="text-truncate">{{ hexdec($voteSet[6]['hex']) }}</td>
                                    <td class="text-truncate">{{ $electionNames[$electionId] ?? 'Unknown' }}</td>
                                    <td class="text-truncate">{{ $candidateNames[$candidateId] ?? 'Unknown' }}</td>
                                    <td class="text-truncate">{{ hexdec($voteSet[3]['hex']) }}</td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="7" class="text-center">No votes found.</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!--/ Data Tables -->




@endsection
