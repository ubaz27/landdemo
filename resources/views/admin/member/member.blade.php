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
                <form id="signupForm" action="{{ route('admin.saveMemberDetail') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-3 ">
                            <label for="name" class="form-label">Phone Numbers</label> <span class = "asterisk">*</span>
                            <input id="phone" class="form-control" value="{{ old('phone') }}" name="phone"
                                type="text" required>
                        </div>

                        <div class="col-md-3 ">
                            <label for="name" class="form-label">Full Name</label> <span class = "asterisk">*</span>
                            <input id="fullname" class="form-control" name="fullname" value="{{ old('fullname') }}"
                                type="text" required>
                        </div>

                        <div class="col-md-3">
                            <label for="ageSelect" class="form-label">LGA</label>
                            <select class="js-example-basic-single form-select" name="lga" id="lga" required>
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
                            <label for="confirm_password" class="form-label">Password</label> <span
                                class = "asterisk">*</span>
                            <input id="address" value="1000" class="form-control" name="password" type="password">
                            <span class="asterisk" style='font-size:16px;'>Default Password is 1000</span>
                        </div>

                        <div class="col-md-6">
                            <label for="confirm_password" class="form-label">Address</label>
                            <textarea name="address" id="address" class="form-control" cols="30" rows="2"></textarea>

                        </div>

                    </div>
                    <br>
                    <input class="btn btn-primary" type="submit" value="Submit" style="width:30%;">
                </form>

            </div>
        </div>
    </div>

    <h4 class="card-title">Members Information</h4>
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Member</a></li>
            <li class="breadcrumb-item active" aria-current="page">Information</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div><span><a class='' style="float:right;"href="{{ url('/admin/batch-member') }}"><i
                                    data-lucide="file-plus"></i>Batch Member Upload
                            </a></span></div>
                    <h6 class="card-title">Available Mmebers</h6>

                    <div class="table-responsive">
                        <table id="dataTableExample" class="table">
                            <thead>
                                <tr>
                                    <th>S/N</th>
                                    <th>Name</th>
                                    <th>Phone</th>
                                    <th>lga</th>
                                    <th>Next of Kin</th>
                                    <th>Next of Kin Phone</th>
                                    <th>Edit</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- {{ $i = 1 }} --}}


                                @foreach ($members as $item)
                                    <tr>
                                        <td> {{ $loop->iteration }} </td>
                                        <td> {{ $item->name }}</td>
                                        <td> {{ $item->phone }}</td>
                                        <td> {{ $item->nok }}</td>
                                        <td> {{ $item->nok_phone }}</td>
                                        <td> {{ $item->picture }}</td>
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
