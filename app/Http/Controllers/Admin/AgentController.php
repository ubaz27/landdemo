<?php

namespace App\Http\Controllers\Admin;

use Throwable;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Agent;
use App\Models\AgentTransaction;
use App\Models\Lga;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use PhpParser\Node\Stmt\TryCatch;
use Yajra\DataTables\DataTables;


class AgentController extends Controller
{

    function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function _toInt($str)
    {
        return (int)preg_replace("/([^0-9\\.])/i", "", $str);
    }



    //show add agent form
    public function showAgent()
    {



        // dd($data);
        $lgas = Lga::all();
        $agents = Agent::all();
        return view('admin.agent.add-agent', compact('lgas', 'agents'));
    }

    //save agent data
    public function saveAgentData(Request $request)
    {
        $request->validate([
            'fullname' => 'required',
            'phone' => 'required',
            'lga' => 'required',
        ]);


        try {
            DB::beginTransaction();
            Agent::Create([
                'name' => $request->fullname,
                'lga' => $request->lga,
                'phone' => $request->phone,
                'agent_company' => $request->company,
                'nok' => $request->nok,
                'nok_phone' => $request->nok_phone,
                'address' => $request->address,

            ]);

            DB::commit();
            return back()->with('mssg', ['type' => 'success', 'icon' => 'check', 'message' => 'Agent Info. Inserted']);
        } catch (Throwable $e) {
            DB::rollback();
            Log::error($e);
            if (env('APP_ENV') == 'local')
                return back()->with('mssg', ['type' => 'danger', 'icon' => 'ban', 'message' => $e->getMessage()]);
            // return back()->with('error', 'Record Not Added');
            return back()->with('mssg', ['type' => 'error', 'icon' => 'check', 'message' => 'Agent Added Successfully']);
        }
    }



    // public function showAgentReport()
    // {
    //     return view('admin.agent-report');
    // }

    public function showAgentLand()
    {
        $agents = DB::select("SELECT
agents.name, agents.phone, lands.land_name,plots.dimension,plots.cost,
lands.commission, plots.plot_no,land_distributions.phone AS member_phone
FROM agents
INNER JOIN land_distributions ON agents.id = land_distributions.agent_id
INNER JOIN plots ON plots.id = land_distributions.plot_id
INNER JOIN lands ON lands.id = plots.land_id where agents.phone != '08000000000' ORDER BY agents.name
");

        return view('admin.agent.show-agent-plots-land', compact('agents'));
    }


    public function showAgentPaymentForm()
    {
        $agents  = Agent::get(['id', 'name', 'phone', DB::raw('concat(name,"-",phone) as name_phone')]);
        return view('admin.agent.agent_transaction', compact('agents'));
    }

    public function fetchPlotByAgent(Request $request)
    {
        $agent_id = $request->file_no;

        $plots = DB::Select("SELECT land_distributions.id AS id,
CONCAT('land: ', lands.land_name ,', Plots No: ', plots.plot_no ,', Commision: ', lands.commission) AS plot_no
, lands.commission FROM land_distributions
INNER JOIN plots ON land_distributions.plot_id = plots.id
INNER JOIN lands ON lands.id= plots.land_id
WHERE land_distributions.agent_id = ?
GROUP BY land_distributions.phone, land_distributions.id", [$agent_id]);
        // $plots = LandDistribution::where('phone', $member_id)->join('plots', 'land_distributions.plot_id', 'plots.id')->get('land_distributions.id', 'plot_no', 'cost', 'dimension');
        // dd($plots);
        // $local_governments = Lga::where('state_id', 20)->get();
        return response()->json(['plots' => $plots]);
    }

    public function saveAgentTransactions(Request $request)
    {
        $request->validate([
            'agent_id' => 'required',
            'distribution_id' => 'required',
            'amount' => 'required',
            'payment_date' => 'required',

        ]);
        try {
            DB::beginTransaction();
            AgentTransaction::Create([
                'agent_id' => $request->agent_id,
                'land_distribution_id' => $request->distribution_id,
                'amount_paid' => $this->_toInt($request->amount),
                'description' => $request->description,
                'payment_date' => $request->payment_date,

            ]);
            DB::commit();
            return back()->with('mssg', ['type' => 'success', 'icon' => 'check', 'message' => 'Agent Payment Inserted']);
        } catch (Throwable $e) {
            DB::rollback();
            Log::error($e);
            if (env('APP_ENV') == 'local1')
                return back()->with('mssg', ['type' => 'danger', 'icon' => 'ban', 'message' => $e->getMessage()]);


            return back()->with('mssg', ['Payment was not successful.']);
        }
    }


    public function getAgentPayments(Request $request)
    {
        if ($request->ajax()) {
            $data = AgentTransaction::join('land_distributions', 'land_distributions.id', 'agent_transactions.land_distribution_id')
                ->join('agents', 'agents.id', 'agent_transactions.agent_id')
                ->join('plots', 'plots.id', 'land_distributions.plot_id')
                ->join('lands', 'lands.id', 'plots.land_id')
                ->orderBy('agent_transactions.id', 'desc')
                ->get(['agents.name', 'land_distributions.phone', 'lands.land_name', DB::raw('land_distributions.id AS land_dist_id'), 'land_distributions.plot_id', 'plots.plot_no', 'plots.cost', 'plots.dimension', 'agent_transactions.amount_paid']);

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<a href="' . Url::signedRoute("admin.getAgentPaymentEdit", ['id' => $row->id]) . '" target="_blank" class="btn btn-primary btn-icon" data-toggle="tooltip" data-placement="top" title="View/Edit"><i data-feather="edit"></i></a>';
                    // $btn = '<a href="' . Url::signedRoute("admin.acceptStudent", ['id' => $row->id]) . '" class="btn btn-danger btn-icon" data-toggle="tooltip" data-placement="top" title="Accept"><i data-lucide="edit"></i></a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function showAgentReportForm()
    {
        $agents =   Agent::all();
        return view('admin.report.agent-report', compact('agents'));
    }
}
