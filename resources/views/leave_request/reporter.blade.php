@extends('layouts.app')
@section('content')

<div class="form-group">
    <?php if(Auth::user()->hr_type != 2) { ?>
    <div class="col-xs-12 col-sm-12 no-padding">
        <div class="col-xs-12 col-sm-3 no-padding icon-alert">
            <div class="col-xs-12 col-sm-2 no-padding icon-alert">
                <div style="width: 0.5px; height: 0.5px" class="alert alert-warning"></div>
            </div>
            <div class="col-xs-12 col-sm-10 no-padding icon-alert" >Đơn vắng mặt của nhân viên</div>
        </div>
        <div class="col-xs-12 col-sm-3 no-padding icon-alert" >
            <div class="col-xs-12 col-sm-2 no-padding icon-alert" >
                <div style="width: 0.5px; height: 0.5px" class="alert alert-success"></div>
            </div>
            <div class="col-xs-12 col-sm-10 no-padding icon-alert" > Đơn vắng mặt của quản lý</div>
        </div>
    </div>
    <?php } ?>
    <div class="col-xs-12 col-sm-12 no-padding">
        <!--Bo loc o day-->
        <form method="get" action="" enctype="multipart/form-data" class="form-inline">
            <input type="hidden" name="_token" value="{!! csrf_token() !!}">
            <label for="sale-type-2">Lọc: </label>
                <div class="form-group">
                    <select id="department_id" class="form-control input-sm" name="department_id">
                        <option value="">Chọn phòng ban</option>
                        @foreach ($a_department as $o_val)
                        <option value="{{ $o_val->id }}" <?php if (isset($a_search['department_id']) && $a_search['department_id'] == $o_val->id) echo "selected"; ?>>{{ $o_val->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <select id="search_type" name="search_type" class="form-control input-sm">
                        <option value="">Loại nghỉ phép</option>
                        <?php foreach ($a_LeaveTypes as $i_LeaveType => $sz_LeaveType) { ?>
                            <option value="<?php echo $i_LeaveType?>" <?php echo isset($a_search['search_type']) && $a_search['search_type'] == $i_LeaveType?'selected':''?>><?php echo $sz_LeaveType?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="form-group">
                    <select id="search_status" name="search_status" class="form-control input-sm">
                        <option value="1" <?php echo isset($a_search['search_status']) && $a_search['search_status'] == 1 ? 'selected' : '' ?>>Chờ duyệt</option>
                        <option value="2" <?php echo isset($a_search['search_status']) && $a_search['search_status'] == 2 ? 'selected' : '' ?>>Đã duyệt</option>
                    </select>
                </div>

                <div class="form-group">
                    <select id="search_by" name="search_by" class="form-control input-sm">
                        <option value="">Tìm kiếm theo</option>
                        <option value="code" <?php echo isset($a_search['search_by']) && $a_search['search_by'] == 'code' ? 'selected' : '' ?>>Mã nhân viên</option>
                        <option value="email" <?php echo isset($a_search['search_by']) && $a_search['search_by'] == 'email'?'selected':''?>>Email</option>
                        <option value="name" <?php echo isset($a_search['search_by']) && $a_search['search_by'] == 'name' ? 'selected' : '' ?>>Họ tên</option>
                    </select>
                </div>
            
                <div class="form-group">
                    <input id="search_field" name="search_field" type="text" class="form-control input-sm" placeholder="Nhập từ khóa" value="<?php echo isset($a_search['search_field']) ? $a_search['search_field'] : '' ?>">
                </div>

                <div class="form-group">
                    <input type="button" class="btn btn-success btn-sm" value="Tìm kiếm" onclick="GLOBAL_JS.v_fSearchSubmit()">
                    <input type="submit" class="btn btn-success btn-sm submit hide">
                </div>
        </form>
    </div>
 
</div>
<h3 class="col-xs-12 no-padding">Danh sách vắng mặt</h3>

    <div class="">
            <table class="table table-responsive table-hover table-bordered">
                <tr>
                    <td><strong>STT</strong></td>
                    <td><strong>Mã NV</strong></td>
                    <td><strong>Họ tên</strong></td>
                    <td><strong>Phòng ban</strong></td>
                    <td><strong>Chức vụ</strong></td>
                    <td><strong>Loại</strong></td>
                    <td><strong>Nội dung</strong></td>
                    <td><strong>Trạng thái</strong></td>
                    <td><strong>Thời gian</strong></td>
                    <td><strong>Ngày tạo</strong></td>
                    <td><strong>Thời gian duyệt</strong></td>
                </tr>
            <?php if(count($a_data)>0){?>
            @foreach ($a_data as $a_val)
                <?php
                    if($a_val->status == 'Đang chờ') $sz_gbcolor = "alert-success";
                    else $sz_gbcolor = "alert-warning";
                ?>
                <tr id="tr_<?php echo $a_val->id ?>" class="alert {{ $sz_gbcolor }}">
                    <td>    {{ $a_val->stt }}</td>
                    <td>    {{ $a_val->code }}</td>
                    <td>    {{ $a_val->name }}</td>
                    <td>    {{ $a_val->department_name }}</td>
                    <td>    {{ $a_val->position_name }}</td>
                    <td>    {{ $a_val->leave_type_name }}</td>
                    <td>    {{ $a_val->user_comment }}</td>
                    <td>    {{ $a_val->status }}</td>
                    <td>    <?php echo $a_val->time?></td>
                    <td>    {{ $a_val->created_at }}</td>
                    <td>    
                        <strong>- QL duyệt:</strong> <?php echo $a_val->manager_act_time?> <br>
                        <strong>- HRM duyệt:</strong> <?php echo $a_val->hrm_act_time?>
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
        <button type="button" class="btn btn-primary confirm_request">Xác Nhận</button>
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
        <button type="button" class="btn btn-primary confirm_request">Xác Nhận</button>
      </div>
    </div>
  </div>
</div>
<!--Hidden input-->
<input type="hidden" name="tbl" id="tbl" value="leave_requests">
<?php echo (empty($a_search))?$a_data->render():$a_data->appends($a_search)->render();?>
@endsection