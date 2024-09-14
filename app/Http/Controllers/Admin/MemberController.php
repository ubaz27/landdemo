<?php

namespace App\Http\Controllers\Admin;

use Throwable;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Member;
use App\Models\Lga;
use App\Models\Land;
use App\Models\Agent;
use App\Models\LandDistribution;
use App\Models\Plot;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Http\JsonResponse;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Exception;
use File;


class MemberController extends Controller
{
    function __construct()
    {
        $this->middleware('auth:admin');
    }
    //Show add member page
    public function showAddMember()  //add a member form
    {
        $lgas = Lga::all();
        $members = Member::all()->sortByDesc('id');
        return view('admin.member.member', compact('lgas', 'members'));
    }

    //save member data
    public function saveMemberDetail(Request $request)  //save a member detail
    {
        $request->validate([
            'phone' => ['required'],
            'fullname' => 'required',
            'nok' => 'required',
            'nok_phone' => 'required',
            'password' => 'required',
        ]);

        // $members = Member::all();

        // dd($request);
        // if (!is_numeric($request->phone)) {
        //     $message_error = 'Please enter a phone number';
        // }

        try {
            DB::beginTransaction();
            Member::Create([
                'phone' => $request->phone,
                'name' => $request->fullname,
                'lga' => $request->lga,
                'address' => $request->address,
                'nok' => $request->nok,
                // 'is_active' => 1,
                'nok_phone' => $request->nok_phone,
                'password' => Hash::make($request->password),

            ]);
            DB::commit();
            return back()->with('mssg', ['type' => 'success', 'icon' => 'check', 'message' => 'Member ' . $request->fullname . ' Info. Inserted']);
        } catch (Throwable $e) {

            DB::rollback();
            Log::error($e);
            if (env('APP_ENV') == 'local')
                return back()->with('mssg', ['type' => 'danger', 'icon' => 'ban', 'message' => $e->getMessage()]);
            // return back()->with('error', 'Record Not Added');

            // if (!empty($message_error)) {
            //     return back()->with('mssg', ['type' => 'error', 'icon' => 'check', 'message' => 'Provide Phone number for  ' . $request->fullname . '. Record Not Added Successfully']);
            // } else {
            return back()->with('mssg', ['type' => 'error', 'icon' => 'check', 'message' => 'Member ' . $request->fullname . ' Not Added Successfully']);
            // }
        }
    }


    public function showBatchMember()
    {
        $members = Member::all()->sortByDesc('id');
        return view('admin.member.batch-member', compact('members'));
    }

