<?php

namespace App\Models;

use App\VoucherStatus;
use Database\Factories\VoucherFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Voucher extends Model
{
    protected $fillable = ['id', 'campaign_id', 'recipient_email', 'value', 'currency', 'status', 'code'];
    /** @use HasFactory<VoucherFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'status' => VoucherStatus::class,
            'value' => 'integer',
        ];
    }

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class);
    }

    public static function totalValueForCampaign(string $campaignId): int
    {
        return (int) self::where('campaign_id', $campaignId)->sum('value');
    }

    public static function countForCampaign(string $campaignId): int
    {
        return self::where('campaign_id', $campaignId)->count();
    }

    public static function countForEmail(string $campaignId, string $email): int
    {
        return self::where('campaign_id', $campaignId)->where('recipient_email', $email)->count();
    }
}
