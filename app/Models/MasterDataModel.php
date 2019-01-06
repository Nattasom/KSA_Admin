<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use App\Models\CoreModel;
use Session;
class MasterDataModel 
{
    private $core;
    public function __construct(){
        $this->core = new CoreModel();
    }
   public function getInsurerTable($resp = array()){
       $where = array();
       $data = array();
        $rowsTotal = 10;
        $searchText = $resp["search"]["value"];
        $sqlCount = "SELECT Count(*) as cc FROM mst_insurer a inner join mst_insurer_detail b on a.InsurerCode=b.InsurerCode AND b.LanguageCode = 'th' WHERE 1=1 ";
        $sql = "SELECT * FROM mst_insurer a inner join mst_insurer_detail b on a.InsurerCode=b.InsurerCode AND b.LanguageCode = 'th'  WHERE 1=1";
        if(!empty($searchText)){
            $sql .=" AND (b.InsurerName LIKE :search OR b.InsurerCode LIKE :search1)";
            $sqlCount .=" AND (b.InsurerName LIKE :search OR b.InsurerCode LIKE :search1)";
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
            if(!empty($item->LogoPath)){
                $image = config("app.root_path")."/uploads/insurer/".$item->LogoPath;
                $image = '<img src="'.$image.'" />';
            }
            $code = $item->InsurerCode;
            $name =$item->InsurerName;
            if($item->Status == "A"){
                $statusText = '<span class="label label-success">Active</span>';
                $btnActive = '<a  href ="javascript:setActive(\'I\',\''.$code.'\');"  class="btn btn-danger btn-xs" >Inactive</a>';

            }else{
                $statusText = '<span class="label label-danger">Inactive</span>';
                $btnActive = '<a  href ="javascript:setActive(\'A\',\''.$code.'\');"  class="btn btn-success btn-xs" >Active</a>';
            }
            $editGroup = '';
            if(in_array('EDIT',Session::get('userinfo')->permission['page_11']['actions']))
            {
                $editGroup='<a  href ="'.url("/master/insurer-edit",[$code]).'" class="btn btn-primary btn-xs btn-edit" >Edit</a> '.$btnActive;
            }

            $data["data"][] = array(
                $row,
                $image,
                $code,
                $name,
                $statusText,
                $editGroup,
            );
            $row++;
        }
        $data["draw"] = $resp["draw"];
        $data["recordsTotal"] = $rowsTotal;
        $data["recordsFiltered"] = $rowsTotal;
        
        return $data;
   }
   public function GetAllInsurer($lang = 'th'){
       $sql = "SELECT * FROM mst_insurer a INNER JOIn mst_insurer_detail b ON a.InsurerCode=b.InsurerCode AND b.LanguageCode = ? WHERE `Status` = 'A'";
       $list = DB::select($sql,[$lang]);

       return $list;

   }
   public function GetAllCarMakeValue(){
       $sql = "SELECT * FROM mst_car WHERE `Status` = 'A' Order By OrderSeq";
       $list = DB::select($sql);

       return $list;
   }
   public function GetModelValue($makevalue){
        $sql = "SELECT * FROM mst_carmodel WHERE MakeValue = ? ";
        $list = DB::select($sql,[$makevalue]);

        return $list;
   }
   public function GetAllProductType(){
       $sql = "SELECT * FROM mst_producttype WHERE `Status` = 'A'";
       $list = DB::select($sql);

       return $list;
   }
   public function GetAllClaimType(){
       $sql = "SELECT * FROM mst_claimtypevalue WHERE `Status` = 'A'";
       $list = DB::select($sql);

       return $list;
   }
   public function GetAllCarGroup(){
       $sql = "SELECT * FROM mst_cargroup WHERE `Status` = 'A'";
       $list = DB::select($sql);

       return $list;
   }
   public function InsurerSetStatus($params = array()){
        $resp = false;
        $row = DB::update("UPDATE mst_insurer SET Status = ? WHERE InsurerCode = ?",[$params["status"],$params["code"]]);
        if($row > 0){
            $resp = true;
        }
        return $resp;
   }
   public function GetInsurer($code){
        $data = array();
        $sql = "SELECT * FROM mst_insurer a inner join mst_insurer_detail b on a.InsurerCode=b.InsurerCode  WHERE a.InsurerCode = ?";
        $list = DB::select($sql,[$code]);
        foreach($list as $key=>$value){
            if($key==0){
                $data["insurer_code"] = $value->InsurerCode;
                $data["insurer_image"] = $value->LogoPath;
                $data["insurer_status"] = $value->Status;
            }
            $data["detail"][$value->LanguageCode] = array(
                "insurer_name"=>$value->InsurerName,
                "insurer_shortname"=>$value->InsurerShortName,
            );
        }
        return $data;
   }
   public function InsurerAdd($params = array(),$file){
       $resp = "00";
       do{
        if($this->CheckInsurerDuplicate($params["insurer_code"])){
            $resp="03";
            break;
        }
        $insertData = array(
            "InsurerCode"=>$params["insurer_code"],
            "Status"=>$params["insurer_status"],
            "CreateBy"=>$params["username"],
            "CreateDate"=>date("Y-m-d H:i:s"),
            "LogoPath"=>"",
            "UpdateBy"=>$params["username"],
            "UpdateDate"=>date("Y-m-d H:i:s"),
        );
        $ins = DB::table("mst_insurer")->insert($insertData);
        if($ins > 0){
            $langs = $this->core->GetLangList();
            foreach($langs as $key=>$value){
                $insDetail = array(
                    "InsurerCode"=>$params["insurer_code"],
                    "LanguageCode"=>$value->LanguageCode,
                    "InsurerName"=>$params["insurer_name_".$value->LanguageCode],
                    "InsurerShortName"=>$params["insurer_shortname_".$value->LanguageCode],
                );
                DB::table("mst_insurer_detail")->insert($insDetail);
            }
            $originalName =  time().$file->getClientOriginalName();
            $destinationPath = 'uploads/insurer';
            if($file->move($destinationPath,$originalName)){
                DB::update("UPDATE mst_insurer SET LogoPath = ? WHERE InsurerCode = ?",[$originalName,$params["insurer_code"]]);
            }
            $resp = "01";
        }
       }while(false);
       
       return $resp;
   }
   public function InsurerEdit($params = array(),$file){
       $resp = "00";
       do{
        if($params["old_code"]!=$params["insurer_code"]){
            if($this->CheckInsurerDuplicate($params["insurer_code"])){
                $resp="03";
                break;
            }
        }
        
        $updateData = array(
            "InsurerCode"=>$params["insurer_code"],
            "Status"=>$params["insurer_status"],
            "UpdateBy"=>$params["username"],
            "UpdateDate"=>date("Y-m-d H:i:s"),
        );
        
        $ins = DB::table('mst_insurer')
            ->where('InsurerCode', $params["old_code"])
            ->update($updateData);
        if($ins > 0){
            //clear detail
            DB::delete("DELETE FROM mst_insurer_detail WHERE InsurerCode = ?",[$params["old_code"]]);
            $langs = $this->core->GetLangList();
            foreach($langs as $key=>$value){
                $insDetail = array(
                    "InsurerCode"=>$params["insurer_code"],
                    "LanguageCode"=>$value->LanguageCode,
                    "InsurerName"=>$params["insurer_name_".$value->LanguageCode],
                    "InsurerShortName"=>$params["insurer_shortname_".$value->LanguageCode],
                );
                DB::table("mst_insurer_detail")->insert($insDetail);
            }
            if(!is_null($file)){
                $originalName =  time().$file->getClientOriginalName();
                $destinationPath = 'uploads/insurer';
                if($file->move($destinationPath,$originalName)){
                    DB::update("UPDATE mst_insurer SET LogoPath = ? WHERE InsurerCode = ?",[$originalName,$params["insurer_code"]]);
                }
            }
            
            $resp = "01";
        }
       }while(false);
       
       return $resp;
   }
   public function CheckInsurerDuplicate($code){
       $resp = false;
        $res = collect(\DB::select("SELECT Count(*) as cc FROM mst_insurer WHERE InsurerCode = ?",[$code]))->first();
        if($res->cc > 0){
            $resp = true;
        }

        return $resp;
   }
}
