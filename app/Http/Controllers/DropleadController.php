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
         from vwt_droplead Where 1=1 ";
         $sqlUpdate = "UPDATE tts_droplead SET ExportCount = ExportCount + 1 WHERE 1=1 ";
         $where = array();
         $prefix_file = "ALL";
         if(!empty($request->input("export_type"))){
            $sql .=" AND DropType = :export_type";
            $sqlUpdate .=" AND DropType = :export_type";
            $where["export_type"] = $request->input("export_type");
            $prefix_file = $request->input("export_type");
        }
         if(!empty($request->input("drop_status"))){
            $sql .=" AND Status = :drop_stat";
            $sqlUpdate .=" AND Status = :drop_stat";
            $where["drop_stat"] = $request->input("drop_status");
        }
        if(!empty($request->input("start_date"))){
            $stDate = $this->core->ConvertToSystemDate($request->input("start_date"));
            $sql .=" AND DropDate >= :startdate";
            $sqlUpdate .=" AND DropDate >= :startdate";
            $where["startdate"] = $stDate;
        }
        if(!empty($request->input("end_date"))){
            $nDate = $this->core->ConvertToSystemDate($request->input("end_date"));
            $sql .=" AND DropDate <= :enddate";
            $sqlUpdate .=" AND DropDate <= :enddate";
            $where["enddate"] = $nDate;
        }
        $list = DB::select($sql,$where);
        $collection = collect($list);
        $collection->transform(function($i) {
            unset($i->Status);
            unset($i->DropDate);
            unset($i->DropType);
            return $i;
        });
        //$first = $collection->first();
        // $keys = collect($list)->keys();
        $headings = array();
        if(count($list)>0){
             $headings = array_keys((array)$list[0]);
        }
        DB::update($sqlUpdate,$where);
        return Excel::download(new DropleadExport($collection,$headings), $prefix_file.'_droplead_'.date("Ymd").'.xlsx');
    }
}
