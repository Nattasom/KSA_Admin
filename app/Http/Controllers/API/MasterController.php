<?php
namespace App\Http\Controllers\API;
use Illuminate\Http\Request; 
use App\Http\Controllers\Controller; 
use App\Models\API\MasterModel;

class MasterController extends Controller 
{
    public $successStatus = 200;
    private $model;
    public function __construct(){
        $this->model = new MasterModel();
    }
    public function getInsurerList(Request $request){
        $lang="th";
        $resp = array();
        if(!is_null($request->input("lang"))){
            $lang = $request->input("lang");
        }
        $resp = $this->model->GetInsurerList($lang);
        
        return response()->json($resp, $this-> successStatus); 
    }
    public function getMakeValueList(Request $request){
        $resp = array();
        $resp = $this->model->GetAllCarMakeValue();
        
        return response()->json($resp, $this-> successStatus); 
    }
    public function getModelValueList(Request $request){
        //check val 
        if(is_null($request->input("make_value"))){
            return response()->json(["fail"=>"make_value not found"], $this-> successStatus); 
        }
        $resp = array();
        $resp = $this->model->GetModelValue($request->input("make_value"));
        
        return response()->json($resp, $this-> successStatus); 
    }
    public function getModelYearList(Request $request){
        //check val 
        if(is_null($request->input("model_value"))){
            return response()->json(["fail"=>"model_value not found"], $this-> successStatus); 
        }
        $resp = array();
        $resp = $this->model->GetModelYear($request->input("model_value"));
        
        return response()->json($resp, $this-> successStatus); 
    }
    public function getClaimType(Request $request){
        $resp = array();
        $resp = $this->model->GetClaimType();
        return response()->json($resp, $this-> successStatus); 
    }
    public function getSperatePayList(Request $request){
        $lang="th";
        $resp = array();
        if(!is_null($request->input("lang"))){
            $lang = $request->input("lang");
        }
        $resp = $this->model->GetSperatePayList($lang);
        return response()->json($resp, $this-> successStatus); 
    }
}

?>