<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use App\Models\CoreModel;

class DropleadModel 
{
    private $core;
    public function __construct(){
        $this->core = new CoreModel();
    }
   public function getDropleadTable($resp = array()){
    $where = array();
       $data = array();
        $rowsTotal = 10;
        $searchText = $resp["search"]["value"];
        $startDate = $resp["start_date"];
        $endDate = $resp["end_date"];
        $dropStat = $resp["drop_status"];
        $sqlCount = "SELECT Count(*) as cc FROM tts_droplead a  WHERE 1=1 ";
        $sql = "SELECT * FROM tts_droplead a   WHERE 1=1";
        if(!empty($searchText)){
            $sql .=" AND (a.TFirstName LIKE :search OR a.TLastName LIKE :search1 OR a.LicencePlateNo LIKE :search2)";
            $sqlCount .=" AND (a.TFirstName LIKE :search OR a.TLastName LIKE :search1 OR a.LicencePlateNo LIKE :search2)";
            $where["search"] = "%".$searchText."%";
            $where["search1"] = "%".$searchText."%";
            $where["search2"] = "%".$searchText."%";
        }
        if(!empty($dropStat)){
            $sql .=" AND a.Status = :drop_stat";
            $sqlCount .=" AND a.Status = :drop_stat";
            $where["drop_stat"] = $dropStat;
        }
        if(!empty($startDate)){
            $stDate = $this->core->ConvertToSystemDate($startDate);
            $sql .=" AND a.DropDate >= :startdate";
            $sqlCount .=" AND a.DropDate >= :startdate";
            $where["startdate"] = $stDate;
        }
        if(!empty($endDate)){
            $nDate = $this->core->ConvertToSystemDate($endDate);
            $sql .=" AND a.DropDate <= :enddate";
            $sqlCount .=" AND a.DropDate <= :enddate";
            $where["enddate"] = $nDate;
        }
        $sql .=" Order by a.DropDate desc";
        $sql .=" LIMIT ".$resp["start"].",".$resp["length"];
        
        $sCount = collect(\DB::select($sqlCount,$where))->first();
        $rowsTotal = $sCount->cc;
        $list = DB::select($sql,$where);
        $row = $resp["start"]+1;
        $data["data"] = array();
        foreach($list as $item){
            
            if($item->Status == "S"){
                $statusText = '<strong class="text-success">Synced</strong>';
                //$btnActive = '<a  href ="javascript:setActive(\'I\',\''.$code.'\');"  class="btn btn-danger btn-xs" >Inactive</a>';

            }else{
                $statusText = '<strong class="text-danger">Default</strong>';
                //$btnActive = '<a  href ="javascript:setActive(\'A\',\''.$code.'\');"  class="btn btn-success btn-xs" >Active</a>';
            }
            $name = $item->TtitleName." ".$item->TFirstName." ".$item->TLastName;
            $data["data"][] = array(
                date("d/m/Y H:i:s",strtotime($item->DropDate)),
                $name,
                $item->IDCard,
                $item->Gender,
                $item->Make,
                $item->Model,
                $item->Mobile,
                $statusText,
                '<a  data-toggle="modal" href="#dropdetail" class="btn btn-primary btn-xs btn-view" data-idx="'.$item->idx.'" >Detail</a>',
            );
            $row++;
        }
        $data["draw"] = $resp["draw"];
        $data["recordsTotal"] = $rowsTotal;
        $data["recordsFiltered"] = $rowsTotal;
        
        return $data;
   }
   public function getDroplead($idx){
       $sql ="SELECT * FROM tts_droplead WHERE idx = ?";
       $data = collect(\DB::select($sql,[$idx]))->first();

       return $data;
   }
}
