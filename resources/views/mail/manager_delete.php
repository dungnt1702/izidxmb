<strong>Xin chào bạn </strong><?php echo $a_EmailBody['user_name'] ?><br>
<strong>Thuộc phòng ban: </strong><?php echo $a_EmailBody['department']?><br>
<strong>Bạn có một đơn xin vắng mặt bị hủy bỏ từ Quản lý</strong><br>
    
<strong>Thông tin đơn vắng mặt:</strong><br>
<strong>Loại: </strong><?php echo $a_EmailBody['leave_request_type']?><br>
<strong><?php echo ($a_EmailBody['leave_request_type'] == config('cmconst.leave_type_business')?'Thời gian bắt đầu công tác: ':'Thời gian bắt đầu nghỉ: ')?> </strong><?php echo $a_EmailBody['from']?><br>
<strong><?php echo ($a_EmailBody['leave_request_type'] == config('cmconst.leave_type_business')?'Thời gian kết thúc công tác: ':'Thời gian đi làm: ')?>  </strong><?php echo $a_EmailBody['to']?><br>
<?php if(isset($a_EmailBody['numb_leave'])) { ?>
<strong>Số ngày nghỉ: </strong><?php echo $a_EmailBody['numb_leave']?><br>
<?php } ?>
<strong>Nội dung vắng mặt: </strong><?php echo $a_EmailBody['user_comment']?><br>

