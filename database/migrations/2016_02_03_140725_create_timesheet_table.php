<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTimesheetTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('timesheet', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id')->index()->comment = "Id Users";
            $table->string('name', 100)->comment = "Tên nhân viên";
            $table->string('code', 20)->comment = "Mã nhân viên";
            $table->unsignedInteger('department_id')->comment = "Id Phòng ban";
            $table->string('department_name',100)->comment = "Tên phòng ban";
            $table->unsignedInteger('position_id')->comment = "Id chức vụ";
            $table->string('position_name',100)->comment = "Tên chức vụ";
            $table->string('26',255)->comment = "Ngày 26";
            $table->string('27',255)->comment = "Ngày 27";
            $table->string('28',255)->comment = "Ngày 28";
            $table->string('29',255)->comment = "Ngày 29";
            $table->string('30',255)->comment = "Ngày 30";
            $table->string('31',255)->comment = "Ngày 31";
            $table->string('01',255)->comment = "Ngày 01";
            $table->string('02',255)->comment = "Ngày 02";
            $table->string('03',255)->comment = "Ngày 03";
            $table->string('04',255)->comment = "Ngày 04";
            $table->string('05',255)->comment = "Ngày 05";
            $table->string('06',255)->comment = "Ngày 06";
            $table->string('07',255)->comment = "Ngày 07";
            $table->string('08',255)->comment = "Ngày 08";
            $table->string('09',255)->comment = "Ngày 09";
            $table->string('10',255)->comment = "Ngày 10";
            $table->string('11',255)->comment = "Ngày 11";
            $table->string('12',255)->comment = "Ngày 12";
            $table->string('13',255)->comment = "Ngày 13";
            $table->string('14',255)->comment = "Ngày 14";
            $table->string('15',255)->comment = "Ngày 15";
            $table->string('16',255)->comment = "Ngày 16";
            $table->string('17',255)->comment = "Ngày 17";
            $table->string('18',255)->comment = "Ngày 18";
            $table->string('19',255)->comment = "Ngày 19";
            $table->string('20',255)->comment = "Ngày 20";
            $table->string('21',255)->comment = "Ngày 21";
            $table->string('22',255)->comment = "Ngày 22";
            $table->string('23',255)->comment = "Ngày 23";
            $table->string('24',255)->comment = "Ngày 24";
            $table->string('25',255)->comment = "Ngày 25";
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
        Schema::drop('timesheet');
    }
}
