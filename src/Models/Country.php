<?php

namespace Savannabits\Saas\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Savannabits\Saas\Concerns\Model\HasAuditColumns;
use Savannabits\Saas\Concerns\Model\HasCodeFactory;
use Savannabits\Saas\Concerns\Model\HasDocStatus;
use Savannabits\Saas\Models\Currency;

class Country extends Model
{
    use HasUuids, HasAuditColumns, HasDocStatus, HasCodeFactory;
    protected $guarded = ['id'];

    public function getCodePrefix(): string
    {
        return 'COUNTRY';
    }
    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class,'currency_code','code');
    }
    public function getFlagAttribute(): string
    {
        return file_get_contents($this->flag_svg_path) ?: '';
    }
    public function getFlagUrlAttribute(): string
    {
        return route('countries.code.flag',['code' => $this->code]);
    }

}
