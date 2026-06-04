<?php

namespace Modules\OmniChat\Listeners;

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
        $event->menu->dropdown('OmniChat', function ($sub) {
            $sub->route('omni-chat.inbox', 'Inbox', [], 1, ['icon' => 'forum']);
            $sub->route('omni-chat.canned_responses.index', 'Canned Responses', [], 2, ['icon' => 'quickreply']);
            $sub->route('omni-chat.settings', 'Settings', [], 3, ['icon' => 'settings']);
        }, 45, ['icon' => 'forum']);
    }
}
