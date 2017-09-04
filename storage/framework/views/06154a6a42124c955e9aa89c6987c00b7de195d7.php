<?php $__env->startSection('content'); ?>
    <h3 class="col-xs-12 no-padding">Danh sách đánh giá tháng</h3>

    <table class="table table-responsive table-hover table-striped table-bordered">
        <tr>
            <td><strong>STT</strong></td>
            <td><strong>Tên nhân viên</strong></td>
            <td><strong>Tháng</strong></td>
            <td><strong>Năm</strong></td>
            <td><strong>Tổng điểm</strong></td>
            <td><strong>Xếp loại</strong></td>
            <td><strong>Action</strong></td>
        </tr>
        <?php foreach($dataCheckPoint as $key => $o_checkpoint): ?>
            <tr>
                <td><?php echo e($key +1); ?></td>
                <td> <?php echo e(Auth::user()->name); ?></td>
                <td>    <?php echo e($o_checkpoint->month); ?></td>
                <td>    <?php echo e($o_checkpoint->year); ?></td>
                <td>    <?php echo e($o_checkpoint->total_point); ?></td>
                <td>    <?php echo e($o_checkpoint->level_point); ?></td>
                <td>
                    <a href="/checkpoint-by-month?id=<?php echo $o_checkpoint->id?>"><button type="button" class="btn-sm btn btn-danger"><?php echo $o_checkpoint->status == 1?'Sửa lại':'Xem'?></button></a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
    <!--Hidden input-->
    <input type="hidden" name="tbl" id="tbl" value="checkpoint">

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>