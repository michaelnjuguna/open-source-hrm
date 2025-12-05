<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;


class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $employee = Role::firstOrCreate(['name' => 'employee']);
    }
}
