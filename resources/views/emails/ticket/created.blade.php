<x-mail::message>
# Ticket Created

Hello {{ $ticket->client->name }},

Your ticket **#{{ $ticket->id }}** titled "**{{ $ticket->title }}**" has been created successfully.

<x-mail::button :url="url('/tickets/'.$ticket->id)">
View Ticket
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>