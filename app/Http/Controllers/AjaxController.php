<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;

use Auth;
use Hash;
use App\Http\Requests;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Input;

use App\Util;
use App\MailApi;
use Illuminate\Support\Facades\Mail;
use App\Models\LeaveRequest as request_model;
use Illuminate\Support\Facades\Session;
class AjaxController extends Controller
{
    private $i_id;
    private $i_type;
    private $sz_func;
    private $sz_tbl;
    private $sz_field;
    private $sz_val;
    private $o_LeaveRequestModel;
    protected $_o_MailApi;


    /**
     * function __Contruct    
     */
    public function __construct() {
        
        $this->i_id = Input::get('id',0);
        $this->i_type = Input::get('type',0);
        $this->sz_func = Input::get('func');
        $this->sz_tbl = Input::get('tbl');
        $this->sz_field = Input::get('field');
        $this->sz_val = Input::get('val');
        $this->o_LeaveRequestModel = new request_model();
        $this->_o_MailApi = new MailApi();
    }
    
    /**
     *@Auth: Dienct
     *@Since: 31/12/2015
     *@Des: Set case process function ajax 
     * 
     */

    public function SetProcess(){
        if($this->sz_func == "") exit;
        //set function process
        switch ($this->sz_func) {
            case "delete-row":
                $this->DeleteRow();
                break;
            case "load-group":
                $this->Load_Department_Group();
                break;
            case "load-direct-manager":
                $this->Load_Direct_Manager();
                break;
            case "validate-add-edit-user":
                $this->ValidateAddEditUser();
                break;
            case "recover-row":
                $this->RecoverRow();
                break;
            case "allow-leave-request":
                $this->AllowLeaveRequest();
                break;
            case "approve-ot":
                $this->ApproveOT();
                break;
            case "send-mail":
                $this->SendMailHrm();
                break;
            case "hrm-send-mail-ot":
                $this->SendMaiOtHrm();
                break;
            case "get-to-time":
                $this->GetToTime();
                break;
            case "manager-confirm-leave-request":
                $this->ManagerAllowLeaveRequest();
                break;
            case "manager-confirm-ot":
                $this->ManagerAllowOT();
                break;
            case "manager-send-mail":
                $this->SendMailManager();
                break;
            case "manager-send-mail-ot":
                $this->SendMailManagerOT();
                break;
            case "check-duplicate-field":
                $this->CheckDuplicateField();
                break;
            case "check-duplicate-leave-request":
                $this->CheckDuplicateLeaveRequest();
                break;
            case "check-duplicate-ot":
                $this->CheckDuplicateOT();
                break;
            case "delete-my-leave-request":
                $this->DeleteMyLeaveRequest();
                break;
            case "manager-del-leave-request":
                $this->ManagerDelLeaveRequest();
                break;
            case "update-status":
                $this->UpdateStatus();
                break;
            case "update_stt_leave_request_report":
                $this->UpdateSttLeaveRequestReport();
                break;
            case "confirm-change-manager":
                $this->ConfirmChangeManager();
                break;
            case "confirm-change-manager-send-mail":
                $this->ConfirmChangeManagerSendMail();
                break;
            case "validate-change-password":
                $this->ValidateChangePassword();
                break;
            case "send_multi_mail_hrm":
                $this->SendMultiMailHrm();
                break;
            case "send_multi_mail_OT_hrm":
                $this->SendMultiMailOTHrm();
                break;
            case "update-checkpoint":
                $this->UpdateCheckpoint();
                break;
            default:
                break;
        }
    }
    
    protected function UpdateCheckpoint()
    {
        $checkpointId= Input::get('checkpointId',0);
        if($checkpointId != 0){
            $i_stt = (Auth::user()->hr_type == 1 || Auth::user()->position_id == 1) ? 3 : 2; 
            $res = DB::table('checkpoint')->where('id',$checkpointId)->update(array('status' => $i_stt));
            if($res)
            {
                $arrayRes = array('success' => "Cập nhật dữ liệu thành công!",
                          'result' => 1
                );
            }
            else
            {
                $arrayRes = array('success' => "Không thể cập nhật dữ liệu!",
                               'result' => 0,
                );
            }
        }else{
            $arrayRes = array('success' => "Không có checkpoint Id!",
                               'result' => 0,
                );
        }
        echo json_encode($arrayRes);
        
    }
    /**
     * Auth: HuyNN
     * Des: Send Multi Email when HRM Confirm Multi Leave Request
     * Since: 26/04/2016
     */
    protected function SendMultiMailHrm()
    {
        set_time_limit(600);
        $a_Data = Input::get('a_Data');
        $sz_typeconfirm = $a_Data['sz_typeconfirm'];
        foreach ($a_Data['a_IdLeaveRequest'] as $i_key => $i_Id) 
        {
            unset($a_Email);
            $o_DataLeave = $this->o_LeaveRequestModel->GetOneLeaveRequestById($i_Id);
       
            //// HRM đồng ý thì send mail tới nhân viên và những ng liên quan////
            if($a_Data['sz_typeconfirm'] == 1)
            {
                if($o_DataLeave->inform_id != '')
                {
                    $a_InformId = explode(',', $o_DataLeave->inform_id);
                    $a_InformUsers = DB::table('users')->select('email')->whereIn('id', $a_InformId)->get();
                    foreach ($a_InformUsers as $o_val) {
                        $a_Email[] = $o_val->email;
                    }
                }
                $a_Email[] = $o_DataLeave->email;
            }
            ///HRM từ chối thì send mail tới nhân viên và quản lý trực tiếp///
            else 
            {
                /// Nếu ko phải trưởng phòng////
                if($o_DataLeave->manager_id != 0) 
                {
                    $o_hrm = DB::table('users')->select('department_id','email')->where('hr_type',1)->first();
                    //////Nếu nhân viên cùng phòng HRM///
                    if($o_DataLeave->department_id == $o_hrm->department_id)  $a_Email = array($o_DataLeave->email);
                    //////Nếu nhân viên khác phòng HRM///
                    else
                    {
                        $o_Manager = DB::table('users')->select('email')->where('id',$o_DataLeave->manager_id)->first();
                        $a_Email = array($o_Manager->email,$o_DataLeave->email);
                    }
                }
                /////////Nếu là trưởng phòng/////
                else $a_Email = array($o_DataLeave->email); 
            }

            $o_type = DB::table('leave_types')->select('name')->where(array('id' => $o_DataLeave->type_id))->first();
            $a_EmailBody = array(
            'type_confirm' => $sz_typeconfirm,
            'user_name' => $o_DataLeave->name,
            'user_code' => $o_DataLeave->code,
            'department' => $o_DataLeave->department_name,
            'leave_request_type' => $o_type->name,
            'from' => Util::sz_DateTimeFormat($o_DataLeave->from_time),
            'to' => Util::sz_DateTimeFormat($o_DataLeave->to_time),
            'user_comment' => $o_DataLeave->user_comment,
            'hrm_comment' => '',
            );
            if($o_DataLeave->numb_leave != 0)
            {
                $a_EmailBody['numb_leave'] = $o_DataLeave->numb_leave;
            }

            Mail::send('mail.hrm_send_mail',array('a_EmailBody' => $a_EmailBody), function($message) use ($a_Email)
            {
                ///Gửi email tới người duyệt đơn///
                $message->from('noreply@dxmb.vn', 'Hệ thống đăng ký vắng mặt DXMB');
                $message->to($a_Email);
                $message->subject('Xác nhận đơn vắng mặt từ Phòng nhân sự');
            });    
            
            if(Session::has('sendallmail')) Session::forget('sendallmail');  
        }
    }
    //SendMultiMailOTHrm
    /**
     * Auth: Dienct
     * Des: Send Multi Email when HRM Confirm Multi Leave Request
     * Since: 23/11/2016
     */
    protected function SendMultiMailOTHrm()
    {
        set_time_limit(600);
        $a_Data = Input::get('a_Data');
        $sz_typeconfirm = $a_Data['sz_typeconfirm'];
        foreach ($a_Data['a_IdOT'] as $i_key => $i_Id)
        {
            unset($a_Email);
            $a_field = array('id','code','user_id','email','name','type_ot','total_time','department_id','department_name','manager_id','manager_comment',
                'hrm_id','hrm_comment','from_time','to_time','user_comment');

            $o_dataOT = DB::table('over_time')->select($a_field)->where('id', $i_Id)->first();

            //// HRM đồng ý thì send mail tới nhân viên và những ng liên quan////
            if($a_Data['sz_typeconfirm'] == 1){
                $a_Email[] = $o_dataOT->email;
            }
            ///HRM từ chối thì send mail tới nhân viên và quản lý trực tiếp///
            else{
                /// Nếu ko phải trưởng phòng////
                if($o_dataOT->manager_id != 0){
                    $o_hrm = DB::table('users')->select('department_id','email')->where('hr_type',1)->first();
                    //////Nếu nhân viên cùng phòng HRM///
                    if($o_dataOT->department_id == $o_hrm->department_id)  $a_Email = array($o_dataOT->email);
                    //////Nếu nhân viên khác phòng HRM///
                    else
                    {
                        $o_Manager = DB::table('users')->select('email')->where('id',$o_dataOT->manager_id)->first();
                        $a_Email = array($o_Manager->email,$o_dataOT->email);
                    }
                }
                /////////Nếu là trưởng phòng/////
                else $a_Email = array($o_dataOT->email);
            }

            $a_EmailBody = array(
                'type_confirm' => $sz_typeconfirm,
                'user_name' => $o_dataOT->name,
                'user_code' => $o_dataOT->code,
                'department' => $o_dataOT->department_name,
                'leave_request_type' => $o_dataOT->type_ot == 1 ? 'làm thêm cộng phép nghỉ bù' : 'làm thêm tính tăng ca',
                'from' => Util::sz_DateTimeFormat($o_dataOT->from_time),
                'to' => Util::sz_DateTimeFormat($o_dataOT->to_time),
                'user_comment' => $o_dataOT->user_comment,
                'hrm_comment' => '',
                'total_time' => $o_dataOT->total_time,
            );


            Mail::send('mail.hrm_send_mail_ot',array('a_EmailBody' => $a_EmailBody), function($message) use ($a_Email)
            {
                ///Gửi email tới người duyệt đơn///
                $message->from('noreply@dxmb.vn', 'Hệ thống đăng ký vắng mặt DXMB');
                $message->to($a_Email);
                $message->subject('Xác nhận đơn vắng mặt từ Phòng nhân sự');
            });

            if(Session::has('sendMutiMailOT')) Session::forget('sendMutiMailOT');
        }
    }



