<?php

namespace Savannabits\Saas\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Savannabits\Saas\Concerns\Model\HasAuditColumns;
use Savannabits\Saas\Concerns\Model\HasCodeFactory;
use Savannabits\Saas\Concerns\Model\HasDocStatus;
use Savannabits\Saas\Concerns\Model\HasTeam;

class DocumentCancellation extends Model
{
    use HasAuditColumns;
    use HasCodeFactory;
    use HasDocStatus;
    use HasUuids;
    use HasTeam;

    protected $guarded = ['id'];

    public function getCodePrefix(): string
    {
        return 'DCL/' . now()->isoFormat('YYYY/MM/DD/');
    }

    public function document(): MorphTo
    {
        return $this->morphTo('document');
    }
}
