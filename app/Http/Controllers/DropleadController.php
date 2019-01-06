<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DropleadModel;
use App\Models\CoreModel;
use Illuminate\Support\Facades\DB;
//excel
use App\Models\Exports\DropleadExport;
use Maatwebsite\Excel\Facades\Excel;

class DropleadController extends Controller
{
    private $model;
    private $core;
    public function __construct(){
        $this->model = new DropleadModel();
        $this->core = new CoreModel();
    }
    public function index(){

        return view("pages.droplead");
    }
    public function dropleadDatatable(Request $request){
        $data = $this->model->getDropleadTable($request->input());
       
        return response()->json($data);
    }
    public function getDataDroplead(Request $request){
        $data = array();
        if(!is_null($request->input("idx"))){
            $data = $this->model->getDroplead($request->input('idx'));
        }
        return response()->json($data);
    }
    public function export(Request $request){
        $sql = "select *
         from vwt_droplead ";
        $list = DB::select($sql);
        $collection = collect($list);
        $first = $collection->first();
        // $keys = collect($list)->keys();
        $headings = array();
        if(count($list)>0){
             $headings = array_keys((array)$list[0]);
        }
        return Excel::download(new DropleadExport($collection,$headings), 'droplead.xlsx');
    }
}
