<?php

use Illuminate\Database\Seeder;
use App\Util;
class LeaveTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Id 1
        DB::table('leave_types')->insert(
                [
                        'name' => 'Nghỉ ốm',
                        'created_at' => Util::sz_fCurrentDateTime()
                ]);
        // Id 2
        DB::table('leave_types')->insert(
                [
                        'name' => 'Nghỉ việc riêng',
                        'created_at' => Util::sz_fCurrentDateTime()
                ]);
        // Id 3
        DB::table('leave_types')->insert(
                [
                        'name' => 'Nghỉ phép năm',
                        'created_at' => Util::sz_fCurrentDateTime()
                ]);
        // Id 4
        DB::table('leave_types')->insert(
                [
                        'name' => 'Phiếu công tác',
                        'created_at' => Util::sz_fCurrentDateTime()
                ]);
        // Id 4
        DB::table('leave_types')->insert(
                [
                        'name' => 'Nghỉ thai sản',
                        'created_at' => Util::sz_fCurrentDateTime()
                ]);
        
    }
}
