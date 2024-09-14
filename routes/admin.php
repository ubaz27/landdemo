<?php

use GuzzleHttp\Middleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\LandController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AgentController;
use App\Http\Controllers\Admin\LoginController;
use App\Http\Controllers\Admin\MemberController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PayConsultantController;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\Admin\LandSummaryController;
use App\Http\Controllers\Admin\MemberPaymentController;

//login
// $user = Auth::user();

// Route::get('/', function () {
//     return redirect()->route('admin.showLogin');
// });
Route::get('/', function () {
    return redirect()->route('admin.showLogin');
});


Route::get('/', [LoginController::class, 'showLogin'])->name('showLogin');
Route::get('/login', [LoginController::class, 'showLogin'])->name('showLogin');
Route::post('/login', [LoginController::class, 'login'])->name('login');
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');
// Dashboard
Route::get('/dashboard', [DashboardController::class, 'showDashboard'])->name('showDashboard');

// Route::get('/dashboard', function () {
//     $reset_password = Auth::guard('admin')->user()->reset_password;

//     if ($reset_password == 1) {
//         return view('admin.password-reset');
//     } else {
//         $land_no = DB::select("select count(id) as no_lands from lands");
//         // dd($land_no);
//         $member_no = DB::select("select count(id) as no_members from members");
//         $agent_no = DB::select("select count(id) as no_agents from agents");
//         $plot_no = DB::select("select count(id) as no_plots from plots");
//         $allocated_plot_no = DB::select("select count(id) as no_plots from plots where is_available = 1");
//         $available_plots = DB::select("select
//       lands.land_name, plots.plot_no, plots.dimension
//       ,  FORMAT(plots.cost, 'N') AS 'Number'
//      FROM lands INNER JOIN plots on
//      lands.id = plots.land_id WHERE plots.is_available = 0
//     ORDER BY lands.land_name , plots.plot_no limit 10");

//         return view('admin.dashboard', compact('available_plots', 'allocated_plot_no', 'land_no', 'member_no', 'plot_no', 'agent_no'));
//     }
// });


Route::group(['middleware' => 'auth'], function () {});

Route::middleware(['auth:admin', 'ChangePasswordAdmin'])->group(function () {});

if (Auth::check()) {  //check if user is logged in
}


//change password
Route::get('/change-password', [LoginController::class, 'showChangePassword'])->name('showChangePassword');
Route::post('/change-password', [LoginController::class, 'saveChangePassword'])->name('saveChangePassword');
Route::post('/reset-password', [LoginController::class, 'resetPassword'])->name('resetPassword');

//update profile

Route::post('/update-profile', [LoginController::class, 'saveProfile'])->name('saveProfile');


Route::get('/users', [UserController::class, 'showUsers'])->name('showUsers');
Route::post('/save-users', [UserController::class, 'saveUser'])->name('saveUser');
Route::post('/user-edit', [UserController::class, 'editUser'])->name('editUser');
Route::post('/save-user-edit', [UserController::class, 'saveEditUser'])->name('saveEditUser');

//admin
Route::get('/admins', [AdminController::class, 'showAdmins'])->name('showAdmins');
Route::post('/save-admins', [AdminController::class, 'saveAdmin'])->name('saveAdmin');
Route::post('/admin-edit', [AdminController::class, 'editAdmin'])->name('editAdmin');
Route::post('/save-admin-edit', [AdminController::class, 'saveEditAdmin'])->name('saveEditAdmin');

// profile
Route::get('/profile', function () {
    $email = Auth::guard('admin')->user()->email;
    $fullname = Auth::guard('admin')->user()->name;
    $phone = Auth::guard('admin')->user()->phone;
    return view('admin.profile', compact('email', 'fullname', 'phone'));
});




// land page
Route::get('/land', [LandController::class, 'showLand'])->name('showLand');
Route::post('/land/landsummary', [LandController::class, 'saveLandInfo'])->name('saveLandInfo');   //save land info
Route::get('land-distribution', [LandController::class, 'showLandDistribution'])->name('showLandDistribution');
Route::post('/land/land-distribution-add', [LandController::class, 'saveLandDistribution'])->name('saveLandDistribution');
Route::get('/batch-land', [LandController::class, 'showLandBatch'])->name('showLandBatch');
Route::post('/batch-upload', [LandController::class, 'batchLandUpload'])->name('batchLandUpload');
Route::get('/batch-plots', [LandController::class, 'showPLotsBatch'])->name('showPLotsBatch');
Route::post('/batch-plots-add', [LandController::class, 'savePlotsBatch'])->name('savePlotsBatch');



