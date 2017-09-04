<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request as o_IllumRequest;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Department as o_DepartmentModel;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\URL;
use App\Models\Roles as o_RoleModel;
class DepartmentController extends Controller
{
    private $o_Department;
    private $o_Request;
    public function __construct() {
        $o_Role = new o_RoleModel();
        $o_Role->b_fCheckRole();
        $this->o_Department = new o_DepartmentModel();
        $this->o_Request = new o_IllumRequest();
    }
    
    /**
     * @Auth: Dienct
     * @Des: Get all department
     * @since: 9/1/2015
     */
    public function ListDepartMent(){
        
        $a_Data = $this->o_Department->getAllSearch();
        $Data_view['a_Department'] = $a_Data['a_data'];
        $Data_view['a_search'] = $a_Data['a_search'];
        return view('department.index',$Data_view);
        
    }
    
    
    /**
     * @Auth: Dienct
     * @Des: Update department
     * @Since: 9/1/2015
     */
    public function editDepartment()
    {        
        $a_DataView = array();
        $department_id = (int)Input::get('id',0);  
        $productname = Input::get('submit');
        if(isset($productname) && $productname !="")
        {
            $this->o_Department->AddEditDepartment($department_id);
            if(Input::get('multi-insert') == 'on') {
                return redirect(URL::current())->with('status', 'Cập nhật thành công!'); 
            }
            else return redirect('list_department')->with('status', 'Cập nhật thành công!'); 
        }

        $a_DataView = $this->o_Department->getDepartmentById($department_id);
        return view('department.edit_department', ['a_Department' => $a_DataView, 'i_id' => $department_id]);

        ///get data department one record///
        
    }
    
    

}
