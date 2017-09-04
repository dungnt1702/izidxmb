<?php
namespace App\Http\Controllers\User;
namespace App\Http\Controllers\LeaveRequest;
use DB;
use Auth;
use Illuminate\Http\Request as o_request;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;
use App\Models\Positions;
use Illuminate\Support\Facades\Input;
use App\Util;
use App\Http\Requests;
use App\Models\LeaveRequest as request_model;
use App\Models\Users as user_model;
use App\Models\Department as o_department_model;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use App\Models\Roles as o_RoleModel;
use Illuminate\Routing\Route;
class LeaveRequestController extends Controller
{
    //
    private $o_leave_request;
    private $o_users;
    private $o_department;
    

    public function __construct() {

        $o_Role = new o_RoleModel();
        $o_Role->b_fCheckRole();

        $this->o_leave_request = new request_model();
        $this->o_users = new user_model();
        $this->o_department = new o_department_model();       
    }
    /**
     * auth: Dienct
     * Des: list leave request userID
     * since: 4/1/2016
     * 
     */
    public function ListLeaveRequest(){
        $a_data = $this->o_leave_request->GetAllLeaveRequestByUserId();
        return view('leave_request.index',['a_leaveRequest' => $a_data]);
    }
    
    /**
     * auth: Dienct
     * Des: leave request by department
     * Since: 4/1/2016
     */
    public function LeaveRequestManagement(){
        $a_LeaveType = DB::table('leave_types')->select('id','name')->get();
        foreach($a_LeaveType as $o_LeaveType){
            $a_LeaveTypes[$o_LeaveType->id] = $o_LeaveType->name;
        }
        $Data_view['a_LeaveTypes'] = $a_LeaveTypes;
        
        $i_userId = Auth::user()->id;
        
        //get leave request manager
        $a_result = $this->o_leave_request->GetAllLeaveManagement($i_userId);
        $Data_view['a_data'] = $a_result['a_data'];
        $Data_view['a_search'] = $a_result['a_search'];
        return view('leave_request.manager',$Data_view); 
    }
    /**
     * auth: HuyNN
     * Des: Add Leave Request
     * Since: 5/1/2016
     */
    public function LeaveRequest(o_request $o_resquest, Route $route)
    {
        $o_department = DB::table('departments')->select('name','numb_of_work','time_start','time_end')->where('id',Auth::user()->department_id)->first();
        $Data_view['o_department'] = $o_department;

        $o_position = DB::table('positions')->select('name')->where('id',Auth::user()->position_id)->first();
        $Data_view['o_position'] = $o_position;
        
        $Data_view['o_direct_manager'] = DB::table('users')->select('name')->where('id',Auth::user()->direct_manager_id)->first();        
        $Data_view['o_group'] = DB::table('groups')->select('name')->where('id',Auth::user()->group_id)->first();
        
        $o_job = DB::table('jobs')->select('name','enable_sunday')->where('id',Auth::user()->job_id)->first();
        $Data_view['o_job'] = $o_job;
        
        $Data_view['a_leave_types'] = DB::table('leave_types')->select('id','name')->where('status', 1)->get();
        $o_hrm = DB::table('users')->select('id','name','email','department_id','direct_manager_id')->where('hr_type',1)->first();
        $Data_view['o_hrm'] = $o_hrm;
        
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
        $Data_view['o_direct_manager'] = $o_direct_manager;
        $Data_view['a_manager'] = $a_manager;

        if($o_resquest->submit)
        {
            $a_data = $o_resquest->data;
            //Nếu không phải nghỉ công tác/////
            if(isset($a_data['numb_leave']))
            {
                $sz_grub = $o_resquest->grub;
                $sz_FromTime = ($sz_grub == '00:00:00'?'Sáng ':'Chiều ').$a_data['from_time']; // Thời gian bắt đầu nghỉ cho vào email body
                $i_FromTime = strtotime(str_replace('/','-',$a_data['from_time']).' '.$sz_grub); // Đổi ngày bắt đầu nghỉ sang int
                $a_data['from_time'] = date('Y-m-d H:i:s',$i_FromTime); // Ngày bắt đầu nghỉ để insert db
                
                $i_ToTime = strtotime($a_data['to_time']);
                $sz_ToTime = (date('H',$i_ToTime) < 12? 'Sáng ': 'Chiều ').date('d/m/Y',$i_ToTime); //Thời gian kết thúc nghỉ cho vào email body
            }
            else  /// Nếu là nghỉ công tác/////
            {
                $a_data['numb_leave'] = 0;
                
                $sz_FromTime = $a_data['from_time_business']; // Thời gian bắt đầu nghỉ cho vào email body
                $i_FromTime_Bsn = strtotime(str_replace('/','-',$a_data['from_time_business']));
                $a_data['from_time'] = date('Y-m-d H:i:s',$i_FromTime_Bsn); // Ngày bắt đầu nghỉ công tác để insert db
                unset($a_data['from_time_business']);
                
                $sz_ToTime = $a_data['to_time_business']; // Thời gian kết thúc nghỉ cho vào email body
                $i_ToTime_Bsn = strtotime(str_replace('/','-',$a_data['to_time_business']));
                $a_data['to_time'] = date('Y-m-d H:i:s',$i_ToTime_Bsn); // Ngày bắt đầu nghỉ công tác để insert db
                unset($a_data['to_time_business']);   
            }
            
            if(Auth::user()->id == $o_hrm->id)
            {
                $a_data['status'] = 1;
            }
            $a_data['user_id'] = Auth::user()->id;
            $a_data['email'] = Auth::user()->email;
            $a_data['code'] = Auth::user()->code;
            $a_data['name'] = Auth::user()->name;
            $a_data['department_id'] = Auth::user()->department_id;
            $a_data['department_name'] = $o_department->name;
            $a_data['position_id'] = Auth::user()->position_id;
            $a_data['position_name'] = $o_position->name;
            $a_data['enable_sunday'] = Util::b_fCheckObject($o_job)?$o_job->enable_sunday:1;
            $a_data['hrm_id'] = $o_hrm->id;
            $a_data['manager_id'] = ($a_data['manager_id']!= $o_hrm->id)?$a_data['manager_id']:(Auth::user()->department_id == $o_hrm->department_id?$o_hrm->id:0);
            $a_data['created_at'] = date('Y-m-d H:i:s',time());
            
            if(isset($a_data['inform_id']))
            {
                $sz_inform_id = '';
                foreach ($a_data['inform_id'] as $sz_id) 
                {
                    $sz_inform_id.= $sz_id.',';
                }
                $a_data['inform_id'] = rtrim($sz_inform_id,', ');
                /// Nếu user thuộc cùng phòng với giám đốc kinh doanh hội sở và ng quản lý trực tiếp ko phải là giám đốc kinh doanh hội sở
                
                if(Util::b_fCheckObject($o_ManagerBussinness) && Auth::user()->department_id == $o_ManagerBussinness->department_id && Auth::user()->direct_manager_id != $o_ManagerBussinness->id)
                {
                    $a_data['inform_id'].= ','.$o_ManagerBussinness->id;
                }
            }   
            else
            {
                if(Util::b_fCheckObject($o_ManagerBussinness) && Auth::user()->department_id == $o_ManagerBussinness->department_id && Auth::user()->direct_manager_id != $o_ManagerBussinness->id)
                {
                    $a_data['inform_id'] = $o_ManagerBussinness->id;
                }
            }
            
            //$a_data['status'] = Auth::user()->department_id == $o_hrm->department_id?1:0;
            $i_NewLeaveRequest = DB::table('leave_requests')->insertGetId($a_data); //// Insert dữ liệu
            $sz_Token = md5($i_NewLeaveRequest.str_random(5)); /// Tạo chuỗi token gửi qua email để duyệt trực tiếp
             
            $o_type = DB::table('leave_types')->select('name')->where(array('id' => $a_data['type_id']))->first();
            $a_EmailBody = array(
            'user_code' => Auth::user()->code,
            'user_name' => Auth::user()->name,
            'position' => $Data_view['o_position']->name,
            'department' => $Data_view['o_department']->name,
            'leave_request_type' => $o_type->name,
            'from' => $sz_FromTime,
            'to' => $sz_ToTime,
            'user_comment' => $a_data['user_comment'],
            );
            if($a_data['numb_leave'] != 0)
            {
                $a_EmailBody['numb_leave'] = $a_data['numb_leave'];
            }

            ///Nếu ng gửi là TP hoặc ng gửi cùng phòng với HRM///
            if($a_data['manager_id'] == 0 || Auth::user()->department_id == $o_hrm->department_id)
            {
                ///Nếu người gửi là HRM-> send email tới Reporter///
                if(Auth::user()->hr_type == 1)
                {
                    $sz_ToEmail = $this->o_users->sz_GetReporter(); 
                    $a_EmailBody['url'] = URL::to('/').'/hrm_management';
                }
                else
                {
                    ///Nếu ng gửi đơn cùng phòng HRM////
                    if(Auth::user()->department_id == $o_hrm->department_id)
                    {
                        $a_EmailBody['url'] = URL::to('/').'/leave_management';
                        $a_EmailBody['accept'] = URL::to('/').'/directly_approve?key='.$sz_Token.'&user=manager&stt=2';
                        $a_EmailBody['rejected'] = URL::to('/').'/directly_approve?key='.$sz_Token.'&user=manager&stt=3';
                    }
                    //Nếu người gửi đơn ko cùng phòng với HRM -> Người gửi đứng đầu phòng ban/////////
                    else
                    {
                        $a_EmailBody['url'] = URL::to('/').'/hrm_management';
                        $a_EmailBody['accept'] = URL::to('/').'/directly_approve?key='.$sz_Token.'&user=hrm&stt=2';
                        $a_EmailBody['rejected'] = URL::to('/').'/directly_approve?key='.$sz_Token.'&user=hrm&stt=3';
                    }  
                    $sz_ToEmail = $o_hrm->email;
                }  
            }
            /////Nếu ng gui là Nhân viên bt thì gửi cho ng quản lý trực tiếp/////
            else 
            {
                $o_Manager = DB::table('users')->select('email')->where(array('id' => $a_data['manager_id']))->first();
                $sz_ToEmail = $o_Manager->email;
                $a_EmailBody['url'] = URL::to('/').'/leave_management';
                $a_EmailBody['accept'] = URL::to('/').'/directly_approve?key='.$sz_Token.'&user=manager&stt=1';
                $a_EmailBody['rejected'] = URL::to('/').'/directly_approve?key='.$sz_Token.'&user=manager&stt=3';
            }            
            Mail::send('mail.leave_request',array('a_EmailBody' => $a_EmailBody), function($message) use ($sz_ToEmail)
            {
                ///Gửi email tới người duyệt đơn///
                $message->from('noreply@dxmb.vn', 'Hệ thống đăng ký vắng mặt DXMB');
                $message->to($sz_ToEmail);
                $message->subject('Đơn đăng ký vắng mặt mới từ Nhân viên DXMB');
            });
            ///////////////////End Thực hiện gửi Email/////////////////  
            
            ///Update DB token key for Leave Request////
            DB::table('leave_requests')->where('id', $i_NewLeaveRequest)->update(array('token_key' => $sz_Token));
            
            if($o_resquest->multi_insert == 'on'){
                return redirect(URL::current())->with('status', 'Bạn đăng ký vắng mặt thành công!'); 
            }
            return redirect('list_leave_request')->with('status', 'Bạn đăng ký vắng mặt thành công!');
        }
        return view('leave_request.request',$Data_view);
    }

