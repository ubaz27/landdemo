<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LandDistribution;
use Illuminate\Support\Facades\DB;
use App\Models\Member;
use App\Models\BookingPlot;
use App\Models\Plot;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\Paystack;
use Carbon\Carbon;


class PaymentController extends Controller
{
    //

    function __construct()
    {
        $this->middleware('auth:member');
    }
    public function showMakePayment()
    {
        $phone = Auth::guard('member')->user()->phone;
        $id = Auth::guard('member')->user()->id;

        $plots = LandDistribution::join('plots', 'land_distributions.plot_id', 'plots.id')
            ->join('lands', 'plots.land_id', 'lands.id')
            ->join('members', 'land_distributions.member_id', 'members.id')
            ->where('land_distributions.phone', $phone)
            ->get(['lands.land_name', DB::raw('land_distributions.id AS land_dist_id'), 'land_distributions.plot_id', 'plots.plot_no', 'plots.cost', 'plots.dimension']);
        $member_details = Member::find($id);
        // dd($member_details);
        return view('member.make-payment', compact('plots', 'member_details'));
    }

    public function showPaymentHistory()
    {
        $phone = Auth::guard('member')->user()->phone;
        $plots = LandDistribution::join('plots', 'land_distributions.plot_id', 'plots.id')
            ->join('lands', 'plots.land_id', 'lands.id')
            ->join('members', 'land_distributions.member_id', 'members.id')
            ->leftJoin('transactions', 'transactions.land_distribution_id', 'land_distributions.id')
            ->where('land_distributions.phone',  $phone)
            ->get(['lands.land_name', DB::raw('land_distributions.id AS land_dist_id'), 'land_distributions.plot_id', 'plots.plot_no', 'plots.cost', 'plots.dimension', 'transactions.amount', 'transactions.month']);


        // dd($plots);

        return view('member.payment-history', compact('plots'));
    }



    public function showGenerateInvoice()
    {
        $phone = Auth::guard('member')->user()->phone;
        $id = Auth::guard('member')->user()->id;

        $plots_member = LandDistribution::join('plots', 'land_distributions.plot_id', 'plots.id')
            ->join('lands', 'plots.land_id', 'lands.id')
            ->where('land_distributions.phone', $phone)
            ->get(['lands.land_name', DB::raw('land_distributions.id AS land_dist_id'),  'land_distributions.plot_id', 'plots.plot_no', 'plots.cost', 'plots.dimension']);

        $plots = LandDistribution::join('plots', 'land_distributions.plot_id', 'plots.id')
            ->join('lands', 'plots.land_id', 'lands.id')
            ->join('paystacks', 'land_distributions.id', 'paystacks.land_distribution_id')
            ->where('land_distributions.phone', $phone)
            ->get(['lands.land_name', DB::raw('land_distributions.id AS land_dist_id'), 'paystacks.id', 'land_distributions.plot_id', 'plots.plot_no', 'plots.cost', 'plots.dimension', 'paystacks.amount', 'paystacks.payment_status_code', 'paystacks.month']);
        // $plots = LandDistribution::join('plots', 'land_distributions.plot_id', 'plots.id')
        //     ->join('lands', 'plots.land_id', 'lands.id')

        //     ->where('land_distributions.phone', $phone)
        //     ->get(['lands.land_name', DB::raw('land_distributions.id AS land_dist_id'),  'land_distributions.plot_id', 'plots.plot_no', 'plots.cost', 'plots.dimension']);
        $member_details = Member::find($id);
        // dd($plots);
        return view('member.generate-invoice', compact('plots', 'member_details', 'plots_member'));
    }

    public function generateInvoice(Request $request)
    {
        $request->validate([
            'phone' => 'required',
            'land_dist_id' => 'required',
            'amount' => 'required',
            'month' => 'required',

        ]);


        $month =  Carbon::createFromFormat('Y-m', $request->month)->format('M Y');

        $t = time();
        $todayDate = date("Y-m-d");
        $it_commission  = env('IT_COMMISSION');
        $payable_amount = $request->amount + $it_commission;
        // sdd($payable_amount);
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
        $ref = 'paystack_' . $t . '_' . $request->phone;
        Paystack::create([
            'phone' => $request->phone,
            'payment_reference' => $ref,
            'payment_status_code' => '025',
            'land_distribution_id' => $request->land_dist_id,
            'amount' => $payable_amount,
            'paystack_commission' => $paystack_commission,
            'month' => $request->month,
            'date_processed' => $todayDate,


        ]);

        return back()->with('message', 'Invoice Generated ' . $ref);
    }

