<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use App\Models\CoreModel;

class PremiumModel 
{
    private $delimeter = '|';
    private $import_limit = 5000;
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
   public function getPremiumHeadTable($resp = array()){
       $where = array();
       $data = array();
        $rowsTotal = 10;
        $sqlCount = "SELECT Count(*) as cc FROM mst_fix_premium_head a  WHERE 1=1 ";
        $sql = "SELECT * FROM mst_fix_premium_head a  WHERE 1=1 ORDER BY a.UpdateDate desc";
        
        $sql .=" LIMIT ".$resp["start"].",".$resp["length"];
        $sCount = collect(\DB::select($sqlCount,$where))->first();
        $rowsTotal = $sCount->cc;
        $list = DB::select($sql,$where);
        $row = $resp["start"]+1;
        $data["data"] = array();
        foreach($list as $item){
            $lblFail = '<a href="#"><strong class="text-danger">'.number_format($item->FailRecords).'</strong></a>';
            $lblStatus = '';
            $textStatus = '';
            $btnApprove = '';
            if($item->ProcessStatus=='PS'){
                $btnApprove = '<button type="button" class="btn btn-success">อนุมัติ</button>';
                $textStatus = 'นำเข้าเรียบร้อย ('.number_format($item->LastProcessRecords).'/'.number_format($item->AllRecords).')';
                $lblStatus = '<strong class="text-primary">'.$textStatus.'</strong>';
            }
            else if($item->ProcessStatus=='P'){
                $textStatus = 'กำลังนำเข้า ('.number_format($item->LastProcessRecords).'/'.number_format($item->AllRecords).')';
                $lblStatus = '<strong class="text-warning">'.$textStatus.'</strong>';
            }
            else if($item->ProcessStatus=='A'){
                $textStatus = 'พร้อมใช้งาน';
                $lblStatus = '<strong class="text-success">'.$textStatus.'</strong>';
            }
            
            $data["data"][] = array(
                date('d/m/Y H:i',strtotime($item->UpdateDate)),
                $item->FileName,
                $item->InsurerCode,
                number_format($item->AllRecords),
                number_format($item->SuccessRecords),
                $lblFail,
                $lblStatus,
                $btnApprove,
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
       $header = true;
       $count = 0;
        while ($csvLine = fgetcsv($handle, 1000, $this->delimeter)) {

            if ($header) {
                $header = false;
            } else {
                $count++;
            }
        }
        return $count;
   }
   public function addImportProcess($input = array()){
       $ins = DB::table("mst_fix_premium_head")->insert($input);
       if($ins > 0){
           return true;
       }else{
           return false;
       }
   }
   public function taskImportFile($params = array()){
       $resp = "00";
        //insert head
        // $insertHead = array(
        //     "FileName"=>$params["file_name"],
        //     "InsurerCode"=>$params["insurer"],
        //     "Remark"=>"",
        //     "CreateBy"=>$params["username"],
        //     "CreateDate"=>date("Y-m-d H:i:s"),
        //     "UpdateBy"=>$params["username"],
        //     "UpdateDate"=>date("Y-m-d H:i:s"),
        // );
        // $insHead = DB::table("mst_fix_premium_head")->insert($insertHead);
        // if($insHead > 0){
        $sql = "SELECT * FROM mst_fix_premium_head WHERE ProcessStatus = 'P' Order By UpdateDate LIMIT 1";
        $qData = collect(\DB::select($sql))->first();
        if(!is_null($qData)){
            $destinationPath = 'uploads/premium/'.$qData->FileName;
            $handle = fopen($destinationPath, "r");
            $startRow = $qData->LastProcessRecords;
            $count = 1;
            $count_limit = 0;
            $header = true;
            // $startDate = $this->core->ConvertToSystemDate($params["from"]);
            // $endDate = $this->core->ConvertToSystemDate($params["to"]);
            while ($csvLine = fgetcsv($handle, 1000, $this->delimeter)) {
                if ($header) {
                    $header = false;
                } else {
                    if($count > $startRow){
                        //validate
                        if($this->validateImport($csvLine,$count,$qData->FileName)){
                            //import
                            $insDetail = array(
                                'FileName'=>$qData->FileName,
                                'InsurerCode'=>$qData->InsurerCode,
                                'MakeValue' => $csvLine[0],
                                'ModelValue' => $csvLine[1],
                                'ModelGroup'=>$csvLine[2],
                                'ModelDescription'=>iconv( "Windows-874", "UTF-8", $csvLine[3] ),
                                'MotorType' => $csvLine[4],
                                'SumInsured' => str_replace(',','',$csvLine[5]),
                                'ClaimTypeValue' => strlen($csvLine[6])==2 ? $csvLine[6]:str_pad($csvLine[6], 2, "0", STR_PAD_LEFT),
                                'CC' => $csvLine[7],
                                'Seat' => $csvLine[8],
                                'Weight' => $csvLine[9],
                                'AgeDriver' => $csvLine[10],
                                'AgeCar' => $csvLine[11],
                                'AgeCarMax' => $csvLine[12],
                                'BasePremium' => is_numeric(str_replace(',','',$csvLine[13])) ? str_replace(',','',$csvLine[13]) : 0,
                                'MainPremium' => is_numeric(str_replace(',','',$csvLine[14])) ? str_replace(',','',$csvLine[14]) :0,
                                'EndorsePremium01' => is_numeric(str_replace(',','',$csvLine[15])) ? str_replace(',','',$csvLine[15]) :0,
                                'EndorsePremium02' => is_numeric(str_replace(',','',$csvLine[16])) ? str_replace(',','',$csvLine[16]) :0,
                                'EndorsePremium03' => is_numeric(str_replace(',','',$csvLine[17])) ? str_replace(',','',$csvLine[17]) :0,
                                'DriverDiscountRate' => is_numeric(str_replace('%','',$csvLine[18])) ? str_replace(',','',$csvLine[18]) : 0,
                                'DriverDiscount' => is_numeric(str_replace(',','',$csvLine[19])) ? str_replace(',','',$csvLine[19]) : 0,
                                'GroupDiscountRate' => is_numeric(str_replace('%','',$csvLine[20])) ? str_replace(',','',$csvLine[20]) : 0,
                                'GroupDiscountAmt' => is_numeric(str_replace(',','',$csvLine[21])) ? str_replace(',','',$csvLine[21]) : 0,
                                'HistoryDiscountRate' => is_numeric(str_replace('%','',$csvLine[22])) ? str_replace(',','',$csvLine[22]) : 0,
                                'HistoryDiscountAmt' => is_numeric(str_replace(',','',$csvLine[23])) ? str_replace(',','',$csvLine[23]) : 0,
                                'OtherDiscountRate' => is_numeric(str_replace('%','',$csvLine[24])) ? str_replace(',','',$csvLine[24]) : 0,
                                'OtherDiscountAmt' => is_numeric(str_replace(',','',$csvLine[25])) ? str_replace(',','',$csvLine[25]) : 0,
                                'PlusPremium1' => is_numeric(str_replace(',','',$csvLine[26])) ? str_replace(',','',$csvLine[26]) : 0,
                                'PlusPremium2' => is_numeric(str_replace(',','',$csvLine[27])) ? str_replace(',','',$csvLine[27]) : 0,
                                'NetPremium' => is_numeric(str_replace(',','',$csvLine[28])) ? str_replace(',','',$csvLine[28]) : 0,
                                'Stamp' => is_numeric(str_replace(',','',$csvLine[29])) ? str_replace(',','',$csvLine[29]) : 0,
                                'VAT' => is_numeric(str_replace(',','',$csvLine[30])) ? str_replace(',','',$csvLine[30]) : 0,
                                'TotalPremium' => is_numeric(str_replace(',','',$csvLine[31])) ? str_replace(',','',$csvLine[31]) : 0,
                                'DeductAmt' => is_numeric(str_replace(',','',$csvLine[32])) ? str_replace(',','',$csvLine[32]) : 0,
                                'FIRE_THEFT' => is_numeric(str_replace(',','',$csvLine[33])) ? str_replace(',','',$csvLine[33]) : 0,
                                'TPPI_P' => is_numeric(str_replace(',','',$csvLine[34])) ? str_replace(',','',$csvLine[34]) : 0,
                                'TPPI_C' => is_numeric(str_replace(',','',$csvLine[35])) ? str_replace(',','',$csvLine[35]) : 0,
                                'TPPD' => is_numeric(str_replace(',','',$csvLine[36])) ? str_replace(',','',$csvLine[36]) : 0,
                                'Bail_Bond' => is_numeric(str_replace(',','',$csvLine[37])) ? str_replace(',','',$csvLine[37]) : 0,
                                'PA_Driver' => is_numeric(str_replace(',','',$csvLine[38])) ? str_replace(',','',$csvLine[38]) : 0,
                                'PA_Passengers' => is_numeric(str_replace(',','',$csvLine[39])) ? str_replace(',','',$csvLine[39]) : 0,
                                'MED' => is_numeric(str_replace(',','',$csvLine[40])) ? str_replace(',','',$csvLine[40]) : 0,
                                'CommissionRate' => is_numeric(str_replace('%','',$csvLine[41])) ? str_replace('%','',$csvLine[41]) : 0,
                                'PromotionDiscountRate' => is_numeric(str_replace('%','',$csvLine[42])) ? str_replace('%','',$csvLine[42]) : 0,
                                'PromotionDiscountAmt' => is_numeric(str_replace(',','',$csvLine[43])) ? str_replace(',','',$csvLine[43]) : 0,
                                'PromotionEffectiveDate' => $csvLine[44],
                                'PromotionExpiryDate' => $csvLine[45],
                                'CarGroup' => $csvLine[46],
                                'ProductType' => $csvLine[47],
                                // "StartDate"=>$startDate,
                                // "EndDate"=>$endDate,
                            );
                            DB::table("mst_fix_premium")->insert($insDetail);
                        }
                        $count_limit++;
                        if($count_limit==$this->import_limit || $count==$qData->AllRecords){
                            //update LastProcessRecords
                            DB::update("UPDATE mst_fix_premium_head SET LastProcessRecords = ? WHERE FileName = ?",[$count,$qData->FileName]);

                            if($count==$qData->AllRecords){
                                $cError = collect(\DB::select("SELECT Count(*) as cc FROM mst_fix_premium_error WHERE FileName = ?",[$qData->FileName]))->first();
                                $successCount = $qData->AllRecords - $cError->cc;
                                DB::update("UPDATE mst_fix_premium_head SET ProcessStatus = 'PS',SuccessRecords=?,FailRecords=? WHERE FileName = ?",[$successCount,$cError->cc,$qData->FileName]);
                            }
                            break;
                        }
                    }
                    $count++;
                }
                
                
            }
            $resp = "01";
        }
            
        //}
        return $resp;
   }
   public function validateImport($line,$rownum,$filename){
        $result = true;
        $make = $line[0];
        $model = $line[1];
        $model_group = $line[2];
        $model_desc = $line[3];
        $msg_error = '';
        if(empty($make)||empty($model)||empty($model_group)||empty($model_desc)){
            $msg_error .= '- Car data is incorrect.';
            $result = false;
        }
        $suminsured = str_replace(',','',$line[5]);
        if(!is_numeric($suminsured)){
            if($msg_error!=""){
                $msg_error .= '\n';
            }
            $msg_error .= '- SumInsured is not number.';
            $result = false;
        }
        $claimtype = $line[6];
        if(!is_numeric($claimtype)){
            if($msg_error!=""){
                $msg_error .= '\n';
            }
            $msg_error .= '- ClaimTypeValue is incorrect.';
            $result = false;
        }else{
            if(intval($claimtype)!=1 && intval($claimtype)!=2){
                if($msg_error!=""){
                $msg_error .= '\n';
                }
                $msg_error .= '- ClaimTypeValue is incorrect.';
                $result = false;
                }
        }
        $agecar = $line[11];
        $agecarmax = $line[12];
        if(!is_numeric($agecar)){
            if($msg_error!=""){
            $msg_error .= '\n';
            }
            $msg_error .= '- AgeCar is incorrect.';
            $result = false;
        }
        if(!is_numeric($agecarmax)){
            if($msg_error!=""){
            $msg_error .= '\n';
            }
            $msg_error .= '- AgeCarMax is incorrect.';
            $result = false;
        }
        $netpremium = str_replace(',','',$line[28]);
        // $stamp = str_replace(',','',$line[29]);
        // $vat = str_replace(',','',$line[30]);
        $totalpremium = str_replace(',','',$line[31]);
        if(!is_numeric($netpremium)){
            if($msg_error!=""){
            $msg_error .= '\n';
            }
            $msg_error .= '- NetPremium is incorrect.';
            $result = false;
        }
        if(!is_numeric($totalpremium)){
            if($msg_error!=""){
            $msg_error .= '\n';
            }
            $msg_error .= '- TotalPremium is incorrect.';
            $result = false;
        }

        $deduct = str_replace(',','',$line[32]);
        $fire = str_replace(',','',$line[33]);
        $tppi_p = str_replace(',','',$line[34]);
        $tppi_c = str_replace(',','',$line[35]);
        $tppd = str_replace(',','',$line[36]);
        $bail = str_replace(',','',$line[37]);
        $pa_driver = str_replace(',','',$line[38]);
        $pa_passenger = str_replace(',','',$line[39]);
        $med = str_replace(',','',$line[40]);
        if(!empty($deduct)){
            if(!is_numeric($deduct)){
                if($msg_error!=""){
                $msg_error .= '\n';
                }
                $msg_error .= '- DeductAmt is incorrect.';
                $result = false;
            }
        }
        if(!empty($fire)){
            if(!is_numeric($fire)){
                if($msg_error!=""){
                $msg_error .= '\n';
                }
                $msg_error .= '- FIRE_THEFT is incorrect.';
                $result = false;
            }
        }
        if(!empty($tppi_p)){
            if(!is_numeric($tppi_p)){
                if($msg_error!=""){
                $msg_error .= '\n';
                }
                $msg_error .= '- TPPI_P is incorrect.';
                $result = false;
            }
        }
        if(!empty($tppi_c)){
            if(!is_numeric($tppi_c)){
                if($msg_error!=""){
                $msg_error .= '\n';
                }
                $msg_error .= '- TPPI_C is incorrect.';
                $result = false;
            }
        }
        if(!empty($tppd)){
            if(!is_numeric($tppd)){
                if($msg_error!=""){
                $msg_error .= '\n';
                }
                $msg_error .= '- TPPD is incorrect.';
                $result = false;
            }
        }
        if(!empty($bail)){
            if(!is_numeric($bail)){
                if($msg_error!=""){
                $msg_error .= '\n';
                }
                $msg_error .= '- Bail Bond is incorrect.';
                $result = false;
            }
        }
        if(!empty($pa_driver)){
            if(!is_numeric($pa_driver)){
                if($msg_error!=""){
                $msg_error .= '\n';
                }
                $msg_error .= '- PA_Driver is incorrect.';
                $result = false;
            }
        }
        if(!empty($pa_passenger)){
            if(!is_numeric($pa_passenger)){
                if($msg_error!=""){
                $msg_error .= '\n';
                }
                $msg_error .= '- PA_Passenger is incorrect.';
                $result = false;
            }
        }
        if(!empty($med)){
            if(!is_numeric($med)){
                if($msg_error!=""){
                $msg_error .= '\n';
                }
                $msg_error .= '- MED is incorrect.';
                $result = false;
            }
        }


        $producttype = $line[47];
        $productTypeCheck = array('1','2+','2','3','3+');
        if(!\in_array($producttype,$productTypeCheck)){
            if($msg_error!=""){
            $msg_error .= '\n';
            }
            $msg_error .= '- ProductType is incorrect.';
            $result = false;
        }


        if(!$result){
            //insert error
            $insData = array(
                "FileName"=>$filename,
                "ErrorRecord"=>$rownum,
                "ErrorMessage"=>$msg_error,
                "CreateDate"=>date("Y-m-d H:i:s"),
            );
            DB::table("mst_fix_premium_error")->insert($insData);
        }
        return $result;
   }
}
