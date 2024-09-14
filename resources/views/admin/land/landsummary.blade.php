@extends('admin.layout.master')
{{-- @extends('admin.asset.scripts') --}}
@push('plugin-styles')
    <link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
@endpush

@section('content')
    <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
        <div>
            <h4 class="mb-3 mb-md-0">Welcome to Land Information Form</h4>
        </div>

    </div>

    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Land Details</h4>
                <hr>
                {{-- @if (Session::has('message'))
                    <div class="alert alert-primary" role="alert">
                        <ul>
                            {{ Session('message') }}

                        </ul>
                    </div>
                @endif --}}


                {{-- <p class="text-muted mb-3">Read the <a href="https://jqueryvalidation.org/" target="_blank"> Official jQuery
                        Validation Documentation </a>for a full list of instructions and other options.</p> --}}
                <form action={{ route('admin.saveLandInfo') }} id="signupForm" method= 'POST'>
                    @csrf
                    <div class="row">
                        <div class="col-md-4 ">
                            <label for="name" class="form-label">Land Name</label><span class="text-danger">(*)</span>
                            <input id="name"
                                class="form-control @if (!empty($errors)) @error('land_name') is-invalid @enderror @endif"
                                name="land_name" value="{{ old('land_name') }}" type="text" required>
                            @if (!empty($errors))
                                @error('land_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            @endif


                        </div>

                        <div class="col-md-4">
                            <label for="ageSelect" class="form-label">Location(LGA)</label> <span
                                class="text-danger">(*)</span>
                            <select class="js-example-basic-single form-select" name="lga_select" id="lga_select" required>
                                <option selected disabled>Select Location(LGA)</option>
                                @foreach ($lgas as $lga)
                                    <option>{{ $lga->lga }}</option>
                                @endforeach
                            </select>
                        </div>



                        <div class="col-md-3">
                            <label for="ageSelect" class="form-label">Cost (₦)</label> <span class="text-danger">(*)</span>
                            <input type="text" class="form-control @error('cost') is-invalid @enderror" id="cost"
                                name="cost" value="{{ old('cost') }}" placeholder="Provide cost" required>
                            @error('cost')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>



                        <div class="col-md-4">
                            <label for="dimension" class="form-label">Dimension</label> <span class="text-danger">(*)</span>
                            <input id="dimension" class="form-control" name="dimension" type="text" required>
                        </div>
                        <div class="col-md-4">
                            <label for="commission" class="form-label">Commission (₦)</label> <span
                                class="text-danger">(*)</span>
                            <input id="commission" class="form-control" name="commission" type="text" required>
                        </div>
                        {{-- <div class="col-md-2">
                            <label for="longitute" class="form-label">Longitute </label>
                            <input id="longitute" class="form-control" name="longitute" type="text">
                        </div> --}}
                        <div class="col-md-4">
                            <label for="latitude" class="form-label"> Available Land Mark</label>
                            <input id="latitude" class="form-control" name="land_mark" type="text">
                        </div>



                    </div>
                    <br>
                    <input class="btn btn-primary" type="submit" value="Submit" style="width:30%;">
                </form>

            </div>
        </div>
    </div>
    <h4 class="card-title">Land Information</h4>
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Lands</a></li>
            <li class="breadcrumb-item active" aria-current="page">Information</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div><span><a class='' style="float:right;"href="{{ url('/admin/batch-land') }}"><i
                                    data-lucide="file-plus"></i>Batch Land Upload</a></span></div>
                    <h6 class="card-title">Available Lands</h6>

                    <div class="table-responsive">
                        <table id="dataTableExample" class="table">
                            <thead>
                                <tr>
                                    <th>S/N</th>
                                    <th>Name</th>
                                    <th>Cost (N)</th>
                                    <th>Dimension</th>
                                    <th>Commission</th>
                                    <th>LGA</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- {{ $i = 1 }} --}}


                                @foreach ($land as $item)
                                    <tr>
                                        <td> {{ $loop->iteration }} </td>
                                        <td> {{ $item->land_name }}</td>
                                        <td> {{ number_format($item->cost, 2) }}</td>
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
    <script src="{{ asset('assets/plugins/bootstrap-inputmask/jquery.inputmask.bundle.min.js') }}"></script>
    {{-- <script src="{{ asset('assets/plugins/easy-autocomplete/jquery.easy-autocomplete.min.js') }}"></script> --}}

    <script src="{{ asset('assets/plugins/datatables-net/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.js') }}"></script>
    @include('includes/datatable-scripts')
@endpush

@push('custom-scripts')
    <script src="{{ asset('assets/js/data-table.js') }}"></script>
    <script>
        $(document).ready(function() {




            $('#commission').inputmask({
                alias: "currency",
                prefix: '₦ '
            });

            $('#cost').inputmask({
                alias: "currency",
                prefix: '₦ '
            });



        });
    </script>
@endpush
