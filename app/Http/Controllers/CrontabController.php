<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Input;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\MailApi;
use App\Util;

class CrontabController extends Controller
{
    protected $_o_MailApi;

    const TOKEN_KEY = 'DxmbMailApi2016';

    public function __construct()
    {
        $this->_o_MailApi = new MailApi();
    }

    /**
     * Check and update users staff from mail server
     * https://izi.dxmb.vn/update_user_staff?token=DxmbMailApi2016
     * @author DungNT
     * @since 14/11/2015
     */
    public function v_fUpdateUsersStaff()
    {
        if(Input::get('token') == self::TOKEN_KEY) {
            set_time_limit(600);
            // Start micro time
            $i_StartTime = microtime(true);

            // Get users from api
            $a_Users = $this->_o_MailApi->a_fGetUsers();
            //print_r($a_Users);die;
            if (Util::b_fCheckArray($a_Users)) {
                $i_TotalUsers = count($a_Users);
                $a_NewUsers = array();
                $a_NeedUpdateUsers = array();
                //Get all users from db
                $a_DbUser = DB::table('users')->select('email', 'password')->get();
                $a_DbUsersEmail = array();
                $a_DbUsersEmailPwd = array();
                if(Util::b_fCheckArray($a_DbUser)) {
                    foreach ($a_DbUser as $o_DbUser) {
                        $a_DbUsersEmail[] = $o_DbUser->email;
                        $a_DbUsersEmailPwd[$o_DbUser->email] = $o_DbUser->password;
                    }
                }
                foreach ($a_Users as $o_User) {
                    // Check user is existed or not
                    if (in_array($o_User->UserName, $a_DbUsersEmail)) {
                        if(bcrypt($o_User->Password) != $a_DbUsersEmailPwd[$o_User->UserName]) {
                            // Update for user
                            $a_NeedUpdateUsers[] = [
                                'email' => trim($o_User->UserName),
                                'password'  => bcrypt($o_User->Password),
                                'updated_at' => Util::sz_fCurrentDateTime(),
                            ];
                        }
                    } else {
                        $a_Email = explode('@', $o_User->UserName);
                        // Insert new user
                        $a_NewUsers[] = [
                            'code' => $a_Email[0],
                            'name' => $o_User->FirstName . ' ' . $o_User->LastName,
                            'email' => trim($o_User->UserName),
                            'password'  => bcrypt($o_User->Password),
                            'job_id'=> 3,
                            'role_id'=> 3,
                            'created_at' => Util::sz_fCurrentDateTime(),
                            'updated_at' => Util::sz_fCurrentDateTime(),
                        ];
                    }
                }
                //Check to insert
                if (Util::b_fCheckArray($a_NewUsers)) {
                    //Get total of new users
                    $i_TotalNewUsers = count($a_NewUsers);
                    //Insert new user into db
                    if (DB::table('users')->insert($a_NewUsers)) {
                        echo "Thêm $i_TotalNewUsers user(s) thành công!\n";
                    } else {
                        echo "Thêm user(s) mới bị lỗi!\n";
                    }
                } else {
                    echo "Không có bất kỳ user mới nào được tìm thấy!\n";
                }
                //Check to update
                if (Util::b_fCheckArray($a_NeedUpdateUsers)) {
                    //Get total of new users
                    $i_UpdateSuccessfully = 0;
                    $i_UpdateFail = 0;
                    foreach($a_NeedUpdateUsers as $a_UpdateUser) {
                        $sz_WhereEmail = $a_UpdateUser['email'];
                        unset($a_UpdateUser['email']);
                        if (DB::table('users')->where('email', $sz_WhereEmail)->update($a_UpdateUser)) {
                            $i_UpdateSuccessfully++;
                        } else {
                            $i_UpdateFail++;
                        }
                    }
                    echo "Updated $i_UpdateSuccessfully user(s) thành công! And $i_UpdateFail user(s) lỗi!\n";
                } else {
                    echo "Không có bất kỳ user(s) nào được update!\n";
                }
            } else {
                echo "Không tìm thấy bất kỳ User nào!\n";
            }
            // End micro time
            $i_EndTime = microtime(true);
            // Get execute time
            $i_ExecuteTime = $i_EndTime - $i_StartTime;
            echo "Tổng thời gian: $i_ExecuteTime.\n";
        } else {
            echo "Token không đúng!\n";
        }
        echo "<br><a href='/user' style= 'color: blue;'>Quay lại trang Danh sách User >>>></a>";
    }

