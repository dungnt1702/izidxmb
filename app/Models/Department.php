<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use Illuminate\Http\Request;
use App\Util;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests;


class Department extends Model {

    public function __construct() {

    }

    /**
     * @Auth: Dienct
     * @Des: get all record table department
     * @Since: 07/01/2015
     */
    public function getAll() {
        $a_data = array();
        $a_data = DB::table('departments')->select('id', 'name', 'status', 'created_at', 'updated_at')->orderBy('id', 'asc')->get();
        foreach ($a_data as $key => &$val) {
            $val->stt = $key + 1;
            $val->created_at = Util::sz_DateTimeFormat($val->created_at);
            $val->updated_at = Util::sz_DateTimeFormat($val->updated_at);
        }

        return $a_data;
    }
    
    /**
     * @Auth: Luongnv
     * @Des: get allSearch record table department
     * @Since: 23/02/2015
     */
    public function getAllSearch() {
        $a_data = array();
        $o_Db = DB::table('departments')->select('id', 'name', 'status', 'created_at', 'updated_at');
        $a_search = array();
        $i_search_status = Input::get('search_status','');
        $sz_search_field = Input::get('search_field','');
        
        if($i_search_status != '') {
            $a_search['search_status'] = $i_search_status;
            $a_data = $o_Db->where('status', $i_search_status);
        }
        if($sz_search_field != '') {
            $a_search['search_field'] = $sz_search_field;
            $a_data = $o_Db->where('name', 'like', '%'.$sz_search_field.'%');
        }
        $a_data = $o_Db->orderBy('id', 'asc')->get();
        foreach ($a_data as $key => &$val) {
            $val->stt = $key + 1;
            $val->created_at = Util::sz_DateTimeFormat($val->created_at);
            $val->updated_at = Util::sz_DateTimeFormat($val->updated_at);
        }
        $a_return = array('a_data' => $a_data, 'a_search' => $a_search);
        return $a_return;
    }

    /**

     * @Auth: Dienct
     * @Des: Get infomation Department by Id
     * @Since: 11/1/2016
     */
    public function getDepartmentById($department_id) {

        $a_Data = array();
        $a_Data = DB::table('departments')->where('id', $department_id)->first();
        if (count($a_Data) > 0)
            $a_Data->created_at = Util::sz_DateTimeFormat($a_Data->created_at);
        if (count($a_Data) > 0)
            $a_Data->updated_at = Util::sz_DateTimeFormat($a_Data->updated_at);

        return $a_Data;
    }

    /**

     * @Auth: Dienct
     * @Des: Add/edit department
     * @Since: 11/1/2016
     */
    public function AddEditDepartment($department_id) {
        $a_DataUpdate = array();
        $a_DataUpdate['name'] = Input::get('name');
        $a_DataUpdate['guid'] = Input::get('guid');
        $a_DataUpdate['status'] = Input::get('status') == 'on' ? 1 : 0;
        if (is_numeric($department_id) == true && $department_id != 0) {
            $a_DataUpdate['updated_at'] = date('Y-m-d H:i:s', time());
            DB::table('departments')->where('id', $department_id)->update($a_DataUpdate);
        } else {
            $a_DataUpdate['created_at'] = date('Y-m-d H:i:s', time());
            DB::table('departments')->insert($a_DataUpdate);
        }
    }

    /**

     * @Auth: Dienct
     * @Des: Import excel file
     * @Since: 14/1/2016
     */
    public function ImportExcel($dirFile) {

        $a_NewUsers = array();
        $a_NeedUpdateUsers = array();
        //Get all users from db
        $a_DbUser = DB::table('users')->select('code', 'email', 'password')->get();
        $a_DbUsersEmail = array();
        $a_DbUsersEmailPwd = array();
        if (Util::b_fCheckArray($a_DbUser)) {
            foreach ($a_DbUser as $o_DbUser) {
                $a_DbUsersEmail[] = trim($o_DbUser->email);
                $a_DbUsersCode[] = trim($o_DbUser->code);
            }
        }


        $results = Excel::load($dirFile, function($reader) use ($a_DbUsersEmail) {
                    
                    $reader->each(function($sheet) use ($a_DbUsersEmail) {
                        foreach ($sheet->toArray() as $row) {                            
                            if (in_array(trim($row['mail_dxmb']), $a_DbUsersEmail)) {
                                // Array Update for user
                                if($row['mnv'] != "" && $row['mail_dxmb'] != ""){
                                    $a_NeedUpdateUsers[] = [
                                        'email' => $row['mail_dxmb'],
                                        'code' => $row['mnv'],
                                        'name' => $row['hoten'],
                                        'updated_at' => Util::sz_fCurrentDateTime(),
                                    ];
                                }
                            } else {
                                // Array Insert new user
                                if($row['mnv'] != "" && $row['mail_dxmb'] != ""){
                                    $a_NewUsers[] = [
                                        'email' => $row['mail_dxmb'],
                                        'code' => $row['mnv'],
                                        'name' => $row['hoten'],
                                        'updated_at' => Util::sz_fCurrentDateTime(),
                                    ];
                                }
                                
                            }
                        }
                        // check duplicate code in file excel file
//                        $cafeid = array(1,2,3,4,5,6,7);
//                        $checkid = array(1,3,5);
//                        var_dump(array_diff($cafeid, $checkid));
//                        die;
                        $a_ExcelCode = array();
                        if(count($a_NewUsers)> 0){
                            foreach($a_NewUsers as $val){
                                $a_ExcelCode[] = $val['code'];                                
                            }
                            
                            $duplicates = array_unique(array_diff_assoc($a_ExcelCode, array_unique($a_ExcelCode)));
                            
                        if(count($duplicates) > 0){
                            $str = "";
                            foreach ($duplicates as $val){
                                $str .= $val. " ";
                            }
                            
                            echo "Kiểm tra lại file excel {$str} đang bị trùng";
                        } 
                        }
                        
                        die;
                        
                        
                        if (isset($a_NewUsers) && Util::b_fCheckArray($a_NewUsers)) {
                            //Get total of new users
                            $i_TotalNewUsers = count($a_NewUsers);
                            //Insert new user into db
                            if (DB::table('users')->insert($a_NewUsers)) {
                                echo "Inserted $i_TotalNewUsers successfully!\n";
                            } else {
                                echo "Insert new users failed!\n";
                            }
                        } else {
                            echo "No any new users found!\n";
                        }
                        //Check to update
                        if (isset($a_NeedUpdateUsers) && Util::b_fCheckArray($a_NeedUpdateUsers)) {
                            //Get total of new users
                            $i_UpdateSuccessfully = 0;
                            $i_UpdateFail = 0;
                            foreach ($a_NeedUpdateUsers as $a_UpdateUser) {
                                $sz_WhereEmail = $a_UpdateUser['email'];
                                unset($a_UpdateUser['email']);
                                if (DB::table('users')->where('email', $sz_WhereEmail)->update($a_UpdateUser)) {
                                    $i_UpdateSuccessfully++;
                                } else {
                                    $i_UpdateFail++;
                                }
                            }
                            echo "Updated $i_UpdateSuccessfully user(s) successfully! And $i_UpdateFail failed!\n";
                        } else {
                            echo "No any existed user to update found!\n";
                        }
                    });
                });
    }
    
    public function GetAllDepartMent()
    {
        $a_departments  = DB::table('departments')->select('id','name')->where('status', 1)->get();
        foreach ($a_departments as $key => $value) {
            $a_department[$value->id] = $value->name;
        }
        return $a_department;
    } 
}
