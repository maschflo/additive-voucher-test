<?php

namespace App\Jobs;

use App\Models\WebhookSubscription;
use Illuminate\Support\Facades\Http;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class DeliverWebhook implements ShouldQueue
{
    use Queueable;

    public int $tries = 5;

    public function backoff(): array
    {
        return [10, 30, 60, 300];
    }

    public function __construct(
        public readonly WebhookSubscription $subscription,
        public readonly array $body,
        public readonly string $payload,
    ) {}

    public function handle(): void
    {
        \Log::info('deliver called');
        $signature = hash_hmac('sha256', $this->payload, $this->subscription->secret);

        $response = Http::withHeader('X-Signature', 'sha256=' . $signature)
            ->post($this->subscription->url, $this->body);

        if ($response->failed()) {
            throw new \Exception("Webhook delivery failed for subscription {$this->subscription->id}");
        }
    }
}
