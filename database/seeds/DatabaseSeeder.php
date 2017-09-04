<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(PositionsTableSeeder::class);
        $this->call(RolesTableSeeder::class);
        $this->call(DepartmentsTableSeeder::class);
        $this->call(GroupsTableSeeder::class);
        $this->call(JobsTableSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(rolegroupsTableSeeder::class);
        $this->call(LeaveTypesTableSeeder::class);
    }
}
