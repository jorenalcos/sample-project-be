<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CacheLocksTableSeeder extends Seeder
{
    public function run(): void
    {
        $now = now()->timestamp;

        DB::table('cache_locks')->updateOrInsert(
            ['key' => 'sample:lock'],
            [
                'owner' => (string) Str::uuid(),
                'expiration' => $now + 60,
            ]
        );
    }
}

