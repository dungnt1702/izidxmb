<?php

namespace App\Http\Controllers\User;

use DB;
use Auth;
use Illuminate\Http\Request as o_request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;
use App\Models\Positions;
use App\Models\Users as users_model;
use App\Models\Department as o_DepartmentModel;
use Illuminate\Support\Facades\Input;
use App\Util;
use Illuminate\Support\Facades\URL;
use App\Models\Roles as o_RoleModel;
use App\MailApi;
use Illuminate\Support\Facades\Mail;
class UserController extends Controller
{
    private $o_Department;
    private $o_users;
    protected $_o_MailApi;
    public function __construct()
    {
        $o_Role = new o_RoleModel();
        $o_Role->b_fCheckRole();
        $this->o_users = new users_model();
        $this->_o_MailApi = new MailApi();
        $this->o_Department = new o_DepartmentModel();   
    }

    /**
     * Show all of the users for the application.
     *
     * @return Response
     */
    public function index()
    {
        $a_Position = DB::table('positions')->select('id','name')->where('status',1)->get();
        foreach($a_Position as $o_position){
            $a_Positions[$o_position->id] = $o_position->name;
        }
        $Data_view['a_Positions'] = $a_Positions;
        
        $a_Department= DB::table('departments')->select('id','name')->where('status',1)->get();
        foreach($a_Department as $o_deparment){
            $a_Departments[$o_deparment->id] = $o_deparment->name;
        }
        $Data_view['a_Departments'] = $a_Departments;
        
        $a_Job= DB::table('jobs')->select('id','name')->where('status',1)->get();
        foreach($a_Job as $o_job){
            $a_Jobs[$o_job->id] = $o_job->name;
        }
        $Data_view['a_Jobs'] = $a_Jobs;
        
        $a_result = $this->o_users->GetAllUsers();
        $a_Role = $this->o_users->a_GetAllRoleGroups();
        $Data_view['users'] = $a_result['a_data'];
        $Data_view['a_search'] = $a_result['a_search'];
        $Data_view['a_Role'] = $a_Role;
        return view('user.index', $Data_view);
    }
    
    /**
     * Insert User
     *
     * @return Response
     */
    public function insert(o_request $o_resquest)
    {
        $a_Role = $this->o_users->a_GetAllRoleGroups();
        if(!in_array($a_Role[Auth::user()->role_id], array('superadmin','reporter 1','reporter 2')))
        {
            return redirect('/')->with('status', 'Bạn không có quyền thêm User mới'); 
        }
        $Data_view = array();
        if($o_resquest->submit)
        {
            $a_data = $o_resquest->data;
            $sz_password = $o_resquest->password;
            $a_data['password'] = bcrypt($sz_password);
            $a_data['status'] = !isset($a_data['status'])?0:1;
            $a_data['is_manager'] = !isset($a_data['is_manager'])?0:1;
            
            $sz_email = $o_resquest->email;
            $a_data['email'] = strpos($sz_email, '@dxmb.vn') == false?trim($sz_email).'@dxmb.vn':$sz_email;
            //DB::table('users')->insert($a_data); /// Insert db izi
            $Data_view['a_data'] = $a_data;
            
            if($a_data['hr_type'] == 1)
            {
                $o_Hrm= DB::table('users')->where('hr_type',1)->first();
                if(Util::b_fCheckObject($o_Hrm))
                {
                    DB::table('users')->where('hr_type',1)->update(['hr_type'=> 0]);
                    $id = DB::table('users')->insertGetId($a_data); /// Insert db izi
                    DB::table('leave_requests')->where('status',0)->orwhere('status',1)->update(['hrm_id'=> $id]);
                }
            }
            else
            {
                $id = DB::table('users')->insertGetId($a_data); /// Insert db izi
            }
            return redirect('user')->with('status', 'Thêm thành viên mới thành công!');
        }
        $Data_view['a_pos'] = DB::table('positions')->select('id','name')->where('status', 1)->get();
        $Data_view['a_role'] = DB::table('rolegroups')->select('id','name')->where('status', 1)->get();
        $Data_view['a_departments']  = DB::table('departments')->select('id','name')->where('status', 1)->get();
        $Data_view['a_job'] = DB::table('jobs')->select('id','name')->where('status', 1)->get();
        return view('user.insert',$Data_view);
    }
    
