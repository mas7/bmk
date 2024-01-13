<?php

namespace App\Filament\Admin\Resources\ContractorResource\Pages;

use App\Enums\ContractorStatus;
use App\Filament\Admin\Resources\ContractorResource;
use App\Livewire\TicketPayments\ListTicketPayments;
use Filament\Actions;
use Filament\Infolists\Components\Fieldset;
use Filament\Infolists\Components\Livewire;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;

class ViewContractor extends ViewRecord
{
    protected static string $resource = ContractorResource::class;

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                TextEntry::make('user.name'),
                TextEntry::make('user.email'),
                TextEntry::make('user.phone_number'),
                TextEntry::make('status')
                    ->badge()
                    ->color(fn(ContractorStatus $state): string => match ($state) {
                        ContractorStatus::ACTIVE   => 'success',
                        ContractorStatus::INACTIVE => 'danger'
                    }),
                TextEntry::make('serviceCategories.name')
                    ->label('Service Category')
                    ->badge()
                    ->color(fn(string $state): string => 'warning'),
                Fieldset::make('Payments')
                    ->schema([
                        Livewire::make(ListTicketPayments::class, ['contractor' => $this->getRecord()])
                            ->columnSpanFull()
                    ])
            ]);
    }
}
