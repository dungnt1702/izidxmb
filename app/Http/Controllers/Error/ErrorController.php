<?php

namespace App\Http\Controllers\Error;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Late;
use App\Models\Users;
use DB;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Roles as o_RoleModel;

class ErrorController extends Controller
{
    public function __construct()
    {
        $o_Role = new o_RoleModel();
        $o_Role->b_fCheckRole();
        $this->o_user =  new Users();
    }

    public function mergeError(){

        if(25 < (int)date("d") && (int)date("d") <= 31 ){
            $current_month = (int)date("m");
            $current_year = (int)date("Y");
        }else if(1 <= (int)date("d") && (int)date("d") <= 25){
            $current_month = (int)date("m") - 1;
            $current_year = (int)date("Y");
            if($current_month == 0){
                $current_month = 12;
                $current_year = date("Y") - 1;
            }
        }
        // delete recore
        DB::table('merge_error')
            ->where('month', $current_month)->where('year', $current_year)
            ->delete();

        // merge from table merge timesheet
        $a_Select = array('name','code','department_name','department_id','26','27','28','29','30','31','01','02','03','04','05','06','07','08','09','10','11','12','13',
            '14','15','16','17','18','19','20','21','22','23','24','25');
        $a_TimeSheet = DB::table('merge_time_sheet')->select($a_Select)->where('month', $current_month)->where('year', $current_year)->get();
        if(isset($a_TimeSheet) && count($a_TimeSheet) >0){
            foreach ($a_TimeSheet as $key => $stafTimeSheet){

                $a_update = array();
                foreach ($stafTimeSheet as $keyDate => $valDate){
                    if($valDate == 'v' || $valDate == 'v/2') $a_update[$keyDate] = $valDate;
                }
                // check late by code
                $a_update['name'] = $stafTimeSheet->name;
                $a_update['code'] = $stafTimeSheet->code;
                $a_update['department_id'] = $stafTimeSheet->department_id;
                if($stafTimeSheet->department_name != ''){
                    $a_update['department_name'] = $stafTimeSheet->department_name;
                }

                $checkCode = DB::table('merge_error')->where('code', $stafTimeSheet->code)->where('month', $current_month)->where('year', $current_year)->count();
                if($checkCode > 0){
                    DB::table('merge_error')
                        ->where('code', $stafTimeSheet->code)->where('month', $current_month)->where('year', $current_year)
                        ->update($a_update);
                }else{
                    $a_update['month'] = $current_month;
                    $a_update['year'] = $current_year;
                    DB::table('merge_error')->insert($a_update);
                }
            }
        }

        // merge from table merge late
        $a_SelectLate = array('name','code','department_name','department_id','26','27','28','29','30','31','01','02','03','04','05','06','07','08','09','10','11','12','13',
            '14','15','16','17','18','19','20','21','22','23','24','25');
        $a_Late = DB::table('late')->select($a_SelectLate)->where('month', $current_month)->where('year', $current_year)->get();

        if(isset($a_Late) && count($a_Late) >0){
            foreach ($a_Late as $keyLate => $stafLate){

                $a_updateLate = array();
                foreach ($stafLate as $keyDateLate => $valDateLate){
                    if($valDateLate != '') $a_updateLate[$keyDateLate] = $valDateLate;
                }
                // check late by code
                $a_updateLate['name'] = $stafLate->name;
                $a_updateLate['code'] = $stafLate->code;
                $a_updateLate['department_id'] = $stafLate->department_id;
                if($stafLate->department_name != ''){
                    $a_updateLate['department_name'] = $stafLate->department_name;
                }
                $checkCode = DB::table('merge_error')->where('code', $stafLate->code)->where('month', $current_month)->where('year', $current_year)->count();

                if($checkCode > 0){
                    DB::table('merge_error')
                        ->where('code', $stafLate->code)->where('month', $current_month)->where('year', $current_year)
                        ->update($a_updateLate);
                }else{
                    $a_updateLate['month'] = $current_month;
                    $a_updateLate['year'] = $current_year;
                    DB::table('merge_error')->insert($a_updateLate);
                }

            }
        }


        echo "Merge thành công tháng {$current_month}/{$current_year}";
    }

    public function listError(){
        $lateModel = new Late();
        $Data_view = $lateModel->getDataError();
        return view('errors.listError', $Data_view);
    }

    public function exportError(){
        $sz_Sql = Session::get('sql_error');
        $a_Select = explode('from', $sz_Sql);
        $a_Select[0] = str_replace("`name`","`name` as `Tên`",$a_Select[0]);
        $a_Select[0] = str_replace("`department_name`","`department_name` as `Phòng`",$a_Select[0]);
        $a_Select[0] = str_replace("`code`","`code` as `MNV`",$a_Select[0]);
        $sz_Sql = $a_Select[0].'from'.$a_Select[1];
        if(strpos($sz_Sql, 'limit') !== false){
            $arr =  explode('limit',$sz_Sql);
            $sz_Sql = $arr[0];
        }
        $a_Error = DB::select(DB::raw($sz_Sql));

        try{
            Excel::create('Bang_Vi_Pham', function($excel) use($a_Error) {
                // Set the title
                $excel->setTitle('no title');
                $excel->setCreator('no no creator')->setCompany('no company');
                $excel->setDescription('report file');
                $excel->sheet('sheet1', function($sheet) use($a_Error) {
                    foreach ($a_Error as $key => $o_person) {

                        unset($o_person->id);
                        unset($o_person->user_id);
                        unset($o_person->email);
                        unset($o_person->department_id);
                        $lost = $time_5_9 = $time_10 = 0;

                        foreach ($o_person as $keyDate => $valDate){
                            if($valDate == 'v' || $valDate == 'v/2') $lost += 1;
                            if($valDate === '5-10' || $valDate === '10-5'){
                                $time_5_9 += 1;
                                $time_10 += 1;
                            }
                            if($valDate === '5'){
                                $time_5_9 += 1;
                            }
                            if($valDate === '10' && $keyDate != 'month'){
                                $time_10 += 1;
                            }
                            if($valDate === '5-5'){
                                $time_5_9 += 2;
                            }
                            if($valDate === '10-10'){
                                $time_10 += 2;
                            }
                        }
                        $o_person->Vắng = $lost;
                        $o_person->Đi_Muộn_Về_Sớm_Từ_5_Tới_9_Phút = ($time_5_9 - 2 )>0 ? ($time_5_9 - 2 ) : 0;
                        $o_person->Đi_Muộn_Về_Sớm_Lớn_Hơn_10_Phút = $time_10;
                        $o_person->Tiền_Phạt = ($o_person->Vắng + $o_person->Đi_Muộn_Về_Sớm_Từ_5_Tới_9_Phút + $o_person->Đi_Muộn_Về_Sớm_Lớn_Hơn_10_Phút*2)*50;
                        $o_person->Tổng_Lỗi = $o_person->Vắng + $o_person->Đi_Muộn_Về_Sớm_Từ_5_Tới_9_Phút + $o_person->Đi_Muộn_Về_Sớm_Lớn_Hơn_10_Phút;
                        $ary[] = (array) $o_person;

                    }
                    if(isset($ary)){
                        $sheet->fromArray($ary);
                    }
                    $sheet->cells('A1:BM1', function($cells) {
                        $cells->setFontWeight('bold');
                        $cells->setBackground('#AAAAFF');
                        $cells->setFont(array(
                            'bold' => true
                        ));
                    });
                });
            })->download('xlsx');
        }catch (\Exception $e){
            echo $e->getMessage();
        }

    }

}
