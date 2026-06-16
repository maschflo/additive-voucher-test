<?php

namespace App\Http\Controllers;

use App\Models\WebhookSubscription;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class WebhookController extends Controller
{
    public function handle(Request $request) {
        $secret = Str::random(32);

        $subscription = WebhookSubscription::create([
            'event' => $request->event,
            'url' => $request->url,
            'secret' => $secret,
        ]);

        return response()->json([
            'id' => $subscription->id,
            'secret' => $secret,
        ], 201);
    }
}
