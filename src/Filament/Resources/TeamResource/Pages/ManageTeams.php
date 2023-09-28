<?php

namespace Savannabits\Saas\Filament\Resources\TeamResource\Pages;

use Coolsam\FilamentExcel\Actions\ImportField;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;
use Illuminate\Support\Str;
use Savannabits\Saas\Concerns\Filament\HasVanadiImports;
use Savannabits\Saas\Filament\Resources\TeamResource;
use function Savannabits\Saas\framework;

class ManageTeams extends ManageRecords
{
    use HasVanadiImports;
    protected static string $resource = TeamResource::class;

    protected function getHeaderActions(): array
    {
        return [
            $this->makeHeaderImportAction(),
            Actions\CreateAction::make(),
        ];
    }
    public function getImportColumns(): array
    {
        return [
            ImportField::make('short_name')->required(),
            ImportField::make('name')->required(),
            ImportField::make('is_active')->required()->mutateBeforeCreate(fn($state) => framework()->strToBool($state)),
            ImportField::make('import_id')->required(),
        ];
    }
    public function identifyForUpsertUsing(array $data): array
    {
        return ['code' => Str::of($data['short_name'])->studly()->upper()->toString()];
    }
}
