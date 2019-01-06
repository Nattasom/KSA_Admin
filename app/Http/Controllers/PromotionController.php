<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CoreModel;
use App\Models\PromotionModel;

class PromotionController extends Controller
{
    //
    private $model;
    private $core;
    public function __construct(){
        $this->model = new PromotionModel();
        $this->core = new CoreModel();
    }
    public function index(){

        return view("pages.promotion");
    }
    public function add(Request $request){

        return view("pages.promotion-add");
    }
    public function edit(Request $request,$id){
        $obj = $this->model->GetPromotion($id);
        if(!array_key_exists("promotion_code",$obj)){
            return redirect("/promotion");
        }
        $data["resp"] = $obj;
        return view("pages.promotion-edit",$data);
    }
    public function actionAdd(Request $request){
        $data["status"] = "00";
        $data["message"] = "";
        $params = $request->input();
        $userInfo = $request->session()->get('userinfo');
        $params["username"] = $userInfo->Username;
        $resp = $this->model->PromotionAdd($params,$request->file('promotion_tag'));
        if($resp=="01"){
            $data["status"] = $resp;
            $data["message"] = "Save data successful";
            $request->session()->put('save', $data["status"]);
        }else{
            $data["status"] = $resp;
            $data["message"] = "Can not save data";
            if($resp=="03"){
                $data["message"] = "Promotion code is duplicate";
            }
        }
        return response()->json($data);
    }
    public function actionEdit(Request $request){
        $data["status"] = "00";
        $data["message"] = "";
        $params = $request->input();
        $userInfo = $request->session()->get('userinfo');
        $params["username"] = $userInfo->Username;
        $resp = $this->model->PromotionEdit($params,$request->file('promotion_tag'));
        if($resp=="01"){
            $data["status"] = $resp;
            $data["message"] = "Save data successful";
            $request->session()->put('save', $data["status"]);
        }else{
            $data["status"] = $resp;
            $data["message"] = "Can not save data";
            if($resp=="03"){
                $data["message"] = "Promotion code is duplicate";
            }
        }
        return response()->json($data);
    }
    public function promotionDatatable(Request $request){
        $data = $this->model->getPromotionTable($request->input());
       
        return response()->json($data);
    }
    public function actionStatus(Request $request){
        $data["status"] = "00";
        $data["message"] = "";
        $resp = $this->model->SetStatus($request->input());
        if($resp){
            $data["status"] = "01";
        }
        return response()->json($data);
    }
}
