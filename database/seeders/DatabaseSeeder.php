<?php

namespace Database\Seeders;

use App\Models\Staff;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Staff::factory(10)->create();
    }
}