    /**
     * Auth: HuyNN
     * Des: Confirm Request Change Manager
     * Since: 15/03/2016
     */
    protected function ConfirmChangeManager()
    {
        if($this->i_id == 0) exit;
        $sz_comment = Input::get('comment','');
        $i_type_confirm = Input::get('type_confirm');
        $i_status = $i_type_confirm == 0?2:$i_type_confirm;
        $o_RequestChange = DB::table('change_direct_manager')->select('user_id','old_department_id','new_department_id','new_manager_id','status')->where('id',(int)$this->i_id)->first();

        ///Nếu yêu cầu chưa được duyệt///
        if($o_RequestChange->status == 0)
        {
            $i_Check = 1;
            //////Nếu yêu cầu được đồng ý /////
            if($i_type_confirm == 1) 
            { 
                ///Nếu có sự thay đổi phòng ban//
                if($o_RequestChange->old_department_id != $o_RequestChange->new_department_id)
                {
                    $a_UsernameOldDepartment = array();
                    $a_UsernameNewDepartment = array();
                    $a_UpdateOldDepartment = array();
                    ////Nếu user có phòng ban cũ////
                    if($o_RequestChange->old_department_id != 0)
                    {
                        $o_OldDepartment= DB::table('departments')->select('guid','name')->where('id',$o_RequestChange->old_department_id)->first();
                        $a_UpdateOldDepartment['UserGroupID'] = $o_OldDepartment->guid;
                        $a_UpdateOldDepartment['UserGroupName'] = $o_OldDepartment->name;
                    
                        ///Mảng lưu toàn bộ user trong phòng ban cũ, bỏ đi user gửi yêu cầu thay đổi////
                        $a_UserOldDepartment = DB::table('users')->select('email')->where('department_id',$o_RequestChange->old_department_id)->where('id','!=',$o_RequestChange->user_id)->get();
                        if(count($a_UserOldDepartment) > 0)
                        {
                            foreach ($a_UserOldDepartment as $o_UserOldDepartment) 
                            {
                                $i_post = strpos($o_UserOldDepartment->email, '@dxmb.vn');
                                $sz_UserName = substr($o_UserOldDepartment->email, 0, $i_post);
                                $a_UsernameOldDepartment[] = $sz_UserName;
                            }
                            $a_UpdateOldDepartment['UserNames'] = $a_UsernameOldDepartment;
                        }                    
                    }
                    
                    $o_NewDepartment= DB::table('departments')->select('guid','name')->where('id',$o_RequestChange->new_department_id)->first();
                    $a_UpdateNewDepartment['UserGroupID'] = $o_NewDepartment->guid;
                    $a_UpdateNewDepartment['UserGroupName'] = $o_NewDepartment->name;
                    ///Mảng lưu toàn bộ user trong phòng ban mới + thêm user mới vừa thêm vào////
                    $a_UserNewDepartment = DB::table('users')->select('email')->where('department_id',$o_RequestChange->new_department_id)->orwhere('id',$o_RequestChange->user_id)->get();
                    
                    if(count($a_UserNewDepartment) > 0)
                    {
                        foreach ($a_UserNewDepartment as $o_UserNewDepartment) 
                        {
                            $i_post = strpos($o_UserNewDepartment->email, '@dxmb.vn');
                            $sz_UserName = substr($o_UserNewDepartment->email, 0, $i_post);
                            $a_UsernameNewDepartment[] = $sz_UserName;
                        }
                        $a_UpdateNewDepartment['UserNames'] = $a_UsernameNewDepartment;
                    }   

                    ////Thực hiện update group trên mail server với 2 mảng user bên trên///
                    $sz_mes = $this->_o_MailApi->a_fUpdateUserGroups($a_UpdateOldDepartment,$a_UpdateNewDepartment);
                    ////Nếu update trên mail server thành công thì thực hiện update bảng user với phòng ban mới và quản lý mới///
                    if($sz_mes == '')
                    {
                        $res = DB::table('users')->where('id',$o_RequestChange->user_id)->update(array('department_id' => $o_RequestChange->new_department_id,'direct_manager_id' => $o_RequestChange->new_manager_id));
                    }  
                }
                ////Nếu không có sự thay đổi phòng ban thì update bảng user với quản lý mới///
                else
                {
                    $res = DB::table('users')->where('id',$o_RequestChange->user_id)->update(array('direct_manager_id' => $o_RequestChange->new_manager_id));
                }
                
                $i_Check == isset($res) && $res?$i_Check:0;
            }

            ///Nếu không có lỗi gì xảy ra thì thực hiện update///
            if($i_Check == 1)
            {
                $res1 = DB::table('change_direct_manager')->where('id',(int)$this->i_id)->update(array('status' => $i_status, 'reporter_comment' => $sz_comment));
                if($res1)
                {
                    $arrayRes = array('success' => "Cập nhật dữ liệu thành công!",
                              'result' => 1
                    );
                }
                else
                {
                    $arrayRes = array('success' => "Không thể cập nhật dữ liệu!",
                                   'result' => 0,
                    );
                }
            }
            else
            {
                $arrayRes = array('success' => "Không thể cập nhật dữ liệu!",
                                   'result' => 0,
                    );
            }
        }
        else
        {
            $arrayRes = array('success' => "Yêu cầu này đã từng được duyệt hoặc bị từ chối! Không thể duyệt lại!",
                                'result' => 0,
                 );
        }
        echo json_encode($arrayRes);
    }
    /**
     * Auth: HuyNN
     * Des: Confirm Request Change Manager Send Mail
     * Since: 15/03/2016
     */
    protected function ConfirmChangeManagerSendMail()
    {
        if($this->i_id == 0) exit;
        $i_status = Input::get('type_confirm',0);
        $sz_comment = Input::get('comment','');
        $o_RequestChange = DB::table('change_direct_manager')->select('email','status')->where('id',(int)$this->i_id)->first();
        $a_EmailBody =  array(
            'status' => $i_status,
            'comment' => $sz_comment,
        );
        $sz_UserEmail = $o_RequestChange->email;
        Mail::send('mail.confirm_change_manager',array('a_EmailBody' => $a_EmailBody), function($message) use ($sz_UserEmail)
        {
            ///Gửi email tới User///
            $message->from('noreply@dxmb.vn', 'Xác nhận yêu cầu thay đổi Quản lý trực tiếp');
            $message->to($sz_UserEmail);
            $message->subject('Xác nhận yêu cầu thay đổi Quản lý trực tiếp từ phòng HCNS');
        });
    }
    
