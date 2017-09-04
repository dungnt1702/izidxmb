<?php

namespace App\Models;

use Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Database\Eloquent\Model;
use DB;
use App\Util;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class LeaveRequest extends Model
{
    /**
     * function __Contruct
     */
    private $o_request;
    public function __construct() {
        
    }
    
    public function GetOneLeaveRequestById($id){
        if($id && $id >0){
            $a_field = array('id','code','user_id','email','name','type_id','numb_leave','department_id','department_name','manager_id','manager_comment',
            'hrm_id','hrm_comment','from_time','to_time','user_comment','inform_id');
            
            $o_data = DB::table('leave_requests')->select($a_field)->where('id', $id)->first();
            return $o_data;
        }else{
            return array();
        }
    }

    /**
     * @auth: Dienct
     * @des: get all leave request by user id
     * since: 4/1/2016     
     */
    public function GetAllLeaveRequestByUserId(){
        $i_userId = Auth::user()->id;
        if(isset($i_userId) && $i_userId > 0 ){
            $a_field = array('id','status','from_time','to_time','user_comment','manager_act_time','hrm_act_time','created_at','type_id','updated_at', 'manager_comment', 'hrm_comment');
            $a_data = DB::table('leave_requests')->select($a_field)->where('user_id', $i_userId)->where('status', '!=', 4)->orderBy('created_at', 'desc')->paginate(15);

            $a_LeaveTypes = DB::table('leave_types')->get();
            foreach ($a_LeaveTypes as $o_LeaveType)
            {
                $a_AllLeaveTypes[$o_LeaveType->id] = $o_LeaveType->name;
            }
            
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
                $val->leave_type_name = $a_AllLeaveTypes[$val->type_id];
                if($val->leave_type_name !=  config('cmconst.leave_type_business'))
                {
                    $i_HourFromTime = date('H',strtotime($val->from_time));
                    $i_HourToTime = date('H',strtotime($val->to_time));
                    $sz_FromTime = $i_HourFromTime == 12?'<strong>Bắt đầu:</strong> Chiều '.date('d/m/Y',strtotime($val->from_time)):'<strong>Bắt đầu:</strong> Sáng '.date('d/m/Y',strtotime($val->from_time));
                    $sz_ToTime = ($i_HourToTime == 12)?'<strong>Đi làm:</strong> Chiều '.date('d/m/Y',strtotime($val->to_time)):'<strong>Đi làm:</strong> Sáng '.date('d/m/Y',strtotime($val->to_time));
                    $val->time = $sz_FromTime.'<br>'.$sz_ToTime;
                    
                }
                else 
                {
                    $val->time = '<strong>Từ:</strong> '.Util::sz_DateTimeFormat($val->from_time).'<br> <strong>Đến:</strong> '.Util::sz_DateTimeFormat($val->to_time);
                }
                $val->stt = $key + 1;
                $val->delete = time() < strtotime($val->from_time)? 1 : 0;
                $val->from_time = Util::sz_DateTimeFormat($val->from_time);
                $val->to_time = Util::sz_DateTimeFormat($val->to_time);
                $val->created_at = Util::sz_DateTimeFormat($val->created_at);
            }
            return $a_data;
        }else{
            array();
        }
    }
    
    /**

     * @Auth: DienCt
     * @Des: Get all leave request management
     * @Since : 5/1/2016
     */
    public function GetAllLeaveManagement($user_id = 0){
        $a_search = array();
        $a_Data = array();
        // field select
        $a_field = array('id','code','name','manager_id','email','status','from_time','to_time','user_comment',
                        'manager_id','hrm_id','manager_act_time','hrm_act_time','created_at','type_id','updated_at');
        $o_Db = DB::table('leave_requests')->select($a_field);
        if($user_id != 0)
        {
            $a_Data = $o_Db->where('manager_id', $user_id);
        }
        
        $i_search_status = Input::get('search_status',0);
        $a_search['search_status'] = $i_search_status;
        if($i_search_status == 0) $a_Data = $o_Db->where('status', 0);
        else $a_Data = $o_Db->whereIn('status', array(1,2));
        
        $i_search_type = Input::get('search_type','');
        $sz_search_by = Input::get('search_by','');
       
        if($i_search_type != '') {
            $a_search['search_type'] = $i_search_type;
            $a_Data = $o_Db->where('type_id', $i_search_type);
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
        $a_Data = $o_Db->orderBy('created_at', 'desc')->paginate(20);

        if(count($a_Data)>0)
        {
            $a_LeaveTypes = DB::table('leave_types')->get();
            foreach ($a_LeaveTypes as $o_LeaveType) 
            {
                $a_AllLeaveTypes[$o_LeaveType->id] = $o_LeaveType->name;
            }
        
            foreach($a_Data as $key =>&$val)
            {
                $val->status_name = config('cmconst.leave_request_status')[$val->status];
                $val->leave_type_name = $a_AllLeaveTypes[$val->type_id];
                if($val->leave_type_name !=  config('cmconst.leave_type_business'))
                {
                    $i_HourFromTime = date('H',strtotime($val->from_time));
                    $i_HourToTime = date('H',strtotime($val->to_time));
                    $sz_FromTime = $i_HourFromTime == 12?'<strong>Bắt đầu:</strong> Chiều '.date('d/m/Y',strtotime($val->from_time)):'<strong>Bắt đầu:</strong> Sáng '.date('d/m/Y',strtotime($val->from_time));
                    $sz_ToTime = ($i_HourToTime == 12)?'<strong>Đi làm:</strong> Chiều '.date('d/m/Y',strtotime($val->to_time)):'<strong>Đi làm:</strong> Sáng '.date('d/m/Y',strtotime($val->to_time));
                    $val->time = $sz_FromTime.'<br>'.$sz_ToTime;
                    
                }
                else 
                {
                    $val->time = '<strong>Từ:</strong> '.Util::sz_DateTimeFormat($val->from_time).'<br> <strong>Đến:</strong> '.Util::sz_DateTimeFormat($val->to_time);
                }
                $val->stt = $key + 1;
                $val->from_time = Util::sz_DateTimeFormat($val->from_time);
                $val->to_time = Util::sz_DateTimeFormat($val->to_time);
                $val->created_at = Util::sz_DateTimeFormat($val->created_at);
            }
        }

        $a_return = array('a_data' => $a_Data, 'a_search' => $a_search);
        return $a_return;
    }
    /**
     * @Auth: DienCt
     * @Des: Hrm management leave request
     * @Since : 6/1/2016
    */
    public function GetAllLeaveHRM($user_id = 0){
        // field select
        $a_field = array('id','code','name','email','status','from_time','to_time','user_comment','position_name','department_name',
                        'type_id','created_at','updated_at');
        $a_search = array();
        $i_department_id = Input::get('department_id','');
        $i_search_type = Input::get('search_type','');
        $sz_search_by = Input::get('search_by','');
        $i_leave_types = Input::get('leave_types','');
        $i_search_status = Input::get('search_status',1);
        $a_data = array();
        
        // get data leave management
        if($user_id != 0){
            $o_Db = DB::table('leave_requests');
            $a_data = $o_Db->select($a_field);
            
            ///Nếu user duyệt là reporter//
            if(Auth::user()->hr_type == 2)
            {
                $o_hrm = DB::table('users')->select('id')->where('hr_type',1)->first();
                $a_data = $o_Db->where('user_id',$o_hrm->id);
            }
           
            if($i_department_id != '')
            {
                $a_search['department_id'] = $i_department_id;
                $a_data = $o_Db->where('department_id', $i_department_id);
            }
            
            if($i_search_type != '') {
            $a_search['search_type'] = $i_search_type;
            $a_Data = $o_Db->where('type_id', $i_search_type);
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

        if(count($a_data)>0){
            
            $a_LeaveTypes = DB::table('leave_types')->get();
            foreach ($a_LeaveTypes as $o_LeaveType) 
            {
                $a_AllLeaveTypes[$o_LeaveType->id] = $o_LeaveType->name;
            }
        
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
                $val->leave_type_name = $a_AllLeaveTypes[$val->type_id];
                
                if($val->leave_type_name !=  config('cmconst.leave_type_business'))
                {
                    $i_HourFromTime = date('H',strtotime($val->from_time));
                    $i_HourToTime = date('H',strtotime($val->to_time));
                    $sz_FromTime = $i_HourFromTime == 12?'<strong>Bắt đầu:</strong> Chiều '.date('d/m/Y',strtotime($val->from_time)):'<strong>Bắt đầu:</strong> Sáng '.date('d/m/Y',strtotime($val->from_time));
                    $sz_ToTime = ($i_HourToTime == 12)?'<strong>Đi làm:</strong> Chiều '.date('d/m/Y',strtotime($val->to_time)):'<strong>Đi làm:</strong> Sáng '.date('d/m/Y',strtotime($val->to_time));
                    $val->time = $sz_FromTime.'<br>'.$sz_ToTime;
                    
                }
                else 
                {
                    $val->time = '<strong>Từ:</strong> '.Util::sz_DateTimeFormat($val->from_time).'<br> <strong>Đến:</strong> '.Util::sz_DateTimeFormat($val->to_time);
                }
                $val->stt = $key + 1;
                $val->created_at = Util::sz_DateTimeFormat($val->created_at);
            }
        }
        $a_return = array('a_data' => $a_data, 'a_search' => $a_search);
        return $a_return;
    }
    
    /**
     * @Auth: HuyNN
     * @Des: Reporter management leave request
     * @Since : 8/4/2016
    */
    public function GetAllLeaveReporter($user_id = 0){
        // field select
        $a_field = array('id','code','name','email','status','from_time','to_time','user_comment','position_name','department_name',
                        'type_id','created_at','updated_at','manager_act_time','hrm_act_time');
        $a_search = array();
        $i_department_id = Input::get('department_id','');
        $i_search_type = Input::get('search_type','');
        $sz_search_by = Input::get('search_by','');
        $i_leave_types = Input::get('leave_types','');
        $i_search_status = Input::get('search_status',1);
        $a_data = array();
        
        // get data leave management
        if($user_id != 0){
            $o_Db = DB::table('leave_requests');
            $a_data = $o_Db->select($a_field);
            
            if($i_department_id != '')
            {
                $a_search['department_id'] = $i_department_id;
                $a_data = $o_Db->where('department_id', $i_department_id);
            }
            
            if($i_search_type != '') {
            $a_search['search_type'] = $i_search_type;
            $a_Data = $o_Db->where('type_id', $i_search_type);
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
                $a_data = $o_Db->where(function ($query) {
                    $query->where('status', 1)
                    ->orWhere(function ($query1) {
                            $query1->where('status', 0)
                                  ->where('manager_id', 0);
                    });
                });
            }
            $a_data = $o_Db->orderBy('created_at', 'desc')->paginate(20);
        }   

        if(count($a_data)>0){
            
            $a_LeaveTypes = DB::table('leave_types')->get();
            foreach ($a_LeaveTypes as $o_LeaveType) 
            {
                $a_AllLeaveTypes[$o_LeaveType->id] = $o_LeaveType->name;
            }
        
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
                $val->leave_type_name = $a_AllLeaveTypes[$val->type_id];
                
                if($val->leave_type_name !=  config('cmconst.leave_type_business'))
                {
                    $i_HourFromTime = date('H',strtotime($val->from_time));
                    $i_HourToTime = date('H',strtotime($val->to_time));
                    $sz_FromTime = $i_HourFromTime == 12?'<strong>- Bắt đầu:</strong> Chiều '.date('d/m/Y',strtotime($val->from_time)):'<strong>- Bắt đầu:</strong> Sáng '.date('d/m/Y',strtotime($val->from_time));
                    $sz_ToTime = ($i_HourToTime == 12)?'<strong>- Đi làm:</strong> Chiều '.date('d/m/Y',strtotime($val->to_time)):'<strong>- Đi làm:</strong> Sáng '.date('d/m/Y',strtotime($val->to_time));
                    $val->time = $sz_FromTime.'<br>'.$sz_ToTime;
                    
                }
                else 
                {
                    $val->time = '<strong>- Từ:</strong> '.Util::sz_DateTimeFormat($val->from_time).'<br> <strong>- Đến:</strong> '.Util::sz_DateTimeFormat($val->to_time);
                }
                $val->stt = $key + 1;
                $val->created_at = Util::sz_DateTimeFormat($val->created_at);
                $val->manager_act_time = $val->manager_act_time != ''? Util::sz_DateTimeFormat($val->manager_act_time):'';
                $val->hrm_act_time = $val->hrm_act_time != ''? Util::sz_DateTimeFormat($val->hrm_act_time):'' ;
            }
        }

        $a_return = array('a_data' => $a_data, 'a_search' => $a_search);
        return $a_return;
    }
    
    /**
     * Get infomation user for Leave Request API
     * @author Vit
     * @since 26/02/2016
     */

    function a_fGetUserInfo($sz_Email, $sz_Pword) {
        
        if ($sz_Email && $sz_Pword){
            $o_department = DB::table('departments')->select('name','numb_of_work','time_start','time_end')->where('id',Auth::user()->department_id)->first();
            $a_Info['o_department'] = $o_department;

            $a_Info['o_direct_manager'] = DB::table('users')->select('name')->where('id',Auth::user()->direct_manager_id)->first();        
            $a_Info['o_group'] = DB::table('groups')->select('name')->where('id',Auth::user()->group_id)->first();

            $o_job = DB::table('jobs')->select('name','enable_sunday')->where('id',Auth::user()->job_id)->first();
            $a_Info['o_job'] = $o_job;

            $a_leave_types = DB::table('leave_types')->select('id','name')->where('status', 1)->get();
            foreach($a_leave_types as $key => $val){
                $ary[$val->id] = $val->name;
            }
            $a_Info['a_leave_types'] = $ary;
            
            $o_hrm = DB::table('users')->select('id','name','email','department_id','direct_manager_id')->where('hr_type',1)->first();
            $a_Info['o_hrm'] = $o_hrm;
            
            $o_ManagerBussinness = DB::table('users')->select('id','department_id')->where('email',config('cmconst.email_manager_bussinness'))->first();
        
            /// Nếu user là nhân viên thường thì lấy ra các quản lý thuộc cùng phòng ban trừ người quản lý trực tiếp////
            if(Auth::user()->is_manager == 0)
            {
                ///////Nếu user thuộc phòng HCNS////////
                if(Auth::user()->department_id == $o_hrm->department_id)
                {
                    switch (Auth::user()->direct_manager_id) {
                        case $o_hrm->id:
                            $a_manager = DB::table('users')->select('id','name')->where(array('department_id' => Auth::user()->department_id, 'is_manager' => 1))->where('id','!=',$o_hrm->id)->get();
                            break;
                        default:
                            $a_manager = DB::table('users')->select('id','name')->where(array('department_id' => Auth::user()->department_id, 'is_manager' => 1))->get();
                            break;
                    }
                }
                /// Nếu user ko thuộc HCNS/////
                else
                {
                    //// Nếu user cùng phòng ban với giám đốc kinh doanh hội sở thì ko cần load giám đốc kinh doanh hội sở//////
                    if(Util::b_fCheckObject($o_ManagerBussinness) && Auth::user()->department_id == $o_ManagerBussinness->department_id)
                    {
                        $a_manager = DB::table('users')->select('id','name')->where(array('department_id' => Auth::user()->department_id, 'is_manager' => 1))->whereNotIn('id', array(Auth::user()->direct_manager_id,$o_ManagerBussinness->id))->get();
                    }
                    else $a_manager = DB::table('users')->select('id','name','direct_manager_id')->where(array('department_id' => Auth::user()->department_id, 'is_manager' => 1))->where('id','!=',Auth::user()->direct_manager_id)->get();
                } 
            }
                ///Nếu user là cấp quản lý load các quản lý khác thuộc cùng phòng ban/////
            else  
            {
                ////Nếu user cùng phòng ban với giám đốc kinh doanh hội sở thì ko cần load giám đốc kinh doanh hội sở//////
                if(Util::b_fCheckObject($o_ManagerBussinness) && Auth::user()->department_id == $o_ManagerBussinness->department_id)
                {
                    $a_manager = DB::table('users')->select('id','name')->where(array('department_id' => Auth::user()->department_id, 'is_manager' => 1))->whereNotIn('id', array(Auth::user()->id,Auth::user()->direct_manager_id,$o_ManagerBussinness->id))->get();
                }
                else
                {
                    /// Nếu user cùng phòng HRM///
                    if(Auth::user()->department_id == $o_hrm->department_id)
                    {
                        ////Nếu quan lý trực tiếp là HRM thì ko load HRM////
                        if(Auth::user()->direct_manager_id == $o_hrm->id){
                            $a_manager = DB::table('users')->select('id','name')->where(array('department_id' => Auth::user()->department_id, 'is_manager' => 1))->whereNotIn('id', array(Auth::user()->id,Auth::user()->direct_manager_id))->get();
                        }
                        ///Nếu quản lý trực tiếp ko phải HRM thì load tất cả các quản lý khác cùng phòng////
                        else $a_manager = DB::table('users')->select('id','name')->where(array('department_id' => Auth::user()->department_id, 'is_manager' => 1))->whereNotIn('id', array(Auth::user()->id))->get();
                    }     
                    ///Nếu ko cùng phòng HRM thì load các quản lý khác trừ ng quản lý trực tiếp////
                    else $a_manager = DB::table('users')->select('id','name')->where(array('department_id' => Auth::user()->department_id, 'is_manager' => 1))->whereNotIn('id', array(Auth::user()->id,Auth::user()->direct_manager_id))->get();
                }
            }    

            //// Lấy ra người quản lý trực tiếp//////
            $o_direct_manager = DB::table('users')->select('id','name','department_id','direct_manager_id')->where('id', Auth::user()->direct_manager_id)->first();    
            if(Util::b_fCheckObject($o_direct_manager))
            {
                $o_name_depart = DB::table('departments')->select('name')->where('id',$o_direct_manager->department_id)->first();
                if($o_name_depart->name == config('cmconst.general_manager_depart') || Auth::user()->department_id == $o_hrm->department_id) $o_direct_manager = $o_hrm;     
            }
            else $o_direct_manager = $o_hrm;
            $a_Info['o_direct_manager'] = $o_direct_manager;
            $a_Info['a_manager'] = $a_manager;
            
            return $a_Info;
        }
        return false;
    }
    
    /**
     * Get infomation user for Leave Request API
     * @author Vit
     * @since 26/02/2016
     */
    function i_fCountLeaveRequestPending()
    {
        $i_Count = DB::table('leave_requests')->where('status', 1)->orWhere(function ($query) 
        {
            $query->where('status', 0)->where('manager_id', 0);
        })->count();
        return $i_Count;
    }
}
