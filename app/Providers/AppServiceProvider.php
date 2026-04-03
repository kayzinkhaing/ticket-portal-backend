<?php

namespace App\Providers;

use App\Contracts\baseInterface;
use App\Contracts\ClientProfileInterface;
use App\Contracts\CommentInterface;
use App\Contracts\CommonTableInterface;
use App\Contracts\messageInterface;
use App\Contracts\OrganizationInterface;
use App\Contracts\roleInterface;
use App\Contracts\UserInterface;
use App\Models\Role;
use App\Observers\RoleObserver;
use App\Repositories\baseRepository;
use App\Repositories\ClientProfileRepository;
use App\Repositories\CommentRepository;
use App\Contracts\TicketInterface;
use App\Contracts\TicketPriorityInterface;
use App\Contracts\TicketStatusInterface;
use App\Contracts\TicketStatusHistoryInterface;
use App\Repositories\CommonTableRepository;
use App\Repositories\messageRepository;
use App\Repositories\OrganizationRepository;
use App\Repositories\permissionRepository;
use App\Repositories\UserRepository;
use App\Repositories\TicketRepository;
use App\Repositories\TicketPriorityRepository;
use App\Repositories\TicketStatusRepository;
use App\Repositories\TicketStatusHistoryRepository;
use App\Services\configFiles;
use App\Services\permissions;
use App\Services\roles;
use Carbon\Carbon;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Passport;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    protected  $permissionService;

    public function register(): void
    {
        $this->app->bind(baseInterface::class, baseRepository::class);
        $this->app->bind(roleInterface::class, roles::class);
        $this->app->bind(UserInterface::class, UserRepository::class);
        $this->app->bind(messageInterface::class, messageRepository::class);
        $this->app->bind(OrganizationInterface::class, OrganizationRepository::class);
        $this->app->bind(ClientProfileInterface::class, ClientProfileRepository::class);
        $this->app->bind(ClientProfileInterface::class, ClientProfileRepository::class);
         $this->app->bind(CommentInterface::class, CommentRepository::class);
        $this->app->bind(CommonTableInterface::class, CommonTableRepository::class);
        $this->app->bind(TicketInterface::class, TicketRepository::class);
        $this->app->bind(TicketPriorityInterface::class, TicketPriorityRepository::class);
        $this->app->bind(TicketStatusInterface::class, TicketStatusRepository::class);
        $this->app->bind(TicketStatusHistoryInterface::class, TicketStatusHistoryRepository::class);

        // Register ConfigFileService to the service container
        $this->app->bind(configFiles::class, function ($app) {
            return new configFiles();
        });
    }

    /**
     * Bootstrap any application services.
     */

    //close start run app
    public function boot(): void
    {
        require_once app_path('Support/helpers.php');
    }
}
