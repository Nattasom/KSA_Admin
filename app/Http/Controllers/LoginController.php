<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserModel;

class LoginController extends Controller
{
    private $userModel;
    function __construct(){
        $this->userModel = new UserModel();
    }
    public function index(Request $request){
        $data["status"]="";
        if ($request->session()->exists('loginStatus')) {
            // user value cannot be found in session
            if($request->session()->get("loginStatus")=="fail"){
                $data["status"] = "02";
                $request->session()->forget('loginStatus');
            }
        }
        return view("login",$data);
    }
    public function login(Request $request){
        $username = $request->input("username");
        $password = $request->input("password");
        $resUser = $this->userModel->login($username,$password);
        if($resUser["status"]=="01"){
             $request->session()->put('userinfo', $resUser["user"]);
             return redirect("/dashboard");
        }else{
            $request->session()->put('loginStatus', "fail");
            return redirect("/login");
        }
        
    }
    public function logout(Request $request){
        $request->session()->forget('userinfo');
        return redirect("/login");
    }
}
