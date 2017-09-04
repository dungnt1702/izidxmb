<?php

namespace App\Http\Controllers\User;

use DB;
use Auth;
use Illuminate\Http\Request as o_request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;
use App\Models\Positions as position_model;
use Illuminate\Support\Facades\Input;
use App\Util;
use Illuminate\Support\Facades\URL;
use App\Models\Roles as o_RoleModel;
class PositionController extends Controller
{
    private $o_positions;
    /**
     * auth: HuyNN
     * Des: list All Positions
     * since: 11/1/2016
     * 
     */
    public function __construct() {

        $o_Role = new o_RoleModel();
        $o_Role->b_fCheckRole();
    }
    
    public function index(position_model $positions ){
        $a_data = $positions->getAllSearch();
        $Data_view['a_positions'] = $a_data['a_data'];
        $Data_view['a_search'] = $a_data['a_search'];
        return view('positions.index',$Data_view);
    }
    
    public function modify(position_model $position, o_request $o_resquest){
        $i_id = $o_resquest->id;
        $Data_view['i_id'] = $i_id;
        if($o_resquest->submit)
        {
            $a_data = $o_resquest->data;
            $a_data['status'] = !isset($a_data['status'])?0:1;
            if($i_id == '')
            {
                $a_data['created_at'] = date('Y-m-d H:i:s',time()); 
                DB::table('positions')->insert($a_data);
            }
            else
            {
                $a_data['updated_at'] = date('Y-m-d H:i:s',time()); 
                DB::table('positions')->where('id', $i_id)->update($a_data);
            }
            if(Input::get('multi-insert') == 'on') {
                return redirect(URL::current())->with('status', 'Cập nhật thành công!'); 
            }
            return redirect('positions')->with('status', 'Cập nhật dữ liệu thành công!');
        } 
        if($i_id == '') return view('positions.modify',$Data_view);
        else
        {
            ///Lấy thông tin position hiện tại///
            $Data_view['o_positions'] = DB::table('positions')->where('id', $i_id)->first();
            return view('positions.modify', $Data_view);
        }
    }
}
