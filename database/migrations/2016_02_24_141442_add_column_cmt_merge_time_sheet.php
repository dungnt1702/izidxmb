<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnCmtMergeTimeSheet extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('merge_time_sheet', function (Blueprint $table) {
            $table->string('26_cmt')->after('26');
            $table->string('27_cmt')->after('27');
            $table->string('28_cmt')->after('28');
            $table->string('29_cmt')->after('29');
            $table->string('30_cmt')->after('30');
            $table->string('31_cmt')->after('31');
            $table->string('01_cmt')->after('01');
            $table->string('02_cmt')->after('02');
            $table->string('03_cmt')->after('03');
            $table->string('04_cmt')->after('04');
            $table->string('05_cmt')->after('05');
            $table->string('06_cmt')->after('06');
            $table->string('07_cmt')->after('07');
            $table->string('08_cmt')->after('08');
            $table->string('09_cmt')->after('09');
            $table->string('10_cmt')->after('10');
            $table->string('11_cmt')->after('11');
            $table->string('12_cmt')->after('12');
            $table->string('13_cmt')->after('13');
            $table->string('14_cmt')->after('14');
            $table->string('15_cmt')->after('15');
            $table->string('16_cmt')->after('16');
            $table->string('17_cmt')->after('17');
            $table->string('18_cmt')->after('18');
            $table->string('19_cmt')->after('19');
            $table->string('20_cmt')->after('20');
            $table->string('21_cmt')->after('21');
            $table->string('22_cmt')->after('22');
            $table->string('23_cmt')->after('23');
            $table->string('24_cmt')->after('24');
            $table->string('25_cmt')->after('25');
            $table->string('department_name')->after('code');
            $table->integer('department_id')->after('department_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('merge_time_sheet', function (Blueprint $table) {
            //
        });
    }
}
