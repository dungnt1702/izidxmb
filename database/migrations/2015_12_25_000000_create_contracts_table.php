<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContractsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contracts', function (Blueprint $table) {
            $table->increments('id');
            $table->date('date_in')->comment = "Ngày vào làm việc";
            $table->date('date_out')->nullable()->comment = "Ngày nghỉ việc";
            $table->date('trial_work_contract_date')->nullable()->comment = "Ngày ký hợp thử việc";
            $table->string('trial_work_contract_no', 50)->nullable()->comment = "Số hợp đồng thử việc";
            $table->string('trial_work_contract_scan')->nullable()->comment = "Scan hợp đồng thử việc";
            $table->date('work_contract_date_1')->nullable()->comment = "Ngày ký hợp đồng lần 1";
            $table->string('work_contract_no_1', 50)->nullable()->comment = "Số hợp đồng ký lần 1";
            $table->string('work_contract_scan_1')->nullable()->comment = "Scan hợp đồng ký lần 1";
            $table->date('work_contract_date_2')->nullable()->comment = "Ngày ký hợp đồng lần 2";
            $table->string('work_contract_no_2', 50)->nullable()->comment = "Số hợp đồng ký lần 2";
            $table->string('work_contract_scan_2')->nullable()->comment = "Scan hợp đồng ký lần 2";
            $table->date('work_contract_date_3')->nullable()->comment = "Ngày ký hợp đồng lần 3";
            $table->string('work_contract_no_3', 50)->nullable()->comment = "Số hợp đồng ký lần 3";
            $table->string('work_contract_scan_3')->nullable()->comment = "Scan hợp đồng ký lần 3";
            $table->string('recruitment_no', 50)->nullable()->comment = "Số quyết định tuyển dụng";
            $table->string('recruitment_scan')->nullable()->comment = "Scan quyết định tuyển dụng";
            $table->string('leave_job_no', 50)->nullable()->comment = "Số quyết định nghỉ việc";
            $table->string('leave_job_scan')->nullable()->comment = "Scan quyết định nghỉ việc";
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
        Schema::drop('contracts');
    }
}
