@extends('admin.layout.master')
{{-- @extends('admin.asset.scripts') --}}

@push('plugin-styles')
    <link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
@endpush
@section('content')
    <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
        <div>
            <h4 class="mb-3 mb-md-0">Welcome to Profile Page</h4>
        </div>

    </div>

    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Profile Details</h4>
                <hr>
                {{-- <p class="text-muted mb-3">Read the <a href="https://jqueryvalidation.org/" target="_blank"> Official jQuery
                        Validation Documentation </a>for a full list of instructions and other options.</p> --}}
                <form id="signupForm" action="{{ route('admin.saveProfile') }}" method="POST">
                    <div class="row">
                        <div class="col-md-4 ">
                            <label for="name" class="form-label">Full Name</label>
                            <input id="name" class="form-control" value ="{{ $fullname }}" name="name"
                                type="text" readonly>
                        </div>

                        <div class="col-md-4">
                            <label for="password" class="form-label">Email</label>
                            <input id="password" class="form-control" value ="{{ $email }}" name="password"
                                type="email" readonly>
                        </div>
                        <div class="col-md-4 ">
                            <label for="name" class="form-label">Phone Number</label>
                            <input id="name" class="form-control" name="phonenumber"
                                placeholder="080xxxxxxxx.  No +234" value ="{{ $phone }}" type="text" required>
                        </div>





                        {{-- <div class="col-md-3">
                            <label for="confirm_password" class="form-label">Home/Office Address</label>
                            <input id="confirm_password" class="form-control" name="confirm_password" type="text">
                        </div> --}}
                        {{-- <div class="col-md-9">
                            <label for="confirm_password" class="form-label"></label>
                            <input id="confirm_password" class="form-control" name="confirm_password" type="text">
                        </div> --}}


                    </div>
                    <br>
                    <input class="btn btn-primary" type="submit" value="Submit" style="width:10%;">
                </form>

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
