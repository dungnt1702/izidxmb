<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDepartmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('departments', function (Blueprint $table) {
            $table->increments('id');
            $table->string('guid', 50)->unique()->comment = "Group id mã hoá từ Mail server";
            $table->string('name', 200)->unique()->comment = "Tên nhóm từ Mail server";
            $table->unsignedInteger('parent_id')->default(0)->comment = "Id của Khối";
            $table->float('numb_of_work')->default(5.5)->comment = "Số ngày làm việc trong tuần";
            $table->time('time_start')->default('08:00:00')->comment = "Thời gian bắt đầu làm việc";
            $table->time('time_end')->default('17:30:00')->comment = "Thời gian kết thúc làm việc";
            $table->unsignedTinyInteger('status')->default(1)->comment = "Trạng thái 0 và 1";
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
        Schema::drop('departments');
    }
}
