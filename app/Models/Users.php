<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use PhpParser\Node\Expr\Cast\Object_;
use App\Util;
class Users extends Model
{
    /**

     * auth: Dienct
     * Des: get all users child
     * Since: 4/1/2016
     */
    public function getChildUserId($parentDeptCode,&$aryChildId)
    {
        $aryResult = DB::table('users')->select('id','name')->where('direct_manager_id', $parentDeptCode)->get();

        foreach ($aryResult as $o_val){
            $aryChildId[] = $o_val->id;
            if(!empty($aryResult)){
                $this->getChildUserId($o_val->id,$aryChildId);
            }
        }
    }
    /**

     * auth: Dienct
     * Des: get all users child
     * Since: 4/1/2016
     */
    public function getAllChildUserId($parentDeptCode,&$aryChildId)
    {
            $aryResult = DB::table('users')->select('id','name','is_manager')->where('direct_manager_id', $parentDeptCode)->get();

            foreach ($aryResult as $o_val){
                if($o_val->is_manager == 1) $aryChildId['manager'][] = $o_val->id;
                else if($o_val->is_manager == 0) $aryChildId['staff'][] = $o_val->id;
                if(!empty($aryResult)){
                    $this->getAllChildUserId($o_val->id,$aryChildId);
                }
            }

    }

    /**
     * auth: HuyNN
     * Des: get all users
     * Since: 20/1/2016
     */
    public function GetAllUsers()
    {
        $a_search = array();
        $a_data = array();
        $o_Db = DB::table('users');
        $i_search_department = Input::get('search_department','');
        $i_search_position = Input::get('search_position','');
        $i_search_jobs = Input::get('search_jobs','');
        $sz_search_by = Input::get('search_by','');

        if($i_search_department != '') {
            $a_search['search_department'] = $i_search_department;
            $a_data = $o_Db->where('department_id', $i_search_department);
        }
        if($i_search_position != ''){
            $a_search['search_position'] = $i_search_position;
            $a_data = $o_Db->where('position_id', $i_search_position);
        }
        if($i_search_jobs != ''){
            $a_search['search_jobs'] = $i_search_jobs;
            $a_data = $o_Db->where('job_id', $i_search_jobs);
        }
        if($sz_search_by != '')
        {
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
        if(Auth::user()->is_manager == 1 && Auth::user()->hr_type != 1) $a_data = $o_Db->where('direct_manager_id',Auth::user()->id);
        $a_data = $o_Db->orderBy('created_at', 'desc')->paginate(20);
        $a_return = array('a_data' => $a_data, 'a_search' => $a_search);
        return $a_return;
    }

    /**
     * auth: Dienct
     * Des: get all users child
     * Since: 4/1/2016
     */
    public function getUserByCode($sz_Code = 0)
    {
            $aryResult = array();
            $aryResult = DB::table('users')->select('id','name','is_manager','department_id')->where('code', $sz_Code)->first();
            return $aryResult;
    }

    /**
     * Get active user by email
     * @author DungNT
     * @since 31/01/2016
     * @param string $the_sz_Email
     * @return object | boolean
     */
    public function o_fGetActiveUserByEmail($the_sz_Email, $the_sz_Password)
    {
        if($the_sz_Email && $the_sz_Password) {
            //Get user from db
            $a_Fields = array(
                'id',
                'code',
                'name',
                'email',
                'password',
                'status',
                'is_manager',
                'hr_type',
                'group_id',
                'job_id',
                'department_id',
                'role_id',
                'profile_id',
                'position_id',
                'salary_id',
                'contract_id',
                'direct_manager_id'
            );
            $a_Where = [['email', $the_sz_Email], ['status', 1]];
            $o_User = DB::table('users')->select($a_Fields)->where($a_Where)->first();
            if ($o_User && Hash::check($the_sz_Password, $o_User->password))
            {
                return $o_User;
            }
            return false;
        }
        return false;
    }
    
    
    public function GetAllPosition()
    {
        $a_positions  = DB::table('positions')->select('id','name')->where('status', 1)->get();
        foreach ($a_positions as $key => $value) {
            $a_position[$value->id] = $value->name;
        }
        return $a_position;
    }
    
    public function sz_fNameUserById($i_UserId)
    {
        $o_User  = DB::table('users')->select('id','name')->where('id', $i_UserId)->first();
        return $o_User->name;
    }

    public function sz_fInfoUserById($i_UserId)
    {
        $o_User  = DB::table('users')->select('id','name','code','email','censor_id','job_id')->where('id', $i_UserId)->first();
        return $o_User;
    }
    
    public function b_CheckRequestChangeManager($i_UserId)
    {
        $i_Check  = DB::table('change_direct_manager')->where(array('user_id' => $i_UserId, 'status' => 0))->count();
        if($i_Check == 0) return TRUE;
        else return False;
    }
    
    public function sz_GetReporter()
    {
        $o_Reporter  = DB::table('users')->select('email')->where('hr_type',2)->first();
        return $o_Reporter->email;
    }
    
    public function a_GetAllRoleGroups()
    {
        $a_DbRole = DB::table('rolegroups')->select('id','name')->where('status',1)->get();
        if(Util::b_fCheckArray($a_DbRole)){
            $a_Role = array();
            foreach ($a_DbRole as $o_DbRole) {
                $a_Role[$o_DbRole->id] = $o_DbRole->name;
            }
        }
        return $a_Role;
    }
}
