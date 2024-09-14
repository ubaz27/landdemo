<?php
// error_reporting(E_ALL);
// ini_set('display_errors', 1);

namespace App\Http\Controllers\Admin;




use \Mpdf\Mpdf as MPDF;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Land;
use App\Models\Member;
use App\Models\Agent;
use App\Models\AgentTransaction;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    function __construct()
    {
        $this->middleware('auth:admin');
    }
    //display form for land summary
    public function showPaymentSummary()
    {
        $land_names = Land::all();
        return view('admin.report.land-report', compact('land_names'));
    }

    //generate land summary excel
    public function LandStatementExcel(Request $request)
    {
        $request->validate(
            [
                'land_id' => 'required',
                'sdate' => 'required|date',
                'edate' => 'required|date',
            ]
        );

        $id = $request->land_id;
        $land_name = get_land_name($request->land_id, 'land_name');
        // dd($land_name);
        $lands = Land::find($id);
        $cost = $lands->cost;
        $lga = $lands->lga;
        $sdate = $request->sdate;
        $edate = $request->edate;

        return view('admin.report.land-export-excel', compact('id', 'sdate', 'edate', 'land_name', 'cost', 'lga'));
    }

    //view member report form
    public function showMemberSummary()
    {
        $members = Member::all();
        return view('admin.report.member-report', compact('members'));
    }

    //member report excel
    public function MemberStatementExcel(Request $request)
    {
        $request->validate(
            [
                'member_id' => 'required',

            ]
        );

        $id = $request->member_id;
        $member_name = get_member_name($request->member_id, 'name');
        $member_phone = get_member_phone($request->member_id, 'phone');
        $sdate = $request->sdate;
        $edate = $request->edate;

        return view('admin.report.member-export-excel', compact('id', 'sdate', 'edate', 'member_name', 'member_phone'));
    }

    //plots distribution report excel
    public function PlotsReportExcel(Request $request)
    {
        $request->validate(
            [
                'land_id' => 'required',

            ]
        );

        $id = $request->land_id;
        $lands = Land::find($id);

        $land_name = $lands->land_name;
        $land_cost = $lands->cost;
        $land_location = $lands->lga;
        $land_dimension = $lands->dimension;

        return view('admin.report.plot-export-excel', compact('id', 'land_name', 'land_cost', 'land_location', 'land_dimension', 'land_dimension'));
    }

    public function pdfLandStatement(Request $request)
    {
        // $request->validate(
        //     [
        //         'land_id' => 'required',
        //         'sdate' => 'required|date',
        //         'edate' => 'required|date',
        //     ]
        // );

        $land_name = get_land_name($request->land_id, 'land_name');
        $id = $request->land_id;
        $lands = Land::find($id);
        $sdate = $request->sdate;
        $edate = $request->edate;


        // $transactions = DB::select("SELECT members.name, land_distributions.phone,lands.id AS land_id,  lands.land_name, land_distributions.id AS land_dist_id, land_distributions.plot_id,plots.plot_no, plots.cost, plots.dimension
        // , transactions.amount  FROM land_distributions JOIN plots
        //  ON land_distributions.plot_id = plots.id
        //  INNER JOIN lands ON lands.id = plots.land_id
        //  INNER JOIN members on members.id = land_distributions.member_id
        //    LEFT JOIN transactions ON transactions.land_distribution_id = land_distributions.id
        //    WHERE lands.id = ? AND transactions.created_at => ? AND  transactions.created_at <= ? ", [$id, $sdate, $edate]);

        $transactions = DB::select("SELECT
 land_distributions.phone,members.name, plots.plot_no
,plots.dimension,plots.cost AS plot_cost, transactions.amount FROM
land_distributions
INNER JOIN transactions ON land_distributions.id = transactions.land_distribution_id
INNER JOIN plots ON land_distributions.plot_id = plots.id
INNER JOIN lands ON lands.id = plots.land_id
INNER JOIN members ON members.id = land_distributions.member_id
			  WHERE lands.id = ? AND transactions.created_at >= ? AND  transactions.created_at <= ?
			  ORDER BY land_distributions.phone asc", [$id, $sdate, $edate]);


        if ($request->type === 'pdf') {
            $filename = $land_name . "-Statement.pdf";
            $pdf = MPDF::loadView('admin.report.pdf-land_summary', compact('land_name', 'transactions',  'lands'), [], [
                'title' => 'Cooperative',
                'format' => 'A4',
                'default_font_size' => '12',
                'default_font' => 'Times',
                'orientation' => 'P',
                'margin_bottom' => 15,
                'margin_top' => 15,
                'margin_footer' => 5,
                'margin_header' => 5,
            ]);



            if ($request->submit == 'btnDownload') {
                return $pdf->download($filename);
            }
            return $pdf->stream($filename);
        }

        if ($request->type === 'excel') {
        }
    }


    public function AgentStatementExcel(Request $request)
    {
        $request->validate(
            [
                'agent_id' => 'required',
                'sdate' => 'required',
                'edate' => 'required',

            ]
        );

        $id = $request->agent_id;
        $agents = Agent::find($id);
        $agent_name = $agents->name;
        $agent_phone = $agents->phone;

        $sdate = $request->sdate;
        $edate = $request->edate;

        return view('admin.report.agent-export-excel', compact('id', 'sdate', 'edate', 'agent_name', 'agent_phone'));
    }
}
