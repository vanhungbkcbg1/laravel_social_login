<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /** @var \Spatie\Permission\Models\Role $adminRole */
        $adminRole=\Spatie\Permission\Models\Role::create(['name'=>"admin"]);
//        $adminRole->givePermissionTo('');
        $allPermission =Permission::all();

        foreach ($allPermission as $permission) {
            $adminRole->givePermissionTo($permission->name);
        }
    }
}
