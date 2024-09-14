<?php

namespace App\Http\Controllers\Admin;

use Throwable;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Plot;
use App\Models\Land;
use App\Models\LandDistribution;
use App\Models\Member;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Exception;
use File;

use App\Models\Transaction;



class MemberPaymentController extends Controller
{
    //
    function __construct()
    {
        $this->middleware('auth:admin');
    }
    public function showMemberPayment()
    {

        $plots = Plot::all();
        $land_names = Land::all();
        $members  = Member::get(['id', 'name', 'phone', DB::raw('concat(name,"-",phone) as name_phone')]);

        // $plotData = LandDistribution::join('Plots', 'Plots.id', 'land_distributions.plot_id')->get(['land_distributions.id', 'land_distributions.plot_id', 'land_distributions.phone', 'plots.cost', 'plots.plot_no', 'plots.dimension']);
        //     $transactions = DB::select("SELECT members.name, land_distributions.phone,  lands.land_name, land_distributions.id AS land_dist_id, land_distributions.plot_id,plots.plot_no, plots.cost, plots.dimension
        //   , transactions.amount    FROM land_distributions JOIN plots
        //    ON land_distributions.plot_id = plots.id
        //    INNER JOIN lands ON lands.id = plots.land_id
        //    INNER JOIN members on members.id = land_distributions.member_id
        // 	 LEFT JOIN transactions ON transactions.land_distribution_id = land_distributions.id order by transactions.id desc
        //   ");

        $plotData = DB::select("SELECT SUM( case when transactions.amount IS NULL then 0 when transactions.amount IS NOT NULL then transactions.amount  end) AS amount ,case when transactions.amount > 0 then (plots.cost - SUM(transactions.amount)) when (plots.cost - SUM(transactions.amount)) IS NULL then plots.cost END AS balance , lands.land_name, land_distributions.id, land_distributions.phone,land_distributions.plot_id,plots.plot_no, plots.cost, plots.dimension
               FROM land_distributions JOIN plots
       ON land_distributions.plot_id = plots.id
       INNER JOIN lands ON lands.id = plots.land_id
       LEFT JOIN transactions ON transactions.land_distribution_id = land_distributions.id
        GROUP BY land_distributions.id, transactions.amount  order by land_distributions.phone asc  ");

        // dd($plotData);

        //    $amountPaid = DB::select("SELECT sum(amount) as sum from payments WHERE payments
        return view('admin.payment.member-payment', compact('plots', 'land_names', 'members', 'plotData'));
    }

    public function getMemberPayments(Request $request)
    {
        if ($request->ajax()) {
            $data = LandDistribution::join('plots', 'land_distributions.plot_id', 'plots.id')
                ->join('lands', 'plots.land_id', 'lands.id')
                ->join('members', 'land_distributions.member_id', 'members.id')
                ->leftJoin('transactions', 'transactions.land_distribution_id', 'land_distributions.id')
                ->orderBy('transactions.id', 'desc')
                ->get(['members.name', 'land_distributions.phone', 'lands.land_name', DB::raw('land_distributions.id AS land_dist_id'), 'land_distributions.plot_id', 'plots.plot_no', 'plots.cost', 'plots.dimension', 'transactions.amount']);


            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<a href="' . Url::signedRoute("admin.getMemberPaymentEdit", ['id' => $row->id]) . '" target="_blank" class="btn btn-primary btn-icon" data-toggle="tooltip" data-placement="top" title="View/Edit"><i data-feather="edit"></i></a>';
                    // $btn = '<a href="' . Url::signedRoute("admin.acceptStudent", ['id' => $row->id]) . '" class="btn btn-danger btn-icon" data-toggle="tooltip" data-placement="top" title="Accept"><i data-lucide="edit"></i></a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function _toInt($str)
    {
        return (int)preg_replace("/([^0-9\\.])/i", "", $str);
    }
    public function savePayment(Request $request)
    {
        $request->validate([
            'distribution_id' => 'required',
            'member_info' => 'required',
            // 'amount' => ['required', 'digits_between:0,1000000000'],
            'amount' => ['required'],
            'file_no' => 'required',
            'month' => 'required',

        ]);
        // dd($request->file_no);
        $memberdetails = Member::where('id', $request->file_no)->get('phone');
        // dd($memberdetails);

        try {

            DB::beginTransaction();
            foreach ($memberdetails as $id) {
                // dd($a->id);
                $t = time();
                $ref = 'manual_' . $t . '_' . $id->phone;
                $phone = $id->phone;

                $month =  Carbon::createFromFormat('Y-m', $request->month)->format('M Y');

                Transaction::Create([
                    'member_phone' => $id->phone,
                    'member_id' => $request->file_no,
                    'land_distribution_id' => $request->distribution_id,
                    'amount' => $this->_toInt($request->amount),
                    'month' => $month,
                    'payment_reference' => $ref,

                ]);
            }

            DB::commit();
            return back()->with('mssg', ['type' => 'success', 'icon' => 'check', 'message' => 'Payment Recorded for ' . $phone . '. Successfully']);
        } catch (Throwable $e) {
            DB::rollback();
            Log::error($e);
            if (env('APP_ENV') == 'local')
                return back()->with('mssg', ['type' => 'danger', 'icon' => 'ban', 'message' => $e->getMessage()]);
            // return back()->with('error', 'Record Not Added');
            return back()->with('mssg', ['type' => 'error', 'icon' => 'check', 'message' => 'Payment Not Added Successfully']);
        }
    }


    public function viewBatchPayment()
    {
        $members  = Member::get(['id', 'name', 'phone', DB::raw('concat(name,"-",phone) as name_phone')]);

        return view('admin.payment.batch-payment', compact('members'));
    }

    public function saveBatchPayments(Request $request)
    {
        $request->validate([
            'member_info' => 'required',
            'file_no' => 'required',
            'file_name' => 'required',
            'distribution_id' => 'required',
        ]);


        $memberdetails = Member::where('id', $request->file_no)->get('phone');

        if ($request->hasFile('file_name')) {
            $extension = File::extension($request->file_name->getClientOriginalName());
            if ($extension == "xlsx") {

                $original_file_name = $request->file_name->getClientOriginalName();
                $filename = time() . '_payments_' . $original_file_name;
                $request->file_name->move(public_path('uploads'), $filename);
                $inputFileName = 'uploads/' . $filename;

                $reader = IOFactory::createReader('Xlsx');
                $reader->setReadDataOnly(true);
                $spreadsheet = $reader->load($inputFileName);
                $spreadsheet->setActiveSheetIndex(0);

                $error_occured = false;
                $rowNumber = '';

                try {
                    DB::beginTransaction();
                    $records = 0;
                    $rowNumber = $spreadsheet->getActiveSheet()->getHighestRow();
                    for ($i = 2; $i <= $rowNumber; $i++) {
                        $amount = $spreadsheet->getActiveSheet()->getCell('B' . $i)->getCalculatedValue();
                        $date = $spreadsheet->getActiveSheet()->getCell('C' . $i)->getCalculatedValue();
                        $amount_paid =   $this->_toInt($amount);
                        $month =  Carbon::createFromFormat('Y-m', $date)->format('M Y');




                        if (($amount > 0) and !empty($month) and ($request->distribution_id > 0)) {
                            foreach ($memberdetails as $id) {
                                $t = time();
                                $ref = 'manual_' . $t . '_' . $id->phone . "_" . $i;
                                Transaction::Create([
                                    'member_phone' => $id->phone,
                                    'member_id' => $request->file_no,
                                    'land_distribution_id' => $request->distribution_id,
                                    'amount' => $amount_paid,
                                    'month' => $month,
                                    'payment_reference' => $ref,

                                ]);
                            }
                            $records++;
                        } else {
                            $error_occured = true;
                            $rowNumber =  $i . ',' . $rowNumber;
                            break;
                        }
                    }


                    if ($error_occured == false) {
                        DB::commit();
                        return back()->with('mssg', ['type' => 'primary', 'icon' => 'check', 'message' => 'Upload was  successful for ' . $records . ' Records']);
                    } else {
                        DB::rollback();
                        return back()->with('mssg', ['type' => 'danger', 'icon' => 'check', 'message' => 'Upload was not successful  due to wrong data at row number(s): ' . $rowNumber]);
                    }
                } catch (Throwable $e) {
                    DB::rollback();
                    Log::error($e);
                    if (env('APP_ENV') == 'local')
                        return back()->with('mssg', ['type' => 'danger', 'icon' => 'ban', 'message' => $e->getMessage()]);
                    return back()->with('mssg', ['type' => 'danger', 'icon' => 'ban', 'message' => 'Upload was not successful. Check row No. ' . $i . ' in the uploaded file ' . $original_file_name . '.']);
                }
            } else {

                return back()->with('mssg', ['type' => 'danger', 'icon' => 'ban', 'message' => 'Wrong FIle Type']);

                //'File is a '.$extension.' file.!! Please upload a valid xls/csv file..!!');
            }
        }
    }
}
