@extends('admin.layout.master')
{{-- @extends('admin.asset.scripts') --}}
@push('plugin-styles')
    <link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
@endpush

@section('content')
    <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
        <div>
            <h4 class="mb-3 mb-md-0">Welcome to Land Distribution Page</h4>
        </div>

    </div>
    {{-- {{ dd('ss') }} --}}

    <h4 class="card-title">Agent Plot Information</h4>
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Agents</a></li>
            <li class="breadcrumb-item active" aria-current="page">Information</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div><span><a class='' style="float:right;"href="{{ url('/admin/member-land') }}"><i
                                    data-lucide="file-plus"></i>Allocate Land</a></span></div>
                    <h6 class="card-title">Agents Plots</h6>

                    <div class="table-responsive">
                        <table id="dataTableExample" class="table">
                            <thead>
                                <tr>
                                    <th>S/N</th>
                                    <th>Agent Name</th>
                                    <th>Agent Phone</th>
                                    <th>Land Name</th>
                                    <th>Plot No</th>
                                    <th>Plot Dimension</th>
                                    <th>Plot Cost</th>
                                    <th>Commission</th>
                                    <th>Member Phone</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>


                                @foreach ($agents as $item)
                                    <tr>
                                        <td> {{ $loop->iteration }} </td>
                                        <td> {{ $item->name }}</td>
                                        <td> {{ $item->phone }}</td>
                                        <td> {{ $item->land_name }}</td>
                                        <td> {{ $item->plot_no }}</td>
                                        <td> {{ $item->dimension }}</td>
                                        <td> N {{ number_format($item->cost), 2 }}</td>
                                        <td> {{ number_format($item->commission, 3) }}</td>
                                        <td> {{ $item->member_phone }}</td>
                                        <td><a href="" class="btn btn-primary btn-sm "><i
                                                    data-lucide="edit"></i></a></td>
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
