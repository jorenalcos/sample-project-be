<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PasswordResetTokensTableSeeder extends Seeder
{
    public function run(): void
    {
        $emails = DB::table('users')->limit(3)->pluck('email');

        foreach ($emails as $email) {
            DB::table('password_reset_tokens')->updateOrInsert(
                ['email' => $email],
                [
                    'token' => Str::random(64),
                    'created_at' => now(),
                ]
            );
        }
    }
}

