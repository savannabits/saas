<?php

namespace Savannabits\Saas\Filament\Resources\UserResource\Pages;

ini_set('max_execution_time', -1);

use Artisan;
use Filament\Actions;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Savannabits\Saas\Filament\Resources\UserResource;
use Savannabits\Saas\Services\Users;

class ListUsers extends ListRecords
{

    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('sync-user')->label('Sync from AD/PnC')
                ->form([
                    TextInput::make('username')->label('Staff Username/Student No.')->required(),
                ])->color('success')
                ->action(fn ($data) => $this->syncUser($data))->icon('heroicon-o-arrow-path-rounded-square'),
            /*Actions\Action::make('import-staff')->label('Import Staff from AD')
                ->icon('heroicon-o-arrow-path')->action('importStaff'),
            Actions\Action::make('import-students')->label('Import Students from AD')
                ->icon('heroicon-o-arrow-path')->action('importStudents'),*/
            Actions\CreateAction::make(),
        ];
    }

    public function importStaff(): \Throwable | \Exception | int
    {
        try {
            $res = Artisan::call('ldap:import', [
                'provider' => 'staff',
                '--no-interaction',
                '--restore' => true,
                '--delete' => true,
                '--chunk' => 500,
            ]);
            Notification::make('import-success')
                ->success()
                ->title('Import Queued')
                ->body('The import has been queued successfully')
                ->persistent()
                ->send();
        } catch (\Throwable $exception) {
            $res = $exception;
            \Log::error($exception);
            Notification::make('import-failed')
                ->danger()
                ->title('Import Failed')
                ->body('The following error occurred during import: ' . $exception->getMessage())
                ->persistent()
                ->send();
        } finally {
            return $res;
        }
    }

    public function importStudents(): void
    {
        try {
            Artisan::call('ldap:import', [
                'provider' => 'students',
                '--no-interaction',
                '--restore' => true,
                '--delete' => true,
                '--chunk' => 500,
            ]);
            Notification::make('import-success')
                ->success()
                ->title('Import Queued')
                ->body('The import has been queued successfully')
                ->persistent()
                ->send();
        } catch (\Throwable $exception) {
            \Log::error($exception);
            Notification::make('import-failed')
                ->danger()
                ->title('Import Failed')
                ->body('The following error occurred during import: ' . $exception->getMessage())
                ->persistent()
                ->send();
        }
    }

    public function syncUser(array $data, ?bool $skipLdapImport = false): void
    {
        $username = trim($data['username']);
        $provider = is_numeric($username) ? 'students' : 'staff';

        try {
            $res = Users::make()->syncUser($username, $provider, $skipLdapImport);
            Notification::make('import-success')
                ->success()
                ->title('Import Successful')
                ->body("$username has been synchronized with the $provider repository")
                ->persistent()
                ->send();
        } catch (\Throwable $exception) {
            \Log::error($exception);
            Notification::make('import-failed')
                ->danger()
                ->title('Import Failed')
                ->body($exception->getMessage())
                ->persistent()
                ->send();
        }
    }
}
