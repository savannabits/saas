<?php

namespace Savannabits\Saas\Custom\Filament\Actions;

use pxlrbt\FilamentExcel\Actions\Pages\ExportAction;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use pxlrbt\FilamentExcel\Columns\Column;
use pxlrbt\FilamentExcel\Exports\ExcelExport;

class VanadiExport
{
    /**
     * @param Column[]|null $columns
     * @return ExportAction
     */
    public static function tableExport(?array $columns = []): ExportAction
    {
        $export = ExcelExport::make('table')->fromTable();
        if ($columns && count($columns)) $export->withColumns($columns);
        return ExportAction::make('export')->label('Export')->exports([
            $export,
        ]);
    }

    /**
     * @param Column[]|null $columns
     * @return ExportAction
     */
    public static function formExport(?array $columns = []): ExportAction
    {
        $export = ExcelExport::make('form')->fromForm();
        if ($columns && count($columns)) $export->withColumns($columns);
        return ExportAction::make('export')->label('Export')->exports([
            $export,
        ]);
    }
    public static function bulkExport(?array $columns = []) {
        /**
         * @param Column[]|null $columns
         * @return ExportBulkAction
         */
        $export = ExcelExport::make('table')->fromTable();
        if ($columns && count($columns)) $export->withColumns($columns);
        return ExportBulkAction::make('export-bulk-records')->exports([
            $export
        ])->label('Export Selected');
    }

}
