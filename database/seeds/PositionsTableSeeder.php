<?php
use Illuminate\Database\Seeder;
use App\Util;

class PositionsTableSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run ()
    {
        // Id 1
        DB::table('positions')->insert(
                [
                        'name' => 'Tổng Giám Đốc',
                        'created_at' => Util::sz_fCurrentDateTime()
                ]);
        // Id 2
        DB::table('positions')->insert(
                [
                        'name' => 'Phó Tổng Giám Đốc',
                        'created_at' => Util::sz_fCurrentDateTime()
                ]);
        // Id 3
        DB::table('positions')->insert(
                [
                        'name' => 'Giám đốc khối',
                        'created_at' => Util::sz_fCurrentDateTime()
                ]);
        // Id 4
        DB::table('positions')->insert(
                [
                        'name' => 'Giám đốc',
                        'created_at' => Util::sz_fCurrentDateTime()
                ]);
        // Id 5
        DB::table('positions')->insert(
                [
                        'name' => 'Phó Giám đốc',
                        'created_at' => Util::sz_fCurrentDateTime()
                ]);
        // Id 6
        DB::table('positions')->insert(
                [
                        'name' => 'Trưởng phòng',
                        'created_at' => Util::sz_fCurrentDateTime()
                ]);
        // Id 7
        DB::table('positions')->insert(
                [
                        'name' => 'Phó phòng',
                        'created_at' => Util::sz_fCurrentDateTime()
                ]);
        // Id 8
        DB::table('positions')->insert(
                [
                        'name' => 'Trưởng bộ phận',
                        'created_at' => Util::sz_fCurrentDateTime()
                ]);
        // Id 9
        DB::table('positions')->insert(
                [
                        'name' => 'Trưởng nhóm',
                        'created_at' => Util::sz_fCurrentDateTime()
                ]);
        // Id 10
        DB::table('positions')->insert(
                [
                        'name' => 'Kế toán trưởng',
                        'created_at' => Util::sz_fCurrentDateTime()
                ]);
        // Id 11
        DB::table('positions')->insert(
                [
                        'name' => 'Kỹ sư trưởng',
                        'created_at' => Util::sz_fCurrentDateTime()
                ]);
        // Id 12
        DB::table('positions')->insert(
                [
                        'name' => 'Thư ký Kinh doanh',
                        'created_at' => Util::sz_fCurrentDateTime()
                ]);
        // Id 13
        DB::table('positions')->insert(
                [
                        'name' => 'Chuyên viên',
                        'created_at' => Util::sz_fCurrentDateTime()
                ]);
        // Id 14
        DB::table('positions')->insert(
                [
                        'name' => 'Nhân viên',
                        'created_at' => Util::sz_fCurrentDateTime()
                ]);
    }
}
