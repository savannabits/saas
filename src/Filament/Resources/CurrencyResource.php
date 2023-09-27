<?php

namespace Savannabits\Saas\Filament\Resources;

use App\Filament\Resources\CurrencyResource\RelationManagers;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Savannabits\Saas\Custom\Filament\Columns\ActiveStatusColumn;
use Savannabits\Saas\Custom\Filament\Fields\AuditFieldset;
use Savannabits\Saas\Custom\Filament\Layouts\Sidebar;
use Savannabits\Saas\Models\Currency;
use Savannabits\Saas\SaasPlugin;
use function Filament\Support\format_money;
use function Filament\Support\format_number;

class CurrencyResource extends Resource
{
    protected static ?string $model = Currency::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getNavigationGroup(): string
    {
        return SaasPlugin::getNavigationGroupLabel();
    }
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return Sidebar::make($form)
            ->schema([
                Forms\Components\Section::make([
                    Forms\Components\TextInput::make('code')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('name')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('symbol')
                        ->maxLength(255),
                    Forms\Components\Toggle::make('is_active')
                        ->required()->default(true),
                ])
            ],[
                AuditFieldset::make()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->searchable()->sortable()->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('code')
                    ->searchable()->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable()->sortable(),
                Tables\Columns\TextColumn::make('symbol')
                    ->searchable()->searchable(),
                Tables\Columns\TextColumn::make('country.name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('exchange_rate')
                    ->formatStateUsing(fn($state, $record) => "1 {$record->exchange_base_currency} = ".number_format(floatval($state), 8)." $record->code"),
                ActiveStatusColumn::make(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => \Savannabits\Saas\Filament\Resources\CurrencyResource\Pages\ManageCurrencies::route('/'),
        ];
    }
}
