@extends('member.layout.master')
{{-- @extends('admin.asset.scripts') --}}
@push('plugin-styles')
    <link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
@endpush

@section('content')
    <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
        <div>
            <h4 class="mb-3 mb-md-0">Welcome Plot View Page</h4>
        </div>

    </div>


    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Plots</a></li>
            <li class="breadcrumb-item active" aria-current="page">Record</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">

            <div class="card">
                <div class="card-body">
                    <div>

                    </div>
                    <br>
                    <h6 class="card-title">Available Plot(s)</h6>
                    <div>
                        <a class="btn btn-success" href="{{ url('/member/view-invoice/booking') }}">View Booked Invoice</a>
                        <br> <span class='text-danger' style='font-size:20px;'>Note that, you have upto the end of today to
                            make a
                            deposit or the plot will be
                            revoked.</span>
                    </div>
                    <div class="table-responsive">
                        <table id="dataTableExample" class="table">
                            <thead>
                                <tr>
                                    <th>S/N</th>

                                    <th>Land Name</th>
                                    <th>Plot No</th>
                                    <th>Cost</th>
                                    <th>Dimension</th>

                                    <th>Book</th>
                                </tr>
                            </thead>
                            <tbody>



                                @foreach ($plots as $item)
                                    <tr>
                                        <td> {{ $loop->iteration }} </td>

                                        <td> {{ $item->land_name }}</td>
                                        <td> {{ $item->plot_no }}</td>
                                        <td> {{ number_format($item->cost, 2) }}</td>
                                        <td> {{ $item->dimension }}</td>

                                        <td>
                                            <form action="{{ route('member.showBooking') }}" method="POST">
                                                @csrf
                                                <input type="text" name="plot_id" id="plot_id" readonly
                                                    value="{{ $item->id }}" hidden>

                                                <div class="col-sm-12">
                                                    <input type="submit" class="btn btn-info" value="Book"
                                                        name='book'>
                                            </form>
                                        </td>

                                    </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('plugin-scripts')
    <script src="{{ asset('assets/plugins/datatables-net/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.js') }}"></script>
@endpush

@push('custom-scripts')
    <script src="{{ asset('assets/js/data-table.js') }}"></script>
@endpush
