<?php

namespace Savannabits\Saas\Filament\Resources\DepartmentResource\Pages;

use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ManageRecords;
use Savannabits\Saas\Filament\Resources\DepartmentResource;
use Savannabits\Saas\Models\Department;
use Savannabits\Saas\Services\Webservice;
use function Savannabits\Saas\framework;
use function Savannabits\Saas\saas;


class ManageDepartments extends ManageRecords
{
    protected static string $resource = DepartmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('synchronize')->label('Sync from PnC')
                ->requiresConfirmation()
                ->color('success')
                ->icon('heroicon-o-arrow-path-rounded-square')->action(fn() => $this->synchronize()),
            Actions\CreateAction::make(),
        ];
    }
    public function synchronize()
    {
        try {
            $records = saas()->synchronizeDepartments();
            Notification::make('success')
                ->title('Sync Successful')
                ->body("The synchronization has been completed successfully. ".$records->count()." records synchronized.")
                ->success()
                ->send();
        } catch (\Throwable $exception) {
            Notification::make('error')
                ->body($exception->getMessage() ?: 'Http Error')
                ->title('Synchronization Error')
                ->danger()
                ->send();
        }
    }
}
