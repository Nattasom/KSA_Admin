<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use App\Models\CoreModel;

class PremiumModel 
{
    private $delimeter = '|';
    private $core ;
    public function __construct(){
        $this->core = new CoreModel();
    }
//    public function uploadPremiumFile($file,$insurer){
//         $response = array();
//         $response["status"] ="";
//         $response["read_list"] = array();
//         $originalName = $file->getClientOriginalName();
//         if(!$this->checkDuplicateFile($originalName)){
//             //Move Uploaded File
//             $destinationPath = 'uploads/premium';
//             if($file->move($destinationPath,$originalName)){
//                 $response["status"] ="01";
//                 $response["read_list"] = $this->readPremiumFile($destinationPath."/".$originalName);
//             }else{
//                 $response["status"] ="03";
//             }
//         }else{
//             $response["status"] ="02";
//         }
    
//         return $response;
//    }
    public function GetPremium($idx){
        $sql = "SELECT * FROM mst_fix_premium WHERE idx = ?";
        $data = collect(\DB::select($sql,[$idx]))->first();

        return $data;
    }
     public function getPremiumTable($resp = array()){
       $where = array();
       $data = array();
        $rowsTotal = 10;
        $sqlCount = "SELECT Count(*) as cc FROM mst_fix_premium a  WHERE 1=1 ";
        $sql = "SELECT * FROM mst_fix_premium a  WHERE 1=1 ";
        if(!empty($resp["insurer"])){
            $sql .= " AND a.InsurerCode = :insurer";
            $sqlCount .=" AND a.InsurerCode = :insurer";
            $where["insurer"] = $resp["insurer"];
        }
        if(!empty($resp["makevalue"])){
            $sql .= " AND a.MakeValue = :makevalue";
            $sqlCount .=" AND a.MakeValue = :makevalue";
            $where["makevalue"] = $resp["makevalue"];
        }
        if(!empty($resp["modelvalue"])){
            $sql .= " AND a.ModelValue = :modelvalue";
            $sqlCount .=" AND a.ModelValue = :modelvalue";
            $where["modelvalue"] = $resp["modelvalue"];
        }
        if(!empty($resp["producttype"])){
            $sql .= " AND a.ProductType = :producttype";
            $sqlCount .=" AND a.ProductType = :producttype";
            $where["producttype"] = $resp["producttype"];
        }
        if(!empty($resp["claimtype"])){
            $sql .= " AND a.ClaimTypeValue = :claimtype";
            $sqlCount .=" AND a.ClaimTypeValue = :claimtype";
            $where["claimtype"] = $resp["claimtype"];
        }
        if(!empty($resp["cargroup"])){
            $sql .= " AND a.CarGroup = :cargroup";
            $sqlCount .=" AND a.CarGroup = :cargroup";
            $where["cargroup"] = $resp["cargroup"];
        }
        if(!empty($resp["premium_start"])){
            $sql .= " AND CAST(a.NetPremium AS DECIMAL(12,2))  >= :premium_start";
            $sqlCount .=" AND CAST(a.NetPremium AS DECIMAL(12,2)) >= :premium_start";
            $where["premium_start"] = $resp["premium_start"];
        }
        if(!empty($resp["premium_end"])){
            $sql .= " AND CAST(a.NetPremium AS DECIMAL(12,2))  <= :premium_end";
            $sqlCount .=" AND CAST(a.NetPremium AS DECIMAL(12,2)) <= :premium_end";
            $where["premium_end"] = $resp["premium_end"];
        }
        if(!empty($resp["suminsured_start"])){
            $sql .= " AND CAST(a.SumInsured AS DECIMAL(12,2))  >= :suminsured_start";
            $sqlCount .=" AND CAST(a.SumInsured AS DECIMAL(12,2)) >= :suminsured_start";
            $where["suminsured_start"] = $resp["suminsured_start"];
        }
        if(!empty($resp["suminsured_end"])){
            $sql .= " AND CAST(a.SumInsured AS DECIMAL(12,2))  <= :suminsured_end";
            $sqlCount .=" AND CAST(a.SumInsured AS DECIMAL(12,2)) <= :suminsured_end";
            $where["suminsured_end"] = $resp["suminsured_end"];
        }
        if(!empty($resp["deduct"])){
            $sql .= " AND CAST(a.DeductAmt AS DECIMAL(12,2)) = :deduct";
            $sqlCount .=" AND CAST(a.DeductAmt AS DECIMAL(12,2)) = :deduct";
            $where["deduct"] = $resp["deduct"];
        }
        $sql .=" LIMIT ".$resp["start"].",".$resp["length"];
        $sCount = collect(\DB::select($sqlCount,$where))->first();
        $rowsTotal = $sCount->cc;
        $list = DB::select($sql,$where);
        $row = $resp["start"]+1;
        $data["data"] = array();
        foreach($list as $item){
            $btnshop = "";
            if(array_key_exists("btn_selected",$resp)){
                $btnshop = '<button type="button" class="btn btn-warning btn-xs btn-shop" onclick="selectPremium(this);" data-idx="'.$item->idx.'">Select</button>';
            }
            $data["data"][] = array(
                $item->InsurerCode,
                $item->ProductType,
                $item->MakeValue,
                $item->ModelValue.' ('.$item->CC.')',
                number_format($item->SumInsured,2),
                number_format($item->NetPremium,2),
                $item->ClaimTypeValue,
                '<a  data-toggle="modal" href="#wide" class="btn btn-primary btn-xs btn-view" data-idx="'.$item->idx.'" >Detail</a> '.$btnshop,
            );
            $row++;
        }
        $data["draw"] = $resp["draw"];
        $data["recordsTotal"] = $rowsTotal;
        $data["recordsFiltered"] = $rowsTotal;
        $data["sql"] = $sql;        
        return $data;
   }
   public function checkDuplicateFile($name){
       $chk = collect(\DB::select("SELECT COUNT(*) as cc FROM mst_fix_premium_head WHERE FileName = ?",[$name]))->first();
       if($chk->cc > 0){
           return true;
       }
       return false;
   }
   public function readPremiumFile($destPath){
       $response = array();
       $handle = fopen($destPath, "r");
       $header = false;
       $count = 0;
        while ($csvLine = fgetcsv($handle, 1000, $this->delimeter)) {

            if ($header) {
                $header = false;
            } else {
            }
            $count++;
        }
        return $count;
   }
   public function importFile($params = array()){
       $resp = "00";
        //insert head
        $insertHead = array(
            "FileName"=>$params["file_name"],
            "InsurerCode"=>$params["insurer"],
            "Remark"=>"",
            "CreateBy"=>$params["username"],
            "CreateDate"=>date("Y-m-d H:i:s"),
            "UpdateBy"=>$params["username"],
            "UpdateDate"=>date("Y-m-d H:i:s"),
        );
        $insHead = DB::table("mst_fix_premium_head")->insert($insertHead);
        if($insHead > 0){
            $destinationPath = 'uploads/premium/'.$params["file_name"];
            $handle = fopen($destinationPath, "r");
            $startDate = $this->core->ConvertToSystemDate($params["from"]);
            $endDate = $this->core->ConvertToSystemDate($params["to"]);
            while ($csvLine = fgetcsv($handle, 1000, $this->delimeter)) {
                $insDetail = array(
                    'FileName'=>$params["file_name"],
                    'InsurerCode'=>$params["insurer"],
                    'MakeValue' => $csvLine[0],
                    'ModelValue' => $csvLine[1],
                    'SubmodelValue'=>$csvLine[2],
                    'MotorType' => $csvLine[3],
                    'SumInsured' => $csvLine[4],
                    'ClaimTypeValue' => $csvLine[5],
                    'CC' => $csvLine[6],
                    'Seat' => $csvLine[7],
                    'Weight' => $csvLine[8],
                    'AgeDriver' => $csvLine[9],
                    'AgeCar' => $csvLine[10],
                    'AgeCarMax' => $csvLine[11],
                    'BasePremium' => $csvLine[12],
                    'MainPremium' => $csvLine[13],
                    'EndorsePremium01' => $csvLine[14],
                    'EndorsePremium02' => $csvLine[15],
                    'EndorsePremium03' => $csvLine[16],
                    'DriverDiscountRate' => $csvLine[17],
                    'DriverDiscount' => $csvLine[18],
                    'GroupDiscountRate' => $csvLine[19],
                    'GroupDiscountAmt' => $csvLine[20],
                    'HistoryDiscountRate' => $csvLine[21],
                    'HistoryDiscountAmt' => $csvLine[22],
                    'OtherDiscountRate' => $csvLine[23],
                    'OtherDiscountAmt' => $csvLine[24],
                    'PlusPremium1' => $csvLine[25],
                    'PlusPremium2' => $csvLine[26],
                    'NetPremium' => $csvLine[27],
                    'Stamp' => $csvLine[28],
                    'VAT' => $csvLine[29],
                    'NetPremium' => $csvLine[30],
                    'DeductAmt' => $csvLine[31],
                    'FIRE_THEFT' => $csvLine[32],
                    'TPPI_P' => $csvLine[33],
                    'TPPI_C' => $csvLine[34],
                    'TPPD' => $csvLine[35],
                    'Bail_Bond' => $csvLine[36],
                    'PA_Driver' => $csvLine[37],
                    'PA_Passengers' => $csvLine[38],
                    'MED' => $csvLine[39],
                    'CommissionRate' => $csvLine[40],
                    'PromotionDiscountRate' => $csvLine[41],
                    'PromotionDiscountAmt' => $csvLine[42],
                    'PromotionEffectiveDate' => $csvLine[43],
                    'PromotionExpiryDate' => $csvLine[44],
                    'CarGroup' => $csvLine[45],
                    'ProductType' => $csvLine[46],
                    "StartDate"=>$startDate,
                    "EndDate"=>$endDate,
                );
                DB::table("mst_fix_premium")->insert($insDetail);
            }
            $resp = "01";
        }
        return $resp;
   }
}
