<?php

use Illuminate\Database\Seeder;
use App\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $manager = User::create([
            'name' => 'Manager',
            'email' => 'manager@dshop.id',
            'password' => bcrypt('12345678')
        ]);
        $manager->assignRole('manager');

        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@dshop.id',
            'password' => bcrypt('12345678')
        ]);
        $admin->assignRole('admin');

        $kasir = User::create([
            'name' => 'Kasir',
            'email' => 'kasir@dshop.id',
            'password' => bcrypt('12345678')
        ]);
        $kasir->assignRole('kasir');
    }
}
