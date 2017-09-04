<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use App\Models\Import;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;

class ImportController extends Controller
{
    private $o_ImportExcel;
    public function __construct() {
        $this->o_ImportExcel = new Import();
    }
    
    /**
     * Auth: Dienct
     * Des: Import file excel table user
     * Since: 15/1/2015
     */
    public function showexcel() {
        $a_Res = array();
        if (Input::hasFile('excel')) {
            
            $filename = Input::file('excel')->getClientOriginalName();
            $extension = Input::file('excel')->getClientOriginalExtension();
            
            if($extension == 'xlsx' || $extension == 'xls'){
                Input::file('excel')->move('uploads/', $filename);
                $sz_FileDir = 'uploads'."/".$filename;
                $a_Res = $this->o_ImportExcel->ImportExcel($sz_FileDir);
                $strRes = "";
                foreach ($a_Res as $key => $val){
                    $strRes .=" ".$val;
                }
            }else{
                $strRes = "Cần nhập đúng định dạng file (xls, xlsx)!!!!";
            }

            return view('import.import',['a_Res'=>$strRes]);

        }else{
            return view('import.import');
        }

    }
    
}
