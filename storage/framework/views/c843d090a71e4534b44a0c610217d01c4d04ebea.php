<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title><?php echo  config('cmconst.text.title') ?></title>

    <!-- Fonts -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css" rel='stylesheet' type='text/css'>
    <link href="https://fonts.googleapis.com/css?family=Lato:100,300,400,700" rel='stylesheet' type='text/css'>
    <link href="<?php echo URL::to('/');?>/css/sb-admin-2.css" rel='stylesheet' type='text/css'>
    <link rel="shortcut icon" href="<?php echo URL::to('/');?>/images/favicon.ico">
    <!-- Styles -->

    <?php /* <link href="<?php echo e(elixir('css/app.css')); ?>" rel="stylesheet"> */ ?>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Lato';
        }

        .fa-btn {
            margin-right: 6px;
        }
    </style>
</head>
<body id="app-layout">
    <div id="wrapper">
        <div class="form-control hidden" id="alert"><?php echo (session('status')?session('status'):'')?></div>
        <nav class="navbar navbar-default">
            <div class="navbar-header">
                <!-- Collapsed Hamburger -->
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle Navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a href="/" class="navbar-brand"><img class="logo" src="/public/images/logo-dxmb.png" alt="<?php echo  config('cmconst.text.title') ?>"></a>
                <!-- Branding Image -->
                
            </div>
            <div class="collapse navbar-collapse" id="spark-navbar-collapse">
                <!-- Left Side Of Navbar -->
                <ul class="nav navbar-nav">
                    <li><a style="color:#337ab7" href="#" data-toggle="modal" data-target="#myModaltext">Bản cập nhật IZI</a></li>
                </ul>
                <!-- Modal -->
                <div id="myModaltext" class="modal fade" role="dialog">
                  <div class="modal-dialog">
                    <!-- Modal content-->
                    <div class="modal-content">
                      <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title font-title-color">Phiên bản IZI</h4>
                      </div>
                    
                        <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                            <!--Phiên bản 1.1.2 -->
                            <div class="panel panel-default">
                                <div class="panel-heading" role="tab" id="headingOne">
                                    <h4 class="panel-title">
                                        <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                            Phiên bản IZI 1.1.2
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
                                    <div class="panel-body">
                                        <div class="modal-body font-title">
                                            <p>* Xóa User trên Izi sẽ xóa trên mail Server.</p>
                                            <p>* Thêm, sửa, xóa User trên Izi sẽ tự động cập nhật Alias trên Mail Server</p>
                                            <p>* Có thể Import nhiều User mới từ file Excel, đồng thời cập nhật trên Mail Server.</p>
                                            <p>* Hiển thị những đơn mới nhât lên trên cùng (Quản lý đơn vắng mặt cá nhân)</p>
                                            <p>* Thay đổi hiển thị mặc định các option như Quyền hạn, Chức vụ, Nghề nghiệp khi tạo mới User </p>
                                            <p>* Tạo module theo dõi các đơn đang chờ duyệt cho Reporter.</p>
                                            <p>* User có thể thay đổi mật khẩu trên Izi, tự động đồng bộ trên Mail Server</p>
                                            <p>* Cập nhật toàn bộ Mail box size các nhân viên từ Mail Server về Izi</p>
                                            <p>* Cập nhật toàn bộ Alias từ Mail Server về Izi</p>
                                            <p>* Mở thống kê vắng mặt cả ngày Chủ nhật</p>
                                            <p>* Cho phép User có thể xóa đơn khi chưa tới thời gian vắng mặt, dù đơn đã được duyệt </p>
                                            <p>* Chỉ cho phép Reporter, SuperAdmin có thể xóa hoặc sửa User.</p>
                                            <p>* Thực hiện Merge chấm công cho cả những User không có dữ liệu trên máy chấm công.</p>
                                            <p>* User có thể gửi yêu cầu thay đổi Quản lý trực tiếp. Reporter duyệt mới được cập nhật.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!--Phiên bản 1.1.1 -->
                            <div class="panel panel-default">
                                <div class="panel-heading" role="tab" id="headingOne">
                                    <h4 class="panel-title">
                                        <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                            Phiên bản IZI 1.1.1
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
                                    <div class="panel-body">
                                        <div class="modal-body font-title">
                                            <p>* Hiện thị số đơn vắng mặt đang trạng thái chờ xử lý ở màn hình HRM.</p>
                                            <p>* Sử dụng user để login, không cần thêm @dxmb.vn.</p>
                                            <p>* Reporter có thể kiểm duyệt đơn vắng mặt của HRM.</p>
                                            <p>* Xử lý merge bảng chấm công và đơn vắng mặt cá nhân.</p>
                                            <p>* Đồng bộ phòng ban trên mail server khi cập nhật ở IZI. </p>
                                            <p>* User có thể thay đổi nhóm, thay đổi quản lý trực tiếp.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--Phiên bản 1.1 -->
                            <div class="panel panel-default">
                                <div class="panel-heading" role="tab" id="headingTwo">
                                    <h4 class="panel-title">
                                        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                            Phiên bản IZI 1.1
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
                                    <div class="panel-body">
                                        <div class="modal-body font-title">
                                            <p>* Thực hiện đồng bộ tài khoản trên <span class="hotline-support">IZI</span> và tài khoản trên <span class="hotline-support">mail server</span>. Có thể thực hiện thay đổi mật khẩu, thay đổi phòng ban, người quản lý trực tiếp.</p>
                                            <p>* Xử lý xác nhận đơn vắng mặt của người quản lý và HRM thông qua email.</p>
                                            <p>* Xử lý import bảng chấm công, hiện thị màn hình bảng chấm công.</p>
                                            <p>* Xử lý merge bảng chấm công và đơn vắng mặt cá nhân.</p>
                                            <p>* Tạo ra màn hình hiện thị bảng công tháng, có chức năng lọc theo phòng ban, ngày tháng, mã nhân viên, họ tên. </p>
                                            <p>* Xử lý xuất file bảng công tháng theo form yêu cầu của HRM.</p>
                                      </div>
                                    </div>
                                </div>
                            </div>
                            <!--Phiên bản 1.0 -->
                            <div class="panel panel-default">
                                <div class="panel-heading" role="tab" id="headingThree">
                                    <h4 class="panel-title">
                                        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                            Phiên bản IZI 1.0
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapseThree" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
                                    <div class="panel-body">
                                        <div class="modal-body font-title">
                                            <p>* Lấy toàn bộ tài khoản mail server về làm tài khoản izi.</p>
                                            <p>* Nhân viên có chức năng đăng ký vắng mặt.</p>
                                            <p>* Có thể xem được đơn vắng mặt cá nhân.</p>
                                            <p>* Xử lý kiểm duyệt ở màn hình quản lý kiểm duyệt.</p>
                                            <p>* Xử lý kiểm duyệt ở màn hình HRM kiểm duyệt.</p>
                                            <p>* Reporter có thể xem được thống kê vắng mặt trong tháng.</p>
                                            <p>* Phân chia quyền cho các user.</p>
                                            <p>* Chức năng quản trị các module như: phòng ban, loại nghỉ phép, user, nghề nghiệp, loại nghỉ phép, chức vụ.</p>
                                            <p>* Chức năng import user.</p>
                                      </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                      <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
                      </div>
                    </div>

                  </div>
                </div>
                <!-- Right Side Of Navbar -->
                <ul class="nav navbar-nav navbar-right">
                    <!-- Authentication Links -->
                    <?php if(!Auth::guest()): ?>
                        <?php if(Auth::user()->hr_type == 1) { 
                            $o_LeaveRequest = new \App\Models\LeaveRequest();
                            $i_Count = $o_LeaveRequest->i_fCountLeaveRequestPending(); 
                        ?>
                        <li class="dropdown">
                            <a class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                <span class="header_color">Đơn chờ duyệt: <span class="hotline-support"><b><?php echo $i_Count?></b></span></span>
                            </a>
                        </li>
                        <?php } ?>
                        <li class="dropdown">
                            <a class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                <span class="header_color">Hotline support: <span class="hotline-support"><b>0163.529.3626 - 0962.882.994</b></span></span>
                            </a>
                        </li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                <i class="fa fa-video-camera"></i> <span class="header_color">Video hướng dẫn sử dụng IZI </span><span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu" role="menu">
                                <li>
                                    <a href="https://www.youtube.com/embed/REtT9RT6iK8" target="frame" class="dropdown-toggle video-tutorial" data-toggle="dropdown" aria-expanded="false" role="button">Đăng ký vắng mặt cho nhân viên </a>
                                </li>
                                <li>
                                    <a href="https://www.youtube.com/embed/kLpyYSDEx5A" target="frame" class="dropdown-toggle video-tutorial" data-toggle="dropdown" aria-expanded="false" role="button">Hướng dẫn quản lý vắng mặt của cấp quản lý</a>
                                </li>
                                <li>
                                    <a href="https://www.youtube.com/embed/FTk7WmLCB8M" target="frame" class="dropdown-toggle video-tutorial" data-toggle="dropdown" aria-expanded="false" role="button">Hướng dẫn cách lọc thư đến</a>
                                </li>
                            </ul>
                        </li>
                        <li class="dropdown">
                            <a class="dropdown-toggle" role="button" href="javascript: GLOBAL_JS.v_fToggleLeftSide();">
                                <i class="fa fa-exchange"></i><span class="header_color"> Ẩn hiện menu trái</span>
                            </a>
                        </li>
                        <li class="dropdown">
                            <a href="#" style="color:#337ab7" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                <?php echo e(Auth::user()->name); ?> <span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu" role="menu">
                                <li><a href="/logout"><i class="fa fa-btn fa-sign-out"></i><span class="header_color">Logout</span></a></li>
                                <?php if(Auth::user()->role_id != 1) { ?>
                                <li><a href="/change-manager"><i class="fa fa-btn fa-odnoklassniki"></i><span class="header_color">Thay đổi Quản lý trực tiêp</span></a></li>
                                <li><a href="/change-sensor"><i class="fa fa-btn fa-odnoklassniki"></i><span class="header_color">Thay đổi người duyệt đánh giá tháng</span></a></li>
                                <?php } ?>
                                <li><a href="/change-password"><i class="fa fa-btn fa-key"></i><span class="header_color">Đổi mật khẩu</span></a></li>
                            </ul>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
    <!--Pop-up video-->
        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">   
                    <div class="modal-content">
                        <div class="center">
                            <iframe name="frame" id="frame" width="560" height="315" frameborder="0" allowfullscreen></iframe>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <!--End pop-up-->
    
            <?php if(!Auth::guest()): ?>
            <?php
                $o_Role = new \App\Models\Roles();
                $a_Role = $o_Role->a_GetAllRoleByRoleGroup(Auth::user()->role_id);
                
                //class display module
                $sz_Class_user = $sz_Class_leaveRequest = $sz_Class_department = $sz_Class_error = $sz_Class_OverTime = $sz_Class_job = $sz_Class_typeRequest = $sz_Class_position = $sz_Class_group =
                $sz_Class_role = $sz_Class_import = $sz_Class_timesheet = $sz_Class_performance = "hidden";
                
                //class display action
                $sz_User_Index = $sz_User_Insert = $sz_User_ListChangeManager = $sz_LeaveRequest_statistic = $sz_LeaveRequest_list = $sz_LeaveRequest_manager = $sz_LeaveRequest_hrm = $sz_LeaveRequest_reporter = $sz_LeaveRequest_insert =
                $sz_Department_list = $sz_Department_edit = $sz_Error_list = $sz_Job_index = $sz_Job_modify = $sz_LeaveTypes_index = $sz_LeaveTypes_modify = $sz_Position_index =
                $sz_Position_modify = $sz_Group_index = $sz_Group_modify = $sz_Role_index = $sz_Role_insert = $sz_Import_index = $sz_Timesheet_index = $sz_Timesheet_table =
                $sz_Timesheet_month =  $sz_CheckpointByMonth = $sz_HrmManagerCheckPoint = $sz_CensorManagerCheckPoint = $sz_ListCheckPoint = $sz_ReportCheckPoint = $sz_addOverTime =
                $sz_ManagementApproveOT =  $sz_HRMApproveOT = $sz_MyselfOT = $sz_ReporterShowListOT = "hidden";
                    foreach($a_Role as $o_val){
                        if($o_val->controller == "UserController") $sz_Class_user = "";
                        if($o_val->controller == "LeaveRequestController") $sz_Class_leaveRequest = "";
                        if($o_val->controller == "DepartmentController") $sz_Class_department = "";
                        if($o_val->controller == "ErrorController") $sz_Class_error = "";
                        if($o_val->controller == "JobsController") $sz_Class_job = "";
                        if($o_val->controller == "LeaveTypesController") $sz_Class_typeRequest = "";
                        if($o_val->controller == "PositionController") $sz_Class_position = "";
                        if($o_val->controller == "GroupController") $sz_Class_group = "";
                        if($o_val->controller == "RoleController") $sz_Class_role = "";
                        if($o_val->controller == "ImportController") $sz_Class_import = "";
                        if($o_val->controller == "TimesheetController") $sz_Class_timesheet = "";
                        if($o_val->controller == "PerformanceController") $sz_Class_performance = "";

                        if($o_val->controller == "UserController" && $o_val->action == "index") $sz_User_Index = "";
                        if($o_val->controller == "UserController" && $o_val->action == "insert") $sz_User_Insert = "";
                        if($o_val->controller == "UserController" && $o_val->action == "ListChangeManager") $sz_User_ListChangeManager = "";
                        if($o_val->controller == "LeaveRequestController" && $o_val->action == "Report") $sz_LeaveRequest_statistic = "";
                        if($o_val->controller == "LeaveRequestController" && $o_val->action == "ListLeaveRequest") $sz_LeaveRequest_list = "";
                        if($o_val->controller == "LeaveRequestController" && $o_val->action == "LeaveRequestManagement") $sz_LeaveRequest_manager = "";
                        if($o_val->controller == "LeaveRequestController" && $o_val->action == "HrmManagement") $sz_LeaveRequest_hrm = "";
                        if($o_val->controller == "LeaveRequestController" && $o_val->action == "ReporterManagement") $sz_LeaveRequest_reporter = "";
                        if($o_val->controller == "LeaveRequestController" && $o_val->action == "LeaveRequest") $sz_LeaveRequest_insert = "";
                        if($o_val->controller == "DepartmentController" && $o_val->action == "ListDepartMent") $sz_Department_list = "";
                        if($o_val->controller == "DepartmentController" && $o_val->action == "editDepartment") $sz_Department_edit = "";
                        if($o_val->controller == "ErrorController" && $o_val->action == "mergeError") $sz_Error_merge = "";
                        if($o_val->controller == "ErrorController" && $o_val->action == "listError") $sz_Error_list = "";
                        if($o_val->controller == "ErrorController" && $o_val->action == "exportError") $sz_Error_export = "";
                        if($o_val->controller == "JobsController" && $o_val->action == "index") $sz_Job_index = "";
                        if($o_val->controller == "JobsController" && $o_val->action == "modify") $sz_Job_modify = "";
                        if($o_val->controller == "LeaveTypesController" && $o_val->action == "index") $sz_LeaveTypes_index = "";
                        if($o_val->controller == "LeaveTypesController" && $o_val->action == "modify") $sz_LeaveTypes_modify = "";
                        if($o_val->controller == "PositionController" && $o_val->action == "index") $sz_Position_index = "";
                        if($o_val->controller == "PositionController" && $o_val->action == "modify") $sz_Position_modify = "";
                        if($o_val->controller == "GroupController" && $o_val->action == "ListGroup") $sz_Group_index = "";
                        if($o_val->controller == "GroupController" && $o_val->action == "editGroup") $sz_Group_modify = "";
                        if($o_val->controller == "RoleController" && $o_val->action == "ListRoleGroup") $sz_Role_index = "";
                        if($o_val->controller == "RoleController" && $o_val->action == "insertRoleGroup") $sz_Role_insert = "";
                        if($o_val->controller == "ImportController" && $o_val->action == "showexcel") $sz_Import_index = "";
                        if($o_val->controller == "TimesheetController" && $o_val->action == "showexcel") $sz_Timesheet_index = "";
                        if($o_val->controller == "TimesheetController" && $o_val->action == "TableTimeSheet") $sz_Timesheet_table = "";
                        if($o_val->controller == "TimesheetController" && $o_val->action == "TimeSheetMonth") $sz_Timesheet_month = "";
                        if($o_val->controller == "PerformanceController" && $o_val->action == "checkpoint") $sz_CheckpointByMonth = "";
                        if($o_val->controller == "PerformanceController" && $o_val->action == "hrmManagerCheckpoint") $sz_HrmManagerCheckPoint = "";
                        if($o_val->controller == "PerformanceController" && $o_val->action == "censorManagerCheckpoint") $sz_CensorManagerCheckPoint = "";
                        if($o_val->controller == "PerformanceController" && $o_val->action == "listCheckPoint") $sz_ListCheckPoint = "";
                        if($o_val->controller == "PerformanceController" && $o_val->action == "reportCheckpoint") $sz_ReportCheckPoint = "";
                        if($o_val->controller == "OverTimeController" && $o_val->action == "addOverTime") $sz_addOverTime = "";
                        if($o_val->controller == "OverTimeController" && $o_val->action == "ManagementApproveOT") $sz_ManagementApproveOT = "";
                        if($o_val->controller == "OverTimeController" && $o_val->action == "HRMApproveOT") $sz_HRMApproveOT = "";
                        if($o_val->controller == "OverTimeController" && $o_val->action == "MyselfOT") $sz_MyselfOT = "";
                        if($o_val->controller == "OverTimeController" && $o_val->action == "ReporterShowListOT") $sz_ReporterShowListOT = "";
                    //$sz_addOverTime = $sz_ManagementApproveOT =  $sz_HRMApproveOT = $sz_MyselfOT = $sz_ReporterShowListOT
                }
            ?>
            <div class="navbar-default sidebar" role="navigation">
                <div class="sidebar-nav navbar-collapse">
                    <ul class="nav" id="side-menu">
                        <li>
                            <a href="<?php echo e(Request::root()); ?>"><i class="fa fa-dashboard fa-fw"></i>Bảng điều khiển</a>
                        </li>
                        <li class="<?php echo e($sz_Class_user); ?>">
                            <a href="#"><i class="fa fa-user"></i>Quản lý Users<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level collapse" aria-expanded="false">
                                <li class="<?php echo e($sz_User_Index); ?>"><a class="" href="<?php echo Request::root()."/user"?>">Danh sách Users</a></li>
                                <li><a class="<?php echo e($sz_User_Insert); ?>" href="<?php echo Request::root()."/user/insert"?>">Thêm users mới</a></li>
                                <li><a class="<?php echo e($sz_User_ListChangeManager); ?>" href="<?php echo Request::root()."/list-change-manager"?>">Danh sách thay đổi trưởng nhóm</a></li>
                            </ul>
                        </li>

                        <li class="<?php echo e($sz_Class_leaveRequest); ?>">
                            <a href="#"><i class="fa fa-user-times"></i> Quản lý vắng mặt<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level collapse" aria-expanded="false">
                                <li><a class="<?php echo e($sz_LeaveRequest_statistic); ?>" href="<?php echo Request::root()."/leave_request_report"?>">Thống kê vắng mặt trong tháng</a></li>
                                <li><a class="<?php echo e($sz_LeaveRequest_list); ?>" href="<?php echo Request::root()."/list_leave_request"?>">Đơn vắng mặt cá nhân</a></li>
                                <li><a class="<?php echo e($sz_LeaveRequest_manager); ?>" href="<?php echo Request::root()."/leave_management"?>">Quản lý kiểm duyệt</a></li>
                                <li><a class="<?php echo e($sz_LeaveRequest_hrm); ?>" href="<?php echo Request::root()."/hrm_management"?>">Nhân sự kiểm duyệt</a></li>
                                <li><a class="<?php echo e($sz_LeaveRequest_reporter); ?>" href="<?php echo Request::root()."/reporter_management"?>">Reporter kiểm duyệt</a></li>
                                <li><a class="<?php echo e($sz_LeaveRequest_insert); ?>" href="<?php echo Request::root()."/leave_request"?>">Đăng ký vắng mặt</a></li>
                            </ul>
                        </li>
                        <?php /*quan ly over time*/ ?>
                        <li class="">
                            <a href="#"><i class="fa fa-battery-full"></i> Quản lý Làm thêm giờ<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level collapse" aria-expanded="false">
                                <li><a class="<?php echo e($sz_addOverTime); ?>" href="<?php echo Request::root()."/add_over_time"?>">Đăng ký làm thêm giờ</a></li>
                                <li><a class="<?php echo e($sz_MyselfOT); ?>" href="<?php echo Request::root()."/add_over_myself"?>">Quản lý làm thêm giờ bản thân</a></li>
                                <li><a class="<?php echo e($sz_ManagementApproveOT); ?>" href="<?php echo Request::root()."/management_approve_OT"?>">Quản lý duyệt làm thêm giờ</a></li>
                                <li><a class="<?php echo e($sz_HRMApproveOT); ?>" href="<?php echo Request::root()."/HRM_approve_OT"?>">HRM duyệt làm thêm giờ</a></li>
                                <li><a class="<?php echo e($sz_ReporterShowListOT); ?>" href="<?php echo Request::root()."/Reporter_show_OT"?>">Thống kê làm thêm giờ</a></li>
                            </ul>
                        </li>

                        <li class="<?php echo e($sz_Class_performance); ?>">
                            <a href="#"><i class="fa fa-sitemap"></i> Hiệu quả làm việc tháng<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level collapse" aria-expanded="false">
                                <li><a class="<?php echo e($sz_CheckpointByMonth); ?>" href="<?php echo Request::root()."/checkpoint-by-month"?>">Tự đánh giá</a></li>
                                <li><a class=" <?php echo e($sz_HrmManagerCheckPoint); ?>" href="<?php echo Request::root()."/hrm-manager-checkpoint"?>">Hrm kiểm duyệt</a></li>
                                <li><a class=" <?php echo e($sz_CensorManagerCheckPoint); ?>" href="<?php echo Request::root()."/censor-manager-checkpoint"?>">Quản lý kiểm duyệt</a></li>
                                <li><a class=" <?php echo e($sz_ListCheckPoint); ?>" href="<?php echo Request::root()."/list-checkpoint"?>">Theo dõi bản thân đánh giá</a></li>
                                <li><a class=" <?php echo e($sz_ReportCheckPoint); ?>" href="<?php echo Request::root()."/report-checkpoint"?>">Tổng hợp đánh giá</a></li>
                            </ul>
                        </li>
                        <li class="<?php echo e($sz_Class_department); ?>">
                            <a href="#"><i class="fa fa-sitemap"></i> Quản lý phòng ban<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level collapse" aria-expanded="false">
                                <li><a class="<?php echo e($sz_Department_list); ?>" href="<?php echo Request::root()."/list_department"?>">Danh sách phòng ban</a></li>
                                <li><a class="<?php echo e($sz_Department_edit); ?>" href="<?php echo Request::root()."/department/addedit"?>">Thêm mới phòng ban</a></li>
                            </ul>
                        </li>
                        <li class="<?php echo e($sz_Class_error); ?>">
                            <a href="#"><i class="fa fa-briefcase"></i> Quản lý vi phạm <span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level collapse" aria-expanded="false">
                                <li><a class="<?php echo e($sz_Error_list); ?>" href="<?php echo Request::root()."/error_in_month"?>">Thống kê lỗi vi phạm</a></li>

                            </ul>
                        </li>
                        <li class="<?php echo e($sz_Class_job); ?>">
                            <a href="#"><i class="fa fa-briefcase"></i> Quản lý nghề nghiệp<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level collapse" aria-expanded="false">
                                <li><a class="<?php echo e($sz_Job_index); ?>" href="<?php echo Request::root()."/jobs"?>">Danh sách nghề nghiệp</a></li>
                                <li><a class="<?php echo e($sz_Job_modify); ?>" href="<?php echo Request::root()."/jobs/modify"?>">Thêm mới nghề nghiệp</a></li>
                            </ul>
                        </li>
                        <li class="<?php echo e($sz_Class_typeRequest); ?>">
                            <a href="#"><i class="fa fa-file"></i> Quản lý loại nghỉ phép<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level collapse" aria-expanded="false">
                                <li><a class="<?php echo e($sz_LeaveTypes_index); ?>" href="<?php echo Request::root()."/leave_types"?>">Danh sách loại nghỉ phép</a></li>
                                <li><a class="<?php echo e($sz_LeaveTypes_modify); ?>" href="<?php echo Request::root()."/leave_types/modify"?>">Thêm mới loại nghỉ phép</a></li>
                            </ul>
                        </li>
                        <li class="<?php echo e($sz_Class_position); ?>">
                            <a href="#"><i class="fa fa-street-view"></i> Quản lý chức vụ<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level collapse" aria-expanded="false">
                                <li><a class="<?php echo e($sz_Position_index); ?>" href="<?php echo Request::root()."/positions"?>">Danh sách chức vụ</a></li>
                                <li><a class="<?php echo e($sz_Position_modify); ?>" href="<?php echo Request::root()."/positions/modify"?>">Thêm mới chức vụ</a></li>
                            </ul>
                        </li>
                        <li class="<?php echo e($sz_Class_group); ?>">
                            <a href="#"><i class="fa fa-users"></i> Quản lý nhóm<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level collapse" aria-expanded="false">
                                <li><a class="<?php echo e($sz_Group_index); ?>" href="<?php echo Request::root()."/list_group"?>">Danh sách nhóm</a></li>
                                <li><a class="<?php echo e($sz_Group_modify); ?>" href="<?php echo Request::root()."/group/addedit"?>">Thêm mới nhóm</a></li>
                            </ul>
                        </li>
                        <li class="<?php echo e($sz_Class_role); ?>">
                            <a href="#"><i class="fa fa-lock"></i> Quản lý phân quyền<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level collapse" aria-expanded="false">
                                <li><a class="<?php echo e($sz_Role_index); ?>" href="<?php echo Request::root()."/list_role_group"?>">Danh sách nhóm quyền</a></li>
                                <li><a class="<?php echo e($sz_Role_insert); ?>" href="<?php echo Request::root()."/role/insert"?>">Thêm mới nhóm quyền</a></li>
                            </ul>
                        </li>
                        <li class="<?php echo e($sz_Class_import); ?>">
                            <a href="#"><i class="fa fa-adjust"></i> Import excel<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level collapse" aria-expanded="false">
                                <li><a class="<?php echo e($sz_Import_index); ?>" href="<?php echo Request::root()."/import_user"?>">Import user</a></li>
                            </ul>
                        </li>
                        <li class="<?php echo e($sz_Class_timesheet); ?>">
                            <a href="#"><i class="fa fa-file-excel-o"></i> Quản lý bảng chấm công<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level collapse" aria-expanded="false">
                                <li><a class="<?php echo e($sz_Timesheet_index); ?>" href="<?php echo Request::root()."/import_time_sheet"?>">Import file chấm công</a></li>
                                <li><a class="<?php echo e($sz_Timesheet_table); ?>" href="<?php echo Request::root()."/time_sheet/table"?>">Bảng chấm công</a></li>
                            </ul>
                        </li>
                        <li class="<?php echo e($sz_Class_timesheet); ?>">
                            <a href="#"><i class="fa fa-calendar-check-o"></i> Quản lý bảng công tháng<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level collapse" aria-expanded="false">
                                <li><a class="<?php echo e($sz_Timesheet_month); ?>" href="<?php echo Request::root()."/time_sheet_month"?>">Bảng công tháng</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
                <!-- /.sidebar-collapse -->
            </div>
            <!-- /.navbar-static-side -->
            <?php endif; ?>
        </nav>
        <?php if(Auth::guest()): ?>
            <div id="page-wrapper" class="no-margin">
        <?php else: ?>
            <div id="page-wrapper">
        <?php endif; ?>
            <?php echo $__env->yieldContent('content'); ?>
        </div>
    </div>
    </div>
    
    <!-- /#page-wrapper -->
    <!-- JavaScripts -->
    <?php /* <script src="<?php echo e(elixir('js/app.js')); ?>"></script> */ ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <script src="<?php echo URL::to('/');?>/js/metisMenu.min.js"></script>
    <script src="<?php echo URL::to('/');?>/js/sb-admin-2.js"></script>
    <script src="<?php echo URL::to('/');?>/js/global.js"></script>
    
    <!--Datepicker-->
    <script src="<?php echo URL::to('/');?>/plugins/datepicker/jquery-ui.min.js"></script>
    <script src="<?php echo URL::to('/');?>/plugins/datepicker/jquery-ui-timepicker-addon.js"></script>
    <link rel="stylesheet" type="text/css" href="<?php echo URL::to('/');?>/plugins/datepicker/jquery-ui.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo URL::to('/');?>/plugins/datepicker/jquery-ui.theme.css">
</body>
</html>
