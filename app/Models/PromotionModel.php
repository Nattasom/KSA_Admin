<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use App\Models\CoreModel;
use Session;

class PromotionModel 
{
    private $core;
    public function __construct(){
        $this->core = new CoreModel();
    }
    public function getPromotionTable($resp = array()){
       $where = array();
       $data = array();
        $rowsTotal = 10;
        $searchText = $resp["search"]["value"];
        $sqlCount = "SELECT Count(*) as cc FROM mst_promotion a  WHERE 1=1 ";
        $sql = "SELECT * FROM mst_promotion a   WHERE 1=1";
        if(!empty($searchText)){
            $sql .=" AND (a.PromotionName LIKE :search OR a.PromotionCode LIKE :search1)";
            $sqlCount .=" AND (a.PromotionName LIKE :search OR a.PromotionCode LIKE :search1)";
            $where["search"] = "%".$searchText."%";
            $where["search1"] = "%".$searchText."%";
        }
        $sql .=" LIMIT ".$resp["start"].",".$resp["length"];
        $sCount = collect(\DB::select($sqlCount,$where))->first();
        $rowsTotal = $sCount->cc;
        $list = DB::select($sql,$where);
        $row = $resp["start"]+1;
        $data["data"] = array();
        foreach($list as $item){
            $image = "";
            $btnActive = "";
            $statusText = "";
            if(!empty($item->PromotionTag)){
                $image = config("app.root_path")."/uploads/promotion/".$item->PromotionTag;
                $image = '<img src="'.$image.'" />';
            }
            $code = $item->PromotionCode;
            $name =$item->PromotionName;
            if($item->Status == "A"){
                $statusText = '<span class="label label-success">Active</span>';
                $btnActive = '<a  href ="javascript:setActive(\'I\',\''.$item->PromotionID.'\');"  class="btn btn-danger btn-xs" >Inactive</a>';

            }else{
                $statusText = '<span class="label label-danger">Inactive</span>';
                $btnActive = '<a  href ="javascript:setActive(\'A\',\''.$item->PromotionID.'\');"  class="btn btn-success btn-xs" >Active</a>';
            }
            $editGroup = '';
            if(in_array('EDIT',Session::get('userinfo')->permission['page_8']['actions']))
            {
                $editGroup='<a  href ="'.url("/promotion-edit",[$item->PromotionID]).'" class="btn btn-primary btn-xs btn-edit" >Edit</a> '.$btnActive;
            }

            $data["data"][] = array(
                $row,
                $code,
                $name,
                $image,
                $statusText,
                date("d/m/Y",strtotime($item->StartDate)),
                date("d/m/Y",strtotime($item->EndDate)),
                $editGroup,
            );
            $row++;
        }
        $data["draw"] = $resp["draw"];
        $data["recordsTotal"] = $rowsTotal;
        $data["recordsFiltered"] = $rowsTotal;
        
        return $data;
   }
   public function GetPromotionList(){
       $sql ="SELECT * FROM mst_promotion a  WHERE a.Status = 'A'";
       $data = DB::select($sql);

       return $data;
   }
   public function GetPromotion($id){
        $data = array();
        $sql = "SELECT * FROM mst_promotion a  WHERE a.PromotionID = ?";
        $list = DB::select($sql,[$id]);
        foreach($list as $key=>$value){
            $data["promotion_id"] = $value->PromotionID;
            $data["promotion_code"] = $value->PromotionCode;
            $data["promotion_name"] = $value->PromotionName;
            $data["promotion_tag"] = $value->PromotionTag;
            $data["promotion_status"] = $value->Status;
            $data["startdate"] = date("d/m/Y",strtotime($value->StartDate));
            $data["enddate"] = date("d/m/Y",strtotime($value->EndDate));
        }
        return $data;
   }
   public function PromotionAdd($params = array(),$file){
        $resp = "00";
       do{
        if($this->CheckDuplicate($params["promotion_code"])){
            $resp="03";
            break;
        }
        $insertData = array(
            "PromotionCode"=>$params["promotion_code"],
            "PromotionName"=>$params["promotion_name"],
            "StartDate"=>$this->core->ConvertToSystemDate($params["from"]),
            "EndDate"=>$this->core->ConvertToSystemDate($params["to"]),
            "Status"=>$params["promotion_status"],
            "CreateBy"=>$params["username"],
            "CreateDate"=>date("Y-m-d H:i:s"),
            "UpdateBy"=>$params["username"],
            "UpdateDate"=>date("Y-m-d H:i:s"),
        );
        $ins = DB::table("mst_promotion")->insertGetId($insertData);
        if($ins > 0){
            // $langs = $this->core->GetLangList();
            // foreach($langs as $key=>$value){
            //     $insDetail = array(
            //         "InsurerCode"=>$params["insurer_code"],
            //         "LanguageCode"=>$value->LanguageCode,
            //         "InsurerName"=>$params["insurer_name_".$value->LanguageCode],
            //         "InsurerShortName"=>$params["insurer_shortname_".$value->LanguageCode],
            //     );
            //     DB::table("mst_insurer_detail")->insert($insDetail);
            // }
            $originalName =  time().$file->getClientOriginalName();
            $destinationPath = 'uploads/promotion';
            if($file->move($destinationPath,$originalName)){
                DB::update("UPDATE mst_promotion SET PromotionTag = ? WHERE PromotionID = ?",[$originalName,$ins]);
            }
            $resp = "01";
        }
       }while(false);
       
       return $resp;
   }
   public function PromotionEdit($params = array(),$file){
        $resp = "00";
       do{
        if($params["old_code"]!=$params["promotion_code"]){
            if($this->CheckDuplicate($params["promotion_code"])){
                $resp="03";
                break;
            }
        }
        
        $updateData = array(
            "PromotionCode"=>$params["promotion_code"],
            "PromotionName"=>$params["promotion_name"],
            "StartDate"=>$this->core->ConvertToSystemDate($params["from"]),
            "EndDate"=>$this->core->ConvertToSystemDate($params["to"]),
            "Status"=>$params["promotion_status"],
            "UpdateBy"=>$params["username"],
            "UpdateDate"=>date("Y-m-d H:i:s"),
        );
        
        $ins = DB::table('mst_promotion')
            ->where('PromotionID', $params["promotion_id"])
            ->update($updateData);
        if($ins > 0){
            
            if(!is_null($file)){
                $originalName =  time().$file->getClientOriginalName();
                $destinationPath = 'uploads/promotion';
                if($file->move($destinationPath,$originalName)){
                    DB::update("UPDATE mst_promotion SET PromotionTag = ? WHERE PromotionID = ?",[$originalName,$params["promotion_id"]]);
                }
            }
            
            $resp = "01";
        }
       }while(false);
       
       return $resp;
   }
   public function SetStatus($params = array()){
        $resp = false;
        $row = DB::update("UPDATE mst_promotion SET Status = ? WHERE PromotionID = ?",[$params["status"],$params["code"]]);
        if($row > 0){
            $resp = true;
        }
        return $resp;
   }
   public function CheckDuplicate($code){
        $stDate = date("Y-m-d");
        $nDate = date("Y-m-d")." 23:59:59";
       $resp = false;
        $res = collect(\DB::select("SELECT Count(*) as cc FROM mst_promotion WHERE PromotionCode = ? AND StartDate >= ? AND EndDate <= ?",[$code,$stDate,$nDate]))->first();
        if($res->cc > 0){
            $resp = true;
        }

        return $resp;
   }
}
