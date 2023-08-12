<?php

namespace App\Console\Commands;

use App\Mail\ReminderMail;
use App\Models\Event;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use function Laravel\Prompts\search;

class SendReminderCommand extends Command
{
    protected $signature = 'send:reminder';

    protected $description = 'Command description';

    public function handle()
    {
        // Seleccionamos el evento del cual vamos a enviar recordatorios
        $eventId = search(
            label: '¿Cuál es el evento que quiere enviar un recordatorio?',
            options: function (string $value) {
                return strlen($value) > 0
                    ? Event::where('name', 'like', "%{$value}%")
                        ->pluck('name', 'id')
                        ->toArray()
                    : [];
            });

        // Buscamos el modelo del evento por su ID
        $event = Event::find($eventId);

        // Recorremos los asistentes del evento y enviamos un mail de recordatorio
        // por cada uno
        // La función withProgressBar hace que se vea una barra de progreso por cada
        // usuario para una mejor experiencia
        $this->withProgressBar($event->users, function (User $user) use ($event) {
            Mail::to($user->email)->send(new ReminderMail($event));
        });

        // Mostramos los resultados
        $this->newLine();
        $this->info('Se enviaron los recordatorios a los siguientes asistentes');
        $this->table(
            ['Name', 'Email'],
            $event->users->map(fn(User $user) => $user->only(['name', 'email']))
                ->toArray()
        );

    }
}
