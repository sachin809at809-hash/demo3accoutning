<?php

namespace Modules\OmniChat\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\OmniChat\Models\OmnichatMessage;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SendMessageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $messageId;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($messageId)
    {
        $this->messageId = $messageId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $message = OmnichatMessage::with('conversation.channel')->find($this->messageId);
        
        if (!$message || !$message->conversation || !$message->conversation->channel) {
            Log::error("SendMessageJob: Invalid message or missing relations for ID {$this->messageId}");
            return;
        }

        $conversation = $message->conversation;
        $channel = $conversation->channel;
        $platform = $channel->platform;
        $credentials = $channel->credentials;
        $recipient = $conversation->external_id;

        try {
            switch ($platform) {
                case 'whatsapp_api':
                    $this->sendWhatsAppApi($credentials, $recipient, $message->body);
                    break;
                case 'facebook':
                    $this->sendFacebook($credentials, $recipient, $message->body);
                    break;
                case 'instagram':
                    $this->sendInstagram($credentials, $recipient, $message->body);
                    break;
                case 'tiktok':
                    $this->sendTikTok($credentials, $recipient, $message->body);
                    break;
                case 'linkedin':
                    $this->sendLinkedIn($credentials, $recipient, $message->body);
                    break;
                case 'mail':
                    $this->sendEmail($credentials, $recipient, $message->body);
                    break;
                case 'whatsapp_qr':
                    $this->sendWhatsAppQr($credentials, $recipient, $message->body);
                    break;
                default:
                    Log::warning("SendMessageJob: Unsupported platform {$platform}");
            }
            
            // Mark as sent
            $message->update(['status' => 'sent']);
            
        } catch (\Exception $e) {
            Log::error("SendMessageJob Error for {$platform}: " . $e->getMessage());
            $message->update(['status' => 'failed']);
        }
    }

    protected function sendWhatsAppApi($credentials, $to, $text)
    {
        $token = $credentials['api_key'] ?? '';
        $phoneId = $credentials['identifier'] ?? ''; // Wait, in Settings we mapped Phone Number ID to 'identifier'
        
        // Settings mapping:
        // WhatsApp API: identifier = Phone Number ID
        // The true identifier might be in the model, let's fetch it from channel
        $channel = OmnichatMessage::find($this->messageId)->conversation->channel;
        $phoneId = $channel->identifier;

        $response = Http::withToken($token)->post("https://graph.facebook.com/v19.0/{$phoneId}/messages", [
            'messaging_product' => 'whatsapp',
            'to' => $to,
            'type' => 'text',
            'text' => ['body' => $text],
        ]);

        if ($response->failed()) {
            throw new \Exception('WhatsApp API Error: ' . $response->body());
        }
    }

    protected function sendFacebook($credentials, $to, $text)
    {
        $channel = OmnichatMessage::find($this->messageId)->conversation->channel;
        $pageId = $channel->identifier;
        $token = $credentials['api_key'] ?? '';

        $response = Http::post("https://graph.facebook.com/v19.0/{$pageId}/messages", [
            'recipient' => ['id' => $to],
            'message' => ['text' => $text],
            'messaging_type' => 'RESPONSE',
            'access_token' => $token
        ]);

        if ($response->failed()) {
            throw new \Exception('Facebook API Error: ' . $response->body());
        }
    }

    protected function sendInstagram($credentials, $to, $text)
    {
        $channel = OmnichatMessage::find($this->messageId)->conversation->channel;
        $igId = $channel->identifier;
        $token = $credentials['api_key'] ?? '';

        $response = Http::post("https://graph.facebook.com/v19.0/{$igId}/messages", [
            'recipient' => ['id' => $to],
            'message' => ['text' => $text],
            'access_token' => $token
        ]);

        if ($response->failed()) {
            throw new \Exception('Instagram API Error: ' . $response->body());
        }
    }

    protected function sendTikTok($credentials, $to, $text)
    {
        // Placeholder for TikTok API
        Log::info("TikTok API Simulation: Sent message to {$to}");
    }

    protected function sendLinkedIn($credentials, $to, $text)
    {
        // Placeholder for LinkedIn Messaging API
        Log::info("LinkedIn API Simulation: Sent message to {$to}");
    }

    protected function sendEmail($credentials, $to, $text)
    {
        // Placeholder for Email API (e.g. SendGrid)
        Log::info("Email API Simulation: Sent message to {$to}");
    }

    protected function sendWhatsAppQr($credentials, $to, $text)
    {
        // Placeholder for Local Baileys Node Wrapper API
        Log::info("WhatsApp QR Local API Simulation: Sent message to {$to}");
    }
}
