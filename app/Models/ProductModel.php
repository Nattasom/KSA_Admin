<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use App\Models\CoreModel;
use Session;

class ProductModel 
{
    private $core;
    public function __construct(){
        $this->core = new CoreModel();
    }
   public function getProductTable($resp = array()){
       $where = array();
       $data = array();
        $rowsTotal = 10;
        $searchText = $resp["search"]["value"];
        $sqlCount = "SELECT Count(*) as cc FROM mst_product a 
            inner join mst_product_detail c on a.ProductId=c.ProductId AND c.LanguageCode = 'th'
            left join mst_insurer_detail b on a.InsurerCode=b.InsurerCode AND b.LanguageCode = 'th' WHERE 1=1 ";
        $sql = "SELECT a.ProductId,c.ProductName,a.InsurerCode,b.InsurerName,a.Status FROM mst_product a 
            inner join mst_product_detail c on a.ProductId=c.ProductId AND c.LanguageCode = 'th'
            left join mst_insurer_detail b on a.InsurerCode=b.InsurerCode AND b.LanguageCode = 'th'  WHERE 1=1";
        if(!empty($searchText)){
            $sql .=" AND (a.ProductName LIKE :search OR b.InsurerCode LIKE :search1)";
            $sqlCount .=" AND (a.ProductName LIKE :search OR b.InsurerCode LIKE :search1)";
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
            $code = $item->ProductId;
            $name =$item->ProductName;
            $insCode = $item->InsurerCode;
            $insName = $item->InsurerName;
            if($item->Status == "A"){
                $statusText = '<span class="label label-success">Active</span>';
                $btnActive = '<a  href ="javascript:setActive(\'I\',\''.$code.'\');"  class="btn btn-danger btn-xs" >Inactive</a>';

            }else{
                $statusText = '<span class="label label-danger">Inactive</span>';
                $btnActive = '<a  href ="javascript:setActive(\'A\',\''.$code.'\');"  class="btn btn-success btn-xs" >Active</a>';
            }
            $editGroup = '';
            if(in_array('EDIT',Session::get('userinfo')->permission['page_4']['actions']))
            {
                $editGroup='<a  href ="'.url("/product-edit",[$code]).'" class="btn btn-primary btn-xs btn-edit" >Edit</a> '.$btnActive;
            }
            $data["data"][] = array(
                $row,
                $name,
                $insCode,
                $insName,
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
   public function SetStatus($params = array()){
        $resp = false;
        $row = DB::update("UPDATE mst_product SET Status = ? WHERE ProductId = ?",[$params["status"],$params["code"]]);
        if($row > 0){
            $resp = true;
        }
        return $resp;
   }
   public function ProductAdd($params = array()){
    $resp = "00";
       do{
        // if($this->CheckInsurerDuplicate($params["insurer_code"])){
        //     $resp="03";
        //     break;
        // }
        $insertData = array(
            // "ProductName"=>$params["product_name"],
            // "ProductDescription"=>$params["editor"],
            "InsurerCode"=>$params["filter_insurer"],
            "MakeValue"=>$params["filter_makevalue"],
            "ModelValue"=>$params["filter_modelvalue"],
            "ProductType"=>$params["filter_producttype"],
            "ClaimTypeValue"=>$params["filter_claimtype"],
            "CarGroup"=>$params["filter_cargroup"],
            "SumInsuredMin"=>$params["insured_min"],
            "SumInsuredMax"=>$params["insured_max"],
            "PremiumMin"=>$params["premium_min"],
            "PremiumMax"=>$params["premium_max"],
            "PromotionID"=>$params["promotion"],
            "Status"=>$params["status"],
            "CreateBy"=>$params["username"],
            "CreateDate"=>date("Y-m-d H:i:s"),
            "UpdateBy"=>$params["username"],
            "UpdateDate"=>date("Y-m-d H:i:s"),
        );
        $ins = DB::table("mst_product")->insertGetId($insertData);
        if($ins > 0){
            $langs = $this->core->GetLangList();
            foreach($langs as $key=>$value){
                $insDetail = array(
                    "ProductId"=>$ins,
                    "LanguageCode"=>$value->LanguageCode,
                    "ProductName"=>$params["product_name_".$value->LanguageCode],
                    "ProductDesc1"=>$params["editor1_".$value->LanguageCode],
                    "ProductDesc2"=>$params["editor2_".$value->LanguageCode],
                    "ProductDesc3"=>$params["editor3_".$value->LanguageCode],
                );
                DB::table("mst_product_detail")->insert($insDetail);
            }

            $resp = "01";
        }
       }while(false);
       
       return $resp;
   }
   public function ProductEdit($params = array()){
       $resp = "00";
       do{
        // if($params["old_code"]!=$params["insurer_code"]){
        //     if($this->CheckInsurerDuplicate($params["insurer_code"])){
        //         $resp="03";
        //         break;
        //     }
        // }
        
        $updateData = array(
            "InsurerCode"=>$params["filter_insurer"],
            "MakeValue"=>$params["filter_makevalue"],
            "ModelValue"=>$params["filter_modelvalue"],
            "ProductType"=>$params["filter_producttype"],
            "ClaimTypeValue"=>$params["filter_claimtype"],
            "CarGroup"=>$params["filter_cargroup"],
            "SumInsuredMin"=>$params["insured_min"],
            "SumInsuredMax"=>$params["insured_max"],
            "PremiumMin"=>$params["premium_min"],
            "PremiumMax"=>$params["premium_max"],
            "PromotionID"=>$params["promotion"],
            "Status"=>$params["status"],
            "UpdateBy"=>$params["username"],
            "UpdateDate"=>date("Y-m-d H:i:s"),
        );
        
        $ins = DB::table('mst_product')
            ->where('ProductId', $params["old_code"])
            ->update($updateData);
        if($ins > 0){
            DB::delete("DELETE FROM mst_product_detail WHERE ProductId = ? ",[$params["old_code"]]);
            $langs = $this->core->GetLangList();
            foreach($langs as $key=>$value){
                $insDetail = array(
                    "ProductId"=>$params["old_code"],
                    "LanguageCode"=>$value->LanguageCode,
                    "ProductName"=>$params["product_name_".$value->LanguageCode],
                    "ProductDesc1"=>$params["editor1_".$value->LanguageCode],
                    "ProductDesc2"=>$params["editor2_".$value->LanguageCode],
                    "ProductDesc3"=>$params["editor3_".$value->LanguageCode],
                );
                DB::table("mst_product_detail")->insert($insDetail);
            }
            $resp = "01";
        }
       }while(false);
       
       return $resp;
   }
   public function GetProduct($code){
       $data = array();
        $sql = "SELECT * FROM mst_product a   WHERE a.ProductId = ?";
        $list = DB::select($sql,[$code]);
        foreach($list as $key=>$value){
            $data["product_id"] = $value->ProductId;
            $data["insurer"] = $value->InsurerCode;
            $data["make"] = $value->MakeValue;
            $data["model"] = $value->ModelValue;
            $data["producttype"] = $value->ProductType;
            $data["claim"] = $value->ClaimTypeValue;
            $data["cargroup"] = $value->CarGroup;
            $data["ins_min"] = $value->SumInsuredMin;
            $data["ins_max"] = $value->SumInsuredMax;
            $data["premium_min"] = $value->PremiumMin;
            $data["premium_max"] = $value->PremiumMax;
            $data["promotion"] = $value->PromotionID;
            $data["status"] = $value->Status;
        }
        $sql = "SELECT * FROM mst_product_detail a WHERE a.ProductId = ?";
        $list = DB::select($sql,[$code]);
        foreach($list as $key=>$value){
            $data["detail"][$value->LanguageCode] = array(
                "product_name"=>$value->ProductName,
                "product_desc1"=>$value->ProductDesc1,
                "product_desc2"=>$value->ProductDesc2,
                "product_desc3"=>$value->ProductDesc3,
            );
            
        }
        return $data;
   }
}
