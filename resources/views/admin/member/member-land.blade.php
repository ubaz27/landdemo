@extends('admin.layout.master')
{{-- @extends('admin.asset.scripts') --}}
@push('plugin-styles')
    <link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
@endpush


@section('content')
    <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
        <div>
            <h4 class="mb-3 mb-md-0">Welcome to Land Distribution Form</h4>
        </div>

    </div>

    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Member Land Acquitision</h4>
                <hr>

                <form id="signupForm" action="{{ route('admin.saveLandMember') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-4 ">
                            <label for="name" class="form-label">Phone Number and Full Name</label> <span
                                class = "asterisk">*</span>
                            {{-- <input id="name" class="form-control" name="name" type="text">
                             --}}
                            {{-- {{ dd($members) }} --}}
                            <input type="text" class="form-control" id="member_info" name="member_info"
                                value="{{ old('member_info') }}" placeholder=" --Search Member-- " required required>
                            <input type="hidden" readonly class="form-control" value="{{ old('file_no') }}" id="file_no"
                                name="file_no">

                            {{-- @error('member_info')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror --}}
                        </div>



                        <div class="col-md-4">
                            <label for="ageSelect" class="form-label">Land Name</label> <span class="text-danger">(required)
                            </span>
                            <select class="js-example-basic-single form-select" name="land_id" id="land_id">
                                <option selected disabled>Select Plot No</option>
                                @foreach ($land_names as $item)
                                    <option value="{{ $item->id }}">{{ $item->land_name }}
                                        (N :{{ number_format($item->cost, 2) }}-{{ $item->lga }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="ageSelect" class="form-label">Plot Number</label> <span
                                class="text-danger">(required)</span>
                            <select class="js-example-basic-single form-select" name="plot_id" id="plot_id">
                                <option selected disabled>Select Plot No</option>
                                @foreach ($plots as $item)
                                    <option value="{{ $item->id }}">
                                        {{ $item->land_name . ' Plot: ' . $item->plot_no }}
                                        (N :{{ number_format($item->cost, 2) }}-{{ $item->dimension }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="password" class="form-label">Agent Name</label> <span class = "asterisk">*</span>
                            <select class="js-example-basic-single form-select" name="agent_id" id="agent_id">
                                <option selected disabled>Select Agent</option>
                                @foreach ($agents as $item)
                                    <option value="{{ $item->id }}"> {{ $item->name }} ({{ $item->phone }} -
                                        {{ $item->lga }}: {{ $item->agent_company }})</option>
                                @endforeach
                            </select>
                        </div>


                    </div>
                    <br>
                    <input class="btn btn-primary" type="submit" value="Submit" style="width:30%;">
                </form>

            </div>
        </div>
    </div>
@endsection
@push('plugin-scripts')
    {{-- <script src="{{ asset('assets/plugins/datatables-net/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.js') }}"></script> --}}

    <link href="{{ asset('assets/plugins/easy-autocomplete/easy-autocomplete.themes.min.css') }}" rel="stylesheet"
        type="text/css">
    <link href="{{ asset('assets/plugins/easy-autocomplete/easy-autocomplete.min.css') }}" rel="stylesheet"
        type="text/css">
    <script src="{{ asset('assets/plugins/bootstrap-inputmask/jquery.inputmask.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/easy-autocomplete/jquery.easy-autocomplete.min.js') }}"></script>
    @include('includes/datatable-scripts')
@endpush
@push('custom-scripts')
    <script>
        $(document).ready(function() {
            // alert('ddd');
            $("#land_id").change(function() {
                $("#plot_id").empty();
                var land_id = $("#land_id").val();
                $.ajax({
                    method: 'POST',
                    url: "{{ route('admin.fetchPlot') }}",
                    data: {
                        'land_id': land_id,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        $('#plot_id').append(
                            '<option value="">-- Select Plots --</option>');
                        response.plots.forEach(plots =>
                            $('#plot_id').append("<option value='" +
                                plots.id + "'" + ">" +
                                "Plot " + plots.plot_no + "</option>")
                        )
                    },
                    error: function(jqXHR, textStatus,
                        errorThrown) {
                        console.log(JSON.stringify(jqXHR));
                        console.log("AJAX error: " + textStatus + ' : ' +
                            errorThrown);
                    }
                });
            });

            var members = @json($members);
            console.log(members);
            var options = {
                data: members,
                getValue: "name_phone",
                list: {
                    maxNumberOfElements: 14,
                    match: {
                        enabled: true
                    },
                    sort: {
                        enabled: true
                    },
                    onChooseEvent: function() {
                        var value = $("#member_info").getSelectedItemData().id;
                        $('#file_no').val(value);
                    }
                },
                adjustWidth: false
            };
            $("#member_info").easyAutocomplete(options);
        });
    </script>
@endpush
@section('scripts')
@endsection

@section('scripts_after')
@endsection
