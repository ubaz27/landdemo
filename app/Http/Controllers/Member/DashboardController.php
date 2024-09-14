<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Plot;
use App\Models\Transaction;
use App\Models\LandDistribution;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;


class DashboardController extends Controller
{
    //
    function __construct()
    {
        $this->middleware('auth:member');
    }
    public function showDashboard()
    {
        $phone = Auth::guard('member')->user()->phone;
        $plot_count = LandDistribution::where('phone',  $phone)->count();
        $cost = LandDistribution::join('plots', 'plots.id', 'land_distributions.plot_id')
            ->where('land_distributions.phone', $phone)->sum('cost');
        // dd($cost);
        $amount_paid = Transaction::where('member_phone',  $phone)->sum('amount');
        $plots = LandDistribution::join('plots', 'land_distributions.plot_id', 'plots.id')
            ->join('lands', 'plots.land_id', 'lands.id')
            ->join('members', 'land_distributions.member_id', 'members.id')
            ->leftJoin('transactions', 'transactions.land_distribution_id', 'land_distributions.id')
            ->where('land_distributions.phone',  $phone)
            ->get(['lands.land_name', DB::raw('land_distributions.id AS land_dist_id'), 'land_distributions.plot_id', 'plots.plot_no', 'plots.cost', 'plots.dimension', 'transactions.amount']);


        // dd($plots);

        return view('member.dashboard', compact('plot_count', 'cost', 'amount_paid', 'plots'));
    }
}
