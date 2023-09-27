<?php

namespace Savannabits\Saas\Filament\Resources;

use App\Filament\Resources\CountryResource\RelationManagers;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\HtmlString;
use Savannabits\Saas\Custom\Filament\Columns\ActiveStatusColumn;
use Savannabits\Saas\Custom\Filament\Fields\AuditFieldset;
use Savannabits\Saas\Custom\Filament\Layouts\Sidebar;
use Savannabits\Saas\Models\Country;
use Savannabits\Saas\SaasPlugin;

class CountryResource extends Resource
{
    protected static ?string $model = Country::class;

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
                        ->maxLength(3)->live()->afterStateUpdated(function ($state, Forms\Set $set) {
                            $set('cca3', $state);
                        }),
                    Forms\Components\TextInput::make('name')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('cca2')
                        ->maxLength(255),
                    Forms\Components\TextInput::make('cca3')
                        ->maxLength(255)->live()->afterStateUpdated(function ($state, Forms\Set $set) {
                            $set('code', $state);
                        }),
                    Forms\Components\TextInput::make('currency_code')
                        ->maxLength(255),
                    Forms\Components\TextInput::make('capital')
                        ->maxLength(255),
                    Forms\Components\Toggle::make('is_active')
                        ->required(),
                ])->columns()
            ],[
                Forms\Components\Placeholder::make('flag_url')
                    ->label("Flag")
                    ->columnSpanFull()
                    ->content(fn($state, $record) => new HtmlString("<img src='{$record->flag_url}' alt='{$record->code}'>")),
                AuditFieldset::make()->columns(1)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('flag_url')
                    ->extraImgAttributes(['width' => '80','height' => '80'])
                    ->label('Flag'),
                Tables\Columns\TextColumn::make('code')
                    ->searchable(),
                Tables\Columns\TextColumn::make('cca2')
                    ->searchable()->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('cca3')
                    ->searchable()->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('currency_code')
                    ->searchable(),
                Tables\Columns\TextColumn::make('capital')
                    ->searchable(),
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
            'index' => \Savannabits\Saas\Filament\Resources\CountryResource\Pages\ManageCountries::route('/'),
        ];
    }
}
