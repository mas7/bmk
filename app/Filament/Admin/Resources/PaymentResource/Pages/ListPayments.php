<?php

namespace App\Filament\Admin\Resources\PaymentResource\Pages;

use App\Enums\PaymentStatus;
use App\Filament\Admin\Resources\PaymentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use pxlrbt\FilamentExcel\Actions\Pages\ExportAction;
use pxlrbt\FilamentExcel\Columns\Column;
use pxlrbt\FilamentExcel\Exports\ExcelExport;

class ListPayments extends ListRecords
{
    protected static string $resource = PaymentResource::class;

    protected function getTableQuery(): Builder
    {
        return parent::getTableQuery()->parents()->withoutGlobalScopes();
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            ExportAction::make()->exports([
                ExcelExport::make('table')
                    ->askForFilename()
                    ->fromTable()
                    ->withColumns([
                        Column::make('status')
                            ->formatStateUsing(fn(PaymentStatus $state): string => match ($state) {
                                PaymentStatus::PAID   => PaymentStatus::PAID->name,
                                PaymentStatus::UNPAID => PaymentStatus::UNPAID->name,
                                default               => '~'
                            }),
                    ]),
            ])
        ];
    }
}
