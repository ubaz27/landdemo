<?php

namespace App\Http\Controllers\Admin;

use Throwable;

use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Yajra\DataTables\DataTables;
use Illuminate\Http\Request;
use App\Models\Land;
use App\Models\Lga;
use App\Models\Plot;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Exception;
use File;

ini_set('max_execution_time', '300');
class LandController extends Controller
{
    //
    function __construct()
    {
        $this->middleware('auth:admin');
    }


    public function _toInt($str)
    {
        return (int)preg_replace("/([^0-9\\.])/i", "", $str);
    }
    public function showLand()
    {


        $land = Land::all();
        $lgas = Lga::all();
        return view('admin.land.landsummary', compact('land', 'lgas'));
    }


    public function showLandBatch()
    {

        $land = Land::all();
        return view('admin.land.batch-land', compact('land'));
    }


    public function batchLandUpload(Request $request)
    {
        $request->validate([
            'land_filename' => 'required',
        ]);

        if ($request->hasFile('land_filename')) {
            $extension = File::extension($request->land_filename->getClientOriginalName());
            if ($extension == "xlsx") {
                $original_file_name = $request->land_filename->getClientOriginalName();
                $filename = time() . '_lands_' . $original_file_name;
                $request->land_filename->move(public_path('uploads'), $filename);
                $inputFileName = 'uploads/' . $filename;

                $reader = IOFactory::createReader('Xlsx');
                $reader->setReadDataOnly(true);
                $spreadsheet = $reader->load($inputFileName);
                $spreadsheet->setActiveSheetIndex(0);

                try {
                    DB::beginTransaction();
                    $records = 0;
                    $rowNumber = $spreadsheet->getActiveSheet()->getHighestRow();
                    for ($i = 2; $i <= $rowNumber; $i++) {
                        $landName = $spreadsheet->getActiveSheet()->getCell('B' . $i)->getCalculatedValue();
                        $lga = $spreadsheet->getActiveSheet()->getCell('C' . $i)->getCalculatedValue();
                        $cost = $spreadsheet->getActiveSheet()->getCell('D' . $i)->getCalculatedValue();
                        $dimension = $spreadsheet->getActiveSheet()->getCell('E' . $i)->getCalculatedValue();
                        $commission = $spreadsheet->getActiveSheet()->getCell('F' . $i)->getCalculatedValue();
                        $landmark = $spreadsheet->getActiveSheet()->getCell('G' . $i)->getCalculatedValue();
                        // $cost = $this->_toInt($cost);

                        if (!empty($landName) and !empty($lga) and !empty($cost) and !empty($dimension) and !empty($commission)) {
                            Land::Create([
                                'land_name' => $landName,
                                'lga' => $lga,
                                'cost' => $this->_toInt($cost),
                                'dimension' => $dimension,
                                'commission' => $commission,
                                'land_mark' => $landmark,
                            ]);

                            $records++;
                        }
                    }

                    DB::commit();
                    return back()->with('mssg', ['type' => 'primary', 'icon' => 'check', 'message' => 'Upload was  successful for ' . $records . ' Records']);
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
    public function showLandDistribution()
    {
        $land_names = Land::all();
        $lgas = Lga::all();
        // $plots = Plot::all();
        $plots = DB::select("select plots.*,lands.land_name from plots join lands on lands.id = plots.land_id order by land_id desc");
        return view('admin.land.land-distribution', compact('land_names', 'plots', 'lgas'));
    }

    public function showPLotsBatch()
    {
        $land_names = Land::all();
        $plots = DB::select("select plots.*,lands.land_name from plots join lands on lands.id = plots.land_id order by land_id desc");
        return view('admin.land.plots-batch', compact('plots', 'land_names'));
    }

    public function savePlotsBatch(Request $request)
    {
        $request->validate([
            'land_id' => 'required',
            'plot_filename' => 'required',


        ]);
        if ($request->hasFile('plot_filename')) {
            $extension = File::extension($request->plot_filename->getClientOriginalName());

            $original_file_name = $request->plot_filename->getClientOriginalName();
            $filename = time() . '_plots_' . $original_file_name;
            $request->plot_filename->move(public_path('uploads'), $filename);
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
                    $plot_no = $spreadsheet->getActiveSheet()->getCell('B' . $i)->getCalculatedValue();
                    $cost = $spreadsheet->getActiveSheet()->getCell('C' . $i)->getCalculatedValue();
                    $dimension = $spreadsheet->getActiveSheet()->getCell('D' . $i)->getCalculatedValue();

                    // $cost = $this->_toInt($cost);
                    // dd($request);
                    if (!empty($plot_no)  and !empty($cost) and !empty($dimension)) {
                        Plot::Create([
                            'land_id' => $land_id,
                            'plot_no' => $plot_no,
                            'cost' => $this->_toInt($cost),
                            'dimension' => $dimension,

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

    public function saveLandDistribution(Request $request)
    {
        // $request->validate([
        //     'land_id' => 'required',
        //     'landno' => 'required',
        //     'dimension' => 'required',
        //     'cost   ' => 'required',

        // ]);
        try {
            DB::beginTransaction();
            $land_id = $request->land_id;
            $landno = $request->landno;
            $dimension = $request->dimension;
            $cost = $request->cost;

            for ($i = 0; $i < count($landno); $i++) {

                Plot::Create([
                    'land_id' => $land_id,
                    'plot_no' => $landno[$i],
                    'dimension' => $dimension[$i],
                    'cost' => $this->_toInt($cost[$i]),
                ]);
            }

            DB::commit();
            // return back()->with('success', 'Record Added successfully');
            return back()->with('mssg', ['type' => 'success', 'icon' => 'check', 'message' => 'Plot Info. Inserted Successfully.']);
        } catch (Throwable $e) {
            DB::rollback();
            Log::error($e);
            if (env('APP_ENV') == 'local')
                return back()->with('mssg', ['type' => 'danger', 'icon' => 'ban', 'message' => $e->getMessage()]);
            // return back()->with('error', 'Record Not Added');
            return back()->with('mssg', ['type' => 'error', 'icon' => 'check', 'message' => 'Plot Not Added Successfully']);
        }
    }

    //save land information
    public function saveLandInfo(Request $request)
    {
        $request->validate([
            'land_name' => 'required',
            'lga_select' => 'required',
            'cost' => 'required',
            'dimension' => 'required',
            'commission' => 'required',


        ]);

        try {
            Land::Create([
                'land_name' => $request->land_name,
                'lga' => $request->lga_select,
                'cost' => $this->_toInt($request->cost),
                'dimension' => $request->dimension,
                'commission' => $this->_toInt($request->commission),
                'land_mark' => $request->land_mark,
            ]);

            // return back()->with('message', "Land Saved Successfully");
            return back()->with('mssg', ['type' => 'primary', 'icon' => 'check', 'message' => 'Upload was  successful for ' . $request->land_name]);
        } catch (Throwable $e) {

            Log::error($e);
            if (env('APP_ENV') == 'local1')
                return back()->with('mssg', ['type' => 'danger', 'icon' => 'ban', 'message' => $e->getMessage()]);

            return back()->with('mssg', ['type' => 'danger', 'icon' => 'ban', 'message' => 'Upload was not successful. for ' . $request->land_name]);
        }
    }
    // public function showLandSummary()
    // {
    //     $lands = Land::all();
    //     return view('admin.land.plots', compact('lands'));
    // }
}
