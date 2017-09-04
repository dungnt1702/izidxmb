
<?php $__env->startSection('content'); ?>

<h3 class="col-xs-12 no-padding">Danh sách nhóm quyền hạn</h3>
<form method="get" action="" id="frmFilter" name="frmFilter"  class="form-inline">
    <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
    <div class="form-group">
        <select id="search_status" name="search_status" class="form-control input-sm">
            <option value="">Trạng thái</option>
            <option value="1" <?php echo isset($a_search['search_status']) && $a_search['search_status'] == 1?'selected':''?>>Trạng thái Hiển thị</option>
            <option value="0" <?php echo isset($a_search['search_status']) && $a_search['search_status'] == 0?'selected':''?>>Trạng thái Ẩn</option>
            <option value="2" <?php echo isset($a_search['search_status']) && $a_search['search_status'] == 2?'selected':''?>>Trạng thái Thùng rác</option>
        </select>
    </div>
    <div class="form-group">
        <input id="search_field" name="search_field" type="text" class="form-control input-sm" placeholder="Nhập tên nhóm quyền" value="<?php echo isset($a_search['search_field'])?$a_search['search_field']:''?>">
    </div>
    <div class="form-group">
        <input type="button" class="btn btn-success btn-sm" value="Tìm kiếm" onclick="GLOBAL_JS.v_fSearchSubmitAll()">
        <input type="submit" class="btn btn-success btn-sm submit hide">
    </div>
</form>
    <div class="">
        <table class="table table-responsive table-hover table-striped table-bordered">
            <tr>
                <td><strong>STT</strong></td>
                <td><strong>Tên nhóm quyền</strong></td>
                <td><strong>Trạng thái</strong></td>
                <td><strong>Ngày tạo</strong></td>
                <td><strong>Ngày sửa</strong></td>
                <td><strong>Action</strong></td>

            </tr>
        <?php foreach($a_RoleGroup as $a_val): ?>
            <tr>
                <td>    <?php echo e($a_val->stt); ?></td>
                <td>    <?php echo e($a_val->name); ?></td>
                <th class="text-center"> <input id="status_<?= $a_val->id;?>" name="status_<?=$a_val->id;?>" type="checkbox" class="" value="1" <?php if($a_val->status == 1) echo "checked"?> disabled/></th>
                <td>    <?php echo e($a_val->created_at); ?></td>
                <td>    <?php echo e($a_val->updated_at); ?></td>
                <td> 
                    <?php
                        if($a_val->status == 1 || $a_val->status == 0){
                    ?>
                    <a title="Edit" href="<?php echo Request::root().'/role/addedit?id='.$a_val->id;?>" title="Edit" class="not-underline">
                        <i class="fa fa-edit fw"></i>
                    </a>
                    <a id="trash_switch_" href="javascript:GLOBAL_JS.v_fDelRow(<?php echo e($a_val->id); ?>,1)" title="Cho vào thùng rác" class="not-underline">
                    <i class="fa fa-trash fa-fw text-danger"></i>
                    </a>
                    <?php }else{ ?>
                    <a title="Khôi phục user" href="javascript:GLOBAL_JS.v_fRecoverRow(<?php echo e($a_val->id); ?>)"  title="Edit" class="not-underline">
                        <i class="fa fa-upload fw"></i>
                    </a>
                    <a id="trash_switch_" href="javascript:GLOBAL_JS.v_fDelRow(<?php echo e($a_val->id); ?>,2)" title="Xóa vĩnh viễn" class="not-underline">
                        <i class="fa fa-trash-o fa-fw text-danger"></i>
                    </a>
                    <?php }?>
                </td>
            </tr>
        <?php endforeach; ?>
        </table>
    </div>

<!--Hidden input-->
<input type="hidden" name="tbl" id="tbl" value="rolegroups">

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>