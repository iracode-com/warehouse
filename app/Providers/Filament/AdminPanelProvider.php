<?php

namespace App\Providers\Filament;

use App\Filament\Pages\Auth\EditProfile;
use App\Filament\Pages\Auth\Login;
use App\Filament\Widgets\DiamondNavigationWidget;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use DiogoGPinto\AuthUIEnhancer\AuthUIEnhancerPlugin;
use Filament\FontProviders\LocalFontProvider;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Support\Facades\FilamentView;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Blade;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login(Login::class)
            ->profile(EditProfile::class)
            ->brandName('انبارداری هلال احمر')
            ->brandLogo(asset('images/helal-logo.png'))
            ->brandLogoHeight('3rem')
            ->colors([
                'primary' => Color::Red,
            ])
            ->font(
                'iransans',
                url: asset('css/iransans.css'),
                provider: LocalFontProvider::class,
            )
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->viteTheme('resources/css/filament/admin/theme.css')
            ->sidebarCollapsibleOnDesktop()
            ->navigationGroups([
                \Filament\Navigation\NavigationGroup::make(__('warehouse.navigation_groups.warehouse_management'))
                    ->collapsed()
                    ->icon('heroicon-o-building-office-2'),
                \Filament\Navigation\NavigationGroup::make(__('warehouse.navigation_groups.location_management'))
                    ->collapsed()
                    ->icon('heroicon-o-map-pin'),
                \Filament\Navigation\NavigationGroup::make(__('warehouse.navigation_groups.user_management'))
                    ->collapsed()
                    ->icon('heroicon-o-users'),
                \Filament\Navigation\NavigationGroup::make('محل قرارگیری کالا')
                    ->collapsed()
                    ->icon('heroicon-o-building-storefront'),
                \Filament\Navigation\NavigationGroup::make('مدیریت کالا')
                    ->collapsed()
                    ->icon('heroicon-o-cube'),
                \Filament\Navigation\NavigationGroup::make('منابع انسانی')
                    ->collapsed()
                    ->icon('heroicon-o-user-group'),
                \Filament\Navigation\NavigationGroup::make('مدیریت اسناد')
                    ->collapsed()
                    ->icon('heroicon-o-document-text'),
                \Filament\Navigation\NavigationGroup::make()
                    ->collapsed()
                    ->label(__('Reporting Subsystem'))
                    ->icon('heroicon-m-document-magnifying-glass'),
                \Filament\Navigation\NavigationGroup::make()
                    ->collapsed()
                    ->label(__('position.navigation.group'))
                    ->icon('heroicon-c-information-circle'),
                \Filament\Navigation\NavigationGroup::make()
                    ->collapsed()
                    ->label(__('Organizational information'))
                    ->icon('heroicon-o-list-bullet'),
            ])
            ->pages([
                Dashboard::class,
            ])
            // ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([
                    // AccountWidget::class,
                    // FilamentInfoWidget::class,
                DiamondNavigationWidget::class,
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
            ])
            ->plugins([
                FilamentShieldPlugin::make()
                    ->navigationIcon('')
                    ->activeNavigationIcon(''),
                AuthUIEnhancerPlugin::make()
                    ->formPanelPosition('left')
                    ->formPanelWidth('60%')
                    ->emptyPanelBackgroundImageUrl(asset('images/login.jpg')),
            ]);
    }

    public function boot(): void
    {
        // Add the opening div after body start
        FilamentView::registerRenderHook(
            'panels::body.start',
            fn(): string => '<div class="main-container" style="direction: rtl !important; overflow-y: auto; height: 100vh;">'
        );

        // Close the div before body end
        FilamentView::registerRenderHook(
            'panels::body.end',
            fn(): string => '</div>'
        );

        // Add your custom styles
        FilamentView::registerRenderHook(
            'panels::head.end',
            fn(): string => Blade::render('<style>
            .fi-body {
                direction: ltr;
                overflow-y: auto;
            }
            .fi-sidebar-nav-groups {
                direction: rtl !important;
            }
            .fi-sidebar-nav {
                direction: ltr;
                box-shadow: -5px 0 5px -5px #5f4c4c;
                margin-left: 20px;
            }
            .fi-layout {
                height: auto;
            }
            body::-webkit-scrollbar {
                display: none;
            }
        </style>')
        );

        FilamentView::registerRenderHook(
            'panels::body.end',
            fn(): string => '
                    <script>
                        function scrollToActiveMenuItem() {
                            const sidebarNav = document.querySelector(".fi-sidebar-nav");
                            const activeItem = document.querySelector(".fi-sidebar-item.fi-active.fi-sidebar-item-has-url");
                            
                            console.log("Elements found:", {
                                sidebarNav: !!sidebarNav,
                                activeItem: !!activeItem
                            });
                            
                            if (sidebarNav && activeItem) {
                                console.log("Scrolling to active menu item");
                                
                                // Get the position of the active item relative to the sidebar nav
                                const navRect = sidebarNav.getBoundingClientRect();
                                const itemRect = activeItem.getBoundingClientRect();
                                
                                // Calculate the scroll position to center the active item
                                const navCenter = navRect.height / 2;
                                const itemTop = itemRect.top - navRect.top + sidebarNav.scrollTop;
                                const itemHeight = itemRect.height;
                                
                                // Scroll to position the item in the center of the visible area
                                const targetScroll = itemTop - navCenter + (itemHeight / 2);
                                
                                sidebarNav.scrollTop = Math.max(0, targetScroll);
                            }
                        }
                        
                        // Run only on page load/refresh
                        function initScrollToActive() {
                            // Try immediately
                            scrollToActiveMenuItem();
                            
                            // Try again after a short delay in case elements are still loading
                            setTimeout(scrollToActiveMenuItem, 200);
                            setTimeout(scrollToActiveMenuItem, 500);
                        }
                        
                        // Initialize only on page load
                        if (document.readyState === "loading") {
                            document.addEventListener("DOMContentLoaded", initScrollToActive);
                        } else {
                            initScrollToActive();
                        }
                    </script>'
        );
    }
}
