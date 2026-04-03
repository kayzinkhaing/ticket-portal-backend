<x-mail::message>
# Ticket Overdue

Hello {{ $ticket->client?->name ?? 'Customer' }},

Your ticket **#{{ $ticket->id }}** titled "**{{ $ticket->title }}**" is now overdue. Please check and resolve it as soon as possible.

<x-mail::button :url="url('/tickets/'.$ticket->id)">
View Ticket
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>