    /**
     * Auth: HuyNN
     * Des: Check duplicate field when onchange input
     * Since: 11/01/2015
     */
    protected function CheckDuplicateField()
    {
        $sz_old_val = Input::get('old_val');

        if($sz_old_val == '')
        {
            $i_check = DB::table($this->sz_tbl)->where($this->sz_field,$this->sz_val)->count();
        }
        else
        {
            $i_check = DB::table($this->sz_tbl)->where($this->sz_field,$this->sz_val)->count();
        }
        if($i_check > 0) $arrayRes = array('result' => 0);
        else $arrayRes = array('result' => 1);
        echo json_encode($arrayRes);
     }
    /**

     * Auth: DienCt
     * Edit: HuyNN 04/04/2016
     * Des: Delete record
     * Since: 31/12/2015
     */
    protected function DeleteRow(){
        
        if($this->i_id == 0 || $this->i_type == 0 || $this->sz_tbl == "") exit;
        if($this->i_type == 1){
            // update            
            $res = DB::table($this->sz_tbl)->where('id',(int)$this->i_id)->update(array('status' => 2));
            
        }else if($this->i_type == 2){
            $res = DB::table($this->sz_tbl)->where('id', '=', $this->i_id)->delete();
        }
        if($res){
            $arrayRes = array('success' => "Cập nhật dữ liệu thành công!",
                              'result' => 1 
                );
           
        }else{
            $arrayRes = array('success' => "Không thể cập nhật dữ liệu!",
                               'result' => 0,
                );
        }
        echo json_encode($arrayRes);       
    }
    
    /**
     * Auth: DienCt
     * Des: Recover record
     * Since: 31/12/2015
     */
    protected function RecoverRow(){

        if($this->i_id == 0 || $this->sz_tbl == "") exit;
        
            // update
            $res = DB::table($this->sz_tbl)->where('id',(int)$this->i_id)->update(array('status' => 1));
        
        if($res){
            $arrayRes = array('success' => "Cập nhật dữ liệu thành công!",
                              'result' => 1 
                );
        }else{
            $arrayRes = array('success' => "Không thể cập nhật dữ liệu!",
                               'result' => 0,
                );
        }
        echo json_encode($arrayRes);

    }