    /**
     * Check and update users staff groups from mail server
     * https://izi.dxmb.vn/update_group_staff?token=DxmbMailApi2016
     * @author DungNT
     * @since 14/11/2015
     */
    public function v_fUpdateStaffGroup()
    {
        if(Input::get('token') == self::TOKEN_KEY) {
            set_time_limit(1000);
            // Start micro time
            $i_StartTime = microtime(true);

            $a_UserGroups = $this->_o_MailApi->a_fGetUserGroupsByDomain();
            //print_r($a_UserGroups);die;
            if (Util::b_fCheckArray($a_UserGroups)) {
                $i_TotalGroups = count($a_UserGroups);
                $a_NewGroups = array();
                $a_UserNameGroups = array();
                //Get Groups from db
                $a_DbGroup = DB::table('departments')->select('guid')->get();
                $a_DbGuid = array();
                if(Util::b_fCheckArray($a_DbGroup)) {
                    foreach ($a_DbGroup as $o_DbGroup) {
                        $a_DbGuid[] = $o_DbGroup->guid;
                    }
                }
                foreach ($a_UserGroups as $o_UserGroup) {
                    if (Util::b_fCheckObject($o_UserGroup) && $o_UserGroup->guid) {
                        // Check user group is existed or not
                        if (!in_array($o_UserGroup->guid, $a_DbGuid)) {
                            // Insert new user group
                            $a_NewGroups[] = [
                                'guid' => $o_UserGroup->guid,
                                'name' => $o_UserGroup->name,
                            ];
                        }
                        if (Util::b_fCheckObject($o_UserGroup->userNames)) {
                            if(isset($o_UserGroup->userNames->string) && $o_UserGroup->userNames->string) {
                                $a_UserNameGroups[$o_UserGroup->guid] = $o_UserGroup->userNames->string;
                            }
                        }
                    }
                }
                if (Util::b_fCheckArray($a_NewGroups)) {
                    $i_TotalNewGroups = count($a_NewGroups);
                    if (DB::table('departments')->insert($a_NewGroups)) {
                        echo "Inserted $i_TotalNewGroups successfully!\n";
                    } else {
                        echo "Insert new groups failed!\n";
                    }
                } else {
                    echo "Have no any new groups!\n";
                }
                //Update group to users table
                if (Util::b_fCheckArray($a_UserNameGroups)) {
                    $this->v_fUpdateGroupForUsers($a_UserNameGroups);
                }
            } else {
                echo "No any user groups found!\n";
            }
            // End micro time
            $i_EndTime = microtime(true);
            // Get execute time
            $i_ExecuteTime = $i_EndTime - $i_StartTime;
            echo "Execute times: $i_ExecuteTime.\n";
        } else {
            echo "Wrong token key!\n";
        }
    }

    /**
     * User name of group to string
     *
     * @author DungNT
     * @since 17/11/2015
     * @param array $the_a_UserNames
     * @return array|boolean
     */
    private function a_fUserNamesOfGroup($the_a_UserNames)
    {
        if (Util::b_fCheckArray($the_a_UserNames)) {
            foreach($the_a_UserNames as &$sz_UserName) {
                $sz_UserName .= '@'.MailApi::DOMAIN_NAME;
            }
            return $the_a_UserNames;
        }
        return false;
    }

