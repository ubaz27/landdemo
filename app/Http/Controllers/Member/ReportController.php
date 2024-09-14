<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\Models\LandDistribution;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class ReportController extends Controller
{
    //
    function __construct()
    {
        $this->middleware('auth:member');
    }
    public function showReportPayment()
    {
        // dd(Hash::make('manufa'));
        $phone = Auth::guard('member')->user()->phone;
        $plots = LandDistribution::join('plots', 'land_distributions.plot_id', 'plots.id')
            ->join('lands', 'plots.land_id', 'lands.id')
            ->join('members', 'land_distributions.member_id', 'members.id')
            ->where('land_distributions.phone', $phone)
            ->get(['lands.land_name', DB::raw('land_distributions.id AS land_dist_id'), 'land_distributions.plot_id', 'plots.plot_no', 'plots.cost', 'members.id', 'plots.dimension']);

        // dd($plots);
        return view('member.member-report', compact('plots'));
    }
    public function MemberStatementExcel(Request $request)
    {
        $request->validate(
            [
                'member_id' => 'required',

            ]
        );

        $id = Auth::guard('member')->user()->id;
        // $id = $request->member_id;

        $member_name = get_member_name($request->member_id, 'name');
        $member_phone = get_member_phone($request->member_id, 'phone');

        return view('member.member-export-excel', compact('id',  'member_name', 'member_phone'));
    }
}
