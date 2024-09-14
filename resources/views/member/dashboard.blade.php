@extends('member.layout.master')
{{-- @extends('member.asset.scripts') --}}


@section('content')
    <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
        <div>
            <h4 class="mb-3 mb-md-0">Welcome to Dashboard</h4>
            {{-- Welcome {{ Auth::admin()->name }} --}}
        </div>

    </div>

    <div class="row">
        <div class="col-12 col-xl-12 stretch-card">
            <div class="row flex-grow-1">
                <div class="col-md-3 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-baseline">
                                <h6 class="card-title mb-0">Plots</h6>

                            </div>
                            <div class="row">
                                <div class="col-6 col-md-12 col-xl-12">
                                    {{-- @foreach ($plot_count as $item) --}}
                                    <h3 class="mb-2">{{ $plot_count }}</h3>
                                    {{-- @endforeach --}}

                                    <div class="d-flex align-items-baseline">
                                        <p class="text-success">
                                            {{-- <span>+3.3%</span> --}}
                                            {{-- <i data-lucide="arrow-up" class="icon-sm mb-1"></i> --}}
                                        </p>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-baseline">
                                <h6 class="card-title mb-0">Cost</h6>

                            </div>
                            <div class="row">
                                <div class="col-6 col-md-12 col-xl-12">

                                    <h3 class="mb-2">N {{ number_format($cost, 2) }}</h3>

                                    <div class="d-flex align-items-baseline">
                                        <p class="text-danger">

                                            {{-- <i data-lucide="arrow-down" class="mdi mdi-account"></i> --}}
                                        </p>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-baseline">
                                <h6 class="card-title mb-0">Amount Paid</h6>

                            </div>
                            <div class="row">
                                <div class="col-6 col-md-12 col-xl-12">
                                    {{-- @foreach ($member_no as $item)
                                        <h3 class="mb-2">{{ $item->no_members }}</h3>
                                    @endforeach --}}
                                    <h3 class="mb-2">N {{ number_format($amount_paid, 2) }}</h3>
                                    <div class="d-flex align-items-baseline">
                                        <p class="text-danger">

                                            {{-- <i data-lucide="arrow-down" class="mdi mdi-account"></i> --}}
                                        </p>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-baseline">
                                <h6 class="card-title mb-0">Outstanding</h6>

                            </div>
                            <div class="row">
                                <div class="col-6 col-md-12 col-xl-12">
                                    {{-- @foreach ($member_no as $item)
                                        <h3 class="mb-2">{{ $item->no_members }}</h3>
                                    @endforeach --}}
                                    <h3 class="mb-2">N {{ number_format($cost - $amount_paid, 2) }}</h3>
                                    <div class="d-flex align-items-baseline">
                                        <p class="text-danger">

                                            {{-- <i data-lucide="arrow-down" class="mdi mdi-account"></i> --}}
                                        </p>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </div> <!-- row -->





    <div class="row">
        <div class="col-lg-12 col-xl-12 stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-baseline mb-2">
                        <h6 class="card-title mb-0">Payment Track</h6>
                        <div class="dropdown mb-2">

                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th class="pt-0">S/N</th>
                                    <th class="pt-0">Land Name</th>
                                    <th class="pt-0">Plots No</th>
                                    <th class="pt-0">Dimenssion</th>
                                    <th class="pt-0">Cost</th>
                                    <th class="pt-0">Amount Paid</th>
                                </tr>
                            </thead>
                            <tbody>

                                @foreach ($plots as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->land_name }}</td>
                                        <td>{{ $item->plot_no }}</td>
                                        <td>{{ $item->dimension }}</td>
                                        <td>N {{ $item->cost }}</td>
                                        <td>N {{ $item->amount }}</td>

                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>


    </div> <!-- row -->
@endsection
