@extends('member.layout.master')
{{-- @extends('admin.asset.scripts') --}}


@section('content')
    <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
        <div>
            <h4 class="mb-3 mb-md-0">Welcome to Profile Form</h4>
        </div>

    </div>

    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Profile Details</h4>
                <hr>
                @if (Session::has('error'))
                    <div class="alert alert-danger" role="alert">
                        <ul>
                            {{ Session('error') }}
                        </ul>
                    </div>
                @endif
                @if (Session::has('message'))
                    <div class="alert alert-primary" role="alert">
                        <ul>
                            {{ Session('message') }}
                        </ul>
                    </div>
                @endif
                {{-- <p class="text-muted mb-3">Read the <a href="https://jqueryvalidation.org/" target="_blank"> Official jQuery
                        Validation Documentation </a>for a full list of instructions and other options.</p> --}}
                <form id="signupForm" action="{{ route('member.saveProfile') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-3 ">
                            <label for="name" class="form-label">Phone Numbers</label> <span
                                class="text-danger">(*)</span>
                            <input id="phone" class="form-control" value="{{ $member->phone }}" name="phone"
                                type="text" required readonly>
                        </div>

                        <div class="col-md-3 ">
                            <label for="name" class="form-label">Full Name</label> <span class = "text-danger">*</span>
                            <input id="fullname" class="form-control" name="fullname" value="{{ $member->name }}"
                                type="text" readonly>
                        </div>

                        <div class="col-md-3">
                            <label for="ageSelect" class="form-label">LGA</label> <span class = "text-danger">*</span>
                            <select class="js-example-basic-single form-select" name="lga" id="lga">

                                @if ($member->lga !== '')
                                    <option selected value="{{ $member->lga }}">{{ $member->lga }}</option>
                                @else
                                    <option selected disabled>Select Location(LGA)</option>
                                @endif

                                @foreach ($lgas as $lga)
                                    <option>{{ $lga->lga }}</option>
                                @endforeach
                            </select>
                        </div>


                        <div class="col-md-3">
                            <label for="password" class="form-label">Next of Kin</label> <span
                                class = "text-danger">*</span>
                            <input id="nok" class="form-control" value="{{ $member->nok }}" name="nok"
                                type="text">
                        </div>
                        <div class="col-md-3">
                            <label for="confirm_password" class="form-label">Next of Kin Phone</label> <span
                                class = "asterisk">*</span>
                            <input id="nok_phone" class="form-control" value="{{ $member->nok_phone }}" name="nok_phone"
                                type="text">
                        </div>
                        <div class="col-md-3">
                            <label for="email" class="form-label">Email</label> <span class = "text-danger">*</span>
                            <input id="email" class="form-control" name="email" type="email"
                                value="{{ $member->email }}" required placeholder="Valid Email">
                        </div>

                        <div class="col-md-6">
                            <label for="confirm_password" class="form-label">Address</label> <span
                                class = "text-danger">*</span>
                            <textarea name="address" id="address" class="form-control" cols="30" rows="2">{{ $member->address }}</textarea>

                        </div>

                    </div>
                    <br>
                    <input class="btn btn-primary" type="submit" value="Update" style="width:20%;">
                </form>

            </div>
        </div>
    </div>
@endsection
