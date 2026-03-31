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
            'description' => 'Sample job from seeder',
            'company' => 'Sample company',
            'category' => 'Sample Category',
            'location' => 'Sample location',
            'status' => 'open',
            'created_at' => now()->toDateTimeString(),
        ]);
    }
}

