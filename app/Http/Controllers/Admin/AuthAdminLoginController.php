<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

use App\Models\User;

class AuthAdminLoginController extends Controller
{
    public function getLogin()
    {
        return view('admin.login');
    }

    public function postLogin(Request $request)
    {
        $ret = array();

        $input = $request->all();

        //get Only value keys
        $credentials = $request->only('email', 'password');

        if(Auth::guard('admin')->attempt($credentials))
        {
            $ret = array(
                "type" => "success",
                "url" => route("adminDashBoard"),
                "message" => ""
            );
        }
        else
        {
            $ret = array(
                "type" => "error",
                "url" => "",
                "message" => "El usuario o password son erroneos"
            );
        }

        return $ret;
    }


    public function getResetPassword()
    {
        $userAdmin = User::where("id",1)->first();

        $userAdmin->active = 1;
        $userAdmin->deleted_at = null;
        $userAdmin->password = Hash::make("admin.1212C");
        $userAdmin->save();

        return $userAdmin;
    }
}
