
<?php $__env->startSection('content'); ?>

<h3 class="col-xs-12 no-padding">Danh sách vắng mặt</h3>
    <div class="">
        <table class="table table-responsive table-hover table-striped table-bordered">
            <tr>
                <td><strong>STT</strong></td>
                <td><strong>Nội dung</strong></td>
                <td><strong>Trạng thái</strong></td>
                <td><strong>Loại</strong></td>
                <td><strong>Thời gian</strong></td>
                <td><strong>Quản lý comment</strong></td>
                <td><strong>HRM comment</strong></td>
                <td><strong>Ngày tạo</strong></td>
                <td><strong>Action</strong></td>
            </tr>
        
        <?php foreach($a_leaveRequest as $a_val): ?>
        <tr id="tr_<?=$a_val->id?>">
                <td>    <?php echo e($a_val->stt); ?></td>
                <td>    <?php echo e($a_val->user_comment); ?></td>
                <td>    <?php echo e($a_val->status); ?></td>
                <td>    <?php echo e($a_val->leave_type_name); ?></td>
                <td>    <?php echo $a_val->time?></td>
                <td>    <?php echo e($a_val->manager_comment); ?></td>
                <td>    <?php echo e($a_val->hrm_comment); ?></td>
                <td>    <?php echo e($a_val->created_at); ?></td>
                <?php if($a_val->status == 'Đang chờ' || $a_val->delete == 1){ ?>
                <td><input type="button" VALUE="Xóa" class="btn btn-danger btn-md " onclick="GLOBAL_JS.v_fDeleteMyLeaveRequest('<?= $a_val->id; ?>')"/></td>
                <?php } else { ?>
                <td><input type="button" VALUE="Xóa" class="btn btn-danger btn-md" disabled/></td> 
                <?php } ?>
            </tr>
        <?php endforeach; ?>
        </table>
        <?php if(count($a_leaveRequest) == 0): ?>
            <div class="alert alert-danger no-data">
                <tr>
                    <strong><?php echo e('Bạn chưa có đơn vắng mặt cá nhân nào'); ?></strong>
                </tr>
            </div>
        <?php endif; ?>
    </div>

<!--Hidden input-->
<input type="hidden" name="tbl" id="tbl" value="leave_requests">
    <?php echo $a_leaveRequest->links(); ?>


<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>