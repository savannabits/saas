<?php

namespace Savannabits\Saas\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Kalnoy\Nestedset\NodeTrait;
use Savannabits\Saas\Concerns\Model\HasAuditColumns;
use Savannabits\Saas\Concerns\Model\HasCodeFactory;
use Savannabits\Saas\Concerns\Model\HasDocStatus;
use Savannabits\Saas\Concerns\Model\HasTeam;

class Department extends Model
{
    use HasAuditColumns;
    use HasCodeFactory;
    use HasDocStatus;
    use HasUuids;
    use HasTeam;
    use NodeTrait;

    protected $guarded = ['id'];

    public function shouldOmitPrefix(): bool
    {
        return false;
    }
    public function users(): HasMany
    {
        return $this->hasMany("App\Models\User",'department_short_name','short_name');
    }
}
