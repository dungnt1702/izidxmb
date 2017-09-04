
<?php $__env->startSection('content'); ?>

<form class="form-horizontal" method="post" action="">
    <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Controller/Action</th>
                <th>ACTIVE</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($a_AllRole as $sz_Controller => $a_Action): ?>           
                <tr>
                    <td colspan="4"><strong><?php echo e($a_NameController[$sz_Controller]); ?></strong>&nbsp &nbsp<input type="checkbox" id="<?php echo e($sz_Controller); ?>" onclick="GLOBAL_JS.v_fCheckAllRoleGroup('<?php echo e($sz_Controller); ?>')"></td>
                </tr>
                <?php foreach($a_Action as $key => $sz_ActionName): ?>
                    <tr>
                        <td>&nbsp &nbsp &nbsp <?php echo e($key); ?> </td>
                        <td>
                            <input type="checkbox" value="1" name="<?php echo e($sz_Controller.'['.$sz_ActionName.']'); ?>" id="" class="<?php echo e($sz_Controller); ?>" <?php if(isset($a_RoleActive[$sz_Controller])  && in_array($sz_ActionName, $a_RoleActive[$sz_Controller])) echo "checked"; ?> />
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="form-group">
        <div class="col-xs-12 col-sm-3 no-padding">
            <input class="btn btn-warning btn-sm" type="reset" value="Nhập lại" >
            <input class="btn btn-primary btn-sm" id="btn_submit_data" name="submit" value="Cập nhật" type="submit">
        </div>
    </div>    
</form>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>