<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;

class CoreModel 
{
   public function GetLangList(){
       $langs = DB::select("SELECT * FROM cfg_language ORDER BY LanguageSort");

       return $langs;
   }
   public function ConvertToSystemDate($date){ //dd/mm/yyyy
        $arr = explode('/',$date);

        return $arr[2]."-".$arr[1]."-".$arr[0];
   }
}
