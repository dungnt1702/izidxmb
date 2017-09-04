<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Roles;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{
    private $o_role;
    public function __construct() {
        $this->o_role = new Roles();
    }
    
    /*
     * @Auth: Dienct
     * @Des: get all role group
     * @Since: 13/1/2015     
     */
    public function ListRoleGroup() {
        
        $a_Data = $this->o_role->a_GetAllRoleGroupSearch();
        $Data_view['a_RoleGroup'] = $a_Data['a_data'];
        $Data_view['a_search'] = $a_Data['a_search'];
        return view('role.index',$Data_view);
    }
    
    public function editRoleGroup(){
        
        
        $a_AllRole = config('cmconst.all_category');
        $a_NameController = config('cmconst.name_controller');
        
        $a_DataView = array();
        $i_RoleGroup_id = (int)Input::get('id',0);
        $productname = Input::get('submit');
        if(isset($productname) && $productname != "")
        {            
            $this->o_role->AddEditRole($i_RoleGroup_id);
            return redirect('list_role_group')->with('status', 'Cập nhật thành công!');
        }

        $a_DataView = $this->o_role->a_GetAllRoleByRoleGroupIDFilter($i_RoleGroup_id);
        
        
        return view('role.edit', ['a_RoleActive' => $a_DataView, 'i_id' => $a_DataView, 'a_AllRole' => $a_AllRole, 'a_NameController' => $a_NameController]);
    }
    
    public function insertRoleGroup(){
        $a_data = array();
        if(Input::get('submit'))
        {
            if (Input::get('status') == 'on'){
                $a_data['status'] = 1;
            }
            else {
                $a_data['status'] = 0;
            }
            $a_data['name'] = Input::get('name');
            $a_data['created_at'] = date('Y-m-d H:i:s',time());
            DB::table('rolegroups')->insert($a_data);
            return redirect('list_role_group')->with('status', 'Cập nhật dữ liệu thành công!');
        }
        return view('role.insert');
    }
}
