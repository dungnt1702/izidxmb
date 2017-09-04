<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use Illuminate\Support\Facades\Input;
use Auth;
class Performance extends Model
{
    /**
     * auth: HuyNN
     * Des: get all users
     * Since: 20/1/2016
     */
    public function GetAllCheckpoints($role)
    {
        $a_search = array();
        $a_data = array();
        $o_Db = DB::table('checkpoint');
        $i_search_department = Input::get('search_department','');
        $i_search_year = Input::get('search_year',0);
        $i_search_month = Input::get('search_month',0);

        if($i_search_department != '') {
            $a_search['search_department'] = $i_search_department;
            $a_data = $o_Db->where('department_id', $i_search_department);
        }
        if($i_search_year != 0) {
            $a_search['search_year'] = $i_search_year;
            $a_data = $o_Db->where('year', $i_search_year);
        }
        if($i_search_month != 0) {
            $a_search['search_month'] = $i_search_month;
            $a_data = $o_Db->where('month', $i_search_month);
        }
        if($role == 'hrm'){
            //$a_data = $o_Db->where(array('status' => 2))->orWhere('censor_id' , 397);
            $i_search_status = Input::get('search_status','');
            if($i_search_status == 1 || $i_search_status == ''){
                $a_data = $o_Db->where(function($query){
                    $query->where('status', 2)
                          ->orWhere(array('censor_id' => 397 , 'status' => 1));
                }); 
                $a_search['search_status'] = 1;
            }else if ($i_search_status == 3) {
                $a_search['search_status'] = 3;
                $a_data = $o_Db->where('status', 3);
            }
        }else if($role == 'censor'){
            $a_data = $o_Db->where('censor_id', Auth::user()->id);
        }else if($role == 'report'){
            $a_data = $o_Db->where('status', 3);
        }
        $a_data = $o_Db->orderBy('department_id', 'desc')->paginate(20);
        $a_return = array('a_data' => $a_data, 'a_search' => $a_search);
        return $a_return;
    }
}
