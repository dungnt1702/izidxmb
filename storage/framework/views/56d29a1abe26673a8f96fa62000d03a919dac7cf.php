
<?php $__env->startSection('content'); ?>

<h3 class="col-xs-12 no-padding"><?php echo $i_id == ''?'Thêm nhóm mới':'Sửa nhóm'?></h3>
<div class="alert alert-danger hide"></div>
<form class="form-horizontal" method="post" action="">
    <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
    <input type="hidden" id="id" value="<?php echo $i_id?>">
    <input type="hidden" id="tbl" value="groups">
    <div class="form-group">
        <div class="col-xs-12 col-sm-6 no-padding">
            <label for="name" class="col-xs-12 col-sm-3 control-label text-left">Tên nhóm</label>
            <div class="col-xs-12 col-sm-9 no-padding">
                <input id="name" name="name" type="text" field-name="Tên nhóm" <?php echo $i_id == 0 ? '' : 'old_val="'.$a_Group->name.'"'?> value="<?php echo isset($a_Group->name)?$a_Group->name:"" ?>" class="form-control check-duplicate" placeholder="Tên nhóm" required />
            </div>
        </div>
        
    </div>
    <div class="form-group">        
        <div class="col-xs-12 col-sm-6 no-padding">
            <label for="guid" class="col-xs-12 col-sm-3 control-label text-left">Phòng ban</label>
            <div class="col-xs-12 col-sm-9 no-padding">
                <select id="department_id" class="form-control" size="1" name="department_id">
                        <option value="">Chọn phòng ban</option>
                        <?php foreach($a_Department as $o_val): ?>
                        <option value="<?php echo e($o_val->id); ?>" <?php if(isset($a_Group->department_id) && $a_Group->department_id == $o_val->id) echo "selected"; ?>><?php echo e($o_val->name); ?></option>
                        <?php endforeach; ?>
                </select>
            </div>
        </div>
    </div>
   
    <div class="form-group">
        <div class="col-xs-12 col-sm-3 no-padding">
            <label for="status" class="col-xs-6 control-label text-left">Trạng thái hiển thị</label>
            <div class="col-xs-6 no-left-padding">
                <input id="status" name="status" type="checkbox" class="form-control" <?php if (isset($a_Group->status) && $a_Group->status): ?>checked<?php endif ?>>
            </div>            
        </div>
    </div>
    <?php if($i_id == 0) { ?>
    <div class="form-group">
        <div class="col-xs-12 col-sm-3 no-padding">
            <label for="status" class="col-xs-6 control-label text-left">Thêm nhiều</label>
            <div class="col-xs-6 no-left-padding">
                <input id="multi-insert" name="multi-insert" type="checkbox" class="form-control">
            </div>            
        </div>
    </div>
    <?php } ?>
    <div class="form-group">
        <div class="col-xs-12 col-sm-3 no-padding">
            <button type="reset" class="btn btn-default">Nhập lại</button>
            <input type="button" name="submit" VALUE="Cập nhật" class="btn btn-primary btn-sm " onclick="GLOBAL_JS.v_fSubmitGroupValidate()"/>
            <input type="submit" name="submit" class="btn btn-primary btn-sm hide submit">
        </div>
    </div>    
</form>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>