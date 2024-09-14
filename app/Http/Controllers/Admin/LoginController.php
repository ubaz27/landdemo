<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;



class LoginController extends Controller
{
    //
    public function showLogin()
    {

        if (Auth::guard('admin')->check()) {
            return redirect()->route('admin.showDashboard');
        }

        return view('admin.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'userEmail' => 'required|email',
            'userPassword' => 'required',
        ]);

        $email = $request->userEmail;
        $password = $request->userPassword;
        $remember = $request->remember;

        // remember variable keep user loged in indefinetely until logged out
        if (Auth::guard('admin')->attempt(['email' => $email, 'password' => $password, 'is_active' => 1], $remember)) {
            // Authentication was successful...
            $request->session()->regenerate();
            return redirect()->intended(route('admin.showDashboard'));
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


    public function showChangePassword()
    {

        return view('admin.change-password');
    }

    public function saveChangePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string|min:6',
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);
        //dd(auth()->user->password);
        $currentPasswordStatus = Hash::check($request->current_password, Auth::guard('admin')->user()->password);

        if ($currentPasswordStatus) {
            Admin::findorFail(Auth::guard('admin')->user()->id)
                ->update([
                    'password' => Hash::make($request->password),
                ]);
            return redirect()->back()->with('message', 'Password Updated Successfully');
        } else {
            return redirect()->back()->with('message', 'Current Password does not match with Old Password');
        }
    }
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('admin.showLogin');
    }


    public function saveProfile(Request $request)
    {
        $request->validate([
            'phonenumber' => 'required',
        ]);

        if (strlen($request->phonenumber) == 11) {
            Admin::findorFail(Auth::guard('admin')->user()->id)
                ->update([
                    'phone' => ($request->phonenumber),
                ]);
            return back()->with('mssg', ['type' => 'primary', 'icon' => 'check', 'message' => 'Phone Number Updated Successfully']);
            // return redirect()->back()->with('message', 'Phone Number Updated Successfully');
        } else {
            return back()->with('mssg', ['type' => 'danger', 'icon' => 'check', 'message' => 'Phone Not  Updated Successfully']);
        }
    }
}
