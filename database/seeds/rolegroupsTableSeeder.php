<?php

use Illuminate\Database\Seeder;
use App\Util;

class rolegroupsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('rolegroups')->insert(
                [
                        'name' => 'superadmin',
                        'status' => 1,
                        'created_at' => Util::sz_fCurrentDateTime()
                ]);
        // Id 2
        DB::table('rolegroups')->insert(
                [
                        'name' => 'admin',
                        'status' => 1,
                        'created_at' => Util::sz_fCurrentDateTime()
                ]);
        DB::table('rolegroups')->insert(
                [
                        'name' => 'staff',
                        'status' => 1,
                        'created_at' => Util::sz_fCurrentDateTime()
                ]);
        DB::table('rolegroups')->insert(
                [
                        'name' => 'reporter',
                        'status' => 1,
                        'created_at' => Util::sz_fCurrentDateTime()
                ]);
        
    }
}
