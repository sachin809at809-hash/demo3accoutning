<?php

namespace Modules\OmniChat\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\OmniChat\Models\OmnichatChannel;
use Modules\OmniChat\Models\OmnichatConversation;
use Modules\OmniChat\Models\OmnichatMessage;

class WebhookController extends Controller
{
    /**
     * Handle webhook verification (GET requests)
     * Useful for Facebook/Instagram webhook setup.
     */
    public function verify(Request $request, $platform)
    {
        // Facebook/Instagram webhook verification
        if ($platform === 'facebook' || $platform === 'instagram') {
            $verify_token = env('OMNICHAT_VERIFY_TOKEN', 'omnichat_secure_token');
            
            if ($request->get('hub_mode') === 'subscribe' && $request->get('hub_verify_token') === $verify_token) {
                return response($request->get('hub_challenge'), 200);
            }
            
            return response('Forbidden', 403);
        }

        return response('OK', 200);
    }

    /**
     * Handle incoming webhook data (POST requests)
     */
    public function handle(Request $request, $platform)
    {
        $payload = $request->all();

        // 1. Identify the channel from the payload (e.g., recipient ID)
        $channel = $this->identifyChannel($platform, $payload);
        
        if (!$channel) {
            \Log::warning("OmniChat: Webhook received for unknown channel on platform {$platform}", $payload);
            return response('Channel not found', 404);
        }

        // 2. Extract sender info and message content based on platform format
        $messageData = $this->extractMessageData($platform, $payload);
        
        if (!$messageData) {
            // Might be a delivery receipt or non-message event
            return response('Event ignored', 200);
        }

        // 3. Find or create the conversation
        $conversation = OmnichatConversation::firstOrCreate(
            [
                'company_id' => $channel->company_id,
                'channel_id' => $channel->id,
                'customer_identifier' => $messageData['customer_identifier'],
            ],
            [
                'customer_name' => $messageData['customer_name'] ?? 'Unknown Customer',
                'status' => 'open',
                'last_message_at' => now(),
            ]
        );

        // Update the conversation's last message time if it already existed
        if (!$conversation->wasRecentlyCreated) {
            $conversation->last_message_at = now();
            if ($conversation->status === 'closed') {
                $conversation->status = 'open'; // Reopen if customer replies
            }
            $conversation->save();
        }

        // 4. Save the incoming message
        OmnichatMessage::create([
            'company_id' => $channel->company_id,
            'conversation_id' => $conversation->id,
            'sender_type' => 'customer',
            'content' => $messageData['content'],
            'attachments' => $messageData['attachments'] ?? null,
            'is_read' => false,
            'platform_message_id' => $messageData['message_id'] ?? null,
        ]);

        return response('EVENT_RECEIVED', 200);
    }

    private function identifyChannel($platform, $payload)
    {
        // Example for Facebook Messenger: 
        // Payload has ['entry'][0]['id'] which is the Page ID.
        if ($platform === 'facebook' && isset($payload['entry'][0]['id'])) {
            $pageId = $payload['entry'][0]['id'];
            return OmnichatChannel::where('platform', 'facebook')
                ->where('identifier', $pageId)
                ->where('is_active', true)
                ->first();
        }

        // Example for WhatsApp API:
        // Payload has ['entry'][0]['changes'][0]['value']['metadata']['phone_number_id']
        if ($platform === 'whatsapp_api' && isset($payload['entry'][0]['changes'][0]['value']['metadata']['phone_number_id'])) {
            $phoneId = $payload['entry'][0]['changes'][0]['value']['metadata']['phone_number_id'];
            return OmnichatChannel::where('platform', 'whatsapp_api')
                ->where('identifier', $phoneId)
                ->where('is_active', true)
                ->first();
        }
        
        // Add other platform identifiers here as needed...

        // Fallback for testing: just get the first active channel for the platform
        return OmnichatChannel::where('platform', $platform)->where('is_active', true)->first();
    }

    private function extractMessageData($platform, $payload)
    {
        if ($platform === 'facebook') {
            if (isset($payload['entry'][0]['messaging'][0])) {
                $messaging = $payload['entry'][0]['messaging'][0];
                if (isset($messaging['message']['text'])) {
                    return [
                        'customer_identifier' => $messaging['sender']['id'],
                        'customer_name' => 'FB User ' . $messaging['sender']['id'], // In real app, fetch name via Graph API
                        'content' => $messaging['message']['text'],
                        'message_id' => $messaging['message']['mid'],
                    ];
                }
            }
        }

        if ($platform === 'whatsapp_api') {
            if (isset($payload['entry'][0]['changes'][0]['value']['messages'][0])) {
                $message = $payload['entry'][0]['changes'][0]['value']['messages'][0];
                $contact = $payload['entry'][0]['changes'][0]['value']['contacts'][0] ?? null;
                if (isset($message['text']['body'])) {
                    return [
                        'customer_identifier' => $message['from'],
                        'customer_name' => $contact['profile']['name'] ?? 'WA User ' . $message['from'],
                        'content' => $message['text']['body'],
                        'message_id' => $message['id'],
                    ];
                }
            }
        }

        // If structure is not recognized or not implemented yet
        return null;
    }
}
