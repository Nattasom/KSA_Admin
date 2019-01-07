<?php

namespace App\Models\API;

use Illuminate\Support\Facades\DB;

class ContentModel 
{
   private $full_url;
   function __construct(){
       $this->full_url = config('app.url');
   }
   public function GetSuggestBanner($lang='th'){
       $resp = array();
       $list = DB::select("SELECT * FROM mst_banner WHERE BannerCode='SUGGEST' AND LanguageCode = ?",[$lang]);
       $path = 'uploads/banner/';
        foreach($list as $key=>$value){
            
            $p_banner1 = $path.$value->Banner1;
            // $type = pathinfo($p_banner1, PATHINFO_EXTENSION);
            // $data1 = file_get_contents($p_banner1);
            // $base64_1 = 'data:image/' . $type . ';base64,' . base64_encode($data1);

            $p_banner2 = $path.$value->Banner2;
            // $type = pathinfo($p_banner2, PATHINFO_EXTENSION);
            // $data2 = file_get_contents($p_banner2);
            // $base64_2 = 'data:image/' . $type . ';base64,' . base64_encode($data2);

          $resp = array(
                "banner_pc"=>$this->full_url.$p_banner1,
                "banner_mb"=>$this->full_url.$p_banner2,
            );  
        }

        return $resp;
   }
   public function GetSliderBannerList($lang = 'th'){
    $data = array();
    $sql = "SELECT a.*,b.BannerName,b.BannerImage,b.BannerImageMobile,b.LanguageCode FROM mst_banner_slider a inner join mst_banner_slider_detail b on a.BannerID=b.BannerID  WHERE a.Status = 'A' AND b.LanguageCode = ?";
    $list = DB::select($sql,[$lang]);
    $resp = array();
    foreach($list as $key=>$value){
        $tmp = $value;
        $tmp->BannerImage = $this->full_url."uploads/banner/".$tmp->BannerImage;
        $tmp->BannerImageMobile = $this->full_url."uploads/banner/".$tmp->BannerImageMobile;
        $resp[] = $tmp;
    }
    return $resp;
   }
   public function GetHomeCategories($lang = 'th'){
        $resp = array();
        $sql = "SELECT * FROM mst_home_category_product a inner join mst_home_category_product_detail b on a.CategoryProductId=b.CategoryProductId  WHERE b.LanguageCode = ? AND a.Status = 'A' Order by a.Seq";
        $list = DB::select($sql,[$lang]);
        $path = 'uploads/home/';
        foreach($list as $key=>$value){

            $icon = $path.$value->Image;
            // $type = pathinfo($icon, PATHINFO_EXTENSION);
            // $data = file_get_contents($icon);
            // $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
            $resp[]=array(
                "id"=>$value->CategoryProductId,
                "icon"=>$this->full_url.$icon,
                "cat_name"=>$value->CategoryProductName,
            );
        }
        return $resp;
   }
   public function GetHomeCatProductList($id,$lang='th',$start=0,$length=6){
        $sql = "SELECT a.CatProductListId,a.Premium_Idx,a.Image as Thumbnail,ins.LogoPath as InsurerIcon,c.CatProductName,p.PromotionCode,p.PromotionName,p.PromotionTag,p.StartDate as ProStartDate,p.EndDate as ProEndDate,b.*
                FROM mst_home_category_product_mapping a 
                inner join mst_fix_premium b on a.Premium_Idx=b.idx  
                left join mst_promotion p on a.PromotionID=p.PromotionID
                left join mst_insurer ins on b.InsurerCode=ins.InsurerCode
                left join mst_home_category_product_mapping_desc c on c.LanguageCode= ? AND a.CatProductListId=c.CatProductListId

                WHERE a.CategoryProductId = ?
                Order By a.Seq
                LIMIT ".$start.",".$length;
         $sqlCount = "SELECT Count(*) as cc
                FROM mst_home_category_product_mapping a 
                WHERE a.CategoryProductId = ?";
        $resp = array();
        $list = DB::select($sql,[$lang,$id]);
        $qCount = collect(\DB::select($sqlCount,[$id]))->first();
        $pathHome = "uploads/home/";
        $pathIns = "uploads/insurer/";
        $pathPromotion = "uploads/promotion/";
        foreach($list as $key=>$value){
            $tmp = $value;
            if(!empty($tmp->Thumbnail)){
                $thumb = $pathHome.$tmp->Thumbnail;
                // $typeThumb = pathinfo($thumb, PATHINFO_EXTENSION);
                // $dataThumb = file_get_contents($thumb);
                // $base64Thumb = 'data:image/' . $typeThumb . ';base64,' . base64_encode($dataThumb);
                $tmp->Thumbnail = $this->full_url.$thumb;
            }
            if(!empty($tmp->InsurerIcon)){
                $iconIns = $pathIns.$tmp->InsurerIcon;
                // $typeIns = pathinfo($iconIns, PATHINFO_EXTENSION);
                // $dataIns = file_get_contents($iconIns);
                // $base64Ins = 'data:image/' . $typeIns . ';base64,' . base64_encode($dataIns);
                $tmp->InsurerIcon = $this->full_url.$iconIns;
            }
            if(!empty($tmp->PromotionTag)){
                $protag = $pathPromotion.$tmp->PromotionTag;
                // $typeIns = pathinfo($iconIns, PATHINFO_EXTENSION);
                // $dataIns = file_get_contents($iconIns);
                // $base64Ins = 'data:image/' . $typeIns . ';base64,' . base64_encode($dataIns);
                $tmp->PromotionTag = $this->full_url.$protag;
            }
            $resp[] = $tmp;
        }
        $data["start"] = $start;
        $data["length"] = $length;
        $data["num_rows"] = $qCount->cc;
        $data["list"] = $resp;
        return $data;
   }
   public function GetHomeCatDetail($cat,$id,$lang){
        $sql = "SELECT cdesc.CatProductName as ProductName,cdesc.CatProductDesc1 as ProductDesc1,cdesc.CatProductDesc2 as ProductDesc2,cdesc.CatProductDesc3 as ProductDesc3,insd.InsurerName,ins.LogoPath as InsurerIcon,a.*
            FROM    mst_fix_premium a
		inner join mst_insurer ins ON a.InsurerCode=ins.InsurerCode
		inner join mst_insurer_detail insd ON ins.InsurerCode=insd.InsurerCode AND insd.LanguageCode= :lang1
		inner join mst_home_category_product_mapping cat ON cat.Premium_Idx=a.idx  
        left join mst_home_category_product_mapping_desc cdesc on cdesc.LanguageCode= :lang2 AND cat.CatProductListId=cdesc.CatProductListId
                WHERE a.idx = :idx AND cat.CategoryProductId = :cat
                ";
        $where["lang1"] = $lang;
        $where["lang2"] = $lang;
        $where["idx"] = $id;
        $where["cat"] = $cat;
        $data = collect(\DB::select($sql,$where))->first();
        $pathIns = "uploads/insurer/";
        if(!empty($data->InsurerIcon)){
            $iconIns = $pathIns.$data->InsurerIcon;
            // $typeIns = pathinfo($iconIns, PATHINFO_EXTENSION);
            // $dataIns = file_get_contents($iconIns);
            // $base64Ins = 'data:image/' . $typeIns . ';base64,' . base64_encode($dataIns);
            $data->InsurerIcon = $this->full_url.$iconIns;
        }

        return $data;
   }
   public function GetContentDetail($lang='th',$id){
        $sql = "SELECT a.*,b.Title,b.Thumbnail,b.Description
                FROM mst_content a 
                    inner join mst_content_detail b on b.LanguageCode= ? AND a.ContentID=b.ContentID

                WHERE a.ContentID=?";
        $data = collect(\DB::select($sql,[$lang,$id]))->first();

        return $data;
   }
   public function GetContentList($lang='th',$start=0,$length=4){
        $sql = "SELECT a.*,b.Title,b.Thumbnail,b.Description
                FROM mst_content a 
                    inner join mst_content_detail b on b.LanguageCode= ? AND a.ContentID=b.ContentID

                WHERE a.Status='A'
                Order By a.UpdateDate desc
                LIMIT ".$start.",".$length;
         $sqlCount = "SELECT Count(*) as cc
                FROM mst_content a 
                WHERE a.Status='A'";
        $resp = array();
        $list = DB::select($sql,[$lang]);
        $qCount = collect(\DB::select($sqlCount))->first();
        $pathContent = "uploads/content/";
        foreach($list as $key=>$value){
            $tmp = $value;
            if(!empty($tmp->Thumbnail)){
                $thumb = $pathContent.$tmp->Thumbnail;
                // $typeThumb = pathinfo($thumb, PATHINFO_EXTENSION);
                // $dataThumb = file_get_contents($thumb);
                // $base64Thumb = 'data:image/' . $typeThumb . ';base64,' . base64_encode($dataThumb);
                $tmp->Thumbnail = $this->full_url.$thumb;
            }
            $resp[] = $tmp;
        }
        $data["start"] = $start;
        $data["length"] = $length;
        $data["num_rows"] = $qCount->cc;
        $data["list"] = $resp;
        return $data;
   }
}
