<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JobsTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('jobs')->insert([
            'title' => 'Sample Title',
            'company_id' => 1,
            'description' => 'Sample job from seeder',
            'location' => 'Sample location',
            'status' => 'open',
            'created_at' => now()->toDateTimeString(),
        ]);
    }
}