    /**
     * Update group for users
     *
     * @author DungNT
     * @since 17/11/2015
     * @param array $the_a_Groups
     */
    private function v_fUpdateGroupForUsers($the_a_Groups)
    {
        $sz_Msg = '';
        if (Util::b_fCheckArray($the_a_Groups)) {
            $a_GroupUid = array_keys($the_a_Groups);
            $a_Groups = DB::table('departments')->select('id', 'guid')->whereIn('guid', $a_GroupUid)->get();
            if (Util::b_fCheckArray($a_Groups)) {
                $a_GroupsIds = array();
                foreach ($a_Groups as $o_Group) {
                    if(isset($o_Group->guid) && isset($o_Group->id)) {
                        $a_GroupsIds[$o_Group->guid] = $o_Group->id;
                    }
                }
                if (Util::b_fCheckArray($a_GroupsIds)) {
                    $i_Updated = 0;
                    $i_NotUpdate = 0;
                    foreach ($the_a_Groups as $sz_Guid => $a_Users) {
                        if ($a_UserNames = $this->a_fUserNamesOfGroup($a_Users)) {
                            if (DB::table('users')->whereIn('email', $a_UserNames)->update(['department_id' => $a_GroupsIds[$sz_Guid]])) {
                                $i_Updated++;
                            } else {
                                $i_NotUpdate++;
                            }
                        }
                    }
                    $sz_Msg = "Updated $i_Updated groups and Cannot update $i_NotUpdate groups\n";
                }
            } else {
                $sz_Msg = "No any group found!\n";
            }
        } else {
            $sz_Msg = "No any group found!\n";
        }
        if ($sz_Msg) {
            echo $sz_Msg;
        }
    }
    
