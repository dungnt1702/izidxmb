
<?php $__env->startSection('content'); ?>
<h3 class="col-xs-12 no-padding">Danh Mục User</h3>
    <form method="get" action="" id="frmFilter" name="frmFilter"  class="form-inline">
        <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
        <?php if(in_array($a_Role[Auth::user()->role_id], array('superadmin','reporter 1','reporter 2'))) { ?>
            <div class="form-group">
                <select id="search_department" name="search_department" class="form-control input-sm">
                    <option value="">Chọn phòng ban</option>
                    <?php foreach ($a_Departments as $i_Department => $sz_Deparment) { ?>
                        <option value="<?php echo $i_Department?>" <?php echo isset($a_search['search_department']) && $a_search['search_department'] == $i_Department?'selected':''?>><?php echo $sz_Deparment?></option>
                    <?php } ?>
                </select>
            </div>
        <?php } ?>
        
        <div class="form-group">
            <select id="search_position" name="search_position" class="form-control input-sm">
            <option value="">Chọn chức vụ</option>
            <?php foreach ($a_Positions as $i_Position => $sz_Position) { ?>
                <option value="<?php echo $i_Position?>" <?php echo isset($a_search['search_position']) && $a_search['search_position'] == $i_Position?'selected':''?>><?php echo $sz_Position?></option>
            <?php } ?>
            </select>
        </div>
        <div class="form-group">
            <select id="search_position" name="search_jobs" class="form-control input-sm">
            <option value="">Chọn nghề nghiệp</option>
            <?php foreach ($a_Jobs as $i_Job => $sz_Job) { ?>
                <option value="<?php echo $i_Job?>" <?php echo isset($a_search['search_jobs']) && $a_search['search_jobs'] == $i_Job?'selected':''?>><?php echo $sz_Job?></option>
            <?php } ?>
            </select>
        </div>
        <div class="form-group">
            <select id="search_by" name="search_by" class="form-control input-sm">
                <option value="">Tìm kiếm theo</option>
                <option value="code" <?php echo isset($a_search['search_by']) && $a_search['search_by'] == 'code'?'selected':''?>>Mã nhân viên</option>
                <option value="email" <?php echo isset($a_search['search_by']) && $a_search['search_by'] == 'email'?'selected':''?>>Email</option>
                <option value="name" <?php echo isset($a_search['search_by']) && $a_search['search_by'] == 'name'?'selected':''?>>Họ tên</option>
            </select>
        </div>
        <div class="form-group">
            <input id="search_field" name="search_field" type="text" class="form-control input-sm" placeholder="Nhập từ khóa" value="<?php echo isset($a_search['search_field'])?$a_search['search_field']:''?>">
        </div>
        <div class="form-group">
            <input type="button" class="btn btn-success btn-sm" value="Tìm kiếm" onclick="GLOBAL_JS.v_fSearchSubmit()">
            <input type="submit" class="btn btn-success btn-sm submit hide">
        </div>
        <div class="form-group">
            <a class="btn btn-primary btn-sm" href="/update_user_staff?token=DxmbMailApi2016" id="update_user">Cập nhật User</a>
        </div>
    </form>

    <table class="table table-responsive table-hover table-striped table-bordered">
        <tr>
            <td><strong>STT</strong></td>
            <td><strong>Mã nhân viên</strong></td>
            <td><strong>Tên</strong></td>
            <td><strong>Email</strong></td>
            <td class="text-center"><strong>Trạng thái</strong></td>
            <td class="text-center"><strong>Phòng ban</strong></td>      
            <td class="text-center"><strong>Chức vụ</strong></td> 
            <td class="text-center"><strong>Nghề nghiệp</strong></td>  
            <?php if(in_array($a_Role[Auth::user()->role_id], array('superadmin','reporter 1','reporter 2'))) { ?>
            <td><strong>Action</strong></td>
            <?php } ?>
            
        </tr>
    <?php foreach($users as $key => $user): ?>

        <tr>
            <td><?php echo ($key + $users->perPage() * $users->currentPage() - $users->perPage() + 1 )?></td>
            <td>    <?php echo e($user->code); ?></td>
            <td>    <?php echo e($user->name); ?></td>
            <td>    <?php echo e($user->email); ?></td>
            <th class="text-center"> <input id="status_<?= $user->id;?>" name="status_<?=$user->id;?>" type="checkbox" class="" value="1" <?php if($user->status == 1) echo "checked"?> disabled/></th>
            <td><?php echo isset($a_Departments[$user->department_id])?$a_Departments[$user->department_id]:'' ?></td>
            <td class="text-center">    <?php
                    echo isset($a_Positions[$user->position_id]) ? $a_Positions[$user->position_id] : '';
            ?></td>
            <td><?php echo isset($a_Jobs[$user->job_id])?$a_Jobs[$user->job_id]:'' ?></td>
            <?php if(in_array($a_Role[Auth::user()->role_id], array('superadmin','reporter 1','reporter 2'))) { ?>
                <td>
                    <?php
                        if($user->status == 1 || $user->status == 0){
                    ?>
                    <a title="Edit" href="<?php echo Request::root().'/user/edit/'.$user->id;?>" title="Edit" class="not-underline">
                        <i class="fa fa-edit fw"></i>
                    </a>
                    <a id="trash_switch_" href="javascript:GLOBAL_JS.v_fDelRow(<?php echo e($user->id); ?>,1)" title="Cho vào thùng rác" class="not-underline">
                    <i class="fa fa-trash fa-fw text-danger"></i>
                    </a>
                    <?php }else{ ?>
                    <a title="Khôi phục user" href="javascript:GLOBAL_JS.v_fRecoverRow(<?php echo e($user->id); ?>)"  title="Edit" class="not-underline">
                        <i class="fa fa-upload fw"></i>
                    </a>
                    <a id="trash_switch_" href="javascript:GLOBAL_JS.v_fDelRow(<?php echo e($user->id); ?>,2)" title="Xóa vĩnh viễn" class="not-underline">
                        <i class="fa fa-trash-o fa-fw text-danger"></i>
                    </a>
                    <?php }?>
                </td>
            <?php } ?>
            
        </tr>
    <?php endforeach; ?>
    </table>

<!--Hidden input-->
<input type="hidden" name="tbl" id="tbl" value="users">
    <?php echo (empty($a_search))?$users->render():$users->appends($a_search)->render();?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>