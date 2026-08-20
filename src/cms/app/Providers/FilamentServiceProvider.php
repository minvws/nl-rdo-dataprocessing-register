<?php

declare(strict_types=1);

namespace App\Providers;

use App\Facades\Authentication;
use App\Filament\NavigationGroups\NavigationGroup;
use App\Filament\Pages\Login;
use App\Filament\Pages\Profile;
use App\Filament\SimpleAvatarProvider;
use App\Http\Controllers\HealthController;
use App\Http\Middleware\EnforceOneTimePassword;
use App\Http\Middleware\IPAllowFilter;
use App\Models\Organisation;
use Exception;
use Filament\Facades\Filament;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Field;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\MenuItem;
use Filament\Navigation\NavigationGroup as FilamentNavigationGroup;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Assets\Js;
use Filament\Support\Facades\FilamentAsset;
use Filament\Support\Facades\FilamentView;
use Filament\Tables\Actions\EditAction;
use Filament\View\PanelsRenderHook;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Routing\Route;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Route as RouteFacade;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Illuminate\View\View;
use Livewire\Component;
use Spatie\Csp\AddCspHeaders;
use Webmozart\Assert\Assert;

use function __;
use function abort;
use function app_path;
use function asset;
use function base_path;
use function request;
use function view;

class FilamentServiceProvider extends PanelProvider
{
    /**
     * WCAG 2.0 compliant primary color palette, based on the RDO orange (#F97316).
     * The 50-900 shades are from Tailwind CSS, and the 950 shade is a custom darker shade.
     */
    private const array PRIMARY_COLOR = [
        50 => '255, 247, 237',
        100 => '255, 237, 213',
        200 => '254, 215, 170',
        300 => '253, 186, 116',
        400 => '249, 115, 22',
        500 => '194, 65, 12',
        600 => '154, 52, 18',
        700 => '124, 45, 18',
        800 => '95, 35, 14',
        900 => '67, 20, 7',
        950 => '45, 14, 5',
    ];

    public function boot(): void
    {
        FilamentAsset::register([
            Js::make('wcag', base_path('resources/js/wcag.js')),
        ]);

        foreach ([Radio::class, CheckboxList::class, Select::class] as $choiceField) {
            $choiceField::configureUsing(static function (Field $field): void {
                $field->validationMessages(['required' => __('validation.required_choice')]);
            });
        }

        EditAction::configureUsing(static function (EditAction $action): void {
            $action->extraAttributes(['data-row-target' => 'true'], merge: true);
        });

        Repeater::configureUsing(static function (Repeater $repeater): void {
            $repeater
                ->extraAttributes(static function (Repeater $component): array {
                    return ['data-state-path' => $component->getStatePath()];
                }, merge: true)
                ->addAction(static function (Action $action): Action {
                    return $action->after(static function (Component $livewire, Repeater $component): void {
                        $livewire->dispatch('repeater-item-added', statePath: $component->getStatePath());
                    });
                });
        });

        FilamentView::registerRenderHook(
            PanelsRenderHook::BODY_START,
            static function (): View {
                return view('filament.skip_link');
            },
        );

        FilamentView::registerRenderHook(
            PanelsRenderHook::CONTENT_START,
            static function (): View {
                return view('filament.main_content_anchor');
            },
        );

        FilamentView::registerRenderHook(
            PanelsRenderHook::TOPBAR_START,
            static function (): View {
                return view('filament.topbar.organisation_name');
            },
        );

        FilamentView::registerRenderHook(
            PanelsRenderHook::SIDEBAR_NAV_START,
            static function (): View {
                return view('filament.sidebar.close_on_escape');
            },
        );

        FilamentView::registerRenderHook(
            PanelsRenderHook::BODY_END,
            static function (): ?View {
                if (Filament::auth()->guest()) {
                    return null;
                }

                return view('filament.session.expiry_warning');
            },
        );
    }

    /**
     * @throws Exception
     */
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('/')
            ->login(Login::class)
            ->profile(Profile::class)
            ->routes(static function (): void {
                RouteFacade::get('/health', HealthController::class);
            })
            ->colors([
                'primary' => self::PRIMARY_COLOR,
            ])
            ->defaultAvatarProvider(SimpleAvatarProvider::class)
            ->unsavedChangesAlerts()
            ->navigationGroups([
                FilamentNavigationGroup::make()
                    ->label(__(NavigationGroup::REGISTERS->value))
                    ->collapsible(false),
                FilamentNavigationGroup::make()
                    ->label(__(NavigationGroup::MANAGEMENT->value))
                    ->collapsible(false),
                FilamentNavigationGroup::make()
                    ->label(__(NavigationGroup::OVERVIEWS->value))
                    ->collapsible(false),
                FilamentNavigationGroup::make()
                    ->label(__(NavigationGroup::ORGANISATION->value))
                    ->collapsible(false),
                FilamentNavigationGroup::make()
                    ->label(__(NavigationGroup::FUNCTIONAL_MANAGEMENT->value)),
                FilamentNavigationGroup::make()
                    ->label(__(NavigationGroup::LOOKUP_LISTS->value)),
            ])
            ->tenant(Organisation::class, 'slug', 'organisation')
            ->tenantMenu(static function (): bool {
                return Authentication::user()->organisations->count() > 1;
            })
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                AccountWidget::class,
                FilamentInfoWidget::class,
            ])
            ->databaseNotifications()
            ->databaseNotificationsPolling(null)
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                PreventRequestForgery::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
                AddCspHeaders::class,
            ])
            ->authMiddleware([
                Authenticate::class,
                EnforceOneTimePassword::class,
            ])
            ->tenantMiddleware([
                IPAllowFilter::class,
            ], isPersistent: true)
            ->viteTheme('resources/css/filament/admin/theme.css')
            ->userMenuItems([
                'account' => MenuItem::make()
                    ->url(static function (): string {
                        $panel = Filament::getCurrentPanel();
                        Assert::isInstanceOf($panel, Panel::class);

                        $route = request()->route();
                        Assert::isInstanceOf($route, Route::class);

                        try {
                            $tenant = Organisation::where(['slug' => $route->parameter('tenant')])->firstOrFail();
                        } catch (ModelNotFoundException) {
                            abort(404);
                        }

                        return Profile::getUrl(panel: $panel->getId(), tenant: $tenant);
                    }),
                'manual' => MenuItem::make()
                    ->url(asset('pdf/verwerkingsregister_handleiding.pdf'), true)
                    ->icon('heroicon-o-document-check')
                    ->label(__('general.manual')),
            ])
            ->maxContentWidth('screen-2xl')
            ->sidebarWidth('25rem')
            ->sidebarCollapsibleOnDesktop()
            ->favicon(asset('favicon.ico'));
    }
}
