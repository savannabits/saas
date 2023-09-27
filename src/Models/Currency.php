<?php

namespace Savannabits\Saas\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Savannabits\Saas\Concerns\Model\HasAuditColumns;
use Savannabits\Saas\Concerns\Model\HasCodeFactory;
use Savannabits\Saas\Concerns\Model\HasDocStatus;
use Savannabits\Saas\Concerns\Model\HasTeam;
use Savannabits\Saas\Models\Country;

class Currency extends Model
{
    use HasUuids, HasAuditColumns, HasCodeFactory, HasDocStatus;
    protected $guarded = ['id'];
    public function getCodePrefix(): string
    {
        return 'CURR';
    }

    public function country(): HasOne
    {
        return $this->hasOne(Country::class,'currency_code','code');
    }
}
