@extends('layouts.app')
@section('content')
    <h3 class="col-xs-12 no-padding">Bảng công tháng {{ $a_Data['sz_TitleTime'] }}</h3>
    <form method="get" action="" id="frmFilter" name="frmFilter"  class="form-inline">
        <input type="hidden" name="_token" value="{!! csrf_token() !!}">
        <div class="form-group">
            <select id="search_year" name="search_year" class="form-control input-sm">
            <option value="0">Chọn năm</option>
            <?php 
                $current_year = date("Y");
                for ($i = $current_year - 1; $i <= $current_year; $i++) {
            ?>
            <option value="<?php echo $i?>" <?php echo isset($a_Search['search_year']) && $a_Search['search_year'] == $i?'selected':''?>>Năm <?php echo $i?></option>
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
                <option value="<?php echo $i?>" <?php echo isset($a_Search['search_month']) && $a_Search['search_month'] == $i?'selected':''?>>Tháng <?php echo $i?></option>
            <?php } ?>
            </select>
        </div>
        <div class="form-group">
            <select id="search_department" name="search_department" class="form-control input-sm">
                <option value="0">Chọn phòng ban</option>
                <?php foreach ($a_Data['a_Departments'] as $i_Department => $sz_Deparment) { ?>
                    <option value="<?php echo $i_Department?>" <?php echo isset($a_Search['search_department']) && $a_Search['search_department'] == $i_Department?'selected':''?>><?php echo $sz_Deparment?></option>
                <?php } ?>
            </select>
        </div>
        <div class="form-group">
            <select id="search_by" name="search_by" class="form-control input-sm">
                <option value="">Tìm kiếm theo</option>
                <option value="code" <?php echo isset($a_Search['search_by']) && $a_Search['search_by'] == 'code'?'selected':''?>>Mã nhân viên</option>
                <option value="name" <?php echo isset($a_Search['search_by']) && $a_Search['search_by'] == 'name'?'selected':''?>>Họ tên</option>
            </select>
        </div>
        <div class="form-group">
            <input id="search_field" name="search_field" type="text" class="form-control input-sm" placeholder="Nhập từ khóa" value="<?php echo isset($a_Search['search_field'])?$a_Search['search_field']:''?>">
        </div>
        <div class="form-group">
            <input type="button" class="btn btn-success btn-sm" value="Tìm kiếm" onclick="GLOBAL_JS.v_fSearchLeaveRequestReport()">
            <input type="submit" class="btn btn-success btn-sm submit hide">
            <a href="<?php echo Request::root()."/export_excel"?>"><input type="button" class="btn btn-info btn-sm" value="Export excel"></a>
        </div>
    </form>
    <table class="table table-responsive table-hover table-striped table-bordered">
        <tr>
            <td><strong>STT</strong></td>
            <td><strong>Họ tên</strong></td>
            <td><strong>Mã nhân viên</strong></td>
            <td><strong>Phòng ban</strong></td>
            <?php foreach ($a_Data['a_RangeDate'] as $i_Date) { ?>
            <td>
                <strong><?php echo $i_Date?></strong><br>
                <span><?php echo $a_Data['a_RangeDay'][$i_Date]?></span><br>
            </td>
            <?php } ?>
        </tr>
        <?php if(isset($a_Data['a_AllTimeSheet'])) { ?>
        <?php foreach ($a_Data['a_AllTimeSheet'] as $key => $o_Timesheet) { ?>
        <?php 
        ?>
            <tr>
                <td><?php echo ($key + $a_Data['a_AllTimeSheet']->perPage() * $a_Data['a_AllTimeSheet']->currentPage() - $a_Data['a_AllTimeSheet']->perPage() + 1 )?></td>
                <td><?php echo $o_Timesheet->name?></td>
                <td><?php echo $o_Timesheet->code?></td>
                <td class="width_department">
                    <?php echo isset($a_Data['a_Departments'][$o_Timesheet->department_id])?$a_Data['a_Departments'][$o_Timesheet->department_id]:'' ?>
                </td>
                <?php foreach ($a_Data['a_RangeDate'] as $key => $i_Date) { ?>
                <td class="td_report" data-toggle="tooltip" data-placement="right" <?php echo $o_Timesheet->$a_Data['a_RangeDate_Cmt'][$key] !=''?('style="background: #dff0d8"'):''?> title="<?php echo $o_Timesheet->$a_Data['a_RangeDate_Cmt'][$key];?>">
                    <?php echo $o_Timesheet->$i_Date;?>
                </td>
                <?php } ?>
            </tr>
        <?php } ?>
        <?php } ?>
    </table>
        @if (count($a_Data['a_AllTimeSheet']) == 0)
            <div class="alert alert-danger no-data">
                <tr>
                    <strong>{{ 'Chưa có bảng công' }}</strong>
                </tr>
            </div>
        @endif
        <?php echo (empty($a_Search))?$a_Data['a_AllTimeSheet']->render():$a_Data['a_AllTimeSheet']->appends($a_Search)->render();?>
@endsection