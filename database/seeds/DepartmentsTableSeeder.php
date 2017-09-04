<?php
use Illuminate\Database\Seeder;
use App\Util;

class DepartmentsTableSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run ()
    {
        // Id 1
        DB::table('departments')->insert(
                [
                        'guid' => '5fa664f0f62b4d57917111c4de964d82',
                        'name' => 'Ban Tổng Giám đốc',
                        'created_at' => Util::sz_fCurrentDateTime()
                ]);
        // Id 2
        DB::table('departments')->insert(
                [
                        'guid' => 'c2cec2ea35574af29c7a0649cb8df7a9',
                        'name' => 'Khối Kinh doanh - Tiếp thị',
                        'created_at' => Util::sz_fCurrentDateTime()
                ]);
        // Id 3
        DB::table('departments')->insert(
                [
                        'guid' => '819d68abc1034802aad6e191f5d21e35',
                        'name' => 'Khối Tài chính - Đầu tư',
                        'created_at' => Util::sz_fCurrentDateTime()
                ]);
        // Id 4
        DB::table('departments')->insert(
                [
                        'guid' => '13db24f4e7564993a4ba387a45d04c11',
                        'name' => 'Phòng Hành chính Nhân sự',
                        'created_at' => Util::sz_fCurrentDateTime()
                ]);
        // Id 5
        DB::table('departments')->insert(
                [
                        'guid' => '2f06585045c64a6a91c6bf43e4878d08',
                        'name' => 'Phòng Kế toán',
                        'created_at' => Util::sz_fCurrentDateTime()
                ]);
        // Id 6
        DB::table('departments')->insert(
                [
                        'guid' => 'e8edf3bde7a04183a9843fcb560a3745',
                        'name' => 'Phòng Đầu tư - Khai thác',
                        'parent_id' => 3,
                        'created_at' => Util::sz_fCurrentDateTime()
                ]);
        // Id 7
        DB::table('departments')->insert(
                [
                        'guid' => '2beab376002c4f64bdad4de81fe40368',
                        'name' => 'Phòng Marketing',
                        'parent_id' => 2,
                        'created_at' => Util::sz_fCurrentDateTime()
                ]);
        // Id 8
        DB::table('departments')->insert(
                [
                        'guid' => '7229b607b3c54194aa3282132f2d3aff',
                        'name' => 'Phòng Công nghệ',
                        'created_at' => Util::sz_fCurrentDateTime()
                ]);
        // Id 9
        DB::table('departments')->insert(
                [
                        'guid' => '00cf3ae78104424aa55a4ad61ae0b1e0',
                        'name' => 'Phòng Tài chính',
                        'parent_id' => 3,
                        'created_at' => Util::sz_fCurrentDateTime()
                ]);
        // Id 10
        DB::table('departments')->insert(
                [
                        'guid' => '94cd74728939480c87f40cb0261c1b3f',
                        'name' => 'Phòng Dịch vụ khách hàng',
                        'created_at' => Util::sz_fCurrentDateTime()
                ]);
        // Id 11
        DB::table('departments')->insert(
                [
                        'guid' => 'cd434614eba740b5b6efbfdeb390f2b0',
                        'name' => 'Phòng Cho thuê và QLTN',
                        'created_at' => Util::sz_fCurrentDateTime()
                ]);
        // Id 12
        DB::table('departments')->insert(
                [
                        'guid' => '5884677d2b42488cb34d44f2b48bd581',
                        'name' => 'BP Thư ký kinh doanh',
                        'parent_id' => 2,
                        'created_at' => Util::sz_fCurrentDateTime()
                ]);
        // Id 13
        DB::table('departments')->insert(
                [
                        'guid' => '2c532c31f96e4ef99d60d114a4fcefd9',
                        'name' => 'Sàn Hội sở',
                        'parent_id' => 2,
                        'created_at' => Util::sz_fCurrentDateTime()
                ]);
        // Id 14
        DB::table('departments')->insert(
                [
                        'guid' => 'dbe7304ac5da455ba3159b0089542e22',
                        'name' => 'Sàn Cầu Giấy',
                        'parent_id' => 2,
                        'created_at' => Util::sz_fCurrentDateTime()
                ]);
        // Id 15
        DB::table('departments')->insert(
                [
                        'guid' => '11f60c34568a478cb955bfef97c80464',
                        'name' => 'Sàn Trung Kính',
                        'parent_id' => 2,
                        'created_at' => Util::sz_fCurrentDateTime()
                ]);
        // Id 16
        DB::table('departments')->insert(
                [
                        'guid' => 'c35628539cea417d9d7c0b65d07a7fca',
                        'name' => 'Sàn Hai Bà Trưng',
                        'parent_id' => 2,
                        'created_at' => Util::sz_fCurrentDateTime()
                ]);
        // Id 17
        DB::table('departments')->insert(
                [
                        'guid' => '7dd1c0f04cf84e719226d59b2ce6966b',
                        'name' => 'Sàn Vinhomes',
                        'parent_id' => 2,
                        'created_at' => Util::sz_fCurrentDateTime()
                ]);
        // Id 18
        DB::table('departments')->insert(
                [
                        'guid' => 'db3e947e193a45078cb98298dfe912de',
                        'name' => 'Sàn Quảng Ninh',
                        'parent_id' => 2,
                        'created_at' => Util::sz_fCurrentDateTime()
                ]);
        // Id 19
        DB::table('departments')->insert(
                [
                        'guid' => 'afdbd374144d406bb758bf22537a816c',
                        'name' => 'Phòng Phát triển mạng lưới kinh doanh',
                        'created_at' => Util::sz_fCurrentDateTime()
                ]);
    }
}
