<?php

namespace App\Providers;

use App\Models\User;
use App\View\Composers\SidebarComposer;
use App\View\Composers\SiteComposer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // $site tersedia di semua view (layout, halaman, dan komponen)
        View::composer('*', SiteComposer::class);
        View::composer('components.sidebar', SidebarComposer::class);

        // Pagination bergaya situs (tanpa Tailwind)
        Paginator::defaultView('vendor.pagination.desa');

        // Menu Pengguna, Pengaturan, dan data resmi desa hanya untuk role admin
        Gate::define('admin-only', fn (User $user) => $user->isAdmin());

        // Deteksi query N+1 saat development: hanya dicatat di storage/logs/laravel.log
        // (tidak membuat error), dan nonaktif di production.
        Model::preventLazyLoading(! $this->app->isProduction());
        Model::handleLazyLoadingViolationUsing(function (Model $model, string $relation) {
            Log::warning('Lazy loading terdeteksi: '.get_class($model).'::'.$relation);
        });

        // Di production, blokir migrate:fresh / migrate:reset / db:wipe agar data tidak terhapus tidak sengaja
        DB::prohibitDestructiveCommands($this->app->isProduction());
    }
}
