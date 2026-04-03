<x-mail::message>
# Ticket Closed

Hello {{ $ticket->client?->name ?? 'Customer' }},

Your ticket **#{{ $ticket->id }}** titled "**{{ $ticket->title }}**" has been closed.

<x-mail::button :url="url('/tickets/'.$ticket->id)">
View Ticket
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>