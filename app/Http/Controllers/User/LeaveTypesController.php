<?php

namespace App\Http\Controllers\User;

use DB;
use Auth;
use Illuminate\Http\Request as o_request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;
use App\Models\LeaveTypes as leave_types_model;
use Illuminate\Support\Facades\Input;
use App\Util;
use Illuminate\Support\Facades\URL;
use App\Models\Roles as o_RoleModel;


class LeaveTypesController extends Controller
{
    private $o_leave_types;
    /**
     * auth: HuyNN
     * Des: list All Jobs
     * since: 9/1/2016
     * 
     */
    public function __construct() {
        $o_Role = new o_RoleModel();
        $o_Role->b_fCheckRole();
    }
    
    public function index(leave_types_model $leave_types ){
        $a_data = $leave_types->getAll();
        return view('leave_types.index',['a_leave_types' => $a_data]);
    }
    
    public function modify(leave_types_model $leave_types, o_request $o_resquest){
        $i_id = $o_resquest->id;
        $Data_view['i_id'] = $i_id;
        if($o_resquest->submit)
        {
            $a_data = $o_resquest->data;
            $a_data['status'] = !isset($a_data['status'])?0:1;
            if($i_id == '')
            {
                $a_data['created_at'] = date('Y-m-d H:i:s',time()); 
                DB::table('leave_types')->insert($a_data);
            }
            else
            {
                $a_data['updated_at'] = date('Y-m-d H:i:s',time()); 
                DB::table('leave_types')->where('id', $i_id)->update($a_data);
            }
            if(Input::get('multi-insert') == 'on') {
                return redirect(URL::current())->with('status', 'Cập nhật thành công!'); 
            }
            else return redirect('leave_types')->with('status', 'Cập nhật dữ liệu thành công!');
        } 
        if($i_id == '') return view('leave_types.modify',$Data_view);
        else
        {
            ///Lấy thông tin job hiện tại///
            $Data_view['o_leave_types'] = DB::table('leave_types')->where('id', $i_id)->first();
            return view('leave_types.modify', $Data_view);
        }
    }
}
