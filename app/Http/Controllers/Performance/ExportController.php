<?php

namespace App\Http\Controllers\Performance;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Users;
use stdClass;
use App\Models\Department;

class ExportController extends Controller
{
    //
    public function __construct()
    {
        $this->o_user =  new Users();
        $this->o_department =  new Department();
    }
    public function export_checkpoint() {
        $sz_Sql = Session::get('sql_checkpoint');
        $a_Select = explode('from', $sz_Sql);
        $a_Select[0] = str_replace("`name`","`name` as `Tên`",$a_Select[0]);
        $a_Select[0] = str_replace("`department_name`","`department_name` as `Phòng`",$a_Select[0]);
        $a_Select[0] = str_replace("`code`","`code` as `MNV`",$a_Select[0]);
        $sz_Sql = $a_Select[0].'from'.$a_Select[1];
        if(strpos($sz_Sql, 'limit') !== false){
            $arr =  explode('limit',$sz_Sql);
            $sz_Sql = $arr[0];
        }
        $a_MergeTimeSheet = DB::select(DB::raw($sz_Sql));
        try{
            Excel::create('Bang_CheckPoint', function($excel) use($a_MergeTimeSheet) {
                // Set the title
                $excel->setTitle('no title');
                $excel->setCreator('no no creator')->setCompany('no company');
                $excel->setDescription('report file');
                $excel->sheet('sheet1', function($sheet) use($a_MergeTimeSheet) {
                    foreach ($a_MergeTimeSheet as $key => $o_person) {

                        $o_person1 = new stdClass();
                        $o_person1->STT = $key +1;
                        $o_person1->MNV = $this->o_user->sz_fInfoUserById($o_person->user_id)->code;
                        $o_person1->Ten = $this->o_user->sz_fInfoUserById($o_person->user_id)->name;
                        if($o_person->department_id != ''){
                            $o_person1->Phong = $this->o_department->getDepartmentById($o_person->department_id)->name;
                        }else{
                            $o_person1->Phong = '';
                        }

                        $o_person1->CT_khai_thác_KH = $o_person->exploit_customer;
                        $o_person1->Chi_tiêu_doanh_thu = $o_person->revenue;
                        $o_person1->Báo_cáo_tuần = $o_person->report_week;
                        $o_person1->Tinh_thần_thái_độ_tác_phong_lv = $o_person->morale;
                        $o_person1->phối_kết_hợp = $o_person->connect;
                        $o_person1->Văn_hóa_giao_tiếp = $o_person->cultural;
                        $o_person1->Ý_thức_chấp_hành_kỷ_luật = $o_person->discipline;
                        $o_person1->Chất_lượng_công_việc = $o_person->work_quality;
                        $o_person1->Tiến_độ_thực_hiện_công_việc = $o_person->progress;
                        $o_person1->Tổng_điểm = $o_person->total_point;
                        $o_person1->Xếp_loại = $o_person->level_point;
                        $o_person1->Tháng = $o_person->month;
                        $o_person1->Năm = $o_person->year;

                        $ary[] = (array) $o_person1;
                    }
                    if(isset($ary)){
                        $sheet->fromArray($ary);
                    }
                    $sheet->cells('A1:BM1', function($cells) {
                        $cells->setFontWeight('bold');
                        $cells->setBackground('#AAAAFF');
                        $cells->setFont(array(
                            'bold' => true
                        ));
                    });
                });
            })->download('xlsx');
        }catch (\Exception $e){
            echo $e->getMessage();
        }
    }
}