    /**
     * Auth: DienCt
     * Des: allow leave
     * Since: 7/1/2016
     */
    protected function AllowLeaveRequest(){

        if($this->i_id == 0) exit;
        $sz_comment = Input::get('comment','');
        $sz_typeconfirm = Input::get('type_confirm',0);
        if($sz_typeconfirm == 1){
            $i_status = 2;
        }else{
            $i_status = 3;
        }
            // update
        $res = DB::table('leave_requests')->where('id',(int)$this->i_id)->update(array('status' => $i_status,'hrm_comment'=>$sz_comment,'hrm_act_time'=> date('Y-m-d H:i:s',time())));
        if($res){
            $arrayRes = array('success' => "Cập nhật dữ liệu thành công!",
                              'result' => 1
                );
        }else{
            $arrayRes = array('success' => "Không thể cập nhật dữ liệu!",
                               'result' => 0,
                );
        }
        echo json_encode($arrayRes);

    }
    /**
     * Auth: DienCt
     * Des: approve OT
     * Since: 7/1/2016
     */
    protected function ApproveOT(){

        if($this->i_id == 0) exit;
        $sz_comment = Input::get('comment','');
        $sz_typeconfirm = Input::get('type_confirm',0);
        if($sz_typeconfirm == 1){
            $i_status = 2;
        }else{
            $i_status = 3;
        }
        // update
        $res = DB::table('over_time')->where('id',(int)$this->i_id)->update(array('status' => $i_status,'hrm_comment'=>$sz_comment,'hrm_act_time'=> date('Y-m-d H:i:s',time())));
        if($res){
            $arrayRes = array('success' => "Cập nhật dữ liệu thành công!",
                'result' => 1
            );
        }else{
            $arrayRes = array('success' => "Không thể cập nhật dữ liệu!",
                'result' => 0,
            );
        }
        echo json_encode($arrayRes);

    }
    /**
     * Auth: DienCt
     * Des: Manager allow leave request
     * Since: 8/1/2016
     */
    protected function ManagerAllowLeaveRequest(){
        //DB::enableQueryLog();
        if($this->i_id == 0) exit;
        $sz_comment = Input::get('comment','');
        $sz_typeconfirm = Input::get('type_confirm',0);
        if($sz_typeconfirm == 1){
            if(Auth::user()->hr_type == 1) $i_status = 2;
            else $i_status = 1;
        }else{
            $i_status = 3;
        }
            // update
        $res = DB::table('leave_requests')->where('id',(int)$this->i_id)->update(array('status' => $i_status,'manager_comment'=>$sz_comment,'manager_act_time'=>date('Y-m-d H:i:s',time())));
        if($res){
            $arrayRes = array('success' => "Cập nhật dữ liệu thành công!",
                              'result' => 1
                );
        }else{
            $arrayRes = array('success' => "Không thể cập nhật dữ liệu!",
                               'result' => 0,
                );
        }
        echo json_encode($arrayRes);

    }
    /**
     * Auth: DienCt
     * Des: Manager allow ot
     * Since: 8/1/2016
     */
    protected function ManagerAllowOT(){
        //DB::enableQueryLog();
        if($this->i_id == 0) exit;
        $sz_comment = Input::get('comment','');
        $sz_typeconfirm = Input::get('type_confirm',0);
        if($sz_typeconfirm == 1){
            if(Auth::user()->hr_type == 1) $i_status = 2;
            else $i_status = 1;
        }else{
            $i_status = 3;
        }
        // update
        $res = DB::table('over_time')->where('id',(int)$this->i_id)->update(array('status' => $i_status,'manager_comment'=>$sz_comment,'manager_act_time'=>date('Y-m-d H:i:s',time())));
        if($res){
            $arrayRes = array('success' => "Cập nhật dữ liệu thành công!",
                'result' => 1
            );
        }else{
            $arrayRes = array('success' => "Không thể cập nhật dữ liệu!",
                'result' => 0,
            );
        }
        echo json_encode($arrayRes);

    }
    /**
     * Auth: DienCt
     * Des: Send mail confirm leave request
     * Since: 7/1/2016
     */
    protected function SendMailHrm(){

        if($this->i_id == 0) exit;
        $sz_typeconfirm = Input::get('type_confirm',0);
        $sz_comment = Input::get('comment','');
        
        $o_DataLeave = $this->o_LeaveRequestModel->GetOneLeaveRequestById($this->i_id);
       
        //// HRM đồng ý thì send mail tới nhân viên và những ng liên quan////
        if($sz_typeconfirm == 1)
        {
            if($o_DataLeave->inform_id != '')
            {
                $a_InformId = explode(',', $o_DataLeave->inform_id);
                $a_InformUsers = DB::table('users')->select('email')->whereIn('id', $a_InformId)->get();
                foreach ($a_InformUsers as $o_val) {
                    $a_Email[] = $o_val->email;
                }
            }
            $a_Email[] = $o_DataLeave->email;
        }
        ///HRM từ chối thì send mail tới nhân viên và quản lý trực tiếp///
        else 
        {
            /// Nếu ko phải trưởng phòng////
            if($o_DataLeave->manager_id != 0) 
            {
                $o_hrm = DB::table('users')->select('department_id','email')->where('hr_type',1)->first();
                //////Nếu nhân viên cùng phòng HRM///
                if($o_DataLeave->department_id == $o_hrm->department_id)  $a_Email = array($o_DataLeave->email);
                //////Nếu nhân viên khác phòng HRM///
                else
                {
                    $o_Manager = DB::table('users')->select('email')->where('id',$o_DataLeave->manager_id)->first();
                    $a_Email = array($o_Manager->email,$o_DataLeave->email);
                }
            }
            /////////Nếu là trưởng phòng/////
            else $a_Email = array($o_DataLeave->email); 
        }

        $o_type = DB::table('leave_types')->select('name')->where(array('id' => $o_DataLeave->type_id))->first();
        $a_EmailBody = array(
        'type_confirm' => $sz_typeconfirm,
        'user_name' => $o_DataLeave->name,
        'user_code' => $o_DataLeave->code,
        'department' => $o_DataLeave->department_name,
        'leave_request_type' => $o_type->name,
        'from' => Util::sz_DateTimeFormat($o_DataLeave->from_time),
        'to' => Util::sz_DateTimeFormat($o_DataLeave->to_time),
        'user_comment' => $o_DataLeave->user_comment,
        'hrm_comment' => $sz_comment,
        );
        if($o_DataLeave->numb_leave != 0)
        {
            $a_EmailBody['numb_leave'] = $o_DataLeave->numb_leave;
        }
        
        Mail::send('mail.hrm_send_mail',array('a_EmailBody' => $a_EmailBody), function($message) use ($a_Email)
        {
            ///Gửi email tới người duyệt đơn///
            $message->from('noreply@dxmb.vn', 'Hệ thống đăng ký vắng mặt DXMB');
            $message->to($a_Email);
            $message->subject('Xác nhận đơn vắng mặt từ Phòng nhân sự');
        });
        
        $arrayRes = array('success' => "Cập nhật dữ liệu thành công!",
                        'result' => 1 
                );
        
        echo json_encode($arrayRes);

    }
    /**
     * Auth: DienCt
     * Des: Send mail confirm leave request
     * Since: 7/1/2016
     */
    protected function SendMaiOtHrm(){

        if($this->i_id == 0) exit;
        $sz_typeconfirm = Input::get('type_confirm',0);
        $sz_comment = Input::get('comment','');

        $o_DataLeave = $this->o_LeaveRequestModel->GetOneLeaveRequestById($this->i_id);
        $a_field = array('id','code','user_id','email','name','type_ot','total_time','department_id','department_name','manager_id','manager_comment',
            'hrm_id','hrm_comment','from_time','to_time','user_comment');

        $o_dataOT = DB::table('over_time')->select($a_field)->where('id', $this->i_id)->first();
        //// HRM đồng ý thì send mail tới nhân viên và những ng liên quan////
        if($sz_typeconfirm == 1)
        {
            $a_Email[] = $o_dataOT->email;
        }
        ///HRM từ chối thì send mail tới nhân viên và quản lý trực tiếp///
        else
        {
            /// Nếu ko phải trưởng phòng////
            if($o_dataOT->manager_id != 0)
            {
                $o_hrm = DB::table('users')->select('department_id','email')->where('hr_type',1)->first();
                //////Nếu nhân viên cùng phòng HRM///
                if($o_dataOT->department_id == $o_hrm->department_id)  $a_Email = array($o_dataOT->email);
                //////Nếu nhân viên khác phòng HRM///
                else
                {
                    $o_Manager = DB::table('users')->select('email')->where('id',$o_dataOT->manager_id)->first();
                    $a_Email = array($o_Manager->email,$o_dataOT->email);
                }
            }
            /////////Nếu là trưởng phòng/////
            else $a_Email = array($o_dataOT->email);
        }

        $o_type = DB::table('leave_types')->select('name')->where(array('id' => $o_DataLeave->type_id))->first();
        $a_EmailBody = array(
            'type_confirm' => $sz_typeconfirm,
            'user_name' => $o_dataOT->name,
            'user_code' => $o_dataOT->code,
            'department' => $o_dataOT->department_name,
            'leave_request_type' => $o_dataOT->type_ot == 1 ? 'làm thêm cộng phép nghỉ bù' : 'làm thêm tính tăng ca',
            'from' => Util::sz_DateTimeFormat($o_dataOT->from_time),
            'to' => Util::sz_DateTimeFormat($o_dataOT->to_time),
            'user_comment' => $o_dataOT->user_comment,
            'hrm_comment' => $sz_comment,
            'total_time' => $o_dataOT->total_time,

        );


        Mail::send('mail.hrm_send_mail_ot',array('a_EmailBody' => $a_EmailBody), function($message) use ($a_Email)
        {
            ///Gửi email tới người duyệt đơn///
            $message->from('noreply@dxmb.vn', 'Hệ thống đăng ký vắng mặt DXMB');
            $message->to($a_Email);
            $message->subject('Xác nhận đơn vắng mặt từ Phòng nhân sự');
        });

        $arrayRes = array('success' => "Cập nhật dữ liệu thành công!",
            'result' => 1
        );

        echo json_encode($arrayRes);

    }
    /**
     * Auth: DienCt
     * Edit: HuyNN
     * Des: manager Send mail confirm leave request
     * Since: 8/1/2016
     */
    protected function SendMailManager(){

        if($this->i_id == 0) exit;
        $sz_typeconfirm = Input::get('type_confirm',0);
        $sz_comment = Input::get('comment','');
         
        $o_DataLeave = $this->o_LeaveRequestModel->GetOneLeaveRequestById($this->i_id);
                
        $o_type = DB::table('leave_types')->select('name')->where(array('id' => $o_DataLeave->type_id))->first();
        $a_EmailBody = array(
        'type_confirm' => $sz_typeconfirm,
        'user_name' => $o_DataLeave->name,
        'department' => $o_DataLeave->department_name,
        'leave_request_type' => $o_type->name,
        'from' => Util::sz_DateTimeFormat($o_DataLeave->from_time),
        'to' => Util::sz_DateTimeFormat($o_DataLeave->to_time),
        'user_comment' => $o_DataLeave->user_comment,
        'manager_comment' => $sz_comment,    
        );  
        if($o_DataLeave->numb_leave != 0)
        {
            $a_EmailBody['numb_leave'] = $o_DataLeave->numb_leave;
        }
        if(Auth::user()->hr_type == 1){
            $a_IdUser = array();
            $a_IdUser = array_map('intval', explode(',', $o_DataLeave->inform_id));
            $a_IdUser = array_unique($a_IdUser);
            $a_Users = DB::table('users')->select('id', 'email')->whereIn('id', $a_IdUser)->get();
            
            $a_Email = array();
            if(count($a_Users) > 0){
                foreach ($a_Users as $o_val) {
                $a_Email[] = $o_val->email;
                }
                Mail::send('mail.send_mail_manager',array('a_EmailBody' => $a_EmailBody), function($message) use ($a_Email)
                {
                    ///Gửi email tới người duyệt đơn///
                    $message->from('noreply@dxmb.vn', 'Hệ thống đăng ký vắng mặt DXMB');
                    $message->to($a_Email);
                    $message->subject('Xác nhận đơn vắng mặt từ Quản lý');
                });
            }
        }
        
        $a_UsersStaff = DB::table('users')->select('id', 'email')->where('id', $o_DataLeave->user_id )->first();
        $sz_EmailStaff = $a_UsersStaff->email;
        if($sz_typeconfirm == 0){
            Mail::send('mail.send_mail_manager',array('a_EmailBody' => $a_EmailBody), function($message) use ($sz_EmailStaff)
            {
                ///Gửi email tới người duyệt đơn///
                $message->from('noreply@dxmb.vn', 'Hệ thống đăng ký vắng mặt DXMB');
                $message->to($sz_EmailStaff);
                $message->subject('Xác nhận đơn vắng mặt từ Quản lý');
            });
        }else{
            if(Auth::user()->hr_type == 1){
                Mail::send('mail.send_mail_manager',array('a_EmailBody' => $a_EmailBody), function($message) use ($sz_EmailStaff)
                {
                    ///Gửi email tới người duyệt đơn///
                    $message->from('noreply@dxmb.vn', 'Hệ thống đăng ký vắng mặt DXMB');
                    $message->to($sz_EmailStaff);
                    $message->subject('Xác nhận đơn vắng mặt từ Quản lý');
                });
            }else{
                $o_EmailHrm = DB::table('users')->select('id', 'email')->where('id', $o_DataLeave->hrm_id )->first();
                $sz_EmailHrm = $o_EmailHrm->email;
                $a_EmailBody['user_code'] = $o_DataLeave->code;
                $a_EmailBody['url'] = URL::to('/').'/hrm_management';
				
				$o_Token = DB::table('leave_requests')->select('token_key')->where('id', $this->i_id)->first();
                if(Util::b_fCheckObject($o_Token))
                {
                    $a_EmailBody['accept'] = URL::to('/').'/directly_approve?key='.$o_Token->token_key.'&user=hrm&stt=2';
                    $a_EmailBody['rejected'] = URL::to('/').'/directly_approve?key='.$o_Token->token_key.'&user=hrm&stt=3';
                }
                Mail::send('mail.send_mail_hrm',array('a_EmailBody' => $a_EmailBody), function($message) use ($sz_EmailHrm)
                {
                    ///Gửi email tới HRM///
                    $message->from('noreply@dxmb.vn', 'Hệ thống đăng ký vắng mặt DXMB');
                    $message->to($sz_EmailHrm);
                    $message->subject('Đơn đăng ký vắng mặt mới từ Nhân viên DXMB');
                });                 
            }
        }
        //send mail Hrm
        
        $arrayRes = array('success' => "Cập nhật dữ liệu thành công!",
                          'result' => 1);
        echo json_encode($arrayRes);

    }

