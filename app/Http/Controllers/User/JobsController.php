<?php

namespace App\Http\Controllers\User;

use DB;
use Auth;
use Illuminate\Http\Request as o_request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;
use App\Models\Jobs as jobs_model;
use Illuminate\Support\Facades\Input;
use App\Util;
use Illuminate\Support\Facades\URL;
use App\Models\Roles as o_RoleModel;


class JobsController extends Controller
{
    private $o_jobs;
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

    public function index(jobs_model $job ){
        $a_data = $job->getAllSearch();
        $Data_view['a_jobs'] = $a_data['a_data'];
        $Data_view['a_search'] = $a_data['a_search'];
        return view('jobs.index',$Data_view);
    }
    
    public function modify(jobs_model $job, o_request $o_resquest){
        $i_id = $o_resquest->id;
        $Data_view['i_id'] = $i_id;
        if($o_resquest->submit)
        {
            $a_data = $o_resquest->data;
            $a_data['status'] = !isset($a_data['status'])?0:1;
            if($i_id == '')
            {
                $a_data['created_at'] = date('Y-m-d H:i:s',time()); 
                DB::table('jobs')->insert($a_data);
            }
            else
            {
                $a_data['updated_at'] = date('Y-m-d H:i:s',time()); 
                DB::table('jobs')->where('id', $i_id)->update($a_data);
            }
            if(Input::get('multi-insert') == 'on') {
                return redirect(URL::current())->with('status', 'Cập nhật thành công!'); 
            }
            return redirect('jobs')->with('status', 'Cập nhật dữ liệu thành công!');
        } 
        if($i_id == '') return view('jobs.modify',$Data_view);
        else
        {
            ///Lấy thông tin job hiện tại///
            $Data_view['o_job'] = DB::table('jobs')->where('id', $i_id)->first();
            return view('jobs.modify', $Data_view);
        }
    }
}
