<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        \App\Models\User::updateOrCreate(
            ['email' => 'admin@colivingfounders.com'],
            [
                'name' => 'COFO Admin',
                'password' => 'changeme-now',
            ]
        );

        $this->call(ContentSeeder::class);
    }
}
