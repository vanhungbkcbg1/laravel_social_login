<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $adminRole = \Spatie\Permission\Models\Role::findByName('admin');
        /** @var \App\User $user */
        $user=\App\User::create([
            'name'=>"admin",
            'email'=>'admin@gmail.com',
            'password'=>Hash::make('admin')
        ]);
        $user->assignRole($adminRole);
    }
}
