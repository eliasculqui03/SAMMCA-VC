<?php

namespace App\Providers\Filament;

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
use Illuminate\View\Middleware\ShareErrorsFromSession;

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
                'primary' => '#16a34a',    // Verde esmeralda (principal)
                'secondary' => '#0f766e',  // Verde azulado oscuro (secundario)
                'success' => '#22c55e',    // Verde brillante
                'danger' => '#ef4444',     // Rojo contaminación
                'warning' => '#f59e0b',    // Naranja advertencia
                'info' => '#0ea5e9',       // Azul información
                'gray' => '#64748b',       // Gris neutro
                'dark' => '#14532d',       // Verde oscuro

                // Indicadores de calidad
                'water-pure' => '#4ade80',      // Agua óptima (verde brillante)
                'water-good' => '#86efac',      // Calidad buena (verde claro)
                'water-fair' => '#fbbf24',      // Calidad aceptable (amarillo)
                'water-poor' => '#fb923c',      // Calidad pobre (naranja)
                'water-contaminated' => '#dc2626', // Contaminada (rojo)
            ])
            ->profile()

            ->font('Red Hat Display')
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                Widgets\FilamentInfoWidget::class,
            ])
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
            ]);
    }
}
