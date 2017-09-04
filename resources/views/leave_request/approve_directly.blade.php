@extends('layouts.app')
@section('content')
<div class="row">
    <?php if(isset($mes)) { ?>
    <div class="col-md-4 col-md-offset-4">
        <div class="alert alert-danger">
            <strong><?php echo $mes?></strong>
        </div>
    </div>
    <?php } else { ?>
    <div class="col-md-4 col-md-offset-4">
        <div class="alert alert-info">
            <strong><?php echo $a_InfoLeaveRequest['i_stt'] == 3?'Từ chối ':'Đồng ý ';?></strong> đơn vắng mặt thành công!
          </div>
    </div>
    <?php } ?>
    <?php if(!isset($mes)) { ?>
        <div class="col-md-4 col-md-offset-4">
            <div class="alert alert-success" style="">
                <strong>Thông tin đơn vắng mặt!</strong><br>
                <strong>Người đăng ký: </strong> <?php echo $a_InfoLeaveRequest['user_name']?><br>
                <strong>Mã NV: </strong> <?php echo $a_InfoLeaveRequest['user_code']?><br>
                <strong>Phòng ban: </strong> <?php echo $a_InfoLeaveRequest['department']?><br>
                <strong>Loại: </strong> <?php echo $a_InfoLeaveRequest['leave_request_type']?><br>
                <?php if(isset($a_InfoLeaveRequest['numb_leave'])) { ?>
                <strong>Số ngày nghỉ: </strong><?php echo $a_InfoLeaveRequest['numb_leave']?><br>
                <?php } ?>
                <strong><?php echo !isset($a_InfoLeaveRequest['numb_leave'])?'Thời gian bắt đầu công tác: ':'Thời gian bắt đầu nghỉ: '?> </strong><?php echo $a_InfoLeaveRequest['from']?><br>
                <strong><?php echo !isset($a_InfoLeaveRequest['numb_leave'])?'Thời gian kết thúc công tác: ':'Thời gian đi làm: '?>  </strong><?php echo $a_InfoLeaveRequest['to']?><br>
                <strong>Nội dung vắng mặt: </strong> <?php echo $a_InfoLeaveRequest['user_comment']?><br>
            </div>
        </div>
    <?php } ?>
    
</div>
@endsection
