<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLeaveRequestReportTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leave_request_report', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id')->index()->comment = "Id Users";
            $table->string('name', 100)->comment = "tên người tạo";
            $table->string('code', 20)->comment = "Mã nhân viên";
            $table->unsignedInteger('department_id')->comment = "Id Phòng ban";
            $table->string('department_name',100)->comment = "Tên phòng ban";
            $table->unsignedInteger('position_id')->comment = "Id chức vụ";
            $table->string('position_name',100)->comment = "Tên chức vụ";
            $table->unsignedTinyInteger('enable_sunday')->default(1)->comment = "Cho phép mở ngày chủ nhật";
            $table->text('26',400)->comment = "Ngày 26";
            $table->text('27',400)->comment = "Ngày 27";
            $table->text('28',400)->comment = "Ngày 28";
            $table->text('29',400)->comment = "Ngày 29";
            $table->text('30',400)->comment = "Ngày 30";
            $table->text('31',400)->comment = "Ngày 31";
            $table->text('01',400)->comment = "Ngày 01";
            $table->text('02',400)->comment = "Ngày 02";
            $table->text('03',400)->comment = "Ngày 03";
            $table->text('04',400)->comment = "Ngày 04";
            $table->text('05',400)->comment = "Ngày 05";
            $table->text('06',400)->comment = "Ngày 06";
            $table->text('07',400)->comment = "Ngày 07";
            $table->text('08',400)->comment = "Ngày 08";
            $table->text('09',400)->comment = "Ngày 09";
            $table->text('10',400)->comment = "Ngày 10";
            $table->text('11',400)->comment = "Ngày 11";
            $table->text('12',400)->comment = "Ngày 12";
            $table->text('13',400)->comment = "Ngày 13";
            $table->text('14',400)->comment = "Ngày 14";
            $table->text('15',400)->comment = "Ngày 15";
            $table->text('16',400)->comment = "Ngày 16";
            $table->text('17',400)->comment = "Ngày 17";
            $table->text('18',400)->comment = "Ngày 18";
            $table->text('19',400)->comment = "Ngày 19";
            $table->text('20',400)->comment = "Ngày 20";
            $table->text('21',400)->comment = "Ngày 21";
            $table->text('22',400)->comment = "Ngày 22"; 
            $table->text('23',400)->comment = "Ngày 23"; 
            $table->text('24',400)->comment = "Ngày 24"; 
            $table->text('25',400)->comment = "Ngày 25"; 
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
        Schema::drop('leave_request_report');
    }
}
