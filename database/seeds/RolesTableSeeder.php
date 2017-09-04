<?php

use Illuminate\Database\Seeder;
use App\Util;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //user
        DB::table('roles')->insert([
            'rolegroup_id' => 1,
            'controller' => 'UserController',
            'action'=>'index',
            'read' => 1,
            'create' => 1,
            'update' => 1,
            'delete' => 1,
            'created_at' => Util::sz_fCurrentDateTime(),
        ]);
        DB::table('roles')->insert([
            'rolegroup_id' => 1,
            'controller' => 'UserController',
            'action'=>'insert',
            'read' => 1,
            'create' => 1,
            'update' => 1,
            'delete' => 1,
            'created_at' => Util::sz_fCurrentDateTime(),
        ]);
        
        //leave request
        DB::table('roles')->insert([
            'rolegroup_id' => 1,
            'controller' => 'LeaveRequestController',
            'action'=>'Report',
            'read' => 1,
            'create' => 1,
            'update' => 1,
            'delete' => 1,
            'created_at' => Util::sz_fCurrentDateTime(),
        ]);
        DB::table('roles')->insert([
            'rolegroup_id' => 1,
            'controller' => 'LeaveRequestController',
            'action'=>'ListLeaveRequest',
            'read' => 1,
            'create' => 1,
            'update' => 1,
            'delete' => 1,
            'created_at' => Util::sz_fCurrentDateTime(),
        ]);
        DB::table('roles')->insert([
            'rolegroup_id' => 1,
            'controller' => 'LeaveRequestController',
            'action'=>'LeaveRequestManagement',
            'read' => 1,
            'create' => 1,
            'update' => 1,
            'delete' => 1,
            'created_at' => Util::sz_fCurrentDateTime(),
        ]);
        DB::table('roles')->insert([
            'rolegroup_id' => 1,
            'controller' => 'LeaveRequestController',
            'action'=>'HrmManagement',
            'read' => 1,
            'create' => 1,
            'update' => 1,
            'delete' => 1,
            'created_at' => Util::sz_fCurrentDateTime(),
        ]);
        DB::table('roles')->insert([
            'rolegroup_id' => 1,
            'controller' => 'LeaveRequestController',
            'action'=>'LeaveRequest',
            'read' => 1,
            'create' => 1,
            'update' => 1,
            'delete' => 1,
            'created_at' => Util::sz_fCurrentDateTime(),
        ]);
        //end leave request
        
        //Department
        DB::table('roles')->insert([
            'rolegroup_id' => 1,
            'controller' => 'DepartmentController',
            'action'=>'ListDepartMent',
            'read' => 1,
            'create' => 1,
            'update' => 1,
            'delete' => 1,
            'created_at' => Util::sz_fCurrentDateTime(),
        ]);
        DB::table('roles')->insert([
            'rolegroup_id' => 1,
            'controller' => 'DepartmentController',
            'action'=>'editDepartment',
            'read' => 1,
            'create' => 1,
            'update' => 1,
            'delete' => 1,
            'created_at' => Util::sz_fCurrentDateTime(),
        ]);
        //job
        DB::table('roles')->insert([
            'rolegroup_id' => 1,
            'controller' => 'JobsController',
            'action'=>'index',
            'read' => 1,
            'create' => 1,
            'update' => 1,
            'delete' => 1,
            'created_at' => Util::sz_fCurrentDateTime(),
        ]);
        DB::table('roles')->insert([
            'rolegroup_id' => 1,
            'controller' => 'JobsController',
            'action'=>'modify',
            'read' => 1,
            'create' => 1,
            'update' => 1,
            'delete' => 1,
            'created_at' => Util::sz_fCurrentDateTime(),
        ]);
        
        //admin
        DB::table('roles')->insert([
            'rolegroup_id' => 2,
            'controller' => 'LeaveRequestController',
            'action'=>'Report',
            'read' => 1,
            'create' => 1,
            'update' => 1,
            'delete' => 1,
            'created_at' => Util::sz_fCurrentDateTime(),
        ]);
        DB::table('roles')->insert([
            'rolegroup_id' => 2,
            'controller' => 'LeaveRequestController',
            'action'=>'ListLeaveRequest',
            'read' => 1,
            'create' => 1,
            'update' => 1,
            'delete' => 1,
            'created_at' => Util::sz_fCurrentDateTime(),
        ]);
        DB::table('roles')->insert([
            'rolegroup_id' => 2,
            'controller' => 'LeaveRequestController',
            'action'=>'LeaveRequestManagement',
            'read' => 1,
            'create' => 1,
            'update' => 1,
            'delete' => 1,
            'created_at' => Util::sz_fCurrentDateTime(),
        ]);
        DB::table('roles')->insert([
            'rolegroup_id' => 2,
            'controller' => 'LeaveRequestController',
            'action'=>'LeaveRequest',
            'read' => 1,
            'create' => 1,
            'update' => 1,
            'delete' => 1,
            'created_at' => Util::sz_fCurrentDateTime(),
        ]);
        //staff
        
        DB::table('roles')->insert([
            'rolegroup_id' => 3,
            'controller' => 'LeaveRequestController',
            'action'=>'ListLeaveRequest',
            'read' => 1,
            'create' => 1,
            'update' => 1,
            'delete' => 1,
            'created_at' => Util::sz_fCurrentDateTime(),
        ]);
        
        DB::table('roles')->insert([
            'rolegroup_id' => 3,
            'controller' => 'LeaveRequestController',
            'action'=>'LeaveRequest',
            'read' => 1,
            'create' => 1,
            'update' => 1,
            'delete' => 1,
            'created_at' => Util::sz_fCurrentDateTime(),
        ]);
        
    }
}
