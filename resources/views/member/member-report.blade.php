@extends('member.layout.master')

@section('content')
    <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
        <div>
            <h4 class="mb-12 mb-md-0">Welcome to Payment Report Summary</h4>
        </div>

        <div class="col-lg-12 col-sm-12 col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Plot Payment Report</h4>
                    <hr>
                    {{-- <p class="text-muted mb-3">Read the <a href="https://jqueryvalidation.org/" target="_blank"> Official jQuery
                            Validation Documentation </a>for a full list of instructions and other options.</p> --}}
                    <form id="signupForm" action="{{ route('member.MemberStatementExcel') }}" Method="POST" target="_blank">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <label for="ageSelect" class="form-label">Land Name (Plot)</label> <span
                                    class="text-danger">(*)</span>
                                <select class="js-example-basic-single form-select" name="member_id" id="member_id">
                                    <option selected disabled>Select Plot Record</option>
                                    @foreach ($plots as $item)
                                        <option value="{{ $item->id }}">
                                            {{ $item->land_name . ' ' . $item->plots_no . ' ' . $item->dimension . ' ' . $item->cost }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>



                        </div>
                        <br>
                        <div class="row">
                            <div class="col-sm-12">
                                <button type="submit" name="type" value="excel"
                                    class="btn btn-outline-success float-right"><i class="fas fa-print"></i>
                                    Generate Excel</button>


                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>

    </div>
@endsection
