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
        $eventId = search(
            label: '¿Cuál es el evento que quiere enviar un recordatorio?',
            options: function (string $value) {
                return strlen($value) > 0
                    ? Event::where('name', 'like', "%{$value}%")
                        ->pluck('name', 'id')
                        ->toArray()
                    : [];
            });

        $event = Event::find($eventId);

        $this->withProgressBar($event->users, function (User $user) use ($event) {
            Mail::to($user->email)->send(new ReminderMail($event));
        });

        $this->newLine();
        $this->info('Se enviaron los recordatorios a los siguientes asistentes');
        $this->table(
            ['Name', 'Email'],
            $event->users->map(fn(User $user) => $user->only(['name', 'email']))
                ->toArray()
        );

    }
}
