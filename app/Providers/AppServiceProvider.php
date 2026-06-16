<?php

namespace App\Providers;

use App\Events\VoucherIssued;
use App\Events\VoucherRedeemed;
use App\Listeners\SendVoucherIssuedWebhook;
use App\Listeners\SendVoucherRedeemedWebhook;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Event::listen(VoucherIssued::class, SendVoucherIssuedWebhook::class);
        Event::listen(VoucherRedeemed::class, SendVoucherRedeemedWebhook::class);
    }
}
