<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jobs', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedTinyInteger('status')->default(1)->comment = "Trạng thái 0 và 1";
            $table->unsignedTinyInteger('enable_sunday')->default(1)->comment = "Kinh doanh là 1 - Văn phòng là 0";
            $table->string('name', 100)->comment = "Tên nhóm nghề";
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
        Schema::drop('jobs');
    }
}
