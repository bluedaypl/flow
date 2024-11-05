<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class Status extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = [
            ['name' => 'Przyjęcie na magazyn', 'order' => 1],
            ['name' => 'Rozpoczęcie przyjmowania', 'order' => 2],
            ['name' => 'Rozłożenie na półce', 'order' => 3],
            ['name' => 'Wysłanie faktury', 'order' => 4],
        ];

        foreach ($statuses as $status) {
            \App\Models\Status::firstOrCreate(['name' => $status['name']], ['order' => $status['order']]);
        }
    }
}
