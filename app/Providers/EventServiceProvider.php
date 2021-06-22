<?php

namespace App\Providers;

use App\Events\ACL\RoleAssignmentEvent;
use App\Events\ACL\RoleUpdateEvent;
use App\Events\AuditLog\AuditHandlerEvent;
use App\Events\Base\CreatedContentEvent;
use App\Events\Base\DeletedContentEvent;
use App\Events\Base\UpdatedContentEvent;
use App\Listeners\ACL\RoleAssignmentListener;
use App\Listeners\ACL\RoleUpdateListener;
use App\Listeners\AuditLog\AuditHandlerListener;
use App\Listeners\CreatedContentListener;
use App\Listeners\DeletedContentListener;
use App\Listeners\UpdatedContentListener;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        AuditHandlerEvent::class=>[
            AuditHandlerListener::class
        ],
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        RoleUpdateEvent::class => [
            RoleUpdateListener::class
        ],
        RoleAssignmentEvent::class => [
            RoleAssignmentListener::class,
        ],
        CreatedContentEvent::class => [
            CreatedContentListener::class
        ],
        UpdatedContentEvent::class => [
            UpdatedContentListener::class
        ],
        DeletedContentEvent::class => [
            DeletedContentListener::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
