<?php

namespace Savannabits\Saas\Filament\Resources\CurrencyResource\Pages;

use Filament\Actions;
use Filament\Forms\Components\Select;
use Filament\Resources\Pages\ManageRecords;
use Savannabits\Saas\Filament\Resources\CurrencyResource;
use Savannabits\Saas\Models\Currency;
use Savannabits\Saas\Vanadi;

class ManageCurrencies extends ManageRecords
{
    protected static string $resource = CurrencyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('update-exchange-rates')->label('Update Exchange Rates')
                ->requiresConfirmation()
                ->form([
                    Select::make('base_currency')->label('Set the Base Currency')
                        ->options(Currency::get()
                            ->map(fn($currency) => ['code' => $currency->code,'label' => "$currency->code - $currency->name"])->pluck('label','code')
                        )
                        ->default('KES')
                        ->searchable()
                ])
                ->action(fn(array $data) => Vanadi::updateExchangeRates($data['base_currency'] ?: 'KES'))
                ->color('success')->icon('heroicon-o-arrow-path'),
            Actions\CreateAction::make(),
        ];
    }
}
