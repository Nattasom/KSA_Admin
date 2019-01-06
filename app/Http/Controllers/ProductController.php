<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProductModel;
use App\Models\CoreModel;
use App\Models\MasterDataModel;
use App\Models\PromotionModel;

class ProductController extends Controller
{
    private $model;
    private $core;
    private $master;
    private $promotion;
    public function __construct(){
        $this->model = new ProductModel();
        $this->core = new CoreModel();
        $this->master = new MasterDataModel();
        $this->promotion = new PromotionModel();
    }
    //
    public function index(){


        return view("pages.product");
    }
    public function productDatatable(Request $request){
        $data = $this->model->getProductTable($request->input());
       
        return response()->json($data);
    }
    public function actionSetStatus(Request $request){
        $data["status"] = "00";
        $data["message"] = "";
        $resp = $this->model->SetStatus($request->input());
        if($resp){
            $data["status"] = "01";
        }
        return response()->json($data);
    }
    public function add(){
        $data=array();
        $data["promotion_list"] = $this->promotion->GetPromotionList();
        $data["lang"] = $this->core->GetLangList();
        $data["insurers"] = $this->master->GetAllInsurer();
        $data["cars"] = $this->master->GetAllCarMakeValue();
        $data["product_type"] = $this->master->GetAllProductType();
        $data["claim_type"] = $this->master->GetAllClaimType();
        $data["car_group"] = $this->master->GetAllCarGroup();
        return view("pages.product-add",$data);
    }
    public function edit(Request $request,$id){
        $data=array();

        $obj = $this->model->GetProduct($id);
        if(!array_key_exists("product_id",$obj)){
            return redirect("/product");
        }
        $data["promotion_list"] = $this->promotion->GetPromotionList();
        $data["lang"] = $this->core->GetLangList();
        $data["resp"] = $obj;
        $data["insurers"] = $this->master->GetAllInsurer();
        $data["cars"] = $this->master->GetAllCarMakeValue();
        $data["product_type"] = $this->master->GetAllProductType();
        $data["claim_type"] = $this->master->GetAllClaimType();
        $data["car_group"] = $this->master->GetAllCarGroup();
        return view("pages.product-edit",$data);
    }
    public function actionAdd(Request $request){
        $data["status"] = "00";
        $data["message"] = "";
        $params = $request->input();
        $userInfo = $request->session()->get('userinfo');
        $params["username"] = $userInfo->Username;
        $resp = $this->model->ProductAdd($params);
        if($resp=="01"){
            $data["status"] = $resp;
            $data["message"] = "Save data successful";
            $request->session()->put('save', $data["status"]);
        }else{
            $data["status"] = $resp;
            $data["message"] = "Can not save data";
            if($resp=="03"){
                $data["message"] = "Product Name is duplicate";
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
        $resp = $this->model->ProductEdit($params);
        if($resp=="01"){
            $data["status"] = $resp;
            $data["message"] = "Save data successful";
            $request->session()->put('save', $data["status"]);
        }else{
            $data["status"] = $resp;
            $data["message"] = "Can not save data";
            if($resp=="03"){
                $data["message"] = "Product Name is duplicate";
            }
        }
        return response()->json($data);
    }
}
