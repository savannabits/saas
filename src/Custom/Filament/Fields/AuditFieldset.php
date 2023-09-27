<?php

namespace Savannabits\Saas\Custom\Filament\Fields;

use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Placeholder;
use Illuminate\Database\Eloquent\Model;

class AuditFieldset
{
    public static function make()
    {
        return Fieldset::make('Audit Details')->schema([
            Placeholder::make('owner')->content(fn (?Model $record) => $record->owner?->name ?? '-'),
            Placeholder::make('lastModifier')->content(fn (?Model $record) => $record->lastModifier?->name ?? '-'),
            Placeholder::make('created_at')
                ->label('Created Date')
                ->content(fn (?Model $record): string => $record?->created_at?->diffForHumans() ?? '-'),

            Placeholder::make('updated_at')
                ->label('Last Modified Date')
                ->content(fn (?Model $record): string => $record?->updated_at?->diffForHumans() ?? '-'),
        ]);
    }
}
