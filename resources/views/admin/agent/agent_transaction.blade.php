@extends('admin.layout.master')
{{-- @extends('admin.asset.scripts') --}}
@push('plugin-styles')
    <link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
@endpush

@section('content')
    <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
        <div>
            <h4 class="mb-3 mb-md-0">Welcome to Transaction Page</h4>
        </div>

    </div>

    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Agent Payment Page</h4>
                <h5><a class='btn btn-info' href="{{ route('admin.consultant') }}">Pay Consultant</a></h5>
                <hr>
                @if ($message = Session::get('mssg'))
                    <div class="alert alert-{{ $message['type'] }} alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <h5><i class="icon fas fa-{{ $message['icon'] }}"></i> Alert!</h5>
                        {{ $message['message'] }}
                    </div>
                @endif

                @if (Session::has('error'))
                    <div class="alert alert-danger" role="alert">
                        <ul>
                            {{ Session('error') }}
                        </ul>
                    </div>
                @endif
                <form id="signupForm" action="{{ route('admin.saveAgentTransactions') }}" method="POST">
                    @csrf
                    <div class="row">

                        <div class="col-md-3 ">
                            <label for="name" class="form-label">Phone Number and Full Name</label> (<span
                                class="text-danger">required</span>)
                            {{-- <input id="name" class="form-control" name="name" type="text">
                             --}}
                            {{-- {{ dd($members) }} --}}
                            <input type="text" class="form-control" id="agent_info" name="agent_info"
                                value="{{ old('agent_info') }}" placeholder=" --Search Member-- " required required>
                            <input type="hidden" class="form-control" value="{{ old('agent_id') }}" id="agent_id"
                                name="agent_id">

                            {{-- @error('member_info')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror --}}
                        </div>

                        <div class="col-md-6">
                            <label for="ageSelect" class="form-label">Plot Distribution Data</label> <span
                                class="text-danger">(required)</span>
                            <select class="js-example-basic-single form-select" name="distribution_id" id="distribution_id">
                                <option selected disabled>Select Plot No</option>

                            </select>
                        </div>

                        <div class="col-md-3">
                            <label for="ageSelect" class="form-label">Amount (Naira)</label> <span
                                class="text-danger">(required)</span>
                            <input type="text" class="form-control" id="amount" name="amount"
                                value="{{ old('amount') }}" placeholder="Provide Amount" required>
                        </div>

                        <div class="col-md-6">
                            <label for="ageSelect" class="form-label">Description</label> <span class="text-danger"></span>
                            <textarea name="description" class="form-control" value="{{ old('description') }}" placeholder="Provide Description"
                                id="description"></textarea>
                            {{-- <input type="text" class="form-control" id="description" name="description" required> --}}
                        </div>
                        <div class="col-md-6">
                            <label for="ageSelect" class="form-label">Description</label> <span class="text-danger">*</span>
                            <input type="date" class="form-control" id="payment_date" name="payment_date"
                                value="{{ old('payment_date') }}" placeholder="Provide Payment Date" required>
                            {{-- <input type="text" class="form-control" id="description" name="description" required> --}}
                        </div>


                    </div>
                    <br>
                    <input class="btn btn-primary" type="submit" value="Submit" style="width:30%;">
                </form>

            </div>
        </div>
    </div>



    <h4 class="card-title">Agents' Transaction</h4>
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Transaction</a></li>
            <li class="breadcrumb-item active" aria-current="page">Information</li>
        </ol>
    </nav>
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-header">
                    <h5>Available Transactions</h5>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <div id="buttons" class="d-flex justify-content-center"></div>
                        <table id="member_payments_datatable" class="cell-border stripe" style="width: 100%">
                            <thead>
                                <tr>
                                    <th>S/N</th>
                                    <th>Agent's Name</th>
                                    <th>Memeber's Phone</th>
                                    <th>Land Name</th>
                                    <th>Plot No</th>
                                    <th>Cost</th>
                                    <th>Dimension</th>
                                    <th>Transaction</th>
                                    <th>Edit</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>


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

            ///get list of land for a member

            // $("#distribution_id").change(function() {
            //     var distribution_id = $("#distribution_id").val();
            //     // alert(distribution_id);
            // });




            $('#amount').inputmask({
                alias: "currency",
                prefix: '₦ '
            });



            $("#agent_info").change(function() {
                $("#distribution_id").empty();
                var file_no = $("#agent_info").val();
                var agent_id = $("#agent_id").val();
                // alert(agent_id);
                // var phonearray = file_no.split("-");
                // var phone = phonearray[1];

                $.ajax({
                    method: 'POST',
                    url: "{{ route('admin.fetchPlotByAgent') }}",
                    data: {
                        'file_no': agent_id,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        $('#distribution_id').append(
                            '<option value="">-- Select Plots --</option>');
                        response.plots.forEach(plots =>
                            $('#distribution_id').append("<option value='" +
                                plots.id + "'" + ">" +
                                plots.plot_no + "</option>")
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



            var agents = @json($agents);
            console.log(agents);
            var options = {
                data: agents,
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
                        var value = $("#agent_info").getSelectedItemData().id;
                        $('#agent_id').val(value);
                    }
                },
                adjustWidth: false
            };
            $("#agent_info").easyAutocomplete(options);

            loadAgentPayments();

            function loadAgentPayments() {
                var table = $('#member_payments_datatable').DataTable({
                    processing: true,
                    serverSide: true,
                    "bDestroy": true,
                    "autoWidth": false,
                    aLengthMenu: [
                        [10, 25, 50, 100, 200, 500, 1000, -1],
                        [10, 25, 50, 100, 200, 500, 1000, "All"]
                    ],
                    iDisplayLength: -1,
                    ajax: {
                        type: 'POST',
                        url: "{{ route('admin.getAgentPayments') }}",
                        data: {
                            // 'filter': filter,
                        },
                    },
                    columnDefs: [{
                            className: 'text-center',
                            targets: [2, 4, 4, 5, 6, 7, 8]
                        },
                        {
                            className: 'dt-body-right',
                            targets: [0]
                        },
                        {
                            "width": "50px",
                            "targets": 8
                        },
                        {
                            "width": "20px",
                            "targets": 0
                        },
                    ],
                    columns: [{
                            data: 'DT_RowIndex'
                        },
                        {
                            data: 'name'
                        },
                        {
                            data: 'phone'
                        },
                        {
                            data: 'land_name'
                        },
                        {
                            data: 'plot_no'
                        },
                        {
                            data: 'cost'
                        },
                        {
                            data: 'dimension'
                        },
                        {
                            data: 'amount_paid'
                        },
                        {
                            data: 'action',
                            orderable: false,
                            searchable: false
                        },
                    ],
                    "drawCallback": function(settings) {
                        lucide.createIcons();
                    }
                });

                var buttons = new $.fn.dataTable.Buttons(table, {
                    buttons: [
                        'copy', 'excel', 'pdf', 'print'
                    ]
                }).container().appendTo($('#buttons'));
            }
        });
    </script>
@endpush

@section('scripts')
@endsection

@section('scripts_after')
@endsection
