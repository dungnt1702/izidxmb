<?php

namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class Jobs extends Model
{
    //
    public function __construct(Request $o_Request) {
        if($o_Request->session()->has('allSession')) {
            $a_sessionAll = $o_Request->session()->get('allSession');
            if(!isset($a_sessionAll['allJobs']) )
            {
                $allData = $this->getAll();
                $o_Request->session()->set('allSession',array('allJobs' => $allData));
            }
        } else {
            $allData = $this->getAll();
            $o_Request->session()->set('allSession',array('allJobs' => $allData));
        }
    }
    
/**
 *@Auth: HuyNN
 *@Des: get all record table Jobs 
 *@Since: 09/01/2015 
 */    
    public function getAll(){
        $a_jobs = DB::table('jobs')->paginate(10);   
        return $a_jobs;
    }
    
/**
 *@Auth: Luongnv
 *@Des: get all search record table Jobs 
 *@Since: 23/02/2016
 */    
    public function getAllSearch(){
        $o_Db = DB::table('jobs');
        $a_data = array();
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
        $a_data = $o_Db->paginate(10);
        $a_return = array('a_data' => $a_data, 'a_search' => $a_search);
        return $a_return;
    }
}
