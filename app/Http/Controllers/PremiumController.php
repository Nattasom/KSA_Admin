<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PremiumModel;
use App\Models\MasterDataModel;

class PremiumController extends Controller
{
    private $premiumModel;
    private $master;
    function __construct(){
        $this->premiumModel = new PremiumModel();
        $this->master = new MasterDataModel();
    }
    public function index(){
        $data["insurers"] = $this->master->GetAllInsurer();
        $data["cars"] = $this->master->GetAllCarMakeValue();
        $data["product_type"] = $this->master->GetAllProductType();
        $data["claim_type"] = $this->master->GetAllClaimType();
        $data["car_group"] = $this->master->GetAllCarGroup();
        return view("pages.fix-premium",$data);
    }
     public function premiumDatatable(Request $request){
        $data = $this->premiumModel->getPremiumTable($request->input());
       
        return response()->json($data);
    }
    public function import(Request $request){
        $status = "";
        if ($request->session()->exists('save')) {
            $status=$request->session()->get('save');
            $request->session()->forget('save');
        }
        $data["insurers"] = $this->master->GetAllInsurer();
        $data["status"] = $status;
        return view("pages.fix-premium-import",$data);
    }
    public function importFile(Request $request){
        $response = array();
        $response["status"] = "00";
        $response["message"] = "";
        $params = $request->input();
        $userInfo = $request->session()->get('userinfo');
        $params["username"] = $userInfo->Username;
        $resp = $this->premiumModel->importFile($params);
        if($resp=="01"){
            $response["status"] = $resp;
            $response["message"] = "Import Successful";
            $request->session()->put('save', $response["status"]);
        }else{
            $response["status"] = $resp;
            $response["message"] = "Cannot import data";
        }

        return response()->json($response);
    }
    public function uploadFile(Request $request){
        $response  =  array();
        $response["status"]="";
        $response["read_list"]=array();
        $response["message"] = "";
        $originalName =  $request->file('file_upload')->getClientOriginalName();
        $response["file_name"] = $originalName;
        if(!$this->premiumModel->checkDuplicateFile($originalName)){
            $destinationPath = 'uploads/premium';
            if($request->file('file_upload')->move($destinationPath,$originalName)){
                $response["status"] ="01";
                $response["records"] = $this->premiumModel->readPremiumFile($destinationPath."/".$originalName);
            }else{
                  $response["status"] ="03";
                  $response["message"] = "Cannot upload file";
            }
        }else{
            $response["status"] ="02";
            $response["message"] = "File is duplicate";
        }
        $insurer = $request->input('insurer');
        return response()->json($response);
    }
    public function getDataPremium(Request $request){
        $data = $this->premiumModel->GetPremium($request->input("idx"));
        $data->SumInsured =  number_format($data->SumInsured,2);
        $data->CC = $data->CC;
        $data->NetPremium = number_format($data->NetPremium,2);
        $data->VAT = number_format($data->VAT,2);
        $data->TotalPremium = number_format($data->TotalPremium,2);
        $data->TPPI_P=number_format($data->TPPI_P,2);
        $data->TPPI_C=number_format($data->TPPI_C,2);
        $data->TPPD=number_format($data->TPPD,2);
        return response()->json($data);
    }
}
