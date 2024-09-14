@extends('admin.layout.master2')

@section('content')
    <div class="page-content d-flex align-items-center justify-content-center">

        <div class="row w-100 mx-0 auth-page">
            <div class="col-md-8 col-xl-6 mx-auto">


            </div>


            {{-- @if ($errors->any())
                            <div class="alert alert-danger pb-0 pl-0" role="alert">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif --}}

            <div class="row">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-md-6">

                            @if (session('message'))
                                <h5 class="alert alert-success mb-2">{{ session('message') }}</h5>
                            @endif

                            @if ($errors->any())
                                <ul class="alert alert-danger">
                                    @foreach ($errors->all() as $error)
                                        <li class="text-danger">{{ $error }}</li>
                                    @endforeach
                                </ul>
                            @endif

                            <div class="card shadow">
                                <div class="card-header bg-primary">
                                    <h4 class="mb-0 text-white">Reset Password</h4>
                                </div>
                                <div class="card-body">
                                    <form action="{{ route('admin.resetPassword') }}" method="POST">
                                        @csrf
                                        <div class="mb-3">
                                            <label>Current Password</label>
                                            <input type="password" name="current_password" class="form-control" />
                                        </div>
                                        <div class="mb-3">
                                            <label>New Password</label>
                                            <input type="password" name="password" class="form-control" />
                                        </div>
                                        <div class="mb-3">
                                            <label>Confirm Password</label>
                                            <input type="password" name="password_confirmation" class="form-control" />
                                        </div>
                                        <div class="mb-3 text-end">
                                            <hr>
                                            <button type="submit" class="btn btn-primary">Update Password</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
