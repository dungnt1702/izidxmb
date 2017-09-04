<?php

namespace App\Http\Controllers\Timesheet;

use Illuminate\Http\Request;
use App\Util;
use DB;
use Illuminate\Http\Request as o_request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use App\Models\Timesheet;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;

class TimesheetController extends Controller
{
    private $o_Timesheet;
    public function __construct() {
        $this->o_Timesheet = new Timesheet();
    }
    
    /**
     * Auth: Dienct
     * Des: Import file excel table user
     * Since: 15/1/2015
     */
    public function showexcel() {
        $a_Res = array();
        if (Input::hasFile('excel')) {
            
            $filename = Input::file('excel')->getClientOriginalName();
            $extension = Input::file('excel')->getClientOriginalExtension();
            
            if($extension == 'xlsx' || $extension == 'xls'){
                Input::file('excel')->move('uploads/', $filename);
                $sz_FileDir = 'uploads'."/".$filename;
                $a_Res = $this->o_Timesheet->ImportExcelTimesheet($sz_FileDir);
                $strRes = "";
                foreach ($a_Res as $key => $val){
                    $strRes .=" ".$val;
                }
            }else{
                $strRes = "Cần nhập đúng định dạng file (xls, xlsx)!!!!";
            }

            return view('timesheet.import',['a_Res'=>$strRes]);

        }else{
            return view('timesheet.import');
        }

    }
    
    /**
     * @author Vit
     * @since 18/01/1016
     * @des View Timesheet Table
     */
    
    public function TableTimeSheet(){
        $Data_view = $this->o_Timesheet->TimesheetTable();
        return view('timesheet.timesheet_table', $Data_view);
    }
    
