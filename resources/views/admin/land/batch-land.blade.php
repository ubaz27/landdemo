@extends('admin.layout.master')
{{-- @extends('admin.asset.scripts') --}}
@push('plugin-styles')
    <link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
@endpush

@section('content')
    <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
        <div>
            <h4 class="mb-3 mb-md-0">Welcome to Land Distribution Form</h4>
        </div>

    </div>

    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Batch Land Upload</h4>
                <hr>

                <form id="signupForm" method="POST" action="{{ route('admin.batchLandUpload') }}"
                    enctype="multipart/form-data">
                    @csrf

                    <div class="row">
                        <div class="col-md-12 ">

                            <div class="custom-file">

                                <label class="custom-file-label" for="MembersFile">Choose file</label>
                                <input class="form-control" type="file" name = 'land_filename' id="formFile">
                            </div>
                        </div>
                    </div>
                    <br>
                    <input class="btn btn-primary" type="submit" value="Submit" style="width:30%;">
                </form>



            </div>
        </div>
    </div>

    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Land</a></li>
            <li class="breadcrumb-item active" aria-current="page">Record</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">

            <div class="card">
                <div class="card-body">
                    <div>

                        <a class='' style="float:right;"href="{{ url('/admin/land') }}"><i
                                data-lucide="file-plus"></i>Single Land
                            Upload</a>


                    </div>
                    <br>
                    <h6 class="card-title">Land Record</h6>

                    <div class="table-responsive">
                        <table id="dataTableExample" class="table">
                            <thead>
                                <tr>
                                    <th>S/N</th>
                                    <th>Name</th>
                                    <th>Cost</th>
                                    <th>Dimension</th>
                                    <th>Commission</th>
                                    <th>LGA</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>



                                @foreach ($land as $item)
                                    <tr>
                                        <td> {{ $loop->iteration }} </td>
                                        <td> {{ $item->land_name }}</td>
                                        <td> {{ $item->cost }}</td>
                                        <td> {{ $item->dimension }}</td>
                                        <td> {{ $item->commission }}</td>
                                        <td> {{ $item->lga }}</td>
                                        <td>
                                            {{-- <a href="" class="btn btn-primary btn-sm "><i data-lucide="edit"></i></a> --}}
                                            <form action="{{ route('admin.PlotsReportExcel') }}" method="POST">
                                                @csrf
                                                <input type="text" name="land_id" id="land_id" readonly
                                                    value="{{ $item->id }}" hidden>

                                                <div class="col-sm-12">
                                                    <button type="submit" name="type" value="excel"
                                                        class="btn btn-info float-right btn-sm"><i class="fas fa-print"></i>
                                                        Excel</button>
                                            </form>
                                        </td>

                                    </tr>
                                    {{-- {{ $i++ }} --}}
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
