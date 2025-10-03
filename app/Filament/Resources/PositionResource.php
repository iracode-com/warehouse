<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PositionResource\Pages;
use App\Models\Organization\Position;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;

class PositionResource extends Resource
{
    protected static ?string $model = Position::class;

    public static function getNavigationGroup(): ?string
    {
        return __('position.navigation.group');
    }

    public static function getNavigationLabel(): string
    {
        return __('position.navigation.plural');
    }

    public static function getModelLabel(): string
    {
        return __('position.navigation.singular');
    }

    public static function getPluralModelLabel(): string
    {
        return __('position.navigation.plural');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Forms\Components\TextInput::make('name')
                    ->label(__('position.fields.name'))
                    ->required()
                    ->maxLength(255),
                
                Forms\Components\Textarea::make('description')
                    ->label(__('position.fields.description'))
                    ->rows(3)
                    ->columnSpanFull(),
                
                Forms\Components\Toggle::make('status')
                    ->label(__('position.fields.status'))
                    ->default(true)
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('position.table.name'))
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('description')
                    ->label(__('position.table.description'))
                    ->limit(50)
                    ->searchable(),
                
                Tables\Columns\IconColumn::make('status')
                    ->label(__('position.table.status'))
                    ->boolean()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('position.table.created_at'))
                    ->jalaliDateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('status')
                    ->label(__('position.filters.status'))
                    ->boolean()
                    ->trueLabel(__('position.filters.active'))
                    ->falseLabel(__('position.filters.inactive'))
                    ->native(false),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPositions::route('/'),
            'create' => Pages\CreatePosition::route('/create'),
            'edit' => Pages\EditPosition::route('/{record}/edit'),
        ];
    }
}
