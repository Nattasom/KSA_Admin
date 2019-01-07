<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use App\Models\CoreModel;
use Session;

class ContentModel 
{
    private $core;
    public function __construct(){
        $this->core = new CoreModel();
    }
   public function getHomeCatTable($resp = array()){
       $where = array();
       $data = array();
        $rowsTotal = 10;
        $searchText = $resp["search"]["value"];
        $sqlCount = "SELECT Count(*) as cc FROM mst_home_category_product a inner join mst_home_category_product_detail b on a.CategoryProductId=b.CategoryProductId AND b.LanguageCode = 'th'  WHERE 1=1 ";
        $sql = "SELECT * FROM mst_home_category_product a inner join mst_home_category_product_detail b on a.CategoryProductId=b.CategoryProductId AND b.LanguageCode = 'th' WHERE 1=1";
        if(!empty($searchText)){
            $sql .=" AND (b.CategoryProductName LIKE :search)";
            $sqlCount .=" AND (b.CategoryProductName LIKE :search)";
            $where["search"] = "%".$searchText."%";
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
            if(!empty($item->Image)){
                $image = config("app.root_path")."/uploads/home/".$item->Image;
                $image = '<img width="120" src="'.$image.'" />';
            }
            $code = $item->CategoryProductId;
            $name =$item->CategoryProductName;
            if($item->Status == "A"){
                $statusText = '<span class="label label-success">Active</span>';
                $btnActive = '<a  href ="javascript:setActive(\'I\',\''.$code.'\');"  class="btn btn-danger btn-xs" >Inactive</a>';

            }else{
                $statusText = '<span class="label label-danger">Inactive</span>';
                $btnActive = '<a  href ="javascript:setActive(\'A\',\''.$code.'\');"  class="btn btn-success btn-xs" >Active</a>';
            }
            $count_list = 0;
            $chkCount = collect(\DB::select("SELECT Count(*) as cc FROM mst_home_category_product_mapping WHERE CategoryProductId = ?",[$item->CategoryProductId]))->first();
            $count_list = $chkCount->cc;

            $editGroup = '';
            if(in_array('EDIT',Session::get('userinfo')->permission['page_3']['actions']))
            {
                $editGroup='<a  href ="'.url("/homecat-edit",[$code]).'" class="btn btn-primary btn-xs btn-edit" >Edit</a> <a  href ="'.url("/homecat-product",[$code]).'" class="btn btn-info btn-xs btn-edit" >Set Product Card</a> <a  href ="'.url("/homecat-product-list",[$code]).'" class="btn btn-warning btn-xs " >Product Card List <span  class="badge">'.$count_list.'</span></a> '.$btnActive;
            }

            $data["data"][] = array(
                $row,
                $image,
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
   public function getBannerSliderTable($resp = array()){
       $where = array();
       $data = array();
        $rowsTotal = 10;
        $searchText = $resp["search"]["value"];
        $sqlCount = "SELECT Count(*) as cc FROM mst_banner_slider a inner join mst_banner_slider_detail b on a.BannerID=b.BannerID AND b.LanguageCode = 'th'  WHERE 1=1 ";
        $sql = "SELECT * FROM mst_banner_slider a inner join mst_banner_slider_detail b on a.BannerID=b.BannerID AND b.LanguageCode = 'th' WHERE 1=1";
        if(!empty($searchText)){
            $sql .=" AND (b.BannerName LIKE :search)";
            $sqlCount .=" AND (b.BannerName LIKE :search)";
            $where["search"] = "%".$searchText."%";
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
            if(!empty($item->BannerImage)){
                $image = config("app.root_path")."/uploads/banner/".$item->BannerImage;
                $image = '<img width="120" src="'.$image.'" />';
            }
            $code = $item->BannerID;
            $name =$item->BannerName;
            $link = $item->BannerLink;
            if($item->Status == "A"){
                $statusText = '<span class="label label-success">Active</span>';
                $btnActive = '<a  href ="javascript:setActive(\'I\',\''.$code.'\');"  class="btn btn-danger btn-xs" >Inactive</a>';

            }else{
                $statusText = '<span class="label label-danger">Inactive</span>';
                $btnActive = '<a  href ="javascript:setActive(\'A\',\''.$code.'\');"  class="btn btn-success btn-xs" >Active</a>';
            }
            $editGroup = '';
            if(in_array('EDIT',Session::get('userinfo')->permission['page_1']['actions']))
            {
                $editGroup = '<a  href ="'.url("/banner-slider/edit",[$code]).'" class="btn btn-primary btn-xs btn-edit" >Edit</a>  '.$btnActive;
            }
            $data["data"][] = array(
                $image,
                $name,
                $link,
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
   public function getContentTable($resp = array()){
       $where = array();
       $data = array();
        $rowsTotal = 10;
        $searchText = $resp["search"]["value"];
        $sqlCount = "SELECT Count(*) as cc FROM mst_content a inner join mst_content_detail b on a.ContentID=b.ContentID AND b.LanguageCode = 'th'  WHERE 1=1 ";
        $sql = "SELECT * FROM mst_content a inner join mst_content_detail b on a.ContentID=b.ContentID AND b.LanguageCode = 'th' WHERE 1=1";
        if(!empty($searchText)){
            $sql .=" AND (b.Title LIKE :search)";
            $sqlCount .=" AND (b.Title LIKE :search)";
            $where["search"] = "%".$searchText."%";
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
            if(!empty($item->Thumbnail)){
                $image = config("app.root_path")."/uploads/content/".$item->Thumbnail;
                $image = '<img width="120" src="'.$image.'" />';
            }
            $code = $item->ContentID;
            $name =$item->Title;
            if($item->Status == "A"){
                $statusText = '<span class="label label-success">Active</span>';
                $btnActive = '<a  href ="javascript:setActive(\'I\',\''.$code.'\');"  class="btn btn-danger btn-xs" >Inactive</a>';

            }else{
                $statusText = '<span class="label label-danger">Inactive</span>';
                $btnActive = '<a  href ="javascript:setActive(\'A\',\''.$code.'\');"  class="btn btn-success btn-xs" >Active</a>';
            }
            $editGroup = '';
            if(in_array('EDIT',Session::get('userinfo')->permission['page_2']['actions']))
            {
                $editGroup = '<a  href ="'.url("/content/edit",[$code]).'" class="btn btn-primary btn-xs btn-edit" >Edit</a>  '.$btnActive;
            }
            $data["data"][] = array(
                $image,
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
   public function getHomeCatProductListTable($resp = array()){
        $where = array();
       $data = array();
        $rowsTotal = 10;
        $searchText = $resp["search"]["value"];
        $sqlCount = "SELECT Count(*) as cc FROM mst_home_category_product_mapping a 
                inner join mst_fix_premium b on a.Premium_Idx=b.idx   
                left join mst_home_category_product_mapping_desc c on c.LanguageCode='th' AND a.CatProductListId=c.CatProductListId
                WHERE 1=1 ";
        $sql = "SELECT a.CatProductListId,a.Premium_Idx,a.Image,c.CatProductName,b.MakeValue,b.ModelValue,b.SumInsured,b.TotalPremium,b.ProductType FROM mst_home_category_product_mapping a 
                inner join mst_fix_premium b on a.Premium_Idx=b.idx  
                left join mst_home_category_product_mapping_desc c on c.LanguageCode='th' AND a.CatProductListId=c.CatProductListId
                WHERE 1=1";
        $sql .=" AND a.CategoryProductId = :cat_id";
        $sqlCount .=" AND a.CategoryProductId = :cat_id";
        $where["cat_id"] = $resp["cat_id"];
        if(!empty($searchText)){
            $sql .=" AND (c.CatProductName LIKE :search)";
            $sqlCount .=" AND (c.CatProductName LIKE :search)";
            $where["search"] = "%".$searchText."%";
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
            if(!empty($item->Image)){
                $image = config("app.root_path")."/uploads/home/".$item->Image;
                $image = '<img width="120" src="'.$image.'" />';
            }
            $code = $item->CatProductListId;
            $name =$item->CatProductName;
            $makeval = $item->MakeValue;
            $modelval = $item->ModelValue;
            $suminsured = $item->SumInsured;
            $premium = $item->TotalPremium;
            $ptype = $item->ProductType;
            // if($item->Status == "A"){
            //     $statusText = '<span class="label label-success">Active</span>';
            //     $btnActive = '<a  href ="javascript:setActive(\'I\',\''.$code.'\');"  class="btn btn-danger btn-xs" >Inactive</a>';

            // }else{
            //     $statusText = '<span class="label label-danger">Inactive</span>';
            //     $btnActive = '<a  href ="javascript:setActive(\'A\',\''.$code.'\');"  class="btn btn-success btn-xs" >Active</a>';
            // }
            $data["data"][] = array(
                $row,
                $image,
                $name,
                $makeval,
                $modelval,
                number_format($suminsured,2),
                number_format($premium,2),
                $ptype,
                '<input type="number" class="form-control" style="width:80px;" onblur = "setSort(this)" data-id="'.$code.'"  />',
                '<a  href ="'.url("/homecat-product-edit",[$code]).'" class="btn btn-primary btn-xs btn-edit" >Edit</a> <a data-toggle="modal" href="#wide" class="btn btn-info btn-xs btn-view" data-idx="'.$item->Premium_Idx.'">Premium Detail</a> ',
            );
            $row++;
        }
        $data["draw"] = $resp["draw"];
        $data["recordsTotal"] = $rowsTotal;
        $data["recordsFiltered"] = $rowsTotal;
        
        return $data;
   }
   public function HomeCatSetStatus($params = array()){
        $resp = false;
        $row = DB::update("UPDATE mst_home_category_product SET Status = ? WHERE CategoryProductId = ?",[$params["status"],$params["code"]]);
        if($row > 0){
            $resp = true;
        }
        return $resp;
   }
   public function HomecatAdd($params = array(),$file){
       $resp = "00";
       do{
        // if($this->CheckInsurerDuplicate($params["insurer_code"])){
        //     $resp="03";
        //     break;
        // }
        $insertData = array(
            "Status"=>$params["status"],
            "CreateBy"=>$params["username"],
            "CreateDate"=>date("Y-m-d H:i:s"),
            "UpdateBy"=>$params["username"],
            "UpdateDate"=>date("Y-m-d H:i:s"),
        );
        $ins = DB::table("mst_home_category_product")->insertGetId($insertData);
        if($ins > 0){
            $langs = $this->core->GetLangList();
            foreach($langs as $key=>$value){
                $insDetail = array(
                    "CategoryProductId"=>$ins,
                    "LanguageCode"=>$value->LanguageCode,
                    "CategoryProductName"=>$params["cat_name_".$value->LanguageCode]
                );
                DB::table("mst_home_category_product_detail")->insert($insDetail);
            }
            $originalName =  time().$file->getClientOriginalName();
            $destinationPath = 'uploads/home';
            if($file->move($destinationPath,$originalName)){
                DB::update("UPDATE mst_home_category_product SET Image = ? WHERE CategoryProductId = ?",[$originalName,$ins]);
            }
            $resp = "01";
        }
       }while(false);
       
       return $resp;
   }
   public function HomecatEdit($params = array(),$file){
       $resp = "00";
       do{
        
        $updateData = array(
            "Status"=>$params["status"],
            "UpdateBy"=>$params["username"],
            "UpdateDate"=>date("Y-m-d H:i:s"),
        );
        
        $ins = DB::table('mst_home_category_product')
            ->where('CategoryProductId', $params["old_id"])
            ->update($updateData);
        if($ins > 0){
            //clear detail
            DB::delete("DELETE FROM mst_home_category_product_detail WHERE CategoryProductId = ?",[$params["old_id"]]);
            $langs = $this->core->GetLangList();
            foreach($langs as $key=>$value){
                $insDetail = array(
                    "CategoryProductId"=>$params["old_id"],
                    "LanguageCode"=>$value->LanguageCode,
                    "CategoryProductName"=>$params["cat_name_".$value->LanguageCode]
                );
                DB::table("mst_home_category_product_detail")->insert($insDetail);
            }
            if(!is_null($file)){
                $originalName =  time().$file->getClientOriginalName();
                $destinationPath = 'uploads/home';
                if($file->move($destinationPath,$originalName)){
                    DB::update("UPDATE mst_home_category_product SET Image = ? WHERE CategoryProductId = ?",[$originalName,$params["old_id"]]);
                }
            }
            
            $resp = "01";
        }
       }while(false);
       
       return $resp;
   }
   public function HomecatProductEdit($params = array()){
       $resp = "00";
       do{
        DB::delete("DELETE FROM mst_home_category_product_mapping WHERE CategoryProductId = ? AND Premium_Idx NOT IN(".$params["selected_id_list"].")",[$params["old_id"]]);
        DB::delete("DELETE FROM mst_home_category_product_mapping_desc WHERE CatProductListId IN (SELECT CatProductListId FROM mst_home_category_product_mapping WHERE CategoryProductId = ? AND Premium_Idx NOT IN(".$params["selected_id_list"]."))",[$params["old_id"]]);
        $arrSave = explode(',',$params["selected_id_list"]);
        for($i=0;$i<count($arrSave);$i++){
            $chkCount = collect(\DB::select("SELECT Count(*) as cc FROM mst_home_category_product_mapping WHERE CategoryProductId = ? AND Premium_Idx = ?",[$params["old_id"],$arrSave[$i]]))->first();
            if($chkCount->cc == 0){
                $insertData = array(
                    "CategoryProductId"=>$params["old_id"],
                    "Premium_Idx"=>$arrSave[$i],
                    "UpdateBy"=>$params["username"],
                    "UpdateDate"=>date("Y-m-d H:i:s"),
                );
                
                $ins = DB::table('mst_home_category_product_mapping')->insert($insertData);
                if($ins > 0){
                    
                    
                    
                }
            }
        }
        $resp = "01";
        
       }while(false);
       
       return $resp;
   }
   public function HomecatProductDetailEdit($params=array(),$file){
        $resp = "00";
       do{
        
        $updateData = array(
            "PromotionID"=>$params["promotion"],
            "UpdateBy"=>$params["username"],
            "UpdateDate"=>date("Y-m-d H:i:s"),
        );
        
        $ins = DB::table('mst_home_category_product_mapping')
            ->where('CatProductListId', $params["old_id"])
            ->update($updateData);
        if($ins > 0){
            //clear detail
            DB::delete("DELETE FROM mst_home_category_product_mapping_desc WHERE CatProductListId = ?",[$params["old_id"]]);
            $langs = $this->core->GetLangList();
            foreach($langs as $key=>$value){
                $insDetail = array(
                    "CatProductListId"=>$params["old_id"],
                    "LanguageCode"=>$value->LanguageCode,
                    "CatProductName"=>$params["product_name_".$value->LanguageCode],
                    "CatProductDesc1"=>$params["editor1_".$value->LanguageCode],
                    "CatProductDesc2"=>$params["editor2_".$value->LanguageCode],
                    "CatProductDesc3"=>$params["editor3_".$value->LanguageCode],
                );
                DB::table("mst_home_category_product_mapping_desc")->insert($insDetail);
            }
            if(!is_null($file)){
                $originalName =  time().$file->getClientOriginalName();
                $destinationPath = 'uploads/home';
                if($file->move($destinationPath,$originalName)){
                    DB::update("UPDATE mst_home_category_product_mapping SET Image = ? WHERE CatProductListId = ?",[$originalName,$params["old_id"]]);
                }
            }
            
            $resp = "01";
        }
       }while(false);
       
       return $resp;
   }
   public function GetSuggestBanner(){
       $resp = array();
       $list = DB::select("SELECT * FROM mst_banner WHERE BannerCode='SUGGEST'");
        foreach($list as $key=>$value){
          $resp[$value->LanguageCode] = array(
                "banner_1"=>$value->Banner1,
                "banner_2"=>$value->Banner2,
            );  
        }

        return $resp;
   }
   public function BannerSliderSetStatus($params = array()){
        $resp = false;
        $row = DB::update("UPDATE mst_banner_slider SET Status = ? WHERE BannerID = ?",[$params["status"],$params["code"]]);
        if($row > 0){
            $resp = true;
        }
        return $resp;
   }
   public function ContentSetStatus($params = array()){
        $resp = false;
        $row = DB::update("UPDATE mst_content SET Status = ? WHERE ContentID = ?",[$params["status"],$params["code"]]);
        if($row > 0){
            $resp = true;
        }
        return $resp;
   }
   public function GetSliderBanner($id){
    $data = array();
    $sql = "SELECT a.*,b.BannerName,b.BannerImage,b.BannerImageMobile,b.LanguageCode FROM mst_banner_slider a inner join mst_banner_slider_detail b on a.BannerID=b.BannerID  WHERE a.BannerID = ?";
    $list = DB::select($sql,[$id]);
    foreach($list as $key=>$value){
        if($key==0){
            $data["id"] = $value->BannerID;
            $data["banner_status"] = $value->Status;
            $data["banner_link"] = $value->BannerLink;
        }
        $data["detail"][$value->LanguageCode] = array(
            "banner_name"=>$value->BannerName,
            "banner_img"=>$value->BannerImage,
            "banner_img_mb"=>$value->BannerImageMobile,
        );
    }
    return $data;
   }
   public function GetContentDetail($id){
    $data = array();
    $sql = "SELECT a.*,b.Title,b.Description,b.Thumbnail,b.LanguageCode FROM mst_content a inner join mst_content_detail b on a.ContentID=b.ContentID  WHERE a.ContentID = ?";
    $list = DB::select($sql,[$id]);
    foreach($list as $key=>$value){
        if($key==0){
            $data["id"] = $value->ContentID;
            $data["content_status"] = $value->Status;
        }
        $data["detail"][$value->LanguageCode] = array(
            "title"=>$value->Title,
            "thumbnail"=>$value->Thumbnail,
            "desc"=>$value->Description,
        );
    }
    return $data;
   }
   public function ContentAdd($params = array(),$files){
        $resp = "00";
       do{
        $insData = array(
            "Status"=>$params["content_status"],
            "CreateBy"=>$params["username"],
            "CreateDate"=>date("Y-m-d H:i:s"),
            "UpdateBy"=>$params["username"],
            "UpdateDate"=>date("Y-m-d H:i:s"),
        );
        $id = DB::table("mst_content")->insertGetId($insData);
        if($id > 0){
            $langs = $this->core->GetLangList();
            foreach($langs as $key=>$value){
                $insDetail = array(
                    "ContentID"=>$id,
                    "LanguageCode"=>$value->LanguageCode,
                    "Title"=>$params["content_title_".$value->LanguageCode],
                    "Description"=>$params["editor_".$value->LanguageCode]
                );
                $detailID = DB::table("mst_content_detail")->insertGetId($insDetail);
                if($detailID > 0){
                    if(array_key_exists("thumbnail_".$value->LanguageCode,$files)){
                        $originalName =  time().$files["thumbnail_".$value->LanguageCode]->getClientOriginalName();
                        $destinationPath = 'uploads/content';
                        if($files["thumbnail_".$value->LanguageCode]->move($destinationPath,$originalName)){
                            DB::update("UPDATE mst_content_detail SET Thumbnail = ? WHERE ContentDetailID = ? AND LanguageCode = ?",[$originalName,$detailID,$value->LanguageCode]);
                        }
                    }
                }
            }//foreach lang
        }//header insert

        $resp = "01";
       }while(false);
       
       return $resp;
   }
   public function BannerSliderAdd($params = array(),$files){
        $resp = "00";
       do{
        $insData = array(
            "Status"=>$params["banner_status"],
            "BannerLink"=>$params["banner_link"],
            "CreateBy"=>$params["username"],
            "CreateDate"=>date("Y-m-d H:i:s"),
            "UpdateBy"=>$params["username"],
            "UpdateDate"=>date("Y-m-d H:i:s"),
        );
        $id = DB::table("mst_banner_slider")->insertGetId($insData);
        if($id > 0){
            $langs = $this->core->GetLangList();
            foreach($langs as $key=>$value){
                $insDetail = array(
                    "BannerID"=>$id,
                    "LanguageCode"=>$value->LanguageCode,
                    "BannerName"=>$params["banner_title_".$value->LanguageCode],
                );
                $detailID = DB::table("mst_banner_slider_detail")->insertGetId($insDetail);
                if($detailID > 0){
                    if(array_key_exists("banner_".$value->LanguageCode,$files)){
                        $originalName =  time().$files["banner_".$value->LanguageCode]->getClientOriginalName();
                        $destinationPath = 'uploads/banner';
                        if($files["banner_".$value->LanguageCode]->move($destinationPath,$originalName)){
                            DB::update("UPDATE mst_banner_slider_detail SET BannerImage = ? WHERE BannerDetailID = ? AND LanguageCode = ?",[$originalName,$detailID,$value->LanguageCode]);
                        }
                    }
                    if(array_key_exists("banner_mb_".$value->LanguageCode,$files)){
                        $originalName =  time().$files["banner_mb_".$value->LanguageCode]->getClientOriginalName();
                        $destinationPath = 'uploads/banner';
                        if($files["banner_mb_".$value->LanguageCode]->move($destinationPath,$originalName)){
                            DB::update("UPDATE mst_banner_slider_detail SET BannerImageMobile = ? WHERE BannerDetailID = ? AND LanguageCode = ?",[$originalName,$detailID,$value->LanguageCode]);
                        }
                    }
                }
            }//foreach lang
        }//header insert

        $resp = "01";
       }while(false);
       
       return $resp;
   }
   public function ContentEdit($params = array(),$files){
        $resp = "00";
       do{
        $insData = array(
            "Status"=>$params["content_status"],
            "UpdateBy"=>$params["username"],
            "UpdateDate"=>date("Y-m-d H:i:s"),
        );
        $row = DB::table("mst_content")
        ->where('ContentID',$params["content_id"])
        ->update($insData);
        if($row > 0){
            $langs = $this->core->GetLangList();
            foreach($langs as $key=>$value){
                $insDetail = array(
                    "Title"=>$params["content_title_".$value->LanguageCode],
                    "Description"=>$params["editor_".$value->LanguageCode]
                );
                $rowDetail = DB::table("mst_content_detail")
                ->where('ContentID',$params["content_id"])
                ->where('LanguageCode',$value->LanguageCode)
                ->update($insDetail);
                if(array_key_exists("thumbnail_".$value->LanguageCode,$files)){
                    $originalName =  time().$files["thumbnail_".$value->LanguageCode]->getClientOriginalName();
                    $destinationPath = 'uploads/content';
                    if($files["thumbnail_".$value->LanguageCode]->move($destinationPath,$originalName)){
                        DB::update("UPDATE mst_content_detail SET Thumbnail = ? WHERE ContentID = ? AND LanguageCode = ?",[$originalName,$params["content_id"],$value->LanguageCode]);
                    }
                }
            }//foreach lang
        }//header insert

        $resp = "01";
       }while(false);
       
       return $resp;
   }
   public function BannerSliderEdit($params = array(),$files){
        $resp = "00";
       do{
        $insData = array(
            "Status"=>$params["banner_status"],
            "BannerLink"=>$params["banner_link"],
            "UpdateBy"=>$params["username"],
            "UpdateDate"=>date("Y-m-d H:i:s"),
        );
        $row = DB::table("mst_banner_slider")
        ->where('BannerID',$params["banner_id"])
        ->update($insData);
        if($row > 0){
            $langs = $this->core->GetLangList();
            foreach($langs as $key=>$value){
                $insDetail = array(
                    // "BannerID"=>$params["banner_id"],
                    // "LanguageCode"=>$value->LanguageCode,
                    "BannerName"=>$params["banner_title_".$value->LanguageCode],
                );
                $rowDetail = DB::table("mst_banner_slider_detail")
                ->where('BannerID',$params["banner_id"])
                ->where('LanguageCode',$value->LanguageCode)
                ->update($insDetail);
                if(array_key_exists("banner_".$value->LanguageCode,$files)){
                    $originalName =  time().$files["banner_".$value->LanguageCode]->getClientOriginalName();
                    $destinationPath = 'uploads/banner';
                    if($files["banner_".$value->LanguageCode]->move($destinationPath,$originalName)){
                        DB::update("UPDATE mst_banner_slider_detail SET BannerImage = ? WHERE BannerID = ? AND LanguageCode = ?",[$originalName,$params["banner_id"],$value->LanguageCode]);
                    }
                }
                if(array_key_exists("banner_mb_".$value->LanguageCode,$files)){
                    $originalName =  time().$files["banner_mb_".$value->LanguageCode]->getClientOriginalName();
                    $destinationPath = 'uploads/banner';
                    if($files["banner_mb_".$value->LanguageCode]->move($destinationPath,$originalName)){
                        DB::update("UPDATE mst_banner_slider_detail SET BannerImageMobile = ? WHERE BannerID = ? AND LanguageCode = ?",[$originalName,$params["banner_id"],$value->LanguageCode]);
                    }
                }
            }//foreach lang
        }//header insert

        $resp = "01";
       }while(false);
       
       return $resp;
   }
   public function BannerEdit($params = array(),$files){
         $resp = "00";
       do{
        $langs = $this->core->GetLangList();
        foreach($langs as $key=>$value){
            if(array_key_exists("banner1_".$value->LanguageCode,$files)){
                $originalName =  time().$files["banner1_".$value->LanguageCode]->getClientOriginalName();
                $destinationPath = 'uploads/banner';
                if($files["banner1_".$value->LanguageCode]->move($destinationPath,$originalName)){
                    DB::update("UPDATE mst_banner SET Banner1 = ? WHERE BannerCode = 'SUGGEST' AND LanguageCode = ?",[$originalName,$value->LanguageCode]);
                }
            }
            if(array_key_exists("banner2_".$value->LanguageCode,$files)){
                $originalName =  time().$files["banner2_".$value->LanguageCode]->getClientOriginalName();
                $destinationPath = 'uploads/banner';
                if($files["banner2_".$value->LanguageCode]->move($destinationPath,$originalName)){
                    DB::update("UPDATE mst_banner SET Banner2 = ? WHERE BannerCode = 'SUGGEST' AND LanguageCode = ?",[$originalName,$value->LanguageCode]);
                }
            }

            $updateData = array(
                "UpdateBy"=>$params["username"],
                "UpdateDate"=>date("Y-m-d H:i:s"),
            );
            
            $ins = DB::table('mst_banner')
                ->where('BannerCode', "SUGGEST")
                ->where('LanguageCode', $value->LanguageCode)
                ->update($updateData);
            
        }
        
        
        $resp = "01";
       }while(false);
       
       return $resp;
   }
   public function GetHomecat($id){
        $data = array();
        $sql = "SELECT * FROM mst_home_category_product a inner join mst_home_category_product_detail b on a.CategoryProductId=b.CategoryProductId  WHERE a.CategoryProductId = ?";
        $list = DB::select($sql,[$id]);
        foreach($list as $key=>$value){
            if($key==0){
                $data["id"] = $value->CategoryProductId;
                $data["icon"] = $value->Image;
                $data["status"] = $value->Status;
            }
            $data["detail"][$value->LanguageCode] = array(
                "cat_name"=>$value->CategoryProductName
            );
        }
        return $data;
   }
   public function GetHomecatProduct($id){
        $data = array();
        $sql = "SELECT * FROM mst_home_category_product_mapping a  WHERE a.CatProductListId = ?";
        $head = collect(\DB::select($sql,[$id]))->first();
        $data["id"] = $id;
        $data["image"] = $head->Image;
        $data["premium_idx"] = $head->Premium_Idx;
        $data["promotion"] = $head->PromotionID;
        $data["seq"] = $head->Seq;


        $sql = "SELECT * FROM mst_home_category_product_mapping_desc a  WHERE a.CatProductListId = ?";
        $list = DB::select($sql,[$id]);
        if(count($list)>0){
            foreach($list as $key=>$value){
                $data["detail"][$value->LanguageCode] = array(
                    "product_name"=>$value->CatProductName,
                    "product_desc_1"=>$value->CatProductDesc1,
                    "product_desc_2"=>$value->CatProductDesc2,
                    "product_desc_3"=>$value->CatProductDesc3,
                );
            }
        }
        else{
            $langs = $this->core->GetLangList();
            foreach($langs as $key=>$value){
                $data["detail"][$value->LanguageCode] = array(
                    "product_name"=>"",
                    "product_desc_1"=>"",
                    "product_desc_2"=>"",
                    "product_desc_3"=>"",
                );
            }
        }

        return $data;
   }
   public function GetHomeCatProductList($id){
        $sql = "SELECT b.idx,b.InsurerCode,b.CC,b.ProductType,b.MakeValue,b.ModelValue,b.SumInsured,b.TotalPremium,b.ClaimTypeValue
                FROM mst_home_category_product_mapping a
                        inner join mst_fix_premium b on a.Premium_Idx=b.idx

                WHERE a.CategoryProductId = ?
        ";
        $list = DB::select($sql,[$id]);

        return $list;
   }
}
