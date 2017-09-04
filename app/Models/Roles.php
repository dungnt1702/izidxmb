<?php

namespace App\Models;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Redirect;
use App\Util;
use Illuminate\Support\Facades\Input;

/**
 * User roles model
 * @author DungNT
 * @since 29/12/2015
 */
class Roles extends Model
{
    protected $name = '';
    protected $controller = '';
    protected $read = 0;
    protected $create = 0;
    protected $update = 0;
    protected $delete = 0;

    /**
     * Get user role by role id from user
     * @author DungNT
     * @since 29/12/2015
     * @param int $the_i_RoleId
     * @return object
     */
    public function __construct()
    {

    }
    /**
     * Check user role
     * @author DungNT
     * @since 29/12/2015
     * @param string $the_sz_Controller
     * @return boolean
     */
    public function b_fCheckRole()
    {
        $a_ControllerAction = explode('@', Route::getCurrentRoute()->getActionName());
        $a_Controller = explode('\\', $a_ControllerAction[0]);
        $sz_ControllerURL = $a_Controller[sizeof($a_Controller) - 1];
        if($sz_ControllerURL != 'HomeController' && $sz_ControllerURL != 'AjaxController' && isset(Auth::user()->role_id) && Auth::user()->role_id != config('cmconst.id_superadmin')){
            $i_userId = isset(Auth::user()->role_id) ? Auth::user()->role_id : 0;
            $a_DataRole = $this->a_GetAllRoleByRoleGroup($i_userId);
            $b_checkExit = True;
            if(is_array($a_DataRole) && count($a_DataRole)>0){
                foreach ($a_DataRole as $i_Key => $o_Val){
                    if($o_Val->controller == $sz_ControllerURL && $o_Val->action == $a_ControllerAction[1]) $b_checkExit = False;
                    if($sz_ControllerURL == 'UserController' && $a_ControllerAction[1]=='edit' && $o_Val->action == 'insert') $b_checkExit = False;
                }
                if($b_checkExit == True) Redirect::to('/')->send();
            }
        }
    }

    /**
     * @Auth: Dienct
     * @Des: Get all role by role_group_id
     * @Since: 12/1/2016
     */
    public function a_GetAllRoleByRoleGroup($i_userId){
        if(isset($i_userId) && $i_userId > 0) {
            $a_DataRole = array();
            $a_DataRole = DB::table('roles')->select('controller','action','read', 'create', 'update', 'delete')->where('rolegroup_id', $i_userId)->get();
            return $a_DataRole;
        }else{
            return false;
        }
    }

    /**
     * @Auth: Dienct
     * @Des: Get all role group
     * @Since: 13/1/2016
     */
    public function a_GetAllRoleGroup(&$a_Result = array()){
        $a_Field = array('id','name','status','created_at','updated_at');
        $a_Result = DB::table('rolegroups')->select($a_Field)->get();
        foreach($a_Result as $key =>&$val){
                $val->stt = $key + 1;
                $val->created_at = Util::sz_DateTimeFormat($val->created_at);
                $val->updated_at = Util::sz_DateTimeFormat($val->updated_at);
        }
    }
    
    /**
     * @Auth: Luongnv
     * @Des: Get allsearch role group
     * @Since: 23/02/2016
     */
    public function a_GetAllRoleGroupSearch(&$a_Result = array()){
        $a_Field = array('id','name','status','created_at','updated_at');
        
        $a_data = array();
        $o_Db = DB::table('rolegroups')->select($a_Field);
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
        $a_data = $o_Db->get();
        foreach($a_data as $key =>&$val){
                $val->stt = $key + 1;
                $val->created_at = Util::sz_DateTimeFormat($val->created_at);
                $val->updated_at = Util::sz_DateTimeFormat($val->updated_at);
        }
        $a_return = array('a_data' => $a_data, 'a_search' => $a_search);
        return $a_return;
    }
    
    /**
     * @Auth: Dienct
     * @Des: Get all role by role_group_id
     * @Since: 12/1/2016
     */
    public function a_GetAllRoleByRoleGroupIDFilter($i_RoleGroupId){
        if(isset($i_RoleGroupId) && $i_RoleGroupId > 0) {
            $a_DataRole = array();
            $a_DataRole = DB::table('roles')->select('controller','action','read', 'create', 'update', 'delete')->where('rolegroup_id', $i_RoleGroupId)->get();
            
            $arrayReturn = array();
            if(count($a_DataRole) > 0){
                foreach($a_DataRole as $key=>$val){
                    if(!isset($arrayReturn[$val->controller]))
                    {
                        $arrayReturn[$val->controller] = array();
                    }
                    if(!isset($arrayReturn[$val->controller][$val->action]))
                    {
                        $arrayReturn[$val->controller][] = $val->action;
                    }
                }
            }
            return $arrayReturn;
        }
    }
    
    /**
     * @Auth: Dienct
     * @Des: Add edit Role
     * @Since: 13/1/2016
     */
    public function AddEditRole($i_RoleGroup_id){

        // delete all role by rolegroup
        DB::table('roles')->where('rolegroup_id', '=', $i_RoleGroup_id)->delete();
        
        $a_DataUpdate =  Input::all();
        
        unset($a_DataUpdate["_token"]);
        unset($a_DataUpdate["submit"]);
        unset($a_DataUpdate["id"]);
        
        foreach($a_DataUpdate as $keyController =>$a_val){
            foreach($a_val as $keyAction => $szVal){
                $a_DataInsert['rolegroup_id'] = $i_RoleGroup_id;
                $a_DataInsert['controller'] = $keyController;
                $a_DataInsert['action'] = $keyAction;
                $a_DataInsert['created_at'] = date('Y-m-d H:i:s',time());
                $a_DataInsert['updated_at'] = date('Y-m-d H:i:s',time());
                DB::table('roles')->insert($a_DataInsert);
            }
        }

    }

}
