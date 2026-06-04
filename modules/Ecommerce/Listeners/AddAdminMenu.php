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

        // Group: Ecommerce
        $menu->dropdown('Ecommerce', function ($sub) {
            $sub->route('dashboard', 'Home', [], 1, ['icon' => 'home']);
            $sub->route('ecommerce.store_users.index', 'Store Users', [], 2, ['icon' => 'people']);
            $sub->route('ecommerce.categories.index', 'Categories', [], 3, ['icon' => 'category']);
            $sub->route('ecommerce.brands.index', 'Brands', [], 4, ['icon' => 'copyright']);
            $sub->route('ecommerce.products.index', 'Products', [], 5, ['icon' => 'inventory_2']);
            $sub->route('ecommerce.inventory.index', 'Inventory', [], 6, ['icon' => 'warehouse']);
            $sub->route('ecommerce.reviews.index', 'Reviews', [], 7, ['icon' => 'star']);
            $sub->route('ecommerce.crm.index', 'Customers', [], 8, ['icon' => 'person']);
            $sub->route('ecommerce.orders.index', 'Orders', [], 9, ['icon' => 'shopping_bag']);
            $sub->route('ecommerce.crm.index', 'Leads', ['type' => 'leads'], 10, ['icon' => 'leaderboard']);
            $sub->route('ecommerce.issues.index', 'Issues', [], 11, ['icon' => 'report_problem']);
            $sub->route('ecommerce.sms.index', 'Apex SMS', [], 12, ['icon' => 'sms']);
            $sub->url('#', 'Discount Coupons', 13, ['icon' => 'local_offer']);
            $sub->url('#', 'Analytics', 14, ['icon' => 'analytics']);
            $sub->url('#', 'Media', 15, ['icon' => 'perm_media']);
            $sub->route('ecommerce.transactions.index', 'Transactions', [], 16, ['icon' => 'receipt']);
        }, 50, ['icon' => 'storefront']);

        // Group: Customizations
        $menu->dropdown('Customizations', function ($sub) {
            $sub->url('#', 'Pages', 1, ['icon' => 'description']);
            $sub->url('#', 'Plugins', 2, ['icon' => 'extension']);
            $sub->url('#', 'Appearance', 3, ['icon' => 'palette']);
            $sub->route('ecommerce.outlets.index', 'Store Setting', [], 4, ['icon' => 'storefront']);
            $sub->url('#', 'Payment Setting', 5, ['icon' => 'payment']);
            $sub->url('#', 'Checkout Setting', 6, ['icon' => 'shopping_cart_checkout']);
        }, 15, ['icon' => 'settings']);
    }
}
