<?php $__env->startSection('content'); ?>
    <h3 class="col-xs-12 no-padding">Lỗi vi phạm trong tháng</h3>
    <form method="get" action="" id="frmFilter" name="frmFilter"  class="form-inline">
        <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
        <div class="form-group">
            <select id="search_year" name="search_year" class="form-control input-sm">
                <option value="0">Chọn năm</option>
                <?php
                $current_year = date("Y");
                for ($i = $current_year - 1; $i <= $current_year; $i++) {
                ?>
                <option value="<?php echo $i?>" <?php echo isset($a_Data['a_search']['search_year']) && $a_Data['a_search']['search_year'] == $i?'selected':''?>>Năm <?php echo $i?></option>
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
                <option value="<?php echo $i?>" <?php echo isset($a_Data['a_search']['search_month']) && $a_Data['a_search']['search_month'] == $i?'selected':''?>>Tháng <?php echo $i?></option>
                <?php } ?>
            </select>
        </div>
        <div class="form-group">
            <select id="search_department" name="search_department" class="form-control input-sm">
                <option value="0">Chọn phòng ban</option>
                <?php foreach ($a_Data['a_Departments'] as $i_Department => $sz_Deparment) { ?>
                <option value="<?php echo $i_Department?>" <?php echo isset($a_Data['a_search']['search_department']) && $a_Data['a_search']['search_department'] == $i_Department?'selected':''?>><?php echo $sz_Deparment?></option>
                <?php } ?>
            </select>
        </div>
        <div class="form-group">
            <select id="search_by" name="search_by" class="form-control input-sm">
                <option value="">Tìm kiếm theo</option>
                <option value="code" <?php echo isset($a_Data['a_search']['search_by']) && $a_Data['a_search']['search_by'] == 'code'?'selected':''?>>Mã nhân viên</option>
                <option value="name" <?php echo isset($a_Data['a_search']['search_by']) && $a_Data['a_search']['search_by'] == 'name'?'selected':''?>>Họ tên</option>
            </select>
        </div>
        <div class="form-group">
            <input id="search_field" name="search_field" type="text" class="form-control input-sm" placeholder="Nhập từ khóa" value="<?php echo isset($a_Data['a_search']['search_field'])?$a_Data['a_search']['search_field']:''?>">
        </div>
        <div class="form-group">
            <input type="button" class="btn btn-success btn-sm" value="Tìm kiếm" onclick="GLOBAL_JS.v_fSearchLeaveRequestReport()">
            <a class="btn btn-primary btn-sm" href="/merge_error" target="_blank">Merge Lỗi vi phạm</a>
            <a class="btn btn-primary btn-sm" href="/export_excel_error" target="_blank">Export Excel</a>
            <input type="submit" class="btn btn-success btn-sm submit hide">
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
            <td class="width_department"><?php echo $o_Timesheet->name?></td>
            <td><?php echo $o_Timesheet->code?></td>
            <td class="width_department">
                <?php echo isset($a_Data['a_Departments'][$o_Timesheet->department_id])?$a_Data['a_Departments'][$o_Timesheet->department_id]:'' ?>
            </td>
            <?php foreach($a_Data['a_RangeDate'] as $i_Date): ?>
            <td class="td_report">
                <?php echo e($o_Timesheet->$i_Date); ?>

            </td>
            <?php endforeach; ?>
        </tr>
        <?php } ?>
        <?php } ?>
    </table>
    <?php if(count($a_Data['a_AllTimeSheet']) == 0): ?>
        <div class="alert alert-danger no-data">
            <tr>
                <strong><?php echo e('Chưa có bảng công'); ?></strong>
            </tr>
        </div>
    <?php endif; ?>
    <?php echo (empty($a_Data['a_search']))?$a_Data['a_AllTimeSheet']->render():$a_Data['a_AllTimeSheet']->appends($a_Data['a_search'])->render();?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>