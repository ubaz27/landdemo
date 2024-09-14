<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    function __construct()
    {
        $this->middleware('auth:admin');
    }
    //
    public function showUsers()
    {
        $users = User::all();
        return view('admin.user.user-page', compact('users'));
    }


    public function saveUser(Request $request)
    {
        $request->validate([
            'phone' => 'required',
            'fullname' => 'required',
            'email' => 'required',
            'password' => 'required',

        ]);

        User::Create([
            'name' => $request->fullname,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
        ]);

        return back()->with('mssg', ['type' => 'success', 'icon' => 'check', 'message' => 'Member Info. Inserted']);
    }

    public function editUser(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
        ]);

        $users =  User::find($request->user_id);
        // dd($users);
        return view('admin.user.edit-user', compact('users'));
    }

    public function saveEditUser(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'phone' => 'required',
            'email' => 'required',
            'fullname' => 'required',

            'is_active' => 'required',
        ]);

        $user = User::find($request->user_id);
        $user->email = $request->email;
        $user->name = $request->fullname;
        if (!empty($request->password)) {
            $user->password = Hash::make($request->password);
            $user->reset_password = 0;
        }
        $user->is_active = $request->is_active;
        $user->save();
        $users = User::all();
        return view('admin.user.user-page', compact('users'));
        // return back()->with('mssg', ['type' => 'success', 'icon' => 'check', 'message' => 'Member Info. Inserted']);
    }
}
