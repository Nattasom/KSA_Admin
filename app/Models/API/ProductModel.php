<?php

namespace App\Models\API;

use Illuminate\Support\Facades\DB;

class ProductModel 
{
    private $full_url;
   function __construct(){
       $this->full_url = config('app.url');
   }

   public function GetProductList($params = array(),$lang,$start,$length){
       $where = array();
       $whereCount = array();
       $sql = "SELECT pd.ProductName,pro.PromotionName,pro.PromotionTag,pro.PromotionCode,pro.StartDate as ProStartDate,pro.EndDate as ProEndDate,insd.InsurerName,ins.LogoPath as InsurerIcon,a.*
            FROM    mst_fix_premium a
		inner join mst_insurer ins ON a.InsurerCode=ins.InsurerCode
		inner join mst_insurer_detail insd ON ins.InsurerCode=insd.InsurerCode AND insd.LanguageCode= :lang1
		inner join mst_product p ON p.ProductId IN (SELECT ProductId FROM mst_product WHERE InsurerCode = a.InsurerCode
		AND (MakeValue IS NULL OR MakeValue=a.MakeValue) AND (ModelValue IS NULL OR ModelValue=a.ModelValue) 
		AND (ProductType IS NULL OR ProductType=a.ProductType) 
		AND (ClaimTypeValue IS NULL OR (ClaimTypeValue*1)=(a.ClaimTypeValue*1)) 
		AND (CarGroup IS NULL OR CarGroup=a.CarGroup) 
		AND (SumInsuredMax IS NULL OR SumInsuredMin IS NULL OR (a.SumInsured*1) BETWEEN (SumInsuredMin*1) AND (SumInsuredMax*1)) 
		AND (PremiumMax IS NULL OR PremiumMin IS NULL OR (a.NetPremium*1) BETWEEN (PremiumMin*1) AND (PremiumMax*1)) )
		inner join mst_product_detail pd ON p.ProductId=pd.ProductId AND pd.LanguageCode = :lang2
        left join mst_promotion pro ON p.PromotionID=pro.PromotionID
                WHERE 1=1
                ";
        $where["lang1"] = $lang;
        $where["lang2"] = $lang;
         $sqlCount = "SELECT Count(*) as cc
                FROM mst_fix_premium a
                    inner join mst_product p ON p.ProductId IN (SELECT ProductId FROM mst_product WHERE InsurerCode = a.InsurerCode
            AND (MakeValue IS NULL OR MakeValue=a.MakeValue) AND (ModelValue IS NULL OR ModelValue=a.ModelValue) 
            AND (ProductType IS NULL OR ProductType=a.ProductType) 
            AND (ClaimTypeValue IS NULL OR (ClaimTypeValue*1)=(a.ClaimTypeValue*1)) 
            AND (CarGroup IS NULL OR CarGroup=a.CarGroup) 
            AND (SumInsuredMax IS NULL OR SumInsuredMin IS NULL OR (a.SumInsured*1) BETWEEN (SumInsuredMin*1) AND (SumInsuredMax*1)) 
            AND (PremiumMax IS NULL OR PremiumMin IS NULL OR (a.NetPremium*1) BETWEEN (PremiumMin*1) AND (PremiumMax*1)) )
            WHERE 1=1";
        if(!empty($params["make_value"])){
            $sql .="    AND a.MakeValue = :make_value";
            $sqlCount .="   AND a.MakeValue = :make_value";
            $where["make_value"] = $params["make_value"];
            $whereCount["make_value"] = $params["make_value"];
        }
        if(!empty($params["model_value"])){
            $sql .="    AND a.ModelValue = :model_value";
            $sqlCount .="   AND a.ModelValue = :model_value";
            $where["model_value"] = $params["model_value"];
            $whereCount["model_value"] = $params["model_value"];
        }
        if(!empty($params["model_year"])){
            $this_year = date("Y");
            $bind_year = ($this_year-$params["model_year"]) + 1;
            $sql .="    AND ((IFNULL(a.AgeCar,0)*1) <= :model_year AND (IFNULL(a.AgeCarMax,0)*1) >= :model_year1) ";
            $sqlCount .="   AND ((IFNULL(a.AgeCar,0)*1) <= :model_year AND (IFNULL(a.AgeCarMax,0)*1) >= :model_year1)";
            $where["model_year"] = $bind_year;
            $where["model_year1"] = $bind_year;
            $whereCount["model_year"] = $bind_year;
            $whereCount["model_year1"] = $bind_year;
        }
        if(!empty($params["product_type"])){
            $arr = explode(',',$params["product_type"]);
            $strRe = "";
            foreach($arr as $k=>$v){
                if($k!=0){
                    $strRe .=",";
                }
                $strRe .= "'".$v."'";
            }
            $sql .="    AND a.ProductType IN (".$strRe.")";
            $sqlCount .="   AND a.ProductType IN (".$strRe.")";
        }
        if(!empty($params["suminsured"])){
            $sql .="    AND (a.SumInsured*1)  <= :suminsured";
            $sqlCount .="   AND (a.SumInsured*1) <= :suminsured";
            $where["suminsured"] = $params["suminsured"];
            $whereCount["suminsured"] = $params["suminsured"];
        }
        if(!empty($params["premium"])){
            $sql .="    AND (a.NetPremium*1)  <= :premium";
            $sqlCount .="   AND (a.NetPremium*1) <= :premium";
            $where["premium"] = $params["premium"];
            $whereCount["premium"] = $params["premium"];
        }
        if(!empty($params["claimtype"])){
            $sql .="    AND (a.ClaimTypeValue*1)  = :claimtype";
            $sqlCount .="   AND (a.ClaimTypeValue*1) = :claimtype";
            $where["claimtype"] = $params["claimtype"];
            $whereCount["claimtype"] = $params["claimtype"];
        }
        if(!empty($params["insurer_code"])){
            $arr = explode(',',$params["insurer_code"]);
            $strRe = "";
            foreach($arr as $k=>$v){
                if($k!=0){
                    $strRe .=",";
                }
                $strRe .= "'".$v."'";
            }
            $sql .="    AND a.InsurerCode IN (".$strRe.")";
            $sqlCount .="   AND a.InsurerCode IN (".$strRe.")";
        }
        if(!empty($params["tppd_min"])){
            $sql .="    AND (a.TPPD*1)  >= :tppd_min";
            $sqlCount .="   AND (a.TPPD*1) >= :tppd_min";
            $where["tppd_min"] = $params["tppd_min"];
            $whereCount["tppd_min"] = $params["tppd_min"];
        }
        if(!empty($params["tppd_max"])){
            $sql .="    AND (a.TPPD*1)  <= :tppd_max";
            $sqlCount .="   AND (a.TPPD*1) <= :tppd_max";
            $where["tppd_max"] = $params["tppd_max"];
            $whereCount["tppd_max"] = $params["tppd_max"];
        }
        $order_type = "asc";
        if(!empty($params["order_field"])){
            if(!empty($params["order_type"])){
                $order_type = $params["order_type"];
            }
            switch ($params["order_field"]) {
                case 'insurer':
                    # code...
                    $sql .=" Order By insd.InsurerName ".$order_type;
                    break;
                
                default:
                    # code...
                    $sql .=" Order By (a.NetPremium*1) ".$order_type;
                    break;
            }
        }
        else{
            $sql .=" Order By (a.NetPremium*1) ".$order_type;
        }
        //order condition
        if(!empty($params["make_value"])){
            $sql .=",-p.MakeValue DESC";
        }
        
        //Order By a.Seq
        $sql .=" LIMIT ".$start.",".$length;
        $resp = array();
        $list = DB::select($sql,$where);
        $qCount = collect(\DB::select($sqlCount,$whereCount))->first();
        $pathIns = "uploads/insurer/";
        $pathPromotion = "uploads/promotion/";
        foreach($list as $key=>$value){
            $tmp = $value;
            // if(!empty($tmp->Thumbnail)){
            //     $thumb = $pathHome.$tmp->Thumbnail;
            //     $typeThumb = pathinfo($thumb, PATHINFO_EXTENSION);
            //     $dataThumb = file_get_contents($thumb);
            //     $base64Thumb = 'data:image/' . $typeThumb . ';base64,' . base64_encode($dataThumb);
            //     $tmp->Thumbnail = $base64Thumb;
            // }
            if(!empty($tmp->InsurerIcon)){
                $iconIns = $pathIns.$tmp->InsurerIcon;

                $tmp->InsurerIcon = $this->full_url.$iconIns;
            }
            if(!empty($tmp->PromotionTag)){
                $protag = $pathPromotion.$tmp->PromotionTag;

                $tmp->PromotionTag = $this->full_url.$protag;
            }
            $resp[] = $tmp;
        }
        $data["start"] = $start;
        $data["length"] = $length;
        $data["num_rows"] = $qCount->cc;
        $data["list"] = $resp;
        return $data;
    //    if($params["page"] > 1){
    //         //header
    //         $response["page"] = 2;
    //         $response["perpage"] = $this->records_per_lap;
    //         $response["numrows"] = 5;
    //         $response["is_last"] = true;

    //         //list
    //         $response["data"][] = array(
    //             "insurer_code"=>"TIP",
    //             "insurer_icon_b64"=>"data:image/png;base64,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",
    //             "ribbon_icon_b64"=>"",
    //             "product_name"=>"ประกันรถยนต์ 3 พลัส",
    //             "fix_premium"=>"5555",
    //             "product_type"=>"3+",
    //             "suminsured"=>"100000",
    //             "deduct_amt"=>"",
    //             "claim_type"=>"01",
    //             "claim_type_text"=>"อู่",
    //             "tppd"=>"1000000",
    //         );
    //         $response["data"][] = array(
    //             "insurer_code"=>"AIG",
    //             "insurer_icon_b64"=>"data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADcAAAAsCAYAAADByiAeAAAGD0lEQVRoBe2ZaWxUVRTHf2/WztbOdGhLwbZCG5YKyCrRqKCoVAx+wkAwfIDERAVUEhMTP7hhAgnEaFxCNKhEJYoEoxAXDAFiAkrUahQp0E0KdgPKzJuZzv7Mfe1g582bdqYkZiC9yeS+e9+5557/2e65byRFURRu0Ga4QXGpsMbAXa/WHbPcmOUKUANjblmARslJpBvaciatCn7tDdMRjGMxSNpXBTuOJBWmlliY7rGkyZgB7q2TPj5oDoDAlkyjLcyBUYKEwgtzPbw8rzRNxgxwNpMBTBKP1TqZaDehULilpxGJJn+U3W0hbAKkpmWAU98nYcMtJcwqtWrIC2/4bUeQ3WeCuoJlTSiBWOFabCiSUFwZCKGhk4PP+pbTIbyWqYSiIC5WRklCyvSeNNadoQS+aALhZeU2EyWWrPpPW6c3uCZwhy6EeP3PK+y7vxLzMNl107GL7LnQz2d3jmPRBLueHOxpkfmoWeZAXwyiSdUa1TYjyypsPFFfzCxv/iEyerUA7zX5OdAe4vvzIV2BU5NtgRjdfTHkWGb69UWTrDncxcrDPRw4389ks8SycitLvBbOxZLsOOXj1m86+fC0L8Uu537Ulmu8GOazf/qhyMjO036WVTuyblokfMwkqW45lCiSUFh3tJt9bUHqvBa2zPFw30QHbuuAztvkKB80yWz+y8fao72UFZl4qCb7PkN5i+dRg3v3lF89XzAb2NcV5qeeMAvLi7T8hx2/KVy6PUR9mZWv7xtPjcucRj/JZeGVBV4mOIwc7wlze0V+/Efllqd9UXa0B7nNY+GFqS6IJFXrpUk2wuByJMlrZ2SwSLy90JsBbOjyx+vd7Fo8ntIi49DpEZ9HBW6nsFoowapJDp6fU4q72MR7fwdp8UVH3DBF8HNPP51ynCVlVhZnSTIp2tH2eYPrCMTY1hoAl4kVk51YjRIbJjkhmOBDYYkcW3sgrrr1tJL0ejC1XOwjXF3UuuL3S2+YEz1h/DpJKbVG2+cdc7tOyyDHeWV+KVXOgRhZP8PNq80y21sDPDXDTZltZPdJihpBUfOMViZ1/M5JH1sbr0DRoP5FWZhQONYwntsrbLprtJN5gbsYTvBWiwx2Ix6rge86Bo4AkQzvKTFzuDPMp81+Ns70aPfJGFfaTWCEFjmW8U5M1HssNNTYcVsMxBWFvb1R9exzmnN3trzAfXLWT3coIUoNNp64DKr6B2UTm1oM7GwJsG56CQ6h6WHa/DILOEwc6IkgjpU549Iz4ZopxYifaE1Xouz96gLVdiNVjvSMOswWuR8F4gDecTagAltbbafYbFBLqhRzUVYd7g7z++UY+9sDrKobECz1XttPdJh59mYH2//w8fSPl/ji/vF4rfruvPW3yxBJ8PBk59UzUMtPb5yz5fa1yjRditJQZeP9RRV6vNjd7OfRI728e0bmkVpXxqGtXfTcbA+Huvr5oSvM8oOdbJnnZdGE/+LpXCDGlsY+drWEwGXmmVklWhbDjnMCJyrvN0T6l+DJadk3WF7jpM7jU2NPlGQNVQPVRFy4b1JJ82Ih1bgiI5/fW8HqIz0c74qw+PsuHii3Uucy448l+LInguyLq5n50N1l1BbrZ9ZsCIcPjMFVBzuCNPriLJ1oY2m1fuErSF1mA+unOMEgsbtZvgrGPhiPemEoBP6uYQIvzXUz2WnkYHeYd5r8fNwWQk4qrJ3i5NSDldw7irMwJ8vdVWmjY8VNuC3GEb+tbJzhZmVtsXqDT91u3ryjnG0LFbyDNaNW0yIjvjjPy6aZHpp9UfqiSUQ9WuMycVMeCUTLNydwXrXs0Q92LUNxZ6u0p9OWZgGlXVtsMTC3LD1ramnyGWd1S6c5pfd82P3/tA6TpBYDqZ0vBGN0BGPqlx99y0mwtzVI48VIWrpPMSiUXhw/jZciapkjCpkr0ST7z4WIKgrLq+yZ51wgloBYks2NfRQ0spSGxReApEJ/QiGcVNRYtUoS4YTOfW51nYvZXqtKlFpf6L0AJu565VYjC8qsiG9GtS4z0th/4oVuuizyZc2WWeivq+kxcNeVuYYIO2a5Icq4rh5vaMv9Cww086ftkvlYAAAAAElFTkSuQmCC",
    //             "ribbon_icon_b64"=>"",
    //             "product_name"=>"Eco Choice Extra 3+",
    //             "fix_premium"=>"7200",
    //             "product_type"=>"3+",
    //             "suminsured"=>"100000",
    //             "deduct_amt"=>"",
    //             "claim_type"=>"01",
    //             "claim_type_text"=>"อู่",
    //             "tppd"=>"2500000",
    //         );
            
    //    }
    //    else{
    //         //header
    //         $response["page"] = 1;
    //         $response["perpage"] = $this->records_per_lap;
    //         $response["numrows"] = 5;
    //         $response["is_last"] = false;

    //         //list
    //         $response["data"][] = array(
    //             "insurer_code"=>"TWW",
    //             "insurer_icon_b64"=>"data:image/png;base64,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",
    //             "ribbon_icon_b64"=>"data:image/png;base64,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",
    //             "product_name"=>"ประกันเปิด-ปิด ประเภท 3+ ",
    //             "fix_premium"=>"4000",
    //             "product_type"=>"3+",
    //             "suminsured"=>"200000",
    //             "deduct_amt"=>"1000",
    //             "claim_type"=>"01",
    //             "claim_type_text"=>"อู่",
    //             "tppd"=>"1500000",
    //         );
    //         $response["data"][] = array(
    //             "insurer_code"=>"ALZ",
    //             "insurer_icon_b64"=>"data:image/png;base64,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",
    //             "ribbon_icon_b64"=>"",
    //             "product_name"=>"ป3+ ซุปเปอร์จิ๋ว ซ่อมอู่ ไม่มี DD ทุน 50000 ทุนประกัน 50000",
    //             "fix_premium"=>"4080",
    //             "product_type"=>"3+",
    //             "suminsured"=>"50000",
    //             "deduct_amt"=>"",
    //             "claim_type"=>"02",
    //             "claim_type_text"=>"ห้าง",
    //             "tppd"=>"200000",
    //         );
    //         $response["data"][] = array(
    //             "insurer_code"=>"MSIG",
    //             "insurer_icon_b64"=>"data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACwAAAAsCAYAAAAehFoBAAAGsUlEQVRYCe1WXWxcRxX+zszdXXvX6+v9yTp2vd67m40TEzshdUtTCEqQSkUTIlR4KKFphYCHVgUJKiQElUCklfIWVcpDX4GkihRVVPyotPy80KpKSF2J1k0TY3djO2ni2F6vs7br9d45B82atewoDZWAkIe9D3vnzvnOOd98c+bMAo2noUBDgYYCDQUaCjQUuIMUoE/KZSq3o0cbusch83brxXfPf1K//zbO+XcBp7LbtwSU+mFA5KvhgIoXWb4N4P9G+JZ8p3J9T5RyO6Ykf7dIfqdMettfuKXDbTCqj8sxne17LqoCL4RAyVn2cQxx3BPYmY9ufbzn43xux/xNCV/L9j8b1YFnlllQgcGHcPAnckFO6IF2wZ9zPQd33w5yN8uhb5yczPYdatOB5ys1sgwBIQGDHlnEq9SKqgq5DtTDbrxvaLb4zj9u9P9ff68jXM7v3GCAUwFR7hIY9RbSIsDb1IzfqyQgBlo5zQAeiiX73pideXeiTjKXy7mxSOTLbYlEsFQqTebz+VAsGn0wEYt1FUulixaXTqc3bYjFHo21tXV1dnWNTk1NGc/zdiWTSbdYLE5ZjOd5G+PR6OPxRGJvwnWXinNzV+o51pXEdTEHW5VOL8LU7bArKhHhpGpHBar2zVyFUjoG0Se6Nh3K18EissUJBl8C86/sHC8vf15p/QqL1A5rNpvNOFr/AUTfAPM+rXXA4siYI1ypPGnH+UzmfmL+m2j9BID9zPytenz7Xt/WmL7oaIKsQYQheAku3lAuwmsWYkkHnLBHWHoMwM+si2bWPlAlkdbNmUyvIdotIjaeb+2+73eHHGezMebZwsTEcUysbA4RCQFsMQIcAdGFaiTySGhuLg6i2Bo6WKfwDJyCIlotBatuGYTfqWQtWr1E6gFWyPjX699GqYAABQLeqQLfI+ZdbMwfibm2ZalU6jQzH1VER3Oe99tsNtteI0nEhqi2KAY2EfCLpvn5DBznr6LU4b17964Ku47w35uSRz7w/fOuWintEARDaMZZFUVkjbo2idYhGLN4WoN/WSfMzFqJGCH6jQK+I0AzEb0uRBGLGRwc9JtbWn5MSj3lKHWAmB+o+TJrDdTKAyKDIvKUT1QAcAoiveVyeVWrdYQfe/+1K2fQ+shlg2ttpGpKn6EWzMNZtxVaB2FM5b1l8KPDwyen64QDRMxKOUz0iogswp5VpcZIqVqV9fT0ZD8ql88YkSO+MSOi1FvWl7Sugmjl4Gj9IwJcLXJGRL6miK4cOHBg9VCtMq8nte9fe/fvGVCVk93EHT+gjtqBi/5L4Zqy/vJQMMAPnzt3YmStn+d5TVrrDaOjoxNb0unOUCw2Z+0LCwtxOzcwMBAoTU/fa4iiwWBwcHh4uLZY2xWaqtXq+cuXZyzedhuuVndppRQcZ8j61vPclLA1Hs/u3vEQlY+fcjr6D9NdsISVcmDM8pua5NCFC8ftlt32Z10fXpv95dL45B63++WYmE2XKJS5SqF5kuqL/vL1b46Onrq6FnvHjQ9vfvC+HZ86uO2OI2YJ2XryPO8r3d3d6/rfLcjqTCbTkc/nW2/AqN5MpmPbtm3B+ryt4fq4/u7s7AyvbVt2fnt7e+RmWGtbWxI00NkZXtZ6D5ifBNFfYrHYUigUCi0sLFRtEO26gVQqFZudnV3c6nmem0h0aa0XQ8HgMTFGp9rbP5yZmbHdAZlM5ksG2E9KvRWLxch13a7KwsL32+Lxc8lkUheLxWXP89q0yNNz5XJLOBy+5Lqus7G1tb0SDD69tLhoItHo1VQqFS4Wi9WVOwWrdwQGBgbCM9PTz0FkSYvEhWiSlJoxzOri+PjzOc/7ORkTNkBcaT0kIvshcrUwPn4o1939UwCdRBQm4LWRsbEXM+n0M1qpT38wNvb1nlzuC74x7Qx8joA0iZyFyDQpdZcAPQxMEfN7EKkorT/DInFSakgBTWKMw45zrFAojFkhVm+Q+fl5TUAvEc3D9k2RhNh+SHTWAoU5Q1qHiTkozPcBCBIwb21EFGKRjRBpFqXStcBEr4rIvv7+/taluTlikX0O0TWjVJFEPgvHGWSRFi1SJqKkAe4mpSJCFAYwIcxbmShEWiut9WofXiXc0dHx0aVC4ScsEgFRiyhV0MC94vunLQFofZSYNRFllch5I5IXx6n9A1PACdY6QCK9jjGvW/jo+PhgPpP5LjMvNFcqb3Jzc0IRvS/MCaOUIeCivQlZ66g2xorUIVqH4fslDRR9pYIgSgmwVBgZuVTj0PhpKNBQoKFAQ4GGAg0FGgr8hwr8EzaiqO51WHT+AAAAAElFTkSuQmCC",
    //             "ribbon_icon_b64"=>"",
    //             "product_name"=>"SG Easy 1",
    //             "fix_premium"=>"4900",
    //             "product_type"=>"3+",
    //             "suminsured"=>"100000",
    //             "deduct_amt"=>"2000",
    //             "claim_type"=>"01",
    //             "claim_type_text"=>"อู่",
    //             "tppd"=>"1000000",
    //         );
    //    }
   }
   public function GetProduct($id,$lang){
       $sql = "SELECT pd.ProductName,pd.ProductDesc1,pd.ProductDesc2,pd.ProductDesc3,insd.InsurerName,ins.LogoPath as InsurerIcon,a.*
            FROM    mst_fix_premium a
		inner join mst_insurer ins ON a.InsurerCode=ins.InsurerCode
		inner join mst_insurer_detail insd ON ins.InsurerCode=insd.InsurerCode AND insd.LanguageCode= :lang1
		inner join mst_product p ON p.ProductId = (SELECT ProductId FROM mst_product WHERE InsurerCode = a.InsurerCode
		AND (MakeValue IS NULL OR MakeValue=a.MakeValue) AND (ModelValue IS NULL OR ModelValue=a.ModelValue) 
		AND (ProductType IS NULL OR ProductType=a.ProductType) 
		AND (ClaimTypeValue IS NULL OR (ClaimTypeValue*1)=(a.ClaimTypeValue*1)) 
		AND (CarGroup IS NULL OR CarGroup=a.CarGroup) 
		AND (SumInsuredMax IS NULL OR SumInsuredMin IS NULL OR (a.SumInsured*1) BETWEEN (SumInsuredMin*1) AND (SumInsuredMax*1)) 
		AND (PremiumMax IS NULL OR PremiumMin IS NULL OR (a.NetPremium*1) BETWEEN (PremiumMin*1) AND (PremiumMax*1))
		LIMIT 1)
		inner join mst_product_detail pd ON p.ProductId=pd.ProductId AND pd.LanguageCode = :lang2
                WHERE a.idx = :idx
                ";
        $where["lang1"] = $lang;
        $where["lang2"] = $lang;
        $where["idx"] = $id;
        $data = collect(\DB::select($sql,$where))->first();
        $pathIns = "uploads/insurer/";
        if(!empty($data->InsurerIcon)){
            $iconIns = $pathIns.$data->InsurerIcon;
            $typeIns = pathinfo($iconIns, PATHINFO_EXTENSION);
            $dataIns = file_get_contents($iconIns);
            $base64Ins = 'data:image/' . $typeIns . ';base64,' . base64_encode($dataIns);
            $data->InsurerIcon = $base64Ins;
        }

        return $data;
   }
}
