<?php

namespace App\Models;

use Database\Factories\CampaignFactory;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Campaign extends Model
{
    protected $fillable = ['name', 'slug', 'quota_count', 'quota_value', 'per_user_limit'];
    /** @use HasFactory<CampaignFactory> */
    use HasFactory, HasUlids;

    protected function casts(): array
    {
        return [
            'quota_count' => 'integer',
            'quota_value' => 'integer',
            'per_user_limit' => 'integer',
        ];
    }

    public function vouchers(): HasMany
    {
        return $this->hasMany(Voucher::class);
    }
}
