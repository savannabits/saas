<?php

namespace Savannabits\Saas\Custom\Filament\Columns;

use Filament\Tables\Columns\TextColumn;

class CancelledStatusColumn
{
    public static function make()
    {
        return TextColumn::make('is_cancelled')
            ->label('Cancelled Status')->badge()
            ->formatStateUsing(fn ($state) => match ($state) {
                false => 'Valid Doc',
                true => 'Cancelled',
            })
            ->colors([
                'success' => false,
                'danger' => true,
            ]);
    }
}
