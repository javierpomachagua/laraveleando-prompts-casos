<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use function Laravel\Prompts\select;
use function Laravel\Prompts\text;

class CreateUserCommand extends Command
{
    protected $signature = 'create:user';

    protected $description = 'Command description';

    public function handle()
    {
        $role = select('¿Cuál será el usuario del rol?', [
            'Administrador', 'Suscriptor', 'Invitado', 'Regular'
        ]);

        $count = text(
            label: '¿Cuántos usuarios se desea?',
            placeholder: 1,
            default: 1,
            validate: fn(string $value) => match (true) {
                !is_numeric($value) || $value <= 0 => 'El valor tiene que ser un número mayor a 0',
                default => null
            }
        );

        $users = User::factory()->count($count)->state([
            'role' => $role
        ])->create()
            ->map(fn(User $user) => $user->only(['email', 'role']))
            ->toArray();

        $this->info('Usuario(s) creado(s)');
        $this->table(
            ['Email', 'Role'],
            $users
        );

    }
}
