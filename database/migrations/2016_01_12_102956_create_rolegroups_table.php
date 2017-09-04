<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRolegroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rolegroups', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 50)->unique()->comment = "Tên vai trò";
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
        Schema::drop('rolegroups');
    }
}
