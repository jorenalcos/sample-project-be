<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SessionsTableSeeder extends Seeder
{
    public function run(): void
    {
        $userIds = DB::table('users')->limit(3)->pluck('id');

        foreach ($userIds as $userId) {
            DB::table('sessions')->updateOrInsert(
                ['id' => (string) Str::uuid()],
                [
                    'user_id' => $userId,
                    'ip_address' => '127.0.0.1',
                    'user_agent' => 'Seeder/Local',
                    'payload' => base64_encode(json_encode(['seeded' => true, 'user_id' => $userId])),
                    'last_activity' => now()->timestamp,
                ]
            );
        }
    }
}

