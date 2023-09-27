<?php

namespace Savannabits\Saas\Concerns\Filament;

use pxlrbt\FilamentExcel\Actions\Pages\ExportAction;
use pxlrbt\FilamentExcel\Columns\Column;
use Savannabits\Saas\Custom\Filament\Actions\VanadiExport;

trait HasExportActions
{
    /**
     * @return Column[]
     */
    public abstract static function getExportColumns(): array;

    public function getPageTableExportAction(): ExportAction
    {
        return VanadiExport::tableExport(static::getExportColumns())
            ->badge(static::getModel()::count())
            ->visible(static::getModel()::count() > 0)
            ->badgeColor('success')->requiresConfirmation();
    }
    public function getPageFormExportAction(): ExportAction
    {
        return VanadiExport::formExport(static::getExportColumns())
            ->badge(static::getModel()::count())
            ->visible(static::getModel()::count() > 0)
            ->badgeColor('success')->requiresConfirmation();
    }
    public function getBulkExportAction(): ExportAction
    {
        return VanadiExport::bulkExport(static::getExportColumns())
            ->badgeColor('success')->requiresConfirmation();
    }
}
