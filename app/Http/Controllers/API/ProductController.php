<?php
namespace App\Http\Controllers\API;
use Illuminate\Http\Request; 
use App\Http\Controllers\Controller; 
use App\Models\API\ProductModel;

class ProductController extends Controller 
{
    private $successStatus = 200;
    private $model;
    public function __construct(){
        $this->model = new ProductModel();
    }
    public function productlist(Request $request){
        $response = array();
        $lang="th";
        $start = 0;
        $length = 6;
        if(!is_null($request->input("lang"))){
            $lang = $request->input("lang");
        }
        if(!is_null($request->input("start"))){
            $start = $request->input("start");
        }
        if(!is_null($request->input("length"))){
            $length = $request->input("length");
        }
        $response = $this->model->GetProductList($request->input(),$lang,$start,$length);
        
        return response()->json($response, $this-> successStatus); 
    }
    public function productDetail(Request $request){
        $response = array();
        $id = "";
        $lang="th";
         if(is_null($request->input("idx"))){
            return response()->json(["fail"=>"ID not found"], $this-> successStatus); 
        }
        if(!is_null($request->input("lang"))){
            $lang = $request->input("lang");
        }
        
        $response = $this->model->GetProduct($request->input("idx"),$lang);
        
        return response()->json($response, $this-> successStatus); 
    }
}

?>