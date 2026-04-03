<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        \App\Models\Organization::class => \App\Policies\OrganizationPolicy::class,
        \App\Models\Ticket::class => \App\Policies\TicketPolicy::class,
        \App\Models\Comment::class => \App\Policies\CommentPolicy::class,
        \App\Models\ClientProfile::class => \App\Policies\ClientProfilePolicy::class,
        \App\Models\TicketStatus::class => \App\Policies\TicketStatusPolicy::class,
        \App\Models\TicketPriority::class => \App\Policies\TicketPriorityPolicy::class,
        \App\Models\TicketStatusHistory::class => \App\Policies\TicketStatusHistoryPolicy::class,
        \App\Models\CommonTable::class => \App\Policies\CommonTablePolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();
    }
}
