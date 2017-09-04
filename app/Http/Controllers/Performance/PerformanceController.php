<?php

namespace App\Http\Controllers\Performance;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\URL;
use App\Models\Performance as o_PerformanceModel;
use App\Models\Roles as o_RoleModel;
use DB;
use Illuminate\Support\Facades\Session;
use App\Models\Users;
use App\Models\Timesheet;
use App\Models\Late;

class PerformanceController extends Controller
{
    //
    public function __construct()
    {
        $o_Role = new o_RoleModel();
        $o_Role->b_fCheckRole();
        $this->o_performance = new o_PerformanceModel();
        $this->o_performance = new o_PerformanceModel();
        $this->o_user = new Users();
    }

    public function checkpoint(){

        // check sensor ID
        $o_TimeSheetModel = new Timesheet();
        $o_Late = new Late();


        $i_checkpointId = Input::get('id');
        $user_id = Auth::user()->id;
        $department_id = Auth::user()->department_id;

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

        ////Nếu ở Form sửa phiếu đánh giá////
        if(isset($i_checkpointId)){
            if($i_checkpointId != '')
            {
                $o_checkpoint = DB::table('checkpoint')->where('id', $i_checkpointId)->first();
                $censor_id = $o_checkpoint->censor_id;

                $infoUser = $this->o_user->sz_fInfoUserById($o_checkpoint->user_id);
                $Data_view['job_id'] = $infoUser->job_id;
                $user_check_point_code = $infoUser->code;
                ////Nếu có tồn tại phiếu này trong hệ thống///
                if($o_checkpoint){
                    ////Nếu phiếu này của người đang vào -> stt = 1///
                    if($o_checkpoint->user_id == Auth::user()->id){
                        ///Nếu phiếu này chưa dc giám đốc hoặc HRM duyệt -> stt = 1;
                        if($o_checkpoint->status == 1) {
                            $i_status = 1;
                        }
                        else $Data_view['i_DisableUpdate'] = 1;
                    }
                    ////Nếu phiếu này ko phải của người đang vào///
                    else
                    {
                        $user_id = $o_checkpoint->user_id;
                        $department_id = $o_checkpoint->department_id;
                        ///Nếu người vào là HRM -> stt = 3///
                        if(Auth::user()->hr_type == 1) $i_status = 3;
                        ////Nếu ng vào ko phải HRM///
                        else 
                        {
                            ///Nếu người vào là giám đốc phòng, có quyền duyệt phiếu///
                            if ($o_checkpoint->censor_id == Auth::user()->id) {
                                ///Nếu ng vào là Tổng Giám đốc thì stt = 3, ngược lại stt = 2///
                                $i_status = Auth::user()->position_id == 1 ? 3 : 2;
                            }
                            else return redirect('/')->with('status', 'Bạn không có quyền truy cập phiếu đánh giá này');
                        }
                    }
                }else return redirect('/');
            }
            else return redirect('/');
        }else////Nếu ở Form thêm đánh giá////
        {
            $userData = $this->o_user->sz_fInfoUserById(Auth::user()->id);
            if($userData->censor_id == Null){
                return redirect('/change-sensor')->with('status', 'Bạn cần phải chọn người duyệt phiếu đánh giá tháng');
            }

            $Data_view['job_id'] = Auth::user()->job_id;
            $user_check_point_code = Auth::user()->code;
            // neu dang ky khac ngay 26,27,28 thi redirect về home
            $aryDateRegister = array('26','27','28','29','30','31','01','02','03','04','05','06','07','08');
            if (!in_array(date("d"), $aryDateRegister)){
                return redirect('/')->with('status', 'Chỉ có thể làm đánh giá vào ngày 26 tới ngày 1 của tháng, mọi thắc mắc liên lạc với phòng hành chính nhân sự');
            }

            ////check duplicate record///
            $checkDuplicate = DB::table('checkpoint')->where('user_id', $user_id)->where('month', $current_month)->where('year', $current_year)->count();
            if($checkDuplicate > 0){
                return redirect('/')->with('status', 'Bạn đã làm phiếu đánh giá của tháng này. Không thể tạo phiếu đánh giá mới');
            }else {
                $i_status = 1;
                $o_hr = DB::table('users')->select('id','department_id')->where('hr_type', 1)->first();
                ///Nếu là nhân viên trong phòng Nhân sự và không phải là HRM thì stt bằng 2///
                if(Auth::user()->department_id == $o_hr->department_id && Auth::user()->id != $o_hr->id) $i_status = 2;
                $o_Sensor = DB::table('users')->where('id', Auth::user()->id)->first();
                $censor_id = $o_Sensor != NULL ? $o_Sensor->censor_id : NULL;
            }
        }

        $checksubmit = Input::get('submit');
        if(isset($checksubmit) && $checksubmit !="")
        {
            $totalError = Input::get('totalError');
            $pointError = ($totalError > 2) ? ($totalError-2)*0.1 : 0;
            $a_DataUpdate = array();  
            $a_DataUpdate['user_id'] = $user_id;
            $a_DataUpdate['department_id'] = $department_id;
            $a_DataUpdate['type_checkpoint'] = Auth::user()->job_id;
            
            $a_DataUpdate['month'] = $current_month;
            $a_DataUpdate['year'] = $current_year;
            $a_DataUpdate['censor_id'] = $censor_id;
            $a_DataUpdate['status'] = $i_status;
            $a_DataUpdate['exploit_customer'] = Input::get('exploit_customer');
            $a_DataUpdate['revenue'] = Input::get('revenue');
            $a_DataUpdate['report_week'] = Input::get('report_week');
            $a_DataUpdate['morale'] = Input::get('morale');
            $a_DataUpdate['connect'] = Input::get('connect');
            $a_DataUpdate['cultural'] = Input::get('cultural');
            $a_DataUpdate['discipline'] = Input::get('discipline','');
            $a_DataUpdate['work_quality'] = Input::get('work_quality');
            $a_DataUpdate['progress'] = Input::get('progress');
            $a_DataUpdate['total_point'] = Input::get('total_point');
            $a_DataUpdate['level_point'] = Input::get('level_point');
            if(Input::get('comment')) $a_DataUpdate['comment'] = Input::get('comment');
            if(Input::get('censor_comment')) $a_DataUpdate['censor_comment'] = Input::get('censor_comment');
            if(Input::get('hrm_comment')) $a_DataUpdate['hrm_comment'] = Input::get('hrm_comment');
            $a_DataUpdate['created_at'] = date('Y-m-d H:i:s', time());
            if($i_checkpointId != '') DB::table('checkpoint')->where('id',$i_checkpointId)->update($a_DataUpdate);
            else DB::table('checkpoint')->insert($a_DataUpdate);
            return redirect('hrm-manager-checkpoint')->with('status', 'Cập nhật thành công!');
        }

        $Data_view['i_id'] = '';
        // get total error by user ID
        $errorTimeSheet = $errorLate = array();
        /*$errorTimeSheet = $o_TimeSheetModel->getErrorByCode($user_check_point_code, $current_month, $current_year);
        $errorLate = $o_Late->getLateByCode($user_check_point_code, $current_month, $current_year);*/
        $Data_view['errorTimeSheet'] = $errorTimeSheet;
        $Data_view['month'] = $current_month;
        $Data_view['year'] = $current_year;
        $Data_view['errorLate'] = $errorLate;

        if(isset($o_checkpoint)) $Data_view['o_checkpoint'] = $o_checkpoint;
        return view('checkpoint.add_edit_checkpoint', $Data_view);
    }

