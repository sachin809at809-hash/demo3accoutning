<?php

namespace Modules\OmniChat\Http\Controllers;

use Modules\OmniChat\Http\Controllers\BaseController as Controller;
use Illuminate\Http\Request;
use Modules\OmniChat\Models\OmnichatConversation;

class InboxController extends Controller
{
    public function index(Request $request)
    {
        $platform = $request->query('platform');
        
        $query = OmnichatConversation::with('channel')
            ->orderBy('last_message_at', 'desc');
            
        if ($platform) {
            $query->whereHas('channel', function($q) use ($platform) {
                $q->where('platform', $platform);
            });
        }
        
        $conversations = $query->get();
        
        $activePlatforms = \Modules\OmniChat\Models\OmnichatChannel::where('is_active', true)
            ->pluck('platform')
            ->unique();
            
        return view('omni-chat::inbox', compact('conversations', 'activePlatforms', 'platform'));
    }

    public function show(Request $request, OmnichatConversation $conversation)
    {
        $platform = $request->query('platform');
        
        $query = OmnichatConversation::with('channel')
            ->orderBy('last_message_at', 'desc');
            
        if ($platform) {
            $query->whereHas('channel', function($q) use ($platform) {
                $q->where('platform', $platform);
            });
        }
        
        $conversations = $query->get();
        
        $activePlatforms = \Modules\OmniChat\Models\OmnichatChannel::where('is_active', true)
            ->pluck('platform')
            ->unique();
            
        $conversation->load('messages', 'channel');
        
        return view('omni-chat::inbox', compact('conversations', 'conversation', 'activePlatforms', 'platform'));
    }

    public function reply(Request $request, OmnichatConversation $conversation)
    {
        $request->validate(['message' => 'required|string']);
        
        $message = $conversation->messages()->create([
            'company_id' => $conversation->company_id,
            'direction' => 'outgoing',
            'body' => $request->message,
            'type' => 'text',
            'status' => 'pending',
        ]);
        
        $conversation->update(['last_message_at' => now()]);
        
        \Modules\OmniChat\Jobs\SendMessageJob::dispatch($message->id);

        return redirect()->back();
    }
}
