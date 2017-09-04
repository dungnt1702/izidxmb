<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use Illuminate\Support\Facades\Input;
use App\Util;
use Illuminate\Support\Facades\Session;

class Late extends Model
{
    //
    public function getLateByCode($code, $month, $year){

        $a_Select = array('26','27','28','29','30','31','01','02','03','04','05','06','07','08','09','10','11','12','13',
            '14','15','16','17','18','19','20','21','22','23','24','25');
        $a_Late = DB::table('late')->select($a_Select)->where('code', $code)->where('month', $month)->where('year', $year)->first();
        $a_ErrorLate = array();
        $a_ErrorLate['countError'] = 0;
        $countError = 0;
        $time_sheet = DB::table('timesheet')->select($a_Select)->where('code', $code)->where('month', $month)->where('year', $year)->first();
        if(isset($a_Late) && count($a_Late) > 0){
            foreach ($a_Late as $key => $val){
                if($val != ''){
                    if(strpos($val, '-') !== false){
                        $countError = $countError + 2;
                    }else{
                        $countError = $countError + 1;
                    }
                    $a_ErrorLate[$key] = isset($time_sheet) && count($time_sheet) > 0 ? $time_sheet->{$key} : '';
                    $a_ErrorLate['countError'] = $countError;
                }
            }
        }
        return $a_ErrorLate;
    }


    public function getDataError(){
        DB::connection()->enableQueryLog();
        $a_search = array();
        $o_Db = DB::table('merge_error');
        $sz_search_by = Input::get('search_by','');

        $i_search_department = Input::get('search_department','');
        if($i_search_department != 0){
            $o_Db->where('department_id', $i_search_department);
            $a_search['search_department'] = $i_search_department;
        }

        if($sz_search_by != ''){
            $a_search['search_by'] = $sz_search_by;
            $sz_search_field = Input::get('search_field','');
            $a_search['search_field'] = $sz_search_field;
            switch ($sz_search_by){
                case 'code':
                    $o_Db->where('code', 'like', '%'.$sz_search_field.'%');
                    break;
                case 'name':
                    $o_Db->where('name', 'like', '%'.$sz_search_field.'%');
                    break;
                default:
                    break;
            }
        }

        $search_year = Input::get('search_year','');
        $search_month = Input::get('search_month','');
        if($search_year != 0 && $search_month != 0){
            $a_search['search_month'] = $search_month;
            $a_search['search_year'] = $search_year;
        }

        if(isset($a_search['search_year'])){
            $o_Db->where(array('month' => $search_month, 'year' => $search_year));
            $search_month = (int) $search_month;
            $a_RangeDate = Util::GetRangeDate($search_month,$search_year);
            $a_AllTimeSheet = $o_Db->orderBy('department_id', 'asc')->paginate(20);
        }else{
            ///Nếu ko chọn tìm kiếm theo tháng năm thì search theo tháng và năm hiện tại
            $a_RangeDate = Util::GetRangeDate(0,0);

            $i_CurrentMonth = date('m');
            $i_CurrentYear = date('Y');
            $a_search['search_month'] = (int)$i_CurrentMonth;
            $a_search['search_year'] = $i_CurrentYear;
            $a_AllTimeSheet = $o_Db->where(array('month' => $i_CurrentMonth,'year' => $i_CurrentYear))->orderBy('department_id', 'asc')->paginate(20);
        }
        // sql
        $query = DB::getQueryLog();
        $query = end($query);
        foreach ($query['bindings'] as $i => $binding) {
            $query['bindings'][$i] = "'$binding'";
        }

        $sz_query_change = str_replace(array('%', '?'), array('%%', '%s'), $query['query']);
        $sz_SqlFull = vsprintf($sz_query_change, $query['bindings']);

        // save session
        Session::set('sql_error', $sz_SqlFull);

        $sz_FirstDateRangeDate = date('d-m-Y',strtotime(reset($a_RangeDate)));
        $sz_EndDateRangeDate = date('d-m-Y',strtotime(end($a_RangeDate)));
        $Data_view['sz_TitleTime'] = 'Từ '.$sz_FirstDateRangeDate.' đến '.$sz_EndDateRangeDate; // Title Page

        foreach ($a_RangeDate as $key => &$sz_Date){
            $i_Date = strtotime($sz_Date);
            $i_DateNumber = date('d',$i_Date);
            $i_DateNumber = $i_DateNumber;
            $a_RangeDate[$key] = $i_DateNumber;
            $sz_GetdayFromDate = date( "l", $i_Date);
            $a_RangeDay[$i_DateNumber] = config('cmconst.day.'.$sz_GetdayFromDate);
        }
        $Data_view['a_RangeDate'] = $a_RangeDate; // Mảng lưu giá trị các ngày trong tháng
        $Data_view['a_RangeDay'] = $a_RangeDay; /// Mảng lưu giá trị các thứ tương ứng với ngày

        $Data_view['a_search'] = $a_search;
        if(Util::b_fCheckObject($a_AllTimeSheet)){
            /*foreach ($a_AllTimeSheet as $key => $o_UserLeaveRequest){
                foreach ($o_UserLeaveRequest as $key => $sz_val){
                    if(is_numeric($key) && $sz_val != ''){
                        if (strpos($sz_val, '&&&') !== false){
                            $a_Each = explode('&&&', $sz_val);

                            foreach ($a_Each as $sz_Each){
                                $a_Each1[] = explode('|', $sz_Each);
                            }
                            $o_UserLeaveRequest->$key = $a_Each1;
                            unset($a_Each1);
                        }else{
                            $a_val = explode('|', $sz_val);
                            $o_UserLeaveRequest->$key = $a_val;
                        }
                    }
                }
            }*/
            $Data_view['a_AllTimeSheet'] = $a_AllTimeSheet;
        }
        $a_Department = DB::table('departments')->select('id','name')->get();
        foreach($a_Department as $o_deparment){
            $a_Departments[$o_deparment->id] = $o_deparment->name;
        }
        $Data_view['a_Departments'] = $a_Departments;

        $a_Return = array ('a_Data' => $Data_view, 'a_Search' => $a_search);
        return $a_Return;
    }
}
