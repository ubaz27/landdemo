@extends('admin.layout.master')
{{-- @extends('admin.asset.scripts') --}}

@push('plugin-styles')
    <link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
@endpush
@section('content')
    <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
        <div>
            <h4 class="mb-3 mb-md-0">Welcome to User Form</h4>
        </div>

    </div>

    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">User Details</h4>
                <hr>
                {{-- <p class="text-muted mb-3">Read the <a href="https://jqueryvalidation.org/" target="_blank"> Official jQuery
                        Validation Documentation </a>for a full list of instructions and other options.</p> --}}
                <form id="signupForm" action="{{ route('admin.saveUser') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-3 ">
                            <label for="name" class="form-label">Phone Numbers</label>
                            <input id="phone" class="form-control" value="{{ old('phone') }}" name="phone"
                                type="text" required>
                        </div>

                        <div class="col-md-3 ">
                            <label for="name" class="form-label">Full Name</label>
                            <input id="fullname" class="form-control" name="fullname" value="{{ old('fullname') }}"
                                type="text">
                        </div>

                        <div class="col-md-3">
                            <label for="confirm_password" class="form-label">Email</label>
                            <input id="nok_phone" class="form-control" name="email" type="text">
                        </div>
                        <div class="col-md-3">
                            <label for="confirm_password" class="form-label">Password</label>
                            <input id="address" class="form-control" name="password" type="password">
                        </div>



                    </div>
                    <br>
                    <input class="btn btn-primary" type="submit" value="Submit" style="width:10%;">
                </form>

            </div>
        </div>
    </div>

    <h4 class="card-title">User Information</h4>
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">User</a></li>
            <li class="breadcrumb-item active" aria-current="page">Information</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div></div>
                    <h6 class="card-title">Available Users</h6>

                    <div class="table-responsive">
                        <table id="dataTableExample" class="table">
                            <thead>
                                <tr>
                                    <th>S/N</th>
                                    <th>Name</th>
                                    <th>Phone</th>
                                    <th>Email</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>

                                @php
                                    $i = 1;
                                @endphp

                                @foreach ($users as $item)
                                    <tr>
                                        <td> {{ $i }} </td>
                                        <td> {{ $item->name }}</td>
                                        <td> {{ $item->phone }}</td>
                                        <td> {{ $item->email }}</td>
                                        <td> {{ $item->is_active == 1 ? 'Active' : 'Not Active' }}</td>

                                        <td>
                                            <form action="{{ route('admin.editUser') }}" method="POST">
                                                @csrf
                                                <input type="text" hidden readonly value="{{ $item->id }}"
                                                    class="form-control" name="user_id">
                                                <input type="submit" value = 'Edit' class = 'btn btn-primary'>
                                            </form>
                                        </td>

                                    </tr>
                                    {{-- {{  }} --}}
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
    <script src="{{ asset('assets/plugins/datatables-net/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.js') }}"></script>
@endpush

@push('custom-scripts')
    <script src="{{ asset('assets/js/data-table.js') }}"></script>
@endpush
