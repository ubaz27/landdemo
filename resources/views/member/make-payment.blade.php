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
                    <form id="paymentForm">
                        @csrf
                        <div class="row">
                            <div class="col-md-4">
                                <label for="ageSelect" class="form-label">Land Name (Plot)</label> <span
                                    class="text-danger">(*)</span>
                                <select class="js-example-basic-single form-select" name="member_id" id="member_id">
                                    {{-- @if ($plots->land_dist_id > 0) --}}
                                    @foreach ($plots as $item)
                                        <option value="{{ $item->land_dist_id }}">
                                            {{ $item->land_name . ' Plot ' . $item->plot_no . ' ' . $item->dimension . ' ' . $item->cost }}
                                        </option>
                                    @endforeach

                                    {{-- @endif --}}
                                </select>
                            </div>
                            <div class="col-md-4 ">
                                <label for="name" class="form-label">Phone Numbers</label> <span
                                    class="text-danger">(*)</span>
                                <input id="phone" class="form-control" value="{{ $member_details->phone }}"
                                    name="phone" type="text" required readonly>
                            </div>

                            <div class="col-md-4 ">
                                <label for="name" class="form-label">Full Name</label> <span
                                    class = "text-danger">*</span>
                                <input id="fullname" class="form-control" name="fullname"
                                    value="{{ $member_details->name }}" type="text" readonly>
                            </div>
                            <div class="col-md-4">
                                <label for="email" class="form-label">Email</label> <span class = "text-danger">*</span>
                                <input id="email" class="form-control" name="email" type="email" readonly
                                    value="{{ $member_details->email }}" required placeholder="Valid Email">
                            </div>

                            <input type="text" id="pid" hidden class="form-control" value="1" />

                            <div class="col-md-4">
                                <label for="email" class="form-label">Amount</label> <span class = "text-danger">*</span>
                                <input id="amount" class="form-control" name="amount" type="number"
                                    value="{{ $item->amount }}" required placeholder="Amount" readonly>
                                <span style="color:red;font-size:14px;">Admin Charges Applied</span>
                            </div>

                            <div class="col-md-4">
                                <label for="email" class="form-label">Payment Reference</label> <span
                                    class = "text-danger">*</span>
                                <input id="reference" class="form-control" name="reference" type="text"
                                    value="{{ $item->payment_reference }}" required placeholder="Payment Ref" readonly>
                            </div>




                        </div>
                        <br>
                        <div class="row">
                            <div class="col-sm-12">
                                <input class="btn btn-primary" onclick="payWithPaystack()" type="submit" value="Pay Online"
                                    style="width:20%;">

                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>

    </div>



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
        // alert('PayStack');
        const paymentForm = document.getElementById('paymentForm');
        paymentForm.addEventListener("submit", payWithPaystack, false);

        function payWithPaystack(e) {
            e.preventDefault();
            let handler = PaystackPop.setup({
                // key: 'pk_live_ab15aef639b70a3bc1da3409137e57df1647710e', // Replace with your public key
                // key: 'pk_test_66bc779ffdb59710487eb329205f2ffdf9546395', // Replace with your public key
                key: "{{ env('PAYSTACK_PUBLIC_KEY') }}", // Replace with your public key
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

                    window.location.href = "{{ route('member.callback') }}" + response.redirecturl;


                }
            });
            handler.openIframe();
        }
    </script>
@endsection
