<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('groups', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 200)->unique()->comment = "Tên nhóm từ Mail server";
            $table->unsignedInteger('department_id')->default(0)->comment = "Id của bảng Phòng ban";
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
        Schema::drop('groups');
    }
}
