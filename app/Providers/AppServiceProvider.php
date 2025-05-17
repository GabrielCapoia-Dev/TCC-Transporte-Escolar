<?php

namespace App\Providers;

use App\Models\DominioEmail;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use App\Policies\DominioEmailPolicy;
use App\Policies\PermissionPolicy;
use App\Policies\RolePolicy;
use App\Policies\UserPolicy;
use BladeUI\Icons\Factory;
use Filament\Support\Facades\FilamentIcon;
use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Injetando html direto na tela de login 
        FilamentView::registerRenderHook(
            PanelsRenderHook::AUTH_LOGIN_FORM_AFTER,
            fn(): string => <<< 'HTML'
            <div class='flex justify-end gap-1 text-sm'>
                <a href="/admin/password-reset" class="text-primary-500">Esqueceu sua senha?</a>
            </div>
            HTML
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::policy(User::class, UserPolicy::class);
        Gate::policy(Role::class, RolePolicy::class);
        Gate::policy(Permission::class, PermissionPolicy::class);
        Gate::policy(DominioEmail::class, DominioEmailPolicy::class);
    }
}