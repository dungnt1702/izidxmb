<strong>Bạn có một đơn đăng ký vắng mặt mới:</strong><br>
<strong>Thông tin người đăng ký vắng mặt:</strong><br>
<strong>Mã nhân viên: </strong><?php echo $a_EmailBody['user_code']?><br>
<strong>Tên nhân viên: </strong><?php echo $a_EmailBody['user_name']?><br>
<strong>Chức vụ: </strong><?php echo $a_EmailBody['position']?><br>
<strong>Phòng ban: </strong><?php echo $a_EmailBody['department']?><br><br>
<strong>Thông tin đơn xin nghỉ phép: </strong><br>
<strong>Loại: </strong><?php echo $a_EmailBody['leave_request_type']?><br>
<strong><?php echo ($a_EmailBody['leave_request_type'] == config('cmconst.leave_type_business')?'Thời gian bắt đầu công tác: ':'Thời gian bắt đầu nghỉ: ')?> </strong><?php echo $a_EmailBody['from']?><br>
<strong><?php echo ($a_EmailBody['leave_request_type'] == config('cmconst.leave_type_business')?'Thời gian kết thúc công tác: ':'Thời gian đi làm: ')?>  </strong><?php echo $a_EmailBody['to']?><br>
<?php if(isset($a_EmailBody['numb_leave'])) { ?>
<strong>Số ngày nghỉ: </strong><?php echo $a_EmailBody['numb_leave']?><br>
<?php } ?>
<strong>Nội dung vắng mặt: </strong><?php echo $a_EmailBody['user_comment']?><br>
<strong>URL kiểm duyệt:</strong> <a href="<?php echo $a_EmailBody['url']?>">Bạn truy cập đường Link này để kiểm duyệt</a><br>
<?php if(isset($a_EmailBody['accept'])) { ?>
    <strong>URL đồng ý:</strong> <a href="<?php echo $a_EmailBody['accept']?>">Bạn truy cập đường Link này để đồng ý trực tiếp không cần vào màn hình duyệt</a><br>
    <strong>URL từ chối:</strong> <a href="<?php echo $a_EmailBody['rejected']?>">Bạn truy cập đường Link này để từ chối không cần vào màn hình duyệt</a>
<?php } ?>

