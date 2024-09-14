<?php

use GuzzleHttp\Middleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Member\DashboardController;
use App\Http\Controllers\Member\MemberController;
use App\Http\Controllers\Member\PaymentController;
use App\Http\Controllers\Member\PlotController;
use App\Http\Controllers\Member\ReportController;
use App\Http\Controllers\Member\LoginController;
use App\Http\Controllers\Member\ProfileController;
use App\Http\Controllers\Member\PaystackController;
use app\http\Controllers\Member\ChangePasswordController;


//login
Route::get('/', [LoginController::class, 'showLogin'])->name('showLogin');
Route::get('/login', [LoginController::class, 'showLogin'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login');


//change password
Route::get('/change-password', [LoginController::class, 'showChangePassword'])->name('showChangePassword');
Route::post('/change-password', [LoginController::class, 'saveChangePassword'])->name('saveChangePassword');


//dashboard controller
Route::get('/dashboard', [DashboardController::class, 'showDashboard'])->name('showDashboard');

//profile
Route::get('/profile', [ProfileController::class, 'showProfile'])->name('profile');
Route::post('/save-profile', [ProfileController::class, 'saveProfile'])->name('saveProfile');

//lands/plots
Route::get('/plots-view', [PlotController::class, 'showPlotsView'])->name('showPlotsView');
Route::get('/payment/history', [PaymentController::class, 'showPaymentHistory'])->name('showPaymentHistory');
Route::get('/available-plots', [PlotController::class, 'showAvailablePlots'])->name('showAvailablePlots');
Route::post('/book-plots', [PaymentController::class, 'showBooking'])->name('showBooking');
Route::post('/invoice/booking', [PaymentController::class, 'generateInvoiceBooking'])->name('generateInvoiceBooking');

//payment
Route::get('/show/payment', [PaymentController::class, 'showMakePayment'])->name('showMakePayment');
Route::get('/invoice/payment', [PaymentController::class, 'showGenerateInvoice'])->name('showGenerateInvoice');
Route::post('/generate/invoice', [PaymentController::class, 'generateInvoice'])->name('generateInvoice');

Route::post('/make/payment', [PaymentController::class, 'makePayment'])->name('makePayment');
Route::post('/make-booking/payment', [PaymentController::class, 'makeBookingPayment'])->name('makeBookingPayment');
Route::get('/view-invoice/booking', [PaymentController::class, 'viewInvoicebBooking'])->name('viewInvoicebBooking');


//paystack payment
Route::get('callback', [PaystackController::class, 'callback'])->name('callback');
Route::get('success', [PaystackController::class, 'success'])->name('success');
Route::get('cancel', [PaystackController::class, 'cancelled'])->name('cancelled');

//paystack payment for booked land
Route::get('callback2', [PaystackController::class, 'callback2'])->name('callback2');

Route::get('/a', [PaymentController::class, 'payment'])->name('payment');
//reports

Route::get('/report/payment', [ReportController::class, 'showReportPayment'])->name('showReportPayment');
Route::post('/member-export', [ReportController::class, 'MemberStatementExcel'])->name('MemberStatementExcel');


//logout
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');
