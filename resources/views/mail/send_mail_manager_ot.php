<strong>Xác nhận từ Quản lý: <?php echo ($a_EmailBody['type_confirm'] == 1?'Đồng ý cho anh chị ':'Không Đồng Ý cho anh chị ')?> </strong><?php echo $a_EmailBody['user_name']?><br>
<strong>Thuộc phòng ban: </strong><?php echo $a_EmailBody['department']?><br>
<strong>Loại: </strong><?php echo $a_EmailBody['leave_request_type']?><br>
<strong>Thời gian bắt đầu: </strong><?php echo $a_EmailBody['from']?><br>
<strong>Thời gian kết thúc: </strong><?php echo $a_EmailBody['to']?><br>
<strong>Tổng thời gian OT </strong><?php echo $a_EmailBody['total_time']?> tiếng<br>
<strong>Nội dung vắng mặt: </strong><?php echo $a_EmailBody['user_comment']?><br>
<strong>Quản lý duyệt: </strong><?php echo $a_EmailBody['manager_comment']?><br>
