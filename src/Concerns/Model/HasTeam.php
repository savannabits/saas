<?php

namespace Savannabits\Saas\Concerns\Model;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Schema;
use Savannabits\Saas\Models\Team;
use Illuminate\Database\Eloquent\Model;
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

        // Add scope
        if (auth()->check()) {
            static::addGlobalScope('team', function (Builder $query) {
                if (in_array($query->getModel()->getMorphClass(), static::getSharedModels())) {
                    return;
                }
                if (Schema::hasColumn($query->getModel()->getTable(),'team_id')) {
                    $query->whereBelongsTo(auth()->user()->team)->orWhereNull('team_id')
                        ->orWhere('team_id','=', default_team()?->id);
                }
//                $query->where('team_id', auth()->user()->team_id);
                // or with a `team` relationship defined:
            });
        }
    }

    public function team()
    {
        return $this->belongsTo(Team::class, $this->getTeamColumnName());
    }

    protected function initializeHasTeam()
    {
//        $this->casts['is_cross_team'] = 'bool';
    }

    protected static function getSharedModels() {
        return config('saas.shared_models',[]);
    }
}