    /**
     * Check and update users alias from mail server
     * https://izi.dxmb.vn/update_alias?token=DxmbMailApi2016
     * @author HuyNN
     * @since 25/03/2016
     */
    public function v_fUpdateAlias()
    {
        if(Input::get('token') == self::TOKEN_KEY) {
            set_time_limit(1000);
            // Start micro time
            $i_StartTime = microtime(true);
            
            $a_UserDb = DB::table('users')->select('email')->get();
            
            $a_AllAlias = $this->_o_MailApi->a_fGetUserAlias();
            $a_AliasEmailConvert = array();
            if(Util::b_fCheckArray($a_AllAlias) && count($a_AllAlias) > 0)
            {
                foreach ($a_AllAlias as $sz_Alias => $val) {
                    if(is_array($val))
                    {
                        if(count($val) > 0) {
                            foreach ($val as $sz_Email) {
                                if(array_key_exists($sz_Email, $a_AliasEmailConvert))
                                {
                                    $a_AliasEmailConvert[$sz_Email].= ','.$sz_Alias;
                                }
                                else $a_AliasEmailConvert[$sz_Email] = $sz_Alias;
                            }
                        }
                    }
                    else {
                        if(array_key_exists($val, $a_AliasEmailConvert))
                        {
                            $a_AliasEmailConvert[$val].= ','.$sz_Alias;
                        }
                        else $a_AliasEmailConvert[$val] = $sz_Alias;
                    }
                }  
            }   

            /////Update DB////
            if(Util::b_fCheckArray($a_UserDb) && count($a_UserDb) > 0)
            {
                $i_UpdateSuccessfully = 0;
                $i_UpdateFail = 0;
                $sz_MissEmails = '';
                foreach ($a_UserDb as $o_Email) {
                    if(array_key_exists($o_Email->email, $a_AliasEmailConvert)) {
                        $i_Update = DB::table('users')->where('email',$o_Email->email)->update(array('alias' => $a_AliasEmailConvert[$o_Email->email]));
                        if ($i_Update) $i_UpdateSuccessfully++;   
                        else $i_UpdateFail++;  
                    }
                    else $sz_MissEmails.= ','.$o_Email->email;
                }
                echo "Updated $i_UpdateSuccessfully alias successfully! And $i_UpdateFail failed!\n";  
                echo "Lost Some Emails: ".$sz_MissEmails; 
            }
        } 
    }
    /**
     * Update leave request report
     *
     * @author HuyNN
     * @since 11/1/2015
     * 
     */
    public function v_fUpdateLeaveRequestReport()
    {
        //DB::enableQueryLog();
        //DB::table('leave_request_report')->delete();
        ///Lấy năm và tháng trước của tháng hiện tại///
        $i_CurrentMonth =  date("m");
        $i_CurrentMonth = (int) $i_CurrentMonth;
        //$i_CurrentMonth = 1;
        $i_Year =  date("Y");

        $a_leave_types = DB::table('leave_types')->select('id','name')->get();
        //Mảng lưu thông tin các loại nghỉ phép///////
        foreach ($a_leave_types as $o_LeaveType) 
        {
            $a_LeaveTypes[$o_LeaveType->id] = $o_LeaveType->name;
        }
        
        if(date('d') < 26 )
        {
            if($i_CurrentMonth == 1)
            {
                $i_Last2Month = 11;
                $i_LastYear = $i_Year - 1;
            } 
            else if($i_CurrentMonth == 2)
            {
                $i_Last2Month = 12;
                $i_LastYear = $i_Year - 1;
            } 
            else
            {
                $i_Last2Month = $i_CurrentMonth - 2;
                $i_LastYear = $i_Year;
            } 
            $sz_TimeStart = $i_LastYear.'-'.($i_Last2Month<10?'0'.$i_Last2Month:$i_Last2Month).'-26';
            $sz_TimeEnd = $i_Year.'-'.($i_CurrentMonth<10?'0'.$i_CurrentMonth:$i_CurrentMonth).'-25'; 
        }
        // Nếu ngày hiện tại lớn hơn 26 thì lấy các ngày từ 26 tháng trước tới 25 tháng sau
        else
        {
            if($i_CurrentMonth == 12)
            {   
                $i_LastMonth = 11;
                $i_NextMonth = 1;
                $i_LastYear = $i_Year;
                $i_NextYear = $i_Year + 1;
            }
            else if($i_CurrentMonth == 1)
            {
                $i_LastMonth = 12;
                $i_NextMonth = 2;
                $i_LastYear = $i_Year - 1;
                $i_NextYear = $i_Year;
            }
            else 
            {
                $i_LastMonth = $i_CurrentMonth - 1;
                $i_NextMonth = $i_CurrentMonth + 1;
                $i_LastYear = $i_Year;
                $i_NextYear = $i_Year;
            }
            $sz_TimeStart = $i_LastYear.'-'.($i_LastMonth < 10?'0'.$i_LastMonth:$i_LastMonth).'-26';
            $sz_TimeEnd = $i_NextYear.'-'.($i_NextMonth < 10?'0'.$i_NextMonth:$i_NextMonth).'-25';
        }
        
        $i_DayTimeStart = date('d',  strtotime($sz_TimeStart));
        $i_MonthTimeStart = date('m',  strtotime($sz_TimeStart));
        $i_YearTimeStart = date('Y',  strtotime($sz_TimeStart));
        if($i_MonthTimeStart == 12)
        {
            $i_NextMonthTimeStart = 1;
            $i_NextYearTimeStart = $i_YearTimeStart + 1;
        }
        else 
        {
            $i_NextMonthTimeStart = $i_MonthTimeStart + 1;
            $i_NextYearTimeStart = $i_YearTimeStart;
        }
        $sz_TimeEnd1 = $i_NextYearTimeStart.'-'.($i_NextMonthTimeStart<10?'0'.$i_NextMonthTimeStart:$i_NextMonthTimeStart).'-25';
        $sz_TimeStart1 = date('Y-m-d',strtotime($sz_TimeEnd1 . "+1 days"));

        ///////Mảng lưu các ngày thỏa mãn điều kiện////////
        $a_AllRangeDate[] = Util::createDateRangeArray($sz_TimeStart,$sz_TimeEnd1);
        $a_AllRangeDate[] = Util::createDateRangeArray($sz_TimeStart1,$sz_TimeEnd);
       
        ///Get Info All Department///
        $a_DbDepartments = DB::table('departments')->select('id','time_start','time_end')->get();
        if(Util::b_fCheckArray($a_DbDepartments)){
            $a_Departments = array();
            foreach ($a_DbDepartments as $o_DbDepartments) 
            {
                $a_Departments[$o_DbDepartments->id] = array('time_start' => $o_DbDepartments->time_start, 'time_end' => $o_DbDepartments->time_end);
            }
        }
        $err = 0;
        foreach ($a_AllRangeDate as $a_RangeDate) 
        {
            $sz_FirstDateRangeDate = reset($a_RangeDate).' 00:00:00';
            $sz_EndDateRangeDate = end($a_RangeDate).' 23:59:59';
            $i_MonthFromEndDate =  date('m',  strtotime($sz_EndDateRangeDate)); // Lấy ra tháng của ngày kết thúc
            $i_YearFromEndDate = date('Y',  strtotime($sz_EndDateRangeDate)); // Lấy ra năm của ngày kết thúc
            ///Lấy ra các đơn nghỉ phép phù hợp từ 26 tháng trước đến 25 tháng này
            ///Bất kỳ đơn xin nghỉ nào mà có ngày nghỉ nằm trong khoảng thời trên đều là phù hợp///
            $a_MatchLeaveRequest = DB::table('leave_requests')
            ->where('status',2)        
            ->where(function($query) use ($sz_FirstDateRangeDate, $sz_EndDateRangeDate)
            {
                $query->where(function($query1) use ($sz_FirstDateRangeDate, $sz_EndDateRangeDate)
                {
                    $query1->where('from_time', '>=', $sz_FirstDateRangeDate)
                    ->Where('from_time', '<=', $sz_EndDateRangeDate);
                })
                ->orWhere(function($query1) use ($sz_FirstDateRangeDate, $sz_EndDateRangeDate)
                {
                    $query1->where('to_time', '>=', $sz_FirstDateRangeDate)
                    ->Where('to_time', '<=', $sz_EndDateRangeDate);
                })
                ->orWhere(function($query1) use ($sz_FirstDateRangeDate, $sz_EndDateRangeDate)
                {
                    $query1->where('from_time', '<=', $sz_FirstDateRangeDate)
                    ->Where('to_time', '>=', $sz_EndDateRangeDate);
                });
            })        
            ->orderBy('user_id', 'desc')->get();

            //dd(DB::getQueryLog());
            $a_AllUserLeaveRequest = array(); // Mảng cấu trúc lại từ mảng $a_MatchLeaveRequest
            foreach ($a_MatchLeaveRequest as $o_UserInfo) 
            {
                if(!array_key_exists($o_UserInfo->user_id, $a_AllUserLeaveRequest)) 
                {
                    $a_AllUserLeaveRequest[$o_UserInfo->user_id] = array(
                        'name' => $o_UserInfo->name,
                        'code' => $o_UserInfo->code,
                        'department_id' => $o_UserInfo->department_id,
                        'department_name' => $o_UserInfo->department_name,
                        'position_id' => $o_UserInfo->position_id,
                        'position_name' => $o_UserInfo->position_name,
                        'enable_sunday' => $o_UserInfo->enable_sunday,
                        'leave_request' => array(
                            array(
                                'id_leave_request' => $o_UserInfo->id,
                                'name_type_id' => $a_LeaveTypes[$o_UserInfo->type_id],
                                'user_comment' => $o_UserInfo->user_comment,
                                'from' => $o_UserInfo->from_time,
                                'to' => $o_UserInfo->to_time,
                            )
                        ),
                    );
                }
                else
                {
                    $a_AllUserLeaveRequest[$o_UserInfo->user_id]['leave_request'][] = array(
                        'id_leave_request' => $o_UserInfo->id,
                        'name_type_id' => $a_LeaveTypes[$o_UserInfo->type_id],
                        'user_comment' => $o_UserInfo->user_comment,
                        'from' => $o_UserInfo->from_time,
                        'to' => $o_UserInfo->to_time,
                    );
                }
            }    

            foreach ($a_AllUserLeaveRequest as $i_UserId => $a_UserLeaveRequest) 
            {
                $o_Department = DB::table('departments')->select('numb_of_work')->where('id',$a_UserLeaveRequest['department_id'])->first();
                $i_Depart = $a_UserLeaveRequest['department_id'];       
                if(isset($a_InsertDate)) unset($a_InsertDate);
                $a_InsertDate = array(
                    'user_id' => $i_UserId,
                    'name' => $a_UserLeaveRequest['name'],
                    'code' => $a_UserLeaveRequest['code'],
                    'department_id' => $a_UserLeaveRequest['department_id'],
                    'department_name' => $a_UserLeaveRequest['department_name'],
                    'position_id' => $a_UserLeaveRequest['position_id'],
                    'position_name' => $a_UserLeaveRequest['position_name'],
                    'enable_sunday' => $a_UserLeaveRequest['enable_sunday'],
                    'month' => $i_MonthFromEndDate,
                    'year' => $i_YearFromEndDate,
                );


                foreach ($a_UserLeaveRequest['leave_request'] as $a_DetailLeaveRequest) 
                {
                    $i_From = strtotime($a_DetailLeaveRequest['from']);
                    if($a_DetailLeaveRequest['name_type_id'] != config('cmconst.value.PCT'))
                    {
                        $sz_Detail_FromTime = date('d-m-Y',$i_From);
                        $sz_InfoFromTime = date('H:i d-m-Y',$i_From);
                    }
                    else $sz_InfoFromTime = date('H:i d-m-Y',$i_From);
                    $sz_DetailFromTime = date('Y-m-d',$i_From); 
                    $i_DetailFromTime = strtotime($sz_DetailFromTime);
                 
                    $i_DetailToTime = strtotime($a_DetailLeaveRequest['to']);
                    if($a_DetailLeaveRequest['name_type_id'] != config('cmconst.value.PCT'))
                    {
                        $sz_Detail_ToTime = date('d-m-Y',$i_DetailToTime); 
                        $sz_InfoToTime = date('H:i d-m-Y',$i_DetailToTime);
                    }
                    else $sz_InfoToTime = date('H:i d-m-Y',$i_DetailToTime);
                    $sz_DetailToTime = date('Y-m-d',$i_DetailToTime); 

                    $sz_InfoLeaveRequest = '|'.$a_DetailLeaveRequest['name_type_id'].'|'.$a_DetailLeaveRequest['user_comment'].'|'.$sz_InfoFromTime.' - '.$sz_InfoToTime.'|'.$a_DetailLeaveRequest['id_leave_request'];
                    foreach ($a_RangeDate as $sz_CheckedDate) 
                    {
                        $i_CheckedDate =  strtotime($sz_CheckedDate);
                        if($i_DetailFromTime <= $i_CheckedDate && $i_CheckedDate <= $i_DetailToTime)
                        {
                            $i_GetDate = date('d',$i_CheckedDate);
                            $sz_val = isset($a_InsertDate[$i_GetDate])?$a_InsertDate[$i_GetDate].'&&&':''; // Nếu ngày đang xét đã có giá trị rồi thì gán vào một biến, lát sẽ nối thêm vào

                            $sz_CheckedDay = date( "l", $i_CheckedDate); // Kiểm tra xem ngày hiện tại là thứ mấy
                            switch ($a_DetailLeaveRequest['name_type_id']) /// Kiểm tra loại nghỉ phép
                            {
                                case config('cmconst.value.PCT'): /// Nếu là phiếu công tác
                                   // Nếu ngành nghề cho phép làm chủ nhật
                                    if($a_UserLeaveRequest['enable_sunday'] == 1)
                                    {
                                        // Nếu phòng ban của nhân viên này làm 5 ngày 1 tuần thì bỏ qua ngày t7 
                                        if($sz_DetailFromTime == $sz_DetailToTime)
                                        {      
                                            $sz_time = date('H:i',$i_From).' - '.date('H:i',$i_DetailToTime);
                                        }
                                        else
                                        {
                                            if($sz_CheckedDate == $sz_DetailFromTime)
                                            {
                                                $sz_time = date('H:i',$i_From).' - '. date('H:i',strtotime($a_Departments[$i_Depart]['time_end']));
                                            }
                                            else if($sz_CheckedDate == $sz_DetailToTime)
                                            {
                                                $sz_time = date('H:i',strtotime($a_Departments[$i_Depart]['time_start'])).' - '.date('H:i',$i_DetailToTime);
                                            }
                                            else
                                            {
                                                $sz_time = date('H:i',strtotime($a_Departments[$i_Depart]['time_start'])).' - '. date('H:i',strtotime($a_Departments[$i_Depart]['time_end']));
                                            }
                                        }

                                       $sz_InfoLeaveRequest = '|'.$a_DetailLeaveRequest['name_type_id'].'|'.$a_DetailLeaveRequest['user_comment'].'|'.$sz_time.'|'.$a_DetailLeaveRequest['id_leave_request'];
                                       $a_InsertDate[$i_GetDate] = $sz_val.'ct'.$sz_InfoLeaveRequest; 
                                    }
                                    /// Nếu ko cho làm ngày chủ nhật
                                    else
                                    {
                                        if(($o_Department->numb_of_work == 5 && $sz_CheckedDay != 'Saturday' && $sz_CheckedDay != 'Sunday') ||  ($o_Department->numb_of_work != 5 && $sz_CheckedDay != 'Sunday'))
                                        {
                                            if($sz_DetailFromTime == $sz_DetailToTime)
                                            {      
                                                $sz_time = date('H:i',$i_From).' - '.date('H:i',$i_DetailToTime);
                                            }
                                            else
                                            {
                                                if($sz_CheckedDate == $sz_DetailFromTime)
                                                {
                                                    $sz_time = date('H:i',$i_From).' - '.date('H:i',strtotime($a_Departments[$i_Depart]['time_end']));;
                                                }
                                                else if($sz_CheckedDate == $sz_DetailToTime)
                                                {
                                                    $sz_time = date('H:i',strtotime($a_Departments[$i_Depart]['time_start'])). ' - '.date('H:i',$i_DetailToTime);
                                                }
                                                else
                                                {
                                                    $sz_time = date('H:i',strtotime($a_Departments[$i_Depart]['time_start'])).' - '. date('H:i',strtotime($a_Departments[$i_Depart]['time_end']));
                                                }
                                            }
                                            $sz_InfoLeaveRequest = '|'.$a_DetailLeaveRequest['name_type_id'].'|'.$a_DetailLeaveRequest['user_comment'].'|'.$sz_time.'|'.$a_DetailLeaveRequest['id_leave_request'];
                                            $a_InsertDate[$i_GetDate] = $sz_val.'ct'.$sz_InfoLeaveRequest;
                                        }
                                    }          
                                    break;
                                default: // Nếu là các loại nghỉ phép còn lại
                                    if($sz_CheckedDate == $sz_DetailFromTime && $sz_CheckedDate == $sz_DetailToTime) $a_InsertDate[$i_GetDate] = $sz_val.(($a_DetailLeaveRequest['name_type_id'] == config('cmconst.value.NPN'))?'pn/2':'p/2').$sz_InfoLeaveRequest;
                                    else
                                    {
                                        if($sz_CheckedDate == $sz_DetailFromTime)
                                        {
                                            if($o_Department->numb_of_work != 5 && $sz_CheckedDay == 'Saturday')
                                            {
                                                $a_InsertDate[$i_GetDate] = $sz_val.(($a_DetailLeaveRequest['name_type_id']== config('cmconst.value.NPN'))?'pn/2':'p/2').$sz_InfoLeaveRequest;
                                            }
                                            else
                                            {
                                                if(date('H',strtotime($a_DetailLeaveRequest['from'])) == 12) $a_InsertDate[$i_GetDate] = $sz_val.(($a_DetailLeaveRequest['name_type_id']== config('cmconst.value.NPN'))?'pn/2':'p/2').$sz_InfoLeaveRequest;
                                                else $a_InsertDate[$i_GetDate] = $sz_val.(($a_DetailLeaveRequest['name_type_id'] == config('cmconst.value.NPN'))?'pn':'p').$sz_InfoLeaveRequest;
                                            }
                                        }
                                        else if($sz_CheckedDate == $sz_DetailToTime)
                                        {
                                            if(date('H',strtotime($a_DetailLeaveRequest['to'])) == 12) $a_InsertDate[$i_GetDate] = $sz_val.(($a_DetailLeaveRequest['name_type_id'] == config('cmconst.value.NPN'))?'pn/2':'p/2').$sz_InfoLeaveRequest;
                                        }
                                        else
                                        {
                                            if($o_Department->numb_of_work == 5 && $sz_CheckedDay != 'Sunday' && $sz_CheckedDay != 'Saturday') 
                                            {

                                                $a_InsertDate[$i_GetDate] = $sz_val.($a_DetailLeaveRequest['name_type_id']== (config('cmconst.value.NPN'))?'pn':'p').$sz_InfoLeaveRequest;
                                            }
                                            //Nếu phòng ban làm việc 5,5 ngày và ngày hiện tại khác chủ nhật
                                            else if($o_Department->numb_of_work != 5 && $sz_CheckedDay != 'Sunday')
                                            {
                                                // Nếu ngày hiện tại là thứ 7 thì chấm nửa ngày
                                                if($sz_CheckedDay == 'Saturday') $a_InsertDate[$i_GetDate] = $sz_val.($a_DetailLeaveRequest['name_type_id'] == (config('cmconst.value.NPN'))?'pn/2':'p/2').$sz_InfoLeaveRequest;
                                                ///Ngược lại chấm 1 ngày/////
                                                else $a_InsertDate[$i_GetDate] = $sz_val.(($a_DetailLeaveRequest['name_type_id']== config('cmconst.value.NPN'))?'pn':'p').$sz_InfoLeaveRequest;
                                            }
                                        }
                                    }
                                    break;
                            }
                        }
                    }
                }
                DB::table('leave_request_report')->where(array('user_id' => $i_UserId,'month' => $i_MonthFromEndDate,'year' => $i_YearFromEndDate))->delete();
                $insert = DB::table('leave_request_report')->insert($a_InsertDate);
                if(!$insert) $err = $err + 1;
            }    
        }  
        if($err == 0) echo "Update thành công";
        else echo "Có ".$err.' bản ghi update không thành công';
    }
    
