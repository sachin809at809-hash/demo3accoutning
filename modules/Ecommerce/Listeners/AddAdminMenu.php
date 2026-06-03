<?php

namespace Modules\Ecommerce\Listeners;

use App\Events\Menu\AdminCreated;

class AddAdminMenu
{
    /**
     * Handle the event.
     *
     * @param AdminCreated $event
     * @return void
     */
    public function handle(AdminCreated $event)
    {
        $menu = $event->menu;
        $title = 'E-Commerce';
        $attr = ['icon' => ''];
        
        $menu->dropdown($title, function ($sub) use ($attr) {
            $sub->route('ecommerce.dashboard', 'Dashboard', [], 10, $attr);
            $sub->route('ecommerce.builder', 'Website Builder', [], 20, $attr);
            $sub->route('ecommerce.zones', 'Delivery Zones', [], 30, $attr);
            $sub->route('ecommerce.products.index', 'Products', [], 40, $attr);
            $sub->route('ecommerce.categories.index', 'Categories', [], 50, $attr);
            $sub->route('ecommerce.orders.index', 'Orders (OMS)', [], 60, $attr);
            $sub->route('ecommerce.settings.index', 'Settings', [], 99, $attr);
        }, 46, [
            'title' => $title,
            'icon' => 'storefront',
        ]);
    }
}
