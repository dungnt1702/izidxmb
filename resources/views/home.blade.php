@extends('layouts.app')

@section('content')
<div class="col-md-12 no-padding">
    <?php
    if(isset(Auth::user()->id)){
        $o_Role = new \App\Models\Roles();
                $a_Role = $o_Role->a_GetAllRoleByRoleGroup(Auth::user()->role_id);
                
                //class display module
                $sz_Class_user = $sz_Class_leaveRequest = $sz_Class_department = $sz_Class_job = $sz_Class_typeRequest = $sz_Class_position = $sz_Class_group =  "hidden";
                
                //class display action
                $sz_User_Index = $sz_User_Insert =$sz_LeaveRequest_statistic =$sz_LeaveRequest_list = $sz_LeaveRequest_manager = $sz_LeaveRequest_hrm = $sz_LeaveRequest_insert = 
                        
                $sz_Department_list = $sz_Department_edit = $sz_Job_index = $sz_Job_modify = $sz_LeaveTypes_index = $sz_LeaveTypes_modify = $sz_Position_index = 
                $sz_Import_index = $sz_Position_modify = $sz_Group_index = $sz_Group_modify = "hidden";
                if(count($a_Role) > 0){
                    foreach($a_Role as $o_val){
                        if($o_val->controller == "UserController") $sz_Class_user = "";
                        if($o_val->controller == "LeaveRequestController") $sz_Class_leaveRequest = "";
                        if($o_val->controller == "DepartmentController") $sz_Class_department = "";
                        if($o_val->controller == "JobsController") $sz_Class_job = "";
                        if($o_val->controller == "LeaveTypesController") $sz_Class_typeRequest = "";
                        if($o_val->controller == "PositionController") $sz_Class_position = "";
                        if($o_val->controller == "GroupController") $sz_Class_group = "";

                        if($o_val->controller == "UserController" && $o_val->action == "index") $sz_User_Index = "";
                        if($o_val->controller == "UserController" && $o_val->action == "insert") $sz_User_Insert = "";
                        if($o_val->controller == "LeaveRequestController" && $o_val->action == "Report") $sz_LeaveRequest_statistic = "";
                        if($o_val->controller == "LeaveRequestController" && $o_val->action == "ListLeaveRequest") $sz_LeaveRequest_list = "";
                        if($o_val->controller == "LeaveRequestController" && $o_val->action == "LeaveRequestManagement") $sz_LeaveRequest_manager = "";
                        if($o_val->controller == "LeaveRequestController" && $o_val->action == "HrmManagement") $sz_LeaveRequest_hrm = "";
                        if($o_val->controller == "LeaveRequestController" && $o_val->action == "LeaveRequest") $sz_LeaveRequest_insert = "";
                        if($o_val->controller == "DepartmentController" && $o_val->action == "ListDepartMent") $sz_Department_list = "";
                        if($o_val->controller == "DepartmentController" && $o_val->action == "editDepartment") $sz_Department_edit = "";
                        if($o_val->controller == "JobsController" && $o_val->action == "index") $sz_Job_index = "";
                        if($o_val->controller == "JobsController" && $o_val->action == "modify") $sz_Job_modify = "";
                        if($o_val->controller == "LeaveTypesController" && $o_val->action == "index") $sz_LeaveTypes_index = "";
                        if($o_val->controller == "LeaveTypesController" && $o_val->action == "modify") $sz_LeaveTypes_modify = "";
                        if($o_val->controller == "PositionController" && $o_val->action == "index") $sz_Position_index = "";
                        if($o_val->controller == "PositionController" && $o_val->action == "modify") $sz_Position_modify = "";
                        if($o_val->controller == "GroupController" && $o_val->action == "ListGroup") $sz_Group_index = "";
                        if($o_val->controller == "GroupController" && $o_val->action == "editGroup") $sz_Group_modify = "";
                        if($o_val->controller == "ImportController" && $o_val->action == "showexcel") $sz_Import_index = "";
                    
                    }
                }
    }

    ?>
        <div class="col-md-12 no-padding">
            <div>
                <div class="panel-heading"><strong>Chào bạn tới phần mềm quản lý vắng mặt</strong></div>                    
                <div class="col-md-12 no-padding">
                        <div class="col-md-12 no-padding">
                            <div class="col-xs-1 col-md-1 col-lg-1 home-index-mobille home-index-pc {{ $sz_LeaveRequest_insert }}">
                                <table>
                                    <tr>
                                        <th class="text-center">
                                            <a href="<?php echo Request::root()."/leave_request" ?>">
                                                <i class="fa fa-pencil-square fa-5x"></i>
                                            </a>
                                        </th>
                                    </tr>
                                    <tr>
                                        <th><strong>Đăng ký vắng mặt</strong></th>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-xs-1 col-md-1 col-lg-1 home-index-mobille home-index-pc {{ $sz_Class_user }}">
                                <table>
                                    <tr>
                                        <th class="text-center">
                                            <a href="<?php if($sz_User_Index =="") echo Request::root()."/user" ?>">
                                                <i class="fa fa-user fa-5x"></i>
                                            </a>
                                        </th>
                                    </tr>
                                    <tr>
                                        <th><strong>Quản lý Users</strong></th>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-xs-1 col-md-1 col-lg-1 home-index-mobille home-index-pc {{ $sz_Class_leaveRequest }}">
                                <table>
                                    <tr>
                                        <th class="text-center">
                                            <a href="<?php if($sz_LeaveRequest_list =="") echo Request::root()."/list_leave_request" ?>">
                                                <i class="fa fa-user-times fa-5x"></i>
                                            </a>
                                        </th>                                        
                                    </tr>
                                    <tr>
                                        <th class="text-center"><strong>Quản lý Vắng mặt</strong></th>
                                    </tr>
                                </table>                                
                            </div>
                            
                            <div class="col-xs-1 col-md-1 col-lg-1 home-index-mobille home-index-pc {{ $sz_Class_department }}">
                                <table>
                                    <tr>                 
                                        <th class="text-center">
                                            <a href="<?php if($sz_Department_list =="") echo Request::root()."/list_department" ?>">
                                                <i class="fa fa-sitemap fa-5x"></i>                                                
                                            </a>
                                        </th>
                                    </tr>
                                    <tr>
                                        <th class="text-center"><strong>Quản lý phòng ban</strong></th>                                           
                                    </tr>
                                </table>                                
                            </div>
                            <div class="col-xs-1 col-md-1 col-lg-1 home-index-mobille home-index-pc {{ $sz_Class_job }}">
                                <table>
                                    <tr>                 
                                        <th class="text-center">
                                            <a href="<?php if($sz_Job_index =="") echo Request::root()."/jobs" ?>">
                                                <i class="fa fa-briefcase fa-5x"></i>                                                
                                            </a>
                                        </th>                                        
                                    </tr>
                                    <tr>
                                        <th class="text-center"><strong>Quản lý ngành nghề</strong></th>
                                    </tr>
                                </table>                                
                            </div>
                            <div class="col-xs-1 col-md-1 col-lg-1 home-index-mobille home-index-pc {{ $sz_Class_typeRequest }}">
                                <table>
                                    <tr>                 
                                        <th class="text-center">
                                            <a href="<?php if($sz_LeaveTypes_index =="") echo Request::root()."/leave_types" ?>">
                                                <i class="fa fa-file fa-5x"></i>                                                
                                            </a>
                                        </th>                                        
                                    </tr>
                                    <tr>
                                        <th class="text-center"><strong>Quản lý loại nghỉ phép</strong></th>
                                    </tr>
                                </table>                                
                            </div>
                            <div class="col-xs-1 col-md-1 col-lg-1 home-index-mobille home-index-pc {{ $sz_Class_position }}">
                                <table>
                                    <tr>                 
                                        <th class="text-center">
                                            <a href="<?php if($sz_Position_index =="") echo Request::root()."/positions"?>">
                                                <i class="fa fa-street-view fa-5x"></i>                                                
                                            </a>
                                        </th>                                        
                                    </tr>
                                    <tr>
                                        <th class="text-center"><strong>Quản lý chức vụ</strong></th>                                           
                                    </tr>
                                </table>                                
                            </div>
                            <div class="col-xs-1 col-md-1 col-lg-1 home-index-mobille home-index-pc {{ $sz_Class_group }}">
                                <table>
                                    <tr>                 
                                        <th class="text-center">
                                            <a href="<?php if($sz_Group_index =="") echo Request::root()."/list_group"?>">
                                                <i class="fa fa-users fa-5x"></i>                                                
                                            </a>
                                        </th>                                        
                                    </tr>
                                    <tr>
                                        <th class="text-center"><strong>Quản lý nhóm</strong></th>                                           
                                    </tr>
                                </table>                                
                            </div>
                            @if(Auth::user()->role_id == config('cmconst.id_superadmin'))
                            <div class="col-xs-1 col-md-1 col-lg-1 home-index-mobille home-index-pc">
                                <table>
                                    <tr>                 
                                        <th class="text-center">
                                            <a href="<?php echo Request::root()."/list_role_group"?>">
                                                <i class="fa fa-lock fa-5x"></i>                                                
                                            </a>
                                        </th>                                        
                                    </tr>
                                    <tr>
                                        <th class="text-center"><strong>Quản lý phân quyền</strong></th>                                           
                                    </tr>
                                </table>                                
                            </div>
                            <div class="col-xs-1 col-md-1 col-lg-1 home-index-mobille home-index-pc {{ $sz_Import_index }}">
                                <table>
                                    <tr>                 
                                        <th class="text-center">
                                            <a href="<?php echo Request::root()."/import_user"?>">
                                                <i class="fa fa-adjust fa-5x"></i>                                                
                                            </a>
                                        </th>                                        
                                    </tr>
                                    <tr>
                                        <th class="text-center"><strong>Import excel</strong></th>                                           
                                    </tr>
                                </table>                                
                            </div>
                            @endif
                        </div>
                </div>
            </div>
            </div>
        </div>
    </div>
@endsection
