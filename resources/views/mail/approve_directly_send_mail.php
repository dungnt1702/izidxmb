<strong><?php echo $a_InfoLeaveRequest['sz_Title'] ?></strong><br>
<?php if(!isset($a_InfoLeaveRequest['url'])) { ?>
<strong style="color:<?php echo $a_InfoLeaveRequest['i_stt'] == 3?'red':'blue'?>"><?php echo $a_InfoLeaveRequest['i_stt'] == 3?' Không đồng ý ':' Đồng ý '?> </strong>đơn vắng mặt<br>
<?php } ?>
<strong>Thông tin đơn đăng ký vắng mặt:</strong><br>
<strong>Mã Nhân viên: </strong><?php echo $a_InfoLeaveRequest['user_code']?><br>
<strong>Thuộc phòng ban: </strong><?php echo $a_InfoLeaveRequest['department']?><br>
<strong>Loại: </strong><?php echo $a_InfoLeaveRequest['leave_request_type']?><br>
<?php if(isset($a_InfoLeaveRequest['numb_leave'])) { ?>
<strong>Số ngày nghỉ: </strong><?php echo $a_InfoLeaveRequest['numb_leave']?><br>
<?php } ?>
<strong><?php echo !isset($a_InfoLeaveRequest['numb_leave'])?'Thời gian bắt đầu công tác: ':'Thời gian bắt đầu nghỉ: '?> </strong><?php echo $a_InfoLeaveRequest['from']?><br>
<strong><?php echo !isset($a_InfoLeaveRequest['numb_leave'])?'Thời gian kết thúc công tác: ':'Thời gian đi làm: '?>  </strong><?php echo $a_InfoLeaveRequest['to']?><br>
<strong>Nội dung vắng mặt: </strong><?php echo $a_InfoLeaveRequest['user_comment']?><br>
<?php if(isset($a_InfoLeaveRequest['url'])) { ?>
<strong>URL kiểm duyệt:</strong> <a href="<?php echo $a_InfoLeaveRequest['url']?>">Bạn truy cập đường Link này để kiểm duyệt</a><br>
<strong>URL đồng ý:</strong> <a href="<?php echo $a_InfoLeaveRequest['accept']?>">Bạn truy cập đường Link này để đồng ý trực tiếp không cần vào màn hình duyệt</a><br>
<strong>URL từ chối:</strong> <a href="<?php echo $a_InfoLeaveRequest['rejected']?>">Bạn truy cập đường Link này để từ chối không cần vào màn hình duyệt</a>
<?php } ?>



