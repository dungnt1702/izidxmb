<?php
use Illuminate\Database\Seeder;
use App\Util;

class JobsTableSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run ()
    {
        
        DB::table('jobs')->insert(
                // Id 1
                [
                        'name' => 'Quản lý',
                        'enable_sunday' => 0,
                        'created_at' => Util::sz_fCurrentDateTime(),
                        
                ]);
                // Id 2
        DB::table('jobs')->insert(
                [
                        'name' => 'Văn phòng',
                        'enable_sunday' => 0,
                        'created_at' => Util::sz_fCurrentDateTime()
                ]);
                //Id 3
        DB::table('jobs')->insert(
                [
                        'name' => 'Kinh doanh',
                        'enable_sunday' => 1,
                        'created_at' => Util::sz_fCurrentDateTime()
                ]);
    }
}
