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
        <div>

            {{-- <a class='' style="float:right;"href="{{ url('/admin/land') }}"><i data-lucide="file-plus"></i>Single Land
                Upload</a> --}}


        </div>
    </div>

    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Plot Batch Upload</h4>
                <hr>

                <form id="signupForm" method="POST" action="{{ route('admin.savePlotsBatch') }}"
                    enctype="multipart/form-data">
                    @csrf

                    <div class="row">
                        <div class="col-md-4">
                            <label for="ageSelect" class="form-label">Land</label><span class="text-danger">(*)</span>
                            <select class="js-example-basic-single form-select" name="land_id" id="ageSelect">
                                <option selected disabled>Select Land</option>
                                @foreach ($land_names as $item)
                                    <option value="{{ $item->id }}">{{ $item->land_name }}
                                        (N :{{ number_format($item->cost, 2) }}-{{ $item->lga }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-8 ">

                            <div class="custom-file">

                                <label class="custom-file-label" for="PlotFileName">Choose file</label>
                                <input class="form-control" type="file" name = 'plot_filename' id="formFile">
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

                        <a class='' style="float:right;"href="{{ url('/admin/land-distribution') }}"><i
                                data-lucide="file-plus"></i>Single Plot
                            Upload</a>


                    </div>
                    <br>
                    <h6 class="card-title">Plot's Record</h6>

                    <div class="table-responsive">
                        <table id="dataTableExample" class="table">
                            <thead>
                                <tr>
                                    <th>S/N</th>
                                    <th>Land Name</th>
                                    <th>Plot No</th>
                                    <th>Cost</th>
                                    <th>Dimension</th>
                                    <th>Edit</th>
                                </tr>
                            </thead>
                            <tbody>



                                @foreach ($plots as $item)
                                    <tr>
                                        <td> {{ $loop->iteration }} </td>
                                        <td> {{ $item->land_name }}</td>
                                        <td> {{ $item->plot_no }}</td>
                                        <td> N {{ number_format($item->cost), 2 }}</td>
                                        <td> {{ $item->dimension }}</td>


                                        <td><a href="" class="btn btn-primary btn-sm "><i data-lucide="edit"></i></a>
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
