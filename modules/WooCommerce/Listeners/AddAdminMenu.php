<?php

namespace Modules\WooCommerce\Listeners;

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
        $event->menu->add([
            'url' => route('woo-commerce.index'),
            'title' => 'WooCommerce',
            'icon' => 'shopping_cart',
            'order' => 50,
        ]);
    }
}
