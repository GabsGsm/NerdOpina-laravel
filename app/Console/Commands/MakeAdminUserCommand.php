<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class MakeAdminUserCommand extends Command
{
    protected $signature = 'make:admin {email} {name?}';

    protected $description = 'Cria um novo usuário admin ou promove um usuário existente';

    public function handle(): int
    {
        $email = $this->argument('email');

        $user = User::where('email', $email)->first();

        if ($user) {
            $user->update(['is_admin' => true]);
            $this->info("✓ Usuário \"{$user->name}\" ({$email}) promovido a admin.");

            return self::SUCCESS;
        }

        $name = $this->argument('name')
            ?? $this->ask('Nome do usuário');

        $password = $this->secret('Senha (Enter para gerar automaticamente)');

        $generated = false;
        if (! $password) {
            $password = Str::random(16);
            $generated = true;
        }

        User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
            'is_admin' => true,
        ]);

        $this->info("✓ Admin criado: \"{$name}\" ({$email})");

        if ($generated) {
            $this->newLine();
            $this->warn("  Senha gerada: {$password}");
            $this->warn('  Guarde esta senha — ela não será exibida novamente.');
        }

        return self::SUCCESS;
    }
}