    public function saveBatchMember(Request $request)
    {
        $request->validate([
            'filename' => 'required',
        ]);

        if ($request->hasFile('filename')) {
            $extension = File::extension($request->filename->getClientOriginalName());

            $original_file_name = $request->filename->getClientOriginalName();
            $filename = time() . '_member_' . $original_file_name;
            $request->filename->move(public_path('uploads'), $filename);
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
                $land_id = $request->land_id;

                for ($i = 2; $i <= $rowNumber; $i++) {
                    $phone = $spreadsheet->getActiveSheet()->getCell('B' . $i)->getCalculatedValue();
                    $name = $spreadsheet->getActiveSheet()->getCell('C' . $i)->getCalculatedValue();

                    if (!empty($phone)  and !empty($name)) {
                        Member::Create([
                            'phone' => $phone,
                            'name' => $name,
                            'password' => Hash::make('1000'),

                        ]);

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
    // Upload members using excel (batch)
    public function showMembers()  //show members and land allocation
    {

        return view('admin.member.member-land');
    }

    //batch upload for members and land recored
    public function showMemberLands()
    {
        $transactions = DB::select("SELECT members.name, land_distributions.phone,  lands.land_name, land_distributions.id AS land_dist_id, land_distributions.plot_id,plots.plot_no, plots.cost, plots.dimension
           FROM land_distributions JOIN plots
         ON land_distributions.plot_id = plots.id
         INNER JOIN lands ON lands.id = plots.land_id
         INNER JOIN members on members.id = land_distributions.member_id

        ");
        return view('admin.member.members-land', compact('transactions'));
    }

    //single upload member land record
    public function showMemberAddPlot()
    {
        $land_names = Land::all();
        $agents = Agent::where('is_active', 1)->get();
        // $members = Member::all();
        $plots = Plot::join('lands', 'lands.id', 'plots.land_id')->where('plots.is_available', 0)->get(['plots.land_id', 'lands.land_name', 'plots.id', 'plots.plot_no', 'plots.cost', 'plots.dimension']);
        // $members = DB::select("select concat_ws('-', phone, name) as member, phone as file_no from members where is_active=?", [1]);
        $members  = Member::get(['id', 'name', 'phone', DB::raw('concat(name,"-",phone) as name_phone')]);

        // dd($members);
        return view('admin.member.member-land', compact('land_names', 'plots', 'agents', 'members'));
    }

    //allocate plot to a member
    public function saveLandMember(Request $request)
    {
        $request->validate([
            'member_info' => 'required',
            'land_id' => 'required',
            'file_no' => 'required',
            'plot_id' => 'required',
        ]);
        // dd($request);

        $memberdetails = Member::where('id', $request->file_no)->get();


        try {
            DB::beginTransaction();
            foreach ($memberdetails as $id) {
                // dd($a->id);
                $member_phone = $id->phone;
                LandDistribution::Create([
                    'phone' => $id->phone,
                    'member_id' => $request->file_no,
                    'plot_id' => $request->plot_id,
                    'agent_id' => $request->agent_id,
                ]);

                $plots = Plot::find($request->plot_id);
                $plots->is_available = 1;
                $plots->save();
            }

            DB::commit();
            return back()->with('mssg', ['type' => 'success', 'icon' => 'check', 'message' => 'Member ' . $member_phone . '\' Plot Info. Inserted']);
        } catch (Throwable $e) {
            DB::rollback();
            Log::error($e);
            if (env('APP_ENV') == 'local')
                return back()->with('mssg', ['type' => 'danger', 'icon' => 'ban', 'message' => $e->getMessage()]);
            // return back()->with('error', 'Record Not Added');
            return back()->with('mssg', ['type' => 'error', 'icon' => 'check', 'message' => 'Plot Not Added Successfully']);
        }
    }

    //automatically populate plot selection
    public function fetchPlot(Request $request)
    {
        $land_id = $request->land_id;
        $plots = Plot::where('land_id', $land_id)->where('is_available', [0])->get();
        // dd($plots);
        // $local_governments = Lga::where('state_id', 20)->get();
        return response()->json(['plots' => $plots]);
    }


    public function fetchPlotByMember(Request $request)
    {
        $phone = $request->file_no;

        $plots = DB::Select("SELECT land_distributions.id AS id,
CONCAT('land: ', lands.land_name ,', Plots No: ', plots.plot_no ,', Cost: ', plots.cost ,', Dimension: ', plots.dimension ) AS plot_no
FROM land_distributions

INNER JOIN plots ON land_distributions.plot_id = plots.id
INNER JOIN lands ON lands.id= plots.land_id
WHERE land_distributions.phone = ?
GROUP BY land_distributions.phone, land_distributions.id", [$phone]);
        // $plots = LandDistribution::where('phone', $member_id)->join('plots', 'land_distributions.plot_id', 'plots.id')->get('land_distributions.id', 'plot_no', 'cost', 'dimension');
        // dd($plots);
        // $local_governments = Lga::where('state_id', 20)->get();
        return response()->json(['plots' => $plots]);
    }


    //get phone number of member
    public function fetcMemberPhone(Request $request)
    {
        $distribution_id = $request->distribution_id;
        $phones = LandDistribution::where('id', $distribution_id)->get('phone');
        // dd($plots);
        // $local_governments = Lga::where('state_id', 20)->get();
        return response()->json(['member' => $phones]);
    }
}
