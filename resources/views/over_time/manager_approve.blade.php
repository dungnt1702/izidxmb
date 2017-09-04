@extends('layouts.app')
@section('content')

    <h3 class="col-xs-12 no-padding">Danh sách đăng ký làm thêm giờ</h3>
    <form method="get" action="" id="frmFilter" name="frmFilter"  class="form-inline">
        <input type="hidden" name="_token" value="{!! csrf_token() !!}">
        <div class="form-group">
            <select id="search_status" name="search_status" class="form-control input-sm">
                <option value="0" <?php echo isset($a_search['search_status']) && $a_search['search_status'] == 0?'selected':''?>>Chờ duyệt</option>
                <option value="1" <?php echo isset($a_search['search_status']) && $a_search['search_status'] == 1?'selected':''?>>Đã duyệt</option>
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
    </form>

    <div class="">
        <table class="table table-responsive table-hover table-bordered">
            <tr>
                <td><strong>STT</strong></td>
                <td><strong>Mã nhân viên</strong></td>
                <td><strong>Người tạo</strong></td>
                <td><strong>Email</strong></td>
                <td><strong>Loại</strong></td>
                <td><strong>Nội dung</strong></td>
                <td><strong>Trạng thái</strong></td>
                <td><strong>Thời gian</strong></td>
                <td><strong>Tổng thời gian</strong></td>
                <td><strong>Action</strong></td>
            </tr>
            <?php if(count($a_data)>0){?>
            @foreach ($a_data as $a_val)
                <?php
                if($a_val->status_name == 'Chờ duyệt') $sz_disable = "active";
                else $sz_disable = "disabled";
                ?>

                <tr tr_<?php echo $a_val->id ?> class="alert alert-success">
                    <td>    {{ $a_val->stt }}</td>
                    <td>    {{ $a_val->code }}</td>
                    <td>    {{ $a_val->name }}</td>
                    <td>    {{ $a_val->email }}</td>
                    <td>    {{ $a_val->leave_type_name }}</td>
                    <td>    {{ $a_val->user_comment }}</td>
                    <td>    {{ $a_val->status_name }}</td>
                    <td>    <?php echo $a_val->time?></td>
                    <td>    {{ $a_val->total_time }} tiếng</td>
                    <td>
                        <?php if($a_val->status == 0) { ?>
                        <button type="button" class="btn-sm btn btn-primary manager-allow-ot {{ $sz_disable }}" <?php if($sz_disable == 'active'){ ?> data-toggle="modal" data-target="#allow" <?php }?> id="confirm_<?php echo $a_val->id ?>">Đồng ý</button>
                        <button type="button" class="btn-sm btn btn-danger manager-reject-ot {{ $sz_disable }}" <?php if($sz_disable == 'active'){ ?> data-toggle="modal" data-target="#reject" <?php }?> id="confirm_<?php echo $a_val->id ?>">Từ chối</button>
                        <?php } else if($a_val->status == 1) { ?>
                        <button type="button" class="btn-sm btn btn-danger" onclick="GLOBAL_JS.v_fManagerDelete(<?php echo $a_val->id?>)">Xóa</button>
                        <?php } ?>
                    </td>
                </tr>
            @endforeach
            <?php }?>
        </table>
        @if (count($a_data) == 0)
            <div class="alert alert-danger no-data">
                <tr>
                    <strong>{{ 'Chưa có danh sách vắng mặt nào' }}</strong>
                </tr>
            </div>
        @endif
    </div>

    <!--Đồng ý vắng mặt-->
    <div class="modal fade" id="allow" tabindex="-1" role="dialog" aria-labelledby="allowLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="allowLabel">Đồng ý vắng mặt</h4>
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
                    <button type="button" class="btn btn-primary confirm_ot">Xác Nhận</button>
                </div>
            </div>
        </div>
    </div>

    <!--Không đồng ý vắng mặt-->
    <div class="modal fade" id="reject" tabindex="-1" role="dialog" aria-labelledby="rejectLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="rejectLabel">Từ chối vắng mặt</h4>
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
                    <button type="button" class="btn btn-primary confirm_ot">Xác Nhận</button>
                </div>
            </div>
        </div>
    </div>
    <!--Hidden input-->
    <input type="hidden" name="tbl" id="tbl" value="leave_requests">
    <?php if(count($a_data)>0) echo $a_data->links(); ?>

@endsection