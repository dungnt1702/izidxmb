<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Group as o_GroupModel;
use Illuminate\Support\Facades\Input;
use App\Models\Department as o_DepartmentModel;
use Illuminate\Support\Facades\URL;
use App\Models\Roles as o_RoleModel;
class GroupController extends Controller
{
    
    /**
     * @Auth: Dienct
     * @Des: Get all group
     * @since: 9/1/2015
     */
    private $o_Group;
    private $o_Department;
    public function __construct() {
        $o_Role = new o_RoleModel();
        $o_Role->b_fCheckRole();
        $this->o_Group = new o_GroupModel();
        $this->o_Department = new o_DepartmentModel();
    }
    
    /**
     * @Auth: Dienct
     * @Des: Get all group
     * @since: 9/1/2015
     */
    public function ListGroup(){
        
        $a_Data = $this->o_Group->getAllSearch();
        $Data_view['a_Group'] = $a_Data['a_data'];
        $Data_view['a_search'] = $a_Data['a_search'];
        return view('group.index',$Data_view);
        
    }
    
    /**
     * @Auth: Dienct
     * @Des: Update group
     * @Since: 9/1/2015
     */
    public function editGroup()
    {        
        $a_DataView = array();        
        $i_GroupID = (int)Input::get('id',0);  
        $productname = Input::get('submit');
        if(isset($productname) && $productname !="")
        {
             $this->o_Group->AddEditGroup($i_GroupID);  
             if(Input::get('multi-insert') == 'on') {
                return redirect(URL::current())->with('status', 'Cập nhật thành công!'); 
            }
            return redirect('list_group')->with('status', 'Cập nhật thành công!');
        }
        $a_DataDep = $this->o_Department->getAll();

        $a_DataView =  $this->o_Group->getGroupById($i_GroupID);        
        return view('group.edit_group', ['a_Group' => $a_DataView, 'a_Department' => $a_DataDep, 'i_id' => $i_GroupID]);

        ///get data department one record///
        
    }
}
