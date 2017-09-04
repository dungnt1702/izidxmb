<?php

namespace App\Models;

use Faker\Provider\DateTime;
use Illuminate\Database\Eloquent\Model;
use DB;
use Illuminate\Support\Facades\Input;
use App\Util;
use Auth;
use Illuminate\Support\Facades\Session;

class OverTime extends Model
{
    //
    /**

     * @Auth: DienCt
     * @Des: Get all overtime management
     * @Since : 21/11/2016
     */
    public function GetOTManagement($user_id = 0){
        $a_search = array();
        $a_Data = array();
        // field select
        $a_field = array('id','code','name','manager_id','email','status','from_time','to_time','user_comment',
            'manager_id','hrm_id','manager_act_time','hrm_act_time','created_at','type_ot','updated_at','total_time');
        $o_Db = DB::table('over_time')->select($a_field);
        if($user_id != 0)
        {
            $a_Data = $o_Db->where('manager_id', $user_id);
        }

        $i_search_status = Input::get('search_status',0);
        $a_search['search_status'] = $i_search_status;
        if($i_search_status == 0) $a_Data = $o_Db->where('status', 0);
        else $a_Data = $o_Db->whereIn('status', array(1,2));

        $sz_search_by = Input::get('search_by','');

        if($sz_search_by != '') {
            $a_search['search_by'] = $sz_search_by;
            $sz_search_field = trim(Input::get('search_field',''));
            $a_search['search_field'] = $sz_search_field;
            switch ($sz_search_by)
            {
                case 'code':
                    $o_Db->where('code', 'like', '%'.$sz_search_field.'%');
                    break;
                case 'email':
                    $o_Db->where('email', 'like', '%'.$sz_search_field.'%');
                    break;
                case 'name':
                    $o_Db->where('name', 'like', '%'.$sz_search_field.'%');
                    break;
                default:
                    break;
            }
        }
        $a_Data = $o_Db->orderBy('created_at', 'desc')->paginate(20);

        if(count($a_Data)>0)
        {

            foreach($a_Data as $key =>&$val)
            {
                $val->status_name = config('cmconst.leave_request_status')[$val->status];
                $val->leave_type_name = $val->type_ot == 1 ? 'làm thêm cộng phép nghỉ bù' : 'làm thêm tính tăng ca';

                $val->time = '<strong>Từ:</strong> '.Util::sz_DateTimeFormat($val->from_time).'<br> <strong>Đến:</strong> '.Util::sz_DateTimeFormat($val->to_time);

                $val->stt = $key + 1;
                $val->from_time = Util::sz_DateTimeFormat($val->from_time);
                $val->to_time = Util::sz_DateTimeFormat($val->to_time);

            }
        }

        $a_return = array('a_data' => $a_Data, 'a_search' => $a_search);
        return $a_return;
    }

    /**
     * @Auth: DienCt
     * @Des: Hrm management over time
     * @Since : 23/11/2016
     */
    public function GetAllOTHRM($user_id = 0){
        // field select
        $a_field = array('id','code','name','email','status','from_time','to_time','user_comment','position_name','department_name',
            'created_at','updated_at','total_time','type_ot');
        $a_search = array();
        $i_department_id = Input::get('department_id','');
        $sz_search_by = Input::get('search_by','');
        $i_search_status = Input::get('search_status',1);
        $a_data = array();

        // get data leave management
        if($user_id != 0){
            $o_Db = DB::table('over_time');
            $a_data = $o_Db->select($a_field);

            ///Nếu user duyệt là reporter//
            if(Auth::user()->hr_type == 2){
                $o_hrm = DB::table('users')->select('id')->where('hr_type',1)->first();
                $a_data = $o_Db->where('user_id',$o_hrm->id);
            }

            if($i_department_id != ''){
                $a_search['department_id'] = $i_department_id;
                $a_data = $o_Db->where('department_id', $i_department_id);
            }



            if($sz_search_by != '') {
                $a_search['search_by'] = $sz_search_by;
                $sz_search_field = trim(Input::get('search_field',''));
                $a_search['search_field'] = $sz_search_field;
                switch ($sz_search_by)
                {
                    case 'code':
                        $o_Db->where('code', 'like', '%'.$sz_search_field.'%');
                        break;
                    case 'email':
                        $o_Db->where('email', 'like', '%'.$sz_search_field.'%');
                        break;
                    case 'name':
                        $o_Db->where('name', 'like', '%'.$sz_search_field.'%');
                        break;
                    default:
                        break;
                }
            }

            if($i_search_status == 2){
                $a_search['search_status'] = $i_search_status;
                $a_data = $o_Db->where(function ($query) {
                    $query->where('status', 2);
                });
            }
            else{
                ///Nếu user duyệt là reporter//
                if(Auth::user()->hr_type == 2)
                {
                    $a_data = $o_Db->where('status', 1);
                }
                else
                {
                    $a_data = $o_Db->where(function ($query) {
                        $query->where('status', 1)
                            ->orWhere(function ($query1) {
                                $query1->where('status', 0)
                                    ->where('manager_id', 0);
                            });
                    });
                }
            }
            $a_data = $o_Db->orderBy('created_at', 'desc')->paginate(15);
        }
//$val->type_ot == 1 ? 'làm thêm cộng phép nghỉ bù' : 'làm thêm tính tăng ca';
        if(count($a_data)>0){

            foreach($a_data as $key =>&$val){
                switch ($val->status){
                    case 0:
                        $val->status = "Đang chờ";
                        break;
                    case 1:
                        $val->status = "Quản lý đã duyệt";
                        break;
                    case 2:
                        $val->status = "Phòng nhân sự đã duyệt";
                        break;
                    case 3:
                        $val->status = "Bác bỏ";
                        break;
                    case 4:
                        $val->status = "Xóa";
                        break;
                    default :
                        break;
                }
                $val->leave_type_name = $val->type_ot == 1 ? 'làm thêm cộng phép nghỉ bù' : 'làm thêm tính tăng ca';

                $val->time = '<strong>Từ:</strong> '.Util::sz_DateTimeFormat($val->from_time).'<br> <strong>Đến:</strong> '.Util::sz_DateTimeFormat($val->to_time);

                $val->from_time = Util::sz_DateTimeFormat($val->from_time);
                $val->to_time = Util::sz_DateTimeFormat($val->to_time);
                $val->stt = $key + 1;
            }
        }
        $a_return = array('a_data' => $a_data, 'a_search' => $a_search);

        return $a_return;
    }

