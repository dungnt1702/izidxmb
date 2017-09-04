<?php

namespace App\Http\Controllers\OverTime;

use DB;
use Auth;
use Illuminate\Http\Request as o_request;
use App\Http\Controllers\Controller;
use App\Util;
use App\Models\LeaveRequest as request_model;
use App\Models\Users as user_model;
use App\Models\Department as o_department_model;
use Illuminate\Support\Facades\Mail;
use App\Models\OverTime;
use Illuminate\Support\Facades\URL;
use DateTime;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Roles as o_RoleModel;
class OverTimeController extends Controller
{
    private $o_leave_request;
    private $o_users;
    private $o_department;
    private $o_overtime;

    public function __construct() {

        $o_Role = new o_RoleModel();
        $o_Role->b_fCheckRole();

        $this->o_leave_request = new request_model();
        $this->o_overtime = new OverTime();
        $this->o_users = new user_model();
        $this->o_department = new o_department_model();
    }

    public function addOverTime(o_request $o_resquest){

        $o_department = DB::table('departments')->select('name','numb_of_work','time_start','time_end')->where('id',Auth::user()->department_id)->first();
        $Data_view['o_department'] = $o_department;

        $o_position = DB::table('positions')->select('name')->where('id',Auth::user()->position_id)->first();
        $Data_view['o_position'] = $o_position;

        $Data_view['o_direct_manager'] = DB::table('users')->select('name')->where('id',Auth::user()->direct_manager_id)->first();
        $Data_view['o_group'] = DB::table('groups')->select('name')->where('id',Auth::user()->group_id)->first();

        $o_job = DB::table('jobs')->select('id','name','enable_sunday')->where('id',Auth::user()->job_id)->first();
        $Data_view['o_job'] = $o_job;

        $o_hrm = DB::table('users')->select('id','name','email','department_id','direct_manager_id')->where('hr_type',1)->first();
        $Data_view['o_hrm'] = $o_hrm;


        /// Nếu user là nhân viên thường thì lấy ra các quản lý thuộc cùng phòng ban trừ người quản lý trực tiếp
        if(Auth::user()->is_manager == 0){
            ///////Nếu user thuộc phòng HCNS////////
            if(Auth::user()->department_id == $o_hrm->department_id){
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
            else{
               $a_manager = DB::table('users')->select('id','name','direct_manager_id')->where(array('department_id' => Auth::user()->department_id, 'is_manager' => 1))->where('id','!=',Auth::user()->direct_manager_id)->get();
            }
        }else{///Nếu user là cấp quản lý load các quản lý khác thuộc cùng phòng ban/////
                /// Nếu user cùng phòng HRM///
                if(Auth::user()->department_id == $o_hrm->department_id){
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

        if($o_resquest->submit){
            $a_data = $o_resquest->data;

            /// Nếu là nghỉ công tác/////
            $sz_FromTime = $a_data['from_time_business']; // Thời gian bắt đầu nghỉ cho vào email body
            $i_FromTime_Bsn = strtotime(str_replace('/','-',$a_data['from_time_business']));
            $a_data['from_time'] = date('Y-m-d H:i:s',$i_FromTime_Bsn); // Ngày bắt đầu nghỉ công tác để insert db
            unset($a_data['from_time_business']);

            $sz_ToTime = $a_data['to_time_business']; // Thời gian kết thúc nghỉ cho vào email body
            $i_ToTime_Bsn = strtotime(str_replace('/','-',$a_data['to_time_business']));
            $a_data['to_time'] = date('Y-m-d H:i:s',$i_ToTime_Bsn); // Ngày bắt đầu nghỉ công tác để insert db
            unset($a_data['to_time_business']);

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
            $a_data['hrm_id'] = $o_hrm->id;
            $a_data['manager_id'] = ($a_data['manager_id']!= $o_hrm->id)?$a_data['manager_id']:(Auth::user()->department_id == $o_hrm->department_id?$o_hrm->id:0);
            $a_data['created_at'] = date('Y-m-d H:i:s',time());

            //$a_data['status'] = Auth::user()->department_id == $o_hrm->department_id?1:0;
            $i_NewOT = DB::table('over_time')->insertGetId($a_data); //// Insert dữ liệu

            $a_EmailBody = array(
                'user_code' => Auth::user()->code,
                'user_name' => Auth::user()->name,
                'position' => $Data_view['o_position']->name,
                'department' => $Data_view['o_department']->name,
                'from' => $sz_FromTime,
                'to' => $sz_ToTime,
                'type' => $a_data['type_ot'] == 1 ? 'làm thêm cộng phép nghỉ bù' : 'làm thêm tính tăng ca',
                'total_time' => $a_data['total_time'],
                'user_comment' => $a_data['user_comment'],
            );

            ///Nếu ng gửi là TP hoặc ng gửi cùng phòng với HRM///
            if($a_data['manager_id'] == 0 || Auth::user()->department_id == $o_hrm->department_id)
            {
                ///Nếu người gửi là HRM-> send email tới Reporter///
                if(Auth::user()->hr_type == 1){
                    $sz_ToEmail = $this->o_users->sz_GetReporter();
                }else{

                    $sz_ToEmail = $o_hrm->email;
                }
            }else{/////Nếu ng gui là Nhân viên bt thì gửi cho ng quản lý trực tiếp/////
                $o_Manager = DB::table('users')->select('email')->where(array('id' => $a_data['manager_id']))->first();
                $sz_ToEmail = $o_Manager->email;
                //$a_EmailBody['url'] = URL::to('/').'/leave_management';
            }
            Mail::send('mail.add_overtime',array('a_EmailBody' => $a_EmailBody), function($message) use ($sz_ToEmail){
                //Gửi email tới người duyệt đơn//
                $message->from('noreply@dxmb.vn', 'Hệ thống đăng ký làm thêm giờ');
                $message->to($sz_ToEmail);
                $message->subject('Đơn đăng ký làm thêm giờ mới từ Nhân viên DXMB');
            });//End Thực hiện gửi Email//

            return redirect('add_over_myself')->with('status', 'Bạn đăng ký vắng mặt thành công!');
        }
        return view('over_time.add_overtime',$Data_view);

    }
    /**
     * auth: Dienct
     * Des: manager approve overtime
     * Since: 4/1/2016
     */
    public function ManagementApproveOT(){
        $i_userId = Auth::user()->id;

        //get leave request manager
        $a_result = $this->o_overtime->GetOTManagement($i_userId);

        $Data_view['a_data'] = $a_result['a_data'];
        $Data_view['a_search'] = $a_result['a_search'];
        return view('over_time.manager_approve',$Data_view);
    }

    public function HRMApproveOT(o_request $o_resquest)
    {
        ////Nếu có thực hiện Check các đơn để duyệt nhiều////
        if($o_resquest->check){
            $a_Check = $o_resquest->check;
            $i_UpdateStt = $o_resquest->allow_all? 2 : 3;


            ///Update DB///
            foreach ($a_Check as $i_IdOT){
                DB::table('over_time')->where('id',$i_IdOT)->update(['status'=> $i_UpdateStt]);
            }
            ////Tạo một Session lưu thông tin tất cả những đơn dc duyệt nhiều để tiến hành gửi Email///
            session(
                ['sendMutiMailOT' => [
                    'a_IdOT' => $a_Check,
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

        $a_result = $this->o_overtime->GetAllOTHRM($i_userId);


        $a_LeaveType = DB::table('leave_types')->select('id','name')->get();
        foreach($a_LeaveType as $o_LeaveType){
            $a_LeaveTypes[$o_LeaveType->id] = $o_LeaveType->name;
        }

        $Data_view['a_LeaveTypes'] = $a_LeaveTypes;
        $Data_view['a_data'] = $a_result['a_data'];
        $Data_view['a_search'] = $a_result['a_search'];

        return view('over_time.hrm_approve',$Data_view);
    }

/*
* @auth: Dienct
* @since: 24/11/2016
* @des: list over time of myself
* **/

    public function MyselfOT(){
        $a_data = $this->o_overtime->GetAllOverTimeByUserId();

        return view('over_time.list_myself',['a_leaveRequest' => $a_data]);
    }

    /*
     * @auth: Dienct
     * @des: insert or update data table over_time_report
     * @since: 24/11/2016
     * **/
    public function ConvertOtReport(){

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
        $a_data = $this->o_overtime->getAllUsersOverTime($current_month,$current_year);

        //insert or update data
        if(isset($a_data) && count($a_data) > 0){
            $sz_code = "";
            $a_DataTimeSheet = array();
            $last_key = key(array_slice($a_data, -1, 1, TRUE));

            $sumNBNT = $sumNBCT = $sumTCNT = $sumTCCT = 0;

            foreach ($a_data as $key => $a_val){
                if ($sz_code == "") $sz_code = trim($a_val->code);

                $sz_Datetime = trim($a_val->from_time);
                $i_Date = strtotime($sz_Datetime);
                $sz_GetdayFromDate = date( "l", $i_Date);
                $datetime = new DateTime($sz_Datetime);
                $sz_Date = $datetime->format('d');

                if ($sz_code == trim($a_val->code)) {
                    if ($key == $last_key){
                        $a_DataTimeSheet[$sz_Date] = $a_val->total_time;
                        $a_DataTimeSheet['code'] = $a_val->code;
                        $a_DataTimeSheet['name'] = $a_val->name;
                        $a_DataTimeSheet['month'] = $current_month;
                        $a_DataTimeSheet['year'] = $current_year;
                        $a_DataTimeSheet['department_id'] = $a_val->department_id;
                        $a_DataTimeSheet['department_name'] = $a_val->department_name;
                        if($sz_GetdayFromDate == 'Saturday' || $sz_GetdayFromDate == 'Sunday'){
                            if($a_val->type_ot == 1){
                                $sumNBCT += (float)$a_val->total_time;
                            }else{
                                $sumTCCT += (float)$a_val->total_time;
                            }
                        }else{
                            if($a_val->type_ot == 1){
                                $sumNBNT += (float)$a_val->total_time;
                            }else{
                                $sumTCNT += (float)$a_val->total_time;
                            }
                        }
                        $a_DataTimeSheet['sum_NBNT'] = $sumNBNT;
                        $a_DataTimeSheet['sum_NBCT'] = $sumNBCT;
                        $a_DataTimeSheet['sum_TCNT'] = $sumTCNT;
                        $a_DataTimeSheet['sum_TCCT'] = $sumTCCT;
                        $a_DataAll[] = $a_DataTimeSheet;
                    }
                }else {
                    $a_DataAll[] = $a_DataTimeSheet;
                    unset($a_DataTimeSheet);
                    $sumNBNT = $sumNBCT = $sumTCNT = $sumTCCT = 0;
                    $sz_code = $a_val->code;
                }
                $a_DataTimeSheet[$sz_Date] = $a_val->total_time;
                $a_DataTimeSheet['code'] = $a_val->code;
                $a_DataTimeSheet['name'] = $a_val->name;
                $a_DataTimeSheet['month'] = $current_month;
                $a_DataTimeSheet['year'] = $current_year;
                $a_DataTimeSheet['department_id'] = $a_val->department_id;
                $a_DataTimeSheet['department_name'] = $a_val->department_name;

                if($sz_GetdayFromDate == 'Saturday' || $sz_GetdayFromDate == 'Sunday'){
                    if($a_val->type_ot == 1){
                        $sumNBCT += (float)$a_val->total_time;
                    }else{
                        $sumTCCT += (float)$a_val->total_time;
                    }
                }else{
                    if($a_val->type_ot == 1){
                        $sumNBNT += (float)$a_val->total_time;
                    }else{
                        $sumTCNT += (float)$a_val->total_time;
                    }
                }
                $a_DataTimeSheet['sum_NBNT'] = $sumNBNT;
                $a_DataTimeSheet['sum_NBCT'] = $sumNBCT;
                $a_DataTimeSheet['sum_TCNT'] = $sumTCNT;
                $a_DataTimeSheet['sum_TCCT'] = $sumTCCT;
            }
        }
        // Delete over_time report by month and year and insert
        $i_InsertSuccessfully = 0;
        $i_InsertFail = 0;
        
        if(isset($a_DataAll) && count($a_DataAll) > 0){
                DB::table('over_time_report')->where('month', $current_month)->where('year', $current_year)->delete();

                foreach ($a_DataAll as $keyOT => $valOTByUser){
                    if (DB::table('over_time_report')->insert($valOTByUser)) {
                        $i_InsertSuccessfully++;
                    } else {
                        $i_InsertFail++;
                    }
                }
        }
        echo "insert $i_InsertSuccessfully overtime (s) successfully! And $i_InsertFail failed!";

    }

    //list overtime
    public function ReporterShowListOT(){

        $Data_view = $this->o_overtime->getDataOverTime();
        return view('over_time.listOverTime', $Data_view);
    }

    //Export excel
    public function exportOT(){
        $sz_Sql = Session::get('sqlListOverTime');
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
            Excel::create('Danh_Sach_OT', function($excel) use($a_Error) {
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