    /**
     * Auth: DienCt
     * Des: Manager send mail when approve OT
     * Since: 8/1/2016
     */
    protected function SendMailManagerOT(){


        if($this->i_id == 0) exit;
        $sz_typeconfirm = Input::get('type_confirm',0);
        $sz_comment = Input::get('comment','');

        $a_field = array('id','code','user_id','email','name','type_ot','total_time','department_id','department_name','manager_id','manager_comment',
            'hrm_id','hrm_comment','from_time','to_time','user_comment');

        $o_dataOT = DB::table('over_time')->select($a_field)->where('id', $this->i_id)->first();

        $a_EmailBody = array(
            'type_confirm' => $sz_typeconfirm,
            'user_name' => $o_dataOT->name,
            'department' => $o_dataOT->department_name,
            'leave_request_type' => $o_dataOT->type_ot == 1 ? 'làm thêm cộng phép nghỉ bù' : 'làm thêm tính tăng ca',
            'from' => Util::sz_DateTimeFormat($o_dataOT->from_time),
            'to' => Util::sz_DateTimeFormat($o_dataOT->to_time),
            'user_comment' => $o_dataOT->user_comment,
            'manager_comment' => $sz_comment,
            'total_time' => $o_dataOT->total_time,
        );

        $a_UsersStaff = DB::table('users')->select('id', 'email')->where('id', $o_dataOT->user_id )->first();
        $sz_EmailStaff = $a_UsersStaff->email;
        if($sz_typeconfirm == 0){
            Mail::send('mail.send_mail_manager_ot',array('a_EmailBody' => $a_EmailBody), function($message) use ($sz_EmailStaff)
            {
                ///Gửi email tới người duyệt đơn///
                $message->from('noreply@dxmb.vn', 'Hệ thống đăng ký làm thêm giờ');
                $message->to($sz_EmailStaff);
                $message->subject('Xác nhận làm thêm giờ từ Quản lý');
            });
        }else{
            if(Auth::user()->hr_type == 1){
                Mail::send('mail.send_mail_manager',array('a_EmailBody' => $a_EmailBody), function($message) use ($sz_EmailStaff)
                {
                    ///Gửi email tới người duyệt đơn///
                    $message->from('noreply@dxmb.vn', 'Hệ thống đăng ký làm thêm giờ');
                    $message->to($sz_EmailStaff);
                    $message->subject('Xác nhận làm thêm giờ từ Quản lý');
                });
            }else{
                $o_EmailHrm = DB::table('users')->select('id', 'email')->where('id', $o_dataOT->hrm_id )->first();
                $sz_EmailHrm = $o_EmailHrm->email;

                $a_EmailBody['user_code'] = $o_dataOT->code;

                Mail::send('mail.send_mail_manager_ot',array('a_EmailBody' => $a_EmailBody), function($message) use ($sz_EmailHrm)
                {
                    ///Gửi email tới HRM///
                    $message->from('noreply@dxmb.vn', 'Hệ thống đăng ký làm thêm giờ');
                    $message->to($sz_EmailHrm);
                    $message->subject('Đơn đăng ký làm thêm giờ mới từ Nhân viên DXMB');
                });
            }
        }
        //send mail Hrm

        $arrayRes = array('success' => "Cập nhật dữ liệu thành công!",
            'result' => 1);
        echo json_encode($arrayRes);

    }