    /**
     * Update all mail box size from Mail Server to Izi
     * https://izi.dxmb.vn/update_mail_size?token=DxmbMailApi2016
     * @author HuyNN
     * @since 12/4/2016
     * 
     */
    public function v_fUpdateMailSize()
    {
        if(Input::get('token') == self::TOKEN_KEY) 
        {
            set_time_limit(1000);
            // Start micro time
            $i_StartTime = microtime(true);
            $a_UserQuotas = $this->_o_MailApi->a_fGetQuotas();
          
            if (Util::b_fCheckArray($a_UserQuotas)) 
            {
                //Get User Mail Size from db
                $a_DbUser = DB::table('users')->select('email','mail_size')->get();
                $a_NotExistEmail =  array();
                $a_DbUserMailSize = array();
                $i_UpdateSuccessfully = 0;
                $i_UpdateFail = 0;
                if(Util::b_fCheckArray($a_DbUser)) 
                {
                    foreach ($a_DbUser as $o_DbMailSize) 
                    {
                        $a_DbUserMailSize[$o_DbMailSize->email] = $o_DbMailSize->mail_size;
                    }
                }
                
                foreach ($a_UserQuotas as $o_UserQuotas) 
                {
                    if (Util::b_fCheckObject($o_UserQuotas)) 
                    {
                        // Check emall is existed or not
                        $sz_Email = $o_UserQuotas->UserName;
                        $sz_Email.='@dxmb.vn';
                        ////If Not Existed Email on DB////
                        if (!array_key_exists($sz_Email, $a_DbUserMailSize)) 
                        {
                            // Insert new user group
                            $a_NotExistEmail[] = $sz_Email;
                        }
                        else 
                        {
                            $i_DbMailSize = $a_DbUserMailSize[$sz_Email];
                            $i_OnlineMailSize = $o_UserQuotas->MaxSpace / 1024 / 1024;
                            
                            if($i_DbMailSize != $i_OnlineMailSize)
                            {    
                                if (DB::table('users')->where('email', $sz_Email)->update(array('mail_size' => $i_OnlineMailSize))) 
                                {
                                    $i_UpdateSuccessfully++;
                                } 
                                else $i_UpdateFail++;
                            }
                        }
                    }
                }
                echo "Updated $i_UpdateSuccessfully User Mail Size and Cannot update $i_UpdateFail User Mail Size <br/>";
                $i_NotExistEmail = count($a_NotExistEmail);
                if($i_NotExistEmail > 0) {
                    echo "No found $i_NotExistEmail emails on DB <br/>";
                    echo ("<pre>");
                    print_r($a_NotExistEmail);
                    echo ("</pre>");
                }
            } 
            else echo  "No any user quotas found! <br/>";
            
            // End micro time
            $i_EndTime = microtime(true);
            // Get execute time
            $i_ExecuteTime = $i_EndTime - $i_StartTime;
            echo  "Execute times: $i_ExecuteTime <br/>";
        }
        else echo  "Wrong token key! <br/>";
    }
}
