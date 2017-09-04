@extends('layouts.app')
@section('content')
    <h3 class="col-xs-12 no-padding font-title">Danh sách yêu cầu thay đổi Quản lý trực tiếp</h3>


    <table class="table table-responsive table-hover table-striped table-bordered">
        <tr>
            <td><strong>STT</strong></td>
            <td><strong>Mã NV</strong></td>
            <td><strong>Tên</strong></td>
            <td class="text-center"><strong>Người kiểm duyệt</strong></td>
            <td class="text-center"><strong>Nội dung</strong></td>
            <td class="text-center"><strong>Ngày gửi</strong></td>
            <td><strong>Trạng thái</strong></td>
        </tr>
        <?php if(count($a_user) > 0) { ?>
        @foreach ($a_user as $key => $user)
            <?php
            if($user->status == 0) $sz_disable = "active";
            else $sz_disable = "disabled";
            ?>
            <tr tr_<?php echo $user->id ?>>
                <td><?php echo ($key + $a_user->perPage() * $a_user->currentPage() - $a_user->perPage() + 1 )?></td>
                <td>    {{ $user->code }}</td>
                <td>    {{ $user->name }}</td>
                <td>    {{ $user->new_sensor_name }} -- {{ $user->department_name }}</td>
                <td>    {{ $user->user_comment }}</td>
                <td>    {{ $user->created_at }}</td>
                <td>
                    <?php if($user->status == 0) { ?>
                    <button type="button" class="btn-sm btn btn-primary allow-change-sensor {{ $sz_disable }}" <?php if($sz_disable == 'active'){ ?> data-toggle="modal" data-target="#allow" <?php }?> id="confirm_<?php echo $user->id ?>">Đồng ý</button>
                    <button type="button" class="btn-sm btn btn-danger reject-change-sensor {{ $sz_disable }}" <?php if($sz_disable == 'active'){ ?> data-toggle="modal" data-target="#reject" <?php }?> id="confirm_<?php echo $user->id ?>">Từ chối</button>
                    <?php } else if($user->status == 1) { ?>
                    Đã duyệt
                    <?php } else if($user->status == 2) {  ?>
                    Từ chối
                    <?php } ?>
                </td>
            </tr>
        @endforeach
        <?php } ?>
    </table>
    @if (count($a_user) == 0)
        <div class="alert alert-danger no-data">
            <tr>
                <strong>{{ 'Chưa có yêu cầu nào' }}</strong>
            </tr>
        </div>
    @endif

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
@endsection