    /**
     * Auth: HuyNN
     * Des: Load Department Groups
     * Since: 05/01/2016
     */
    protected function Load_Department_Group()
    {
        $i_department = Input::get('i_department',0);
        $a_depart_groups = DB::table('groups')->select('id','name')->where('department_id',$i_department)->get();
        if (Util::b_fCheckArray($a_depart_groups)) {
            $arrayRes = array('result' => 1,'data' => $a_depart_groups); 
        }
        else $arrayRes = array('result' => 0); 
        echo json_encode($arrayRes);
    }
    
    /**
     * Auth: HuyNN
     * Des: Load Direct Manager
     * Since: 05/01/2016
     */
    protected function Load_Direct_Manager()
    {
        $i_group_manager = Input::get('i_group_manager',0);
        $a_user_manager = DB::table('users')->select('id','name')->where(array('department_id' => $i_group_manager, 'is_manager' => 1))->get();
        if (Util::b_fCheckArray($a_user_manager)) {
            $arrayRes = array('result' => 1,'data' => $a_user_manager); 
        }
        else $arrayRes = array('result' => 0); 
        echo json_encode($arrayRes);
    }
    
    protected function ValidateAddEditUser()
    { 
        $sz_Code = trim(Input::get('sz_code')); 
        $sz_Email = trim(Input::get('sz_email'));
        $i_HrmType = trim(Input::get('i_HrmType'));
        $i_check_hrmtype = DB::table('users')->where('hr_type',1)->count();
        if (strpos($sz_Email, '@dxmb.vn') == false) $sz_Email = trim($sz_Email).'@dxmb.vn';
        $i_user_id = trim(Input::get('i_user_id'));
        $mes = '';
        if($i_user_id == 0)
        {
            $i_check_code = DB::table('users')->where('code',$sz_Code)->count();  
            if($i_check_code > 0)
            {
                $mes = 'Đã tồn tại Mã nhân viên này! Vui lòng kiểm tra lại';
                $sz_IdFocus = 'code';
            }     
            else
            {
                $i_check_email = DB::table('users')->where('email',$sz_Email)->count();
                if($i_check_email > 0)
                {
                    $mes = 'Đã tồn tại Email này! Vui lòng kiểm tra lại!';
                    $sz_IdFocus = 'email';
                }
                elseif($i_check_hrmtype == 1 && $i_HrmType == 1)
                {
                    $mes = 'Hrm đã tồn tại! Vui lòng kiểm tra lại!';
                    $sz_IdFocus = 'hr_type';
                }
            }
        }
        else
        {
            $o_user = DB::table('users')->select('hr_type','email','code')->where('id',$i_user_id)->first();
            if(Util::b_fCheckObject($o_user))
            {
                if($o_user->code != $sz_Code)
                {
                    $i_check_code = DB::table('users')->where('code',$sz_Code)->count();
                    if($i_check_code > 0)
                    {
                        $sz_IdFocus = 'code';
                        $mes = 'Đã tồn tại Mã nhân viên này! Vui lòng kiểm tra lại';
                    }
                }  
                else if($o_user->email != $sz_Email) 
                {
                    $i_check_email = DB::table('users')->where('email',$sz_Email)->count();
                    if($i_check_email > 0)   
                    {
                        $sz_IdFocus = 'email';
                        $mes = 'Đã tồn tại Email này! Vui lòng kiểm tra lại';
                    }
                }
                elseif($i_check_hrmtype == 1 && $i_HrmType == 1)
                {
                    if($o_user->hr_type != 1)
                    {
                        $mes = 'Đã có Hrm! Vui lòng kiểm tra lại!';
                        $sz_IdFocus = 'hr_type';
                    }
                }
            }
        }
        
        if($mes != '') $arrayRes = array('result' => 0,'mes' => $mes,'idFocus' => $sz_IdFocus); 
        else $arrayRes = array('result' => 1);
        
        echo json_encode($arrayRes);
    }
    
    /**
     * Auth: HuyNN
     * Des: Load To Time
     * Since: 13/01/2016
    */
    protected function GetToTime()
    {
        $arrayRes = array();
        $o_department = DB::table('departments')->select('name','numb_of_work','time_start','time_end')->where('id',Auth::user()->department_id)->first();
        $sz_grub = Input::get('sz_grub','');

        $sz_FromTime = Input::get('sz_FromTime'); 
        $f_numb_leave = Input::get('f_numb_leave',0);
        $i_NumbLeaveToTime = 86400 * $f_numb_leave; // Đổi số ngày đăng ký nghỉ sang đơn vị int
        $i_FromTime = strtotime(str_replace('/','-',$sz_FromTime).' '.$sz_grub); // Đổi ngày bắt đầu nghỉ sang int
        $i_ToTime = $i_FromTime + $i_NumbLeaveToTime; // Tính ra ngày kết thúc nghỉ đổi sang int

        $sz_CheckDayFrom = date( "l", $i_FromTime); // Kiểm tra ngày bắt đầu nghỉ có phải thứ 7 ko để hide select grub
        $sz_CheckDayTo = date( "l", $i_ToTime); // Kiểm tra ngày kết thúc nghỉ 
        //echo $sz_check_day
        $i_check_hours = date('H',$i_ToTime); // Lấy giờ của ngày kết thúc nghỉ
        if(Util::b_fCheckObject($o_department))
        {
            switch ($o_department->numb_of_work) 
            {
                case 5.5: // Nếu phòng ban làm việc 5,5 ngày 1 tuần
                    // Nếu phủ định của (ngày kết thúc nghỉ vào t7 và giờ < 12 ==> vào sáng t7)
                    if(!($sz_CheckDayTo == 'Saturday' && $i_check_hours < 12))
                    {
                        $i_Saturdays = Util::i_fNumberOfDays($i_FromTime,$i_ToTime,'Saturday'); // Tính số ngày thứ 7 trong khoảng thời gian nghỉ
                        //Vì dc nghỉ chiều t7 và cn nên ngày kết thúc nghỉ sẽ phải cộng thêm 1,5 ngày
                        if($i_Saturdays > 0)
                        {
                            $i_ToTime += $i_Saturdays * (86400 + 43200) ;
                        }
                    }
                    break;

                default: // Mặc định là làm việc 5 ngày 1 tuần 
                    $i_Saturdays = Util::i_fNumberOfDays($i_FromTime,$i_ToTime,'Saturday'); // Tính số ngày thứ 7

                    //Vì dc nghỉ t7 và cn nên ngày kết thúc nghỉ sẽ phải cộng thêm 2 ngày
                    if($i_Saturdays > 0)
                    {
                        $i_ToTime += $i_Saturdays * 2*86400 ;
                    }
                    break;
            }
        }

        $i_check_hours = date('H',$i_ToTime); // Lấy số giờ của ngày kết thúc nghỉ
        $sz_grub_end = $i_check_hours < 12?'Sáng':'Chiều'; 
        $sz_to_time = date('d/m/Y',$i_ToTime); // Đổi ngày kết thúc sang kiểu date để hiển thị ô input ra ngoài 
        $sz_to_time_db = date('Y-m-d H:i:s',$i_ToTime); // Đổi ngày kết thúc sang kiểu date để cho vào input hidden và lưu db
        $a_res = array('sz_grub_end' => $sz_grub_end, 'sz_to_time' => $sz_to_time, 'sz_to_time_db' => $sz_to_time_db,'sz_CheckDayFrom' => $sz_CheckDayFrom,'sz_CheckDayTo' => $sz_CheckDayTo);
        $arrayRes = array('result' => 1,'a_res' => $a_res);

        echo json_encode($arrayRes);
    }
    
