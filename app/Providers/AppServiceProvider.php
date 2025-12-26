<?php

namespace App\Providers;

use App\Models\LockerItem;
use App\Models\LockerSession;
use App\Observers\LockerItemObserver;
use App\Observers\LockerSessionObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        LockerItem::observe(LockerItemObserver::class);
        LockerSession::observe(LockerSessionObserver::class);
        \App\Models\LockerItem::observe(\App\Observers\LockerItemObserver::class);
    }
}
