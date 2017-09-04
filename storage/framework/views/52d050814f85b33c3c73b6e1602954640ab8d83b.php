
<?php $__env->startSection('content'); ?>

<div class="col-xs-6 alert alert-success">
  <strong>Chú ý!</strong> bạn cần nhập file (xls, xlsx) <br/>
  File phải bao gồm các cột là email, code (mã nhân viên), name (tên), direct_manager_code (mã nhân viên của quản lý), 
  position(Chức vụ), department(Phòng ban), job(Ngành nghề)
</div>
<h3 class="col-xs-12 no-padding">Import user</h3>
<form class="form-horizontal" method="post" action="" enctype="multipart/form-data">
    <?php if(isset($a_Res)): ?>
    <div class="form-group">
        <div class="col-xs-12 col-sm-12 no-padding">
            <label class="alert alert-warning"><?php echo $a_Res; ?></label>
        </div>
    </div>
    <?php endif; ?>
    <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
    <div class="form-group">
        <div class="col-xs-12 col-sm-6 no-padding">            
            <input type="file" name="excel" id="excel" />            
        </div>
    </div>
    <div class="form-group">
        <div class="col-xs-12 col-sm-6 no-padding">
            <button type="reset" class="btn btn-default">Nhập lại</button>
            <input type="submit" name="submit" VALUE="Cập nhật" class="btn btn-primary btn-sm ">
        </div>
    </div>
</form>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>