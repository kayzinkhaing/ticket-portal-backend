<?php

return [
    'models' => [

        /*
        |--------------------------------------------------------------------------
        | User
        |--------------------------------------------------------------------------
        */
        App\Models\User::class => [
            'roles' => ['label' => 'name'],
            'media' => ['label' => 'url'],
            'clientProfiles' => ['label' => 'id'],

            'eager_load' => [
                'roles:id,name',
                'roles.users:id,first_name,middle_name,last_name,email',       // if needed pivot back
                'media:id,mediable_id,mediable_type,url',
                'clientProfiles:id,user_id,organization_id',
                'clientProfiles.organization:id,name',
                'clientProfiles.tickets:id,client_profile_id,title,status_id,priority_id',
            ],
        ],

        /*
        |--------------------------------------------------------------------------
        | Role
        |--------------------------------------------------------------------------
        */
        App\Models\Role::class => [
            'users' => ['label' => 'name'],

            'eager_load' => [
                'users:id,first_name,middle_name,last_name,email',
            ],
        ],

        /*
        |--------------------------------------------------------------------------
        | Pivot RoleUser / PermissionRole
        |--------------------------------------------------------------------------
        */
        // Laravel usually doesn't have a dedicated model for pivot, but if you do:
        App\Models\Pivots\RoleUser::class => [
            'user' => ['label' => 'name'],
            'role' => ['label' => 'name'],

            'eager_load' => [
                'user:id,first_name,middle_name,last_name,email',
                'role:id,name',
            ],
        ],

        /*
        |--------------------------------------------------------------------------
        | Organization
        |--------------------------------------------------------------------------
        */
        App\Models\Organization::class => [
            'clientProfiles' => ['label' => 'user.name'],

            'eager_load' => [
                'clientProfiles:id,user_id,organization_id',
                'clientProfiles.user:id,first_name,middle_name,last_name,email'
            ],
        ],

        /*
        |--------------------------------------------------------------------------
        | ClientProfile
        |--------------------------------------------------------------------------
        */
        App\Models\ClientProfile::class => [
            'user'          => ['label' => 'name'],
            'organization'  => ['label' => 'name'],
            'tickets'       => ['label' => 'title'],

            'eager_load' => [
                'user:id,first_name,middle_name,last_name,email',
                'organization:id,name',
                'tickets:id,client_profile_id,title,status_id,priority_id',
                'tickets.status:id,name',
                'tickets.priority:id,name',
                // 'tickets.media:id,mediable_id,mediable_type,url',
                'tickets.comments:id,ticket_id,user_id,content,is_internal',
                'tickets.comments.user:id,name,email',
            ],
        ],

        /*
        |--------------------------------------------------------------------------
        | Ticket
        |--------------------------------------------------------------------------
        */
        App\Models\Ticket::class => [
            'clientProfile' => ['label' => 'user.name'],
            'creator'       => ['label' => 'name'],
            'assignee'      => ['label' => 'name'],
            'assignedBy'    => ['label' => 'name'],
            'status'        => ['label' => 'name'],
            'priority'      => ['label' => 'name'],
            'comments'      => ['label' => 'content'],
            'media'         => ['label' => 'url'],

            'eager_load' => [
                'clientProfile:id,user_id,organization_id',
                'clientProfile.user:id,first_name,middle_name,last_name,email',
                'creator:id,first_name,middle_name,last_name,email',
                'assignee:id,first_name,middle_name,last_name,email',
                'assignedBy:id,first_name,middle_name,last_name,email',
                'status:id,name',
                'priority:id,name',
                'comments:id,ticket_id,user_id,content,is_internal',
                'comments.user:id,first_name,middle_name,last_name,email',
                'media:id,mediable_id,mediable_type,url',
                'statusHistories:id,ticket_id,old_status_id,new_status_id,changed_by,created_at',
                'statusHistories.oldStatus:id,name',
                'statusHistories.newStatus:id,name',
                'statusHistories.changedBy:id,first_name,middle_name,last_name,email',
            ],
        ],

        /*
        |--------------------------------------------------------------------------
        | TicketStatus
        |--------------------------------------------------------------------------
        */
        App\Models\TicketStatus::class => [
            'tickets' => ['label' => 'title'],

            'eager_load' => [
                'tickets:id,status_id,title,client_profile_id',
                'tickets.clientProfile:id,user_id,organization_id',
            ],
        ],

        /*
        |--------------------------------------------------------------------------
        | TicketPriority
        |--------------------------------------------------------------------------
        */
        App\Models\TicketPriority::class => [
            'tickets' => ['label' => 'title'],

            'eager_load' => [
                'tickets:id,priority_id,title,client_profile_id',
                'tickets.clientProfile:id,user_id,organization_id',
            ],
        ],

        /*
        |--------------------------------------------------------------------------
        | Comment
        |--------------------------------------------------------------------------
        */
        App\Models\Comment::class => [
            'ticket' => ['label' => 'title'],
            'user'   => ['label' => 'name'],

            'eager_load' => [
                'ticket:id,title,client_profile_id,status_id,priority_id',
                'user:id,first_name,middle_name,last_name,email',
                'media:id,mediable_id,mediable_type,url',
            ],
        ],

        /*
        |--------------------------------------------------------------------------
        | TicketStatusHistory
        |--------------------------------------------------------------------------
        */
        App\Models\TicketStatusHistory::class => [
            'ticket'    => ['label' => 'title'],
            'oldStatus' => ['label' => 'name'],
            'newStatus' => ['label' => 'name'],
            'changedBy' => ['label' => 'name'],

            'eager_load' => [
                'ticket:id,title,status_id,priority_id,client_profile_id',
                'oldStatus:id,name',
                'newStatus:id,name',
                'changedBy:id,first_name,middle_name,last_name,email',
            ],
        ],

        /*
        |--------------------------------------------------------------------------
        | Media
        |--------------------------------------------------------------------------
        */
        App\Models\Media::class => [
            'mediable' => ['label' => 'id'],

            'eager_load' => [
                'mediable',
            ],
        ],

        /*
        |--------------------------------------------------------------------------
        | CommonTable
        |--------------------------------------------------------------------------
        */
        App\Models\CommonTable::class => [
            'eager_load' => [],
        ],

    ],
];
