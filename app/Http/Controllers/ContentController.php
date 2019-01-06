<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ContentModel;
use App\Models\PromotionModel;
use App\Models\CoreModel;
use App\Models\MasterDataModel;


class ContentController extends Controller
{
    //
    private $model;
    private $core;
    private $master;
    private $promotion;
    public function __construct(){
        $this->model = new ContentModel();
        $this->core = new CoreModel();
        $this->master = new MasterDataModel();
        $this->promotion = new PromotionModel();
    }
    public function banner(){
        $data["lang"] = $this->core->GetLangList();
        $data["resp"] = $this->model->GetSuggestBanner();
        return view("pages.banner",$data);
    }
    public function bannerSlider(){
        $data["lang"] = $this->core->GetLangList();
        $data["resp"] = $this->model->GetSuggestBanner();
        return view("pages.banner-slider",$data);
    }
    public function bannerSliderDatatable(Request $request){
        $data = $this->model->getBannerSliderTable($request->input());
       
        return response()->json($data);
    }
    public function bannerSliderAdd(){
        $data["lang"] = $this->core->GetLangList();
        return view("pages.banner-slider-add",$data);
    }
    public function bannerSliderEdit($id){
        $data["lang"] = $this->core->GetLangList();
        $data["resp"] = $this->model->GetSliderBanner($id);
        return view("pages.banner-slider-edit",$data);
    }
    public function actionBannerSliderAdd(Request $request){
        $data["status"] = "00";
        $data["message"] = "";
        $params = $request->input();
        $userInfo = $request->session()->get('userinfo');
        $params["username"] = $userInfo->Username;
        $resp = $this->model->BannerSliderAdd($params,$request->file());
        if($resp=="01"){
            $data["status"] = $resp;
            $data["message"] = "Save data successful";
            $request->session()->put('save', $data["status"]);
        }else{
            $data["status"] = $resp;
            $data["message"] = "Can not save data";
            // if($resp=="03"){
            //     $data["message"] = "Insurer code is duplicate";
            // }
        }
        return response()->json($data);
    }
    public function actionBannerSliderEdit(Request $request){
        $data["status"] = "00";
        $data["message"] = "";
        $params = $request->input();
        $userInfo = $request->session()->get('userinfo');
        $params["username"] = $userInfo->Username;
        $resp = $this->model->BannerSliderEdit($params,$request->file());
        if($resp=="01"){
            $data["status"] = $resp;
            $data["message"] = "Save data successful";
        }else{
            $data["status"] = $resp;
            $data["message"] = "Can not save data";
        }
        return response()->json($data);
    }
    public function actionBannerSliderStatus(Request $request){
        $data["status"] = "00";
        $data["message"] = "";
        $resp = $this->model->BannerSliderSetStatus($request->input());
        if($resp){
            $data["status"] = "01";
        }
        return response()->json($data);
    }
    public function actionBannerEdit(Request $request){
        $data["status"] = "00";
        $data["message"] = "";
        $params = $request->input();
        $userInfo = $request->session()->get('userinfo');
        $params["username"] = $userInfo->Username;
        $resp = $this->model->BannerEdit($params,$request->file());
        if($resp=="01"){
            $data["status"] = $resp;
            $data["message"] = "Save data successful";
            $request->session()->put('save', $data["status"]);
        }else{
            $data["status"] = $resp;
            $data["message"] = "Can not save data";
            // if($resp=="03"){
            //     $data["message"] = "Insurer code is duplicate";
            // }
        }
        return response()->json($data);
    }
    public function homecat(Request $request){
        $status = "";
        if ($request->session()->exists('save')) {
            $status=$request->session()->get('save');
            $request->session()->forget('save');
        }
        $data["status"] = $status;
        return view("pages.homecat",$data);
    }
    public function homecatAdd(){
        $data["lang"] = $this->core->GetLangList();
        return view("pages.homecat-add",$data);
    }
    public function homecatEdit(Request $request,$id){
        $data["lang"] = $this->core->GetLangList();
        $obj = $this->model->GetHomecat($id);
        if(!array_key_exists("id",$obj)){
            return redirect("/homecat");
        }
        $data["resp"] = $obj;
        return view("pages.homecat-edit",$data);
    }
    public function homecatProductEdit(Request $request,$id){
        $data["lang"] = $this->core->GetLangList();
        $obj = $this->model->GetHomecatProduct($id);
        if(!array_key_exists("id",$obj)){
            return back();
        }
        $data["promotion_list"] = $this->promotion->GetPromotionList();
        $data["resp"] = $obj;
        return view("pages.homecat-product-edit",$data);
    }
    public function homecatProduct(Request $request,$id){
        $obj = $this->model->GetHomecat($id);
        if(!array_key_exists("id",$obj)){
            return redirect("/homecat");
        }
        $data["resp"] = $obj;
        $data["selected_list"] = $this->model->GetHomeCatProductList($id);
        $data["insurers"] = $this->master->GetAllInsurer();
        $data["cars"] = $this->master->GetAllCarMakeValue();
        $data["product_type"] = $this->master->GetAllProductType();
        $data["claim_type"] = $this->master->GetAllClaimType();
        $data["car_group"] = $this->master->GetAllCarGroup();
        return view("pages.homecat-product",$data);
    }
    public function homecatProductList(Request $request,$id){
        $obj = $this->model->GetHomecat($id);
        if(!array_key_exists("id",$obj)){
            return redirect("/homecat");
        }
        $data["resp"] = $obj;
        return view("pages.homecat-product-list",$data);
    }
    public function actionHomecatAdd(Request $request){
        $data["status"] = "00";
        $data["message"] = "";
        $params = $request->input();
        $userInfo = $request->session()->get('userinfo');
        $params["username"] = $userInfo->Username;
        $resp = $this->model->HomecatAdd($params,$request->file('cat_ico'));
        if($resp=="01"){
            $data["status"] = $resp;
            $data["message"] = "Save data successful";
            $request->session()->put('save', $data["status"]);
        }else{
            $data["status"] = $resp;
            $data["message"] = "Can not save data";
            // if($resp=="03"){
            //     $data["message"] = "Insurer code is duplicate";
            // }
        }
        return response()->json($data);
    }
    public function actionHomecatProduct(Request $request){
        $data["status"] = "00";
        $data["message"] = "";
        $params = $request->input();
        $userInfo = $request->session()->get('userinfo');
        $params["username"] = $userInfo->Username;
        $resp = $this->model->HomecatProductEdit($params);
        if($resp=="01"){
            $data["status"] = $resp;
            $data["message"] = "Save data successful";
            // $request->session()->put('save', $data["status"]);
        }else{
            $data["status"] = $resp;
            $data["message"] = "Can not save data";
            // if($resp=="03"){
            //     $data["message"] = "Insurer code is duplicate";
            // }
        }
        return response()->json($data);
    }
    public function actionHomecatEdit(Request $request){
        $data["status"] = "00";
        $data["message"] = "";
        $params = $request->input();
        $userInfo = $request->session()->get('userinfo');
        $params["username"] = $userInfo->Username;
        $resp = $this->model->HomecatEdit($params,$request->file('cat_ico'));
        if($resp=="01"){
            $data["status"] = $resp;
            $data["message"] = "Save data successful";
            $request->session()->put('save', $data["status"]);
        }else{
            $data["status"] = $resp;
            $data["message"] = "Can not save data";
            // if($resp=="03"){
            //     $data["message"] = "Insurer code is duplicate";
            // }
        }
        return response()->json($data);
    }
    public function actionHomecatProductEdit(Request $request){
        $data["status"] = "00";
        $data["message"] = "";
        $params = $request->input();
        $userInfo = $request->session()->get('userinfo');
        $params["username"] = $userInfo->Username;
        $resp = $this->model->HomecatProductDetailEdit($params,$request->file('product_image'));
        if($resp=="01"){
            $data["status"] = $resp;
            $data["message"] = "Save data successful";
        }else{
            $data["status"] = $resp;
            $data["message"] = "Can not save data";
        }
        return response()->json($data);
    }
    public function homecatDatatable(Request $request){
        $data = $this->model->getHomeCatTable($request->input());
       
        return response()->json($data);
    }
    public function homecatProductListDatatable(Request $request){
        $data = $this->model->getHomeCatProductListTable($request->input());
       
        return response()->json($data);
    }
    public function homecatSetStatus(Request $request){
        $data["status"] = "00";
        $data["message"] = "";
        $resp = $this->model->HomeCatSetStatus($request->input());
        if($resp){
            $data["status"] = "01";
        }
        return response()->json($data);
    }
}
