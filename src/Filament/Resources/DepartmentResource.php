<?php

namespace Savannabits\Saas\Filament\Resources;

use Savannabits\Saas\Models\Department;
use Savannabits\Saas\SaasPlugin;
use Savannabits\Saas\Filament\Resources\DepartmentResource\Pages;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Savannabits\Saas\Custom\Filament\Columns\ActiveStatusColumn;

class DepartmentResource extends Resource
{
    protected static ?string $model = Department::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office';
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
    public static function getNavigationGroup(): ?string
    {
        return SaasPlugin::getNavigationGroupLabel();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('code')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('short_name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('sync_id')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('parent_sync_id')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Toggle::make('is_active')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->searchable()->sortable(),
                Tables\Columns\TextColumn::make('short_name')
                    ->searchable()->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable()->sortable(),
                Tables\Columns\TextColumn::make('parent.name')
                    ->searchable()->sortable(),
                Tables\Columns\TextColumn::make('hod_username')
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
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageDepartments::route('/'),
        ];
    }
}
