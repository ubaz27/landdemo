@extends('admin.layout.master')

@section('content')
    <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
        <div>
            <h4 class="mb-12 mb-md-0">Welcome to Payment Consultant Summary</h4>
        </div>

        <div class="col-lg-12 col-sm-12 col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Generate Invoice</h4>
                    <hr>

                    {{-- <p class="text-muted mb-3">Read the <a href="https://jqueryvalidation.org/" target="_blank"> Official jQuery
                            Validation Documentation </a>for a full list of instructions and other options.</p> --}}
                    <form id="paymentForm" action="{{ route('admin.generateInvoice') }}" Method="POST">
                        @csrf
                        <div class="row">

                            <div class="col-md-3 ">
                                <label for="name" class="form-label">Admin Phone Numbers</label> <span
                                    class="text-danger">(*)</span>
                                <input id="phone" class="form-control" value="{{ $phone }}" name="phone"
                                    type="text" required readonly>
                            </div>

                            <div class="col-md-3 ">
                                <label for="name" class="form-label">Admin Email</label> <span
                                    class = "text-danger">*</span>
                                <input id="email" class="form-control" name="email" value="{{ $email }}"
                                    type="text" readonly>
                            </div>


                            {{-- <input type="text" id="pid" hidden class="form-control" value="" /> --}}
                            <div class="col-md-4">
                                <label for="email" class="form-label">Number of Transactions</label> <span
                                    class = "text-danger">*</span>
                                @foreach ($no_transactions as $item)
                                    <input id="no_transactions" class="form-control" name="no_transactions" type="text"
                                        placeholder="Amount" value = "{{ $item->no_transaction }}" readonly required>
                                @endforeach


                            </div>
                            <div class="col-md-4">
                                <label for="email" class="form-label">Amount</label> <span class = "text-danger">*</span>
                                @foreach ($no_transactions as $item)
                                    <input id="amount" class="form-control" name="amount" type="text"
                                        placeholder="Amount" readonly value = "{{ $item->no_transaction * $it_commission }}"
                                        required>
                                @endforeach


                            </div>


                            <div class="col-md-6">
                                <label for="ageSelect" class="form-label">Description</label> <span
                                    class="text-danger">(*)</span>
                                <textarea class="form-control" name='description' id='description'></textarea>

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
                                    <th class="pt-0">Email</th>
                                    <th class="pt-0">Paymenet Reference</th>
                                    <th class="pt-0">No Of Transactions</th>
                                    <th class="pt-0">Amount to Pay</th>

                                    <th class="pt-0">Action</th>
                                </tr>
                            </thead>
                            <tbody>

                                @foreach ($consultant_invoice as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->email }}</td>
                                        <td>{{ $item->payment_reference }}</td>
                                        <td>{{ $item->no_transactions }}</td>
                                        <td>N {{ number_format($item->amount, 2) }}</td>
                                        <td>
                                            @if ($item->payment_status_code == '025')
                                                <form action="{{ route('admin.makePayment') }}" method = 'POST'>
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
