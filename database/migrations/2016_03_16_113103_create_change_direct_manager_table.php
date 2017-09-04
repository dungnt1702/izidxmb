<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChangeDirectManagerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('change_direct_manager', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id')->index()->comment = "Id nhân viên";
            $table->string('code', 20)->index()->comment = "Mã nhân viên";
            $table->string('name')->index()->comment = "Họ tên đầy đủ";
            $table->string('email', 100)->index()->comment = "Email của nhân viên";
            
            $table->unsignedInteger('old_department_id')->comment = "Id Phòng ban cũ";
            $table->string('old_department_name',100)->comment = "Tên phòng ban cũ";
            $table->unsignedInteger('old_manager_id')->comment = "Id manager cũ";
            $table->string('old_manager_name',100)->comment = "Tên manager cũ";
            
            $table->unsignedInteger('new_department_id')->comment = "Id phòng ban mới";
            $table->string('new_department_name',100)->comment = "Tên phòng ban mới";
            $table->unsignedInteger('new_manager_id')->comment = "Id manager mới";
            $table->string('new_manager_name',100)->comment = "Tên manager mới";
            
            $table->unsignedInteger('position_id')->comment = "Id chức vụ";
            $table->string('position_name',100)->comment = "Tên chức vụ";
            $table->text('user_comment',400)->nullable()->comment = "Nội dung yêu cầu của user";
            $table->text('reporter_comment',400)->nullable()->comment = "Nội dung comment của reporter";
            $table->unsignedTinyInteger('status')->default(0)->comment = "0 - Chưa duyệt, 1 - Đồng ý, 2 - Từ chối";
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
        Schema::drop('change_direct_manager');
    }
}
