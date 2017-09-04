<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('profiles', function (Blueprint $table) {
            $table->increments('id');
            $table->date('birthday')->comment = "Ngày tháng năm sinh";
            $table->unsignedTinyInteger('gender')->default(1)->comment = "Giới tính (1 là Nam, 0 là Nữ)";
            $table->string('private_email', 100)->nullable()->comment = "Email cá nhân";
            $table->unsignedInteger('phone_no_1')->nullable()->comment = "Số điện thoại di động 1";
            $table->unsignedInteger('phone_no_2')->nullable()->comment = "Số điện thoại di động 2";
            $table->string('permanent_addr')->nullable()->comment = "Địa chỉ thường trú";
            $table->unsignedInteger('permanent_district')->nullable()->comment = "Id Quận/Huyện (bảng areas) địa chỉ thường trú";
            $table->unsignedInteger('permanent_city')->nullable()->comment = "Id Tỉnh/Thành (bảng areas) địa chỉ thường trú";
            $table->string('contact_addr')->nullable()->comment = "Địa chỉ liên hệ";
            $table->unsignedInteger('contact_district')->nullable()->comment = "Id Quận/Huyện (bảng areas) địa chỉ liên hệ";
            $table->unsignedInteger('contact_city')->nullable()->comment = "Id Tỉnh/Thành (bảng areas) địa chỉ liên hệ";
            $table->string('born_addr')->nullable()->comment = "Địa chỉ khai sinh";
            $table->unsignedInteger('born_district')->nullable()->comment = "Id Quận/Huyện (bảng areas) địa chỉ khai sinh";
            $table->unsignedInteger('born_city')->nullable()->comment = "Id Tỉnh/Thành (bảng areas) địa chỉ khai sinh";
            $table->unsignedInteger('identity_card_no')->comment = "Số chứng minh nhân dân";
            $table->date('identity_card_date')->comment = "Ngày cấp CMND";
            $table->unsignedInteger('identity_card_area')->comment = "Id (bảng areas) Nơi cấp CMND";
            $table->string('edu_level')->nullable()->comment = "Trình độ văn hoá";
            $table->string('skill_level')->nullable()->comment = "Trình độ chuyên môn";
            $table->unsignedTinyInteger('ethnic')->default(0)->comment = "Dân tộc";
            $table->unsignedTinyInteger('religion')->default(0)->comment = "Tôn giáo";
            $table->unsignedInteger('tax_code')->nullable()->comment = "Mã số thuế cá nhân";
            $table->unsignedTinyInteger('dependent_people_no')->default(0)->comment = "Số người phụ thuộc";
            $table->string('social_insurance_no', 50)->nullable()->comment = "Số sổ BHXH";
            $table->string('father_name', 50)->nullable()->comment = "Họ tên bố";
            $table->string('mother_name', 50)->nullable()->comment = "Họ tên mẹ";
            $table->string('other_relatives_name')->nullable()->comment = "Họ tên người thân khác (phân tách bằng dấu phẩy)";
            $table->text('scan_profile')->nullable()->comment = "Scan thông tin cá nhân";
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
        Schema::drop('profiles');
    }
}
