@extends('admin.layout.master')
{{-- @extends('admin.asset.scripts') --}}

@push('plugin-styles')
    <link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
@endpush
@section('content')
    <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
        <div>
            <h4 class="mb-3 mb-md-0">Welcome to Edit User Form</h4>
        </div>

    </div>

    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Edit User Details</h4>
                <hr>

                {{-- <p class="text-muted mb-3">Read the <a href="https://jqueryvalidation.org/" target="_blank"> Official jQuery
                        Validation Documentation </a>for a full list of instructions and other options.</p> --}}
                {{-- <form action="{{ route('admin.editUser') }}" method="POST"> --}}

                <form action="{{ route('admin.saveEditUser') }}" method="POST">
                    @csrf
                    {{-- @foreach ($users as $item) --}}
                    {{-- {{ $user_id = $item->id }} --}}

                    <div class="row">
                        <div class="col-md-3 ">
                            <input type="text" class='form-control' name= "user_id" hidden readonly
                                value = "{{ $users->id }}">
                            <label for="name" class="form-label">Phone Numbers</label>
                            <input id="phone" class="form-control" readonly value="{{ $users->phone }}" name="phone"
                                type="text" required>
                        </div>

                        <div class="col-md-3 ">
                            <label for="name" class="form-label">Full Name</label>
                            <input id="fullname" class="form-control" name="fullname" value="{{ $users->name }}"
                                type="text">
                        </div>

                        <div class="col-md-3">
                            <label for="confirm_password" class="form-label">Email</label>
                            <input id="nok_phone" class="form-control" value="{{ $users->email }}" name="email"
                                type="text">
                        </div>
                        <div class="col-md-3">
                            <label for="confirm_password" class="form-label">Password</label>
                            <input id="address" class="form-control" name="password" type="password">
                        </div>

                        <div class="col-md-3">
                            <label for="status" class="form-label">Active</label>

                            <select class="form-control" name="is_active">
                                <option value="0" {{ $users->is_active == '0' ? 'selected' : '' }}>No</option>
                                <option value="1" {{ $users->is_active == '1' ? 'selected' : '' }}>Yes</option>
                            </select>
                        </div>
                        {{-- @endforeach --}}

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
