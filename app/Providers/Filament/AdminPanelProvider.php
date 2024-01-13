<?php

namespace App\Providers\Filament;

use App\Filament\Admin\Widgets\PropertyStatsOverview;
use App\Filament\Admin\Widgets\TicketsStatsOverview;
use App\Filament\Admin\Widgets\UsersStatsOverview;
use App\Http\Middleware\RedirectUser;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
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
use pxlrbt\FilamentSpotlight\SpotlightPlugin;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('admin')
            ->path('admin')
            ->darkMode(false)
            ->login()
            ->colors([
                'primary' => Color::Cyan,
            ])
            ->discoverResources(in: app_path('Filament/Admin/Resources'), for: 'App\\Filament\\Admin\\Resources')
            ->discoverPages(in: app_path('Filament/Admin/Pages'), for: 'App\\Filament\\Admin\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            //->discoverWidgets(in: app_path('Filament/Admin/Widgets'), for: 'App\\Filament\\Admin\\Widgets')
            ->widgets([
                TicketsStatsOverview::class,
                UsersStatsOverview::class,
                PropertyStatsOverview::class,
                //Widgets\AccountWidget::class,
                //Widgets\FilamentInfoWidget::class,
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
            ->navigationGroups([
                'Ticket Management',
                'Contractors',
                'Properties',
            ])
            ->plugins([
                SpotlightPlugin::make(),
                FilamentShieldPlugin::make(),
                BreezyCore::make()
                    ->myProfile()
                    ->passwordUpdateRules(
                        rules: [
                            Password::default()->mixedCase()
                        ],                                     // you may pass an array of validation rules as well. (default = ['min:8'])
                        requiresCurrentPassword: false         // when false, the user can update their password without entering their current password. (default = true)
                    )
            ])
            ->brandLogo(fn() => view('filament.admin.logo', [
                'roleName' => 'Admin'
            ]));
    }
}
