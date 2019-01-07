<?php

namespace App\Models\API;

use Illuminate\Support\Facades\DB;

class MasterModel 
{
    public function GetInsurerList($lang){
        $sql = "SELECT a.InsurerCode,a.LogoPath as Icon,b.InsurerName,b.InsurerShortName FROM mst_insurer a INNER JOIn mst_insurer_detail b ON a.InsurerCode=b.InsurerCode AND b.LanguageCode = ? WHERE `Status` = 'A'";
       $list = DB::select($sql,[$lang]);
       $resp = array();
       $path = 'uploads/insurer/';
       foreach($list as $key=>$value){
            $tmp = $value;
            $icon = $path.$value->Icon;
            $type = pathinfo($icon, PATHINFO_EXTENSION);
            $data = file_get_contents($icon);
            $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
            $tmp->Icon = $base64;
            $resp[] = $tmp;
       }
        
       return $resp;
    }
    public function GetAllCarMakeValue(){
       $sql = "SELECT MakeValue,MakeValueName FROM mst_car WHERE `Status` = 'A' Order By OrderSeq";
       $list = DB::select($sql);

       return $list;
   }
   public function GetModelValue($makevalue){
        $sql = "SELECT Distinct ModelValue FROM mst_fix_premium WHERE MakeValue = ? ";
        $list = DB::select($sql,[$makevalue]);

        return $list;
   }
   public function GetSubModelValue($modelvalue,$year){
        $this_year = date("Y");
        $diffYear = intval($year)-intval($this_year);
        $sql = "SELECT Distinct ModelDescription FROM mst_fix_premium WHERE ModelValue = ? AND AgeCarMax >= ?";
        $list = DB::select($sql,[$modelvalue,$diffYear]);

        return $list;
   }
   public function GetModelYear($modelvalue){
        $list = array();
        $sql = "SELECT Max(AgeCarMax) as agemax FROM mst_fix_premium WHERE ModelValue = ? ";
        $qAge = collect(\DB::select($sql,[$modelvalue]))->first();

        $this_year = date("Y");
        for($i = $this_year;$i >= ($this_year-$qAge->agemax);$i--){
            $list[] = $i;
        }
        return $list;
   }
   public function GetClaimType(){
        $sql = "SELECT ClaimTypeValue,Description FROM mst_claimtypevalue WHERE `Status` = 'A' ";
        $list = DB::select($sql);

        return $list;
   }
   public function GetSperatePayList($lang)
   {
        $sql = "SELECT SperatePayMonth,SperateDesc_".$lang." as Description FROM mst_sperate_pay Order By Seq";
        $list = DB::select($sql);

        return $list;
   }
   

}

?>