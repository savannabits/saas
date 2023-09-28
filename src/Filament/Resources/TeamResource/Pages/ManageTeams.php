<?php

namespace Savannabits\Saas\Filament\Resources\TeamResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;
use Savannabits\Saas\Filament\Resources\TeamResource;

class ManageTeams extends ManageRecords
{
    protected static string $resource = TeamResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
