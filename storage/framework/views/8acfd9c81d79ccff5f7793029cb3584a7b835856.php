<strong>Bạn có một yêu cầu thay đổi Quản lý trực tiếp từ nhân viên DXMB:</strong><br>
<strong>Mã nhân viên: </strong><?php echo $a_EmailBody['code']?><br>
<strong>Tên nhân viên: </strong><?php echo $a_EmailBody['name']?><br>
<strong>Chức vụ: </strong><?php echo $a_EmailBody['position_name']?><br>
<strong>Phòng ban hiện tại: </strong><?php echo $a_EmailBody['old_department_name']?><br>
<strong>Quản lý trực tiếp hiện tại: </strong><?php echo $a_EmailBody['old_manager_name']?><br>
<?php if($a_EmailBody['new_department_name'] != $a_EmailBody['old_department_name']){?>
    <strong>Phòng ban mới: </strong><?php echo $a_EmailBody['new_department_name']?><br>
<?php }?>
<strong>Quản lý trực tiếp mới: </strong><?php echo $a_EmailBody['new_manager_name']?><br>
<strong>Nội dung yêu cầu: </strong><?php echo $a_EmailBody['comment']?><br>

