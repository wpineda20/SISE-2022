<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roleAdmin = Role::findOrFail(1);
        $roleUser = Role::findOrFail(2);

        $admin = User::create([
            'id' => 1,
            'user_name' => 'wpineda20',
            'name' => 'William Alberto Pineda Tovar',
            'job_title' => 'Técnico',
            'phone' => '7284-2854',
            // 'ou_id' => 1,
            // 'institution_id' => 2,
            'email' => 'willalbertopineda@gmail.com',
            'password' => Hash::make('12345678'),
            'email_verified_at' => now(),
        ]);
        $admin->assignRole($roleAdmin);

        $admin = User::create([
            'id' => 2,
            'user_name' => 'GiovanniTzec',
            'name' => 'Giovanni Ariel Tzec',
            'job_title' => 'Ingeniero',
            'phone' => '7941-9348',
            // 'ou_id' => 1,
            // 'institution_id' => 2,
            'email' => 'giovanni.tzec@gmail.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        $admin->assignRole($roleAdmin);

        // $admin = User::create([
        //     'id' => 3,
        //     'name' => 'Tzec',
        //     'dui' => '12345678-1',
        //     'email' => 'giovanni.tzec@gmail.com',
        //     'password' => Hash::make('12345678'),
        //     'name' => 'Tzec',
        //     'email_verified_at' => now(),
        // ]);
        // $admin->assignRole($roleAdmin);
    }
}
