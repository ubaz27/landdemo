@extends('member.layout.master')

@section('content')
    <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
        <div>
            <h4 class="mb-12 mb-md-0">Welcome to Payment Report Summary</h4>
        </div>

        <div class="col-lg-12 col-sm-12 col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Generate Invoice</h4>
                    <hr>
                    @if (Session::has('message'))
                        <div class="alert alert-primary" role="alert">
                            <ul>
                                {{ Session('message') }}
                                {{-- @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach --}}
                            </ul>
                        </div>
                    @endif
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
                    {{-- <p class="text-muted mb-3">Read the <a href="https://jqueryvalidation.org/" target="_blank"> Official jQuery
                            Validation Documentation </a>for a full list of instructions and other options.</p> --}}
                    <form id="paymentForm" action="{{ route('member.generateInvoiceBooking') }}" Method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-4">
                                <label for="ageSelect" class="form-label">Land Name (Plot)</label> <span
                                    class="text-danger">(*)</span>
                                <select class="js-example-basic-single form-select" name="plot_id" id="plot_id">
                                    {{-- <option selected disabled>Select Plot Record</option> --}}
                                    @foreach ($plots as $item)
                                        <option value="{{ $item->id }}">
                                            {{ $item->land_name . ' Plot ' . $item->plot_no . ' ' . $item->dimension . ' ' . $item->cost }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4 ">
                                <label for="name" class="form-label">Phone Numbers</label> <span
                                    class="text-danger">(*)</span>
                                <input id="phone" class="form-control" value="{{ $phone }}" name="phone"
                                    type="text" required readonly>
                            </div>

                            <div class="col-md-4 ">
                                <label for="name" class="form-label">Full Name</label> <span
                                    class = "text-danger">*</span>
                                <input id="fullname" class="form-control" name="fullname" value="{{ $name }}"
                                    type="text" readonly>
                            </div>


                            {{-- <input type="text" id="pid" hidden class="form-control" value="" /> --}}

                            <div class="col-md-4">
                                <label for="email" class="form-label">Amount</label> <span class = "text-danger">*</span>
                                <input id="amount" class="form-control" name="amount" type="number"
                                    value="{{ $deposit_amount }}" readonly required placeholder="Amount">

                            </div>
                            <div class="col-md-3">
                                <label for="ageSelect" class="form-label">Month</label> <span class="text-danger">(*)</span>
                                <input type="month" class="form-control" name='month' id='month'>
                            </div>



                        </div>
                        <br>
                        <div class="row">
                            <div class="col-sm-12">
                                <input class="btn btn-primary" type="submit" value="Generate Invoice">

                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>

    </div>




    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-baseline mb-2">
                        <h6 class="card-title mb-0">Invoice Track</h6>
                        <div class="dropdown mb-2">

                        </div>
                    </div>
                    <div class="table-responsive">
                        <table id="dataTableExample" class="table">
                            <thead>
                                <tr>
                                    <th class="pt-0">S/N</th>
                                    <th class="pt-0">Land Name</th>
                                    <th class="pt-0">Plots No</th>
                                    <th class="pt-0">Paymenet Reference</th>
                                    <th class="pt-0">Cost</th>
                                    <th class="pt-0">Amount to Pay</th>
                                    <th class="pt-0">Month</th>
                                    <th class="pt-0">Action</th>
                                </tr>
                            </thead>
                            <tbody>

                                @foreach ($booking_details as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->member_phone }}</td>
                                        <td>{{ $item->plot_no }}</td>
                                        <td>{{ $item->dimension }}</td>
                                        <td>N {{ number_format($item->cost, 2) }}</td>
                                        <td>N {{ number_format($item->deposit_amount, 2) }}</td>
                                        <td>N </td>
                                        <td>
                                            @if ($item->payment_status_code == '025')
                                                <form action="{{ route('member.makeBookingPayment') }}" method = 'POST'>
                                                    <input type="text" hidden class="form-control"
                                                        value ="{{ $item->id }}" name="payment_id">
                                                    <input type="submit" class="btn btn-success btn-sm" name="pay"
                                                        value="Pay">
                                                </form>
                                            @else
                                                <span>Paid</span>
                                            @endif


                                        </td>

                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>


    </div> <!-- row -->
@endsection


<script src="https://js.paystack.co/v1/inline.js"></script>

<script>
    function isNumber(evt) {
        evt = (evt) ? evt : window.event;
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        if (charCode > 31 && (charCode < 48 || charCode > 57)) {
            return false;
        }
        return true;
    }

    const paymentForm = document.getElementById('paymentForm');
    paymentForm.addEventListener("submit", payWithPaystack, false);

    function payWithPaystack(e) {
        e.preventDefault();
        let handler = PaystackPop.setup({
            // key: 'pk_live_ab15aef639b70a3bc1da3409137e57df1647710e', // Replace with your public key
            // key: 'pk_test_66bc779ffdb59710487eb329205f2ffdf9546395', // Replace with your public key
            key: 'pk_live_003e075cbdaef6954b4bd7e463b12e2bcd4ff690', // Replace with your public key
            email: document.getElementById("email").value,
            lastname: document.getElementById("fullname").value,
            firstname: document.getElementById("fullname").value,

            amount: document.getElementById("amount").value * 100,


            gsm: document.getElementById("phone").value,
            pid: document.getElementById("pid").value,
            ref: document.getElementById("reference")
                .value, // generates a pseudo-unique reference. Please replace with a reference you generated. Or remove the line entirely so our API will generate one for you
            // label: "Optional string that replaces customer email"
            onClose: function() {
                alert('Window closed.');
            },
            callback: function(response) {
                let message = 'Payment complete! Reference: ' + response.reference;
                // alert(message);
                window.location = "./online_reg_ref.php?reference=" + response.reference;
            }
        });
        handler.openIframe();
    }
</script>
