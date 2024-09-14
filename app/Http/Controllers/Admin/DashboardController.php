<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Land;
use App\Models\Member;
use App\Models\Plot;
use App\models\Agent;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;


class DashboardController extends Controller
{
    function __construct()
    {
        $this->middleware('auth:admin');
    }
    public function showDashboard()
    {

        $reset_password = Auth::guard('admin')->user()->reset_password;


        $land_no = DB::select("select count(id) as no_lands from lands");
        // dd($land_no);
        $member_no = DB::select("select count(id) as no_members from members");
        $agent_no = DB::select("select count(id) as no_agents from agents");
        $plot_no = DB::select("select count(id) as no_plots from plots");
        $allocated_plot_no = DB::select("select count(id) as no_plots from plots where is_available = 1");
        $available_plots = DB::select("select
  lands.land_name, plots.plot_no, plots.dimension
  ,  FORMAT(plots.cost, 'N') AS 'Number'
 FROM lands INNER JOIN plots on
 lands.id = plots.land_id WHERE plots.is_available = 0
ORDER BY lands.land_name , plots.plot_no limit 10");

        if ($reset_password == 1) {
            return view('admin.password-reset');
        } else {
            return view('admin.dashboard', compact('available_plots', 'allocated_plot_no', 'land_no', 'member_no', 'plot_no', 'agent_no'));
        }
    }
}
