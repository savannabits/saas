<?php

namespace Savannabits\Saas\Concerns\Filament;

use Coolsam\FilamentExcel\Actions\ImportAction;
use Coolsam\FilamentExcel\Actions\ImportField;

trait HasVanadiImports
{
    protected function makeHeaderImportAction(?\Closure $createRecordUsing = null) {
        $action = ImportAction::make('import')->fields($this->getImportFields());
        if ($createRecordUsing) {
            $action->createRecordUsing($createRecordUsing);
        } else {
            $action->createRecordUsing(fn($data) => $this->importRecord($data));
        }
        return $action;
    }

    /**
     * @deprecated use getImportFields() instead
     */
    /**
     * @return ImportField[]|array
     */
    public abstract function getImportColumns(): array;

    public function getImportFields(): array
    {
        return $this->getImportColumns();
    }

    public function enableUpserts(): bool
    {
        return true;
    }

    public function mutateFieldsForRecordCreation(array $data): array
    {
        return [];
    }

    public function identifyForUpsertUsing(array $data): array
    {
        return ['code' => $data['code']];
    }
    public function importRecord(array $data)
    {
        $args = collect([
            ...$data,
            ...$this->mutateFieldsForRecordCreation($data)
        ]);
        $identifiers = collect($this->identifyForUpsertUsing($data));
        return $this->enableUpserts()
            ? static::getModel()::updateOrCreate($identifiers->toArray(), $args->except($identifiers->keys()->toArray())->toArray())
            : static::getModel()::firstOrCreate($identifiers->toArray(), $args->except($identifiers->keys()->toArray())->toArray());
    }
}