    public function hrmManagerCheckpoint(){
        ////Nếu có thực hiện Check các phiếu để duyệt nhiều////
        if(Input::get('check'))
        {
            $a_Check = Input::get('check');
            ///Update DB///
            foreach ($a_Check as $i_IdCheckPoint) 
            {
                DB::table('checkpoint')->where('id',$i_IdCheckPoint)->update(['status'=> 3]);
            }
            return redirect(URL::current())->with('status', 'Cập nhật thành công!');  
        }
        
        $a_DbDepartment = DB::table('departments')->select('id','name')->where('status',1)->get();
        foreach($a_DbDepartment as $o_DbDeparment){
            $a_Departments[$o_DbDeparment->id] = $o_DbDeparment->name;
        }
        $Data_view['a_Departments'] = $a_Departments;

        $a_DbPosition = DB::table('positions')->select('id','name')->where('status',1)->get();
        foreach($a_DbPosition as $o_DbPosition){
            $a_Positions[$o_DbPosition->id] = $o_DbPosition->name;
        }
        $Data_view['a_Positions'] = $a_Positions;

        $a_DbUser = DB::table('users')->select('id','code','name','email','department_id','position_id')->get();
        foreach($a_DbUser as $o_DbUser){
            $a_Users[$o_DbUser->id] = array(
                'code' => $o_DbUser->code,
                'name' => $o_DbUser->name,
                'email' => $o_DbUser->email,
                'department' => isset($a_Departments[$o_DbUser->department_id]) ? $a_Departments[$o_DbUser->department_id] : '',
                'position' => isset($a_Positions[$o_DbUser->position_id]) ? $a_Positions[$o_DbUser->position_id] : '',
            );
        }
        $Data_view['a_Users'] = $a_Users;
        $a_result = $this->o_performance->GetAllCheckpoints('hrm');
        $Data_view['a_checkpoints'] = $a_result['a_data'];
        $Data_view['a_search'] = $a_result['a_search'];
        return view('checkpoint.hrm_manager_checkpoint', $Data_view);
    }