    public function MergeTimeSheet(o_request $o_resquest) 
    {   
        set_time_limit(1000);
        $month = $o_resquest->month;
        $year = $o_resquest->year;

        DB::table('merge_time_sheet')->where(array('month' => $month, 'year' =>$year))->delete();
        DB::table('late')->where(array('month' => $month, 'year' =>$year))->delete();

        $a_DbUsers = DB::table('users')->select('code','work_start','flag_timesheet','name')->get();
        if($a_DbUsers){
            foreach ($a_DbUsers as $o_Val) {
                $o_Val->code = trim($o_Val->code);
                $a_infoUsers[$o_Val->code] = array(
                    'name' => $o_Val->name,
                    'work_start' => strtotime($o_Val->work_start),
                    'flag_timesheet' => $o_Val->flag_timesheet
                );       
            }
        }
        ////////Get All Departments in DB////////
        $a_AllDepartments = $this->o_Timesheet->a_GetAllDepartment();

        $a_AllAbsent = array('month' => $month, 'year' => $year); // Khởi tạo mảng lưu dữ liệu tất cả ngày nghỉ, vắng của toàn bộ nhân viên trong tháng
        
        ////Mảng lưu giá trị dải ngày phù hợp với tháng đang merge///
        $a_RangeDate = Util::GetRangeDate($month,$year);
        
        $a_RangeDay = Util::GetRangeDay($a_RangeDate);

        /// Lấy dữ liệu từ bảng Leave Request Report trong tháng //// 
        $a_LeaveReport = DB::table('leave_request_report')->where(array('month' => $month, 'year' => $year))->get();
        foreach ($a_LeaveReport as $key => $o_val) 
        {
            $o_val->code = trim($o_val->code);
            $a_LeaveReport[$o_val->code] = $a_LeaveReport[$key];
            unset($a_LeaveReport[$key]);
        }
        
        /// Lấy dữ liệu từ bảng Time Sheet trong tháng ////
        $a_TimeSheet = DB::table('timesheet')->where(array('month' => $month, 'year' => $year))->get();
        foreach ($a_TimeSheet as $i_key => $o_val) 
        {
            $o_val->code = trim($o_val->code);
            $a_TimeSheet[$o_val->code] = $a_TimeSheet[$i_key];
            unset($a_TimeSheet[$i_key]); 
        }

        $a_Late =  array(); // Mảng lưu các ngày đi muộn
        foreach ($a_TimeSheet as $code => $a_InfoTimeSheet) 
        {
            if(isset($a_infoUsers[$code]) && $a_infoUsers[$code]['flag_timesheet'] != 0)
            {
                if(!isset($a_AllAbsent[$code]))
                {
                    $a_AllAbsent[$code] = array('user_id' => $a_InfoTimeSheet->user_id, 'name' => $a_InfoTimeSheet->name, 'code' => $code, 'department_id' => $a_InfoTimeSheet->department_id, 'department_name' => $a_InfoTimeSheet->department_name);
                }

                if(!isset($a_Late[$code]))
                {
                    $a_Late[$code] = array(
                        'user_id' => $a_InfoTimeSheet->user_id , 
                        'name' => $a_InfoTimeSheet->name, 
                        'code' => $code, 
                        'department_id' => $a_InfoTimeSheet->department_id, 
                        'department_name' => isset($a_AllDepartments[$a_InfoTimeSheet->department_id])?$a_AllDepartments[$a_InfoTimeSheet->department_id] :'Chưa có phòng ban', 
                        'month' => $month, 
                        'year' => $year
                    );
                }    
                foreach ($a_InfoTimeSheet as $key => $val) 
                {
                    $sz_Month = $a_TimeSheet[$code]->month < 10?'0'.$a_TimeSheet[$code]->month:$a_TimeSheet[$code]->month;
                    $sz_Year = $a_TimeSheet[$code]->year ;
                    if(is_numeric($key) && array_key_exists($key,$a_RangeDay))
                    {  
                        $sz_Date = $key.'-'.$sz_Month.'-'.$sz_Year;
                        if($a_infoUsers[$code]['work_start'] == '' || Util::GetRealDate($sz_Year,$sz_Month,$key) >= $a_infoUsers[$code]['work_start'])
                        {     
                            if(isset($a_Each1)) unset($a_Each1);
                            $i_Time8Hours = strtotime($sz_Date.' 08:00');
                            $i_Time805Hours = strtotime($sz_Date.' 08:05');
                            $i_Time810Hours = strtotime($sz_Date.' 08:10');
                            $i_Time9Hours = strtotime($sz_Date.' 09:00');
                            $i_Time11Hours = strtotime($sz_Date.' 11:00');
                            $i_Time1150Hours = strtotime($sz_Date.' 11:50');
                            $i_Time1155Hours = strtotime($sz_Date.' 11:55');
                            $i_Time12Hours = strtotime($sz_Date.' 12:00');
                            $i_Time1330Hours = strtotime($sz_Date.' 13:00');
                            $i_Time1335Hours = strtotime($sz_Date.' 13:35');
                            $i_Time1340Hours = strtotime($sz_Date.' 13:40');
                            $i_Time1430Hours = strtotime($sz_Date.' 14:30');
                            $i_Time1630Hours = strtotime($sz_Date.' 16:30');
                            $i_Time1720Hours = strtotime($sz_Date.' 17:20');
                            $i_Time1725Hours = strtotime($sz_Date.' 17:25');
                            $i_Time1730Hours = strtotime($sz_Date.' 17:30');
                            $sz_Day = $a_RangeDay[$key];

                            ////Ngày CN sẽ ko xét////
                            if($sz_Day != 'CN')
                            {
                                ///Nếu ngày này không có giá trị --> không có chấm công///
                                if($val == '' || $val == '|')
                                {       
                                    ///Xét bảng tổng hợp Leave Request Report, nếu ngày này không có giá trị -> không có đơn vắng mặt///
                                    if((array_key_exists($code,$a_LeaveReport) && $a_LeaveReport[$code]->$key == '') || !array_key_exists($code,$a_LeaveReport))
                                    {  
                                        ///Nếu ngày đang xét từ t2 -> t6///
                                        if(in_array($sz_Day, array('T2','T3','T4','T5','T6','T7')))
                                        {
                                            if(isset($a_AllAbsent[$code][$key]))
                                            {
                                                $a_AllAbsent[$code][$key] .= ',v';
                                            }
                                            else $a_AllAbsent[$code][$key] = 'v';
                                        }
                                    } 
                                    //////Xét bảng tổng hợp Report, nếu ngày này có giá trị -> có đơn vắng mặt/////
                                    else
                                    {
                                        /////Nếu ngày này từ 2 đơn vắng mặt trở lên///
                                        if (strpos($a_LeaveReport[$code]->$key, '&&&') !== false)
                                        {
                                            $a_Each = explode('&&&', $a_LeaveReport[$code]->$key);
                                            foreach ($a_Each as $sz_Each) 
                                            {
                                                $a_Each1[] = explode('|', $sz_Each);
                                            }

                                            $sz_Comment = ''; ///Comment nghỉ nửa ngày
                                            $sz_CommentBusiness = ''; ///Comment PCT
                                            $a_CheckAbsent =  array(); ///Mảng lưu tất cả các đơn là nghỉ nửa ngày, lưu theo key của mảng $a_Each1 ////
                                            $a_StartBusiness =  array(); /// Mảng lưu tất cả thời gian bắt đầu công tác của tất cả Phiếu CT trong ngày
                                            $a_EndBusiness = array(); /// Mảng lưu tất cả thời gian kết thúc công tác của tất cả Phiếu CT trong ngày

                                            foreach ($a_Each1 as $i_key => $a_InfoAbsent) 
                                            { 
                                                if(in_array($a_InfoAbsent[0], array('p/2','pn/2')))
                                                {
                                                    $a_CheckAbsent[] = $i_key; 
                                                    $a_ExTimeAbsent = explode(' ', $a_InfoAbsent[3]); /// Tách theo dấu '' của đơn để xấc định time bắt đầu nghỉ của đơn 2 là sáng hay chiều///
                                                    if($a_ExTimeAbsent[0] == '12:00') $sz_Comment = $sz_Comment == ''?'Nghỉ chiều':'|Nghỉ chiều';
                                                    else $sz_Comment = $sz_Comment == ''?'Nghỉ sáng':'|Nghỉ sáng';
                                                }
                                                ///Với các đơn còn lại sẽ là Phiếu công tác///
                                                else
                                                {
                                                    $sz_CommentBusiness.=  $sz_CommentBusiness == ''?$a_InfoAbsent[2].'. Thời gian: '.$a_InfoAbsent[3]:'|'.$a_InfoAbsent[2].'. Thời gian: '.$a_InfoAbsent[3];
                                                    $a_ExAbsentBusiness = explode(' ', $a_InfoAbsent[3]); /// Tách theo dấu '' để xác định khoảng thời gian công tác của đơn////
                                                    $a_StartBusiness[] = strtotime($sz_Date.' '.$a_ExAbsentBusiness[0]); /// Mảng lưu tất cả thời gian bắt đầu công tác của tất cả Phiếu CT trong ngày
                                                    $a_EndBusiness[] = strtotime($sz_Date.' '.$a_ExAbsentBusiness[2]); /// Mảng lưu tất cả thời gian kết thúc công tác của tất cả Phiếu CT trong ngày
                                                    $i_MinStartBusiness = min($a_StartBusiness); /// Lấy Thời gian bắt đầu công tác nhỏ nhất của các phiếu///
                                                    $i_MaxEndBusiness = max($a_EndBusiness); /// Lấy Thời gian kết thúc công tác lớn nhất của các phiếu///  
                                                }
                                            }

                                            /////Nếu ngày này không có bất kỳ đơn xin nghỉ nửa ngày nào => Tất cả là PCT///
                                            if(count($a_CheckAbsent) == 0)
                                            {
                                                ////Nếu Thời gian bắt đầu công tác nhỏ nhất các PCT dưới 9h////
                                                if($i_MinStartBusiness < $i_Time9Hours)
                                                {
                                                    ///Nếu Thời gian kết thúc công tác lớn nhất của các phiếu dưới 11h////
                                                    if($i_MaxEndBusiness < $i_Time11Hours)
                                                    {
                                                        if(isset($a_AllAbsent[$code][$key]))
                                                        {
                                                            $a_AllAbsent[$code][$key] .= ',v';
                                                        }
                                                        else $a_AllAbsent[$code][$key] = 'v';
                                                    }
                                                    ///Nếu Thời gian kết thúc công tác lớn nhất của các phiếu từ 11h -> 16h30////
                                                    else if($i_Time11Hours <= $i_MaxEndBusiness &&  $i_MaxEndBusiness < $i_Time1630Hours)
                                                    {
                                                        ////Chỉ xét vắng trong trường hợp là ko phải T7///
                                                        if($sz_Day != 'T7')
                                                        {
                                                            if(isset($a_AllAbsent[$code][$key]))
                                                            {
                                                                $a_AllAbsent[$code][$key] .= ',v/2';
                                                            }
                                                            else $a_AllAbsent[$code][$key] = 'v/2';
                                                        }
                                                        ////Nếu là T7 -> đủ công -> tạo comment///
                                                        else 
                                                        {
                                                            $a_AllAbsent[$code][$key.'_cmt'] = $sz_CommentBusiness; 
                                                            ///Xác định vi phạm đi muộn///
                                                            if($i_Time805Hours <= $i_MinStartBusiness && $i_MinStartBusiness < $i_Time810Hours)
                                                            {
                                                                $a_Late[$code][$key] = '5';
                                                            }
                                                            else if($i_Time810Hours <= $i_MinStartBusiness )
                                                            {
                                                                $a_Late[$code][$key] = '10';
                                                            }

                                                            ///Xác định vi phạm về sớm///
                                                            if($i_Time1150Hours <= $i_MaxEndBusiness && $i_MaxEndBusiness < $i_Time1155Hours)
                                                            {
                                                                $a_Late[$code][$key] = isset($a_Late[$code][$key])? $a_Late[$code][$key].'|5':'5';
                                                            }
                                                            else if($i_MaxEndBusiness < $i_Time1150Hours )
                                                            {
                                                                $a_Late[$code][$key] = isset($a_Late[$code][$key])?$a_Late[$code][$key].'|10':'10';
                                                            }
                                                        }
                                                    }
                                                    ////Tr hợp còn lại -> đủ công -> tạo cm///
                                                    else
                                                    {
                                                        $a_AllAbsent[$code][$key.'_cmt'] = $sz_CommentBusiness; 
                                                        ///Xác định vi phạm đi muộn///
                                                        if($i_Time805Hours <= $i_MinStartBusiness && $i_MinStartBusiness < $i_Time810Hours)
                                                        {
                                                            $a_Late[$code][$key] = '5';
                                                        }
                                                        if($i_Time810Hours <= $i_MinStartBusiness)
                                                        {
                                                            $a_Late[$code][$key] = '10';
                                                        }

                                                        ///Xác định vi phạm về sớm///
                                                        if($i_Time1720Hours <= $i_MaxEndBusiness && $i_MaxEndBusiness < $i_Time1725Hours)
                                                        {
                                                            $a_Late[$code][$key] = isset($a_Late[$code][$key])? $a_Late[$code][$key].'|5':'5';
                                                        }
                                                        else if($i_MaxEndBusiness < $i_Time1720Hours )
                                                        {
                                                            $a_Late[$code][$key] = isset($a_Late[$code][$key])?$a_Late[$code][$key].'|10':'10';
                                                        }
                                                    }
                                                }
                                                ////Nếu Thời gian bắt đầu công tác nhỏ nhất sau 9h -> Luôn là vắng ko lý do -> ko cần tạo cm////
                                                else
                                                {
                                                    ////Nếu Thời gian bắt đầu công tác nhỏ nhất trước 14h30////
                                                    if($i_MinStartBusiness < $i_Time1430Hours)
                                                    {
                                                        ////Nếu Thời gian kết thúc công tác lớn nhất trước 16h30///
                                                        if($i_MaxEndBusiness < $i_Time1630Hours)
                                                        {
                                                            if(isset($a_AllAbsent[$code][$key]))
                                                            {
                                                                $a_AllAbsent[$code][$key] .= ',v';  
                                                            }
                                                            else $a_AllAbsent[$code][$key] =  'v';
                                                        }
                                                        ////Nếu Thời gian kết thúc công tác lớn nhất từ 16h30 trở đi//// 
                                                        else
                                                        {
                                                            if(isset($a_AllAbsent[$code][$key]))
                                                            {
                                                                $a_AllAbsent[$code][$key] .= ',v/2';  
                                                            }
                                                            else $a_AllAbsent[$code][$key] = 'v/2';
                                                        }
                                                    }
                                                    ////Nếu Thời gian bắt đầu công tác nhỏ nhất sau 14h30///
                                                    else
                                                    {
                                                        if(isset($a_AllAbsent[$code][$key]))
                                                        {
                                                            $a_AllAbsent[$code][$key] .= ',v';  
                                                        }
                                                        else $a_AllAbsent[$code][$key] = 'v';
                                                    }
                                                }         
                                            }    

                                            ////Nếu có 1 đơn xin nghỉ nửa ngày -> Còn lại là PCT///
                                            else if(count($a_CheckAbsent) == 1)
                                            {
                                                ///Nếu đơn xin nghỉ chiều////
                                                if($a_ExTimeAbsent[0] == '12:00')
                                                {
                                                    ///Nếu thời gian bắt đầu công tác nhỏ nhất của các PCT hơn 9:00 hoặc thời gian kết thúc công tác lớn nhất của các PCT nhỏ hơn 11:00 -> v/2
                                                    if($i_MinStartBusiness >= $i_Time9Hours || $i_MaxEndBusiness < $i_Time11Hours)
                                                    {           
                                                        if(isset($a_AllAbsent[$code][$key]))
                                                        {
                                                            $a_AllAbsent[$code][$key] .= ',v/2';
                                                        }
                                                        else $a_AllAbsent[$code][$key] = 'v/2';  
                                                    }
                                                    else
                                                    {       
                                                        /////Xác định vi phạm đi muộn/////
                                                        if($i_Time805Hours <= $i_MinStartBusiness && $i_MinStartBusiness < $i_Time810Hours)
                                                        {
                                                            $a_Late[$code][$key] = '5';
                                                        }
                                                        else if($i_Time810Hours <= $i_MinStartBusiness)
                                                        {
                                                            $a_Late[$code][$key] = '10';
                                                        }

                                                        ///Xác định vi phạm về sớm///
                                                        if($i_Time1150Hours <= $i_MaxEndBusiness && $i_MaxEndBusiness < $i_Time1155Hours)
                                                        {
                                                            $a_Late[$code][$key] = isset($a_Late[$code][$key])? $a_Late[$code][$key].'|5':'5';
                                                        }
                                                        else if($i_MaxEndBusiness < $i_Time1150Hours )
                                                        {
                                                            $a_Late[$code][$key] = isset($a_Late[$code][$key])?$a_Late[$code][$key].'|10':'10';
                                                        }

                                                        if(isset($a_AllAbsent[$code][$key]))
                                                        {
                                                            $a_AllAbsent[$code][$key] .= ','.$a_Each1[$a_CheckAbsent[0]][0]; 
                                                            $a_AllAbsent[$code][$key.'_cmt'] .= '|Nghỉ chiều';
                                                        }
                                                        else
                                                        {
                                                            $a_AllAbsent[$code][$key] = $a_Each1[$a_CheckAbsent[0]][0]; 
                                                            $a_AllAbsent[$code][$key.'_cmt'] = 'Nghỉ chiều';
                                                        }
                                                    }
                                                }
                                                ////Nếu đơn xin nghỉ sáng////
                                                else
                                                {
                                                    ///Nếu thời gian bắt đầu công tác nhỏ nhất của các PCT hơn 14:30 hoặc thời gian kết thúc công tác lớn nhất của các PCT nhỏ hơn 16:30 -> v/2
                                                    if($i_MinStartBusiness >= $i_Time1430Hours || $i_MaxEndBusiness < $i_Time1630Hours)
                                                    {           
                                                        if(isset($a_AllAbsent[$code][$key]))
                                                        {
                                                            $a_AllAbsent[$code][$key] .= ',v/2';
                                                        }
                                                        else $a_AllAbsent[$code][$key] = 'v/2';     
                                                    }
                                                    else
                                                    {
                                                        /////Xác định vi phạm đi muộn/////
                                                        if($i_Time1335Hours <= $i_MinStartBusiness && $i_MinStartBusiness < $i_Time1340Hours)
                                                        {
                                                            $a_Late[$code][$key] = '5';
                                                        }
                                                        if($i_Time810Hours <= $i_MinStartBusiness )
                                                        {
                                                            $a_Late[$code][$key] = '10';
                                                        }

                                                        ///Xác định vi phạm về sớm///
                                                        if($i_Time1720Hours <= $i_MaxEndBusiness && $i_MaxEndBusiness < $i_Time1725Hours)
                                                        {
                                                            $a_Late[$code][$key] = isset($a_Late[$code][$key])? $a_Late[$code][$key].'|5':'5';
                                                        }
                                                        else if($i_MaxEndBusiness < $i_Time1720Hours)
                                                        {
                                                            $a_Late[$code][$key] = isset($a_Late[$code][$key])?$a_Late[$code][$key].'|10':'10';
                                                        }

                                                        if(isset($a_AllAbsent[$code][$key]))
                                                        {
                                                            $a_AllAbsent[$code][$key] .= ','.$a_Each1[$a_CheckAbsent[0]][0];
                                                            $a_AllAbsent[$code][$key.'_cmt'] .= '|Nghỉ sáng';
                                                        }
                                                        else {
                                                            $a_AllAbsent[$code][$key] = $a_Each1[$a_CheckAbsent[0]][0];
                                                            $a_AllAbsent[$code][$key.'_cmt'] = 'Nghỉ sáng';
                                                        }
                                                    }
                                                }
                                            }
                                            ////Nếu tất cả là đơn xin nghỉ nửa ngày////
                                            else 
                                            {
                                                if(isset($a_AllAbsent[$code][$key]))
                                                {
                                                    $a_AllAbsent[$code][$key] .= ','.$a_Each1[$a_CheckAbsent[0]][0];
                                                    $a_AllAbsent[$code][$key] .= ','.$a_Each1[$a_CheckAbsent[1]][0];  
                                                }
                                                else {
                                                    $a_AllAbsent[$code][$key] = $a_Each1[$a_CheckAbsent[0]][0].','.$a_Each1[$a_CheckAbsent[1]][0];
                                                }
                                            }    
                                        }

                                        ///Nếu ngày này chỉ có 1 đơn vắng mặt///
                                        else
                                        {
                                            $a_val = explode('|', $a_LeaveReport[$code]->$key);
                                            /////Nếu đơn là đi công tác///
                                            if($a_val[0] == 'ct')
                                            {
                                                $a_ExTimeAbsent = explode(' ', $a_val[3]); /// Tách theo dấu '' để xác định khoảng thời gian công tác của đơn////
                                                $i_StartBusiness = strtotime($sz_Date.' '.$a_ExTimeAbsent[0]); /// Thời gian bắt đầu công tác của Phiếu CT 
                                                $i_EndBusiness = strtotime($sz_Date.' '.$a_ExTimeAbsent[2]); /// Thời gian kết thúc công tác của Phiếu CT 
                                                ////Nếu Time bắt đầu công tác là dưới 9h////
                                                if($i_StartBusiness < $i_Time9Hours)
                                                {
                                                    ////Nếu Time kết thúc công tác là dưới 11h////
                                                    if($i_EndBusiness < $i_Time11Hours)
                                                    {
                                                        if(isset($a_AllAbsent[$code][$key]))
                                                        {
                                                            $a_AllAbsent[$code][$key] .= ',v';
                                                        }
                                                        else $a_AllAbsent[$code][$key] = 'v';
                                                    }
                                                    ///Nếu Time kết thúc công tác từ 11h -> 16h30////
                                                    else if($i_Time11Hours <= $i_EndBusiness &&  $i_EndBusiness < $i_Time1630Hours)
                                                    {
                                                        ////Chỉ cần xét trường hợp là ko phải T7///
                                                        if($sz_Day != 'T7')
                                                        {
                                                            if(isset($a_AllAbsent[$code][$key]))
                                                            {
                                                                $a_AllAbsent[$code][$key] .= ',v/2';
                                                            }
                                                            else $a_AllAbsent[$code][$key] = 'v/2';
                                                        }
                                                        ////Nếu là T7 -> đủ công -> tạo comment//
                                                        else
                                                        {
                                                            ///Xác định vi phạm đi muộn///
                                                            if($i_Time805Hours <= $i_StartBusiness && $i_StartBusiness < $i_Time810Hours)
                                                            {
                                                                $a_Late[$code][$key] = '5';
                                                            }
                                                            else if($i_Time810Hours <= $i_StartBusiness )
                                                            {
                                                                $a_Late[$code][$key] = '10';
                                                            }

                                                            ///Xác định vi phạm về sớm///
                                                            if($i_Time1150Hours <= $i_EndBusiness && $i_EndBusiness < $i_Time1155Hours)
                                                            {
                                                                $a_Late[$code][$key] = isset($a_Late[$code][$key])? $a_Late[$code][$key].'|5':'5';
                                                            }
                                                            else if($i_EndBusiness < $i_Time1150Hours )
                                                            {
                                                                $a_Late[$code][$key] = isset($a_Late[$code][$key])?$a_Late[$code][$key].'|10':'10';
                                                            }

                                                            $a_AllAbsent[$code][$key.'_cmt'] = $a_val[2].'. Thời gian: '.$a_val[3]; 
                                                        }
                                                    }
                                                    ////Tr hợp còn lại -> đủ công -> tạo cm///
                                                    else
                                                    {
                                                        ///Xác định vi phạm đi muộn///
                                                        if($i_Time805Hours <= $i_StartBusiness && $i_StartBusiness < $i_Time810Hours)
                                                        {
                                                            $a_Late[$code][$key] = '5';
                                                        }
                                                        else if($i_Time810Hours <= $i_StartBusiness )
                                                        {
                                                            $a_Late[$code][$key] = '10';
                                                        }

                                                        ///Xác định vi phạm về sớm///
                                                        if($i_Time1720Hours <= $i_EndBusiness && $i_EndBusiness < $i_Time1725Hours)
                                                        {
                                                            $a_Late[$code][$key] = isset($a_Late[$code][$key])? $a_Late[$code][$key].'|5':'5';
                                                        }
                                                        else if($i_EndBusiness < $i_Time1720Hours )
                                                        {
                                                            $a_Late[$code][$key] = isset($a_Late[$code][$key])?$a_Late[$code][$key].'|10':'10';
                                                        }

                                                        $a_AllAbsent[$code][$key.'_cmt'] =  $a_val[2].'. Thời gian: '.$a_val[3]; 
                                                    }
                                                }
                                                ////Nếu Time bắt đầu công tác sau 9h -> Ít nhất vắng nửa ngày ko phép -> ko cần tạo cm////
                                                else
                                                {
                                                    ////Nếu Time bắt đầu công tác trước 14h30////
                                                    if($i_StartBusiness < $i_Time1430Hours)
                                                    {
                                                        ////Nếu Time kết thúc công tác trước 16h30///
                                                        if($i_EndBusiness < $i_Time1630Hours)
                                                        {
                                                            if(isset($a_AllAbsent[$code][$key]))
                                                            {
                                                                $a_AllAbsent[$code][$key] .= ',v';  
                                                            }
                                                            else $a_AllAbsent[$code][$key] = 'v';
                                                        }
                                                        ////Nếu Time kết thúc công tác sau 16h30///
                                                        else
                                                        {
                                                            if(isset($a_AllAbsent[$code][$key]))
                                                            {
                                                                $a_AllAbsent[$code][$key] .= ',v/2';
                                                            }
                                                            else $a_AllAbsent[$code][$key] = 'v/2';  
                                                        }
                                                    }
                                                    ////Nếu Time bắt đầu công tác sau 14h30///
                                                    else
                                                    {
                                                        if(isset($a_AllAbsent[$code][$key]))
                                                        {
                                                            $a_AllAbsent[$code][$key] .= ',v';  
                                                        }
                                                        else $a_AllAbsent[$code][$key] = 'v';
                                                    }
                                                }       
                                            }
                                            ///Nếu là đơn xin nghỉ phép///
                                            else
                                            {
                                                ////Nếu là đơn xin nghỉ nửa ngày////
                                                if(($a_val[0] == 'p/2' || $a_val[0] == 'pn/2'))
                                                {
                                                    $a_ExTimeAbsent = explode(' ', $a_val[3]); /// Tách theo dấu '' của đơn để xấc định time bắt đầu nghỉ của đơn là sáng hay chiều///
                                                    ////Nếu ko phải T7///
                                                    if($sz_Day != 'T7')
                                                    {
                                                        if(isset($a_AllAbsent[$code][$key]))
                                                        {
                                                            $a_AllAbsent[$code][$key] .= ',v/2';  
                                                        }
                                                        else $a_AllAbsent[$code][$key] = 'v/2';
                                                    }
                                                    ////Nếu là T7///
                                                    else
                                                    {
                                                        if(isset($a_AllAbsent[$code][$key]))
                                                        {
                                                            $a_AllAbsent[$code][$key] .= $a_val[0] == 'p/2' ? 'p' : 'pn';
                                                            $a_AllAbsent[$code][$key.'_cmt'] .= $a_ExTimeAbsent[0] == '12:00'?'|Nghỉ chiều':'|Nghỉ sáng';
                                                        }
                                                        else {
                                                            $a_AllAbsent[$code][$key] = $a_val[0] == 'p/2' ? 'p' : 'pn';
                                                            $a_AllAbsent[$code][$key.'_cmt'] = $a_ExTimeAbsent[0] == '12:00'?'Nghỉ chiều':'Nghỉ sáng';
                                                        }
                                                    }
                                                }
                                                ////Nếu là đơn xin nghỉ cả ngày////
                                                else
                                                {
                                                    if(isset($a_AllAbsent[$code][$key]))
                                                    {
                                                        $a_AllAbsent[$code][$key] .= $a_val[0];
                                                    }
                                                    else {
                                                        $a_AllAbsent[$code][$key] = $a_val[0];
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                                //// Nếu ngày này có giá trị --> Có chấm công///
                                else
                                {  
                                    $a_ExVal = explode('|', $val);
                                    $i_CheckIn = strtotime($sz_Date.' '.$a_ExVal[0]); /// Lấy giờ Check In//
                                    ////Nếu có chấm đủ ra + vào///
                                    if($a_ExVal[1] != '')
                                    { 
                                        $i_CheckOut = strtotime($sz_Date.' '.$a_ExVal[1]); /// Lấy giờ Check Out///

                                        ///Xét bảng tổng hợp Report, nếu ngày này không có giá trị -> không có đơn vắng mặt///
                                        if((array_key_exists($code,$a_LeaveReport) && $a_LeaveReport[$code]->$key == '') || !array_key_exists($code,$a_LeaveReport))
                                        {
                                            ///Nếu Check In dưới 9:00///
                                            if($i_CheckIn < $i_Time9Hours)
                                            {      
                                                ///Xác định vi phạm đi muộn//
                                                if($i_Time805Hours <= $i_CheckIn && $i_CheckIn < $i_Time810Hours)
                                                {
                                                    $a_Late[$code][$key] = '5';
                                                }
                                                else if($i_Time810Hours <= $i_CheckIn)
                                                {
                                                    $a_Late[$code][$key] = '10';
                                                }

                                                ///Nếu Check Out dưới 11h////
                                                if($i_CheckOut < $i_Time11Hours)
                                                {
                                                    if(isset($a_AllAbsent[$code][$key]))
                                                    {
                                                        $a_AllAbsent[$code][$key] .= ',v';
                                                    }
                                                    else $a_AllAbsent[$code][$key] = 'v';
                                                }
                                                ///Nếu Check Out từ 11h -> 16h30////
                                                else if($i_Time11Hours <= $i_CheckOut &&  $i_CheckOut < $i_Time1630Hours)
                                                {
                                                    ////Nếu ko phải T7 thì là v/2///
                                                    if($sz_Day != 'T7')
                                                    {
                                                        if(isset($a_AllAbsent[$code][$key]))
                                                        {
                                                            $a_AllAbsent[$code][$key] .= ',v/2';
                                                        }
                                                        else $a_AllAbsent[$code][$key] = 'v/2';
                                                    }
                                                    ///Nếu là T7 thì sẽ tính toán có đi muộn hay về sớm ko?///
                                                    else
                                                    {
                                                        ///Xác định vi phạm về sớm///
                                                        if($i_Time1150Hours <= $i_CheckOut && $i_CheckOut < $i_Time1155Hours)
                                                        {
                                                            $a_Late[$code][$key] = isset($a_Late[$code][$key])? $a_Late[$code][$key].'|5':'5';
                                                        }
                                                        else if($i_CheckOut < $i_Time1150Hours )
                                                        {
                                                            $a_Late[$code][$key] = isset($a_Late[$code][$key])?$a_Late[$code][$key].'|10':'10';
                                                        }   
                                                    }
                                                }
                                                ///Check Out sau 16h30 thì sẽ tính toán vi phạm về sớm////
                                                else if($i_CheckOut >= $i_Time1630Hours){
                                                    if($i_Time1720Hours <= $i_CheckOut && $i_CheckOut < $i_Time1725Hours)
                                                    {
                                                        $a_Late[$code][$key] = isset($a_Late[$code][$key])? $a_Late[$code][$key].'|5':'5';
                                                    }
                                                    else if($i_CheckOut < $i_Time1720Hours )
                                                    {
                                                        $a_Late[$code][$key] = isset($a_Late[$code][$key])?$a_Late[$code][$key].'|10':'10';
                                                    } 
                                                }
                                            }
                                            ////Nếu Check in sau 9h-> vắng ko lý do////
                                            else
                                            {
                                                ////Nếu Check in trước 14h30////
                                                if($i_CheckIn < $i_Time1430Hours)
                                                {
                                                    ////Nếu Check Out trước 16h30///
                                                    if($i_CheckOut < $i_Time1630Hours)
                                                    {
                                                        if(isset($a_AllAbsent[$code][$key]))
                                                        {
                                                            $a_AllAbsent[$code][$key] .= ',v';  
                                                        }
                                                        else $a_AllAbsent[$code][$key] = 'v';
                                                    }
                                                    ///Nếu Check Out sau 16h30//
                                                    else
                                                    {
                                                        if(isset($a_AllAbsent[$code][$key]))
                                                        {
                                                            $a_AllAbsent[$code][$key] .= ',v/2';  
                                                        }
                                                        else $a_AllAbsent[$code][$key] = 'v/2';
                                                    }
                                                }
                                                ////Nếu Check In sau 14h30///
                                                else
                                                {
                                                    if(isset($a_AllAbsent[$code][$key]))
                                                    {
                                                        $a_AllAbsent[$code][$key] = ',v';  
                                                    }
                                                    else $a_AllAbsent[$code][$key] = 'v';
                                                }
                                            }
                                        }
                                        ///Xét bảng tổng hợp Report, nếu ngày này có giá trị -> có đơn vắng mặt///
                                        else
                                        {    
                                            ////Nếu có từ 2 đơn vắng mặt trở lên///
                                            if (strpos($a_LeaveReport[$code]->$key, '&&&') !== false)
                                            {    
                                                $a_Each = explode('&&&', $a_LeaveReport[$code]->$key);
                                                foreach ($a_Each as $sz_Each) 
                                                {
                                                    $a_Each1[] = explode('|', $sz_Each);
                                                }

                                                $sz_Comment = ''; ///Comment nghỉ nửa ngày
                                                $sz_CommentBusiness = ''; ///Comment PCT
                                                $a_CheckAbsent =  array(); ///Mảng lưu tất cả các đơn là nghỉ nửa ngày, lưu theo key của mảng $a_Each1 ////
                                                $a_StartBusiness =  array(); /// Mảng lưu tất cả thời gian bắt đầu công tác của tất cả Phiếu CT trong ngày
                                                $a_EndBusiness = array(); /// Mảng lưu tất cả thời gian kết thúc công tác của tất cả Phiếu CT trong ngày
                                                foreach ($a_Each1 as $i_key => $a_InfoAbsent) 
                                                { 
                                                    if(in_array($a_InfoAbsent[0], array('p/2','pn/2')))
                                                    {
                                                        $a_CheckAbsent[] = $i_key; 
                                                        $a_ExTimeAbsent = explode(' ', $a_InfoAbsent[3]); /// Tách theo dấu '' của đơn để xấc định time bắt đầu nghỉ của đơn là sáng hay chiều///
                                                        if($a_ExTimeAbsent[0] == '12:00') $sz_Comment = $sz_Comment == ''?'Nghỉ chiều':'|Nghỉ chiều';
                                                        else $sz_Comment = $sz_Comment == ''?'Nghỉ sáng':'|Nghỉ sáng';
                                                    }
                                                    ///Với các đơn còn lại sẽ là Phiếu công tác///
                                                    else
                                                    {
                                                        $sz_CommentBusiness .=  $sz_CommentBusiness == ''?$a_InfoAbsent[2].'. Thời gian: '.$a_InfoAbsent[3]:'|'.$a_InfoAbsent[2].'. Thời gian: '.$a_InfoAbsent[3];
                                                        $a_ExAbsentBusiness = explode(' ', $a_InfoAbsent[3]); /// Tách theo dấu '' để xác định khoảng thời gian công tác của đơn////
                                                        $a_StartBusiness[] = strtotime($sz_Date.' '.$a_ExAbsentBusiness[0]); /// Mảng lưu tất cả thời gian bắt đầu công tác của tất cả Phiếu CT trong ngày
                                                        $a_EndBusiness[] = strtotime($sz_Date.' '.$a_ExAbsentBusiness[2]); /// Mảng lưu tất cả thời gian kết thúc công tác của tất cả Phiếu CT trong ngày
                                                        $i_MinStartBusiness = min($a_StartBusiness); /// Lấy Thời gian bắt đầu công tác nhỏ nhất của các phiếu///
                                                        $i_MaxEndBusiness = max($a_EndBusiness); /// Lấy Thời gian kết thúc công tác lớn nhất của các phiếu///  
                                                    }
                                                }
                                                ///Nếu không có đơn xin nghỉ nửa ngày nào -> Tất cả là PCT///
                                                if(count($a_CheckAbsent) == 0)
                                                {
                                                    $i_CheckIn = $i_MinStartBusiness < $i_CheckIn?$i_MinStartBusiness:$i_CheckIn;
                                                    $i_CheckOut = $i_MaxEndBusiness > $i_CheckOut?$i_MaxEndBusiness:$i_CheckOut;

                                                    ////Nếu Check In dưới 9h////
                                                    if($i_CheckIn < $i_Time9Hours)
                                                    {
                                                        ///Nếu Check Out dưới 11h////
                                                        if($i_CheckOut < $i_Time11Hours)
                                                        {
                                                            if(isset($a_AllAbsent[$code][$key]))
                                                            {
                                                                $a_AllAbsent[$code][$key] .= ',v';
                                                            }
                                                            else $a_AllAbsent[$code][$key] = 'v';
                                                        }
                                                        ///Nếu Check Out từ 11h -> 16h30////
                                                        else if($i_Time11Hours <= $i_CheckOut &&  $i_CheckOut < $i_Time1630Hours)
                                                        {
                                                            ////Nếu ko phải T7///
                                                            if($sz_Day != 'T7')
                                                            {
                                                                if(isset($a_AllAbsent[$code][$key]))
                                                                {
                                                                    $a_AllAbsent[$code][$key] .= ',v/2';
                                                                }
                                                                else $a_AllAbsent[$code][$key] = 'v/2';
                                                            }
                                                            ////Nếu là T7 -> đủ công -> tạo comment///
                                                            else 
                                                            {
                                                                ///Xác định vi phạm đi muộn///
                                                                if($i_Time805Hours <= $i_CheckIn && $i_CheckIn < $i_Time810Hours)
                                                                {
                                                                    $a_Late[$code][$key] = '5';
                                                                }
                                                                else if($i_Time810Hours <= $i_CheckIn )
                                                                {
                                                                    $a_Late[$code][$key] = '10';
                                                                }

                                                                ///Xác định vi phạm về sớm///
                                                                if($i_Time1150Hours <= $i_CheckOut && $i_CheckOut < $i_Time1155Hours)
                                                                {
                                                                    $a_Late[$code][$key] = isset($a_Late[$code][$key])? $a_Late[$code][$key].'|5':'5';
                                                                }
                                                                else if($i_CheckOut < $i_Time1150Hours)
                                                                {
                                                                    $a_Late[$code][$key] = isset($a_Late[$code][$key])?$a_Late[$code][$key].'|10':'10';
                                                                }

                                                                $a_AllAbsent[$code][$key.'_cmt'] = $sz_CommentBusiness; 
                                                            }
                                                        }
                                                        ////Tr hợp còn lại -> đủ công -> tạo cm///
                                                        else
                                                        {
                                                            ///Xác định vi phạm đi muộn///
                                                            if($i_Time805Hours <= $i_CheckIn && $i_CheckIn < $i_Time810Hours)
                                                            {
                                                                $a_Late[$code][$key] = '5';
                                                            }
                                                            else if($i_Time810Hours <= $i_CheckIn )
                                                            {
                                                                $a_Late[$code][$key] = '10';
                                                            }

                                                            ///Xác định vi phạm về sớm///
                                                            if($i_Time1720Hours <= $i_CheckOut && $i_CheckOut < $i_Time1725Hours)
                                                            {
                                                                $a_Late[$code][$key] = isset($a_Late[$code][$key])? $a_Late[$code][$key].'|5':'5';
                                                            }
                                                            else if($i_CheckOut < $i_Time1720Hours)
                                                            {
                                                                $a_Late[$code][$key] = isset($a_Late[$code][$key])?$a_Late[$code][$key].'|10':'10';
                                                            }

                                                            $a_AllAbsent[$code][$key.'_cmt'] = $sz_CommentBusiness; 
                                                        }
                                                    }
                                                    ////Nếu Check in sau 9h -> Luôn là vắng ko lý do -> ko cần tạo cm////
                                                    else
                                                    {
                                                        ////Nếu Check in trước 14h30////
                                                        if($i_CheckIn < $i_Time1430Hours)
                                                        {
                                                            ////Nếu Check Out trước 16h30///
                                                            if($i_CheckOut < $i_Time1630Hours)
                                                            {
                                                                if(isset($a_AllAbsent[$code][$key]))
                                                                {
                                                                    $a_AllAbsent[$code][$key] .= ',v';  
                                                                }
                                                                else $a_AllAbsent[$code][$key] =  'v';
                                                            }
                                                            ////Nếu Check Out từ 16h30 trở đi//// 
                                                            else
                                                            {
                                                                if(isset($a_AllAbsent[$code][$key]))
                                                                {
                                                                    $a_AllAbsent[$code][$key] .= ',v/2';  
                                                                }
                                                                else $a_AllAbsent[$code][$key] = 'v/2';
                                                            }
                                                        }
                                                        ////Nếu Check In sau 14h30///
                                                        else
                                                        {
                                                            if(isset($a_AllAbsent[$code][$key]))
                                                            {
                                                                $a_AllAbsent[$code][$key] .= ',v';  
                                                            }
                                                            else $a_AllAbsent[$code][$key] = 'v';
                                                        }
                                                    }         
                                                }

                                                ////Nếu có 1 đơn xin nghỉ nửa ngày -> Còn lại là PCT///
                                                else if(count($a_CheckAbsent) == 1)
                                                {
                                                    $i_CheckIn = $i_MinStartBusiness < $i_CheckIn?$i_MinStartBusiness:$i_CheckIn;
                                                    $i_CheckOut = $i_MaxEndBusiness > $i_CheckOut?$i_MaxEndBusiness:$i_CheckOut;

                                                    ///Nếu đơn xin nghỉ chiều////
                                                    if($a_ExTimeAbsent[0] == '12:00')
                                                    {
                                                        ///Nếu Check In lớn hơn 9:00 hoặc Check Out nhỏ hơn 11:00 -> v/2
                                                        if($i_CheckIn >= strtotime($sz_Date.' 09:00') || $i_CheckOut < strtotime($sz_Date.' 11:00'))
                                                        {           
                                                            if(isset($a_AllAbsent[$code][$key]))
                                                            {
                                                                $a_AllAbsent[$code][$key] .= ',v/2';
                                                            }
                                                            else $a_AllAbsent[$code][$key] = 'v/2';  
                                                        }
                                                        else
                                                        {             
                                                            ///Xác định vi phạm đi muộn///
                                                            if($i_Time805Hours <= $i_CheckIn && $i_CheckIn < $i_Time810Hours)
                                                            {
                                                                $a_Late[$code][$key] = '5';
                                                            }
                                                            else if($i_Time810Hours <= $i_CheckIn )
                                                            {
                                                                $a_Late[$code][$key] = '10';
                                                            }

                                                            ///Xác định vi phạm về sớm///
                                                            if($i_Time1150Hours <= $i_CheckOut && $i_CheckOut < $i_Time1155Hours)
                                                            {
                                                                $a_Late[$code][$key] = isset($a_Late[$code][$key])? $a_Late[$code][$key].'|5':'5';
                                                            }
                                                            else if($i_CheckOut < $i_Time1150Hours )
                                                            {
                                                                $a_Late[$code][$key] = isset($a_Late[$code][$key])?$a_Late[$code][$key].'|10':'10';
                                                            }

                                                            if(isset($a_AllAbsent[$code][$key]))
                                                            {
                                                                $a_AllAbsent[$code][$key] .= ','.$a_Each1[$a_CheckAbsent[0]][0]; 
                                                                $a_AllAbsent[$code][$key.'_cmt'] .= '|Nghỉ chiều';
                                                            }
                                                            else
                                                            {
                                                                $a_AllAbsent[$code][$key] = $a_Each1[$a_CheckAbsent[0]][0]; 
                                                                $a_AllAbsent[$code][$key.'_cmt'] = 'Nghỉ chiều';
                                                            }
                                                        }
                                                    }
                                                    ////Nếu đơn xin nghỉ sáng////
                                                    else
                                                    {
                                                        ///Nếu Check In lớn hơn 14:30 hoặc Check Out nhỏ 16:30 -> v/2
                                                        if($i_CheckIn >= strtotime($sz_Date.' 14:30') || $i_CheckOut < strtotime($sz_Date.' 16:30'))
                                                        {           
                                                            if(isset($a_AllAbsent[$code][$key]))
                                                            {
                                                                $a_AllAbsent[$code][$key] .= ',v/2';
                                                            }
                                                            else $a_AllAbsent[$code][$key] = 'v/2';     
                                                        }
                                                        else
                                                        {
                                                            //Xác định vi phạm đi muộn///
                                                            if($i_Time1335Hours <= $i_CheckIn && $i_CheckIn < $i_Time1340Hours)
                                                            {
                                                                $a_Late[$code][$key] = '5';
                                                            }
                                                            else if($i_Time1340Hours <= $i_CheckIn )
                                                            {
                                                                $a_Late[$code][$key] = '10';
                                                            }

                                                            ///Xác định vi phạm về sớm///
                                                            if($i_Time1720Hours <= $i_CheckOut && $i_CheckOut < $i_Time1725Hours)
                                                            {
                                                                $a_Late[$code][$key] = isset($a_Late[$code][$key])? $a_Late[$code][$key].'|5':'5';
                                                            }
                                                            else if($i_CheckOut < $i_Time1720Hours )
                                                            {
                                                                $a_Late[$code][$key] = isset($a_Late[$code][$key])?$a_Late[$code][$key].'|10':'10';
                                                            }

                                                            if(isset($a_AllAbsent[$code][$key]))
                                                            {
                                                                $a_AllAbsent[$code][$key] .= ','.$a_Each1[$a_CheckAbsent[0]][0];
                                                                $a_AllAbsent[$code][$key.'_cmt'] .= '|Nghỉ sáng';
                                                            }
                                                            else {
                                                                $a_AllAbsent[$code][$key] = $a_Each1[$a_CheckAbsent[0]][0];
                                                                $a_AllAbsent[$code][$key.'_cmt'] = 'Nghỉ sáng';
                                                            }
                                                        }
                                                    }
                                                }

                                                ////Nếu tất cả là đơn xin nghỉ nửa ngày////
                                                else 
                                                {
                                                    if(isset($a_AllAbsent[$code][$key]))
                                                    {
                                                        $a_AllAbsent[$code][$key] .= ','.$a_Each1[$a_CheckAbsent[0]][0];
                                                        $a_AllAbsent[$code][$key] .= ','.$a_Each1[$a_CheckAbsent[1]][0];  
                                                    }
                                                    else {
                                                        $a_AllAbsent[$code][$key] = $a_Each1[$a_CheckAbsent[0]][0].','.$a_Each1[$a_CheckAbsent[1]][0];
                                                    }
                                                }
                                            }

                                            ///Nếu ngày này chỉ có 1 đơn vắng mặt///
                                            else
                                            {
                                                $a_val = explode('|', $a_LeaveReport[$code]->$key);
                                                /////Nếu đơn là đi công tác///
                                                if($a_val[0] == 'ct')
                                                {
                                                    $a_ExTimeAbsent = explode(' ', $a_val[3]); /// Tách theo dấu '' để xác định khoảng thời gian công tác của đơn////
                                                    $i_StartBusiness = strtotime($sz_Date.' '.$a_ExTimeAbsent[0]); /// Thời gian bắt đầu công tác của Phiếu CT 
                                                    $i_EndBusiness = strtotime($sz_Date.' '.$a_ExTimeAbsent[2]); /// Thời gian kết thúc công tác của Phiếu CT 

                                                    $i_CheckIn = $i_StartBusiness < $i_CheckIn?$i_StartBusiness:$i_CheckIn;
                                                    $i_CheckOut = $i_EndBusiness > $i_CheckOut?$i_EndBusiness:$i_CheckOut;

                                                    ////Nếu Check In dưới 9h////
                                                    if($i_CheckIn < $i_Time9Hours)
                                                    {
                                                        ///Nếu Check Out dưới 11h////
                                                        if($i_CheckOut < $i_Time11Hours)
                                                        {
                                                            if(isset($a_AllAbsent[$code][$key]))
                                                            {
                                                                $a_AllAbsent[$code][$key] .= ',v';
                                                            }
                                                            else $a_AllAbsent[$code][$key] = 'v';
                                                        }
                                                        ///Nếu Check Out từ 11h -> 16h30////
                                                        else if($i_Time11Hours <= $i_CheckOut &&  $i_CheckOut < $i_Time1630Hours)
                                                        {
                                                            ////Nếu ko phải T7///
                                                            if($sz_Day != 'T7')
                                                            {
                                                                if(isset($a_AllAbsent[$code][$key]))
                                                                {
                                                                    $a_AllAbsent[$code][$key] .= ',v/2';
                                                                }
                                                                else $a_AllAbsent[$code][$key] = 'v/2';
                                                            }
                                                            ////Nếu là T7 -> đủ công -> tạo comment///
                                                            else 
                                                            {
                                                                ///Xác định vi phạm đi muộn///
                                                                if($i_Time805Hours <= $i_CheckIn && $i_CheckIn < $i_Time810Hours)
                                                                {
                                                                    $a_Late[$code][$key] = '5';
                                                                }
                                                                else if($i_Time810Hours <= $i_CheckIn )
                                                                {
                                                                    $a_Late[$code][$key] = '10';
                                                                }

                                                                ///Xác định vi phạm về sớm///
                                                                if($i_Time1150Hours <= $i_CheckOut && $i_CheckOut < $i_Time1155Hours)
                                                                {
                                                                    $a_Late[$code][$key] = isset($a_Late[$code][$key])? $a_Late[$code][$key].'|5':'5';
                                                                }
                                                                else if($i_CheckOut < $i_Time1150Hours )
                                                                {
                                                                    $a_Late[$code][$key] = isset($a_Late[$code][$key])?$a_Late[$code][$key].'|10':'10';
                                                                }

                                                                $a_AllAbsent[$code][$key.'_cmt'] = $a_val[2].'. Thời gian: '.$a_val[3]; 
                                                            }
                                                        }
                                                        ////Tr hợp còn lại -> đủ công -> tạo cm///
                                                        else
                                                        {
                                                            ///Xác định vi phạm đi muộn///
                                                            if($i_Time805Hours <= $i_CheckIn && $i_CheckIn < $i_Time810Hours)
                                                            {
                                                                $a_Late[$code][$key] = '5';
                                                            }
                                                            else if($i_Time810Hours <= $i_CheckIn )
                                                            {
                                                                $a_Late[$code][$key] = '10';
                                                            }

                                                            ///Xác định vi phạm về sớm///
                                                            if($i_Time1720Hours <= $i_CheckOut && $i_CheckOut < $i_Time1725Hours)
                                                            {
                                                                $a_Late[$code][$key] = isset($a_Late[$code][$key])? $a_Late[$code][$key].'|5':'5';
                                                            }
                                                            else if($i_CheckOut < $i_Time1720Hours )
                                                            {
                                                                $a_Late[$code][$key] = isset($a_Late[$code][$key])?$a_Late[$code][$key].'|10':'10';
                                                            }

                                                            $a_AllAbsent[$code][$key.'_cmt'] = $a_val[2].'. Thời gian: '.$a_val[3]; 
                                                        }
                                                    }
                                                    ////Nếu Check in sau 9h -> Luôn là vắng ko lý do -> ko cần tạo cm////
                                                    else
                                                    {
                                                        ////Nếu Check in trước 14h30////
                                                        if($i_CheckIn < $i_Time1430Hours)
                                                        {
                                                            ////Nếu Check Out trước 16h30///
                                                            if($i_CheckOut < $i_Time1630Hours)
                                                            {
                                                                if(isset($a_AllAbsent[$code][$key]))
                                                                {
                                                                    $a_AllAbsent[$code][$key] .= ',v';  
                                                                }
                                                                else $a_AllAbsent[$code][$key] =  'v';
                                                            }
                                                            ////Nếu Check Out từ 16h30 trở đi//// 
                                                            else
                                                            {
                                                                if(isset($a_AllAbsent[$code][$key]))
                                                                {
                                                                    $a_AllAbsent[$code][$key] .= ',v/2';  
                                                                }
                                                                else $a_AllAbsent[$code][$key] = 'v/2';
                                                            }
                                                        }
                                                        ////Nếu Check In sau 14h30///
                                                        else
                                                        {
                                                            if(isset($a_AllAbsent[$code][$key]))
                                                            {
                                                                $a_AllAbsent[$code][$key] .= ',v';  
                                                            }
                                                            else $a_AllAbsent[$code][$key] =  'v';
                                                        }
                                                    }     
                                                }
                                                ///Nếu là đơn xin nghỉ phép///
                                                else
                                                {
                                                    ////Nếu là đơn xin nghỉ nửa ngày////
                                                    if(($a_val[0] == 'p/2' || $a_val[0] == 'pn/2'))
                                                    {
                                                        $a_ExTimeAbsent = explode(' ', $a_val[3]); /// Tách theo dấu '' của đơn để xấc định time bắt đầu nghỉ của đơn là sáng hay chiều///

                                                        ///////Nếu đơn nghỉ chiều////
                                                        if($a_ExTimeAbsent[0] == '12:00')
                                                        {
                                                            ///Nếu Check In lớn hơn 9:00 hoặc Check Out nhỏ hơn 11:00 -> v/2
                                                            if($i_CheckIn >= strtotime($sz_Date.' 09:00') || $i_CheckOut < strtotime($sz_Date.' 11:00'))
                                                            {           
                                                                if(isset($a_AllAbsent[$code][$key]))
                                                                {
                                                                    $a_AllAbsent[$code][$key] .= ',v/2';
                                                                }
                                                                else $a_AllAbsent[$code][$key] = 'v/2';  
                                                            }
                                                            else
                                                            {  
                                                                //Xác định vi phạm đi muộn//
                                                                if($i_Time805Hours <= $i_CheckIn && $i_CheckIn < $i_Time810Hours)
                                                                {
                                                                    $a_Late[$code][$key] = '5';
                                                                }
                                                                else if($i_Time810Hours <= $i_CheckIn)
                                                                {
                                                                    $a_Late[$code][$key] = '10';
                                                                }

                                                                ///Xác định vi phạm về sớm///
                                                                if($i_Time1150Hours <= $i_CheckOut && $i_CheckOut < $i_Time1155Hours)
                                                                {
                                                                    $a_Late[$code][$key] = isset($a_Late[$code][$key])? $a_Late[$code][$key].'|5':'5';
                                                                }
                                                                else if($i_CheckOut < $i_Time1150Hours )
                                                                {
                                                                    $a_Late[$code][$key] = isset($a_Late[$code][$key])?$a_Late[$code][$key].'|10':'10';
                                                                }

                                                                if(isset($a_AllAbsent[$code][$key]))
                                                                {
                                                                    $a_AllAbsent[$code][$key] .= ','.$a_val[0];
                                                                    $a_AllAbsent[$code][$key.'_cmt'] .= '|Nghỉ chiều';
                                                                }
                                                                else
                                                                {
                                                                    $a_AllAbsent[$code][$key] = $a_val[0]; 
                                                                    $a_AllAbsent[$code][$key.'_cmt'] = 'Nghỉ chiều';
                                                                }
                                                            }  
                                                        }
                                                        ///Nếu đơn nghỉ sáng///
                                                        else if($a_ExTimeAbsent[0] == '00:00')
                                                        {
                                                            ///Nếu không phải T7///
                                                            if($sz_Day != 'T7')
                                                            {
                                                                if($i_CheckIn >= $i_Time1430Hours || $i_CheckOut < $i_Time1630Hours)
                                                                {           
                                                                    if(isset($a_AllAbsent[$code][$key]))
                                                                    {
                                                                        $a_AllAbsent[$code][$key] .= ',v/2';
                                                                    }
                                                                    else $a_AllAbsent[$code][$key] = 'v/2';     
                                                                }
                                                                else
                                                                {
                                                                    ///Xác định vi phạm đi muộn//
                                                                    if($i_Time1335Hours <= $i_CheckIn && $i_CheckIn < $i_Time1340Hours)
                                                                    {
                                                                        $a_Late[$code][$key] = '5';
                                                                    }
                                                                    else if($i_Time1340Hours <= $i_CheckIn)
                                                                    {
                                                                        $a_Late[$code][$key] = '10';
                                                                    }

                                                                    ///Xác định vi phạm về sớm///
                                                                    if($i_Time1720Hours <= $i_CheckOut && $i_CheckOut < $i_Time1725Hours)
                                                                    {
                                                                        $a_Late[$code][$key] = isset($a_Late[$code][$key])? $a_Late[$code][$key].'|5':'5';
                                                                    }
                                                                    else if($i_CheckOut < $i_Time1720Hours )
                                                                    {
                                                                        $a_Late[$code][$key] = isset($a_Late[$code][$key])?$a_Late[$code][$key].'|10':'10';
                                                                    }

                                                                    if(isset($a_AllAbsent[$code][$key]))
                                                                    {
                                                                        $a_AllAbsent[$code][$key] .= ','.$a_val[0];
                                                                        $a_AllAbsent[$code][$key.'_cmt'] .= '|Nghỉ sáng';
                                                                    }
                                                                    else {
                                                                        $a_AllAbsent[$code][$key] = $a_val[0]; 
                                                                        $a_AllAbsent[$code][$key.'_cmt'] = 'Nghỉ sáng';
                                                                    }
                                                                }
                                                            }
                                                            ///Nếu là T7///
                                                            else
                                                            {
                                                                if(isset($a_AllAbsent[$code][$key]))
                                                                {
                                                                    
                                                                    $a_AllAbsent[$code][$key] .= ','.$a_val[0] == 'p/2' ? 'p' : 'pn';
                                                                    $a_AllAbsent[$code][$key.'_cmt'] .= '|Nghỉ sáng';
                                                                }
                                                                else {
                                                                    $a_AllAbsent[$code][$key] = $a_val[0] == 'p/2' ? 'p' : 'pn'; 
                                                                    $a_AllAbsent[$code][$key.'_cmt'] = 'Nghỉ sáng';
                                                                }
                                                            } 
                                                        }
                                                    }
                                                    ////Nếu là đơn xin nghỉ cả ngày////
                                                    else
                                                    {
                                                        if(isset($a_AllAbsent[$code][$key]))
                                                        {
                                                            $a_AllAbsent[$code][$key] .= $a_val[0];
                                                        }
                                                        else $a_AllAbsent[$code][$key] = $a_val[0];
                                                    }
                                                }
                                            }
                                        }
                                    }
                                    ////Nếu chỉ chấm 1 lần///
                                    else
                                    {       
                                        ///Xét bảng tổng hợp Report, nếu ngày này không có giá trị -> không có đơn vắng mặt///
                                        if((array_key_exists($code,$a_LeaveReport) && $a_LeaveReport[$code]->$key == '') || !array_key_exists($code,$a_LeaveReport))
                                        {
                                            ////Nếu Check In dưới 9h => tr///
                                            if($i_CheckIn < $i_Time9Hours)
                                            {
                                                if(isset($a_AllAbsent[$code][$key]))
                                                {
                                                    $a_AllAbsent[$code][$key] .= 'kr';
                                                }
                                                else $a_AllAbsent[$code][$key] = 'kr';
                                            }
                                            ///Nếu Check In sau 9h////
                                            else{
                                                ///Nếu là T7 thì sẽ xét mốc 11h trưa///
                                                if($sz_Day == 'T7'){
                                                    if ($i_Time9Hours <= $i_CheckIn && $i_CheckIn < $i_Time11Hours)
                                                    {
                                                        if(isset($a_AllAbsent[$code][$key]))
                                                        {
                                                            $a_AllAbsent[$code][$key] .= 'v';
                                                        }
                                                        else $a_AllAbsent[$code][$key] = 'v';
                                                    }
                                                    ////Nếu Check In sau 11 => tv////
                                                    else
                                                    {
                                                        if(isset($a_AllAbsent[$code][$key]))
                                                        {
                                                            $a_AllAbsent[$code][$key] .= 'kv';
                                                        }
                                                        else $a_AllAbsent[$code][$key] = 'kv';
                                                    }
                                                }
                                                ///Nếu là ngày thường sẽ xét mốc 16h30//
                                                else{
                                                    ////Nếu Check in trong khoảng từ 9h tới 16h30 => v///
                                                    if ($i_Time9Hours <= $i_CheckIn && $i_CheckIn < $i_Time1630Hours)
                                                    {
                                                        if(isset($a_AllAbsent[$code][$key]))
                                                        {
                                                            $a_AllAbsent[$code][$key] .= 'v';
                                                        }
                                                        else $a_AllAbsent[$code][$key] = 'v';
                                                    }
                                                    ////Nếu Check In sau 16h30 => tv////
                                                    else
                                                    {
                                                        if(isset($a_AllAbsent[$code][$key]))
                                                        {
                                                            $a_AllAbsent[$code][$key] .= 'kv';
                                                        }
                                                        else $a_AllAbsent[$code][$key] = 'kv';
                                                    }
                                                }
                                            }
                                        }
                                        ////Nếu ngày này có đơn vắng mặt////
                                        else
                                        {
                                            /////Nếu ngày này từ 2 đơn vắng mặt trở lên///
                                            if (strpos($a_LeaveReport[$code]->$key, '&&&') !== false)
                                            {      
                                                $a_Each = explode('&&&', $a_LeaveReport[$code]->$key);
                                                foreach ($a_Each as $sz_Each) 
                                                {
                                                    $a_Each1[] = explode('|', $sz_Each);
                                                }
                                                $sz_Comment = ''; ///Comment nghỉ nửa ngày
                                                $sz_CommentBusiness = ''; ///Comment PCT
                                                $a_CheckAbsent =  array(); ///Mảng lưu tất cả các đơn là nghỉ nửa ngày, lưu theo key của mảng $a_Each1 ////
                                                $a_StartBusiness =  array(); /// Mảng lưu tất cả thời gian bắt đầu công tác của tất cả Phiếu CT trong ngày
                                                $a_EndBusiness = array(); /// Mảng lưu tất cả thời gian kết thúc công tác của tất cả Phiếu CT trong ngày

                                                foreach ($a_Each1 as $i_key => $a_InfoAbsent) 
                                                { 
                                                    if(in_array($a_InfoAbsent[0], array('p/2','pn/2')))
                                                    {
                                                        $a_CheckAbsent[] = $i_key; 
                                                        $a_ExTimeAbsent = explode(' ', $a_InfoAbsent[3]); /// Tách theo dấu '' của đơn để xấc định time bắt đầu nghỉ của đơn là sáng hay chiều///
                                                        if($a_ExTimeAbsent[0] == '12:00') $sz_Comment = $sz_Comment == ''?'Nghỉ chiều':'|Nghỉ chiều';
                                                        else $sz_Comment = $sz_Comment == ''?'Nghỉ sáng':'|Nghỉ sáng';
                                                    }
                                                    ///Với các đơn còn lại sẽ là Phiếu công tác///
                                                    else
                                                    {
                                                        $sz_CommentBusiness.=  $sz_CommentBusiness == ''? $a_InfoAbsent[2].'. Thời gian: '.$a_InfoAbsent[3]:'|'.$a_InfoAbsent[2].'. Thời gian: '.$a_InfoAbsent[3];
                                                        $a_ExAbsentBusiness = explode(' ', $a_InfoAbsent[3]); /// Tách theo dấu '' để xác định khoảng thời gian công tác của đơn////
                                                        $a_StartBusiness[] = strtotime($sz_Date.' '.$a_ExAbsentBusiness[0]); /// Mảng lưu tất cả thời gian bắt đầu công tác của tất cả Phiếu CT trong ngày
                                                        $a_EndBusiness[] = strtotime($sz_Date.' '.$a_ExAbsentBusiness[2]); /// Mảng lưu tất cả thời gian kết thúc công tác của tất cả Phiếu CT trong ngày
                                                        $i_MinStartBusiness = min($a_StartBusiness); /// Lấy Thời gian bắt đầu công tác nhỏ nhất của các phiếu///
                                                        $i_MaxEndBusiness = max($a_EndBusiness); /// Lấy Thời gian kết thúc công tác lớn nhất của các phiếu///  
                                                    }
                                                }

                                                ////Nếu trong ngày có PCT thì tìm ra Check In và Check Out dựa vào PCT và giờ chấm công///
                                                if(count($a_StartBusiness) > 0)
                                                {
                                                    if($i_CheckIn <= $i_MinStartBusiness) $i_CheckOut = $i_MaxEndBusiness;
                                                    else if($i_MinStartBusiness < $i_CheckIn && $i_CheckIn <= $i_MaxEndBusiness)
                                                    {
                                                        $i_CheckIn = $i_MinStartBusiness;
                                                        $i_CheckOut = $i_MaxEndBusiness;
                                                    }
                                                    else 
                                                    {
                                                        $i_CheckOut = $i_CheckIn;
                                                        $i_CheckIn = $i_MinStartBusiness;     
                                                    } 
                                                }
                                                /////Nếu ngày này không có bất kỳ đơn xin nghỉ nửa ngày nào => Tất cả là PCT///
                                                if(count($a_CheckAbsent) == 0)
                                                {

                                                    ////Nếu Check In dưới 9h////
                                                    if($i_CheckIn < $i_Time9Hours)
                                                    {
                                                        ///Nếu Check Out dưới 11h////
                                                        if($i_CheckOut < $i_Time11Hours)
                                                        {
                                                            if(isset($a_AllAbsent[$code][$key]))
                                                            {
                                                                $a_AllAbsent[$code][$key] .= ',v';
                                                            }
                                                            else $a_AllAbsent[$code][$key] = 'v';
                                                        }
                                                        ///Nếu Check Out từ 11h -> 16h30////
                                                        else if($i_Time11Hours <= $i_CheckOut &&  $i_CheckOut < $i_Time1630Hours)
                                                        {
                                                            ////Nếu ko phải T7///
                                                            if($sz_Day != 'T7')
                                                            {
                                                                if(isset($a_AllAbsent[$code][$key]))
                                                                {
                                                                    $a_AllAbsent[$code][$key] .= ',v/2';
                                                                }
                                                                else $a_AllAbsent[$code][$key] = 'v/2';
                                                            }
                                                            ////Nếu là T7 -> đủ công -> tạo comment///
                                                            else 
                                                            {
                                                                ///Xác định vi phạm đi muộn//
                                                                if($i_Time805Hours <= $i_CheckIn && $i_CheckIn < $i_Time810Hours)
                                                                {
                                                                    $a_Late[$code][$key] = '5';
                                                                }
                                                                else if($i_Time810Hours <= $i_MinStartBusiness )
                                                                {
                                                                    $a_Late[$code][$key] = '10';
                                                                }

                                                                ///Xác định vi phạm về sớm///
                                                                if($i_Time1150Hours <= $i_CheckOut && $i_CheckOut < $i_Time1155Hours)
                                                                {
                                                                    $a_Late[$code][$key] = isset($a_Late[$code][$key])? $a_Late[$code][$key].'|5':'5';
                                                                }
                                                                else if($i_CheckOut < $i_Time1150Hours)
                                                                {
                                                                    $a_Late[$code][$key] = isset($a_Late[$code][$key])?$a_Late[$code][$key].'|10':'10';
                                                                }

                                                                $a_AllAbsent[$code][$key.'_cmt'] = $sz_CommentBusiness; 
                                                            }
                                                        }
                                                        ////Tr hợp còn lại -> đủ công -> tạo cm///
                                                        else
                                                        {
                                                            //Xác định vi phạm đi muộn//
                                                            if($i_Time805Hours <= $i_CheckIn && $i_CheckIn < $i_Time810Hours)
                                                            {
                                                                $a_Late[$code][$key] = '5';
                                                            }
                                                            else if($i_Time810Hours <= $i_CheckIn )
                                                            {
                                                                $a_Late[$code][$key] = '10';
                                                            }

                                                            ///Xác định vi phạm về sớm///
                                                            if($i_Time1720Hours <= $i_CheckOut && $i_CheckOut < $i_Time1725Hours)
                                                            {
                                                                $a_Late[$code][$key] = isset($a_Late[$code][$key])? $a_Late[$code][$key].'|5':'5';
                                                            }
                                                            else if($i_CheckOut < $i_Time1720Hours)
                                                            {
                                                                $a_Late[$code][$key] = isset($a_Late[$code][$key])?$a_Late[$code][$key].'|10':'10';
                                                            }

                                                            $a_AllAbsent[$code][$key.'_cmt'] = $sz_CommentBusiness; 
                                                        }
                                                    }
                                                    ////Nếu Check in sau 9h -> Luôn là vắng ko lý do -> ko cần tạo cm////
                                                    else
                                                    {
                                                        ////Nếu Check in trước 14h30////
                                                        if($i_CheckIn < $i_Time1430Hours)
                                                        {
                                                            ////Nếu Check Out trước 16h30///
                                                            if($i_CheckOut < $i_Time1630Hours)
                                                            {
                                                                if(isset($a_AllAbsent[$code][$key]))
                                                                {
                                                                    $a_AllAbsent[$code][$key] .= ',v';  
                                                                }
                                                                else $a_AllAbsent[$code][$key] = 'v';
                                                            }
                                                            ////Nếu Check Out từ 16h30 trở đi//// 
                                                            else
                                                            {
                                                                if(isset($a_AllAbsent[$code][$key]))
                                                                {
                                                                    $a_AllAbsent[$code][$key] .= ',v/2';  
                                                                }
                                                                else $a_AllAbsent[$code][$key] = 'v/2';
                                                            }
                                                        }
                                                        ////Nếu Check In sau 14h30///
                                                        else
                                                        {
                                                            if(isset($a_AllAbsent[$code][$key]))
                                                            {
                                                                $a_AllAbsent[$code][$key] .= ',v';  
                                                            }
                                                            else $a_AllAbsent[$code][$key] =  'v';
                                                        }
                                                    } 
                                                }

                                                ////Nếu có 1 đơn xin nghỉ nửa ngày -> Còn lại là PCT///
                                                else if(count($a_CheckAbsent) == 1)
                                                {
                                                    ///Nếu đơn xin nghỉ chiều////
                                                    if($a_ExTimeAbsent[0] == '12:00')
                                                    {
                                                        ///Nếu Check In lớn hơn 9:00 hoặc Check Out nhỏ hơn 11:00 -> v/2
                                                        if($i_CheckIn >= strtotime($sz_Date.' 09:00') || $i_CheckOut < strtotime($sz_Date.' 11:00'))
                                                        {           
                                                            if(isset($a_AllAbsent[$code][$key]))
                                                            {
                                                                $a_AllAbsent[$code][$key] .= ',v/2';
                                                            }
                                                            else $a_AllAbsent[$code][$key] = 'v/2';  
                                                        }
                                                        else
                                                        {       
                                                            ///Xác định vi phạm đi muộn///
                                                            if($i_Time805Hours <= $i_CheckIn && $i_CheckIn < $i_Time810Hours)
                                                            {
                                                                $a_Late[$code][$key] = '5';
                                                            }
                                                            else if($i_Time810Hours <= $i_CheckIn)
                                                            {
                                                                $a_Late[$code][$key] = '10';
                                                            }

                                                            ///Xác định vi phạm về sớm///
                                                            if($i_Time1150Hours <= $i_CheckOut && $i_CheckOut < $i_Time1155Hours)
                                                            {
                                                                $a_Late[$code][$key] = isset($a_Late[$code][$key])? $a_Late[$code][$key].'|5':'5';
                                                            }
                                                            else if($i_MaxEndBusiness < $i_CheckOut)
                                                            {
                                                                $a_Late[$code][$key] = isset($a_Late[$code][$key])?$a_Late[$code][$key].'|10':'10';
                                                            }

                                                            if(isset($a_AllAbsent[$code][$key]))
                                                            {
                                                                $a_AllAbsent[$code][$key] .= ','.$a_Each1[$a_CheckAbsent[0]][0]; 
                                                                $a_AllAbsent[$code][$key.'_cmt'] .= '|Nghỉ chiều';
                                                            }
                                                            else
                                                            {
                                                                $a_AllAbsent[$code][$key] = $a_Each1[$a_CheckAbsent[0]][0]; 
                                                                $a_AllAbsent[$code][$key.'_cmt'] = 'Nghỉ chiều';
                                                            }
                                                        }
                                                    }
                                                    ////Nếu đơn xin nghỉ sáng////
                                                    else
                                                    {
                                                        ///Nếu Check In hơn 14:30 hoặc Check Out nhỏ hơn 16:30 -> v/2
                                                        if($i_CheckIn >= strtotime($sz_Date.' 14:30') || $i_CheckOut < strtotime($sz_Date.' 16:30'))
                                                        { 
                                                            if(isset($a_AllAbsent[$code][$key]))
                                                            {
                                                                $a_AllAbsent[$code][$key] .= ',v/2';
                                                            }
                                                            else {
                                                                $a_AllAbsent[$code][$key] = 'v/2';  
                                                            }
                                                        }
                                                        else
                                                        {
                                                            ///Xác định vi phạm đi muộn//
                                                            if($i_Time1335Hours <= $i_CheckIn && $i_CheckIn < $i_Time1340Hours)
                                                            {
                                                                $a_Late[$code][$key] = '5';
                                                            }
                                                            else if($i_Time1340Hours <= $i_CheckIn )
                                                            {
                                                                $a_Late[$code][$key] = '10';
                                                            }

                                                            ///Xác định vi phạm về sớm///
                                                            if($i_Time1720Hours <= $i_CheckOut && $i_CheckOut < $i_Time1725Hours)
                                                            {
                                                                $a_Late[$code][$key] = isset($a_Late[$code][$key])? $a_Late[$code][$key].'|5':'5';
                                                            }
                                                            else if($i_CheckOut < $i_Time1720Hours)
                                                            {
                                                                $a_Late[$code][$key] = isset($a_Late[$code][$key])?$a_Late[$code][$key].'|10':'10';
                                                            }

                                                            if(isset($a_AllAbsent[$code][$key]))
                                                            {
                                                                $a_AllAbsent[$code][$key] .= ','.$a_Each1[$a_CheckAbsent[0]][0];
                                                                $a_AllAbsent[$code][$key.'_cmt'] .= '|Nghỉ sáng';
                                                            }
                                                            else {
                                                                $a_AllAbsent[$code][$key] = $a_Each1[$a_CheckAbsent[0]][0];
                                                                $a_AllAbsent[$code][$key.'_cmt'] = 'Nghỉ sáng';
                                                            }
                                                        }
                                                    }
                                                }

                                                ////Nếu tất cả là đơn xin nghỉ nửa ngày////
                                                else 
                                                {
                                                    if(isset($a_AllAbsent[$code][$key]))
                                                    {
                                                        $a_AllAbsent[$code][$key] .= ','.$a_Each1[$a_CheckAbsent[0]][0];
                                                        $a_AllAbsent[$code][$key] .= ','.$a_Each1[$a_CheckAbsent[1]][0];  
                                                    }
                                                    else {
                                                        $a_AllAbsent[$code][$key] = $a_Each1[$a_CheckAbsent[0]][0].','.$a_Each1[$a_CheckAbsent[1]][0];
                                                    }
                                                }
                                            }  

                                            ///Nếu ngày này chỉ có 1 đơn vắng mặt///
                                            else
                                            {
                                                $a_val = explode('|', $a_LeaveReport[$code]->$key);
                                                /////Nếu đơn là đi công tác///
                                                if($a_val[0] == 'ct')
                                                {
                                                    $a_ExTimeAbsent = explode(' ', $a_val[3]); /// Tách theo dấu '' để xác định khoảng thời gian công tác của đơn////
                                                    $i_StartBusiness = strtotime($sz_Date.' '.$a_ExTimeAbsent[0]); /// Thời gian bắt đầu công tác của Phiếu CT 
                                                    $i_EndBusiness = strtotime($sz_Date.' '.$a_ExTimeAbsent[2]); /// Thời gian kết thúc công tác của Phiếu CT 

                                                    if($i_CheckIn <= $i_StartBusiness) $i_CheckOut = $i_EndBusiness;
                                                    else if($i_StartBusiness < $i_CheckIn && $i_CheckIn <= $i_EndBusiness)
                                                    {
                                                        $i_CheckIn = $i_StartBusiness;
                                                        $i_CheckOut = $i_EndBusiness;
                                                    }
                                                    else 
                                                    {
                                                        $i_CheckOut = $i_CheckIn;
                                                        $i_CheckIn = $i_StartBusiness;     
                                                    } 

                                                    ////Nếu Check In dưới 9h////
                                                    if($i_CheckIn < $i_Time9Hours)
                                                    {
                                                        ///Nếu Check Out dưới 11h////
                                                        if($i_CheckOut < $i_Time11Hours)
                                                        {
                                                            if(isset($a_AllAbsent[$code][$key]))
                                                            {
                                                                $a_AllAbsent[$code][$key] .= ',v';
                                                            }
                                                            else $a_AllAbsent[$code][$key] = 'v';
                                                        }
                                                        ///Nếu Check Out từ 11h -> 16h30////
                                                        else if($i_Time11Hours <= $i_CheckOut &&  $i_CheckOut < $i_Time1630Hours)
                                                        {
                                                            ////Nếu ko phải T7///
                                                            if($sz_Day != 'T7')
                                                            {
                                                                if(isset($a_AllAbsent[$code][$key]))
                                                                {
                                                                    $a_AllAbsent[$code][$key] .= ',v/2';
                                                                }
                                                                else $a_AllAbsent[$code][$key] = 'v/2';
                                                            }
                                                            ////Nếu là T7 -> đủ công -> tạo comment///
                                                            else 
                                                            {
                                                                ///Xác định vi phạm đi muộn//
                                                                if($i_Time805Hours <= $i_CheckIn && $i_CheckIn < $i_Time810Hours)
                                                                {
                                                                    $a_Late[$code][$key] = '5';
                                                                }
                                                                else if($i_Time810Hours <= $i_CheckIn )
                                                                {
                                                                    $a_Late[$code][$key] = '10';
                                                                }

                                                                ///Xác định vi phạm về sớm///
                                                                if($i_Time1150Hours <= $i_CheckOut && $i_CheckOut < $i_Time1155Hours)
                                                                {
                                                                    $a_Late[$code][$key] = isset($a_Late[$code][$key])? $a_Late[$code][$key].'|5':'5';
                                                                }
                                                                else if($i_CheckOut < $i_Time1150Hours )
                                                                {
                                                                    $a_Late[$code][$key] = isset($a_Late[$code][$key])?$a_Late[$code][$key].'|10':'10';
                                                                }

                                                                $a_AllAbsent[$code][$key.'_cmt'] = $a_val[2].'. Thời gian: '.$a_val[3]; 
                                                            }
                                                        }
                                                        ////Tr hợp còn lại -> đủ công -> tạo cm///
                                                        else
                                                        {
                                                            //Xác định vi phạm đi muộn//
                                                            if($i_Time805Hours <= $i_CheckIn && $i_CheckIn < $i_Time810Hours)
                                                            {
                                                                $a_Late[$code][$key] = '5';
                                                            }
                                                            else if($i_Time810Hours <= $i_CheckIn )
                                                            {
                                                                $a_Late[$code][$key] = '10';
                                                            }

                                                            ///Xác định vi phạm về sớm///
                                                            if($i_Time1720Hours <= $i_CheckOut && $i_CheckOut < $i_Time1725Hours)
                                                            {
                                                                $a_Late[$code][$key] = isset($a_Late[$code][$key])? $a_Late[$code][$key].'|5':'5';
                                                            }
                                                            else if($i_CheckOut < $i_Time1720Hours )
                                                            {
                                                                $a_Late[$code][$key] = isset($a_Late[$code][$key])?$a_Late[$code][$key].'|10':'10';
                                                            }

                                                            $a_AllAbsent[$code][$key.'_cmt'] = $a_val[2].'. Thời gian: '.$a_val[3]; 
                                                        }
                                                    }
                                                    ////Nếu Check in sau 9h -> Luôn là vắng ko lý do -> ko cần tạo cm////
                                                    else
                                                    {
                                                        ////Nếu Check in trước 14h30////
                                                        if($i_CheckIn < $i_Time1430Hours)
                                                        {
                                                            ////Nếu Check Out trước 16h30///
                                                            if($i_CheckOut < $i_Time1630Hours)
                                                            {
                                                                if(isset($a_AllAbsent[$code][$key]))
                                                                {
                                                                    $a_AllAbsent[$code][$key] .= ',v';  
                                                                }
                                                                else $a_AllAbsent[$code][$key] =  'v';
                                                            }
                                                            ////Nếu Check Out từ 16h30 trở đi//// 
                                                            else
                                                            {
                                                                if(isset($a_AllAbsent[$code][$key]))
                                                                {
                                                                    $a_AllAbsent[$code][$key] .= ',v/2';  
                                                                }
                                                                else $a_AllAbsent[$code][$key] = 'v/2';
                                                            }
                                                        }
                                                        ////Nếu Check In sau 14h30///
                                                        else
                                                        {
                                                            if(isset($a_AllAbsent[$code][$key]))
                                                            {
                                                                $a_AllAbsent[$code][$key] .= ',v';  
                                                            }
                                                            else $a_AllAbsent[$code][$key] =  'v';
                                                        }
                                                    }     
                                                }
                                                ///Nếu là đơn xin nghỉ phép///
                                                else
                                                {
                                                    ////Nếu là đơn xin nghỉ nửa ngày////
                                                    if(($a_val[0] == 'p/2' || $a_val[0] == 'pn/2'))
                                                    {
                                                        $a_ExTimeAbsent = explode(' ', $a_val[3]); /// Tách theo dấu '' của đơn để xác định time bắt đầu nghỉ của đơn là sáng hay chiều///

                                                        ///////Nếu đơn nghỉ chiều////
                                                        if($a_ExTimeAbsent[0] == '12:00')
                                                        {   
                                                            ////Nếu Check In dưới 9h => p/2 hoặc pn/2///
                                                            if($i_CheckIn < $i_Time9Hours)
                                                            {
                                                                ///XÁc định vi phạm đi muộn//
                                                                if($i_Time805Hours <= $i_CheckIn && $i_CheckIn < $i_Time810Hours)
                                                                {
                                                                    $a_Late[$code][$key] = '5';
                                                                }
                                                                else if($i_Time810Hours <= $i_CheckIn )
                                                                {
                                                                    $a_Late[$code][$key] = '10';
                                                                }

                                                                if(isset($a_AllAbsent[$code][$key]))
                                                                {
                                                                    $a_AllAbsent[$code][$key] .= $a_val[0];
                                                                    $a_AllAbsent[$code][$key.'_cmt'] .= '|Nghỉ chiều';
                                                                }
                                                                else
                                                                {
                                                                    $a_AllAbsent[$code][$key] = $a_val[0];
                                                                    $a_AllAbsent[$code][$key.'_cmt'] = '|Nghỉ chiều';
                                                                }
                                                            }
                                                            ////Nếu Check in sau 9h => v/2///
                                                            else
                                                            {
                                                                if(isset($a_AllAbsent[$code][$key]))
                                                                {
                                                                    $a_AllAbsent[$code][$key] .= 'v/2';
                                                                }
                                                                else $a_AllAbsent[$code][$key] = 'v/2';
                                                            }                                
                                                        }
                                                        ///Nếu đơn nghỉ sáng///
                                                        else if($a_ExTimeAbsent[0] == '00:00')
                                                        {
                                                            ///Nếu không phải T7///
                                                            if($sz_Day != 'T7')
                                                            {
                                                                ////Nếu Check In dưới 12h => v/2///
                                                                if($i_CheckIn < $i_Time12Hours)
                                                                {
                                                                    if(isset($a_AllAbsent[$code][$key]))
                                                                    {
                                                                        $a_AllAbsent[$code][$key] .= 'v/2';
                                                                    }
                                                                    else $a_AllAbsent[$code][$key] = 'v/2';
                                                                }
                                                                ///Nếu Check in trong khoảng 12h đến 14h30 => p/2 hoặc pn/2///
                                                                else if($i_Time12Hours <= $i_CheckIn && $i_CheckIn < $i_Time1430Hours)
                                                                {
                                                                    if($i_Time1335Hours <= $i_CheckIn && $i_CheckIn < $i_Time1340Hours)
                                                                    {
                                                                        $a_Late[$code][$key] = '5';
                                                                    }
                                                                    else if($i_Time1340Hours <= $i_CheckIn )
                                                                    {
                                                                        $a_Late[$code][$key] = '10';
                                                                    }

                                                                    if(isset($a_AllAbsent[$code][$key]))
                                                                    {
                                                                        $a_AllAbsent[$code][$key] .= $a_val[0];
                                                                        $a_AllAbsent[$code][$key.'_cmt'] .= '|Nghỉ sáng';
                                                                    }
                                                                    else
                                                                    {
                                                                        $a_AllAbsent[$code][$key] = $a_val[0];
                                                                        $a_AllAbsent[$code][$key.'_cmt'] = '|Nghỉ sáng';
                                                                    }
                                                                }
                                                                ////Nếu Check In sau 14h30 => v/2///
                                                                else
                                                                {
                                                                    if(isset($a_AllAbsent[$code][$key]))
                                                                    {
                                                                        $a_AllAbsent[$code][$key] .= 'v/2';
                                                                    }
                                                                    else $a_AllAbsent[$code][$key] = 'v/2';
                                                                }
                                                            }
                                                            ////Nếu là T7///
                                                            else
                                                            {
                                                                if(isset($a_AllAbsent[$code][$key]))
                                                                {
                                                                    $a_AllAbsent[$code][$key] .= ','.$a_val[0] == 'p/2' ? 'p' :'pn';
                                                                    $a_AllAbsent[$code][$key.'_cmt'] .= '|Nghỉ sáng';
                                                                }
                                                                else {
                                                                    $a_AllAbsent[$code][$key] = $a_val[0] == 'p/2' ? 'p' :'pn';
                                                                    $a_AllAbsent[$code][$key.'_cmt'] = 'Nghỉ sáng';
                                                                }
                                                            }         
                                                        }
                                                    }
                                                    ////Nếu là đơn xin nghỉ cả ngày////
                                                    else
                                                    {
                                                        if(isset($a_AllAbsent[$code][$key]))
                                                        {
                                                            $a_AllAbsent[$code][$key] .= $a_val[0];
                                                        }
                                                        else $a_AllAbsent[$code][$key] = $a_val[0];
                                                    }
                                                }
                                            }   
                                        }
                                    }
                                }
                            } 
                        }
                    } 
                } 
            }      
        }

        ///Thực hiện Insert mảng lưu tất cả các nhân viên còn lại ko vắng mặt, ko vi phạm////
        foreach ($a_TimeSheet as $sz_Code => $a_UserTimeSheet) 
        {
            $a_InsertAbsent =  array();
            $a_InsertAbsent['month'] = $a_UserTimeSheet->month;
            $a_InsertAbsent['year'] = $a_UserTimeSheet->year;
            $a_InsertAbsent['user_id'] = $a_UserTimeSheet->user_id;
            $a_InsertAbsent['name'] = $a_UserTimeSheet->name;
            $a_InsertAbsent['code'] = $a_UserTimeSheet->code;
            $a_InsertAbsent['department_id'] = $a_UserTimeSheet->department_id;
            $a_InsertAbsent['department_name'] = array_key_exists($a_UserTimeSheet->department_id, $a_AllDepartments)?$a_AllDepartments[$a_UserTimeSheet->department_id]:' Chưa có phòng ban ';
            ////Nếu user ko nằm trong mảng danh sách vắng mặt, vi phạm////
            if(!array_key_exists($sz_Code, $a_AllAbsent))
            {
                ///Nếu user này không cần phải chấm công hoặc ko có trong bảng user -> để trống hết////
                if(!isset($a_infoUsers[$sz_Code]) || $a_infoUsers[$sz_Code]['flag_timesheet'] == 0){
                    foreach ($a_RangeDay as $sz_Date => $sz_Day) 
                    { 
                        $a_InsertAbsent[$sz_Date] = ''; 
                    }
                }else{
                    foreach ($a_RangeDay as $sz_Date => $sz_Day) 
                    { 
                        if($a_infoUsers[$sz_Code]['work_start'] != '' && Util::GetRealDate($year , $month, $sz_Date) < $a_infoUsers[$sz_Code]['work_start']){
                            $a_InsertAbsent[$sz_Date] = '';
                        }else{
                            if($sz_Day == 'T7') $a_InsertAbsent[$sz_Date] = 'x/2';
                            else if($sz_Day == 'CN') $a_InsertAbsent[$sz_Date] = '';
                            else $a_InsertAbsent[$sz_Date] = 'x'; 
                        }
                    }
                }
            }
            else
            {
                foreach ($a_RangeDay as $sz_Date => $sz_Day) 
                {    
                    if(array_key_exists($sz_Date,$a_AllAbsent[$sz_Code]))
                    {
                        $a_InsertAbsent[$sz_Date] = $a_AllAbsent[$sz_Code][$sz_Date];
                        $a_InsertAbsent[$sz_Date.'_cmt'] = array_key_exists($sz_Date.'_cmt',$a_AllAbsent[$sz_Code])?$a_AllAbsent[$sz_Code][$sz_Date.'_cmt']:'';
                    }
                    else
                    {
                        if($a_infoUsers[$sz_Code]['work_start'] != '' && Util::GetRealDate($year , $month, $sz_Date) < $a_infoUsers[$sz_Code]['work_start']){
                            $a_InsertAbsent[$sz_Date] = '';
                        }else{
                            if($sz_Day == 'T7') $a_InsertAbsent[$sz_Date] = 'x/2';
                            else if($a_RangeDay[$sz_Date] == 'CN') $a_InsertAbsent[$sz_Date] = '';
                            else $a_InsertAbsent[$sz_Date] = 'x';
                            $a_InsertAbsent[$sz_Date.'_cmt'] = array_key_exists($sz_Date.'_cmt',$a_AllAbsent[$sz_Code])?$a_AllAbsent[$sz_Code][$sz_Date.'_cmt']:'';
                        }
                    }     
                }
            }
            DB::table('merge_time_sheet')->insert($a_InsertAbsent);
        }   
        
        /////Thực hiện xử lý tất cả các nhân viên không có chấm công////  
        ////////Get All User in DB////////
        $a_Users = $this->o_Timesheet->a_GetAllCodeDbUsers();
        if(Util::b_fCheckArray($a_Users))
        {     
            $a_InsertTimeSheet['month'] = $month;
            $a_InsertTimeSheet['year'] = $year;
            foreach ($a_Users as $o_Users) 
            {

                $o_Users->code = trim($o_Users->code);
                ////Nếu user này không xuất hiện trong bảng chấm công/////
                if(!array_key_exists($o_Users->code, $a_TimeSheet)) 
                {
                    ////Nếu User này có trong bảng thống kê vắng mặt///
                    if(array_key_exists($o_Users->code, $a_LeaveReport))
                    {
                        if(!isset($a_AllAbsent2[$o_Users->code]))
                        {
                            $a_AllAbsent2[$o_Users->code] = array(
                                'name' => $o_Users->name, 
                                'code' => $o_Users->code, 
                                'department_id' => $o_Users->department_id, 
                                'department_name' => array_key_exists($o_Users->department_id, $a_AllDepartments)?$a_AllDepartments[$o_Users->department_id]:'Chưa có phòng ban');
                        }

                        foreach ($a_LeaveReport[$o_Users->code] as $key => $val) 
                        {             
                            if(is_numeric($key) && array_key_exists($key,$a_RangeDay))
                            {
                                if($a_infoUsers[$o_Users->code]['work_start'] == '' || Util::GetRealDate($sz_Year , $sz_Month, $key) >= $a_infoUsers[$o_Users->code]['work_start'])
                                {
                                    if(isset($a_Each1)) unset($a_Each1);

                                    $sz_Day = $a_RangeDay[$key];
                                    if($sz_Day != 'CN')
                                    {
                                        ////Nếu ngày này không có đơn vắng mặt nào////
                                        if($val == '') 
                                        {
                                            $a_AllAbsent2[$o_Users->code][$key] = 'v';
                                        }
                                        //////Nếu ngày này có đơn vắng mặt/////
                                        else
                                        {
                                            $sz_Month = $a_LeaveReport[$o_Users->code]->month < 10?'0'.$a_LeaveReport[$o_Users->code]->month:$a_LeaveReport[$o_Users->code]->month;
                                            $sz_Year = $a_LeaveReport[$o_Users->code]->year ;

                                            $sz_Date = $key.'-'.$sz_Month.'-'.$sz_Year;
                                            $i_Time8Hours = strtotime($sz_Date.' 08:00');
                                            $i_Time805Hours = strtotime($sz_Date.' 08:05');
                                            $i_Time810Hours = strtotime($sz_Date.' 08:10');
                                            $i_Time9Hours = strtotime($sz_Date.' 09:00');
                                            $i_Time11Hours = strtotime($sz_Date.' 11:00');
                                            $i_Time1150Hours = strtotime($sz_Date.' 11:50');
                                            $i_Time1155Hours = strtotime($sz_Date.' 11:55');
                                            $i_Time12Hours = strtotime($sz_Date.' 12:00');
                                            $i_Time1330Hours = strtotime($sz_Date.' 13:00');
                                            $i_Time1335Hours = strtotime($sz_Date.' 13:35');
                                            $i_Time1340Hours = strtotime($sz_Date.' 13:40');
                                            $i_Time1430Hours = strtotime($sz_Date.' 14:30');
                                            $i_Time1630Hours = strtotime($sz_Date.' 16:30');
                                            $i_Time1720Hours = strtotime($sz_Date.' 17:20');
                                            $i_Time1725Hours = strtotime($sz_Date.' 17:25');
                                            $i_Time1730Hours = strtotime($sz_Date.' 17:30');

                                            /////Nếu có từ 2 đơn vắng mặt trở lên////
                                            if (strpos($a_LeaveReport[$o_Users->code]->$key, '&&&') !== false)
                                            {
                                                $a_Each = explode('&&&', $a_LeaveReport[$o_Users->code]->$key);
                                                foreach ($a_Each as $sz_Each) 
                                                {
                                                    $a_Each1[] = explode('|', $sz_Each);
                                                }

                                                $sz_Comment = ''; ///Comment nghỉ nửa ngày
                                                $sz_CommentBusiness = ''; ///Comment PCT
                                                $a_CheckAbsent =  array(); ///Mảng lưu tất cả các đơn là nghỉ nửa ngày, lưu theo key của mảng $a_Each1 ////
                                                $a_StartBusiness =  array(); /// Mảng lưu tất cả thời gian bắt đầu công tác của tất cả Phiếu CT trong ngày
                                                $a_EndBusiness = array(); /// Mảng lưu tất cả thời gian kết thúc công tác của tất cả Phiếu CT trong ngày

                                                foreach ($a_Each1 as $i_key => $a_InfoAbsent) 
                                                { 
                                                    //Nếu có 1 đơn là xin nghỉ nửa ngày////
                                                    if(in_array($a_InfoAbsent[0], array('p/2','pn/2')))
                                                    {
                                                        $a_CheckAbsent[] = $i_key; 
                                                        $a_ExTimeAbsent = explode(' ', $a_InfoAbsent[3]); /// Tách theo dấu '' của đơn để xấc định time bắt đầu nghỉ của đơn 2 là sáng hay chiều///
                                                        if($a_ExTimeAbsent[0] == '12:00') $sz_Comment = $sz_Comment == ''?'Nghỉ chiều':'|Nghỉ chiều';
                                                        else $sz_Comment = $sz_Comment == ''?'Nghỉ sáng':'|Nghỉ sáng';
                                                    }
                                                    ///Với các đơn còn lại sẽ là Phiếu công tác///
                                                    else
                                                    {
                                                        $sz_CommentBusiness.=  $sz_CommentBusiness == ''?$a_InfoAbsent[2].'. Thời gian: '.$a_InfoAbsent[3]:'|'.$a_InfoAbsent[2].'. Thời gian: '.$a_InfoAbsent[3];
                                                        $a_ExAbsentBusiness = explode(' ', $a_InfoAbsent[3]); /// Tách theo dấu '' để xác định khoảng thời gian công tác của đơn////
                                                        $a_StartBusiness[] = strtotime($sz_Date.' '.$a_ExAbsentBusiness[0]); /// Mảng lưu tất cả thời gian bắt đầu công tác của tất cả Phiếu CT trong ngày
                                                        $a_EndBusiness[] = strtotime($sz_Date.' '.$a_ExAbsentBusiness[2]); /// Mảng lưu tất cả thời gian kết thúc công tác của tất cả Phiếu CT trong ngày
                                                        $i_MinStartBusiness = min($a_StartBusiness); /// Lấy Thời gian bắt đầu công tác nhỏ nhất của các phiếu///
                                                        $i_MaxEndBusiness = max($a_EndBusiness); /// Lấy Thời gian kết thúc công tác lớn nhất của các phiếu///  
                                                    }
                                                }

                                                /////Nếu ngày này không có bất kỳ đơn xin nghỉ nửa ngày nào => Tất cả là PCT///
                                                if(count($a_CheckAbsent) == 0)
                                                {
                                                    ////Nếu Thời gian bắt đầu công tác nhỏ nhất các PCT dưới 9h////
                                                    if($i_MinStartBusiness < $i_Time9Hours)
                                                    {
                                                        ///Nếu Thời gian kết thúc công tác lớn nhất của các phiếu dưới 11h////
                                                        if($i_MaxEndBusiness < $i_Time11Hours)
                                                        {
                                                            if(isset($a_AllAbsent2[$o_Users->code][$key]))
                                                            {
                                                                $a_AllAbsent2[$o_Users->code][$key] .= ',v';
                                                            }
                                                            else $a_AllAbsent2[$o_Users->code][$key] = 'v';
                                                        }
                                                        ///Nếu Thời gian kết thúc công tác lớn nhất của các phiếu từ 11h -> 16h30////
                                                        else if($i_Time11Hours <= $i_MaxEndBusiness &&  $i_MaxEndBusiness < $i_Time1630Hours)
                                                        {
                                                            ////Chỉ xét vắng trong trường hợp là ko phải T7///
                                                            if($sz_Day != 'T7')
                                                            {
                                                                if(isset($a_AllAbsent2[$o_Users->code][$key]))
                                                                {
                                                                    $a_AllAbsent2[$o_Users->code][$key] .= ',v/2';
                                                                }
                                                                else $a_AllAbsent2[$o_Users->code][$key] = 'v/2';
                                                            }
                                                            ////Nếu là T7 -> đủ công -> tạo comment///
                                                            else 
                                                            {
                                                                $a_AllAbsent2[$o_Users->code][$key.'_cmt'] = $sz_CommentBusiness; 

                                                                ///Xác định vi phạm đi muộn//
                                                                if($i_Time805Hours <= $i_MinStartBusiness && $i_MinStartBusiness < $i_Time810Hours)
                                                                {
                                                                    $a_Late[$o_Users->code][$key] = '5';
                                                                }
                                                                else if($i_Time810Hours <= $i_MinStartBusiness )
                                                                {
                                                                    $a_Late[$o_Users->code][$key] = '10';
                                                                }

                                                                ///Xác định vi phạm về sớm///
                                                                if($i_Time1150Hours <= $i_MaxEndBusiness && $i_MaxEndBusiness < $i_Time1155Hours)
                                                                {
                                                                    $a_Late[$o_Users->code][$key] = isset($a_Late[$o_Users->code][$key])? $a_Late[$o_Users->code][$key].'|5':'5';
                                                                }
                                                                else if($i_MaxEndBusiness < $i_Time1150Hours )
                                                                {
                                                                    $a_Late[$o_Users->code][$key] = isset($a_Late[$o_Users->code][$key])?$a_Late[$o_Users->code][$key].'|10':'10';
                                                                }

                                                            }
                                                        }
                                                        ////Tr hợp còn lại -> đủ công -> tạo cm///
                                                        else
                                                        {
                                                            $a_AllAbsent2[$o_Users->code][$key.'_cmt'] = $sz_CommentBusiness; 
                                                            if($i_Time805Hours <= $i_MinStartBusiness && $i_MinStartBusiness < $i_Time810Hours)
                                                            {
                                                                $a_Late[$o_Users->code][$key] = '5';
                                                            }
                                                            else if($i_Time810Hours <= $i_MinStartBusiness )
                                                            {
                                                                $a_Late[$o_Users->code][$key] = '10';
                                                            }

                                                            ///Xác định vi phạm về sớm///
                                                            if($i_Time1720Hours <= $i_MaxEndBusiness && $i_MaxEndBusiness < $i_Time1725Hours)
                                                            {
                                                                $a_Late[$o_Users->code][$key] = isset($a_Late[$o_Users->code][$key])? $a_Late[$o_Users->code][$key].'|5':'5';
                                                            }
                                                            else if($i_MaxEndBusiness < $i_Time1720Hours )
                                                            {
                                                                $a_Late[$o_Users->code][$key] = isset($a_Late[$o_Users->code][$key])?$a_Late[$o_Users->code][$key].'|10':'10';
                                                            }
                                                        }
                                                    }
                                                    ////Nếu Thời gian bắt đầu công tác nhỏ nhất sau 9h -> Luôn là vắng ko lý do -> ko cần tạo cm////
                                                    else
                                                    {
                                                        ////Nếu Thời gian bắt đầu công tác nhỏ nhất trước 14h30////
                                                        if($i_MinStartBusiness < $i_Time1430Hours)
                                                        {
                                                            ////Nếu Thời gian kết thúc công tác lớn nhất trước 16h30///
                                                            if($i_MaxEndBusiness < $i_Time1630Hours)
                                                            {
                                                                if(isset($a_AllAbsent2[$o_Users->code][$key]))
                                                                {
                                                                    $a_AllAbsent2[$o_Users->code][$key] .= ',v';  
                                                                }
                                                                else $a_AllAbsent2[$o_Users->code][$key] =  'v';
                                                            }
                                                            ////Nếu Thời gian kết thúc công tác lớn nhất từ 16h30 trở đi//// 
                                                            else
                                                            {
                                                                if(isset($a_AllAbsent2[$o_Users->code][$key]))
                                                                {
                                                                    $a_AllAbsent2[$o_Users->code][$key] .= ',v/2';  
                                                                }
                                                                else $a_AllAbsent2[$o_Users->code][$key] = 'v/2';
                                                            }
                                                        }
                                                        ////Nếu Thời gian bắt đầu công tác nhỏ nhất sau 14h30///
                                                        else
                                                        {
                                                            if(isset($a_AllAbsent2[$o_Users->code][$key]))
                                                            {
                                                                $a_AllAbsent2[$o_Users->code][$key] .= ',v';  
                                                            }
                                                            else $a_AllAbsent2[$o_Users->code][$key] =  'v';
                                                        }
                                                    }         
                                                }    

                                                ////Nếu có 1 đơn xin nghỉ nửa ngày -> Còn lại là PCT///
                                                else if(count($a_CheckAbsent) == 1)
                                                {
                                                    ///Nếu đơn xin nghỉ chiều////
                                                    if($a_ExTimeAbsent[0] == '12:00')
                                                    {
                                                        ///Nếu thời gian bắt đầu công tác nhỏ nhất của các PCT hơn 9:00 hoặc thời gian kết thúc công tác lớn nhất của các PCT nhỏ hơn 11:00 -> v/2
                                                        if($i_MinStartBusiness >= strtotime($sz_Date.' 09:00') || $i_MaxEndBusiness < strtotime($sz_Date.' 11:00'))
                                                        {           
                                                            if(isset($a_AllAbsent2[$o_Users->code][$key]))
                                                            {
                                                                $a_AllAbsent2[$o_Users->code][$key] .= ',v/2';
                                                            }
                                                            else $a_AllAbsent2[$o_Users->code][$key] = 'v/2';  
                                                        }
                                                        else
                                                        {          
                                                            ///Xác định vi phạm đi muộn///
                                                            if($i_Time805Hours <= $i_MinStartBusiness && $i_MinStartBusiness < $i_Time810Hours)
                                                            {
                                                                $a_Late[$o_Users->code][$key] = '5';
                                                            }
                                                            else if($i_Time810Hours <= $i_MinStartBusiness )
                                                            {
                                                                $a_Late[$o_Users->code][$key] = '10';
                                                            }

                                                            ///Xác định vi phạm về sớm///
                                                            if($i_Time1150Hours <= $i_MaxEndBusiness && $i_MaxEndBusiness < $i_Time1155Hours)
                                                            {
                                                                $a_Late[$o_Users->code][$key] = isset($a_Late[$o_Users->code][$key])? $a_Late[$o_Users->code][$key].'|5':'5';
                                                            }
                                                            else if($i_MaxEndBusiness < $i_Time1150Hours )
                                                            {
                                                                $a_Late[$o_Users->code][$key] = isset($a_Late[$o_Users->code][$key])?$a_Late[$o_Users->code][$key].'|10':'10';
                                                            }

                                                            if(isset($a_AllAbsent2[$o_Users->code][$key]))
                                                            {
                                                                $a_AllAbsent2[$o_Users->code][$key] .= ','.$a_Each1[$a_CheckAbsent[0]][0]; 
                                                                $a_AllAbsent2[$o_Users->code][$key.'_cmt'] .= '|Nghỉ chiều';
                                                            }
                                                            else
                                                            {
                                                                $a_AllAbsent2[$o_Users->code][$key] = $a_Each1[$a_CheckAbsent[0]][0]; 
                                                                $a_AllAbsent2[$o_Users->code][$key.'_cmt'] = 'Nghỉ chiều';
                                                            }
                                                        }
                                                    }
                                                    ////Nếu đơn xin nghỉ sáng////
                                                    else
                                                    {
                                                        ///Nếu thời gian bắt đầu công tác nhỏ nhất của các PCT hơn 14:30 hoặc thời gian kết thúc công tác lớn nhất của các PCT nhỏ hơn 16:30 -> v/2
                                                        if($i_MinStartBusiness >= strtotime($sz_Date.' 14:30') || $i_MaxEndBusiness < strtotime($sz_Date.' 16:30'))
                                                        {           
                                                            if(isset($a_AllAbsent2[$o_Users->code][$key]))
                                                            {
                                                                $a_AllAbsent2[$o_Users->code][$key] .= ',v/2';
                                                            }
                                                            else $a_AllAbsent2[$o_Users->code][$key] = 'v/2';     
                                                        }
                                                        else
                                                        {
                                                            ///Xác đinh vi phạm đi muôn///
                                                            if($i_Time1335Hours <= $i_MinStartBusiness && $i_MinStartBusiness < $i_Time1340Hours)
                                                            {
                                                                $a_Late[$o_Users->code][$key] = '5';
                                                            }
                                                            else if($i_Time1340Hours <= $i_MinStartBusiness )
                                                            {
                                                                $a_Late[$o_Users->code][$key] = '10';
                                                            }

                                                            ///Xác định vi phạm về sớm///
                                                            if($i_Time1720Hours <= $i_MaxEndBusiness && $i_MaxEndBusiness < $i_Time1725Hours)
                                                            {
                                                                $a_Late[$o_Users->code][$key] = isset($a_Late[$o_Users->code][$key])? $a_Late[$o_Users->code][$key].'|5':'5';
                                                            }
                                                            else if($i_MaxEndBusiness < $i_Time1720Hours )
                                                            {
                                                                $a_Late[$code][$key] = isset($a_Late[$code][$key])?$a_Late[$code][$key].'|10':'10';
                                                            }

                                                            if(isset($a_AllAbsent2[$o_Users->code][$key]))
                                                            {
                                                                $a_AllAbsent2[$o_Users->code][$key] .= ','.$a_Each1[$a_CheckAbsent[0]][0];
                                                                $a_AllAbsent2[$o_Users->code][$key.'_cmt'] .= '|Nghỉ sáng';
                                                            }
                                                            else {
                                                                $a_AllAbsent2[$o_Users->code][$key] = $a_Each1[$a_CheckAbsent[0]][0];
                                                                $a_AllAbsent2[$o_Users->code][$key.'_cmt'] = 'Nghỉ sáng';
                                                            }
                                                        }
                                                    }
                                                }
                                                ////Nếu tất cả là đơn xin nghỉ nửa ngày////
                                                else 
                                                {
                                                    if(isset($a_AllAbsent2[$code][$key]))
                                                    {
                                                        $a_AllAbsent2[$o_Users->code][$key] .= ','.$a_Each1[$a_CheckAbsent[0]][0];
                                                        $a_AllAbsent2[$o_Users->code][$key] .= ','.$a_Each1[$a_CheckAbsent[1]][0];  
                                                    }
                                                    else {
                                                        $a_AllAbsent2[$o_Users->code][$key] = $a_Each1[$a_CheckAbsent[0]][0].','.$a_Each1[$a_CheckAbsent[1]][0];
                                                    }
                                                }    
                                            }

                                            ///Nếu ngày này chỉ có 1 đơn vắng mặt///
                                            else
                                            {
                                                $a_val = explode('|', $a_LeaveReport[$o_Users->code]->$key);
                                                /////Nếu đơn là đi công tác///
                                                if($a_val[0] == 'ct')
                                                {
                                                    $a_ExTimeAbsent = explode(' ', $a_val[3]); /// Tách theo dấu '' để xác định khoảng thời gian công tác của đơn////
                                                    $i_StartBusiness = strtotime($sz_Date.' '.$a_ExTimeAbsent[0]); /// Thời gian bắt đầu công tác của Phiếu CT 
                                                    $i_EndBusiness = strtotime($sz_Date.' '.$a_ExTimeAbsent[2]); /// Thời gian kết thúc công tác của Phiếu CT 
                                                    ////Nếu Time bắt đầu công tác là dưới 9h////
                                                    if($i_StartBusiness < $i_Time9Hours)
                                                    {
                                                        ////Nếu Time kết thúc công tác là dưới 11h////
                                                        if($i_EndBusiness < $i_Time11Hours)
                                                        {
                                                            if(isset($a_AllAbsent2[$o_Users->code][$key]))
                                                            {
                                                                $a_AllAbsent2[$o_Users->code][$key] .= ',v';
                                                            }
                                                            else $a_AllAbsent2[$o_Users->code][$key] = 'v';
                                                        }
                                                        ///Nếu Time kết thúc công tác từ 11h -> 16h30////
                                                        else if($i_Time11Hours <= $i_EndBusiness &&  $i_EndBusiness < $i_Time1630Hours)
                                                        {
                                                            ////Trường hợp là ko phải T7 thì là vắng///
                                                            if($sz_Day != 'T7')
                                                            {
                                                                if(isset($a_AllAbsent2[$o_Users->code][$key]))
                                                                {
                                                                    $a_AllAbsent2[$o_Users->code][$key] .= ',v/2';
                                                                }
                                                                else $a_AllAbsent2[$o_Users->code][$key] = 'v/2';
                                                            }
                                                            ////Nếu là T7 -> đủ công -> tạo comment//
                                                            else
                                                            {
                                                                ///Xác đinh vi phạm đi muộn//
                                                                if($i_Time805Hours <= $i_StartBusiness && $i_StartBusiness < $i_Time810Hours)
                                                                {
                                                                    $a_Late[$o_Users->code][$key] = '5';
                                                                }
                                                                else if($i_Time810Hours <= $i_StartBusiness )
                                                                {
                                                                    $a_Late[$o_Users->code][$key] = '10';
                                                                }

                                                                ///Xác định vi phạm về sớm///
                                                                if($i_Time1150Hours <= $i_EndBusiness && $i_EndBusiness < $i_Time1155Hours)
                                                                {
                                                                    $a_Late[$o_Users->code][$key] = isset($a_Late[$o_Users->code][$key])? $a_Late[$o_Users->code][$key].'|5':'5';
                                                                }
                                                                else if($i_EndBusiness < $i_Time1150Hours )
                                                                {
                                                                    $a_Late[$o_Users->code][$key] = isset($a_Late[$o_Users->code][$key])?$a_Late[$o_Users->code][$key].'|10':'10';
                                                                }

                                                                $a_AllAbsent2[$o_Users->code][$key.'_cmt'] = $a_val[2].'. Thời gian: '.$a_val[3]; 
                                                            }
                                                        }
                                                        ////Tr hợp còn lại -> đủ công -> tạo cm///
                                                        else
                                                        {
                                                            ///Xác định vi phạm đi muộn//
                                                            if($i_Time805Hours <= $i_StartBusiness && $i_StartBusiness < $i_Time810Hours)
                                                            {
                                                                $a_Late[$o_Users->code][$key] = '5';
                                                            }
                                                            else if($i_Time810Hours <= $i_StartBusiness )
                                                            {
                                                                $a_Late[$o_Users->code][$key] = '10';
                                                            }

                                                            ///Xác định vi phạm về sớm///
                                                            if($i_Time1720Hours <= $i_EndBusiness && $i_EndBusiness < $i_Time1725Hours)
                                                            {
                                                                $a_Late[$o_Users->code][$key] = isset($a_Late[$o_Users->code][$key])? $a_Late[$o_Users->code][$key].'|5':'5';
                                                            }
                                                            else if($i_EndBusiness < $i_Time1720Hours )
                                                            {
                                                                $a_Late[$o_Users->code][$key] = isset($a_Late[$o_Users->code][$key])?$a_Late[$o_Users->code][$key].'|10':'10';
                                                            }

                                                            $a_AllAbsent2[$o_Users->code][$key.'_cmt'] =  $a_val[2].'. Thời gian: '.$a_val[3]; 
                                                        }
                                                    }
                                                    ////Nếu Time bắt đầu công tác sau 9h -> Ít nhất vắng nửa ngày ko phép -> ko cần tạo cm////
                                                    else
                                                    {
                                                        ////Nếu Time bắt đầu công tác trước 14h30////
                                                        if($i_StartBusiness < $i_Time1430Hours)
                                                        {
                                                            ////Nếu Time kết thúc công tác trước 16h30///
                                                            if($i_EndBusiness < $i_Time1630Hours)
                                                            {
                                                                if(isset($a_AllAbsent2[$o_Users->code][$key]))
                                                                {
                                                                    $a_AllAbsent2[$o_Users->code][$key] .= ',v';  
                                                                }
                                                                else $a_AllAbsent2[$o_Users->code][$key] = 'v';
                                                            }
                                                            ////Nếu Time kết thúc công tác sau 16h30///
                                                            else
                                                            {
                                                                if(isset($a_AllAbsent2[$o_Users->code][$key]))
                                                                {
                                                                    $a_AllAbsent2[$o_Users->code][$key] .= ',v/2';
                                                                }
                                                                else $a_AllAbsent2[$o_Users->code][$key] = 'v/2';  
                                                            }
                                                        }
                                                        ////Nếu Time bắt đầu công tác sau 14h30///
                                                        else
                                                        {
                                                            if(isset($a_AllAbsent2[$o_Users->code][$key]))
                                                            {
                                                                $a_AllAbsent2[$o_Users->code][$key] .= ',v';  
                                                            }
                                                            else $a_AllAbsent2[$o_Users->code][$key] = 'v';
                                                        }
                                                    }       
                                                }
                                                ///Nếu là đơn xin nghỉ phép///
                                                else
                                                {
                                                    ////Nếu là đơn xin nghỉ nửa ngày////
                                                    if(($a_val[0] == 'p/2' || $a_val[0] == 'pn/2'))
                                                    {
                                                        $a_ExTimeAbsent = explode(' ', $a_val[3]); /// Tách theo dấu '' của đơn để xấc định time bắt đầu nghỉ của đơn là sáng hay chiều///
                                                        ////Nếu ko phải T7///
                                                        if($sz_Day != 'T7')
                                                        {
                                                            if(isset($a_AllAbsent2[$o_Users->code][$key]))
                                                            {
                                                                $a_AllAbsent2[$o_Users->code][$key] .= ',v/2';  
                                                            }
                                                            else $a_AllAbsent2[$o_Users->code][$key] = 'v/2';
                                                        }
                                                        ////Nếu là T7///
                                                        else
                                                        {
                                                            if(isset($a_AllAbsent[$o_Users->code][$key]))
                                                            {
                                                                $a_AllAbsent2[$o_Users->code][$key] .= $a_val[0] == 'p/2'? 'p': 'pn';
                                                                $a_AllAbsent2[$o_Users->code][$key.'_cmt'] .= $a_ExTimeAbsent[0] == '12:00'?'|Nghỉ chiều':'|Nghỉ sáng';
                                                            }
                                                            else {
                                                                $a_AllAbsent2[$o_Users->code][$key] = $a_val[0] == 'p/2'? 'p': 'pn';
                                                                $a_AllAbsent2[$o_Users->code][$key.'_cmt'] = $a_ExTimeAbsent[0] == '12:00'?'Nghỉ chiều':'Nghỉ sáng';
                                                            }
                                                        }
                                                    }
                                                    ////Nếu là đơn xin nghỉ cả ngày////
                                                    else
                                                    {
                                                        if(isset($a_AllAbsent2[$o_Users->code][$key]))
                                                        {
                                                            $a_AllAbsent2[$o_Users->code][$key] .= $a_val[0];
                                                        }
                                                        else $a_AllAbsent2[$o_Users->code][$key] = $a_val[0];
                                                    }
                                                }
                                            }
                                        }
                                    } 
                                }    
                            }
                        } 
                    }
                    ////Nếu User này ko có trong bảng thống kê vắng mặt/////
                    else
                    {
                        $a_InsertTimeSheet['name'] = $o_Users->name;
                        $a_InsertTimeSheet['code'] = $o_Users->code;
                        $a_InsertTimeSheet['department_id'] = $o_Users->department_id;
                        $a_InsertTimeSheet['department_name'] = array_key_exists($o_Users->department_id, $a_AllDepartments)?$a_AllDepartments[$o_Users->department_id]:' Chưa có phòng ban ';
                        foreach ($a_RangeDay as $sz_Date => $sz_Day) 
                        {
                            if($a_infoUsers[$o_Users->code]['flag_timesheet'] == 0){
                                $a_InsertTimeSheet[$sz_Date] = '';
                            }else{
                                if($a_infoUsers[$o_Users->code]['work_start'] != '' && Util::GetRealDate($year , $month, $sz_Date) < $a_infoUsers[$o_Users->code]['work_start']){
                                $a_InsertTimeSheet[$sz_Date] = '';
                                }else{
                                    if($sz_Day == 'CN') $a_InsertTimeSheet[$sz_Date] = '';
                                    else $a_InsertTimeSheet[$sz_Date] = 'v'; 
                                }
                            }   
                        }
                        DB::table('merge_time_sheet')->insert($a_InsertTimeSheet);
                    }
                }  
            }
        }
        ////Thực hiện insert mảng thống kê các user không có dữ liệu chấm công///
        if(isset($a_AllAbsent2))
        {
            $a_InsertAbsent2['month'] = $month;
            $a_InsertAbsent2['year'] = $year;
            foreach ($a_AllAbsent2 as $a_UserAbsent2) 
            {
                $a_InsertAbsent2['name'] = $a_UserAbsent2['name'];
                $a_InsertAbsent2['code'] = $a_UserAbsent2['code'];
                $a_InsertAbsent2['department_id'] = $a_UserAbsent2['department_id'];
                $a_InsertAbsent2['department_name'] = $a_UserAbsent2['department_name'];
                if($a_infoUsers[$a_UserAbsent2['code']]['flag_timesheet'] == 0){
                    foreach ($a_RangeDay as $sz_Date => $sz_Day) 
                    { 
                        $a_InsertAbsent2[$sz_Date] = '';
                    }
                }
                else
                {
                    foreach ($a_RangeDay as $sz_Date => $sz_Day) 
                    {
                        if(array_key_exists($sz_Date,$a_UserAbsent2))
                        {
                            $a_InsertAbsent2[$sz_Date] = $a_UserAbsent2[$sz_Date];
                            $a_InsertAbsent2[$sz_Date.'_cmt'] = array_key_exists($sz_Date.'_cmt',$a_UserAbsent2)?$a_UserAbsent2[$sz_Date.'_cmt']:'';
                        }
                        else
                        {
                            if($a_infoUsers[$a_UserAbsent2['code']]['work_start'] != '' && Util::GetRealDate($year, $month, $sz_Date) < $a_infoUsers[$a_UserAbsent2['code']]['work_start']){
                                $a_InsertAbsent[$sz_Date] = '';
                            }else{
                                if($sz_Day == 'T7') $a_InsertAbsent2[$sz_Date] = 'x/2';
                                else if($a_RangeDay[$sz_Date] == 'CN') $a_InsertAbsent2[$sz_Date] = '';
                                else $a_InsertAbsent2[$sz_Date] = 'x';
                                $a_InsertAbsent2[$sz_Date.'_cmt'] = array_key_exists($sz_Date.'_cmt',$a_UserAbsent2)?$a_UserAbsent2[$sz_Date.'_cmt']:'';
                            }
                        }  
                    }
                }
                DB::table('merge_time_sheet')->insert($a_InsertAbsent2);
            }
        }
        
        ////Thực hiện insert dữ liệu đi muộn////
        if(count($a_Late) > 0){
            foreach ($a_Late as $sz_code => $a_val) {
                DB::table('late')->insert($a_val);
            }
        }
        return redirect('time_sheet_month');
    }
    
    /**
     * Auth: Dienct
     * Des: Export file excel table user
     * Since: 23/2/2015
     */
    public function export_excel() {
        $this->o_Timesheet->DownloadExcel();
    }
    
    /**
     * @author Vit
     * @since 25/02/1016
     * @des Show Timesheet Table by Month
     */
    
    public function TimeSheetMonth(){
        $Data_view = $this->o_Timesheet->ShowTimesheetMonth();
        return view('timesheet.timesheetmonth', $Data_view);
    }
}
