<?php

namespace Database\Seeders;

use App\Models\users;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        users::create([
            'email' => 'neiac21@gmail.com',
            'usu_login' => 'saldanha',
            'usu_senha' => bcrypt('salnei'),
            'usu_status' => 'A',
            'usu_nivel' => 'A',
            'foto' => 'foto.jpg',
            'usu_data_cad' => now(),
            'usu_data_update' => now(),
            'senha' => 'salnei', // Assuming this is a separate field for password reset or something similar
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
