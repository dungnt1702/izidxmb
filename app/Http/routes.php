<?php
use App\Task;
use App\Models\Roles;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/
/* not use
Route::get('/', function () {
    return view('welcome');
});
*/
/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/

Route::group(['middleware' => 'web'], function () {
    Route::auth();

    Route::get('/', ['middleware' => 'auth', 'uses' => 'HomeController@index']);

    Route::get('home', ['middleware' => 'auth', 'uses' => 'HomeController@index']);

    Route::get('user', ['middleware' => 'auth', 'uses' => 'User\UserController@index']);
    Route::post('user', ['middleware' => 'auth', 'uses' => 'User\UserController@index']);
    Route::get('user/insert', ['middleware' => 'auth', 'uses' => 'User\UserController@insert']);
    Route::post('user/insert',['middleware' => 'auth', 'uses' => 'User\UserController@insert']);
    Route::get('user/edit/{user_id}', ['middleware' => 'auth', 'uses' => 'User\UserController@edit']);
    Route::post('user/edit/{user_id}',['middleware' => 'auth', 'uses' => 'User\UserController@edit']);
    Route::get('leave_request', ['middleware' => 'auth', 'uses' => 'LeaveRequest\LeaveRequestController@LeaveRequest']);
    Route::post('leave_request',['middleware' => 'auth', 'uses' => 'LeaveRequest\LeaveRequestController@LeaveRequest']);

    Route::get('jobs', ['middleware' => 'auth', 'uses' => 'User\JobsController@index']);
    Route::post('jobs',['middleware' => 'auth', 'uses' => 'User\JobsController@index']);

    Route::get('jobs/modify', ['middleware' => 'auth', 'uses' => 'User\JobsController@modify']);
    Route::post('jobs/modify',['middleware' => 'auth', 'uses' => 'User\JobsController@modify']);

    Route::get('leave_types', ['middleware' => 'auth', 'uses' => 'LeaveRequest\LeaveTypesController@index']);
    Route::get('leave_types/modify', ['middleware' => 'auth', 'uses' => 'LeaveRequest\LeaveTypesController@modify']);
    Route::post('leave_types/modify',['middleware' => 'auth', 'uses' => 'LeaveRequest\LeaveTypesController@modify']);

    Route::get('positions', ['middleware' => 'auth', 'uses' => 'User\PositionController@index']);
    Route::post('positions',['middleware' => 'auth', 'uses' => 'User\PositionController@index']);
    Route::get('positions/modify', ['middleware' => 'auth', 'uses' => 'User\PositionController@modify']);
    Route::post('positions/modify',['middleware' => 'auth', 'uses' => 'User\PositionController@modify']);

    /**
     * Show Task Dashboard
     */
    Route::get('task', ['middleware' => 'auth', function () {
        $tasks = Task::orderBy('created_at', 'asc')->get();

        return view('tasks', [
            'tasks' => $tasks
        ]);
    }]);
    /**
     * Add New Task
    */
    Route::post('task', ['middleware' => 'auth', function (Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
        ]);

        if ($validator->fails()) {
            return redirect('/')
                ->withInput()
                ->withErrors($validator);
        }
        $task = new Task;
        $task->name = $request->name;
        $task->save();

        return redirect('/');
    }]);
    /**
     * Delete Task
    */
    Route::delete('task/{task}', ['middleware' => 'auth', function (Task $task) {
        $task->delete();

        return redirect('/');
    }]);
    /**
     * Crontab
     */
    Route::get('update_leave_request_report', 'CrontabController@v_fUpdateLeaveRequestReport');
    Route::get('update_user_staff', 'CrontabController@v_fUpdateUsersStaff');
    Route::get('update_group_staff', 'CrontabController@v_fUpdateStaffGroup');
    Route::get('update_leave_request_report', 'CrontabController@v_fUpdateLeaveRequestReport');
    Route::get('update_alias', 'CrontabController@v_fUpdateAlias');
    Route::get('update_mail_size', 'CrontabController@v_fUpdateMailSize');

    Route::post('ajax','AjaxController@SetProcess');

    Route::get('list_leave_request', ['middleware' => 'auth','uses' =>'LeaveRequest\LeaveRequestController@ListLeaveRequest']);
    Route::get('leave_management', ['middleware' => 'auth','uses' =>'LeaveRequest\LeaveRequestController@LeaveRequestManagement']);

    Route::get('hrm_management', ['middleware' => 'auth','uses' =>'LeaveRequest\LeaveRequestController@HrmManagement']);
    Route::post('hrm_management', ['middleware' => 'auth','uses' =>'LeaveRequest\LeaveRequestController@HrmManagement']);

    Route::get('reporter_management', ['middleware' => 'auth','uses' =>'LeaveRequest\LeaveRequestController@ReporterManagement']);

    Route::get('leave_request_report',['middleware' => 'auth', 'uses' => 'LeaveRequest\LeaveRequestController@Report']);
    Route::post('leave_request_report',['middleware' => 'auth', 'uses' => 'LeaveRequest\LeaveRequestController@Report']);

