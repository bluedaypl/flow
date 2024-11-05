<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Producer;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        \App\Models\User::firstOrCreate(['name' => 'Admin'], [
            'name' => 'Admin',
            'email' => 'admin@localhost.org',
            'password' => bcrypt('admin'),
            'role' => 'admin',
        ]);

       $this->call([
           Status::class,
       ]);

       Producer::factory()
       ->count(30)
       ->create();
    }
}
