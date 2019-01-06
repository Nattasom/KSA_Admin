<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MasterDataModel;
use App\Models\CoreModel;

class MasterController extends Controller
{
    private $model;
    private $core;
    public function __construct(){
        $this->model = new MasterDataModel();
        $this->core = new CoreModel();
    }

    public function insurerlist(Request $request){
        $status = "";
        if ($request->session()->exists('save')) {
            $status=$request->session()->get('save');
            $request->session()->forget('save');
        }
        $data["status"] = $status;
        return view("pages.insurerlist",$data);
    }
    public function insureradd(Request $request){
        $data["lang"] = $this->core->GetLangList();
        return view("pages.insureradd",$data);
    }
    public function actionInsurerAdd(Request $request){
        $data["status"] = "00";
        $data["message"] = "";
        $params = $request->input();
        $userInfo = $request->session()->get('userinfo');
        $params["username"] = $userInfo->Username;
        $resp = $this->model->InsurerAdd($params,$request->file('insurer_logo'));
        if($resp=="01"){
            $data["status"] = $resp;
            $data["message"] = "Save data successful";
            $request->session()->put('save', $data["status"]);
        }else{
            $data["status"] = $resp;
            $data["message"] = "Can not save data";
            if($resp=="03"){
                $data["message"] = "Insurer code is duplicate";
            }
        }
        return response()->json($data);
    }
    public function actionSetStatusInsurer(Request $request){
        $data["status"] = "00";
        $data["message"] = "";
        $resp = $this->model->InsurerSetStatus($request->input());
        if($resp){
            $data["status"] = "01";
        }
        return response()->json($data);
    }
    public function insureredit(Request $request,$id){
        $data["lang"] = $this->core->GetLangList();
        $obj = $this->model->GetInsurer($id);
        if(!array_key_exists("insurer_code",$obj)){
            return redirect("/master/insurer");
        }
        $data["resp"] = $obj;
        return view("pages.insureredit",$data);
    }
    public function actionInsurerEdit(Request $request){
        $data["status"] = "00";
        $data["message"] = "";
        $params = $request->input();
        $userInfo = $request->session()->get('userinfo');
        $params["username"] = $userInfo->Username;
        $resp = $this->model->InsurerEdit($params,$request->file('insurer_logo'));
        if($resp=="01"){
            $data["status"] = $resp;
            $data["message"] = "Save data successful";
            $request->session()->put('save', $data["status"]);
        }else{
            $data["status"] = $resp;
            $data["message"] = "Can not save data";
            if($resp=="03"){
                $data["message"] = "Insurer code is duplicate";
            }
        }
        return response()->json($data);
    }
    public function insurerDatatable(Request $request){
        $data = $this->model->getInsurerTable($request->input());
       
        return response()->json($data);
    }

    public function loadModelValue(Request $request){
        $data = $this->model->GetModelValue($request->input("makevalue"));
        return response()->json($data);
    }
}
