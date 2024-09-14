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
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Land Details</h4>
                <hr>

                {{-- <p class="text-muted mb-3">Read the <a href="https://jqueryvalidation.org/" target="_blank"> Official jQuery
                    Validation Documentation </a>for a full list of instructions and other options.</p> --}}
                <form id="my-form" action="{{ route('admin.saveLandDistribution') }}" method = "POST">
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
                    </div>
                    <br>
                    <div class="row">
                        <table class="table table-bordered table-striped col-md-12" id="myTable">
                            <thead>
                                <tr>
                                    <th>S/N</th>
                                    <th>Land No <span class = "asterisk">*</span></th>
                                    <th>Dimension <span class = "asterisk">*</span></th>
                                    <th>Cost <span class = "asterisk">*</span></th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr onclick="myFunction(this)">
                                    <td>1</td>
                                    <td><input type="text" class="form-control" name="landno[]" placeholder="Land No">
                                    </td>
                                    <td><input type="text" class="form-control" name="dimension[]"
                                            placeholder="Dimension"></td>
                                    <td><input type="text" class="form-control" id="cost" name = "cost[]"
                                            placeholder="Cost"></td>
                                    <td> <button type="button" class="add-fields btn btn-success btn-sm"
                                            onclick="myCreateFunction()">
                                            Add
                                        </button></td>
                                </tr>
                            </tbody>

                        </table>

                    </div>
                    <br>
                    <input class="btn btn-primary" type="submit" value="Submit" style="width:30%;">
                    <div id= "user">

                    </div>
                    <div class="fields"></div>
                </form>

            </div>
        </div>
    </div>

    <h4 class="card-title">Land Information</h4>
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Plots</a></li>
            <li class="breadcrumb-item active" aria-current="page">Information</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div><span><a class='' style="float:right;"href="{{ url('/admin/batch-plots') }}"><i
                                    data-lucide="file-plus"></i>Batch Plot Upload</a></span></div>
                    <h6 class="card-title">Plots</h6>

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
                                <?php $i = 1; ?>


                                @foreach ($plots as $item)
                                    <tr>
                                        <td> {{ $i }} </td>
                                        <td> {{ $item->land_name }}</td>
                                        <td> {{ $item->plot_no }}</td>
                                        <td> N {{ number_format($item->cost), 2 }}</td>
                                        <td> {{ $item->dimension }}</td>


                                        <td><a href="" class="btn btn-primary btn-sm "><i data-lucide="edit"></i></a>
                                        </td>

                                    </tr>
                                    @php
                                        $i++;
                                    @endphp
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
    <script src="{{ asset('assets/plugins/datatables-net/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('assets/plugins/bootstrap-inputmask/jquery.inputmask.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.js') }}"></script>
@endpush

@push('custom-scripts')
    <script src="{{ asset('assets/js/data-table.js') }}"></script>
@endpush


<script>
    function myCreateFunction() {


        var table = document.getElementById("myTable");
        var no_rows = document.getElementById("myTable").rows.length;

        var row = table.insertRow(no_rows);
        var cell1 = row.insertCell(0);
        var cell2 = row.insertCell(1);
        var cell3 = row.insertCell(2);
        var cell4 = row.insertCell(3);
        var cell5 = row.insertCell(4);
        cell1.innerHTML +=
            no_rows;

        cell2.innerHTML +=
            '<tr onclick="myFunction(this)"><input type="text" name="landno[]" class="form-control" placeholder="Land No " value ="' +
            no_rows + '">';
        cell3.innerHTML +=
            '<input type="text" name="dimension[]" class="form-control" placeholder="Dimension">';
        cell4.innerHTML +=
            '<input type="text" name="cost[]" id="cost" class="form-control" placeholder="Cost">';
        cell5.innerHTML +=
            '<button class="btn btn-danger btn-sm" id="remove" type="button" onclick="myDeleteFunction(' + no_rows +
            ')">Delete</button></tr>';


    }



    function myDeleteFunction(x) {
        document.getElementById("myTable").deleteRow(x);
    }
</script>
