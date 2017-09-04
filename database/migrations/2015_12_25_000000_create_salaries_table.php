<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('salaries', function (Blueprint $table) {
            $table->increments('id');
            $table->float('trial_salary')->default(0)->comment = "Lương thử việc";
            $table->float('main_salary')->default(0)->comment = "Lương chính";
            $table->float('allowances')->default(0)->comment = "Phụ cấp";
            $table->float('social_insurance_salary')->default(0)->comment = "Lương đóng BHXH";
            $table->float('social_insurance_subtract')->default(0)->comment = "Trừ phí Bảo hiểm xã hội";
            $table->float('health_insurance_subtract')->default(0)->comment = "Trừ bảo hiểm y tế";
            $table->float('unemployment_insurance_subtract')->default(0)->comment = "Trừ Bảo hiểm thất nghiệp";
            $table->float('union_subtract')->default(0)->comment = "Trừ phí Công đoàn";
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
        Schema::drop('salaries');
    }
}
