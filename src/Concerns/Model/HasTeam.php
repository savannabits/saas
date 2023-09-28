<?php

namespace Savannabits\Saas\Concerns\Model;

use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Model;
use Savannabits\Saas\Models\Team;
use function Savannabits\Saas\default_team;

trait HasTeam
{
    public static function getTeamColumnName(): string
    {
        return 'team_id';
    }

    public static function bootHasTeam()
    {
        self::creating(function (Model $model) {
            $col = $model::getTeamColumnName();
            if (!$model->team_id) {
                if (auth()->check()) {
                    $model->team_id = auth()->user()->team_id;
                } else {
                    $model->team_id = default_team()?->getAttribute('id');
                }
                $model->save();
            }
            /*if ($model->getAttribute('is_cross_team')) {
                $model->{$col} = null;
            }*/
        });
    }

    public function team()
    {
        return $this->belongsTo(Team::class, $this->getTeamColumnName());
    }

    protected function initializeHasTeam()
    {
//        $this->casts['is_cross_team'] = 'bool';
    }
}
