<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
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
        DB::table('users')->insert([
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('1234'),
        ]);
        
        DB::table('users')->insert([
            'name' => 'jadd',
            'email' => 'jadd@gmail.com',
            'password' => Hash::make('1234'),
        ]);

        $role = Role::find(1);
        $user = User::find(1);
        $user->assignRole($role);
    }
}
