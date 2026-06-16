<?php

namespace App\Http\Controllers;

use App\Events\VoucherIssued;
use App\Events\VoucherRedeemed;
use App\Http\Requests\IssueVoucherRequest;
use App\Http\Requests\RedeemVoucherRequest;
use App\Http\Resources\VoucherResource;
use App\Models\Campaign;
use App\Models\Voucher;
use App\VoucherStatus;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;

class VoucherController extends Controller
{
    public function issue(IssueVoucherRequest $request): JsonResponse {
        $requestData = $request->validated();
        $campaign = Campaign::find($requestData['campaign_id']);

        if (!(Voucher::countForCampaign($requestData['campaign_id']) < $campaign->quota_count)
            || ((Voucher::totalValueForCampaign($requestData['campaign_id']) + $requestData['value']) > $campaign->quota_value)
            || !(Voucher::countForEmail($requestData['campaign_id'], $requestData['recipient_email']) < $campaign->per_user_limit)
        ) {
            return response()->json(['error' => 'Quota exceeded'], 422);
        }

        $newVoucher = Voucher::create([
            'id' => Str::ulid(),
            'campaign_id' => $requestData['campaign_id'],
            'recipient_email' => $requestData['recipient_email'],
            'value' => $requestData['value'],
            'currency' => $requestData['currency'],
            'status' => VoucherStatus::Issued,
            'code' => strtoupper(Str::random(16))
        ]);

        \Log::info('event fired');
        VoucherIssued::dispatch($newVoucher);

        return new VoucherResource($newVoucher)->response()->setStatusCode(201);
    }

    public function redeem(RedeemVoucherRequest $request): JsonResponse {
        $requestData = $request->validated();
        $voucher = Voucher::where('code', $requestData['code'])->firstOrFail();

        if ($voucher->status == VoucherStatus::Redeemed) {
            return response()->json(['error' => 'Voucher already redeemed'], 422);
        }

        $voucher->update(['status' => VoucherStatus::Redeemed]);

        VoucherRedeemed::dispatch($voucher);

        return new VoucherResource($voucher)->response()->setStatusCode(201);
    }
}
