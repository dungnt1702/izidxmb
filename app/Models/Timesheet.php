<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Maatwebsite\Excel\Facades\Excel;
use DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use App\Util;
use Illuminate\Http\Request as o_request;
use App\Models\Users as User_model;
use Illuminate\Support\Facades\Session;
class Timesheet extends Model
{
    private $o_ModelUser;
    public function __construct() {
        $this->o_ModelUser = new User_model();
    }
    
    /**
     * @Auth: Dienct
     * @Des: Import excel file
     * @Since: 14/1/2016
     */
    public function ImportExcelTimesheet($dirFile) {
        set_time_limit(600);

        $o_Result = Excel::selectSheetsByIndex(0)->load($dirFile, function($reader) use (&$results) {
            $reader->formatDates(true, 'Y-m-d');
            $results = $reader->toArray();
        });

        $sz_code = "";
        $a_DataTimeSheet = array();


        foreach ($results as $key => $a_val) {
            if (!isset($a_val['code']) || $a_val['code'] == '' || $a_val['date'] == ''  || $a_val['name'] == '')
                unset($results[$key]);
        }
        $results = array_values($results);

        $last_key = key(array_slice($results, -1, 1, TRUE));
        $sz_Datetime = 0;

        // current month and current year
        if(9 < (int)date("d") && (int)date("d") <= 31 ){
            $i_Month = (int)date("m");
            $i_Year = (int)date("Y");
        }else if(1 <= (int)date("d") && (int)date("d") <= 9){
            $i_Month = (int)date("m") - 1;
            $i_Year = (int)date("Y");
            if($i_Month == 0){
                $i_Month = 12;
                $i_Year = date("Y") - 1;
            }
        }
                
        foreach ($results as $key => $a_val) {
            //validate
            if (!isset($a_val['code']))
                $a_Respon['err_code'] = "Kiểm tra lại trường Code trong file excel <br/>";
            if (!isset($a_val['name']))
                $a_Respon['err_name'] = "Kiểm tra lại trường Name trong file excel <br/>";

            if ($sz_code == "") $sz_code = trim($a_val['code']);
            $a_Datetime = explode('-', $a_val['date']);
            $sz_Date = (string) $a_Datetime[2];

            if ($sz_code == trim($a_val['code'])) {
                $sz_CheckinNew  = $a_val['checkin'];
                $sz_CheckoutNew  = $a_val['checkout'];
                
                if($sz_Datetime == trim($a_val['date'])){
                    $a_val['checkin'] = $sz_CheckinOld;
                    
                    if(isset($sz_CheckoutNew) && $sz_CheckoutNew != ""){
                        $a_val['checkout'] = $sz_CheckoutNew;
                    }else{
//                        if(strtotime($sz_CheckinNew) > strtotime($sz_CheckoutOld)) 
                        $a_val['checkout'] = $sz_CheckinNew;
                    } 
                }
                
                $a_DataTimeSheet[$sz_Date] = $a_val['checkin'] . "|" . $a_val['checkout'];
                $a_DataTimeSheet['code'] = $a_val['code'];
                $a_DataTimeSheet['name'] = $a_val['name'];
                $a_DataTimeSheet['month'] = isset($i_Month) ? $i_Month : 0;
                $a_DataTimeSheet['year'] = isset($i_Year) ? $i_Year : 0;
                if ($key == $last_key)
                    $a_DataAll[] = $a_DataTimeSheet;
            }else {
                $a_DataAll[] = $a_DataTimeSheet;
                unset($a_DataTimeSheet);
                $a_DataTimeSheet[$sz_Date] = $a_val['checkin'] . "|" . $a_val['checkout'];
                $a_DataTimeSheet['code'] = $a_val['code'];
                $a_DataTimeSheet['name'] = $a_val['name'];
                $a_DataTimeSheet['month'] = $i_Month;
                $a_DataTimeSheet['year'] = $i_Year;
                $sz_code = $a_val['code'];
            }
            $sz_Datetime = $a_val['date'];
            $sz_CheckinOld  = $a_val['checkin'];
            $sz_CheckoutOld  = $a_val['checkout'];
        }


        // get all timeshet 
        $a_NewTimeSheet = array();
        $a_NeedUpdateTimeSheet = array();
        $a_Error = array();
        //Get all timesheet from db
        $a_DbTimeSheet = DB::table('timesheet')->select('code')->where('month', $i_Month)->where('year', $i_Year)->get();
        $a_DbUsersCode = array();
        if (Util::b_fCheckArray($a_DbTimeSheet)) {
            foreach ($a_DbTimeSheet as $o_DbTimeSheet) {
                $a_DbUsersCode[] = trim($o_DbTimeSheet->code);
            }
        }

        foreach ($a_DataAll as $key => $row) {
            // Check user is existed or not
            if ($row['code'] != "") {
                //Get department_id
                $o_UserData = $this->o_ModelUser->getUserByCode($row['code']);
                $row['department_id'] = isset($o_UserData) && count($o_UserData) > 0 ? $o_UserData->department_id : 0;

                if (in_array($row['code'], $a_DbUsersCode)) {
                    // Update for user
                    $a_NeedUpdateTimeSheet[] = $row;
                } else {
                    // Insert new user
                    $a_NewTimeSheet[] = $row;
                }
            }
        }
        //Check to insert
        if (!isset($a_Respon)) {
            if (Util::b_fCheckArray($a_NewTimeSheet)) {
                //Get total of new users
                $i_InsertSuccessfully = 0;
                $i_InsertFail = 0;
                foreach ($a_NewTimeSheet as $a_RowNewTimeSheet) {
                    if (DB::table('timesheet')->insert($a_RowNewTimeSheet)) {
                        $i_InsertSuccessfully++;
                    } else {
                        $i_InsertFail++;
                    }
                }
                $a_Respon['insert'] = "insert $i_InsertSuccessfully timesheet(s) successfully! And $i_InsertFail failed!\n";
            } else {
                $a_Respon['insert'] = "No any existed timesheet to insert found!\n";
            }
            //Check to update
            if (Util::b_fCheckArray($a_NeedUpdateTimeSheet)) {
                //Get total of new users
                $i_UpdateSuccessfully = 0;
                $i_UpdateFail = 0;
                foreach ($a_NeedUpdateTimeSheet as $a_RowNeedUpdateTimeSheet) {
                    $sz_WhereCode = $a_RowNeedUpdateTimeSheet['code'];
                    unset($a_RowNeedUpdateTimeSheet['code']);
                    if (DB::table('timesheet')->where(array('code'=>$sz_WhereCode,'month'=>$i_Month,'year'=>$i_Year))->update($a_RowNeedUpdateTimeSheet)) {
                        $i_UpdateSuccessfully++;
                    } else {
                        $i_UpdateFail++;
                    }
                }
                $a_Respon['update'] = "Updated $i_UpdateSuccessfully user(s) successfully! And $i_UpdateFail not update!\n";
            } else {
                $a_Respon['update'] = "No any existed timesheet to update found!\n";
            }
        }
        return $a_Respon;
    }
    
