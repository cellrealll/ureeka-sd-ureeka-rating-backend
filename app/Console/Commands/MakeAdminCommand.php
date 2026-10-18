<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class MakeAdminCommand extends Command
{
    protected $signature = 'user:make-admin {email}';

    protected $description =
        'Memberikan role admin kepada user berdasarkan email';

    public function handle(): int
    {
        $email = $this->argument('email');

        $user = User::where('email', $email)->first();

        if (!$user) {
            $this->error(
                'User tidak ditemukan. Minta user login terlebih dahulu.'
            );

            return self::FAILURE;
        }

        $user->role = 'admin';
        $user->save();

        $this->info(
            "User {$user->email} sekarang memiliki role admin."
        );

        return self::SUCCESS;
    }
}