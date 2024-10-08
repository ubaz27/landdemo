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
                <h4 class="card-title">Batch Land Upload</h4>
                <hr>

                <form id="signupForm" method="POST" action="{{ route('admin.saveBatchPayments') }}"
                    enctype="multipart/form-data">
                    @csrf

                    <div class="row">
                        <div class="col-md-3 ">
                            <label for="name" class="form-label">Phone Number and Full Name</label> (<span
                                class="text-danger">required</span>)

                            <input type="text" class="form-control" id="member_info" name="member_info"
                                value="{{ old('member_info') }}" placeholder=" --Search Member-- " required required>
                            <input type="hidden" class="form-control" value="{{ old('file_no') }}" id="file_no"
                                name="file_no">


                        </div>
                        <div class="col-md-5">
                            <label for="ageSelect" class="form-label">Plot Distribution Data</label> <span
                                class="text-danger">(required)</span>
                            <select class="js-example-basic-single form-select" name="distribution_id" id="distribution_id">
                                <option selected disabled>Select Plot No</option>

                            </select>
                        </div>


                        <div class="col-md-4 ">

                            <div class="custom-file">

                                <label class="custom-file-label" for="MembersFile">Choose file</label>
                                <input class="form-control" type="file" name = 'file_name' id="formFile">
                            </div>
                        </div>
                    </div>
                    <br>
                    <input class="btn btn-primary" type="submit" value="Submit" style="width:30%;">
                </form>



            </div>
        </div>
    </div>

    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Land</a></li>
            <li class="breadcrumb-item active" aria-current="page">Record</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">

                <div class="card-header">
                    <div><span><a class='' style="float:right;"href="{{ url('/admin/member-payment/showform') }}"><i
                                    data-lucide="file-plus"></i>Single Payment Upload</a></span></div>
                    <h5>Available Transactions</h5>
                </div>

                <div class="card-body">

                    <div class="table-responsive">
                        <div id="buttons" class="d-flex justify-content-center"></div>
                        <table id="member_payments_datatable" class="cell-border stripe" style="width: 100%">
                            <thead>
                                <tr>
                                    <th>S/N</th>
                                    <th>Name</th>
                                    <th>Phone</th>
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
    <link href="{{ asset('assets/plugins/easy-autocomplete/easy-autocomplete.min.css') }}" rel="stylesheet" type="text/css">
    <script src="{{ asset('assets/plugins/bootstrap-inputmask/jquery.inputmask.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/easy-autocomplete/jquery.easy-autocomplete.min.js') }}"></script>

    @include('includes/datatable-scripts')
@endpush

@push('custom-scripts')
    <script>
        $(document).ready(function() {

            ///get list of land for a member
            $("#member_info").change(function() {
                $("#distribution_id").empty();
                var file_no = $("#member_info").val();
                var phonearray = file_no.split("-");
                var phone = phonearray[1];

                $.ajax({
                    method: 'POST',
                    url: "{{ route('admin.fetchPlotByMember') }}",
                    data: {
                        'file_no': phone,
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


            Inputmask.extendAliases({
                pesos: {
                    prefix: "₱ ",
                    groupSeparator: ".",
                    alias: "numeric",
                    placeholder: "0",
                    autoGroup: true,
                    digits: 2,
                    digitsOptional: false,
                    clearMaskOnLostFocus: false
                }
            });

            $('#amount').inputmask({
                alias: "currency",
                prefix: '₦ '
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

            loadMemberPayments();

            function loadMemberPayments() {
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
                        url: "{{ route('admin.getMemberPayments') }}",
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
                            data: 'amount'
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
