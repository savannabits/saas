<?php

namespace Savannabits\Saas\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Kalnoy\Nestedset\NodeTrait;
use Savannabits\Saas\Concerns\Model\HasAuditColumns;
use Savannabits\Saas\Concerns\Model\HasCodeFactory;
use Savannabits\Saas\Concerns\Model\HasDocStatus;

class Team extends Model
{
    use HasAuditColumns;
    use HasCodeFactory;
    use HasDocStatus;
    use HasUuids;
    use NodeTrait;

    protected $guarded = ['id'];

    public function getCodePrefix(): string
    {
        return 'TEAM';
    }

    public function shouldOmitPrefix(): bool
    {
        return false;
    }
    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class,'team_user');
    }
}
