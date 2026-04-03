<x-mail::message>
# Ticket Due Soon

Hello {{ $ticket->client?->name ?? 'Customer' }},

Your ticket **#{{ $ticket->id }}** titled "**{{ $ticket->title }}**" is due soon. Please take action before the deadline.

<x-mail::button :url="url('/tickets/'.$ticket->id)">
View Ticket
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>