<?php

namespace App\Listeners;

use App\Events\VoucherIssued;
use App\Jobs\DeliverWebhook;
use App\Models\WebhookSubscription;

class SendVoucherIssuedWebhook
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**‚
     * Handle the event.
     */
    public function handle(VoucherIssued $event): void
    {
        $body = [
            'event' => 'voucher.issued',
            'voucher' => $event->voucher->toArray(),
        ];

        $payload = json_encode($body);

        WebhookSubscription::where('event', 'voucher.issued')
            ->each(fn($subscription) => DeliverWebhook::dispatch($subscription, $body, $payload));
    }
}
