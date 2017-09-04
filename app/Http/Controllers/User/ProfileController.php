<?php

namespace App\Http\Controllers\User;
use DB;
use Auth;
use Illuminate\Http\Request as o_request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;
use App\Models\Users as users_model;
use App\Models\Department as o_DepartmentModel;
use App\Util;
use Illuminate\Support\Facades\URL;
use App\Models\Roles as o_RoleModel;
use Illuminate\Support\Facades\Mail;
use App\MailApi;
class ProfileController extends Controller
{
    private $o_Department;
    private $o_users;
    protected $_o_MailApi;
    public function __construct()
    {
        $o_Role = new o_RoleModel();
        $o_Role->b_fCheckRole();
        $this->o_users = new users_model();
        $this->o_Department = new o_DepartmentModel();
        $this->_o_MailApi = new MailApi();
    }
    ////Request change manager///
    public function ChangeManager(o_request $o_resquest)
    {
        $Data_view['a_departments'] = $this->o_Department->GetAllDepartMent();
            if($o_resquest->submit){
                if(Auth::user()->direct_manager_id == $o_resquest->new_manager_id){
                    return redirect(URL::current())->with('status', 'Bạn cần chọn Người quản lý trực tiếp mới!');
                }
                $result = DB::table('users')->where('id',(int)Auth::user()->id)->update(array('direct_manager_id' => $o_resquest->new_manager_id, 'department_id' => $o_resquest->new_department_id ));
                if($result){
                    return redirect('/')->with('status', 'thành công!');
                }else{
                    return redirect('/')->with('status', 'Kiểm tra lại dữ liệu!');
                }
            }
            return view('profile.change_manager', $Data_view);
    }
    /**
     * @auth: Dienct
     * @Des: change sensor
     * since 10/11/2016
     */
    public function ChangeSensor(o_request $o_resquest)
    {
            $Data_view['a_departments'] = $this->o_Department->GetAllDepartMent();
            if($o_resquest->submit){
                if(Auth::user()->censor_id == $o_resquest->new_manager_id){
                    return redirect(URL::current())->with('status', 'Bạn cần chọn Người quản lý trực tiếp mới!');
                }
                $result = DB::table('users')->where('id',(int)Auth::user()->id)->update(array('censor_id' => $o_resquest->new_manager_id, 'censor2_id' => $o_resquest->new_manager_id2 ));
                if($result){
                    return redirect('/')->with('status', 'Gửi yêu cầu thay đổi người duyệt thành công!');
                }else{
                    return redirect('/')->with('status', 'Kiểm tra lại dữ liệu!');
                }
            }
            return view('profile.change_sensor', $Data_view);
    }

    /**
     * @auth: HuyNN
     * @des: Change Password for User
     * since: 11/04/2016
     */
    public function ChangePassword(o_request $o_resquest)
    {
        if($o_resquest->submit)
        {
            $sz_Password = $o_resquest->password;
            $sz_DbPassword = bcrypt($sz_Password);
            $b_Update = DB::table('users')->where('email', Auth::user()->email)->update(array('password' => $sz_DbPassword));
            if($b_Update) redirect('/')->with('status', 'Thay đổi mật khẩu thành công!');
            else redirect(URL::current())->with('status', 'Có lỗi khi cập nhật mật khẩu!');
        }
        return view('profile.change-password');
    }
}
