<?php

namespace App\Http\Controllers\Admin;

use Throwable;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Consultant;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class PayConsultantController extends Controller
{
    //


    function __construct()
    {
        $this->middleware('auth:admin');
    }
    public function payConsultants()
    {
        $phone = Auth::guard('admin')->user()->phone;
        $email = Auth::guard('admin')->user()->email;
        $id = Auth::guard('admin')->user()->id;
        $it_commission = env('IT_COMMISSION') - 4;

        $settlement_data = DB::select("select count(id) as no_transaction from transactions where settlement_id =?", [0]);

        // $n = $settlement_data->no_transaction;

        $no_transactions = ($settlement_data);

        $payment_history = Consultant::all();
        $consultant_invoice = Consultant::all();
        // dd($settlement_data);
        return view('admin.payment.pay-consultant', compact('no_transactions', 'email', 'phone', 'it_commission', 'payment_history', 'consultant_invoice'));
    }


    public function generateInvoice(Request $request)
    {
        $request->validate([
            'phone' => 'required',
            'email' => 'required',
            'amount' => 'required',
            'no_transactions' => 'required',

        ]);


        $t = time();
        $todayDate = date("Y-m-d");
        $payable_amount = $request->amount;

        if ($payable_amount < 2500) {
            $paystack_commission = ($payable_amount) * 0.015;
        } else {
            $paystack_commission = ($payable_amount) * 0.015 + 100;

            if ($paystack_commission > 2000) {
                $paystack_commission = 2000;
            }
        }



        // $payable_amount = $request->amount + $paystack_commission;

        // dd($t);
        $ref = 'consultant_' . $t . '_' . $request->phone;
        Consultant::create([
            'email' => $request->email,
            'payment_reference' => $ref,
            'payment_status_code' => '025',
            'date_processed' => $todayDate,
            'no_transactions' => $request->no_transactions,
            'amount' => $payable_amount,

        ]);

        return back()->with('message', 'Invoice Generated ' . $ref);
    }


    public function makePayment(Request $request)
    {
        $request->validate([
            'payment_id' => 'required',

        ]);


        $phone = Auth::guard('admin')->user()->phone;
        $id = Auth::guard('admin')->user()->id;
        $email = Auth::guard('admin')->user()->email;
        $name = Auth::guard('admin')->user()->name;
        $payment_id = $request->payment_id;

        // $consultant_invoice = Consultant::where('id', $payment_id);

        $payment_references = Consultant::where('consultants.id', $payment_id)->get(DB::raw('payment_reference'));
        $amounts = Consultant::where('consultants.id', $payment_id)->get(DB::raw('amount'));
        // dd($amount);
        return view('admin.payment.make-payment-consultant', compact('name', 'payment_references', 'amounts', 'phone', 'email'));
    }


    public function callback(Request $request)
    {
        $phone = Auth::guard('admin')->user()->phone;
        $id = Auth::guard('admin')->user()->id;
        $email = Auth::guard('admin')->user()->email;
        $name = Auth::guard('admin')->user()->name;
        // dd($ref)/;

        $ref = $request->reference;
        // dd($ref);
        $secret_key = env('PAYSTACK_SECRET_KEY');
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.paystack.co/transaction/verify/" . $ref,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer $secret_key",
                "Cache-Control: no-cache",

            ),

        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);



        $response = json_decode($response);
        // dd($response);

        if ($response->data->status == 'success') {
            $todayDate = date("Y-m-d");
            $amount_paid = ($response->data->amount) / 100;
            // dd($amount_paid);

            $paystack = Consultant::where('payment_reference', $ref)
                ->update(['payment_status_code' => 01, 'date_processed' => $todayDate]);

            $settlement_data = DB::select("select  no_transactions from consultants where payment_reference =?", [$ref]);


            // dd($settlement_data);

            foreach ($settlement_data as $item) {
                $no = $item->no_transactions;
            }
            // dd($no);
            $affectedRows = Transaction::where('settlement_id', '=', 0)
                ->limit($no)
                ->update(array('settlement_id' => 1));
            // dd($affectedRows);
            // DB::update("UPDATE transactions set settlement_id = ? where settlement_id = ? limit ? ", ['1', '0', $no]);

            $it_commission = env('IT_COMMISSION') - 4;

            $settlement_data = DB::select("select count(id) as no_transaction from transactions where settlement_id =?", [0]);



            $no_transactions = ($settlement_data);

            $payment_history = Consultant::all();
            $consultant_invoice = Consultant::all();

            return view('admin.payment.pay-consultant', compact('no_transactions', 'email', 'phone', 'it_commission', 'consultant_invoice'));
        } else {
            // return redirect()->route('admin.cancel');
        }
    }
}
