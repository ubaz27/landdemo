@extends('admin.layout.master')
@extends('admin.asset.scripts')
{{-- @push('plugin-styles')
    <link href="{{ asset('assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css') }}" rel="stylesheet" />
<link href="{{ asset('assets/plugins/select2/select2.min.css') }}" rel="stylesheet" />
<link href="{{ asset('assets/plugins/jquery-tags-input/jquery.tagsinput.min.css') }}" rel="stylesheet" />
<link href="{{ asset('assets/plugins/dropzone/dropzone.min.css') }}" rel="stylesheet" />
<link href="{{ asset('assets/plugins/dropify/css/dropify.min.css') }}" rel="stylesheet" />
<link href="{{ asset('assets/plugins/bootstrap-colorpicker/bootstrap-colorpicker.min.css') }}" rel="stylesheet" />
<link href="{{ asset('assets/plugins/bootstrap-datepicker/bootstrap-datepicker.min.css') }}" rel="stylesheet" />
<link href="{{ asset('assets/plugins/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet" />
<link href="{{ asset('assets/plugins/tempusdominus-bootstrap-4/tempusdominus-bootstrap-4.min.css') }}" rel="stylesheet" />
@endpush --}}
@section('content')
    <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
        <div>
            <h4 class="mb-3 mb-md-0">Welcome to Payment Report Summary</h4>
        </div>

        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Land Payment</h4>
                    <hr>
                    {{-- <p class="text-muted mb-3">Read the <a href="https://jqueryvalidation.org/" target="_blank"> Official jQuery
                            Validation Documentation </a>for a full list of instructions and other options.</p> --}}
                    <form id="signupForm" action="{{ route('admin.MemberStatementExcel') }}" Method="POST" target="_blank">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <label for="ageSelect" class="form-label">Member Name (Record)</label> <span
                                    class="text-danger">(required)</span>
                                <select class="js-example-basic-single form-select" name="member_id" id="member_id">
                                    <option selected disabled>Select Member Record</option>
                                    @foreach ($members as $item)
                                        <option value="{{ $item->id }}">{{ $item->name . ' ' . $item->phone }}</option>
                                    @endforeach
                                </select>
                            </div>
                            {{-- <div class="col-md-3">
                                <label for="confirm_password" class="form-label">From</label> <span
                                    class="text-danger">(required)</span>
                                <input id="sdate" class="form-control" name="sdate" type="date">
                            </div>
                            <div class="col-md-3">
                                <label for="confirm_password" class="form-label">To</label> <span
                                    class="text-danger">(required)</span>
                                <input id="edate" class="form-control" name="edate" type="date">
                            </div> --}}



                        </div>
                        <br>
                        <div class="row">
                            <div class="col-sm-12">
                                <button type="submit" name="type" value="excel"
                                    class="btn btn-outline-success float-right"><i class="fas fa-print"></i>
                                    Generate Excel</button>

                                {{-- <button type="submit" name="type" value="pdf"
                                    class="btn btn-outline-danger float-right mr-2"><i class="fas fa-print"></i>
                                    Generate PDF</button> --}}
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>

    </div>
@endsection

{{-- @push('plugin-scripts')
    <script src="{{ asset('assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>
<script src="{{ asset('assets/plugins/apexcharts/apexcharts.min.js') }}"></script>
<script src="{{ asset('assets/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
<script src="{{ asset('assets/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js') }}"></script>
<script src="{{ asset('assets/plugins/inputmask/jquery.inputmask.bundle.min.js') }}"></script>
<script src="{{ asset('assets/plugins/select2/select2.min.js') }}"></script>
<script src="{{ asset('assets/plugins/typeahead-js/typeahead.bundle.min.js') }}"></script>
<script src="{{ asset('assets/plugins/jquery-tags-input/jquery.tagsinput.min.js') }}"></script>
<script src="{{ asset('assets/plugins/dropzone/dropzone.min.js') }}"></script>
<script src="{{ asset('assets/plugins/dropify/js/dropify.min.js') }}"></script>
<script src="{{ asset('assets/plugins/bootstrap-colorpicker/bootstrap-colorpicker.min.js') }}"></script>
<script src="{{ asset('assets/plugins/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
<script src="{{ asset('assets/plugins/moment/moment.min.js') }}"></script>
<script src="{{ asset('assets/plugins/tempusdominus-bootstrap-4/tempusdominus-bootstrap-4.js') }}"></script>
@endpush

@push('custom-scripts')
<script src="{{ asset('assets/js/dashboard.js') }}"></script>
<script src="{{ asset('assets/js/datepicker.js') }}"></script>
<script src="{{ asset('assets/js/form-validation.js') }}"></script>
<script src="{{ asset('assets/js/bootstrap-maxlength.js') }}"></script>
<script src="{{ asset('assets/js/inputmask.js') }}"></script>
<script src="{{ asset('assets/js/select2.js') }}"></script>
<script src="{{ asset('assets/js/typeahead.js') }}"></script>
<script src="{{ asset('assets/js/tags-input.js') }}"></script>
<script src="{{ asset('assets/js/dropzone.js') }}"></script>
<script src="{{ asset('assets/js/dropify.js') }}"></script>
<script src="{{ asset('assets/js/bootstrap-colorpicker.js') }}"></script>
<script src="{{ asset('assets/js/datepicker.js') }}"></script>
<script src="{{ asset('assets/js/timepicker.js') }}"></script>
@endpush --}}
