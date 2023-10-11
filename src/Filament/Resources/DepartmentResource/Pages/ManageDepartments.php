<?php

namespace Savannabits\Saas\Filament\Resources\DepartmentResource\Pages;

use Coolsam\FilamentExcel\Actions\ImportField;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ManageRecords;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use pxlrbt\FilamentExcel\Columns\Column;
use Savannabits\Saas\Concerns\Filament\HasExportActions;
use Savannabits\Saas\Concerns\Filament\HasVanadiImports;
use Savannabits\Saas\Filament\Resources\DepartmentResource;
use Savannabits\Saas\Models\Department;
use Savannabits\Saas\Services\Webservice;
use function Savannabits\Saas\framework;
use function Savannabits\Saas\saas;


class ManageDepartments extends ManageRecords
{
    use HasExportActions;
    use HasVanadiImports;
    protected static string $resource = DepartmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            $this->getPageTableExportAction(),
            $this->makeHeaderImportAction()->label('Import KFS Linking Data'),
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

    public static function getExportColumns(): array
    {
        return [
            Column::make('code'),
            Column::make('short_name'),
            Column::make('name'),
            Column::make('account_number'),
            Column::make('object_code'),
        ];
    }

    public function getImportColumns(): array
    {
        return [
            ImportField::make('code')->required(),
            ImportField::make('short_name')->required(),
            ImportField::make('account_number')->required(),
            ImportField::make('object_code')->required(),
            ImportField::make('chart_code')->required(),
        ];
    }
    public function importRecord(array $data)
    {
        $data= collect($this->mutateFieldsForRecordCreation($data));
        try {
            /**
             * @var Department $model
             */
            $model = static::getModel()::whereCode($data['code'])->orWhere('short_name',$data['short_name'])->first();
            if ($model) {
                $model->update([
                    'chart_code' => Str::of($data['chart_code'])->toString(),
                    'object_code' => Str::of($data['object_code'])->padLeft(4,'0'),
                    'account_number' => Str::of($data['account_number'])->padLeft(7,'0'),
                ]);
            }
            return $model;
        } catch (\Throwable $exception) {
            Log::error($exception);
            throw $exception;
        }
    }
}
