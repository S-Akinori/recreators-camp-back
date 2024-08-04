<?php

namespace App\Providers;

use App\Events\MaterialCreated;
use App\Events\MaterialFavorited;
use App\Events\MaterialLiked;
use App\Events\UserFollowed;
use App\Listeners\SendFavoriteNotification;
use App\Listeners\SendFirstLoginNotification;
use App\Listeners\SendFollowNotification;
use App\Listeners\SendLikeNotification;
use App\Listeners\SendNewMaterialNotification;
use App\Listeners\UpdateLastLoginAt;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        UserFollowed::class => [
            SendFollowNotification::class,
        ],
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        MaterialLiked::class => [
            SendLikeNotification::class,
        ],
        MaterialFavorited::class => [
            SendFavoriteNotification::class,
        ],
        Login::class => [
            SendFirstLoginNotification::class,
            UpdateLastLoginAt::class,
        ],
        MaterialCreated::class => [
            SendNewMaterialNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        parent::boot();
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
