<?php

namespace App\Filament\Resources\Reports;

use App\Filament\Resources\Users\Pages\EditUser;
use Filament\Facades\Filament;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\HtmlString;
use App\Models\Reporting\Report;
use Filament\Forms;
use Filament\Support\Icons\Heroicon;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;

// use function App\Support\formComponentsConfiguration;

class ReportResource extends Resource
{
    protected static ?string $model = Report::class;
    // protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentText;


    public static function getNavigationGroup(): ?string
    {
        return __('Reporting Subsystem');
    }

    public static function getNavigationLabel(): string
    {
        return __('Reporting');
    }

    public static function getLabel(): ?string
    {
        return __('Reporting');
    }

    public static function getPluralLabel(): ?string
    {
        return __('Reporting');
    }

    public static function canAccess(): bool
    {
        return true;
    }

    public static function form(Schema $form): Schema
    {
        formComponentsConfiguration();
        return $form
            ->columns(1)
            ->schema([
                Forms\Components\Select::make('reportable_resource')
                    ->hint(new HtmlString(Blade::render('<x-filament::loading-indicator wire:loading class="h-5 w-5"/>')))
                    ->label(__('Module'))
                    ->afterStateUpdated(fn(Set $set, $state) => $state ? $set('reportable_type', app($state)::getModel()) : null)
                    ->searchable()
                    ->preload()
                    ->reactive()
                    ->options(function () {
                        $models = Arr::map(Filament::getResources(), fn($resource) => Arr::prepend([], app($resource)::getModel()));
                        $models = Arr::flatten($models);
                        $names = Arr::map($models, fn($model) => __(pascalToTitle(str($model)->afterLast('\\')->value())));
                        return array_combine(Filament::getResources(), $names);
                    }),

                Forms\Components\TextInput::make('title')->label(__("Title"))->required(),
                Forms\Components\Textarea::make('description')->label(__("Description"))->columnSpanFull(),
                Forms\Components\Hidden::make('reportable_type')->required(),
                Forms\Components\Hidden::make('created_by')->formatStateUsing(fn(?Model $record) => $record->created_by ?? auth()->id()),
                Forms\Components\Hidden::make('updated_by')->formatStateUsing(fn() => auth()->id()),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')->label(__("Title"))->searchable(),
                Tables\Columns\TextColumn::make('reportable_type')
                    ->label(__('Module'))
                    ->getStateUsing(function ($record) {
                        return __(pascalToTitle(__(str($record->reportable_type)->afterLast('\\')->value())));
                    }),
                Tables\Columns\TextColumn::make('description')->label(__("Description"))->searchable(),
                Tables\Columns\TextColumn::make('createdBy.name')->label(__('Creator'))
                    ->icon(Heroicon::OutlinedUser)
                    ->iconColor('primary')
                    ->sortable()
                    ->weight('bold')
                    ->color('primary'),
                Tables\Columns\TextColumn::make('updatedBy.name')->label(__('Updater'))
                    ->icon(Heroicon::OutlinedUser)
                    ->iconColor('primary')
                    ->sortable()
                    ->weight('bold')
                    ->color('primary'),
                Tables\Columns\TextColumn::make('created_at')->label(__('Created at'))->jalaliDateTime()->sortable()->toggleable(),
                Tables\Columns\TextColumn::make('updated_at')->label(__('Updated at'))->jalaliDateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deleted_at')->label(__('Deleted at'))->jalaliDateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->recordActions([
                Action::make('reporting')
                    ->color('success')
                    ->icon('heroicon-o-document-chart-bar')
                    ->label(__('Reporting'))
                    ->url(fn(?Model $record) => static::getUrl('reporting', ['record' => $record])),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListReports::route('/'),
            'reporting' => Pages\Reporting::route('/{record}/reporting'),
        ];
    }
}
