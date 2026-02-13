<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Session;
use Illuminate\Pagination\Paginator;

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
        Paginator::useBootstrapFive();

        // View composer untuk passing layout ke semua view
        View::composer('*', function ($view) {
            $layout = Session::has('anggota_id') ? 'layouts.member' : 'layouts.app';
            $view->with('app_layout', $layout);
        });
    }
}