// Members
Route::get('/member', [MemberController::class, 'showAddMember'])->name('showAddMember');  //add/show a member form
Route::post('/member/add', [MemberController::class, 'saveMemberDetail'])->name('saveMemberDetail'); //save member data

Route::get('/batch-member', [MemberController::class, 'showBatchMember'])->name('showBatchMember');
Route::post('/batch-member-upload', [MemberController::class, 'saveBatchMember'])->name('saveBatchMember');

Route::get('/members-land', [MemberController::class, 'showMemberLands'])->name('showMemberLands');  //Batch upload
Route::get('/member-land', [MemberController::class, 'showMemberAddPlot'])->name('showMemberAddPlot'); //single land to member upload
Route::post('/allocate/land', [MemberController::class, 'saveLandMember'])->name('saveLandMember');  //save plot to land_distribution table
Route::post('/fetchPlot', [MemberController::class, 'fetchPlot'])->name('fetchPlot'); //
Route::post('/fetchPlotByMember', [MemberController::class, 'fetchPlotByMember'])->name('fetchPlotByMember'); //

Route::post('/fetcMemberPhone', [MemberController::class, 'fetcMemberPhone'])->name('fetcMemberPhone'); //


// section
Route::get('/member-payment/showform', [MemberPaymentController::class, 'showMemberPayment'])->name('showMemberPayment'); //show payment form
Route::post('/member-payment/add', [MemberPaymentController::class, 'savePayment'])->name('savePayment'); //save payment for member


Route::post('/member-payment/get/member/payments', [MemberPaymentController::class, 'getMemberPayments'])->name('getMemberPayments');
Route::get('/member-payment/get/member/payment/edit', [MemberPaymentController::class, 'getMemberPaymentEdit'])->name('getMemberPaymentEdit')->middleware('signed');
//transaction/save payment data for plots

Route::get('payment/form', [MemberPaymentController::class, 'showPaymentform'])->name('showPaymentform');

//batch payment
Route::get('batch-payment', [MemberPaymentController::class, 'viewBatchPayment'])->name('viewBatchPayment');
Route::post('batch-payment-save', [MemberPaymentController::class, 'saveBatchPayments'])->name('saveBatchPayments');
// Agent
Route::get('/add-agent', [AgentController::class, 'showAgent'])->name('showAgent');  //show add agent form
Route::post('/agent/add', [AgentController::class, 'saveAgentData'])->name('saveAgentData');  ///save agent data
Route::get('/agent-land', [AgentController::class, 'ShowAgentLand'])->name('showAgentLand'); //
Route::post('/agent-payment/get/agent/payments', [AgentController::class, 'getAgentPayments'])->name('getAgentPayments');
Route::get('/agent-payment/get/agent/payment/edit', [AgentController::class, 'getAgentPaymentEdit'])->name('getAgentPaymentEdit')->middleware('signed');
Route::get('/agent-report', [AgentController::class, 'showAgentReportForm'])->name('showAgentReportForm');
// Route::get('/agent-report', [AgentController::class, 'showAgentReport'])->name('showAgentReport');
Route::get('/agent-payment-add', [AgentController::class, 'showAgentPaymentForm'])->name('showAgentPaymentForm');
Route::post('/agent-payment/save', [AgentController::class, 'saveAgentTransactions'])->name('saveAgentTransactions');
Route::post('/fetchPlotByAgent', [AgentController::class, 'fetchPlotByAgent'])->name('fetchPlotByAgent'); //


// Report

Route::get('/Land-report', [ReportController::class, 'showPaymentSummary'])->name('showPaymentSummary');
Route::post('/land-transaction/pdf', [ReportController::class, 'pdfLandStatement'])->name('pdfLandStatement');
Route::post('/land-export', [ReportController::class, 'LandStatementExcel'])->name('LandStatementExcel');
Route::get('/member-report', [ReportController::class, 'showMemberSummary'])->name('showMemberSummary');
Route::post('/member-export', [ReportController::class, 'MemberStatementExcel'])->name('MemberStatementExcel');
Route::post('/agent-export', [ReportController::class, 'AgentStatementExcel'])->name('AgentStatementExcel');

Route::post('/plots-report', [ReportController::class, 'PlotsReportExcel'])->name('PlotsReportExcel');



//pay consultants
Route::get('/pay-consultants', [PayConsultantController::class, 'payConsultants'])->name('consultant');
Route::post('/generate/invoice', [PayConsultantController::class, 'generateInvoice'])->name('generateInvoice');
Route::post('/make/payment', [PayConsultantController::class, 'makePayment'])->name('makePayment');
Route::get('callback', [PayConsultantController::class, 'callback'])->name('callback');


// Route::get('/a', function () {
//     return view('admin.a');
// });

// Route::get('/b', function () {
//     return view('admin.b');
// });
