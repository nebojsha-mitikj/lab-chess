<?php

namespace App\Listeners;

use Illuminate\Support\Facades\Log;
use Laravel\Paddle\Events\WebhookReceived;

class PaddleEventListener
{
    /**
     * Handle the event.
     * @param WebhookReceived $event
     * @return void
     */
    public function handle(WebhookReceived $event) {
        Log::info("Paddle Webhook Payload: " . json_encode($event->payload));
    }
}
