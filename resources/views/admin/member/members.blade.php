@extends('admin.layout.master')
{{-- @extends('admin.asset.scripts') --}}

@push('plugin-styles')
    <link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
@endpush
@section('content')
    <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
        <div>
            <h4 class="mb-3 mb-md-0">Welcome to Member Form</h4>
        </div>

    </div>

    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Member Details</h4>
                <hr>
                {{-- <p class="text-muted mb-3">Read the <a href="https://jqueryvalidation.org/" target="_blank"> Official jQuery
                        Validation Documentation </a>for a full list of instructions and other options.</p> --}}
                <form id="signupForm">
                    <div class="row">
                        <div class="col-md-12 ">

                            <div class="custom-file">

                                <label class="custom-file-label" for="MembersFile">Choose file</label>
                                <input class="form-control" type="file" id="formFile">
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

                        <a class='' style="float:right;"href="{{ url('/admin/member') }}"><i
                                data-lucide="file-plus"></i>Single
                            Upload</a>


                    </div>
                    <br>
                    <h6 class="card-title">Member's Record</h6>

                    <div class="table-responsive">
                        <table id="dataTableExample" class="table">
                            <thead>
                                <tr>
                                    <th>S/N</th>
                                    <th>Member</th>
                                    <th>Name</th>
                                    <th>Land No</th>
                                    <th>Dimension</th>
                                    <th>Cost</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>08062253405</td>
                                    <td>Musa Garba</td>
                                    <td>61</td>
                                    <td>40x60</td>
                                    <td>$320,800</td>
                                    <td><a href="" class=""><i data-lucide="edit"></i> </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>08099887765</td>
                                    <td>Jabir Musa</td>
                                    <td>01</td>
                                    <td>63x90</td>

                                    <td>$170,750</td>
                                    <td><a href="" class=""><i data-lucide="edit"></i> </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>3</td>
                                    <td>08099887765</td>
                                    <td>Jabir Musa</td>
                                    <td>01</td>
                                    <td>63x90</td>

                                    <td>$170,750</td>
                                    <td><a href="" class=""><i data-lucide="edit"></i> </a>
                                    </td>
                                </tr>

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
