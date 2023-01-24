<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use AApp\Models\User;

class AdminDashBoardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {
        return view('admin.dashBoard');
    }

    public function logout()
    {   
        Auth::logout();
        return redirect('/admin/login')->with('status','User has been logged out!');
    }
}
