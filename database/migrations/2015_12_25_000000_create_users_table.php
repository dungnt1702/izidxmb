<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code', 20)->unique()->comment = "Mã nhân viên";
            $table->string('name')->comment = "Họ tên đầy đủ";
            $table->string('email', 100)->unique()->comment = "Email của công ty";
            $table->string('password', 60)->comment = "Mật khẩu truy cập sau khi mã hoá";
            $table->unsignedTinyInteger('status')->default(1)->comment = "Trạng thái 0 và 1";
            $table->unsignedTinyInteger('is_manager')->default(0)->comment = "Thông thường là 0; Cấp quản lý là 1";
            $table->unsignedTinyInteger('hr_type')->default(0)->comment = "Thông thường là 0; HRM là 1; Reporter là 2";
            $table->unsignedInteger('group_id')->comment = "Id của bảng Nhóm";
            $table->unsignedInteger('job_id')->comment = "Id của bảng Nghề";
            $table->unsignedInteger('department_id')->comment = "Id của bảng phòng ban";
            $table->unsignedInteger('role_id')->comment = "Id của bảng Vai trò Roles";
            $table->unsignedInteger('profile_id')->comment = "Id của bảng Thông tin Profiles";
            $table->unsignedInteger('position_id')->comment = "Id của bảng Chức vụ Positions";
            $table->unsignedInteger('salary_id')->comment = "Id của bảng Lương Salaries";
            $table->unsignedInteger('contract_id')->comment = "Id của bảng Hợp đồng Contracts";
            $table->unsignedInteger('direct_manager_id')->default(0)->comment = "Id của Người quản lý trực tiếp";
            $table->rememberToken()->comment = "Token ghi nhớ mỗi lần đăng nhập";
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
        Schema::drop('users');
    }
}
