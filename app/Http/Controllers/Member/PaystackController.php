<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Paystack;
use App\Models\BookingPlot;
use App\Models\Transaction;
use App\Models\LandDistribution;
use App\Models\Member;
use App\Models\Plot;
use Throwable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PaystackController extends Controller
{
    //

    public function callback(Request $request)
    {
        $phone = Auth::guard('member')->user()->phone;
        $id = Auth::guard('member')->user()->id;
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

        // if ($err) {

        //     echo "cURL Error #:" . $err;
        // } else {

        //     echo $response;
        //     $result = json_decode($response);
        // }

        $response = json_decode($response);
        // dd($response);

        if ($response->data->status == 'success') {
            $todayDate = date("Y-m-d");
            $amount_paid = ($response->data->amount) / 100;
            // dd($amount_paid);

            $paystack = Paystack::where('payment_reference', $ref)
                ->update(['payment_status_code' => 01, 'date_processed' => $todayDate]);

            $payment_details = DB::select("select
     paystacks.amount,paystacks.paystack_commission ,paystacks.month, land_distributions.id AS land_distribution_id
    from
      `paystacks`
      inner join `land_distributions` on `paystacks`.`land_distribution_id` = `land_distributions`.`id`
    where
      `paystacks`.`payment_reference` = ?
      and `paystacks`.`deleted_at` is null", [$ref]);

            // dd($payment_details);
            // $land_distribution_id = $payment_details->land_distribution_id;
            // $amount = $payment_details->amount;





            $it_commission = env('IT_COMMISSION');

            foreach ($payment_details as $detail) {
                $amount_paid = $detail->amount;
                // $paystack_commission = $detail->paystack_commission;
                $actual_amount = $amount_paid - $it_commission;


                Transaction::create([
                    'member_id' => $id,
                    'member_phone' => $phone,
                    'land_distribution_id' => $detail->land_distribution_id,
                    'amount' =>  $actual_amount,
                    'month' =>  Carbon::createFromFormat('Y-m', $detail->month)->format('M Y'),
                    'payment_reference' => $ref,
                ]);
            }

            $plots_member = LandDistribution::join('plots', 'land_distributions.plot_id', 'plots.id')
                ->join('lands', 'plots.land_id', 'lands.id')
                ->where('land_distributions.phone', $phone)
                ->get(['lands.land_name', DB::raw('land_distributions.id AS land_dist_id'),  'land_distributions.plot_id', 'plots.plot_no', 'plots.cost', 'plots.dimension']);

            $plots = LandDistribution::join('plots', 'land_distributions.plot_id', 'plots.id')
                ->join('lands', 'plots.land_id', 'lands.id')
                ->join('paystacks', 'land_distributions.id', 'paystacks.land_distribution_id')
                ->where('land_distributions.phone', $phone)
                ->get(['lands.land_name', DB::raw('land_distributions.id AS land_dist_id'), 'paystacks.id', 'land_distributions.plot_id', 'plots.plot_no', 'plots.cost', 'plots.dimension', 'paystacks.amount', 'paystacks.payment_status_code']);
            $member_details = Member::find($id);
            // dd($plots);
            return view('member.generate-invoice', compact('plots', 'member_details', 'plots_member'));
        } else {
            return redirect()->route('member.cancel');
        }
    }

    //payment for booking of plot
    public function callback2(Request $request)
    {
        $phone = Auth::guard('member')->user()->phone;
        $id = Auth::guard('member')->user()->id;
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

        if ($response->data->status == 'success') {
            $todayDate = date("Y-m-d");
            $amount_paid = ($response->data->amount) / 100;

            //update bookingplot payment status to paid;
            $paystack = BookingPlot::where('payment_reference', $ref)
                ->update(['payment_status_code' => 01, 'date_processed' => $todayDate]);

            //get plot id
            //  $booking_payment =BookingPlot::where('payment_reference', $ref)
            //     foreach ($booking_payment as $pay) {
            //             $plot_id = $pay->plot_id;
            //             Plot::where('plot_id', $plot_id)->update(['is_available' => 1]);
            //         }



            // dd($records);
            //         $payment_details = DB::select("select
            //  paystacks.amount,paystacks.paystack_commission ,paystacks.month, land_distributions.id AS land_distribution_id
            // from
            //   `paystacks`
            //   inner join `land_distributions` on `paystacks`.`land_distribution_id` = `land_distributions`.`id`
            // where
            //   `paystacks`.`payment_reference` = ?
            //   and `paystacks`.`deleted_at` is null", [$ref]);

            // dd($payment_details);
            // $land_distribution_id = $payment_details->land_distribution_id;
            // $amount = $payment_details->amount;



            $records =  BookingPlot::where('payment_reference', $ref)
                ->where('payment_status_code', 1)->get(['plot_id', 'deposit_amount', 'month']);



            $it_commission = env('IT_COMMISSION');

            foreach ($records as $detail) {
                $amount_paid = $detail->deposit_amount;
                $plot_id = $detail->plot_id;
                $month = $detail->month;
                // $paystack_commission = $detail->paystack_commission;
                $actual_amount = $amount_paid - $it_commission;
                // dd($plot_id);
                //update plot availability
                $plotdata = Plot::find($plot_id);
                $plotdata->is_available = 1;
                $plotdata->save();

                //insert into land distribution
                LandDistribution::create([
                    'phone' => $phone,
                    'member_id' => $id,
                    'plot_id' => $plot_id,
                    'agent_id' => 1,

                ]);

                //get the id of land distribution
                $land_details = LandDistribution::where('phone', $phone)
                    ->where('plot_ID', $plot_id)
                    ->get(['land_distributions.id',]);


                //insert into transaction table
                foreach ($land_details as $transaction) {
                    Transaction::create([
                        'member_id' => $id,
                        'member_phone' => $phone,
                        'land_distribution_id' => $transaction->id,
                        'amount' =>  $amount_paid,
                        'month' =>  Carbon::createFromFormat('Y-m', $month)->format('M Y'),
                        'payment_reference' => $ref,
                    ]);
                }
            }


            // dd($plots);
            //get details for display
            $plots_member = BookingPlot::join('plots', 'booking_plots.plot_id', 'plots.id')
                ->join('lands', 'plots.land_id', 'lands.id')
                ->where('booking_plots.member_phone', $phone)
                ->get(['booking_plots.id', 'booking_plots.payment_status_code', 'booking_plots.payment_reference', 'booking_plots.deposit_amount', 'plots.plot_no', 'lands.land_name', 'plots.cost', 'plots.dimension']);

            return view('member.view-booked-plots', compact('plots_member'));
        } else {
            return redirect()->route('member.cancel');
        }
    }
}
