<?php
use Illuminate\Database\Seeder;
use App\Util;

class GroupsTableSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run ()
    {
        // Id 1
        DB::table('groups')->insert(
                [
                        'name' => 'Nhóm KD 1A',
                        'department_id' => 14, // Sàn CG
                        'created_at' => Util::sz_fCurrentDateTime()
                ]);
        // Id 2
        DB::table('groups')->insert(
                [
                        'name' => 'Nhóm KD 1B',
                        'department_id' => 14, // Sàn CG
                        'created_at' => Util::sz_fCurrentDateTime()
                ]);
        // Id 3
        DB::table('groups')->insert(
                [
                        'name' => 'Nhóm KD 1C',
                        'department_id' => 14, // Sàn CG
                        'created_at' => Util::sz_fCurrentDateTime()
                ]);
        // Id 4
        DB::table('groups')->insert(
                [
                        'name' => 'Nhóm KD 1D',
                        'department_id' => 14, // Sàn CG
                        'created_at' => Util::sz_fCurrentDateTime()
                ]);
        // Id 5
        DB::table('groups')->insert(
                [
                        'name' => 'Nhóm KD 1E',
                        'department_id' => 14, // Sàn CG
                        'created_at' => Util::sz_fCurrentDateTime()
                ]);
        // Id 6
        DB::table('groups')->insert(
                [
                        'name' => 'Nhóm KD 1F',
                        'department_id' => 14, // Sàn CG
                        'created_at' => Util::sz_fCurrentDateTime()
                ]);
        // Id 7
        DB::table('groups')->insert(
                [
                        'name' => 'Nhóm KD 2A',
                        'department_id' => 16, // Sàn HBT
                        'created_at' => Util::sz_fCurrentDateTime()
                ]);
        // Id 8
        DB::table('groups')->insert(
                [
                        'name' => 'Nhóm KD 2B',
                        'department_id' => 16, // Sàn HBT
                        'created_at' => Util::sz_fCurrentDateTime()
                ]);
        // Id 9
        DB::table('groups')->insert(
                [
                        'name' => 'Nhóm KD 2C',
                        'department_id' => 16, // Sàn HBT
                        'created_at' => Util::sz_fCurrentDateTime()
                ]);
        // Id 10
        DB::table('groups')->insert(
                [
                        'name' => 'Nhóm KD 2D',
                        'department_id' => 16, // Sàn HBT
                        'created_at' => Util::sz_fCurrentDateTime()
                ]);
        // Id 11
        DB::table('groups')->insert(
                [
                        'name' => 'Nhóm KD 2E',
                        'department_id' => 16, // Sàn HBT
                        'created_at' => Util::sz_fCurrentDateTime()
                ]);
        // Id 12
        DB::table('groups')->insert(
                [
                        'name' => 'Nhóm KD 2F',
                        'department_id' => 16, // Sàn HBT
                        'created_at' => Util::sz_fCurrentDateTime()
                ]);
        // Id 13
        DB::table('groups')->insert(
                [
                        'name' => 'Nhóm KD 3A',
                        'department_id' => 17, // Sàn VH
                        'created_at' => Util::sz_fCurrentDateTime()
                ]);
        // Id 14
        DB::table('groups')->insert(
                [
                        'name' => 'Nhóm KD 3B',
                        'department_id' => 17, // Sàn VH
                        'created_at' => Util::sz_fCurrentDateTime()
                ]);
        // Id 15
        DB::table('groups')->insert(
                [
                        'name' => 'Nhóm KD 3C',
                        'department_id' => 17, // Sàn VH
                        'created_at' => Util::sz_fCurrentDateTime()
                ]);
        // Id 16
        DB::table('groups')->insert(
                [
                        'name' => 'Nhóm KD 3D',
                        'department_id' => 17, // Sàn VH
                        'created_at' => Util::sz_fCurrentDateTime()
                ]);
        // Id 17
        DB::table('groups')->insert(
                [
                        'name' => 'Nhóm KD 3E',
                        'department_id' => 17, // Sàn VH
                        'created_at' => Util::sz_fCurrentDateTime()
                ]);
        // Id 18
        DB::table('groups')->insert(
                [
                        'name' => 'Nhóm KD 3F',
                        'department_id' => 17, // Sàn VH
                        'created_at' => Util::sz_fCurrentDateTime()
                ]);
        // Id 19
        DB::table('groups')->insert(
                [
                        'name' => 'Nhóm KD 4A',
                        'department_id' => 13, // Sàn HS
                        'created_at' => Util::sz_fCurrentDateTime()
                ]);
        // Id 20
        DB::table('groups')->insert(
                [
                        'name' => 'Nhóm KD 4B',
                        'department_id' => 13, // Sàn HS
                        'created_at' => Util::sz_fCurrentDateTime()
                ]);
        // Id 21
        DB::table('groups')->insert(
                [
                        'name' => 'Nhóm KD 4C',
                        'department_id' => 13, // Sàn HS
                        'created_at' => Util::sz_fCurrentDateTime()
                ]);
        // Id 22
        DB::table('groups')->insert(
                [
                        'name' => 'Nhóm KD 4D',
                        'department_id' => 13, // Sàn HS
                        'created_at' => Util::sz_fCurrentDateTime()
                ]);
        // Id 23
        DB::table('groups')->insert(
                [
                        'name' => 'Nhóm KD 4E',
                        'department_id' => 13, // Sàn HS
                        'created_at' => Util::sz_fCurrentDateTime()
                ]);
        // Id 24
        DB::table('groups')->insert(
                [
                        'name' => 'Nhóm KD 4F',
                        'department_id' => 13, // Sàn HS
                        'created_at' => Util::sz_fCurrentDateTime()
                ]);
        // Id 25
        DB::table('groups')->insert(
                [
                        'name' => 'Nhóm KD 5A',
                        'department_id' => 15, // Sàn TK
                        'created_at' => Util::sz_fCurrentDateTime()
                ]);
        // Id 26
        DB::table('groups')->insert(
                [
                        'name' => 'Nhóm KD 5B',
                        'department_id' => 15, // Sàn TK
                        'created_at' => Util::sz_fCurrentDateTime()
                ]);
        // Id 27
        DB::table('groups')->insert(
                [
                        'name' => 'Nhóm KD 5C',
                        'department_id' => 15, // Sàn TK
                        'created_at' => Util::sz_fCurrentDateTime()
                ]);
        // Id 28
        DB::table('groups')->insert(
                [
                        'name' => 'Nhóm KD 5D',
                        'department_id' => 15, // Sàn TK
                        'created_at' => Util::sz_fCurrentDateTime()
                ]);
        // Id 29
        DB::table('groups')->insert(
                [
                        'name' => 'Nhóm KD 5E',
                        'department_id' => 15, // Sàn TK
                        'created_at' => Util::sz_fCurrentDateTime()
                ]);
        // Id 30
        DB::table('groups')->insert(
                [
                        'name' => 'Nhóm KD 5F',
                        'department_id' => 15, // Sàn TK
                        'created_at' => Util::sz_fCurrentDateTime()
                ]);
        // Id 31
        DB::table('groups')->insert(
                [
                        'name' => 'Nhóm KD 6A',
                        'department_id' => 18, // Sàn QN
                        'created_at' => Util::sz_fCurrentDateTime()
                ]);
        // Id 32
        DB::table('groups')->insert(
                [
                        'name' => 'Nhóm KD 6B',
                        'department_id' => 18, // Sàn QN
                        'created_at' => Util::sz_fCurrentDateTime()
                ]);
        // Id 33
        DB::table('groups')->insert(
                [
                        'name' => 'Nhóm KD 6C',
                        'department_id' => 18, // Sàn QN
                        'created_at' => Util::sz_fCurrentDateTime()
                ]);
        // Id 34
        DB::table('groups')->insert(
                [
                        'name' => 'Nhóm KD 6D',
                        'department_id' => 18, // Sàn QN
                        'created_at' => Util::sz_fCurrentDateTime()
                ]);
        // Id 34
        DB::table('groups')->insert(
                [
                        'name' => 'Nhóm KD 6E',
                        'department_id' => 18, // Sàn QN
                        'created_at' => Util::sz_fCurrentDateTime()
                ]);
        // Id 35
        DB::table('groups')->insert(
                [
                        'name' => 'Nhóm KD 6F',
                        'department_id' => 18, // Sàn QN
                        'created_at' => Util::sz_fCurrentDateTime()
                ]);
        // Id 36
        DB::table('groups')->insert(
                [
                        'name' => 'Nhóm KD 7A',
                        'department_id' => 11, // P.Cho thuê
                        'created_at' => Util::sz_fCurrentDateTime()
                ]);
        // Id 37
        DB::table('groups')->insert(
                [
                        'name' => 'Nhóm KD 7B',
                        'department_id' => 11, // P.Cho thuê
                        'created_at' => Util::sz_fCurrentDateTime()
                ]);
        // Id 38
        DB::table('groups')->insert(
                [
                        'name' => 'Nhóm KD 7C',
                        'department_id' => 11, // P.Cho thuê
                        'created_at' => Util::sz_fCurrentDateTime()
                ]);
        // Id 39
        DB::table('groups')->insert(
                [
                        'name' => 'Nhóm KD 7D',
                        'department_id' => 11, // P.Cho thuê
                        'created_at' => Util::sz_fCurrentDateTime()
                ]);
        // Id 40
        DB::table('groups')->insert(
                [
                        'name' => 'Nhóm KD 7E',
                        'department_id' => 11, // P.Cho thuê
                        'created_at' => Util::sz_fCurrentDateTime()
                ]);
        // Id 41
        DB::table('groups')->insert(
                [
                        'name' => 'Nhóm KD 7F',
                        'department_id' => 11, // P.Cho thuê
                        'created_at' => Util::sz_fCurrentDateTime()
                ]);
    }
}
