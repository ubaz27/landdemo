@extends('admin.layout.master')
{{-- @extends('admin.asset.scripts') --}}
@push('plugin-styles')
    <link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
@endpush

@section('content')
    <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
        <div>
            <h4 class="mb-3 mb-md-0">Welcome to Agent Form</h4>
        </div>

    </div>

    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Agent Details</h4>
                <hr>
                @if ($message = Session::get('mssg'))
                    <div class="alert alert-{{ $message['type'] }} alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <h5><i class="icon fas fa-{{ $message['icon'] }}"></i> Alert!</h5>
                        {{ $message['message'] }}
                    </div>
                @endif

                @if (Session::has('error'))
                    <div class="alert alert-danger" role="alert">
                        <ul>
                            {{ Session('error') }}
                            {{-- @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach --}}
                        </ul>
                    </div>
                @endif

                <form id="signupForm" action="{{ route('admin.saveAgentData') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-3 ">
                            <label for="name" class="form-label">Phone Number</label> <span class = "asterisk">*</span>
                            <input id="phone" class="form-control" name="phone" type="text">
                        </div>

                        <div class="col-md-3 ">
                            <label for="name" class="form-label">Full Name</label> <span class = "asterisk">*</span>
                            <input id="fullname" class="form-control" name="fullname" type="text">
                        </div>

                        <div class="col-md-3">
                            <label for="ageSelect" class="form-label">LGA</label>
                            <select class="js-example-basic-single form-select" name="lga" id="lga">
                                <option selected disabled>Select Location(LGA)</option>
                                @foreach ($lgas as $lga)
                                    <option>{{ $lga->lga }}</option>
                                @endforeach
                            </select>
                        </div>


                        <div class="col-md-3">
                            <label for="password" class="form-label">Next of Kin</label>
                            <input id="nok" class="form-control" name="nok" type="text">
                        </div>
                        <div class="col-md-3">
                            <label for="confirm_password" class="form-label">Next of Kin Phone</label>
                            <input id="nok_phone" class="form-control" name="nok_phone" type="text">
                        </div>
                        <div class="col-md-3">
                            <label for="confirm_password" class="form-label">Company</label>
                            <input id="company" class="form-control" name="company" type="text">
                        </div>
                        <div class="col-md-6">
                            <label for="confirm_password" class="form-label">Home/Office Address</label>
                            <input id="address" class="form-control" name="address" type="text">
                        </div>


                    </div>
                    <br>
                    <input class="btn btn-primary" type="submit" value="Submit" style="width:30%;">
                </form>

            </div>
        </div>
    </div>
    {{-- list of agents --}}
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Agents</a></li>
            <li class="breadcrumb-item active" aria-current="page">Record</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">

            <div class="card">
                <div class="card-body">

                    <br>
                    <h6 class="card-title">Agent's Record</h6>

                    <div class="table-responsive">
                        <table id="dataTableExample" class="table">
                            <thead>
                                <tr>
                                    <th style="width:10px;">S/N</th>
                                    <th style="width:30px;">Agent Code</th>
                                    <th style="width:200px;">Name</th>
                                    <th style="width:50px;">LGA</th>
                                    <th style="width:20px;">Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($agents as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->phone }}</td>
                                        <td>{{ $item->name }}</td>
                                        <td>{{ $item->lga }}</td>

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
