<?php

namespace Savannabits\Saas\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Savannabits\Saas\Concerns\Model\HasTeam;

class Permission extends \Spatie\Permission\Models\Permission
{
    use HasUuids;
}
