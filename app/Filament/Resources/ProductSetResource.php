<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductSetResource\Pages;
use App\Models\ProductSet;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\HtmlString;

class ProductSetResource extends Resource
{
    protected static ?string $model = ProductSet::class;

    public static function getNavigationIcon(): ?string
    {
        return 'heroicon-o-cube-transparent';
    }

    public static function getNavigationLabel(): string
    {
        return __('product-set.navigation_label');
    }

    public static function getModelLabel(): string
    {
        return __('product-set.model_label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('product-set.model_label_plural');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('product-set.navigation_group');
    }

    public static function getNavigationSort(): ?int
    {
        return 3;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make(__('product-set.basic_info'))
                    ->description(__('product-set.basic_info_desc'))
                    ->icon('heroicon-o-information-circle')
                    ->iconColor('primary')
                    ->columns(3)
                    ->columnSpanFull()
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label(__('product-set.name'))
                            ->required()
                            ->maxLength(255)
                            ->prefixIcon('heroicon-o-cube-transparent')
                            ->placeholder('مثال: سبد غذایی رمضان')
                            ->columnSpan(2),

                        Forms\Components\TextInput::make('code')
                            ->label(__('product-set.code'))
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255)
                            ->prefixIcon('heroicon-o-hashtag')
                            ->placeholder('مثال: SET-001')
                            ->columnSpan(1),

                        Forms\Components\Select::make('set_type')
                            ->label(__('product-set.set_type'))
                            ->options(__('product-set.set_types'))
                            ->required()
                            ->native(false)
                            ->prefixIcon('heroicon-o-tag')
                            ->default('set')
                            ->columnSpan(1),

                        Forms\Components\TextInput::make('total_quantity')
                            ->label(__('product-set.total_quantity'))
                            ->numeric()
                            ->default(1)
                            ->minValue(1)
                            ->required()
                            ->prefixIcon('heroicon-o-calculator')
                            ->suffix('عدد')
                            ->columnSpan(1),

                        Forms\Components\Select::make('unit_id')
                            ->label(__('product-set.unit'))
                            ->relationship('unit', 'name')
                            ->searchable()
                            ->preload()
                            ->prefixIcon('heroicon-o-scale')
                            ->placeholder('انتخاب واحد')
                            ->columnSpan(1),

                        Forms\Components\Toggle::make('is_active')
                            ->label(__('product-set.is_active'))
                            ->default(true)
                            ->inline(false)
                            ->columnSpan(1),

                        Forms\Components\Textarea::make('description')
                            ->label(__('product-set.description'))
                            ->rows(3)
                            ->placeholder('توضیحات تکمیلی درباره این ست/سبد...')
                            ->columnSpanFull(),
                    ]),

                Section::make(__('product-set.items_info'))
                    ->description(__('product-set.items_info_desc'))
                    ->icon('heroicon-o-shopping-cart')
                    ->iconColor('success')
                    ->columnSpanFull()
                    ->schema([
                        Forms\Components\Repeater::make('items')
                            ->relationship('items')
                            ->label(__('Items'))
                            ->schema([
                                Grid::make(12)
                                    ->schema([
                                        Forms\Components\Select::make('item_id')
                                            ->label(__('product-set.item_product'))
                                            ->relationship(
                                                'item',
                                                'serial_number',
                                                fn ($query) => $query->with('productProfile')
                                            )
                                            ->getOptionLabelFromRecordUsing(fn ($record) => ($record->productProfile->name ?? 'کالا').
                                                ' | سریال: '.($record->serial_number ?? '-').
                                                ' | موجودی: '.number_format($record->current_stock ?? 0, 0)
                                            )
                                            ->required()
                                            ->searchable(['serial_number', 'batch_number'])
                                            ->preload()
                                            ->prefixIcon('heroicon-o-cube')
                                            ->live()
                                            ->afterStateUpdated(function ($state, $set) {
                                                if ($state) {
                                                    $item = \App\Models\Item::with('productProfile')->find($state);
                                                    if ($item && $item->productProfile && $item->productProfile->unit_id) {
                                                        $set('unit_id', $item->productProfile->unit_id);
                                                    }
                                                }
                                            })
                                            ->columnSpan(3),

                                        Forms\Components\TextInput::make('quantity')
                                            ->label(__('product-set.item_quantity'))
                                            ->numeric()
                                            ->default(1)
                                            ->minValue(0.01)
                                            ->required()
                                            ->prefixIcon('heroicon-o-calculator')
                                            ->live(debounce: 500)
                                            ->afterStateUpdated(fn ($state, $get, $set) => $set('effective_display', number_format(($state ?? 0) * ($get('coefficient') ?? 1), 2))
                                            )
                                            ->columnSpan(2),

                                        Forms\Components\TextInput::make('coefficient')
                                            ->label(__('product-set.item_coefficient'))
                                            ->numeric()
                                            ->default(1)
                                            ->minValue(0.0001)
                                            ->step(0.0001)
                                            ->helperText(__('product-set.coefficient_helper'))
                                            ->required()
                                            ->prefixIcon('heroicon-o-variable')
                                            ->live(debounce: 500)
                                            ->afterStateUpdated(fn ($state, $get, $set) => $set('effective_display', number_format(($get('quantity') ?? 0) * ($state ?? 1), 2))
                                            )
                                            ->columnSpan(2),

                                        Forms\Components\Select::make('unit_id')
                                            ->label(__('product-set.item_unit'))
                                            ->relationship('unit', 'name')
                                            ->searchable()
                                            ->preload()
                                            ->prefixIcon('heroicon-o-scale')
                                            ->placeholder('واحد')
                                            ->columnSpan(3),

                                        Forms\Components\Placeholder::make('effective_display')
                                            ->label(__('product-set.effective_quantity'))
                                            ->content(function ($get, $record) {
                                                $unitName = 'واحد';
                                                if ($get('unit_id')) {
                                                    $unit = \App\Models\Unit::find($get('unit_id'));
                                                    $unitName = $unit?->name ?? 'واحد';
                                                }

                                                return new HtmlString(
                                                    '<div class="text-center p-2 rounded-lg bg-green-50 border border-green-200">
                                                        <div class="text-lg font-bold text-green-600">'.
                                                        number_format(($get('quantity') ?? 0) * ($get('coefficient') ?? 1), 2).
                                                        '</div>
                                                        <div class="text-xs text-green-700">'.$unitName.'</div>
                                                    </div>'
                                                );
                                            })
                                            ->columnSpan(2),
                                    ]),

                                Forms\Components\Textarea::make('notes')
                                    ->label(__('product-set.item_notes'))
                                    ->rows(2)
                                    ->placeholder('یادداشت‌های تکمیلی...')
                                    ->columnSpanFull(),
                            ])
                            ->defaultItems(1)
                            ->addActionLabel('➕ '.__('product-set.add_item'))
                            ->reorderable()
                            ->reorderableWithButtons()
                            ->collapsible()
                            ->collapsed(false)
                            ->itemLabel(fn (array $state): ?string => $state['item_id']
                                    ? (\App\Models\Item::with('productProfile')->find($state['item_id'])?->productProfile?->name ?? 'کالا').
                                      ' | سریال: '.(\App\Models\Item::find($state['item_id'])?->serial_number ?? '-').
                                      ' ('.number_format(($state['quantity'] ?? 0) * ($state['coefficient'] ?? 1), 2).
                                      ' '.(\App\Models\Unit::find($state['unit_id'] ?? null)?->name ?? '').')'
                                    : 'کالای جدید'
                            )
                            ->cloneable()
                            ->columnSpanFull()
                            ->minItems(1)
                            ->deleteAction(
                                fn ($action) => $action->requiresConfirmation()
                            ),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->label(__('product-set.table.code'))
                    ->searchable()
                    ->sortable()
                    ->copyable(),

                Tables\Columns\TextColumn::make('name')
                    ->label(__('product-set.table.name'))
                    ->searchable()
                    ->sortable()
                    ->limit(50),

                Tables\Columns\TextColumn::make('set_type')
                    ->label(__('product-set.table.type'))
                    ->formatStateUsing(fn ($state) => __('product-set.set_types')[$state] ?? $state)
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'set' => 'success',
                        'basket' => 'info',
                        default => 'gray',
                    })
                    ->sortable(),

                Tables\Columns\TextColumn::make('items_count')
                    ->label(__('product-set.table.items_count'))
                    ->counts('items')
                    ->badge()
                    ->color('warning')
                    ->sortable(),

                Tables\Columns\TextColumn::make('total_value')
                    ->label(__('product-set.table.total_value'))
                    ->money('IRR')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\IconColumn::make('is_active')
                    ->label(__('product-set.table.is_active'))
                    ->boolean()
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('تاریخ ایجاد')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('set_type')
                    ->label(__('product-set.set_type'))
                    ->options(__('product-set.set_types')),

                Tables\Filters\TernaryFilter::make('is_active')
                    ->label(__('product-set.is_active')),
            ])
            ->actions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
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
            'index' => Pages\ListProductSets::route('/'),
            'create' => Pages\CreateProductSet::route('/create'),
            'view' => Pages\ViewProductSet::route('/{record}'),
            'edit' => Pages\EditProductSet::route('/{record}/edit'),
            'build' => Pages\BuildProductSet::route('/{record}/build'),
        ];
    }
}