// Route Department
    Route::get('list_department', ['middleware' => 'auth','uses' =>'User\DepartmentController@ListDepartMent']);
    Route::get('department/addedit', ['middleware' => 'auth', 'uses' => 'User\DepartmentController@editDepartment']);
    Route::post('department/addedit',['middleware' => 'auth', 'uses' => 'User\DepartmentController@editDepartment']);
// End Router Department

    // Route Group
    Route::get('list_group', ['middleware' => 'auth','uses' =>'User\GroupController@ListGroup']);
    Route::get('group/addedit', ['middleware' => 'auth', 'uses' => 'User\GroupController@editGroup']);
    Route::post('group/addedit',['middleware' => 'auth', 'uses' => 'User\GroupController@editGroup']);
// END Route Group

    // Route Role
    Route::get('list_role_group', ['middleware' => 'auth','uses' =>'User\RoleController@ListRoleGroup']);
    Route::get('role/addedit', ['middleware' => 'auth', 'uses' => 'User\RoleController@editRoleGroup']);
    Route::post('role/addedit',['middleware' => 'auth', 'uses' => 'User\RoleController@editRoleGroup']);
    Route::get('role/insert', ['middleware' => 'auth', 'uses' => 'User\RoleController@insertRoleGroup']);
    Route::post('role/insert', ['middleware' => 'auth', 'uses' => 'User\RoleController@insertRoleGroup']);
// END Route Group
    Route::get('import_user', ['middleware' => 'auth', 'uses' => 'User\ImportController@showexcel']);
    Route::post('import_user',['middleware' => 'auth', 'uses' => 'User\ImportController@showexcel']);
// DIenct test merge
// User API
    Route::get('api/user_api', 'API\APIUserController@sz_fFunctionNavigation');
    Route::post('api/user_api', 'API\APIUserController@sz_fFunctionNavigation');
// Leave Request API
    Route::get('api/leave_request_api', 'API\APILeaveRequestController@GetInfomation');
    Route::post('api/leave_request_api', 'API\APILeaveRequestController@GetInfomation');
// import time sheet
    Route::get('import_time_sheet', ['middleware' => 'auth', 'uses' => 'Timesheet\TimesheetController@showexcel']);
    Route::post('import_time_sheet',['middleware' => 'auth', 'uses' => 'Timesheet\TimesheetController@showexcel']);
    Route::get('time_sheet/table', ['middleware' => 'auth', 'uses' => 'Timesheet\TimesheetController@TableTimeSheet']);
    Route::get('time_sheet_month', ['middleware' => 'auth', 'uses' => 'Timesheet\TimesheetController@TimeSheetMonth']);
// Route approve directly for manager and HRM
    Route::get('directly_approve', 'LeaveRequest\LeaveRequestController@ApproveDirectly');
// End Router approve directly for manager and HRM

// End Router approve directly for manager and HRM
    Route::get('time_sheet/merge_timesheet', ['middleware' => 'auth', 'uses' => 'Timesheet\TimesheetController@MergeTimeSheet']);
    //Export excel
    Route::get('export_excel', ['middleware' => 'auth', 'uses' => 'Timesheet\TimesheetController@export_excel']);
    //end export excel.