    public function censorManagerCheckpoint(){
        ////Nếu có thực hiện Check các phiếu để duyệt nhiều////
        if(Input::get('check'))
        {          
            $a_Check = Input::get('check');
            $i_stt = Auth::user()->position_id == 1 ? 3 : 2;
            ///Update DB///
            foreach ($a_Check as $i_IdCheckPoint) 
            {
                DB::table('checkpoint')->where('id',$i_IdCheckPoint)->update(['status'=> $i_stt]);
            }
            return redirect(URL::current())->with('status', 'Cập nhật thành công!');  
        }
        $a_DbPosition = DB::table('positions')->select('id','name')->where('status',1)->get();
        foreach($a_DbPosition as $o_DbPosition){
            $a_Positions[$o_DbPosition->id] = $o_DbPosition->name;
        }
        $Data_view['a_Positions'] = $a_Positions;
        if(Auth::user()->id != 397) {
            $a_DbUser = DB::table('users')->select('id','code','name','email','position_id')->where('censor_id',Auth::user()->id)->get();
        }else{
            $a_User = DB::table('checkpoint')->select('user_id')->where('censor_id', 397)->get();
            if(count($a_User) > 0){
                $a_UserId = array();
                foreach ($a_User as $o_Val) {
                    $a_UserId[] = $o_Val->user_id;
                }
            }
            $a_DbUser = DB::table('users')->select('id','code','name','email','position_id')->whereIn('id', $a_UserId)->get();  
        }

        foreach($a_DbUser as $o_DbUser){
            $a_Users[$o_DbUser->id] = array(
                'code' => $o_DbUser->code,
                'name' => $o_DbUser->name,
                'email' => $o_DbUser->email,
                'position' => isset($a_Positions[$o_DbUser->position_id]) ? $a_Positions[$o_DbUser->position_id] : '',
            );
        }


        $Data_view['a_Users'] = $a_Users;

        $a_result = $this->o_performance->GetAllCheckpoints('censor');
        $Data_view['a_checkpoints'] = $a_result['a_data'];
        $Data_view['a_search'] = $a_result['a_search'];
        return view('checkpoint.censor_manager_checkpoint', $Data_view);
    }
    
    public function listCheckPoint(){
        $Data_CheckPoint = DB::table('checkpoint')->select('id','user_id','month','year','total_point','level_point','status')
            ->where('user_id',Auth::user()->id)->get();
        return view('checkpoint.list_check_point', ['dataCheckPoint' => $Data_CheckPoint]);
    }
    
    public function reportCheckpoint(){
        $a_DbDepartment = DB::table('departments')->select('id','name')->where('status',1)->get();
        foreach($a_DbDepartment as $o_DbDeparment){
            $a_Departments[$o_DbDeparment->id] = $o_DbDeparment->name;
        }
        $Data_view['a_Departments'] = $a_Departments;

        $a_DbPosition = DB::table('positions')->select('id','name')->where('status',1)->get();
        foreach($a_DbPosition as $o_DbPosition){
            $a_Positions[$o_DbPosition->id] = $o_DbPosition->name;
        }
        $Data_view['a_Positions'] = $a_Positions;

        $a_DbUser = DB::table('users')->select('id','code','name','email','department_id','position_id')->get();
        foreach($a_DbUser as $o_DbUser){
            $a_Users[$o_DbUser->id] = array(
                'code' => $o_DbUser->code,
                'name' => $o_DbUser->name,
                'email' => $o_DbUser->email,
                'department' => isset($a_Departments[$o_DbUser->department_id]) ? $a_Departments[$o_DbUser->department_id] : '',
                'position' => isset($a_Positions[$o_DbUser->position_id]) ? $a_Positions[$o_DbUser->position_id] : '',
            );
        }
        $Data_view['a_Users'] = $a_Users;
        DB::connection()->enableQueryLog();
        $a_result = $this->o_performance->GetAllCheckpoints('report');
        $query = DB::getQueryLog();
        $query = end($query);
        foreach ($query['bindings'] as $i => $binding) {
            $query['bindings'][$i] = "'$binding'";
        }

        $sz_query_change = str_replace(array('%', '?'), array('%%', '%s'), $query['query']);
        $sz_SqlCheckpoint = vsprintf($sz_query_change, $query['bindings']);

        // save session
        Session::set('sql_checkpoint', $sz_SqlCheckpoint);
        $Data_view['a_checkpoints'] = $a_result['a_data'];
        $Data_view['a_search'] = $a_result['a_search'];
        return view('checkpoint.report_checkpoint', $Data_view);
    }
}
