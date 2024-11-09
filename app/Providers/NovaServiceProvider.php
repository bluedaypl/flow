<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Gate;
use Laravel\Nova\Nova;
use Laravel\Nova\NovaApplicationServiceProvider;
use Illuminate\Http\Request;
use Laravel\Nova\Menu\MenuItem;
use Laravel\Nova\Menu\MenuSection;
use App\Nova\Dashboards\Main as DashboardsMain;
use App\Nova\User;

class NovaServiceProvider extends NovaApplicationServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();


        Nova::footer(function ($request) {
            return Blade::render('<p class="text-center">Powered by <a class="link-default" href="https://blueday.pl" target="_blank">BlueDay.pl</a> · v1.0.0</p>');
        });

        Nova::style('admin', public_path('css/admin.css'));
        Nova::mainMenu(function (Request $request) {
            return [
                MenuSection::dashboard(DashboardsMain::class)->icon('chart-bar'),
                MenuSection::make(__('Delivery'), [
                    MenuItem::resource(\App\Nova\Order::class),
                    MenuItem::resource(\App\Nova\Producer::class),
                ])->icon('truck'),

                MenuSection::make(__('Settings'), [
                    MenuItem::resource(\App\Nova\Status::class),
                    MenuItem::resource(User::class),
                ])->icon('cog')->collapsable()->canSee(function ($request) {
                    return $request->user() ? $request->user()->isAdmin() : false;
                }),
                ];
        });

    }

    /**
     * Register the Nova routes.
     *
     * @return void
     */
    protected function routes()
    {
        Nova::routes()
                ->withAuthenticationRoutes()
                ->withPasswordResetRoutes()
                ->register();
    }

    /**
     * Register the Nova gate.
     *
     * This gate determines who can access Nova in non-local environments.
     *
     * @return void
     */
    protected function gate()
    {
        Gate::define('viewNova', function ($user) {
            return true;
            // return in_array($user->email, [
            //     //
            // ]);
        });
    }

    /**
     * Get the dashboards that should be listed in the Nova sidebar.
     *
     * @return array
     */
    protected function dashboards()
    {
        return [
            new \App\Nova\Dashboards\Main,
        ];
    }

    /**
     * Get the tools that should be listed in the Nova sidebar.
     *
     * @return array
     */
    public function tools()
    {
        return [
        ];
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
