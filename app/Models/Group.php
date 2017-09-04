<?php

namespace App\Models;
use DB;
use Illuminate\Database\Eloquent\Model;
use App\Util;
use Illuminate\Support\Facades\Input;

class Group extends Model
{
    public function __construct() {
        
    }
    
    /**
    *@Auth: Dienct
    *@Des: get all record table group
    *@Since: 07/01/2015
    */
    public function getAll(){
        $a_Data = array();
        $a_Data = DB::table('groups')->select('id','name','status','created_at','updated_at','department_id')->orderBy('updated_at', 'desc')->paginate(15);
        foreach($a_Data as $key =>&$val){
                $val->stt = $key + 1;
                $val->created_at = Util::sz_DateTimeFormat($val->created_at);
                $val->updated_at = Util::sz_DateTimeFormat($val->updated_at);
        }
        
        return $a_Data;
    }
    
    /**
    *@Auth: Luongnv
    *@Des: get allsearch record table group
    *@Since: 23/02/2016
    */
    public function getAllSearch(){
        $a_data = array();
        $o_Db = DB::table('groups')->select('id','name','status','created_at','updated_at','department_id');
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
        $a_data = $o_Db->orderBy('updated_at', 'desc')->paginate(15);
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
     * @Des: Get infomation group by Id
     * @Since: 11/1/2016
     */
    public function getGroupById($department_id){

        $a_Data = array();
        $a_Data = DB::table('groups')->where('id', $department_id)->first();
        if(count($a_Data) > 0) $a_Data->created_at = Util::sz_DateTimeFormat($a_Data->created_at);
        if(count($a_Data) > 0) $a_Data->updated_at = Util::sz_DateTimeFormat($a_Data->updated_at);

        return $a_Data;
    }
    
    /**

     * @Auth: Dienct
     * @Des: Add/edit group
     * @Since: 11/1/2016
     */
    public function AddEditGroup($i_GroupID){
        $a_DataUpdate = array();
        $a_DataUpdate['name'] = Input::get('name');
        $a_DataUpdate['department_id'] = Input::get('department_id');
        $a_DataUpdate['status'] = Input::get('status') == 'on'? 1 : 0;
        if(is_numeric($i_GroupID) == true && $i_GroupID != 0){
            $a_DataUpdate['updated_at'] = date('Y-m-d H:i:s',time());
            DB::table('groups')->where('id', $i_GroupID)->update($a_DataUpdate);
        }else{
            $a_DataUpdate['created_at'] = date('Y-m-d H:i:s',time());
            $a_DataUpdate['updated_at'] = date('Y-m-d H:i:s',time());
            DB::table('groups')->insert($a_DataUpdate);
        }

    }
    
}