    /**
     * Auth: HuyNN
     * Des: Check Duplicate when submit form Leave Request
     * Since: 14/01/2016
    */
    protected function CheckDuplicateLeaveRequest()
    {
        $arrayRes['result'] = 1;
        $sz_grub = Input::get('sz_grub','');
        if($sz_grub != '')
        {
            $sz_FromTime = Input::get('sz_FromTime'); 
            $f_numb_leave = Input::get('f_NumbLeave',0);
            $i_NumbLeaveToTime = 86400 * $f_numb_leave; // Đổi số ngày đăng ký nghỉ sang đơn vị int
            $i_FromTime = strtotime(str_replace('/','-',$sz_FromTime).' '.$sz_grub); // Đổi ngày bắt đầu nghỉ sang int
            $i_ToTime = $i_FromTime + $i_NumbLeaveToTime; // Tính ra ngày kết thúc nghỉ đổi sang int
        }
        else
        {
            $sz_FromTimeBusiness = Input::get('sz_FromTimeBusiness',''); 
            $sz_ToTimeBusiness = Input::get('sz_ToTimeBusiness','');
            $i_FromTime = strtotime(str_replace('/','-',$sz_FromTimeBusiness));
            $i_ToTime = strtotime(str_replace('/','-',$sz_ToTimeBusiness));
            //echo $i_FromTime.'-'.$i_ToTime;die;
        }

        $a_UserLeave = DB::table('leave_requests')->select('from_time','to_time')->whereIn('status',array(0,1,2))->where('user_id',Auth::user()->id)->get();
        //print_r($a_UserLeave);die;
        foreach ($a_UserLeave as $o_Time) 
        {
            $i_DbFromTime = strtotime($o_Time->from_time);
            $i_DbToTime = strtotime($o_Time->to_time);
          
            if(($i_DbFromTime <= $i_FromTime && $i_FromTime < $i_DbToTime) || ($i_DbFromTime < $i_ToTime && $i_ToTime <= $i_DbToTime) || ($i_FromTime < $i_DbFromTime && $i_DbToTime < $i_ToTime))
            {
                $arrayRes['result'] = 0;
                $arrayRes['mes'] = 'Bạn đã có một đơn nghỉ phép trùng lặp thời gian!';
                break;
            }
        }
        if($sz_grub == '' && $arrayRes['result'] == 1)
        {
            $i_HoursFromTimeBusiness = date('H',$i_FromTime);
            $i_HoursToTimeBusiness = date('H',$i_ToTime);
            $o_Department = DB::table('departments')->select('time_start','time_end')->where('id',Auth::user()->department_id)->first();
            $i_TimeStartValidate = date('H',strtotime($o_Department->time_start));
            $i_TimeEndValidate = date('H',strtotime($o_Department->time_end));

            if((($i_TimeStartValidate <= $i_HoursFromTimeBusiness && $i_HoursFromTimeBusiness <= $i_TimeEndValidate) && ($i_TimeStartValidate <= $i_HoursToTimeBusiness && $i_HoursToTimeBusiness <= $i_TimeEndValidate)))
            {
                if($i_FromTime >= $i_ToTime) 
                {
                    $arrayRes['result'] = 0;
                    $arrayRes['mes'] = 'Thời gian kết thúc phải lớn hơn thời gian bắt đầu công tác';
                }
            }
            else
            {
                $arrayRes['result'] = 0;
                $arrayRes['mes'] = $i_TimeEndValidate == 17?'Bạn phải chọn thời gian công tác từ 8:00 tới 17:30':'Bạn phải chọn thời gian công tác từ 8:00 tới 18:00';
            }
        }
        
        echo json_encode($arrayRes);
    }


    protected function CheckDuplicateOT(){
        $arrayRes['result'] = 1;

        $sz_FromTimeBusiness = Input::get('sz_FromTimeBusiness','');
        $sz_ToTimeBusiness = Input::get('sz_ToTimeBusiness','');

        $a_timeFrom = explode(" ",$sz_FromTimeBusiness);
        $a_timeTo = explode(" ",$sz_ToTimeBusiness);
        if($a_timeFrom[0] != $a_timeTo[0]){
            $arrayRes['result'] = 0;
            $arrayRes['mes'] = 'Bạn chỉ có thẻ đăng ký trong một cùng một ngày!';
        }
        
        $i_FromTime = strtotime(str_replace('/','-',$sz_FromTimeBusiness));
        $i_ToTime = strtotime(str_replace('/','-',$sz_ToTimeBusiness));

        $a_UserOT = DB::table('over_time')->select('from_time','to_time')->whereIn('status',array(0,1,2))->where('user_id',Auth::user()->id)->get();
        //print_r($a_UserLeave);die;
        if(count($a_UserOT) > 0){
            foreach ($a_UserOT as $o_Time){
                $i_DbFromTime = strtotime($o_Time->from_time);
                $i_DbToTime = strtotime($o_Time->to_time);

                if(($i_DbFromTime <= $i_FromTime && $i_FromTime < $i_DbToTime) || ($i_DbFromTime < $i_ToTime && $i_ToTime <= $i_DbToTime) || ($i_FromTime < $i_DbFromTime && $i_DbToTime < $i_ToTime))
                {
                    $arrayRes['result'] = 0;
                    $arrayRes['mes'] = 'Bạn đã có một đơn nghỉ phép trùng lặp thời gian!';
                    break;
                }
            }
        }

        echo json_encode($arrayRes);

    }
    
