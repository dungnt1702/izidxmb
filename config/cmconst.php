<?php
return [
    'text' => [
        'title' => 'Bản cập nhật IZI 1.1',
        'exists_user_code' => 'Đã tồn tại Mã nhân viên này! Vui lòng kiểm tra lại',
        'exists_email' => 'Đã tồn tại Email này! Vui lòng kiểm tra lại',
        'duplicate_leave_request' => 'Bạn đã có một đơn xin nghỉ phép trùng lặp thời gian',
    ],

    'value' => [
        'NPN' => 'Nghỉ phép năm',
        'PCT' => 'Phiếu công tác',
        'duplicate_leave_request' => 'Bạn đã có một đơn xin nghỉ phép trùng lặp thời gian',
    ],
    'day' => [
        'Monday' => 'T2',
        'Tuesday' => 'T3',
        'Wednesday' => 'T4',
        'Thursday' => 'T5',
        'Friday' => 'T6',
        'Saturday' => 'T7',
        'Sunday' => 'CN',
    ],
    'all_category'=>[
        'UserController'=>[
            'Danh sách user'=>'index',
            'Thêm mới user'=>'insert',
            'Danh sách thay đổi trưởng nhóm'=>'ListChangeManager',
        ],
        'ProfileController'=>[
            'Thay đổi nhóm'=>'ChangeManager',
            'Đổi mật khẩu'=>'ChangePassword',
            'Thay đổi người duyệt đánh giá tháng'=>'ChangeSensor'
        ],
        'LeaveRequestController'=>[
            'Thống kê vắng mặt trong tháng' => 'Report',
            'Đơn vắng mặt cá nhân'=>'ListLeaveRequest',
            'Quản lý kiểm duyệt'=>'LeaveRequestManagement',
            'Nhân sự kiểm duyệt'=>'HrmManagement',
            'Reporter kiểm duyệt'=>'ReporterManagement',
            'Đăng ký vắng mặt'=>'LeaveRequest',
            'Kiểm duyệt trực tiếp'=>'ApproveDirectly',
        ],
        'PerformanceController'=>[
            'Tự đánh giá' => 'checkpoint',
            'Hrm kiểm duyệt đánh giá'=>'hrmManagerCheckpoint',
            'Quản lý kiểm duyệt đánh giá'=>'censorManagerCheckpoint',
            'Danh sách phiếu đánh giá'=>'listCheckPoint',
            'Thống kê phiếu đánh giá'=>'reportCheckpoint',
        ],
        'ErrorController'=>[// VI PHAM THANG
            'Merge nghỉ không phép và đi muộn về sớm' => 'mergeError',
            'Thống kê vi phạm tháng' => 'listError',
            'Export vi phạm' => 'exportError',
        ],
        'OverTimeController'=>[
            'Đăng ký làm thêm giờ'=>'addOverTime',
            'Quản lý duyệt làm thêm giờ'=>'ManagementApproveOT',
            'HRM duyệt làm thêm giờ'=>'HRMApproveOT',
            'Làm thêm giờ bản thân'=>'MyselfOT',
            'Merge Dữ liệu'=>'ConvertOtReport',
            'Thống kê làm thêm'=> 'ReporterShowListOT',
            'Export Làm thêm giờ'=>'exportOT'
        ],
        'DepartmentController'=>[
            'Danh sách phòng ban'=>'ListDepartMent',
            'Thêm mới phòng ban'=>'editDepartment',
        ],
        'JobsController'=>[
            'Danh sách nghề nghiệp'=>'index',
            'Thêm mới nghề nghiệp'=>'modify',
        ],
        'LeaveTypesController'=>[
            'Danh sách loại nghỉ phép'=>'index',
            'Thêm mới loại nghỉ phép'=>'modify',
        ],
        'PositionController'=>[
            'Danh sách chức vụ'=>'index',
            'Thêm mới chức vụ'=>'modify',
        ],
        'GroupController'=>[
            'Danh sách nhóm'=>'ListGroup',
            'Thêm mới nhóm'=>'editGroup',
        ],
        'RoleController'=>[
            'Danh sách nhóm quyền'=>'ListRoleGroup',
            'Thêm mới nhóm quyền'=>'insertRoleGroup',
        ],
        'ImportController'=>[
            'Import user'=>'showexcel',
        ],
        'TimesheetController'=>[
            'Import file chấm công'=>'showexcel',
            'Bảng chấm công'=>'TableTimeSheet',
            'Quản lý bảng công tháng'=> 'TimeSheetMonth'
        ]
    ],
    'name_controller'=>[
        'UserController'=>'Quản lý user',
        'ProfileController'=>'Thông tin User',
        'LeaveRequestController'=>'Quản lý vắng mặt',
        'PerformanceController'=>'Quản lý đánh giá tháng',
        'DepartmentController'=>'Quản lý phòng ban',
        'ErrorController'=>'Quản lý vi phạm',
        'OverTimeController'=>'Quản lý làm thêm giờ',
        'JobsController'=>'Quản lý nghề nghiệp',
        'LeaveTypesController'=>'Quản lý loại nghỉ phép',
        'PositionController'=>'Quản lý chức vụ',
        'GroupController'=>'Quản lý nhóm',
        'RoleController'=>'Quản lý phân quyền',
        'ImportController'=>'Import Excel',
        'TimesheetController'=>'Quản lý bảng chấm công',
        'TimesheetController'=>'Quản lý bảng công tháng',
    ],
    'id_superadmin' => 1,
    'job_business' => 'Kinh doanh',
    'general_manager_depart' => 'Ban Tổng Giám đốc',
    'leave_type_business' => 'Phiếu công tác',
    'email_manager_bussinness' => 'hangdtt@dxmb.vn',
    'leave_request_status'=> [
        'Chờ duyệt',
        'Quản lý đã duyệt',
        'Nhân sự đã duyệt',
        'Từ chối',
        'Đã xóa',
    ],
    'DomainAdmin' => ['lannt@dxmb.vn'],
    'DefaultPass' => 'datxanhmienbac',
    'Role_Staff' => [
        'Nhân viên',
        'Chuyên viên',
        'Thư ký Kinh doanh',
        'Kỹ sư trưởng',
    ],
];

