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
use App\Filament\Resources\ExpertFieldResource\Pages\ListExpertFields;
use App\Filament\Resources\ExpertFieldResource\Pages\CreateExpertField;
use App\Filament\Resources\ExpertFieldResource\Pages\EditExpertField;
use App\Filament\Resources\ExpertFieldResource\Pages;
use App\Filament\Resources\ExpertFieldResource\RelationManagers;
use App\Models\Personnel\ExpertField;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ExpertFieldResource extends Resource
{
    protected static ?string $model = ExpertField::class;

    public static function getNavigationGroup(): ?string
    {
        return __('Base information subsystem');
    }

    public static function getNavigationGroupIcon(): ?string
    {
        return 'heroicon-o-information-circle';
    }

    public static function getNavigationLabel(): string
    {
        return __('Expert Field');
    }

    public static function getLabel(): string
    {
        return __('Expert Field');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Expert Field');
    }

    public static function canAccess(): bool
    {
        return false;
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
            ]);
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
            'index' => ListExpertFields::route('/'),
            'create' => CreateExpertField::route('/create'),
            'edit' => EditExpertField::route('/{record}/edit'),
        ];
    }
}