//Change Group for Business Department///
    Route::get('change-manager', ['middleware' => 'auth', 'uses' => 'User\ProfileController@ChangeManager']);
    Route::post('change-manager', ['middleware' => 'auth', 'uses' => 'User\ProfileController@ChangeManager']);
    Route::get('list-change-manager', ['middleware' => 'auth', 'uses' => 'User\UserController@ListChangeManager']);
    Route::post('list-change-manager', ['middleware' => 'auth', 'uses' => 'User\UserController@ListChangeManager']);

// change sensor
    Route::get('change-sensor', ['middleware' => 'auth', 'uses' => 'User\ProfileController@ChangeSensor']);
    Route::post('change-sensor', ['middleware' => 'auth', 'uses' => 'User\ProfileController@ChangeSensor']);

    Route::get('list-change-sensor', ['middleware' => 'auth', 'uses' => 'User\UserController@ListChangeSensor']);
    Route::post('list-change-sensor', ['middleware' => 'auth', 'uses' => 'User\UserController@ListChangeSensor']);

     ////Change Password////
    Route::get('change-password', ['middleware' => 'auth', 'uses' => 'User\ProfileController@ChangePassword']);
    Route::post('change-password', ['middleware' => 'auth', 'uses' => 'User\ProfileController@ChangePassword']);

    // check point myself
    Route::get('checkpoint-by-month', ['middleware' => 'auth', 'uses' => 'Performance\PerformanceController@checkpoint']);
    Route::post('checkpoint-by-month', ['middleware' => 'auth', 'uses' => 'Performance\PerformanceController@checkpoint']);

    Route::get('hrm-manager-checkpoint', ['middleware' => 'auth', 'uses' => 'Performance\PerformanceController@hrmManagerCheckpoint']);
    Route::post('hrm-manager-checkpoint', ['middleware' => 'auth', 'uses' => 'Performance\PerformanceController@hrmManagerCheckpoint']);

    Route::get('list-checkpoint', ['middleware' => 'auth', 'uses' => 'Performance\PerformanceController@listCheckPoint']);

    Route::get('censor-manager-checkpoint', ['middleware' => 'auth', 'uses' => 'Performance\PerformanceController@censorManagerCheckpoint']);
    Route::post('censor-manager-checkpoint', ['middleware' => 'auth', 'uses' => 'Performance\PerformanceController@censorManagerCheckpoint']);

    Route::get('report-checkpoint', ['middleware' => 'auth', 'uses' => 'Performance\PerformanceController@reportCheckpoint']);

    Route::get('export_checkpoint', ['middleware' => 'auth', 'uses' => 'Performance\ExportController@export_checkpoint']);
    //merge error

    //Vi Pham thang
    Route::get('merge_error', 'Error\ErrorController@mergeError');

    Route::get('error_in_month', ['middleware' => 'auth', 'uses' => 'Error\ErrorController@listError']);
    Route::get('export_excel_error', ['middleware' => 'auth', 'uses' => 'Error\ErrorController@exportError']);

    //Over TIme
    Route::get('add_over_time', ['middleware' => 'auth', 'uses' => 'OverTime\OverTimeController@addOverTime']);
    Route::post('add_over_time', ['middleware' => 'auth', 'uses' => 'OverTime\OverTimeController@addOverTime']);

    // manager approve OT
    Route::get('management_approve_OT', ['middleware' => 'auth','uses' =>'OverTime\OverTimeController@ManagementApproveOT']);

    // hrm approve OT
    Route::get('HRM_approve_OT', ['middleware' => 'auth','uses' =>'OverTime\OverTimeController@HRMApproveOT']);
    Route::post('HRM_approve_OT', ['middleware' => 'auth','uses' =>'OverTime\OverTimeController@HRMApproveOT']);

    // ot myself
    Route::get('add_over_myself', ['middleware' => 'auth','uses' =>'OverTime\OverTimeController@MyselfOT']);

    // convert to list over tive by user ID.
    Route::get('convert_over_time_report', 'OverTime\OverTimeController@ConvertOtReport');

    Route::get('Reporter_show_OT', ['middleware' => 'auth','uses' =>'OverTime\OverTimeController@ReporterShowListOT']);
    Route::get('export_excel_OT', ['middleware' => 'auth', 'uses' => 'OverTime\OverTimeController@exportOT']);

});
