<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Member;
use App\Models\Lga;
use Throwable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;


class ProfileController extends Controller
{
    //
    protected $member;
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->member = Auth::guard('member')->user()->id;
            return $next($request);
        });
    }
    public function showProfile()
    {

        $id = Auth::guard('member')->user()->id;
        $phone = Auth::guard('member')->user()->phone;
        // dd($phone);



        $lgas = Lga::all();
        $member = Member::find($id);
        // dd($member);
        return view('member.profile', compact('member', 'lgas',));
    }
    public function saveProfile(Request $request)
    {
        $id = Auth::guard('member')->user()->id;
        $request->validate([
            'email' => 'required',
            'lga' => 'required',
            'nok' => 'required',
            'nok_phone' => 'required',
            'address' => 'required',
        ]);

        try {

            $member = Member::find($id);
            // $member = Member::where('phone', '08027391950');
            $member->email = $request->email;
            $member->lga = $request->lga;
            $member->address = $request->address;
            $member->nok = $request->nok;
            $member->nok_phone = $request->nok_phone;
            $member->save();
            return back()->with('message', 'Profile Updated Successfully');
            // return back()->with('message', ['message' => 'Profile Updated Successfully']);
        } catch (Throwable $e) {
            DB::rollback();
            Log::error($e);
            if (env('APP_ENV') == 'local')
                return back()->with('error', $e->getMessage());
            return back()->with('error', 'Profile Not Updated Successfully');
            // return back()->with('error', ['error' => 'Profile Not Update Successfully']);
        }
    }
}
