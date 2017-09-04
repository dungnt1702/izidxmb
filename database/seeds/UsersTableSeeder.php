<?php
use Illuminate\Database\Seeder;
use App\Util;

class UsersTableSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run ()
    {
        // Id 1
        DB::table('users')->insert(
                [
                        'code' => 'MB00000',
                        'name' => 'SuperAdmin',
                        'email' => 'admin@dxmb.vn',
                        'password' => bcrypt('Dxmb2015'),
                        'role_id' => 1,
                        'created_at' => Util::sz_fCurrentDateTime()
                ]);
        // Id 2
        DB::table('users')->insert(
                [
                        'code' => 'MB01',
                        'name' => 'Vũ Cương Quyết',
                        'email' => 'Quyetvc@datxanh.com.vn',
                        'password' => bcrypt('Dxmb2015'),
                        'is_manager' => 1,
                        'department_id' => 1, // B.TGĐ
                        'position_id' => 1, // TGĐ
                        'job_id' => 1, // Quản lý
                        'role_id' => 2,
                        'created_at' => Util::sz_fCurrentDateTime()
                ]);
        // Id 3
        DB::table('users')->insert(
                [
                        'code' => 'MB700',
                        'name' => 'Ngô Thành Chung',
                        'email' => 'chungnt@dxmb.vn',
                        'password' => bcrypt('Dxmb2015'),
                        'is_manager' => 1,
                        'department_id' => 3, // Khối TC-ĐT
                        'position_id' => 4, // GĐK
                        'job_id' => 5, // Tài chính
                        'role_id' => 2,
                        'direct_manager_id' => 2, // Vũ Cương Quyết
                        'created_at' => Util::sz_fCurrentDateTime()
                ]);
        // Id 4
        DB::table('users')->insert(
                [
                        'code' => 'MB45',
                        'name' => 'Hồ Thị Thu Mai',
                        'email' => 'maihtt@dxmb.vn',
                        'password' => bcrypt('Dxmb2015'),
                        'is_manager' => 1,
                        'department_id' => 2, // Khối KD-TT
                        'position_id' => 4, // GĐK
                        'job_id' => 6, // Marketing
                        'role_id' => 2,
                        'direct_manager_id' => 2, // Vũ Cương Quyết
                        'created_at' => Util::sz_fCurrentDateTime()
                ]);
        // Id 5
        DB::table('users')->insert(
                [
                        'code' => 'MB06',
                        'name' => 'Nguyễn Thị Ngọc Dung',
                        'email' => 'dungntn@dxmb.vn',
                        'password' => bcrypt('Dxmb2015'),
                        'is_manager' => 1,
                        'department_id' => 6, // P.ĐT-KT
                        'position_id' => 4, // GĐ
                        'job_id' => 14, // Đầu tư
                        'role_id' => 2,
                        'direct_manager_id' => 3, // Ngô Thành Chung
                        'created_at' => Util::sz_fCurrentDateTime()
                ]);
        // Id 6
        DB::table('users')->insert(
                [
                        'code' => 'MB24',
                        'name' => 'Nguyễn Thị Lệ Uyên',
                        'email' => 'uyenntl@dxmb.vn',
                        'password' => bcrypt('Dxmb2015'),
                        'is_manager' => 1,
                        'hr_type' => 1, // Is HRM
                        'department_id' => 4, // P.HCNS
                        'position_id' => 4, // GĐ
                        'job_id' => 3, // Hành chính nhân sự
                        'role_id' => 2,
                        'direct_manager_id' => 2, // Vũ Cương Quyết
                        'created_at' => Util::sz_fCurrentDateTime()
                ]);
        // Id 7
        DB::table('users')->insert(
                [
                        'code' => 'MB204',
                        'name' => 'Phạm Bách Sơn Tùng',
                        'email' => 'tungpbs@dxmb.vn',
                        'password' => bcrypt('Dxmb2015'),
                        'is_manager' => 1,
                        'department_id' => 5, // P.KT
                        'position_id' => 10, // Kế toán trưởng
                        'job_id' => 4, // Kế toán
                        'role_id' => 2,
                        'direct_manager_id' => 2, // Vũ Cương Quyết
                        'created_at' => Util::sz_fCurrentDateTime()
                ]);
        // Id 8
        DB::table('users')->insert(
                [
                        'code' => 'MB12',
                        'name' => 'Nguyễn Ngọc Hải',
                        'email' => 'hainn@dxmb.vn',
                        'password' => bcrypt('Dxmb2015'),
                        'is_manager' => 1,
                        'department_id' => 14, // Sàn Cầu Giấy
                        'position_id' => 4, // GĐ
                        'job_id' => 2, // Kinh doanh
                        'role_id' => 2,
                        'direct_manager_id' => 4, // Hồ Thị Thu Mai
                        'created_at' => Util::sz_fCurrentDateTime()
                ]);
        // Id 9
        DB::table('users')->insert(
                [
                        'code' => 'MB32',
                        'name' => 'Đinh Quang Tuấn',
                        'email' => 'tuandq@dxmb.vn',
                        'password' => bcrypt('Dxmb2015'),
                        'is_manager' => 1,
                        'department_id' => 17, // Sàn Vinhomes
                        'position_id' => 4, // GĐ
                        'job_id' => 2, // Kinh doanh
                        'role_id' => 2,
                        'direct_manager_id' => 4, // Hồ Thị Thu Mai
                        'created_at' => Util::sz_fCurrentDateTime()
                ]);
        // Id 10
        DB::table('users')->insert(
                [
                        'code' => 'MB44',
                        'name' => 'Nguyễn Văn Văn',
                        'email' => 'vannv@dxmb.vn',
                        'password' => bcrypt('Dxmb2015'),
                        'is_manager' => 1,
                        'department_id' => 15, // Sàn Trung Kính
                        'position_id' => 4, // GĐ
                        'job_id' => 2, // Kinh doanh
                        'role_id' => 2,
                        'direct_manager_id' => 4, // Hồ Thị Thu Mai
                        'created_at' => Util::sz_fCurrentDateTime()
                ]);
        // Id 11
        DB::table('users')->insert(
                [
                        'code' => 'MB74',
                        'name' => 'Đỗ Thị Thúy Hằng',
                        'email' => 'hangdtt@dxmb.vn',
                        'password' => bcrypt('Dxmb2015'),
                        'is_manager' => 1,
                        'department_id' => 13, // Sàn Hội sở
                        'position_id' => 4, // GĐ
                        'job_id' => 2, // Kinh doanh
                        'role_id' => 2,
                        'direct_manager_id' => 4, // Hồ Thị Thu Mai
                        'created_at' => Util::sz_fCurrentDateTime()
                ]);
        // Id 12
        DB::table('users')->insert(
                [
                        'code' => 'MB371',
                        'name' => 'Trần Minh Thắng',
                        'email' => 'thangtm@dxmb.vn',
                        'password' => bcrypt('Dxmb2015'),
                        'is_manager' => 1,
                        'department_id' => 16, // Sàn Hai Bà Trưng
                        'position_id' => 4, // GĐ
                        'job_id' => 2, // Kinh doanh
                        'role_id' => 2,
                        'direct_manager_id' => 4, // Hồ Thị Thu Mai
                        'created_at' => Util::sz_fCurrentDateTime()
                ]);
        // Id 13
        DB::table('users')->insert(
                [
                        'code' => 'MB413',
                        'name' => 'Nguyễn Thị Thùy',
                        'email' => 'thuynt@dxmb.vn',
                        'password' => bcrypt('Dxmb2015'),
                        'is_manager' => 1,
                        'department_id' => 11, // P.Cho thuê
                        'position_id' => 4, // GĐ
                        'job_id' => 2, // Kinh doanh
                        'role_id' => 2,
                        'direct_manager_id' => 2, // Vũ Cương Quyết
                        'created_at' => Util::sz_fCurrentDateTime()
                ]);
        // Id 14
        DB::table('users')->insert(
                [
                        'code' => 'MB828',
                        'name' => 'Nguyễn Việt Hưng',
                        'email' => 'hungnv@dxmb.vn',
                        'password' => bcrypt('Dxmb2015'),
                        'is_manager' => 1,
                        'department_id' => 11, // P.Cho thuê
                        'position_id' => 5, // P.GĐ
                        'job_id' => 2, // Kinh doanh
                        'role_id' => 2,
                        'direct_manager_id' => 13, // Nguyễn Thị Thùy
                        'created_at' => Util::sz_fCurrentDateTime()
                ]);
        // Id 15
        DB::table('users')->insert(
                [
                        'code' => 'MB04',
                        'name' => 'Trần Quốc Trung',
                        'email' => 'trungtq@dxmb.vn',
                        'password' => bcrypt('Dxmb2015'),
                        'is_manager' => 1,
                        'department_id' => 6, // P.ĐT-KT
                        'position_id' => 6, // TP
                        'job_id' => 14, // Đầu tư
                        'role_id' => 2,
                        'direct_manager_id' => 5, // Nguyễn Thị Ngọc Dung
                        'created_at' => Util::sz_fCurrentDateTime()
                ]);
        // Id 16
        DB::table('users')->insert(
                [
                        'code' => 'MB10',
                        'name' => 'Trần Thị Hải Huyền',
                        'email' => 'huyentth@dxmb.vn',
                        'password' => bcrypt('Dxmb2015'),
                        'is_manager' => 1,
                        'department_id' => 19, // P.PTMLKD
                        'position_id' => 6, // TP
                        'job_id' => 2, // Kinh doanh
                        'role_id' => 2,
                        'direct_manager_id' => 4, // Hồ Thị Thu Mai
                        'created_at' => Util::sz_fCurrentDateTime()
                ]);
        // Id 17
        DB::table('users')->insert(
                [
                        'code' => 'MB51',
                        'name' => 'Lê Xuân Hưng',
                        'email' => 'hunglx@dxmb.vn',
                        'password' => bcrypt('Dxmb2015'),
                        'is_manager' => 1,
                        'department_id' => 14, // Sàn CG
                        'position_id' => 6, // TP
                        'job_id' => 2, // Kinh doanh
                        'role_id' => 2,
                        'direct_manager_id' => 8, // Nguyễn Ngọc Hải
                        'created_at' => Util::sz_fCurrentDateTime()
                ]);
        // Id 18
        DB::table('users')->insert(
                [
                        'code' => 'MB85',
                        'name' => 'Trần Thị Thùy Dương A',
                        'email' => 'duongttt@dxmb.vn',
                        'password' => bcrypt('Dxmb2015'),
                        'is_manager' => 1,
                        'department_id' => 17, // Sàn Vinhomes
                        'position_id' => 6, // TP
                        'job_id' => 2, // Kinh doanh
                        'role_id' => 2,
                        'direct_manager_id' => 9, // Đinh Quang Tuấn
                        'created_at' => Util::sz_fCurrentDateTime()
                ]);
        // Id 19
        DB::table('users')->insert(
                [
                        'code' => 'MB105',
                        'name' => 'Nguyễn Hoài Thương',
                        'email' => 'thuongnh@dxmb.vn',
                        'password' => bcrypt('Dxmb2015'),
                        'is_manager' => 1,
                        'department_id' => 18, // Sàn QN
                        'position_id' => 6, // TP
                        'job_id' => 2, // Kinh doanh
                        'role_id' => 2,
                        'direct_manager_id' => 4, // Hồ Thị Thu Mai
                        'created_at' => Util::sz_fCurrentDateTime()
                ]);
        // Id 20
        DB::table('users')->insert(
                [
                        'code' => 'MB460',
                        'name' => 'Nguyễn Thị Thu Thủy',
                        'email' => 'thuyntt@dxmb.vn',
                        'password' => bcrypt('Dxmb2015'),
                        'is_manager' => 1,
                        'department_id' => 11, // P.Cho thuê
                        'position_id' => 6, // TP
                        'job_id' => 2, // Kinh doanh
                        'role_id' => 2,
                        'direct_manager_id' => 14, // Nguyễn Việt Hưng
                        'created_at' => Util::sz_fCurrentDateTime()
                ]);
        // Id 21
        DB::table('users')->insert(
                [
                        'code' => 'MB608',
                        'name' => 'Nguyễn Tuấn Dũng',
                        'email' => 'dungnt@dxmb.vn',
                        'password' => bcrypt('Dxmb2015'),
                        'is_manager' => 1,
                        'department_id' => 8, // P.CN
                        'position_id' => 6, // TP
                        'job_id' => 7, // Phát triển phần mềm
                        'role_id' => 2,
                        'direct_manager_id' => 2, // Vũ Cương Quyết
                        'created_at' => Util::sz_fCurrentDateTime()
                ]);
        // Id 22
        DB::table('users')->insert(
                [
                        'code' => 'MB422',
                        'name' => 'Bùi Thị Hoàng Yến',
                        'email' => 'yenbth@dxmb.vn',
                        'password' => bcrypt('Dxmb2015'),
                        'is_manager' => 1,
                        'department_id' => 7, // P.MKT
                        'position_id' => 4, // GĐ
                        'job_id' => 6, // Marketing
                        'role_id' => 2,
                        'direct_manager_id' => 4, // Hồ Thị Thu Mai
                        'created_at' => Util::sz_fCurrentDateTime()
                ]);
        // Id 23
        DB::table('users')->insert(
                [
                        'code' => 'MB919',
                        'name' => 'Nguyễn Thị Hoài Thu',
                        'email' => 'thunth@dxmb.vn',
                        'password' => bcrypt('Dxmb2015'),
                        'is_manager' => 1,
                        'department_id' => 7, // P.MKT
                        'position_id' => 6, // TP
                        'job_id' => 6, // Marketing
                        'role_id' => 2,
                        'direct_manager_id' => 22, // Bùi Thị Hoàng Yến
                        'created_at' => Util::sz_fCurrentDateTime()
                ]);
        // Id 24
        DB::table('users')->insert(
                [
                        'code' => 'MB558',
                        'name' => 'Nguyễn Phụng Hiệp',
                        'email' => 'hiepnp@dxmb.vn',
                        'password' => bcrypt('Dxmb2015'),
                        'is_manager' => 1,
                        'department_id' => 11, // P.Cho thuê
                        'position_id' => 11, // Kỹ sư trưởng
                        'job_id' => 9, // Kỹ thuật hệ thống
                        'role_id' => 2,
                        'direct_manager_id' => 13, // Nguyễn Thị Thùy
                        'created_at' => Util::sz_fCurrentDateTime()
                ]);
        // Id 25
        DB::table('users')->insert(
                [
                        'code' => 'MB708',
                        'name' => 'Trần Đông Dương',
                        'email' => 'duongtd@dxmb.vn',
                        'password' => bcrypt('Dxmb2015'),
                        'is_manager' => 1,
                        'department_id' => 9, // P.TC
                        'position_id' => 8, // Trưởng bộ phận
                        'job_id' => 5, // Tài chính
                        'role_id' => 2,
                        'direct_manager_id' => 3, // Ngô Thành Chung
                        'created_at' => Util::sz_fCurrentDateTime()
                ]);
        // Id 26
        DB::table('users')->insert(
                [
                        'code' => 'MB955',
                        'name' => 'Trần Ngọc Linh',
                        'email' => 'linhtn@dxmb.vn',
                        'password' => bcrypt('Dxmb2015'),
                        'is_manager' => 1,
                        'department_id' => 7, // P.MKT
                        'position_id' => 6, // TP
                        'job_id' => 6, // Marketing
                        'role_id' => 2,
                        'direct_manager_id' => 22, // Bùi Thị Hoàng Yến
                        'created_at' => Util::sz_fCurrentDateTime()
                ]);
        // Id 27
        DB::table('users')->insert(
                [
                        'code' => 'MB954',
                        'name' => 'Lê Ngọc Hiệp',
                        'email' => 'hiepln@dxmb.vn',
                        'password' => bcrypt('Dxmb2015'),
                        'is_manager' => 1,
                        'department_id' => 11, // P.Cho thuê
                        'position_id' => 8, // Trưởng bộ phận
                        'job_id' => 2, // Kinh doanh
                        'role_id' => 2,
                        'direct_manager_id' => 13, // Nguyễn Thị Thùy
                        'created_at' => Util::sz_fCurrentDateTime()
                ]);
        // Id 28
        DB::table('users')->insert(
                [
                        'code' => 'MB124',
                        'name' => 'Lê Văn Thắng A',
                        'email' => 'thanglv1@dxmb.vn',
                        'password' => bcrypt('Dxmb2015'),
                        'is_manager' => 1,
                        'department_id' => 14, // Sàn CG
                        'position_id' => 9, // Trưởng nhóm
                        'job_id' => 2, // Kinh doanh
                        'role_id' => 2,
                        'direct_manager_id' => 17, // Lê Xuân Hưng
                        'created_at' => Util::sz_fCurrentDateTime()
                ]);
        // Id 29
        DB::table('users')->insert(
                [
                        'code' => 'MB125',
                        'name' => 'Bùi Văn Tình',
                        'email' => 'tinhbv@dxmb.vn',
                        'password' => bcrypt('Dxmb2015'),
                        'is_manager' => 1,
                        'department_id' => 14, // Sàn CG
                        'position_id' => 9, // Trưởng nhóm
                        'job_id' => 2, // Kinh doanh
                        'role_id' => 2,
                        'direct_manager_id' => 17, // Lê Xuân Hưng
                        'created_at' => Util::sz_fCurrentDateTime()
                ]);
        // Id 30
        DB::table('users')->insert(
                [
                        'code' => 'MB254',
                        'name' => 'Đỗ Đức Ngọc',
                        'email' => 'ngocdd@dxmb.vn',
                        'password' => bcrypt('Dxmb2015'),
                        'is_manager' => 1,
                        'department_id' => 14, // Sàn CG
                        'position_id' => 9, // Trưởng nhóm
                        'job_id' => 2, // Kinh doanh
                        'role_id' => 2,
                        'direct_manager_id' => 17, // Lê Xuân Hưng
                        'created_at' => Util::sz_fCurrentDateTime()
                ]);
        // Id 31
        DB::table('users')->insert(
                [
                        'code' => 'MB305',
                        'name' => 'Trịnh Thị Mai',
                        'email' => 'maitt@dxmb.vn',
                        'password' => bcrypt('Dxmb2015'),
                        'is_manager' => 1,
                        'department_id' => 14, // Sàn CG
                        'position_id' => 9, // Trưởng nhóm
                        'job_id' => 2, // Kinh doanh
                        'role_id' => 2,
                        'direct_manager_id' => 17, // Lê Xuân Hưng
                        'created_at' => Util::sz_fCurrentDateTime()
                ]);
        // Id 32
        DB::table('users')->insert(
                [
                        'code' => 'MB250',
                        'name' => 'Nguyễn Khắc Linh',
                        'email' => 'linhnk@dxmb.vn',
                        'password' => bcrypt('Dxmb2015'),
                        'is_manager' => 1,
                        'department_id' => 16, // Sàn HBT
                        'position_id' => 9, // Trưởng nhóm
                        'job_id' => 2, // Kinh doanh
                        'role_id' => 2,
                        'direct_manager_id' => 12, // Trần Minh Thắng
                        'created_at' => Util::sz_fCurrentDateTime()
                ]);
        // Id 33
        DB::table('users')->insert(
                [
                        'code' => 'MB366',
                        'name' => 'Nguyễn Thị Hường A',
                        'email' => 'huongnt3@dxmb.vn',
                        'password' => bcrypt('Dxmb2015'),
                        'is_manager' => 1,
                        'department_id' => 16, // Sàn HBT
                        'position_id' => 9, // Trưởng nhóm
                        'job_id' => 2, // Kinh doanh
                        'role_id' => 2,
                        'direct_manager_id' => 12, // Trần Minh Thắng
                        'created_at' => Util::sz_fCurrentDateTime()
                ]);
        // Id 34
        DB::table('users')->insert(
                [
                        'code' => 'MB451',
                        'name' => 'Đinh Quang Lương',
                        'email' => 'luongdq@dxmb.vn',
                        'password' => bcrypt('Dxmb2015'),
                        'is_manager' => 1,
                        'department_id' => 16, // Sàn HBT
                        'position_id' => 9, // Trưởng nhóm
                        'job_id' => 2, // Kinh doanh
                        'role_id' => 2,
                        'direct_manager_id' => 12, // Trần Minh Thắng
                        'created_at' => Util::sz_fCurrentDateTime()
                ]);
        // Id 35
        DB::table('users')->insert(
                [
                        'code' => 'MB113',
                        'name' => 'Mai Viết Vĩnh',
                        'email' => 'vinhmv@dxmb.vn',
                        'password' => bcrypt('Dxmb2015'),
                        'is_manager' => 1,
                        'department_id' => 13, // Sàn HS
                        'position_id' => 9, // Trưởng nhóm
                        'job_id' => 2, // Kinh doanh
                        'role_id' => 2,
                        'direct_manager_id' => 11, // Đỗ Thị Thúy Hằng
                        'created_at' => Util::sz_fCurrentDateTime()
                ]);
        // Id 36
        DB::table('users')->insert(
                [
                        'code' => 'MB114',
                        'name' => 'Hà Minh Tuấn',
                        'email' => 'tuanhm@dxmb.vn',
                        'password' => bcrypt('Dxmb2015'),
                        'is_manager' => 1,
                        'department_id' => 13, // Sàn HS
                        'position_id' => 9, // Trưởng nhóm
                        'job_id' => 2, // Kinh doanh
                        'role_id' => 2,
                        'direct_manager_id' => 11, // Đỗ Thị Thúy Hằng
                        'created_at' => Util::sz_fCurrentDateTime()
                ]);
        // Id 37
        DB::table('users')->insert(
                [
                        'code' => 'MB147',
                        'name' => 'Nguyễn Thị Hồng Nhung A',
                        'email' => 'nhungnth1@dxmb.vn',
                        'password' => bcrypt('Dxmb2015'),
                        'is_manager' => 1,
                        'department_id' => 13, // Sàn HS
                        'position_id' => 9, // Trưởng nhóm
                        'job_id' => 2, // Kinh doanh
                        'role_id' => 2,
                        'direct_manager_id' => 11, // Đỗ Thị Thúy Hằng
                        'created_at' => Util::sz_fCurrentDateTime()
                ]);
        // Id 38
        DB::table('users')->insert(
                [
                        'code' => 'MB213',
                        'name' => 'Nguyễn Đình Đức',
                        'email' => 'ducnd@dxmb.vn',
                        'password' => bcrypt('Dxmb2015'),
                        'is_manager' => 1,
                        'department_id' => 13, // Sàn HS
                        'position_id' => 9, // Trưởng nhóm
                        'job_id' => 2, // Kinh doanh
                        'role_id' => 2,
                        'direct_manager_id' => 11, // Đỗ Thị Thúy Hằng
                        'created_at' => Util::sz_fCurrentDateTime()
                ]);
        // Id 39
        DB::table('users')->insert(
                [
                        'code' => 'MB33',
                        'name' => 'Nguyễn Thị Huyền A',
                        'email' => 'huyennt@dxmb.vn',
                        'password' => bcrypt('Dxmb2015'),
                        'is_manager' => 1,
                        'department_id' => 15, // Sàn TK
                        'position_id' => 9, // Trưởng nhóm
                        'job_id' => 2, // Kinh doanh
                        'role_id' => 2,
                        'direct_manager_id' => 10, // Nguyễn Văn Văn
                        'created_at' => Util::sz_fCurrentDateTime()
                ]);
        // Id 40
        DB::table('users')->insert(
                [
                        'code' => 'MB83',
                        'name' => 'Nguyễn Xuân Hòa',
                        'email' => 'hoanx@dxmb.vn',
                        'password' => bcrypt('Dxmb2015'),
                        'is_manager' => 1,
                        'department_id' => 15, // Sàn TK
                        'position_id' => 9, // Trưởng nhóm
                        'job_id' => 2, // Kinh doanh
                        'role_id' => 2,
                        'direct_manager_id' => 10, // Nguyễn Văn Văn
                        'created_at' => Util::sz_fCurrentDateTime()
                ]);
        // Id 41
        DB::table('users')->insert(
                [
                        'code' => 'MB97',
                        'name' => 'Nguyễn Thị Dung B',
                        'email' => 'dungnt2@dxmb.vn',
                        'password' => bcrypt('Dxmb2015'),
                        'is_manager' => 1,
                        'department_id' => 15, // Sàn TK
                        'position_id' => 9, // Trưởng nhóm
                        'job_id' => 2, // Kinh doanh
                        'role_id' => 2,
                        'direct_manager_id' => 10, // Nguyễn Văn Văn
                        'created_at' => Util::sz_fCurrentDateTime()
                ]);
        // Id 42
        DB::table('users')->insert(
                [
                        'code' => 'MB146',
                        'name' => 'Bùi Thị Loan',
                        'email' => 'loanbt@dxmb.vn',
                        'password' => bcrypt('Dxmb2015'),
                        'is_manager' => 1,
                        'department_id' => 15, // Sàn TK
                        'position_id' => 9, // Trưởng nhóm
                        'job_id' => 2, // Kinh doanh
                        'role_id' => 2,
                        'direct_manager_id' => 10, // Nguyễn Văn Văn
                        'created_at' => Util::sz_fCurrentDateTime()
                ]);
        // Id 43
        DB::table('users')->insert(
                [
                        'code' => 'MB265',
                        'name' => 'Đỗ Quang Huy',
                        'email' => 'huydq@dxmb.vn',
                        'password' => bcrypt('Dxmb2015'),
                        'is_manager' => 1,
                        'department_id' => 15, // Sàn TK
                        'position_id' => 9, // Trưởng nhóm
                        'job_id' => 2, // Kinh doanh
                        'role_id' => 2,
                        'direct_manager_id' => 10, // Nguyễn Văn Văn
                        'created_at' => Util::sz_fCurrentDateTime()
                ]);
        // Id 44
        DB::table('users')->insert(
                [
                        'code' => 'MB243',
                        'name' => 'Ngô Quang Thắng',
                        'email' => 'thangnq@dxmb.vn',
                        'password' => bcrypt('Dxmb2015'),
                        'is_manager' => 1,
                        'department_id' => 17, // Sàn VH
                        'position_id' => 9, // Trưởng nhóm
                        'job_id' => 2, // Kinh doanh
                        'role_id' => 2,
                        'direct_manager_id' => 9, // Đinh Quang Tuấn
                        'created_at' => Util::sz_fCurrentDateTime()
                ]);
        // Id 45
        DB::table('users')->insert(
                [
                        'code' => 'MB184',
                        'name' => 'Ngô Thị Phương',
                        'email' => 'phuongnt@dxmb.vn',
                        'password' => bcrypt('Dxmb2015'),
                        'is_manager' => 1,
                        'department_id' => 17, // Sàn VH
                        'position_id' => 9, // Trưởng nhóm
                        'job_id' => 2, // Kinh doanh
                        'role_id' => 2,
                        'direct_manager_id' => 9, // Đinh Quang Tuấn
                        'created_at' => Util::sz_fCurrentDateTime()
                ]);
        // Id 46
        DB::table('users')->insert(
                [
                        'code' => 'MB268',
                        'name' => 'Trần Phương Chương',
                        'email' => 'chuongtp@dxmb.vn',
                        'password' => bcrypt('Dxmb2015'),
                        'is_manager' => 1,
                        'department_id' => 17, // Sàn VH
                        'position_id' => 9, // Trưởng nhóm
                        'job_id' => 2, // Kinh doanh
                        'role_id' => 2,
                        'direct_manager_id' => 9, // Đinh Quang Tuấn
                        'created_at' => Util::sz_fCurrentDateTime()
                ]);
        // Id 47
        DB::table('users')->insert(
                [
                        'code' => 'MB506',
                        'name' => 'Nguyễn Thùy Linh',
                        'email' => 'linhnt@dxmb.vn',
                        'password' => bcrypt('Dxmb2015'),
                        'is_manager' => 1,
                        'department_id' => 17, // Sàn VH
                        'position_id' => 9, // Trưởng nhóm
                        'job_id' => 2, // Kinh doanh
                        'role_id' => 2,
                        'direct_manager_id' => 9, // Đinh Quang Tuấn
                        'created_at' => Util::sz_fCurrentDateTime()
                ]);
        // Id 48
        DB::table('users')->insert(
                [
                        'code' => 'MB119',
                        'name' => 'Nguyễn Ngọc Tuyền',
                        'email' => 'tuyennn@dxmb.vn',
                        'password' => bcrypt('Dxmb2015'),
                        'is_manager' => 1,
                        'department_id' => 17, // Sàn VH
                        'position_id' => 9, // Trưởng nhóm
                        'job_id' => 2, // Kinh doanh
                        'role_id' => 2,
                        'direct_manager_id' => 18, // Trần Thị Thùy Dương A
                        'created_at' => Util::sz_fCurrentDateTime()
                ]);
        // Id 49
        DB::table('users')->insert(
                [
                        'code' => 'MB295',
                        'name' => 'Đỗ Thanh Tùng',
                        'email' => 'tungdt1@dxmb.vn',
                        'password' => bcrypt('Dxmb2015'),
                        'is_manager' => 1,
                        'department_id' => 17, // Sàn VH
                        'position_id' => 9, // Trưởng nhóm
                        'job_id' => 2, // Kinh doanh
                        'role_id' => 2,
                        'direct_manager_id' => 18, // Trần Thị Thùy Dương A
                        'created_at' => Util::sz_fCurrentDateTime()
                ]);
    }
}
