<?php

namespace App\Models\API;

use Illuminate\Support\Facades\DB;

class PremiumModel 
{
    public function GetMinMaxPremium(){
        $sql = "select MIN(TotalPremium*1) as Minimum,MAX(TotalPremium*1) as Maximum FROM mst_fix_premium";
        $data = collect(\DB::select($sql))->first();

        return $data;
    }
    public function GetMinMaxSumInsured(){
        $sql = "select MIN(SumInsured*1) as Minimum,MAX(SumInsured*1) as Maximum FROM mst_fix_premium";
        $data = collect(\DB::select($sql))->first();

        return $data;
    }
    public function GetMinMaxTPPD(){
        $sql = "select MIN(TPPD*1) as Minimum,MAX(TPPD*1) as Maximum FROM mst_fix_premium";
        $data = collect(\DB::select($sql))->first();

        return $data;
    }
    public function saveDroplead($params = array()){
        $resp = "00";
        $arrName = explode(' ',$params["name"]);
        $fname = $arrName[0];
        $lname = array_key_exists(1,$arrName) ? $arrName[1]:'';
        $calldate = null;
        if(!empty($params["callback_date"]) && !empty($params["callback_time"])){
            $calldate = $params["callback_date"]." ".$params["callback_time"];
        }
        $email = array_key_exists("email",$params) ? $params["email"]:'';
        $remark = array_key_exists("remark",$params) ? $params["remark"]:'';
        $insertData = array(
            "TFirstName"=>$fname,
            "TLastName"=>$lname,
            "Mobile"=>$params["tel"],
            "Email"=>$email,
            "CallbackDateTime"=>$calldate,
            "Remark1"=>$remark,
            "Make"=>$params["make"],
            "Model"=>$params["model"],
            "MotorType"=>$params["motor_type"],
            "Seat"=>$params["seat"],
            "CC"=>$params["cc"],
            "DropDate"=>date("Y-m-d H:i:s"),
            "Status"=>'N'
        );
        $ins = DB::table("tts_droplead")->insertGetId($insertData);
        if($ins > 0){
            $resp = "01";
        }
        return $resp;
    }
}