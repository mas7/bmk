<?php

namespace App\Providers\Filament;

use App\Filament\Admin\Widgets\TicketsStatsOverview;
use App\Http\Middleware\RedirectUser;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Jeffgreco13\FilamentBreezy\BreezyCore;

class ContractorPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('contractor')
            ->path('contractor')
            ->darkMode(false)
            ->login()
            ->colors([
                'primary' => Color::Indigo,
            ])
            ->discoverResources(in: app_path('Filament/Contractor/Resources'), for: 'App\\Filament\\Contractor\\Resources')
            ->discoverPages(in: app_path('Filament/Contractor/Pages'), for: 'App\\Filament\\Contractor\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Contractor/Widgets'), for: 'App\\Filament\\Contractor\\Widgets')
            ->widgets([
                TicketsStatsOverview::class
                // Widgets\AccountWidget::class,
                // Widgets\FilamentInfoWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                RedirectUser::class,
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
                BreezyCore::make()
                    ->myProfile(
                        shouldRegisterUserMenu: true,    // Sets the 'account' link in the panel User Menu (default = true)
                        shouldRegisterNavigation: false, // Adds a main navigation item for the My Profile page (default = false)
                        hasAvatars: false,               // Enables the avatar upload form component (default = false)
                        slug: 'my-profile'              // Sets the slug for the profile page (default = 'my-profile')
                    )
                    ->passwordUpdateRules(
                        rules: [
                            Password::default()->mixedCase()
                        ],                              // you may pass an array of validation rules as well. (default = ['min:8'])
                        requiresCurrentPassword: false, // when false, the user can update their password without entering their current password. (default = true)
                    )
            ])
            ->brandLogo(fn() => view('filament.admin.logo', [
                'roleName' => 'Contractor'
            ]));
    }
}
