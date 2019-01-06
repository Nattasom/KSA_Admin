<?php
namespace App\Http\Controllers\API;
use Illuminate\Http\Request; 
use App\Http\Controllers\Controller; 
use App\Models\API\PremiumModel;

class PremiumController extends Controller 
{
    public $successStatus = 200;
    private $model;
    public function __construct(){
        $this->model = new PremiumModel();
    }
    public function getMinMaxPremium(){
        $resp = $this->model->GetMinMaxPremium();
        return response()->json($resp, $this-> successStatus); 
    }
    public function getMinMaxSumInsured(){
        $resp = $this->model->GetMinMaxSumInsured();
        return response()->json($resp, $this-> successStatus); 
    }
    public function getMinMaxTPPD(){
        $resp = $this->model->GetMinMaxTPPD();
        return response()->json($resp, $this-> successStatus); 
    }
    public function sendDroplead(Request $request){
        //check val 
        if(is_null($request->input("name"))){
            return response()->json(["fail"=>"name not found"], $this-> successStatus); 
        }
        if(is_null($request->input("tel"))){
            return response()->json(["fail"=>"tel not found"], $this-> successStatus); 
        }
        $response = array();
        $resp = $this->model->saveDroplead($request->input());

        return response()->json(["status"=>$resp], $this-> successStatus); 
    }
}