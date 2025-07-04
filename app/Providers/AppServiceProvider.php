<?php

namespace App\Providers;

use App\Services\VideoContentService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(VideoContentService::class, function ($app) {
        return new VideoContentService();
    });
     $this->app->bind(\App\Services\CourseService::class);

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
