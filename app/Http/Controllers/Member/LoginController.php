<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Member;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class LoginController extends Controller
{
    // //
    public function showLogin()
    {
        if (Auth::guard('member')->check()) {
            return redirect()->route('member.showDashboard');
        }

        return view('member.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'userPhone' => 'required',
            'userPassword' => 'required',
        ]);

        $phone = $request->userPhone;
        $password = $request->userPassword;
        $remember = $request->remember;

        // remember variable keep user loged in indefinetely until logged out
        if (Auth::guard('member')->attempt(['phone' => $phone, 'password' => $password, 'is_active' => 1], $remember)) {
            // Authentication was successful...
            $request->session()->regenerate();
            return redirect()->intended(route('member.showDashboard'));
        }

        return back()->with('error', 'Wrong Password or Username');
        // or below code

        // $credentials = [
        //     'email' => $request->userEmail,
        //     'password' => $request->userPassword,
        // ];

        // if (Auth::guard('admin')->attempt($credentials)) {
        //     // $request->session()->regenerate();
        //     // return redirect('admin/dashboard')->with('success', 'Login Success');
        //     // either redirect
        //     return redirect()->intended(route('admin.showDashboard'));
        // }




        // return back()->withErrors([
        //     'email' => 'The provided credentials do not match our records.',
        // ])->onlyInput('userEmail');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerate();
        return redirect()->route('member.login');
    }


    public function showChangePassword()
    {

        return view('member.change-password');
    }

    public function saveChangePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string|min:6',
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);
        //dd(auth()->user->password);
        $currentPasswordStatus = Hash::check($request->current_password, Auth::guard('member')->user()->password);

        if ($currentPasswordStatus) {
            Member::findorFail(Auth::guard('member')->user()->id)
                ->update([
                    'password' => Hash::make($request->password),
                ]);
            return redirect()->back()->with('message', 'Password Updated Successfully');
        } else {
            return redirect()->back()->with('message', 'Current Password does not match with Old Password');
        }
    }
}
