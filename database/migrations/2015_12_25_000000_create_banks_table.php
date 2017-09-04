<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBanksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('banks', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id')->index()->comment = "Id của nhân viên";
            $table->string('bank_acc_no', 100)->comment = "Số tài khoản ngân hàng";
            $table->string('bank_acc_name', 100)->comment = "Tên chủ tài khoản";
            $table->string('bank_name')->comment = "Tên Ngân hàng";
            $table->string('bank_branch')->comment = "Chi nhánh ngân hàng";
            $table->string('bank_addr')->nullable()->comment = "Địa chỉ ngân hàng";
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
        Schema::drop('banks');
    }
}
