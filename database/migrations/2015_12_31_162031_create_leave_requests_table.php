<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLeaveRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leave_requests', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id')->index()->comment = "Id Users";
            $table->string('email', 100)->comment = "email người tạo";
            $table->string('code', 20)->comment = "Mã nhân viên";
            $table->string('name', 100)->comment = "tên người tạo";
            $table->unsignedInteger('department_id')->comment = "Id Phòng ban";
            $table->string('department_name',100)->comment = "Tên phòng ban";
            $table->unsignedInteger('position_id')->comment = "Id chức vụ";
            $table->string('position_name',100)->comment = "Tên chức vụ";
            $table->unsignedTinyInteger('enable_sunday')->default(1)->comment = "Cho phép mở ngày chủ nhật";
            $table->dateTime('from_time')->comment = "Từ thời gian";
            $table->dateTime('to_time')->comment = "Đến thời gian";
            $table->float('numb_leave')->comment = "Số ngày nghỉ";
            $table->unsignedTinyInteger('status')->default(0)->comment = "Trạng thái Pending(0),Manager Approved(1),HRM Approved(2),Rejected(3),Deleted(4)";
            $table->unsignedInteger('type_id')->comment = "id của bảng leave type";
            $table->text('user_comment')->nullable()->comment = "Lý do vắng mặt";
            $table->string('inform_id',256)->comment = "Thông báo tới quản lý liên quan";
            $table->unsignedInteger('manager_id')->index()->comment = "Id Users của quản lý phòng ban";
            $table->string('manager_comment')->nullable()->comment = "Ý kiến của quản lý";
            $table->dateTime('manager_act_time')->nullable()->comment = "Thời gian cập nhật của quản lý";
            $table->unsignedInteger('hrm_id')->comment = "Id Users của HRM";
            $table->string('hrm_comment')->nullable()->comment = "Ý kiến của HRM";
            $table->dateTime('hrm_act_time')->nullable()->comment = "Thời gian cập nhật của HRM";
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('leave_requests');
    }
}
