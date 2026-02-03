<?php

namespace App\Providers;

use App\Models\Asset;
use App\Models\AssetGroup;
use App\Policies\AssetPolicy;
use App\Policies\AssetGroupPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Policies\UserPolicy;
use App\Policies\ActivityLogPolicy;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Gate::policy(Asset::class, AssetPolicy::class);
        Gate::policy(User::class, UserPolicy::class);
        Gate::policy(AssetGroup::class, AssetGroupPolicy::class);
        Gate::policy(ActivityLog::class, ActivityLogPolicy::class);
    }
}
