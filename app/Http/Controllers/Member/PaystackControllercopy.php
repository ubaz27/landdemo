<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Paystack;
use App\Models\Transaction;
use App\Models\LandDistribution;
use App\Models\Member;
use Throwable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class PaystackController extends Controller
{
    //

    public function callback(Request $request)
    {
        $phone = Auth::guard('member')->user()->phone;
        $id = Auth::guard('member')->user()->id;
        // dd($ref)/;

        $ref = $request->reference;
        $secret_key = env('PAYSTACK_SECRET_KEY ');
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

        // if ($err) {

        //     echo "cURL Error #:" . $err;
        // } else {

        //     echo $response;
        //     $result = json_decode($response);
        // }

        $response = json_decode($response);

        if ($response->data->status == 'success') {
            $todayDate = date("Y-m-d");

            $paystack = Paystack::where('payment_reference', $ref)
                ->update(['payment_status_code' => 01, 'date_processed' => $todayDate]);

            $payment_details = DB::select("select
     paystacks.amount, land_distributions.id AS land_distribution_id
    from
      `paystacks`
      inner join `land_distributions` on `paystacks`.`land_distribution_id` = `land_distributions`.`id`
    where
      `paystacks`.`payment_reference` = ?
      and `paystacks`.`deleted_at` is null", [$ref]);

            // dd($payment_details);
            // $land_distribution_id = $payment_details->land_distribution_id;
            // $amount = $payment_details->amount;
            foreach ($payment_details as $detail) {
                Transaction::create([
                    'member_id' => $id,
                    'member_phone' => $phone,
                    'land_distribution_id' => $detail->land_distribution_id,
                    'amount' =>  $detail->amount,
                ]);
            }


            $plots = LandDistribution::join('plots', 'land_distributions.plot_id', 'plots.id')
                ->join('lands', 'plots.land_id', 'lands.id')
                ->join('paystacks', 'land_distributions.id', 'paystacks.land_distribution_id')
                ->where('land_distributions.phone', $phone)
                ->get(['lands.land_name', DB::raw('land_distributions.id AS land_dist_id'), 'paystacks.id', 'land_distributions.plot_id', 'plots.plot_no', 'plots.cost', 'plots.dimension', 'paystacks.amount', 'paystacks.payment_status_code']);
            $member_details = Member::find($id);
            // dd($plots);
            return view('member.generate-invoice', compact('plots', 'member_details'));
        }
    }
}
