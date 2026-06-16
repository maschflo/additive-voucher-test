<?php

namespace App\Listeners;

use App\Events\VoucherRedeemed;
use App\Jobs\DeliverWebhook;
use App\Models\WebhookSubscription;

class SendVoucherRedeemedWebhook
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(VoucherRedeemed $event): void
    {
        $body = [
            'event' => 'voucher.redeemed',
            'voucher' => $event->voucher->toArray(),
        ];

        $payload = json_encode($body);

        WebhookSubscription::where('event', 'voucher.redeemed')
            ->each(fn($subscription) => DeliverWebhook::dispatch($subscription, $body, $payload));
    }
}
