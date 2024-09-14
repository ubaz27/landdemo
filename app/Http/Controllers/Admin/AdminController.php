<?php

namespace App\Http\Controllers\Admin;

use Throwable;
use App\Models\Admin;
use App\Models\Position;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use \Illuminate\Support\Facades\URL;
use App\Http\Controllers\Controller;
use App\Models\Member;
use Illuminate\Support\Facades\Hash;


class AdminController extends Controller
{
    //
    function __construct()
    {
        $this->middleware('auth:admin');
    }
    public function showAdmins()
    {
        // $users = Admin::all();
        $users = DB::table('admins')
            ->where('position_id', '>', 1)
            ->get();

        return view('admin.admin.admin-page', compact('users'));
    }
    public function saveAdmin(Request $request)
    {
        $request->validate([
            // 'phone' => 'required',
            'fullname' => 'required',
            'email' => 'required',
            'password' => 'required',

        ]);

        Admin::Create([
            'name' => $request->fullname,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return back()->with('mssg', ['type' => 'success', 'icon' => 'check', 'message' => 'Member Info. Inserted']);
    }

    public function editAdmin(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
        ]);

        $users =  Admin::find($request->user_id);
        // dd($users);
        return view('admin.admin.edit-admin', compact('users'));
    }

    public function saveEditAdmin(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'email' => 'required',
            'fullname' => 'required',
            'is_active' => 'required',
        ]);

        $user = Admin::find($request->user_id);
        $user->email = $request->email;
        $user->name = $request->fullname;
        if (!empty($request->password)) {
            $user->password = Hash::make($request->password);
            $user->reset_password = 1;
        }
        $user->is_active = $request->is_active;
        $user->save();
        $users = DB::table('admins')
            ->where('position_id', '>', 1)
            ->get();
        return view('admin.admin.admin-page', compact('users'));
        // return back()->with('mssg', ['type' => 'success', 'icon' => 'check', 'message' => 'Member Info. Inserted']);
    }
}
