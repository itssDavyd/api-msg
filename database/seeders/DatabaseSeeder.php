<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        /*\App\Models\User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@gmail.com',
            'password' => bcrypt(123456),
        ]);*/

        \App\Models\Post::factory()->create([
            'title' => 'POST del administrador',
            'user_id' => 2,
            'description' => 'ando probando el post',
            'create_at' => date('Y-m-d'),
            'update_at' => date('Y-m-d'),
        ]);
    }
}
