<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\BookingPlot;
use Illuminate\Http\Request;
use App\Models\LandDistribution;
use App\Models\Plot;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;


class PlotController extends Controller
{
    //
    function __construct()
    {
        $this->middleware('auth:member');
    }
    public function showPlotsView()
    {
        $phone = Auth::guard('member')->user()->phone;
        $id = Auth::guard('member')->user()->id;
        $plots = LandDistribution::join('plots', 'land_distributions.plot_id', 'plots.id')
            ->join('lands', 'plots.land_id', 'lands.id')
            ->join('members', 'land_distributions.member_id', 'members.id')
            ->where('land_distributions.phone', $phone)
            ->get(['lands.land_name', DB::raw('land_distributions.id AS land_dist_id'), 'land_distributions.plot_id', 'plots.plot_no', 'plots.cost', 'plots.dimension']);


        return view('member.member-land', compact('plots'));
    }
    public function showAvailablePlots()
    {

        $booked = BookingPlot::join('plots', 'booking_plots.plot_id', 'plots.id')
            ->where('booking_plots.created_at', '<=', date('Y-m-d'))
            ->where('plots.is_available', '=', 2)
            ->get([
                'booking_plots.id',
                'booking_plots.plot_id',
                'booking_plots.created_at',
                'booking_plots.member_phone',
                'plots.plot_no',
                'plots.id as plotid',
                'plots.is_available'
            ]);
        // dd($booked);
        foreach ($booked as $item) {
            // dd($item->plotid);
            $plotupdate = Plot::find($item->plotid);
            $plotupdate->is_available = 0;
            $plotupdate->save();


            $bookingdelete = DB::table('booking_plots')->where('id', '=', $item->id)->delete();
            // $bookingdelete->delete();

            // $bookingdelete = BookingPlot::withTrashed()->find($item->id);
            // dd($bookingdelete);
            // $bookingdelete->forceDelete();
        }


        $plots = Plot::join('lands', 'plots.land_id', 'lands.id')
            ->where('plots.is_available', '0')

            ->get(['lands.land_name',  'plots.plot_no', 'plots.id', 'plots.cost', 'plots.dimension']);

        return view('member.view-alailable-plots', compact('plots'));
    }
}
