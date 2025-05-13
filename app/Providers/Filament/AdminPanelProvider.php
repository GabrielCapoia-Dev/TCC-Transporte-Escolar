<?php

namespace App\Providers\Filament;

use App\Models\User;
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
use Laravel\Socialite\Contracts\User as SocialiteUserContract;
use Illuminate\Support\Str;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->colors([
                'primary' => Color::Blue,
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
                        $allowedDomains = ['gmail.com','gmail.com.br','edu.umuarama.pr.gov.br', 'umuarama.pr.gov.br'];
                        $email = $oauthUser->getEmail();
                        $domain = strtolower(explode('@', $email)[1] ?? '');

                        if (!in_array($domain, $allowedDomains)) {
                            abort(403, 'Acesso negado: domínio de e-mail não permitido.');
                        }

                        // Verifica se já existe um SocialiteUser com esse provider e provider_id
                        $existingSocialite = SocialiteUser::where('provider', $provider)
                            ->where('provider_id', $oauthUser->getId())
                            ->first();

                        if ($existingSocialite) {
                            return $existingSocialite->user;
                        }

                        // Verifica se já existe um User com esse e-mail
                        $user = User::where('email', $email)->first();

                        // Se existir, verifica se já tem um SocialiteUser correspondente
                        if ($user) {
                            $alreadyLinked = $user->socialiteUsers()
                                ->where('provider', $provider)
                                ->where('provider_id', $oauthUser->getId())
                                ->exists();

                            return $user;
                        }

                        // Se domínio não for permitido, aborta
                        if (!in_array($domain, $allowedDomains)) {
                            return null;
                        }

                        // Cria novo usuário e vincula SocialiteUser
                        $newUser = User::create([
                            'name' => $oauthUser->getName() ?? 'Usuário Sem Nome',
                            'email' => $email,
                            'password' => bcrypt(Str::random(16)),
                            'email_approved' => false,
                            'email_verified_at' => null,
                        ]);

                        $newUser->assignRole('Acessar Painel');
                        
                        return $newUser;
                    })

            ]);
    }
}