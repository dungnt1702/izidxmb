<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStatusLeaveRequestReportTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('status_leave_request_report', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id')->index()->comment = "Id Users";
            $table->unsignedTinyInteger('26')->comment = "Ngày 26";
            $table->unsignedTinyInteger('27')->comment = "Ngày 27";
            $table->unsignedTinyInteger('28')->comment = "Ngày 28";
            $table->unsignedTinyInteger('29')->comment = "Ngày 29";
            $table->unsignedTinyInteger('30')->comment = "Ngày 30";
            $table->unsignedTinyInteger('31')->comment = "Ngày 31";
            $table->unsignedTinyInteger('01')->comment = "Ngày 01";
            $table->unsignedTinyInteger('02')->comment = "Ngày 02";
            $table->unsignedTinyInteger('03')->comment = "Ngày 03";
            $table->unsignedTinyInteger('04')->comment = "Ngày 04";
            $table->unsignedTinyInteger('05')->comment = "Ngày 05";
            $table->unsignedTinyInteger('06')->comment = "Ngày 06";
            $table->unsignedTinyInteger('07')->comment = "Ngày 07";
            $table->unsignedTinyInteger('08')->comment = "Ngày 08";
            $table->unsignedTinyInteger('09')->comment = "Ngày 09";
            $table->unsignedTinyInteger('10')->comment = "Ngày 10";
            $table->unsignedTinyInteger('11')->comment = "Ngày 11";
            $table->unsignedTinyInteger('12')->comment = "Ngày 12";
            $table->unsignedTinyInteger('13')->comment = "Ngày 13";
            $table->unsignedTinyInteger('14')->comment = "Ngày 14";
            $table->unsignedTinyInteger('15')->comment = "Ngày 15";
            $table->unsignedTinyInteger('16')->comment = "Ngày 16";
            $table->unsignedTinyInteger('17')->comment = "Ngày 17";
            $table->unsignedTinyInteger('18')->comment = "Ngày 18";
            $table->unsignedTinyInteger('19')->comment = "Ngày 19";
            $table->unsignedTinyInteger('20')->comment = "Ngày 20";
            $table->unsignedTinyInteger('21')->comment = "Ngày 21";
            $table->unsignedTinyInteger('22')->comment = "Ngày 22"; 
            $table->unsignedTinyInteger('23')->comment = "Ngày 23"; 
            $table->unsignedTinyInteger('24')->comment = "Ngày 24"; 
            $table->unsignedTinyInteger('25')->comment = "Ngày 25"; 
            $table->unsignedTinyInteger('month')->comment = "Tháng"; 
            $table->unsignedInteger('year')->comment = "Năm"; 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('status_leave_request_report');
    }
}
