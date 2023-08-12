@component('mail::message')
# Introducción

Recordatorio del Evento "{{ $event->name }}"

@component('mail::button', ['url' => ''])
Ver Evento
@endcomponent

Gracias,<br>
{{ config('app.name') }}
@endcomponent