    public function showBooking(Request $request)
    {
        $phone = Auth::guard('member')->user()->phone;
        $id = Auth::guard('member')->user()->id;
        $name = Auth::guard('member')->user()->name;

        $request->validate([
            'plot_id' => 'required',
        ]);
        $plot_id = $request->plot_id;
        // $plots = Plot::join('lands', 'lands.id', 'plots.land_id')
        //     ->where('plots.id', $plot_id)->get('lands.land_name', 'plots.plot_no');

        $plots = Plot::join('lands', 'plots.land_id', 'lands.id')
            ->where('plots.id', $plot_id)
            ->get(['lands.land_name', 'plots.plot_no', 'plots.cost', 'plots.dimension', 'plots.id']);
        $deposit_amount = env('INITIAL_DEPOSIT');
        $it_commission = env('IT_COMMISSION');
        $deposit_amount = $it_commission + $deposit_amount;

        // dd($plots);
        $booking_details = BookingPlot::join('plots', 'plots.id', 'booking_plots.plot_id')
            ->where('booking_plots.member_id', $id)->get(['plots.plot_no', 'plots.dimension', 'plots.cost', 'booking_plots.member_id', 'booking_plots.member_phone', 'booking_plots.deposit_amount', 'booking_plots.payment_status_code']);
        // dd($booking_details);
        return view('member.generate-invoice-booking', compact('booking_details', 'deposit_amount', 'name', 'plots', 'phone', 'id'));
    }
    public function generateInvoiceBooking(Request $request)
    {
        $phone = Auth::guard('member')->user()->phone;
        $id = Auth::guard('member')->user()->id;

        $request->validate([
            'phone' => 'required',
            'plot_id' => 'required',
            'amount' => 'required',
            'month' => 'required',

        ]);


        $month =  Carbon::createFromFormat('Y-m', $request->month)->format('M Y');

        $t = time();
        $todayDate = date("Y-m-d");
        $it_commission  = env('IT_COMMISSION');
        // dd($request);
        $payable_amount = $request->amount + $it_commission;

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
        $ref = 'pay_booking_' . $t . '_' . $request->phone;
        BookingPlot::create([
            'plot_id' => $request->plot_id,
            'member_phone' => $phone,
            'member_id' => $id,
            'payment_reference' => $ref,
            'payment_status_code' => '025',
            'deposit_amount' => $request->amount,
            'paystack_commission' => $paystack_commission,
            'month' => $request->month,
            'date_processed' => $todayDate,

        ]);

        $plot = Plot::find($request->plot_id);
        $plot->is_available = 2;
        $plot->save();


        //view invoices


        $plots_member = BookingPlot::join('plots', 'booking_plots.plot_id', 'plots.id')
            ->join('lands', 'plots.land_id', 'lands.id')
            ->where('booking_plots.member_phone', $phone)
            ->get(['booking_plots.id', 'booking_plots.payment_status_code', 'booking_plots.payment_reference', 'booking_plots.deposit_amount', 'plots.plot_no', 'lands.land_name', 'plots.cost', 'plots.dimension']);



        return view('member.view-booked-plots', compact('plots_member'));

        // return back()->with('message', 'Invoice Generated ' . $ref);
    }
    public function makePayment(Request $request)
    {
        $phone = Auth::guard('member')->user()->phone;
        $id = Auth::guard('member')->user()->id;
        $payment_id = $request->payment_id;
        $plots = LandDistribution::join('plots', 'land_distributions.plot_id', 'plots.id')
            ->join('lands', 'plots.land_id', 'lands.id')
            ->join('paystacks', 'land_distributions.id', 'paystacks.land_distribution_id')
            ->where('paystacks.id', $payment_id)
            ->get(['lands.land_name', DB::raw('land_distributions.id AS land_dist_id'), 'land_distributions.plot_id', 'plots.plot_no', 'plots.cost', 'plots.dimension', 'paystacks.id', 'paystacks.amount', 'paystacks.payment_status_code', 'paystacks.payment_reference']);
        $member_details = Member::find($id);
        // dd($plots);
        return view('member.make-payment', compact('plots', 'member_details'));
    }

    public function viewInvoicebBooking()
    {
        $phone = Auth::guard('member')->user()->phone;
        $plots_member = BookingPlot::join('plots', 'booking_plots.plot_id', 'plots.id')
            ->join('lands', 'plots.land_id', 'lands.id')
            ->where('booking_plots.member_phone', $phone)
            ->get(['booking_plots.id', 'booking_plots.payment_status_code', 'booking_plots.payment_reference', 'booking_plots.deposit_amount', 'plots.plot_no', 'lands.land_name', 'plots.cost', 'plots.dimension']);



        return view('member.view-booked-plots', compact('plots_member'));
    }


    public function makeBookingPayment(Request $request)
    {

        $request->validate([
            'payment_id' => 'required',
        ]);


        $payment_id = $request->payment_id;
        $phone = Auth::guard('member')->user()->phone;
        $id = Auth::guard('member')->user()->id;
        $payment_id = $request->payment_id;

        $plots = BookingPlot::join('plots', 'booking_plots.plot_id', 'plots.id')
            ->join('lands', 'plots.land_id', 'lands.id')
            ->where('booking_plots.id', $payment_id)
            ->get(['booking_plots.id', 'booking_plots.payment_status_code', 'booking_plots.payment_reference', 'booking_plots.deposit_amount as amount', 'plots.plot_no', 'lands.land_name', 'plots.cost', 'plots.dimension']);


        $member_details = Member::find($id);
        // dd($plots);
        return view('member.make-payment-booking', compact('plots', 'member_details'));
    }

    public function PayWithPayStack(Request $request) {}

    // public function payment()
    // {
    //     return view('member.a');
    // }
}
