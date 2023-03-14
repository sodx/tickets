<?php

namespace App\Providers;

use App\Actions\GetActiveCity;
use App\Actions\GetMenuItems;
use App\Actions\Slugify;
use Illuminate\Support\ServiceProvider;
use Spatie\Menu\Laravel\Menu;

class MacrosServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Menu::macro('footer', function () {
            $activeCity = GetActiveCity::run();
            return Menu::new()
                ->route('home', 'Home', [
                    'location' => Slugify::run($activeCity['user_location']),
                ])
                ->setActiveFromRequest();
        });

        Menu::macro('Main', function () {
            $activeCity = GetActiveCity::run();
            $menuItems = GetMenuItems::run($activeCity);
            $menuBuilder = new GetMenuItems();

            return Menu::new()
                ->route('events', 'Events', [
                    'location' => Slugify::run($activeCity['user_location']),
                ])
                ->submenuIf(
                    array_key_exists(0, $menuItems) && !empty($menuItems[0]) && !empty($menuItems[0]['genres']),
                    $menuBuilder->getSubMenuHeader($menuItems, 0, $activeCity),
                    $menuBuilder->getSubMenu($menuItems, 0, $activeCity)
                )
                ->submenuIf(
                    array_key_exists(1, $menuItems) && !empty($menuItems[1]) && !empty($menuItems[1]['genres']),
                    $menuBuilder->getSubMenuHeader($menuItems, 1, $activeCity),
                    $menuBuilder->getSubMenu($menuItems, 1, $activeCity)
                )
                ->submenuIf(
                    isset($menuItems[2]) && !empty($menuItems[2]) && !empty($menuItems[2]['genres']) ,
                    $menuBuilder->getSubMenuHeader($menuItems, 2, $activeCity),
                    $menuBuilder->getSubMenu($menuItems, 2, $activeCity)
                )
                ->submenuIf(
                    isset($menuItems[3]) && !empty($menuItems[3]) && !empty($menuItems[3]['genres']),
                    $menuBuilder->getSubMenuHeader($menuItems, 3, $activeCity),
                    $menuBuilder->getSubMenu($menuItems, 3, $activeCity)
                )
                ->submenuIf(
                    isset($menuItems[4]) && !empty($menuItems[4]) && !empty($menuItems[4]['genres']),
                    $menuBuilder->getSubMenuHeader($menuItems, 4, $activeCity),
                    $menuBuilder->getSubMenu($menuItems, 4, $activeCity)
                )
                ->setActiveFromRequest();
        });
    }
}
