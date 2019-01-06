<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;

class UserModel 
{
    //
    public function login($username,$password){
        $response = array();
        $response["status"] = ""; //status response
        $response["user"] = array(); //user object
        $encPassword = md5($password);
        $user = DB::select("select * from mst_user where `Username` = ? AND `Password` = ? AND `Status` = 'A'", [$username,$encPassword]);
        if(count($user) > 0){
            $response["status"] = "01";
            $response["user"] = $user[0];
            $response["user"]->menu = $this->getMenu($user[0]->UserRoleCode);
            $response["user"]->permission = $this->getPermission($user[0]->UserRoleCode);
            //$response["user"]->Menu = $this->getMenuByUser($user[0]->SysUser_ID);
        }
        else {
            $response["status"] = "02";
        }
        return $response;
    }
    public function getPermission($role){
        $sql = "SELECT a.ElementID as PageElement,a.PageID,a.PageName,a.PageUrl
                ,b.PageActionCode
                FROM 
                    cfg_page a
                        inner join mst_user_role_mapping b on a.PageID=b.PageID 
                        inner join cfg_page_group c on a.PageGroupID=c.PageGroupID
                WHERE c.IsMain = 1 AND b.UserRoleCode = ?

                order by c.Seq,a.Seq";
        $menu = DB::select($sql,[$role]);
        $arrMenu = array();
        $lastPage = "";
        foreach($menu as $key=>$value){
            $arrMenu["page_".$value->{"PageID"}]["actions"][] = $value->PageActionCode;
        }

        return $arrMenu;
    }
    public function getMenu($role){
        $sql = "SELECT c.PageGroupID,c.PageGroupName,c.HasSub,c.ElementID as GroupElement,c.PageGroupIcon,a.ElementID as PageElement,a.PageID,a.PageName,a.PageUrl

                FROM 
                    cfg_page a
                        inner join mst_user_role_mapping b on a.PageID=b.PageID AND b.PageActionCode='MENU'
                        inner join cfg_page_group c on a.PageGroupID=c.PageGroupID

                WHERE c.IsMain = 1 AND b.UserRoleCode = ?

                order by c.Seq,a.Seq";
        $menu = DB::select($sql,[$role]);
        $arrMenu = array();
        $lastGroup = "";
        foreach($menu as $key=>$value){
            if($lastGroup!=$value->PageGroupID)
            {
                $arrMenu["group_".$value->PageGroupID]=array(
                    "PageGroupID"=>$value->{"PageGroupID"},
                    "ElementID"=>$value->GroupElement,
                    "PageGroupName"=>$value->{"PageGroupName"},
                    "PageGroupIcon"=>$value->{"PageGroupIcon"},
                    "HasSub"=>$value->{"HasSub"},
                );
                $lastGroup = $value->{"PageGroupID"};
            }
            $arrMenu["group_".$value->{"PageGroupID"}]["pages"][] = array(
                "PageID"=>$value->{"PageID"},
                "ElementID"=>$value->PageElement,
                "PageName"=>$value->{"PageName"},
                "PageUrl"=>$value->{"PageUrl"},
            );
        }

        return $arrMenu;
    }
}
