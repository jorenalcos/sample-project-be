<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JobsTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('jobs')->insert([
            'description' => 'Sample job from seeder',
            'created_at' => now()->timestamp,
        ]);
    }
}

