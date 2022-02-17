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
            'ou_id' => 1,
            'institution_id' => 2,
            'email' => 'willalbertopineda@gmail.com',
            'password' => Hash::make('12345678'),
            'email_verified_at' => now(),
        ]);
        $admin->assignRole($roleAdmin);

        $admin2 = User::create([
            'id' => 2,
            'user_name' => 'gtzec',
            'name' => 'Giovanni Tzec',
            'job_title' => 'Técnico',   
            'phone' => '7284-2854',
            'ou_id' => 1,
            'institution_id' => 2,
            'email' => 'giovanni.tzec@gmail.com',
            'password' => Hash::make('12345678'),
            'email_verified_at' => now(),
         ]);
        $admin2->assignRole($roleAdmin);

        // $user = User::create([
        //     'id' => 2,
        //     'name' => 'Leonel',
        //     'dui' => '12345678-2',
        //     'email' => 'lopezleonel191@gmail.com',
        //     'password' => Hash::make('Leonel23'),
        //     'name' => 'Leonel',
        //     // 'place_id' => 1,
        //     'email_verified_at' => now(),
        // ]);
        // $user->assignRole($roleUser);

    }
}
