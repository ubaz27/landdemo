@extends('member.layout.master')
{{-- @extends('member.asset.scripts') --}}
@push('plugin-styles')
    <link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
@endpush

@section('content')
    <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
        <div>
            <h4 class="mb-3 mb-md-0">Welcome to Payment History</h4>
            {{-- Welcome {{ Auth::admin()->name }} --}}
        </div>

    </div>


    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Plots</a></li>
            <li class="breadcrumb-item active" aria-current="page">Payment Records</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-baseline mb-2">
                        <h6 class="card-title mb-0">Payment Track</h6>
                        <div class="dropdown mb-2">

                        </div>
                    </div>
                    <div class="table-responsive">
                        <table id="dataTableExample" class="table">
                            <thead>
                                <tr>
                                    <th class="pt-0">S/N</th>
                                    <th class="pt-0">Land Name</th>
                                    <th class="pt-0">Plots No</th>
                                    <th class="pt-0">Dimenssion</th>
                                    <th class="pt-0">Cost</th>
                                    <th class="pt-0">Amount Paid</th>
                                    <th class="pt-0">Month</th>
                                </tr>
                            </thead>
                            <tbody>

                                @foreach ($plots as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->land_name }}</td>
                                        <td>{{ $item->plot_no }}</td>
                                        <td>{{ $item->dimension }}</td>
                                        <td>N {{ number_format($item->cost, 2) }}</td>
                                        <td>N {{ number_format($item->amount, 2) }}</td>
                                        <td> {{ $item->month }}</td>

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


@push('plugin-scripts')
    <script src="{{ asset('assets/plugins/datatables-net/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.js') }}"></script>
@endpush

@push('custom-scripts')
    <script src="{{ asset('assets/js/data-table.js') }}"></script>
@endpush
