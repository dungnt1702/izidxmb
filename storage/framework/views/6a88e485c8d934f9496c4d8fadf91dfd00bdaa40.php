
<?php $__env->startSection('content'); ?>
<h3 class="col-xs-12 no-padding font-title">Danh sách yêu cầu thay đổi Quản lý trực tiếp</h3>
    

    <table class="table table-responsive table-hover table-striped table-bordered">
        <tr>
            <td><strong>STT</strong></td>
            <td><strong>Mã NV</strong></td>
            <td><strong>Tên</strong></td>
            <td><strong>Email</strong></td>
            <td class="text-center"><strong>Phòng ban hiện tại</strong></td>
            <td class="text-center"><strong>TN hiện tại</strong></td> 
            <td class="text-center"><strong>Phòng ban mới</strong></td>      
            <td class="text-center"><strong>TN mới</strong></td>
            <td class="text-center"><strong>Nội dung</strong></td>
            <td class="text-center"><strong>Ngày gửi</strong></td>
            <td><strong>Trạng thái</strong></td>
        </tr>
        <?php if(count($a_user) > 0) { ?>
        <?php foreach($a_user as $key => $user): ?>
            <?php
                if($user->status == 0) $sz_disable = "active";
                else $sz_disable = "disabled";
            ?>
            <tr tr_<?php echo $user->id ?>>
                <td><?php echo ($key + $a_user->perPage() * $a_user->currentPage() - $a_user->perPage() + 1 )?></td>
                <td>    <?php echo e($user->code); ?></td>
                <td>    <?php echo e($user->name); ?></td>
                <td>    <?php echo e($user->email); ?></td>
                <td>    <?php echo e($user->old_department_name); ?></td>
                <td>    <?php echo e($user->old_manager_name); ?></td>
                <td>    <?php echo e($user->new_department_name); ?></td>
                <td>    <?php echo e($user->new_manager_name); ?></td>
                <td>    <?php echo e($user->user_comment); ?></td>
                <td>    <?php echo e($user->created_at); ?></td>
                <td>
                    <?php if($user->status == 0) { ?>
                    <button type="button" class="btn-sm btn btn-primary allow-change-manager <?php echo e($sz_disable); ?>" <?php if($sz_disable == 'active'){ ?> data-toggle="modal" data-target="#allow" <?php }?> id="confirm_<?php echo $user->id ?>">Đồng ý</button>
                    <button type="button" class="btn-sm btn btn-danger reject-change-manager <?php echo e($sz_disable); ?>" <?php if($sz_disable == 'active'){ ?> data-toggle="modal" data-target="#reject" <?php }?> id="confirm_<?php echo $user->id ?>">Từ chối</button>
                    <?php } else if($user->status == 1) { ?>
                        Đã duyệt
                    <?php } else if($user->status == 2) {  ?>
                        Từ chối
                    <?php } ?>
                </td>
            </tr>
        <?php endforeach; ?>
        <?php } ?>
    </table>
    <?php if(count($a_user) == 0): ?>
        <div class="alert alert-danger no-data">
            <tr>
                <strong><?php echo e('Chưa có yêu cầu nào'); ?></strong>
            </tr>
        </div>
    <?php endif; ?>
    
    <!--Đồng ý -->
<div class="modal fade" id="allow" tabindex="-1" role="dialog" aria-labelledby="allowLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="allowLabel">Đồng ý yêu cầu</h4>
      </div>
      <div class="modal-body">
        <form>
          <div class="form-group">
            <label for="message-text" class="control-label">Comment:</label>
            <textarea class="form-control" id="comment_allow" name="comment" ></textarea>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
        <button type="button" class="btn btn-primary confirm_request">Xác Nhận</button>
      </div>
    </div>
  </div>
</div>

<!--Không đồng ý -->
<div class="modal fade" id="reject" tabindex="-1" role="dialog" aria-labelledby="rejectLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="rejectLabel">Từ chối yêu cầu</h4>
      </div>
      <div class="modal-body">
        <form>
          <div class="form-group">
             <div class="notice-cmt text-danger" style="display:none;"><strong>Thông báo: Cần nhập Comment!!!!!</strong></div>
            <label for="message-text" class="control-label">Comment:</label>
            <textarea class="form-control" id="comment_reject" name="comment" ></textarea>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
        <button type="button" class="btn btn-primary confirm_request">Xác Nhận</button>
      </div>
    </div>
  </div>
</div>
<!--Hidden input-->
<input type="hidden" name="tbl" id="tbl" value="change_direct_manager">
<?php if(count($a_user)>0) echo $a_user->links(); ?>  
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>