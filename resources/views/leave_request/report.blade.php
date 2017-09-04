@extends('layouts.app')

@section('content')
        <h3 class="col-xs-12 no-padding">Thống kê vắng mặt <?php echo $sz_TitleTime?></h3>
        <form method="get" action="" id="frmFilter" name="frmFilter"  class="form-inline">
            <input type="hidden" name="_token" value="{!! csrf_token() !!}">
            <input type="hidden" id="tbl" value="status_leave_request_report">
            <div class="form-group">
                <select id="search_department" name="search_department" class="form-control input-sm">
                    <option value="0">Chọn phòng ban</option>
                    <?php foreach ($a_Department as $val) { ?>
                        <option value="<?php echo $val->id?>" <?php echo isset($a_search['search_department']) && $a_search['search_department'] == $val->id?'selected':''?>><?php echo $val->name?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="form-group">
                <select id="search_position" name="search_position" class="form-control input-sm">
                <option value="0">Chọn chức vụ</option>
                <?php foreach ($a_Position as $val) { ?>
                    <option value="<?php echo $val->id?>" <?php echo isset($a_search['search_position']) && $a_search['search_position'] == $val->id?'selected':''?>><?php echo $val->name?></option>
                <?php } ?>
                </select>
            </div>
            <div class="form-group">
                <input id="search_name" name="search_name" type="text" class="form-control input-sm" placeholder="Tên nhân viên" value="<?php echo isset($a_search['search_name'])?$a_search['search_name']:''?>">
            </div>
            <div class="form-group">
                <select id="search_year" name="search_year" class="form-control input-sm">
                <option value="0">Chọn năm</option>
                <?php 
                    $current_year = date("Y");
                    for ($i = $current_year - 1; $i <= $current_year; $i++) {
                ?>
                <option value="<?php echo $i?>" <?php echo isset($a_search['search_year']) && $a_search['search_year'] == $i?'selected':''?>>Năm <?php echo $i?></option>
                <?php } ?>
                </select>
            </div>
            <div class="form-group">
                <select id="search_month" name="search_month" class="form-control input-sm">
                <option value="0">Chọn tháng</option>
                <?php 
                    $current_month = date("m");
                    for ($i = 1; $i <=12; $i++) { 
                ?>
                    <option value="<?php echo $i?>" <?php echo isset($a_search['search_month']) && $a_search['search_month'] == $i?'selected':''?>>Tháng <?php echo $i?></option>
                <?php } ?>
                </select>
            </div>
            <div class="form-group">
                <input type="button" class="btn btn-success btn-sm" value="Tìm kiếm" onclick="GLOBAL_JS.v_fSearchLeaveRequestReport()">
                <input type="submit" class="btn btn-success btn-sm submit hide">
            </div>
        </form>
        <table class="table table-responsive table-hover table-striped table-bordered">
            <tr>
                <td><strong>STT</strong></td>
                <td><strong>Họ tên</strong></td>
                <td><strong>Mã nhân viên</strong></td>
                <td><strong>Chức vụ</strong></td>
                <td><strong>Phòng ban</strong></td>
                <?php foreach ($a_RangeDate as $i_Date) { ?>
                <td>
                    <strong><?php echo $i_Date?></strong><br>
                    <span><?php echo $a_RangeDay[$i_Date]?></span><br>
                </td>
                <?php } ?>
            </tr>
            <?php if(isset($a_AllLeaveRequest)) { ?>
            <?php foreach ($a_AllLeaveRequest as $key => $o_UserLeaveRequest) { ?>
                <tr>
                    <td><?php echo ($key + $a_AllLeaveRequest->perPage() * $a_AllLeaveRequest->currentPage() - $a_AllLeaveRequest->perPage() + 1 )?></td>
                    <td><?php echo $o_UserLeaveRequest->name?></td>
                    <td><?php echo $o_UserLeaveRequest->code?></td>
                    <td><?php echo $o_UserLeaveRequest->position_name?></td>
                    <td class="width_department"><?php echo $o_UserLeaveRequest->department_name?></td>
                    <?php foreach ($a_RangeDate as $i_Date) { ?>
                    <td class="td_report">
                        <?php if(is_array($o_UserLeaveRequest->$i_Date))
                        {
                            $a_Info = $o_UserLeaveRequest->$i_Date;
                            if(is_array($a_Info[0])) {
                                foreach ($a_Info as $a_val) { ?>
                                    <div><a href="" data-toggle="modal" data-target="#popup-report-<?php echo $o_UserLeaveRequest->user_id.'-'.$i_Date.'-'.$a_val[4]?>"><?php echo $a_val[0] == 'ct'?$a_val[3]:$a_val[0];?></a></div>
                                    <!-- Modal -->
                                    <div class="modal fade modal-<?php echo $o_UserLeaveRequest->user_id.'-'.$i_Date.'-'.$a_val[4]?>" id="popup-report-<?php echo $o_UserLeaveRequest->user_id.'-'.$i_Date.'-'.$a_val[4]?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-body col-xs-12 col-sm-12">
                                                    <div class="no-padding">
                                                        <div class="col-xs-12 col-sm-12 no-padding">
                                                            <ul class="list-group">
                                                                <li class="list-group-item active">Thông tin vắng mặt</li>
                                                                <li class="list-group-item">Tên nhân viên: <strong><?php echo $o_UserLeaveRequest->name?></strong></li>
                                                                <li class="list-group-item">Chức vụ: <strong><?php echo $o_UserLeaveRequest->position_name?></strong></li>
                                                                <li class="list-group-item">Phòng ban: <strong><?php echo $o_UserLeaveRequest->department_name?></strong></li>
                                                                <li class="list-group-item">Loại: <strong><?php echo $a_val[1]?></strong></li>
                                                                <li class="list-group-item">Nội dung: <strong class="down-line-text"><?php echo $a_val[2]?></strong></li>
                                                                <li class="list-group-item">Thời gian: <strong><?php echo $a_val[3]?></strong></li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    
                                                </div>
                                            </div>
                                        </div>
                                    </div>    
                               <?php } ?> 
                            <?php } else { ?>
                            <div><a href="" data-toggle="modal" data-target="#popup-report-<?php echo $o_UserLeaveRequest->user_id.'-'.$i_Date?>"><?php echo $a_Info[0] == 'ct'?$a_Info[3]:$a_Info[0];?></a></div>
                            <!-- Modal -->
                            <div class="modal fade modal-<?php echo $o_UserLeaveRequest->user_id.'-'.$i_Date?>" id="popup-report-<?php echo $o_UserLeaveRequest->user_id.'-'.$i_Date?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-body col-xs-12 col-sm-12">
                                            <div class="no-padding">
                                                <div class="col-xs-12 col-sm-12 no-padding">
                                                    <ul class="list-group">
                                                        <li class="list-group-item active">Thông tin vắng mặt</li>
                                                        <li class="list-group-item">Tên nhân viên: <strong><?php echo $o_UserLeaveRequest->name?></strong></li>
                                                        <li class="list-group-item">Chức vụ: <strong><?php echo $o_UserLeaveRequest->position_name?></strong></li>
                                                        <li class="list-group-item">Phòng ban: <strong><?php echo $o_UserLeaveRequest->department_name?></strong></li>
                                                        <li class="list-group-item">Loại: <strong><?php echo $a_Info[1]?></strong></li>
                                                        <li class="list-group-item">Nội dung: <strong class="down-line-text"><?php echo $a_Info[2]?></strong></li>
                                                        <li class="list-group-item">Thời gian: <strong><?php echo $a_Info[3]?></strong></li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php } ?>
                            <div><a><i class="fa <?php echo (isset($a_UserSttReport[$o_UserLeaveRequest->user_id][$a_search['search_year']][$a_search['search_month']][$i_Date]) && $a_UserSttReport[$o_UserLeaveRequest->user_id][$a_search['search_year']][$a_search['search_month']][$i_Date] == 1?'fa-check-square-o':'fa-square-o') ?> check_enough" user_id="<?php echo $o_UserLeaveRequest->user_id?>" date="<?php echo $i_Date?>" month="<?php echo $a_search['search_month']?>" year="<?php echo $a_search['search_year']?>"></i></a></div>
                    <?php } ?>
                    </td>
                    <?php } ?>
                </tr>
            <?php } ?>
            <?php } ?>
        </table>
        @if (count($a_AllLeaveRequest) == 0)
            <div class="alert alert-danger no-data">
                <tr>
                    <strong>{{ 'Chưa có danh sách vắng mặt nào' }}</strong>
                </tr>
            </div>
        @endif
        <?php echo (empty($a_search))?$a_AllLeaveRequest->render():$a_AllLeaveRequest->appends($a_search)->render();?>
@endsection