    /**
     * auth: Dienct
     * Des: leave request by department
     * Since: 6/1/2016
     */
    public function HrmManagement(o_request $o_resquest)
    {
        ////Nếu có thực hiện Check các đơn để duyệt nhiều////
        if($o_resquest->check)
        {          
            $a_Check = $o_resquest->check;
            $i_UpdateStt = $o_resquest->allow_all? 2 : 3;

            ///Update DB///
            foreach ($a_Check as $i_IdLeaveRequest) 
            {
                DB::table('leave_requests')->where('id',$i_IdLeaveRequest)->update(['status'=> $i_UpdateStt]);
            }
            
            ////Tạo một Session lưu thông tin tất cả những đơn dc duyệt nhiều để tiến hành gửi Email///
            session(
                ['sendallmail' => [
                        'a_IdLeaveRequest' => $a_Check,
                        'sz_typeconfirm' => $i_UpdateStt == 2? 1 : 0,
                    ]    
                ]
            );
            return redirect(URL::current())->with('status', 'Cập nhật thành công!');  
        }
        
        $i_userId = Auth::user()->id;
        $a_department = $this->o_department->getAll();
        $Data_view['a_department'] = $a_department;
        //get leave request manager
        $a_result = $this->o_leave_request->GetAllLeaveHrm($i_userId);
        
        $a_LeaveType = DB::table('leave_types')->select('id','name')->get();
        foreach($a_LeaveType as $o_LeaveType){
            $a_LeaveTypes[$o_LeaveType->id] = $o_LeaveType->name;
        }
        
        $Data_view['a_LeaveTypes'] = $a_LeaveTypes;
        $Data_view['a_data'] = $a_result['a_data'];
        $Data_view['a_search'] = $a_result['a_search'];
              
        return view('leave_request.hrm',$Data_view); 
    }
    
