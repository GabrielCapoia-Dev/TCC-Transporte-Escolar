<?php

namespace App\Providers\Filament;

use App\Livewire\PasswordReset;
use App\Models\User;
use App\Services\DominioEmailService;
use App\Services\GoogleService;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Rmsramos\Activitylog\ActivitylogPlugin;
use Filament\Http\Middleware\Authenticate;
use DutchCodingCompany\FilamentSocialite\FilamentSocialitePlugin;
use DutchCodingCompany\FilamentSocialite\Models\SocialiteUser;
use DutchCodingCompany\FilamentSocialite\Provider;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Contracts\User as SocialiteUserContract;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->routes(function () {
                Route::get('/password-reset', PasswordReset::class);
            })
            ->favicon(asset('images/favicon.png'))
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->brandName('GeoBus')
            ->colors([
                'primary' => Color::Green,
                'gray' => Color::Slate,
            ])
            ->sidebarCollapsibleOnDesktop()
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->plugins([
                ActivitylogPlugin::make()
                    ->label('Registro de Atividade')
                    ->pluralLabel('Registro de Atividades')
                    ->navigationGroup('Administrativo')
                    ->authorize(function () {
                        /** @var \App\Models\User|null $user */
                        $user = Auth::user();

                        // Se não estiver autenticado, esconde
                        if (!$user) {
                            return false;
                        }

                        // Mostra só para Admin
                        return $user->hasRole('Admin');
                    }),

                FilamentSocialitePlugin::make()
                    ->providers([
                        'google' => Provider::make('google')->label('Google'),
                    ])
                    ->registration(true)
                    ->createUserUsing(function (string $provider, SocialiteUserContract $oauthUser) {
                        $service = new GoogleService();

                        $email = $oauthUser->getEmail();

                        if (!app('App\Services\DominioEmailService')->isEmailAutorizado($email)) {
                            throw new \App\Exceptions\EmailNaoAutorizado('Email não é permitido para cadastro, entre em contato com o administrador.');
                        }

                        // Verifica se já existe um SocialiteUser com esse provider e provider_id
                        $existingSocialite = SocialiteUser::where('provider', $provider)
                            ->where('provider_id', $oauthUser->getId())
                            ->first();

                        if ($existingSocialite) {
                            return $existingSocialite->user;
                        }
                        $user = $service->registrarOuLogar($oauthUser);

                        return $user;
                    })
            ]);
    }
}
