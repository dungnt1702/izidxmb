<?php

namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class Positions extends Model
{
    //
    public function __construct(Request $o_Request) {

    }
    
/**
 *@Auth: Dienct
 *@Des: get all record table positions 
 *@Since: 30/12/2015 
 */    
    public function getAll(){
        $a_data = DB::table('positions')->paginate(20);
        return $a_data;
    }
    
/**
 *@Auth: Luongnv
 *@Des: get allsearch record table positions 
 *@Since: 23/02/2016
 */    
    public function getAllSearch(){
        $o_Db = DB::table('positions');
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
        $a_data = $o_Db->paginate(20);
        $a_return = array('a_data' => $a_data, 'a_search' => $a_search);
        return $a_return;
    }
}