    /**
     * auth: HuyNN
     * Des: Show leave request report for Reporter
     * Since: 8/4/2016
     */
    public function ReporterManagement(){
        $i_userId = Auth::user()->id;
        $a_department = $this->o_department->getAll();
        $Data_view['a_department'] = $a_department;
        //get leave request manager
        $a_result = $this->o_leave_request->GetAllLeaveReporter($i_userId);
        
        $a_LeaveType = DB::table('leave_types')->select('id','name')->get();
        foreach($a_LeaveType as $o_LeaveType){
            $a_LeaveTypes[$o_LeaveType->id] = $o_LeaveType->name;
        }
        
        $Data_view['a_LeaveTypes'] = $a_LeaveTypes;
        $Data_view['a_data'] = $a_result['a_data'];
        
        $Data_view['a_search'] = $a_result['a_search'];
              
        return view('leave_request.reporter',$Data_view); 
    }
    
    /**
     * auth: HuyNN
     * Des: leave request for Reporter
     * Since: 13/01/2016
     */
    public function Report(o_request $o_resquest)
    {     
        $a_search = array();
        $a_AllLeaveRequest = array();
        $o_Db = DB::table('leave_request_report');
        
        $i_search_department = $o_resquest->search_department;
        if($i_search_department != 0)
        {
            $a_AllLeaveRequest = $o_Db->where('department_id', $i_search_department);
            $a_search['search_department'] = $i_search_department;
        }

        $i_search_position = $o_resquest->search_position;
        if($i_search_position != 0)
        {
            $a_AllLeaveRequest = $o_Db->where('position_id', $i_search_position);
            $a_search['search_position'] = $i_search_position;
        }

        $sz_search_name = $o_resquest->search_name;
        if($sz_search_name != '')
        {
            $a_AllLeaveRequest = $o_Db->where('name', 'like', '%'.$sz_search_name.'%');
            $a_search['search_name'] = $sz_search_name;
        }

        $search_year = $o_resquest->search_year;
        $search_month = $o_resquest->search_month;
        if($search_year != 0 && $search_month != 0)
        {
            $a_AllLeaveRequest = $o_Db->where(array('month' => $search_month, 'year' => $search_year));
            $a_search['search_month'] = $search_month;
            $a_search['search_year'] = $search_year;
        }
            
        /// Nếu có chọn tìm kiếm theo tháng, năm thì search theo tháng năm
        if(isset($a_search['search_year']))
        {
            $search_month = (int) $search_month;
            $a_RangeDate = Util::GetRangeDate($search_month,$search_year);
            $a_AllLeaveRequest = $o_Db->orderBy('name', 'asc')->paginate(20);
        }
        ///Nếu ko chọn tìm kiếm theo tháng năm thì search theo tháng và năm hiện tại
        else 
        {
            $a_RangeDate = Util::GetRangeDate(0,0); 
            
            $i_CurrentMonth = date('m');
            $i_CurrentYear = date('Y');
            $a_search['search_month'] = (int)$i_CurrentMonth;
            $a_search['search_year'] = $i_CurrentYear;
            $a_AllLeaveRequest = $o_Db->where(array('month' => $i_CurrentMonth,'year' => $i_CurrentYear))->orderBy('name', 'asc')->paginate(20);
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
        if(Util::b_fCheckObject($a_AllLeaveRequest))
        {
            foreach ($a_AllLeaveRequest as $key => $o_UserLeaveRequest) 
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
                            //echo 'ko có &&& là '.$key;
                            $a_val = explode('|', $sz_val);
                            $o_UserLeaveRequest->$key = $a_val;
                        }
                    }
                }
            }
            $Data_view['a_AllLeaveRequest'] = $a_AllLeaveRequest;
        }
        
        $a_SttReport = DB::table('status_leave_request_report')->where(array('month' => $a_search['search_month'], 'year' => $a_search['search_year']))->get();

        $a_UserSttReport = array();
        foreach ($a_SttReport as $o_val) 
        {
            foreach ($o_val as $key => $val) {
                if(is_numeric ($key) && $val == 1)
                {
                   $a_UserSttReport[$o_val->user_id][$o_val->year][$o_val->month][$key] = $val;
                }
            }
        }
        
        $Data_view['a_UserSttReport'] = $a_UserSttReport;
        $Data_view['a_Position'] = DB::table('positions')->select('id','name')->get();
        $Data_view['a_Department'] = DB::table('departments')->select('id','name')->get();
        
        return view('leave_request.report',$Data_view);
    }
	
	/**
     * auth: HuyNN
     * Des: Approve Directly for Manager and HRM///
     * Since: 03/02/2016
     */
    public function ApproveDirectly(o_request $o_resquest, Route $route)
    {     
        $sz_mes = '';
        $sz_key = $o_resquest->key;
        $sz_user = $o_resquest->user;
        $i_stt = $o_resquest->stt;
        
        if(!isset($sz_key,$sz_user,$i_stt) || ($sz_key == '' || !in_array($sz_user,array('manager','hrm')) || !in_array($i_stt,array(1,2,3))))
        {
            $sz_mes = 'Liên kết không chính xác. Vui lòng kiểm tra lại!';
        }
        else
        {
            $o_LeaveRequest = DB::table('leave_requests')->where('token_key',$sz_key)->first();
            if(!Util::b_fCheckObject($o_LeaveRequest)) $sz_mes = 'Token không chính xác hoặc đơn đã được xử lý! Vui lòng kiểm tra lại!';
            else
            {
                $o_type = DB::table('leave_types')->select('name')->where('id',$o_LeaveRequest->type_id)->first();
                ///Mảng lưu thông tin đơn vắng mặt///
                $a_InfoLeaveRequest = array(
                    'user_code' => $o_LeaveRequest->code,
                    'user_name' => $o_LeaveRequest->name,
                    'department' => $o_LeaveRequest->department_name,
                    'leave_request_type' => $o_type->name,
                    'from' => Util::sz_DateTimeFormat($o_LeaveRequest->from_time),
                    'to' => Util::sz_DateTimeFormat($o_LeaveRequest->to_time),
                    'user_comment' => $o_LeaveRequest->user_comment,
                );
                if($o_LeaveRequest->numb_leave != 0)
                {
                    $a_InfoLeaveRequest['numb_leave'] = $o_LeaveRequest->numb_leave;
                }
                
                ///Mảng lưu email những người liên quan///
                if($o_LeaveRequest->inform_id != '')
                {
                    $a_InformId = explode(',', $o_LeaveRequest->inform_id);
                    $a_InformUsers = DB::table('users')->select('email')->whereIn('id', $a_InformId)->get();
                    foreach ($a_InformUsers as $o_val) {
                        $a_EmailInform[] = $o_val->email;
                    }
                }         
                         
                switch ($sz_user) 
                {
                    case 'hrm':
                        if(!in_array($i_stt,array(2,3))) $sz_mes = 'Liên kết không chính xác! Vui lòng kiểm tra lại!';
                        else
                        {
                            //Nếu là TP và trạng thái đơn là 0 hoặc nếu ko là trưởng phòng và trạng thái đơn là 1////
                            if((($o_LeaveRequest->manager_id == 0 && $o_LeaveRequest->status == 0) || ($o_LeaveRequest->manager_id != 0 && $o_LeaveRequest->status == 1)))
                            {
                                $sz_SubjectEmail = 'Xác nhận đơn vắng mặt từ Phòng nhân sự';  // Subject email gửi
                                ///Nếu đồng ý đơn thì gửi mail tới nhân viên và ng liên quan////////
                                if($i_stt == 2)
                                {
                                    $a_EmailTo = isset($a_EmailInform)?array_merge($a_EmailInform,array($o_LeaveRequest->email)):array($o_LeaveRequest->email);
                                }
                                //Nếu từ chối////
                                else
                                {
                                    ///Nếu là Tp thì chỉ gửi mail cho chính TP///
                                    if($o_LeaveRequest->manager_id == 0) $a_EmailTo = array($o_LeaveRequest->email);
                                    
                                    ///Nếu ko phải TP thì gửi mail cho nhân viên và ng quản lý
                                    else
                                    {
                                        $o_manager = DB::table('users')->select('email')->where('id', $o_LeaveRequest->manager_id)->first();
                                        $a_EmailTo =  array($o_LeaveRequest->email,$o_manager->email);
                                    }
                                } 
                                
                                /////////Thực hiện Update Database////
                                DB::table('leave_requests')->where('id', $o_LeaveRequest->id)->update(array('status' => $i_stt, 'token_key' => '', 'hrm_act_time'=>date('Y-m-d H:i:s',time())));
                            }
                            else $sz_mes = 'Bạn không thể đồng ý hoặc từ chối đơn này. Vui lòng kiểm tra lại!';
                        }
                        break;
                    default:
                        $o_hrm = DB::table('users')->select('department_id')->where('hr_type',1)->first();
                        //Nếu cùng phòng ban với HRM///
                        if($o_LeaveRequest->department_id == $o_hrm->department_id && $o_LeaveRequest->status == 0)
                        {
                            if(!in_array($i_stt,array(2,3))) $sz_mes = 'Liên kết không chính xác! Vui lòng kiểm tra lại!';
                            else
                            {
                                $sz_SubjectEmail = 'Xác nhận đơn vắng mặt từ Quản lý';  // Subject email gửi
                                ///Nếu đồng ý đơn thì gửi mail tới nhân viên và ng liên quan////////
                                if($i_stt == 2) $a_EmailTo = isset($a_EmailInform)?array_merge($a_EmailInform,array($o_LeaveRequest->email)):array($o_LeaveRequest->email);
                                
                                //Từ chối gửi mail cho nhân viên đó////
                                else $a_EmailTo = array($o_LeaveRequest->email); 
                                
                                /////////Thực hiện Update Database////
                                DB::table('leave_requests')->where('id', $o_LeaveRequest->id)->update(array('status' => $i_stt, 'token_key' => '', 'manager_act_time'=>date('Y-m-d H:i:s',time())));
                            }  
                        }
                        ///Nếu là nhân viên bt////
                        else if($o_LeaveRequest->manager_id != 0 )
                        {
                            if($o_LeaveRequest->status == 0)
                            {
                                $sz_SubjectEmail = 'Đơn đăng ký vắng mặt mới từ Nhân viên DXMB';  // Subject email gửi
                                if(!in_array($i_stt,array(1,3))) $sz_mes = 'Liên kết không chính xác! Vui lòng kiểm tra lại!';
                                else
                                {
                                    ///Nếu đồng ý đơn thì gửi mail tới HRM kèm theo các url duyệt////////
                                    if($i_stt == 1)
                                    {
                                        $o_hrm = DB::table('users')->select('email')->where('id', $o_LeaveRequest->hrm_id)->first();
                                        $a_EmailTo = array($o_hrm->email);
                                        $a_InfoLeaveRequest['url'] = URL::to('/').'/hrm_management';
                                        $a_InfoLeaveRequest['accept'] = URL::to('/').'/directly_approve?key='.$sz_key.'&user=hrm&stt=2';
                                        $a_InfoLeaveRequest['rejected'] = URL::to('/').'/directly_approve?key='.$sz_key.'&user=hrm&stt=3';
                                        DB::table('leave_requests')->where('id', $o_LeaveRequest->id)->update(array('status' => $i_stt,'manager_act_time'=>date('Y-m-d H:i:s',time())));
                                    }
                                    //Từ chối gửi mail cho nhân viên đó////
                                    else $a_EmailTo = array($o_LeaveRequest->email); 
                                }  
                            }
                            else $sz_mes = 'Bạn không thể đồng ý hoặc từ chối đơn này. Vui lòng kiểm tra lại!';
                        }
                        
                        break;
                }
            }  
        }

        if($sz_mes == '') /// Nếu ko có lỗi gì xảy ra thì thực hiện update và gửi email///
        {
            //DB::table('leave_requests')->where('token_key', $sz_key)->update(array('status' => $i_stt, 'token_key' => ''));
            $a_InfoLeaveRequest['i_stt'] = $i_stt;
            $a_InfoLeaveRequest['sz_Title'] = $sz_SubjectEmail;
            Mail::send('mail.approve_directly_send_mail',array('a_InfoLeaveRequest' => $a_InfoLeaveRequest), function($message) use ($a_EmailTo,$sz_SubjectEmail)
            {
                ///Gửi email tới người duyệt đơn///
                $message->from('noreply@dxmb.vn', 'Hệ thống đăng ký vắng mặt DXMB');
                $message->to($a_EmailTo);
                $message->subject($sz_SubjectEmail);
            });
            ///////////////////End Thực hiện gửi Email/////////////////  
            
        }
        else $Data_view['mes'] = $sz_mes;
        $Data_view['a_InfoLeaveRequest'] = isset($a_InfoLeaveRequest)?$a_InfoLeaveRequest:array();
        
        ///Get Current Controller and Action////
        $sz_Controller =  $route->getActionName();
        $a_Controller = explode('\\',$sz_Controller);
        $sz_action =  end($a_Controller);
        $Data_view['sz_action'] = $sz_action;
        return view('leave_request.approve_directly',$Data_view);
    }
}
