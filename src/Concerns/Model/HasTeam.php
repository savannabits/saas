<?php

namespace Savannabits\Saas\Concerns\Model;

use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Model;
use Savannabits\Saas\Models\Team;

trait HasTeam
{
    public static function getTeamColumnName(): string
    {
        return 'team_id';
    }

    public static function bootHasTeam()
    {
        self::saving(function (Model $model) {
            $col = $model::getTeamColumnName();
            if (Filament::auth()->check() && ! $model->{$col}) {
                $model->{$col} = Filament::getTenant()?->id;
                if (! $model->{$col}) {
                    $model->{$col} = Team::first()?->id; // Attach to first company
                }
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
