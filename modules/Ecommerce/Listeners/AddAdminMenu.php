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

        // Group: Main Links
        $menu->dropdown('Main Links', function ($sub) {
            $sub->route('dashboard', 'Home', [], 1, ['icon' => 'home']);
            $sub->url('#', 'Store Users', 2, ['icon' => 'people']);
            $sub->url('#', 'Categories', 3, ['icon' => 'category']);
            $sub->url('#', 'Brands', 4, ['icon' => 'copyright']);
            $sub->url('#', 'Products', 5, ['icon' => 'inventory_2']);
            $sub->route('ecommerce.inventory.index', 'Inventory', [], 6, ['icon' => 'warehouse']);
            $sub->url('#', 'Reviews', 7, ['icon' => 'star']);
            $sub->url('#', 'Customers', 8, ['icon' => 'person']);
            $sub->url('#', 'Orders', 9, ['icon' => 'shopping_bag']);
            $sub->url('#', 'Leads', 10, ['icon' => 'leaderboard']);
            $sub->url('#', 'Issues', 11, ['icon' => 'report_problem']);
            $sub->route('ecommerce.sms.index', 'Blanxer SMS', [], 12, ['icon' => 'sms']);
            $sub->url('#', 'Discount Coupons', 13, ['icon' => 'local_offer']);
            $sub->url('#', 'Analytics', 14, ['icon' => 'analytics']);
            $sub->url('#', 'Media', 15, ['icon' => 'perm_media']);
            $sub->url('#', 'Transactions', 16, ['icon' => 'receipt']);
        }, 5, ['icon' => 'list']);

        // Group: Customizations
        $menu->dropdown('Customizations', function ($sub) {
            $sub->url('#', 'Pages', 1, ['icon' => 'description']);
            $sub->url('#', 'Plugins', 2, ['icon' => 'extension']);
            $sub->url('#', 'Appearance', 3, ['icon' => 'palette']);
            $sub->url('#', 'Store Setting', 4, ['icon' => 'storefront']);
            $sub->url('#', 'Payment Setting', 5, ['icon' => 'payment']);
            $sub->url('#', 'Checkout Setting', 6, ['icon' => 'shopping_cart_checkout']);
        }, 15, ['icon' => 'settings']);
    }
}