    /**
     * Edit User
     *
     * @return Response
     */
    public function edit($user_id, o_request $o_resquest)
    {
        $a_Role = $this->o_users->a_GetAllRoleGroups();
        if(!in_array($a_Role[Auth::user()->role_id], array('superadmin','reporter 1','reporter 2')))
        {
            return redirect('/')->with('status', 'Bạn không có quyền sửa User'); 
        }
        $Data_view = array();
        ///Lấy thông tin user hiện tại///
        $o_user = DB::table('users')->where('id', $user_id)->first();
        $Data_view['o_user'] = $o_user;
                
        //Lấy toàn bộ departments trong bảng departments//// 
        $Data_view['a_department'] = DB::table('departments')->select('id','name')->where('status',1)->get();
        
        // Lấy toàn bộ chức vụ trong bảng chức vụ////
        $Data_view['a_position'] = DB::table('positions')->select('id','name')->where('status',1)->get();
        
        // Lấy toàn bộ quyền hạn trong bảng quyền hạn////
        $Data_view['a_role'] = DB::table('rolegroups')->select('id','name')->get();
        
        ///Lấy các nhóm trong phòng ban của user hiện tại////
        $Data_view['a_group'] = DB::table('groups')->select('id','name')->where('department_id',$o_user->department_id)->get();
        
        ///Lấy các nghề trong bảng nghề nghiệp////
        $Data_view['a_job'] = DB::table('jobs')->select('id','name')->where('status',1)->get();
        
        //Lấy ra người quản lý trực tiếp của user///
        $o_direct_manager = DB::table('users')->select('id','name','department_id')->where('id', $o_user->direct_manager_id)->first();
        
        
        if(Util::b_fCheckObject($o_direct_manager))
        {
            $Data_view['o_direct_manager'] = $o_direct_manager;
            ///Lấy ra toàn bộ người quản lý thuộc cùng phòng ban với người quản lý trực tiếp user này///
            $Data_view['a_manager'] = DB::table('users')->select('id','name')->where(array('department_id' => $o_direct_manager->department_id, 'is_manager' => 1))->where('id','!=',$o_user->id)->get();
        }
  
        $Data_view['user_id'] = $user_id;
        
        if($o_resquest->submit)
        {
            $a_data = $o_resquest->data;
            $sz_password = $o_resquest->password;
            if($sz_password != '') $a_data['password'] = bcrypt($sz_password);
            $Data_view['a_data'] = $a_data;
            $a_data['status'] = !isset($a_data['status'])?0:1;
            $a_data['is_manager'] = !isset($a_data['is_manager'])?0:1;

            if($a_data['hr_type'] == 1)
            {
                $o_Hrm = DB::table('users')->where('hr_type',1)->first();
                if(Util::b_fCheckObject($o_Hrm))
                {
                    DB::table('users')->where('hr_type',1)->update(['hr_type'=> 0]);
                }
                DB::table('leave_requests')->where('status',0)->orwhere('status',1)->update(['hrm_id'=> $user_id]);
            }
            DB::table('users')->where('id', $user_id)->update($a_data); 
            return redirect('user')->with('status', 'Sửa thành viên thành công!'); 
        }
        
        return view('user.edit', $Data_view);
    }
    
    ////Show list Change Manager////
    public function ListChangeManager()
    {
        $Data_view = array();
        ///Lấy thông tin user hiện tại///
        $a_user = DB::table('change_direct_manager')->orderBy('created_at', 'desc')->paginate(20);;
        $Data_view['a_user'] = $a_user;
        return view('user.list_change_manager', $Data_view);
    }
}
