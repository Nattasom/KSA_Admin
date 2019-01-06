<?php
namespace App\Http\Controllers\API;
use Illuminate\Http\Request; 
use App\Http\Controllers\Controller; 
use App\Models\API\ContentModel;

class ContentController extends Controller 
{
    public $successStatus = 200;
    private $model;
    public function __construct(){
        $this->model = new ContentModel();
    }
    public function testapi(){

        return response()->json(['success' => "TEST จ้า"], $this-> successStatus); 
    }
    public function getBanner(Request $request){
        $lang="";
        $resp = array();
        if(!is_null($request->input("lang"))){
            $lang = $request->input("lang");
            $resp = $this->model->GetSuggestBanner($lang);
        }else{
            $resp = $this->model->GetSuggestBanner();
        }
        
        return response()->json($resp, $this-> successStatus); 
    }
    public function getBannerSlider(Request $request){
        $lang="";
        $resp = array();
        if(!is_null($request->input("lang"))){
            $lang = $request->input("lang");
            $resp = $this->model->GetSliderBannerList($lang);
        }else{
            $resp = $this->model->GetSliderBannerList();
        }
        
        return response()->json($resp, $this-> successStatus); 
    }
    public function getHomecat(Request $request){
        $lang="";
        $resp = array();
        if(!is_null($request->input("lang"))){
            $lang = $request->input("lang");
            $resp = $this->model->GetHomeCategories($lang);
        }else{
            $resp = $this->model->GetHomeCategories();
        }
        
        return response()->json($resp, $this-> successStatus); 
    }
    public function getHomecatDetail(Request $request){
        $lang="th";
        if(!is_null($request->input("lang"))){
            $lang = $request->input("lang");
        }
        $resp = $this->model->GetHomeCatDetail($request->input("cat"),$request->input("idx"),$lang);
        return response()->json($resp, $this-> successStatus); 
    }
    public function getHomecatList(Request $request){
        //check val 
        if(is_null($request->input("cat_id"))){
            return response()->json(["fail"=>"cat_id not found"], $this-> successStatus); 
        }
        $lang="th";
        $start = 0;
        $length = 6;
        $cat_id = $request->input("cat_id");
        if(!is_null($request->input("lang"))){
            $lang = $request->input("lang");
        }
        if(!is_null($request->input("start"))){
            $start = $request->input("start");
        }
        if(!is_null($request->input("length"))){
            $length = $request->input("length");
        }
        $resp = $this->model->GetHomeCatProductList($cat_id,$lang,$start,$length);
        
        return response()->json($resp, $this-> successStatus); 
    }
}

?>