@extends('admin.layout.master2')

@section('content')
    <div class="page-content d-flex align-items-center justify-content-center">

        <div class="row w-100 mx-0 auth-page">
            <div class="col-md-8 col-xl-6 mx-auto">
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
                <div class="card">
                    <div class="row">
                        <div class="col-md-4 pe-md-0 sm-hide" style="">
                            <div class="auth-side-wrapper">
                                <center>
                                    <img src="{{ asset('assets/images/logo.png') }}"
                                        style="margin-top:40%; width: 50%;height: 150%;object-fit: contain;" alt="">
                                </center>

                            </div>
                            {{-- <div class="auth-side-wrapper">
                                <img src="{{ asset('assets/images/logo1.png') }}"
                                    style="width: 100%;height: 100%;object-fit: contain;" alt="">
                            </div> --}}

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
                        <div class="col-md-8 ps-md-0">
                            <div class="auth-form-wrapper px-4 py-5">
                                <a href="#" class="noble-ui-logo d-block mb-2">
                                    {{ env('APP_NAME') }}
                                    <span>Management
                                    </span>System</a>
                                {{-- <a href="#" class="noble-ui-logo d-block mb-2">Land <span>Management </span>System</a> --}}
                                <h5 class="text-muted fw-normal mb-4">Welcome back! Log in to your account.</h5>
                                <form class="forms-sample" action="{{ route('admin.login') }}" method="POST">
                                    <div class="mb-3">
                                        <label for="userEmail" class="form-label">Email address</label>
                                        <input type="email" class="form-control" name="userEmail" id="userEmail"
                                            placeholder="Email" value="{{ old('userEmail') }}">

                                    </div>
                                    <div class="mb-3">
                                        <label for="userPassword" class="form-label">Password</label>
                                        <input type="password" class="form-control" name="userPassword" id="userPassword"
                                            autocomplete="current-password" placeholder="Password">
                                    </div>
                                    <div class="form-check mb-3">
                                        <input type="checkbox" class="form-check-input" name="remember" id="authCheck">
                                        <label class="form-check-label" for="authCheck">
                                            Remember me
                                        </label>
                                    </div>
                                    <div>
                                        {{-- <a href="{{ url('/') }}" class="btn btn-primary me-2 mb-2 mb-md-0">Login</a> --}}
                                        {{-- <button type="button" class="btn btn-outline-primary btn-icon-text mb-2 mb-md-0">
                                            Login
                                        </button> --}}
                                        <input type="submit" class="btn btn-outline-primary btn-icon-text mb-2 mb-md-0"
                                            name = 'submit' value="Login">
                                        {{-- <button type="button" class="btn btn-outline-primary btn-icon-text mb-2 mb-md-0">
                                            <i class="btn-icon-prepend" data-lucide="twitter"></i>
                                            Login with twitter
                                        </button> --}}
                                    </div>
                                    {{-- <a href="{{ url('/auth/register') }}" class="d-block mt-3 text-muted">Not a user? Sign
                                        up</a> --}}
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