    /**
     * @auth: Dienct
     * @Des: Export file excel
     * @Since: 26/2/2016
     */
    public function DownloadExcel() {
        //phpinfo(); die;
        // get session url
        $sz_Sql = Session::get('sql_search');
        $a_Select = explode('from', $sz_Sql);
        $a_Select[0] = str_replace("`name`","`name` as `Tên`",$a_Select[0]);
        $a_Select[0] = str_replace("`department_name`","`department_name` as `Phòng`",$a_Select[0]);
        $a_Select[0] = str_replace("`code`","`code` as `MNV`",$a_Select[0]);
        $sz_Sql = $a_Select[0].'from'.$a_Select[1];
        if(strpos($sz_Sql, 'limit') !== false){
            $arr =  explode('limit',$sz_Sql);
            $sz_Sql = $arr[0];
        }

        $a_MergeTimeSheet = DB::select(DB::raw($sz_Sql));
        try{
            Excel::create('Bang_cong_thang', function($excel) use($a_MergeTimeSheet) {
                // Set the title
                $excel->setTitle('no title');
                $excel->setCreator('no no creator')->setCompany('no company');
                $excel->setDescription('report file');
                $excel->sheet('sheet1', function($sheet) use($a_MergeTimeSheet) {
                    foreach ($a_MergeTimeSheet as $key => $o_person) {
                        unset($o_person->id);
                        unset($o_person->month);
                        unset($o_person->year);
                        unset($o_person->department_id);
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
    
    public function TimesheetTable(){
        
        $a_search = array();
        $a_AllTimeSheet = array();
        $o_Db = DB::table('timesheet');
        $sz_search_by = Input::get('search_by','');
        
        $i_search_department = Input::get('search_department','');
        if($i_search_department != 0)
        {
            $a_AllLeaveRequest = $o_Db->where('department_id', $i_search_department);
            $a_search['search_department'] = $i_search_department;
        }

        $sz_search_name = Input::get('search_name','');
        
        if($sz_search_by != '')
        {
            $a_search['search_by'] = $sz_search_by;
            $sz_search_field = Input::get('search_field','');
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

        $search_year = Input::get('search_year','');
        $search_month = Input::get('search_month','');
        if($search_year != 0 && $search_month != 0)
        {
            $a_AllTimeSheet = $o_Db->where(array('month' => $search_month, 'year' => $search_year));
            $a_search['search_month'] = $search_month;
            $a_search['search_year'] = $search_year;
        }
        
        if(isset($a_search['search_year']))
        {
            $search_month = (int) $search_month;
            $a_RangeDate = Util::GetRangeDate($search_month,$search_year);
            $a_AllTimeSheet = $o_Db->orderBy('name', 'asc')->paginate(20);
        }
        ///Nếu ko chọn tìm kiếm theo tháng năm thì search theo tháng và năm hiện tại
        else
        {
            $a_RangeDate = Util::GetRangeDate(0,0);
            
            $i_CurrentMonth = date('m');
            $i_CurrentYear = date('Y');
            $a_search['search_month'] = (int)$i_CurrentMonth;
            $a_search['search_year'] = $i_CurrentYear;
            $a_AllTimeSheet = $o_Db->where(array('month' => $i_CurrentMonth,'year' => $i_CurrentYear))->orderBy('name', 'asc')->paginate(20);
        }
        
        $sz_FirstDateRangeDate = date('d-m-Y',strtotime(reset($a_RangeDate)));
        $sz_EndDateRangeDate = date('d-m-Y',strtotime(end($a_RangeDate)));
        $Data_view['sz_TitleTime'] = 'Từ '.$sz_FirstDateRangeDate.' đến '.$sz_EndDateRangeDate; // Title Page

        foreach ($a_RangeDate as $key => &$sz_Date)
        {
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
        if(Util::b_fCheckObject($a_AllTimeSheet))
        {
            foreach ($a_AllTimeSheet as $key => $o_UserLeaveRequest)
            {
                foreach ($o_UserLeaveRequest as $key => $sz_val)
                {
                    if(is_numeric($key) && $sz_val != '')
                    {
                        if (strpos($sz_val, '&&&') !== false)
                        {
                            $a_Each = explode('&&&', $sz_val);

                            foreach ($a_Each as $sz_Each)
                            {
                                $a_Each1[] = explode('|', $sz_Each);
                            }
                            $o_UserLeaveRequest->$key = $a_Each1;
                            unset($a_Each1);
                        }
                        else
                        {
                            $a_val = explode('|', $sz_val);
                            $o_UserLeaveRequest->$key = $a_val;
                        }
                    }
                }
            }
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

    /**
     * @author Vit
     * @since 25/02/1016
     * @des Show Timesheet Table by Month
     */
    
    public function ShowTimesheetMonth(){
        //Set session url
        DB::connection()->enableQueryLog();
        $a_search = array();
        $a_DataTimesheetMonth = array();
        $o_Db = DB::table('merge_time_sheet');

        $sz_search_by = Input::get('search_by','');
        
        $i_search_department = Input::get('search_department','');
        
        if($i_search_department != 0)
        {
            $a_AllLeaveRequest = $o_Db->where('department_id', $i_search_department);
            $a_search['search_department'] = $i_search_department;
        }

        $sz_search_name = Input::get('search_name','');
        
        if($sz_search_by != '')
        {
            $a_search['search_by'] = $sz_search_by;
            $sz_search_field = Input::get('search_field','');
            $a_search['search_field'] = $sz_search_field;
            switch ($sz_search_by)
            {
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
        if($search_year != 0 && $search_month != 0)
        {
            $a_AllTimesheet = $o_Db->where(array('month' => $search_month, 'year' => $search_year));
            $a_search['search_month'] = $search_month;
            $a_search['search_year'] = $search_year;
        }
        if(isset($a_search['search_year']))
        {
            $search_month = (int) $search_month;
            $a_RangeDate = Util::GetRangeDate($search_month,$search_year);
            $a_AllTimesheet = $o_Db->orderBy('department_id', 'asc')->paginate(20);
        }
        ///Nếu ko chọn tìm kiếm theo tháng năm thì search theo tháng và năm hiện tại
        else
        {
            $a_RangeDate = Util::GetRangeDate(0,0);
            $i_CurrentMonth = date('m');
            $i_CurrentYear = date('Y');
            $a_search['search_month'] = (int)$i_CurrentMonth;
            $a_search['search_year'] = $i_CurrentYear;
            $a_AllTimeSheet = $o_Db->where(array('month' => $i_CurrentMonth,'year' => $i_CurrentYear))->orderBy('department_id', 'asc')->paginate(20);
        }
        
        $sz_FirstDateRangeDate = date('d-m-Y',strtotime(reset($a_RangeDate)));
        $sz_EndDateRangeDate = date('d-m-Y',strtotime(end($a_RangeDate)));
        $a_DataTimesheetMonth['sz_TitleTime'] = 'Từ '.$sz_FirstDateRangeDate.' đến '.$sz_EndDateRangeDate; // Title Page

        foreach ($a_RangeDate as $key => &$sz_Date)
        {
            $i_Date = strtotime($sz_Date);
            $i_DateNumber = date('d',$i_Date);
            $i_DateNumber = $i_DateNumber;
            $a_RangeDate[$key] = $i_DateNumber;
            $sz_GetdayFromDate = date( "l", $i_Date);
            $a_RangeDay[$i_DateNumber] = config('cmconst.day.'.$sz_GetdayFromDate);
            $a_RangeDate_Cmt[$key] = $i_DateNumber."_cmt";
            
        }
        $a_DataTimesheetMonth['a_RangeDate'] = $a_RangeDate; // Mảng lưu giá trị các ngày trong tháng
        $a_DataTimesheetMonth['a_RangeDay'] = $a_RangeDay; /// Mảng lưu giá trị các thứ tương ứng với ngày
        $a_DataTimesheetMonth['a_RangeDate_Cmt'] = $a_RangeDate_Cmt; /// Mảng lưu cmt các ngày
        
        
        
        $a_Department = DB::table('departments')->select('id','name')->get();
        foreach($a_Department as $o_deparment){
            $a_Departments[$o_deparment->id] = $o_deparment->name;
        }
        $a_DataTimesheetMonth['a_Departments'] = $a_Departments;
        $a_FieldSelect = array('id','name','code','department_name','department_id','26','26_cmt','27','27_cmt','28','28_cmt','29','29_cmt','30','30_cmt','31','31_cmt','01',
                               '01_cmt','02','02_cmt','03','03_cmt','04','04_cmt','05','05_cmt','06','06_cmt','07','07_cmt','08','08_cmt','09','09_cmt','10',
                               '10_cmt','11','11_cmt','12','12_cmt','13','13_cmt','14','14_cmt','15','15_cmt','16','16_cmt','17','17_cmt','18','18_cmt','19',
                               '19_cmt','20','20_cmt','21','21_cmt','22','22_cmt','23','23_cmt','24','24_cmt','25','25_cmt','month','year'
                                );
        $a_AllTimesheet = $o_Db->select($a_FieldSelect)->orderBy('department_id', 'asc')->paginate(30);
        
        //get sql
        $query = DB::getQueryLog();
        $query = end($query);
        foreach ($query['bindings'] as $i => $binding) {
            $query['bindings'][$i] = "'$binding'";
        }
        
        $sz_query_change = str_replace(array('%', '?'), array('%%', '%s'), $query['query']);
        $sz_SqlFull = vsprintf($sz_query_change, $query['bindings']);
        
        // save session
        Session::set('sql_search', $sz_SqlFull);
//        if(Util::b_fCheckObject($a_AllTimesheet))
//        {
//            foreach ($a_AllTimesheet as $key => $o_UserLeaveRequest)
//            {
//                foreach ($o_UserLeaveRequest as $key => $sz_val)
//                {
//                    if(is_numeric($key) && $sz_val != '')
//                    {
//                        if (strpos($sz_val, '&&&') !== false)
//                        {
//                            $a_Each = explode('&&&', $sz_val);
//
//                            foreach ($a_Each as $sz_Each)
//                            {
//                                $a_Each1[] = explode('|', $sz_Each);
//                            }
//                            $o_UserLeaveRequest->$key = $a_Each1;
//                            unset($a_Each1);
//                        }
//                        else
//                        {
//                            $a_val = explode('|', $sz_val);
//                            $o_UserLeaveRequest->$key = $a_val;
//                        }
//                    }
//                }
//            }
//        }
        $a_DataTimesheetMonth['a_AllTimeSheet'] = $a_AllTimesheet;
        $a_Return = array ('a_Data' => $a_DataTimesheetMonth, 'a_Search' => $a_search);
        return $a_Return;
    }
    /**
     * @author HuyNN
     * @since 14/04/1016
     * @des Get All Users
     */
    
    public function a_GetAllCodeDbUsers(){
        $a_DbUsers = DB::table('users')->select('id','code','name','department_id')->where('status', 1)->get();
        if(Util::b_fCheckArray($a_DbUsers))
        {
           return $a_DbUsers;
        }
        else return array();
    }
    
    /**
     * @author HuyNN
     * @since 14/04/1016
     * @des Get All Departments
     */
    public function a_GetAllDepartment(){
        $a_DbDepartments = DB::table('departments')->select('id','name')->get();
        $a_AllDepartments = array();
        if(Util::b_fCheckArray($a_DbDepartments))
        {
            foreach ($a_DbDepartments as $o_DbDepartments) {
                $a_AllDepartments[$o_DbDepartments->id] = $o_DbDepartments->name;
            }
        }
        return $a_AllDepartments;
    }
    /*
     * @auth: Dienct
     * @since: 14/11/2016
     * @des: get error log by code
     * */
    public function getErrorByCode($code, $month, $year){

        $a_Select = array('26','27','28','29','30','31','01','02','03','04','05','06','07','08','09','10','11','12','13',
            '14','15','16','17','18','19','20','21','22','23','24','25');
        $a_TimeSheet = DB::table('merge_time_sheet')->select($a_Select)->where('code', $code)->where('month', $month)->where('year', $year)->first();
        $a_ErrorTimeSheet = array();
        if(isset($a_TimeSheet) && count($a_TimeSheet) > 0){
            foreach ($a_TimeSheet as $key => $val){
                if($val == 'v' || $val =='v/2'){
                    $a_ErrorTimeSheet[$key] = $val;
                }
            }
        }

        return $a_ErrorTimeSheet;

    }

}