    /**
     * @auth: Dienct
     * @des: get all overtime by user ID
     * since: 24/11/2016
     */
    public function GetAllOverTimeByUserId(){
        $i_userId = Auth::user()->id;
        if(isset($i_userId) && $i_userId > 0 ){
            $a_field = array('id','code','name','email','status','from_time','to_time','user_comment','position_name','department_name',
                'created_at','updated_at','total_time','type_ot','manager_comment','hrm_comment');
            $a_data = DB::table('over_time')->select($a_field)->where('user_id', $i_userId)->where('status', '!=', 4)->orderBy('created_at', 'desc')->paginate(15);

            if(isset($a_data) && count($a_data) > 0){
                foreach($a_data as $key =>&$val){
                    switch ($val->status){
                        case 0:
                            $val->status = "Đang chờ";
                            break;
                        case 1:
                            $val->status = "Quản lý đã duyệt";
                            break;
                        case 2:
                            $val->status = "Phòng nhân sự đã duyệt";
                            break;
                        case 3:
                            $val->status = "Bác bỏ";
                            break;
                        case 4:
                            $val->status = "Xóa";
                            break;
                        default :
                            break;
                    }
                    $val->leave_type_name = $val->type_ot == 1 ? 'làm thêm cộng phép nghỉ bù' : 'làm thêm tính tăng ca';
                    $val->time = '<strong>Từ:</strong> '.Util::sz_DateTimeFormat($val->from_time).'<br> <strong>Đến:</strong> '.Util::sz_DateTimeFormat($val->to_time);
                    $val->stt = $key + 1;

                }
            }

            return $a_data;
        }else{
            array();
        }
    }
    /**
     * @auth: Dienct
     * @des: get all overtime group by user ID
     * since: 24/11/2016
     */
    public function getAllUsersOverTime($month = 0, $year = 2015){

        $dateTimeFrom = $year.'-'.($month-1).'-26 00:00:00';
        $dateTimeTo = $year.'-'.($month).'-25 23:59:59';

        $a_field = array('id','user_id','code','name','email','status','from_time','to_time','user_comment','position_name','department_name','department_id',
            'created_at','updated_at','total_time','type_ot','manager_comment','hrm_comment');
        $a_data = DB::table('over_time')->select($a_field)
            ->where('from_time','>=', $dateTimeFrom)
            ->where('from_time','<=', $dateTimeTo)
            ->where('status', 2)
            ->orderBy('user_id', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();
        return $a_data;
    }
    /**
     * @auth: Dienct
     * @des: get all overtime by user ID and month and year
     * since: 24/11/2016
     */
    public function getAllOverTimeByUserAndTime($user=0, $month=0, $year=2015){

        $dateTimeFrom = $year.'-'.($month-1).'-26 00:00:00';
        $dateTimeTo = $year.'-'.($month).'-25 23:59:59';

        $a_field = array('user_id','email');
        $a_data = DB::table('over_time')->select($a_field)
            ->where('user_id', $user)
            ->where('from_time','>=', $dateTimeFrom)
            ->where('from_time','<=', $dateTimeTo)
            ->where('status', 2)
            ->orderBy('created_at', 'desc')->get();
        return $a_data;
    }

    /*
     * @auth: Dienct
     * @since: 30/11/2016
     * @des: show list over time
     * **/
    public function getDataOverTime(){

        DB::connection()->enableQueryLog();
        $a_search = array();
        $o_Db = DB::table('over_time_report');
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
        Session::set('sqlListOverTime', $sz_SqlFull);

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