    /**
     * Auth: Dienct
     * Des: delete my request and send mail
     * Since: 30/01/2016
    */
    protected function DeleteMyLeaveRequest()
    {
        $o_LeaveRequest = DB::table('leave_requests')->select('id','user_id','manager_id','hrm_id','numb_leave','user_comment','name','code','department_name','status','type_id','from_time','to_time')->where('id',$this->i_id)->first();
        $o_type = DB::table('leave_types')->select('name')->where(array('id' => $o_LeaveRequest->type_id))->first();
        
        $a_EmailBody = array(
            'user_name' => $o_LeaveRequest->name,
            'user_code' => $o_LeaveRequest->code,
            'department' => $o_LeaveRequest->department_name,
            'leave_request_type' => $o_type->name,
            'from' => Util::sz_DateTimeFormat($o_LeaveRequest->from_time),
            'to' => Util::sz_DateTimeFormat($o_LeaveRequest->to_time),
            'user_comment' => $o_LeaveRequest->user_comment,
        );
        if($o_LeaveRequest->numb_leave != 0){
            $a_EmailBody['numb_leave'] = $o_LeaveRequest->numb_leave;
        }
        //
        $a_IdUser = array();
        if($o_LeaveRequest->manager_id != 0) $a_IdUser[] = $o_LeaveRequest->manager_id ; // manager_ID
        else $a_IdUser[] = $o_LeaveRequest->hrm_id ; // HRM_ID
        
        $a_IdUser[] = $o_LeaveRequest->user_id; // user request
        $a_IdUser = array_unique($a_IdUser);
        
        $a_Users = DB::table('users')->select('id', 'email')
                        ->whereIn('id', $a_IdUser)->get();
        $a_Email = array();
        foreach ($a_Users as $o_val) {
            $a_Email[] = $o_val->email;
        }
        ///Check for Leave Request can deleted/////
        $i_CheckDelete = time() < strtotime($o_LeaveRequest->from_time)? 1 : 0;
        if(($o_LeaveRequest->status == 0 || $i_CheckDelete == 1)&& Auth::user()->id == $o_LeaveRequest->user_id){
            DB::table('leave_requests')->where('id', '=', $this->i_id)->update(array('status' => 4));
            Mail::send('mail.del_my_request',array('a_EmailBody' => $a_EmailBody), function($message) use ($a_Email)
            {
                ///Gửi email tới người duyệt đơn///
                $message->from('noreply@dxmb.vn', 'Hệ thống đăng ký vắng mặt DXMB');
                $message->to($a_Email);
                $message->subject('Hủy bỏ đơn xin vắng mặt');
            });
            $arrayRes = array('mes' => "Cập nhật dữ liệu thành công!",
                          'result' => 1);
        }
        else $arrayRes = array('mes' => "Đơn này không thể xóa!",
                          '0' => 1);
        
        echo json_encode($arrayRes);
    }
    /**
     * Auth: HuyNN
     * Des: Manager delete leave request
     * Since: 02/02/2016
    */
    protected function ManagerDelLeaveRequest()
    {
        $Id_LeaveRequest = Input::get('id',0);
        $o_LeaveRequest = DB::table('leave_requests')->select('id','user_id','status','manager_id','email','name','department_name','user_comment','numb_leave','type_id','from_time','to_time')->where('id',$Id_LeaveRequest)->first();   
        //print_r($o_LeaveRequest);die;
        if($o_LeaveRequest->manager_id == Auth::user()->id && $o_LeaveRequest->status == 1)
        {
            DB::table('leave_requests')->where('id',$Id_LeaveRequest)->update(array('status' => 4));
            
            $o_type = DB::table('leave_types')->select('name')->where(array('id' => $o_LeaveRequest->type_id))->first();
            $a_EmailBody = array(
            'user_name' => $o_LeaveRequest->name,
            'department' => $o_LeaveRequest->department_name,
            'leave_request_type' => $o_type->name,
            'from' => Util::sz_DateTimeFormat($o_LeaveRequest->from_time),
            'to' => Util::sz_DateTimeFormat($o_LeaveRequest->to_time),
            'user_comment' => $o_LeaveRequest->user_comment,  
            );  
            if($o_LeaveRequest->numb_leave != 0)
            {
                $a_EmailBody['numb_leave'] = $o_LeaveRequest->numb_leave;
            }
            $sz_Mailsend = $o_LeaveRequest->email;
            Mail::send('mail.manager_delete',array('a_EmailBody' => $a_EmailBody), function($message) use ($sz_Mailsend)
            {
                ///Gửi email tới người duyệt đơn///
                $message->from('noreply@dxmb.vn', 'Hệ thống đăng ký vắng mặt DXMB');
                $message->to($sz_Mailsend);
                $message->subject('Hủy bỏ đơn vắng mặt');
            });
            
            $arrayRes['result'] = 1;
            $arrayRes['mes'] = 'Xóa đơn vắng mặt thành công!';
        }
        else
        {
            $arrayRes['result'] = 0;
            $arrayRes['mes'] = 'Bạn không thể xóa được đơn này!';
        }
        echo json_encode($arrayRes);
    }
    
     /**
     * Auth: Vit
     * Des: Update Status
     * Since: 03/02/2016
     */
    protected function UpdateStatus(){
        
        if($this->i_id == 0 || $this->i_type == 0 || $this->sz_tbl == "") exit;
        if($this->i_type == 4){
            //update status = 4
           $res = DB::table($this->sz_tbl)->where('id',(int)$this->i_id)->update(array('status' => 4));
        }
        if($res){
            $arrayRes = array('success' => "Cập nhật dữ liệu thành công!",
                              'result' => 1 
                );
           
        }else{
            $arrayRes = array('success' => "Không thể cập nhật dữ liệu!",
                               'result' => 0,
                );
        }
        echo json_encode($arrayRes);       
    }
    
    /**
     * Auth: Huy
     * Des: Update Status Leave Request Report
     * Since: 16/02/2016
     */
    protected function UpdateSttLeaveRequestReport()
    {
        $i_UserId = Input::get('user_id',0);
        $i_Month = Input::get('month',0);
        $i_Year = Input::get('year',0);
        
        $i_count = DB::table($this->sz_tbl)->where(array('user_id' => $i_UserId, 'month' => $i_Month, 'year' => $i_Year))->count();  
        if($i_count == 0)
        {
            $res = DB::table($this->sz_tbl)->insert(array('user_id' => $i_UserId, 'month' => $i_Month, 'year' => $i_Year, $this->sz_field => $this->sz_val));
        }
        else
        {
            $res = DB::table($this->sz_tbl)->where(array('user_id' => $i_UserId, 'month' => $i_Month, 'year' => $i_Year))->update(array($this->sz_field => $this->sz_val));
        }
          
        if($res){
            $arrayRes = array('success' => "Cập nhật dữ liệu thành công!",
                              'result' => 1 
                );
           
        }else{
            $arrayRes = array('success' => "Không thể cập nhật dữ liệu!",
                               'result' => 0,
                );
        }
        echo json_encode($arrayRes);       
    }
    /**
     * Auth: Huy
     * Des: Validate Change Password
     * Since: 11/04/2016
     */
    protected function ValidateChangePassword()
    {
        $sz_old_pass = Input::get('sz_old_pass','');
        if (!Hash::check($sz_old_pass, Auth::user()->password))
        {
            $mes = 'Mật khẩu hiện tại chưa chính xác!';
            $sz_IdFocus = 'old_password';
            $arrayRes = array('result' => 0,'mes' => $mes,'idFocus' => $sz_IdFocus);
        }
        else $arrayRes = array('result' => 1);
        echo json_encode($arrayRes);       
    }
    
}
