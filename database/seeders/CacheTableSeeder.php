<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CacheTableSeeder extends Seeder
{
    public function run(): void
    {
        $now = now()->timestamp;

        DB::table('cache')->updateOrInsert(
            ['key' => 'sample:welcome_message'],
            [
                'value' => json_encode(['message' => 'Hello from cache seeder']),
                'expiration' => $now + 3600,
            ]
        );
    }
}

