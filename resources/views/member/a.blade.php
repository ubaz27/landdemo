@extends('member.layout.master')
@section('content')
    <div class="container" style="width: 100%;font-size: 15px;margin-top:8%; margin-left:3%;">


        <form id="paymentForm">
            <div class="row" style="width: 100%; ">
                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="email" id="email-address" class="form-control" value="ubaz27@gmail.com" required />
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="email">Phone</label>
                        <input type="text" id="phone" class="form-control" value='08066554433' required />
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="amount">Amount</label>
                        <input type="tel" id="amount" class="form-control" readonly value='5000' required />
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="first-name">Ward's Name</label>
                        <input type="text" id="first-name" readonly class="form-control" value='umar ' />
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="first-name">Parent's Name</label>
                        <input type="text" id="last-name" class="form-control" value='isah' />
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="reference">Payment Ref</label>
                        <input type="text" id="reference" readonly class="form-control" value="fi0230932" />
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">

                        <input type="text" id="pid" hidden class="form-control" value="12" />
                    </div>
                </div>
                <div class="col-sm-4">

                </div>
            </div>
            <div class="form-submit">
                <button type="submit" class="btn btn-primary" onclick="payWithPaystack()"> Pay </button>
            </div>
        </form>

        <!--<script src="https://js.paystack.co/v1/inline.js"></script> -->
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

        const paymentForm = document.getElementById('paymentForm');
        paymentForm.addEventListener("submit", payWithPaystack, false);

        function payWithPaystack(e) {
            e.preventDefault();
            let handler = PaystackPop.setup({
                // key: 'pk_live_ab15aef639b70a3bc1da3409137e57df1647710e', // Replace with your public key
                // key: 'pk_test_66bc779ffdb59710487eb329205f2ffdf9546395', // Replace with your public key
                key: 'pk_live_003e075cbdaef6954b4bd7e463b12e2bcd4ff690', // Replace with your public key
                email: document.getElementById("email-address").value,
                lastname: document.getElementById("last-name").value,
                firstname: document.getElementById("first-name").value,

                amount: document.getElementById("amount").value * 100,

                // alert(lastname);
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
@endsection
