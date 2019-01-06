<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;

class DropleadModel 
{
   public function getDropleadTable($resp = array()){
    $where = array();
       $data = array();
        $rowsTotal = 10;
        $searchText = $resp["search"]["value"];
        $sqlCount = "SELECT Count(*) as cc FROM tts_droplead a  WHERE 1=1 ";
        $sql = "SELECT * FROM tts_droplead a   WHERE 1=1";
        if(!empty($searchText)){
            $sql .=" AND (a.TFirstName LIKE :search OR a.TLastName LIKE :search1 OR a.LicencePlateNo LIKE :search2)";
            $sqlCount .=" AND (a.TFirstName LIKE :search OR a.TLastName LIKE :search1 OR a.LicencePlateNo LIKE :search2)";
            $where["search"] = "%".$searchText."%";
            $where["search1"] = "%".$searchText."%";
            $where["search2"] = "%".$searchText."%";
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
                $statusText = '<span class="label label-success">Synced</span>';
                //$btnActive = '<a  href ="javascript:setActive(\'I\',\''.$code.'\');"  class="btn btn-danger btn-xs" >Inactive</a>';

            }else{
                $statusText = '<span class="label label-danger">No-Synced</span>';
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
