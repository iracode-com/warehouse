<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ActivityLocationResource\Pages;
use App\Models\ActivityLocation;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;

class ActivityLocationResource extends Resource
{
    protected static ?string $model = ActivityLocation::class;

    public static function getNavigationIcon(): ?string
    {
        return 'heroicon-o-map-pin';
    }

    public static function getNavigationLabel(): string
    {
        return __('activity-location.navigation.plural');
    }

    public static function getModelLabel(): string
    {
        return __('activity-location.navigation.singular');
    }

    public static function getPluralModelLabel(): string
    {
        return __('activity-location.navigation.plural');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('position.navigation.group');
    }

    public static function getNavigationSort(): ?int
    {
        return 3;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('activity-location.sections.basic_info'))
                    ->description(__('activity-location.sections.basic_info_desc'))
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label(__('activity-location.fields.name'))
                            ->required()
                            ->maxLength(255),
                        
                        Forms\Components\Textarea::make('description')
                            ->label(__('activity-location.fields.description'))
                            ->rows(3)
                            ->columnSpanFull(),
                        
                        Forms\Components\Toggle::make('status')
                            ->label(__('activity-location.fields.status'))
                            ->default(true)
                            ->required(),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('activity-location.table.name'))
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('description')
                    ->label(__('activity-location.table.description'))
                    ->limit(50)
                    ->searchable(),
                
                Tables\Columns\IconColumn::make('status')
                    ->label(__('activity-location.table.status'))
                    ->boolean()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('activity-location.table.created_at'))
                    ->jalaliDateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('status')
                    ->label(__('activity-location.filters.status'))
                    ->boolean()
                    ->trueLabel(__('activity-location.filters.active'))
                    ->falseLabel(__('activity-location.filters.inactive'))
                    ->native(false),
            ])
            ->actions([
                EditAction::make(),
                ViewAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListActivityLocations::route('/'),
            'create' => Pages\CreateActivityLocation::route('/create'),
            'edit' => Pages\EditActivityLocation::route('/{record}/edit'),
        ];
    }
}
