<?php

namespace App\Filament\Admin\Resources\UserResource\Pages;

use App\Filament\Admin\Resources\UserResource;
use App\Infolists\Components\UserPayment;
use App\Livewire\Payments\ListPayments;
use Filament\Infolists\Components\Actions\Action;
use Filament\Infolists\Components\Fieldset;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\Livewire;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\View;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists\Components\ViewEntry;


class ViewUser extends ViewRecord
{
    protected static string $resource = UserResource::class;

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                TextEntry::make('name'),
                TextEntry::make('email'),
                TextEntry::make('phone_number'),
                TextEntry::make('roles.name')
                    ->formatStateUsing(fn(string $state): string => ucwords(str_replace('_', ' ', $state)))
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'super_admin' => 'primary',
                        'client'      => 'success',
                        'contractor'  => 'info',
                        default       => 'gray'
                    }),
                TextEntry::make('created_at')
                    ->label('Added Date')
                    ->placeholder('~'),
                //Fieldset::make('Payments')
                //    ->schema([
                //        Livewire::make(ListPayments::class, ['user' => $this->getRecord()])
                //            ->columnSpanFull()
                //    ])
                //    ->visible($this->getRecord()->isClient)
            ]);
    }
}
