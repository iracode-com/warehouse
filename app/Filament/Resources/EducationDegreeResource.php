<?php

namespace App\Filament\Resources;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Actions\EditAction;
use Filament\Support\Enums\Width;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\EducationDegreeResource\Pages\ListEducationDegrees;
use App\Filament\Resources\EducationDegreeResource\Pages;
use App\Filament\Resources\EducationDegreeResource\RelationManagers;
use App\Models\Courses\EducationDegree;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EducationDegreeResource extends Resource
{
    protected static ?string $model = EducationDegree::class;


    public static function getNavigationGroup(): ?string
    {
        return __('position.navigation.group');
    }

    public static function getNavigationLabel(): string
    {
        return __('Education Degree');
    }

    public static function getLabel(): string
    {
        return __('Education Degree');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Education Degree');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->translateLabel(),
                Textarea::make('description')
                    ->translateLabel(),
                Toggle::make('status')
                    ->required()
                    ->default(1)
                    ->translateLabel(),
            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->translateLabel()->searchable()->placeholder(__('No Data')),
                ToggleColumn::make('status')->translateLabel(),
                TextColumn::make('description')->translateLabel()->placeholder(__('No Data')),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make()->modalWidth(Width::Medium),
            ])
            ->toolbarActions([
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
            'index' => ListEducationDegrees::route('/'),
            // 'create' => Pages\CreateEducationDegree::route('/create'),
            // 'edit' => Pages\EditEducationDegree::route('/{record}/edit'),
        ];
    }
}
