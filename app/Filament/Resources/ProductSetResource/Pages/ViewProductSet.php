<?php

namespace App\Filament\Resources\ProductSetResource\Pages;

use App\Filament\Resources\ProductSetResource;
use Filament\Actions;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ViewProductSet extends ViewRecord
{
    protected static string $resource = ProductSetResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('build')
                ->label(__('product-set.build_set'))
                ->icon('heroicon-o-wrench')
                ->color('success')
                ->url(fn () => ProductSetResource::getUrl('build', ['record' => $this->record])),
            Actions\EditAction::make(),
            Actions\DeleteAction::make(),
        ];
    }

    public function infolist(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make(__('product-set.basic_info'))
                    ->description(__('product-set.basic_info_desc'))
                    ->icon('heroicon-o-information-circle')
                    ->iconColor('primary')
                    ->columnSpanFull()
                    ->columns(4)
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextEntry::make('name')
                                    ->label(__('product-set.name'))
                                    ->size('lg')
                                    ->weight('bold')
                                    ->columnSpan(1),

                                TextEntry::make('code')
                                    ->label(__('product-set.code'))
                                    ->copyable()
                                    ->badge()
                                    ->color('success')
                                    ->columnSpan(1),
                            ])
                            ->columnSpanFull(),

                        TextEntry::make('set_type')
                            ->label(__('product-set.set_type'))
                            ->formatStateUsing(fn ($state) => __('product-set.set_types')[$state] ?? $state)
                            ->badge()
                            ->size('lg')
                            ->color(fn ($state) => match ($state) {
                                'set' => 'success',
                                'basket' => 'info',
                                default => 'gray',
                            })
                            ->columnSpan(1),

                        IconEntry::make('is_active')
                            ->label(__('product-set.is_active'))
                            ->boolean()
                            ->size('lg')
                            ->columnSpan(1),

                        TextEntry::make('total_quantity')
                            ->label(__('product-set.total_quantity'))
                            ->numeric()
                            ->icon('heroicon-o-calculator')
                            ->columnSpan(1),

                        TextEntry::make('unit')
                            ->label(__('product-set.unit'))
                            ->placeholder('-')
                            ->icon('heroicon-o-scale')
                            ->columnSpan(1),

                        TextEntry::make('total_value')
                            ->label(__('product-set.table.total_value'))
                            ->money('IRR')
                            ->icon('heroicon-o-banknotes')
                            ->columnSpan(2),

                        TextEntry::make('description')
                            ->label(__('product-set.description'))
                            ->placeholder('بدون توضیحات')
                            ->columnSpanFull(),
                    ]),

                Section::make(__('product-set.items_info'))
                    ->description('کالاهای تشکیل دهنده این '.__('product-set.set_types')[$this->record->set_type] ?? 'ست')
                    ->icon('heroicon-o-shopping-cart')
                    ->iconColor('success')
                    ->headerActions([
                        \Filament\Actions\Action::make('view_items_count')
                            ->label($this->record->items->count().' کالا')
                            ->badge()
                            ->color('warning')
                            ->disabled(),
                    ])
                    ->columnSpanFull()
                    ->schema($this->getItemsSchema()),
            ]);
    }

    protected function getItemsSchema(): array
    {
        $items = $this->record->items;

        if ($items->isEmpty()) {
            return [
                Section::make()
                    ->schema([
                        TextEntry::make('no_items')
                            ->label('')
                            ->default('⚠️ '.__('product-set.no_items'))
                            ->color('warning')
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull(),
            ];
        }

        $schema = [];
        foreach ($items as $index => $setItem) {
            $effectiveQty = $setItem->quantity * $setItem->coefficient;
            $itemLabel = ($setItem->item->productProfile->name ?? 'کالا').
                         ' | سریال: '.($setItem->item->serial_number ?? '-');

            $schema[] = Section::make()
                ->heading($itemLabel)
                ->description($setItem->notes ?? null)
                ->icon('heroicon-o-cube')
                ->iconColor('primary')
                ->badge('موجودی: '.number_format($setItem->item->current_stock ?? 0, 0))
                ->collapsible()
                ->collapsed($index > 2) // اولین 3 آیتم باز باشند
                ->columns(6)
                ->schema([
                    TextEntry::make("quantity_{$index}")
                        ->label(__('product-set.item_quantity'))
                        ->default($setItem->quantity)
                        ->numeric()
                        ->suffix($setItem->unit ?? '')
                        ->icon('heroicon-o-calculator')
                        ->color('info')
                        ->columnSpan(1),

                    TextEntry::make("coefficient_{$index}")
                        ->label(__('product-set.item_coefficient'))
                        ->default($setItem->coefficient)
                        ->numeric(decimalPlaces: 4)
                        ->icon('heroicon-o-variable')
                        ->color('warning')
                        ->columnSpan(1),

                    TextEntry::make("effective_{$index}")
                        ->label(__('product-set.effective_quantity'))
                        ->default($effectiveQty)
                        ->numeric(decimalPlaces: 2)
                        ->suffix($setItem->unit ?? '')
                        ->icon('heroicon-o-check-circle')
                        ->badge()
                        ->color('success')
                        ->size('lg')
                        ->weight('bold')
                        ->columnSpan(2),

                    TextEntry::make("unit_price_{$index}")
                        ->label('قیمت واحد')
                        ->default($setItem->item->unit_price ?? 0)
                        ->money('IRR')
                        ->icon('heroicon-o-currency-dollar')
                        ->columnSpan(1),

                    TextEntry::make("total_price_{$index}")
                        ->label('قیمت کل')
                        ->default(($setItem->item->unit_price ?? 0) * $effectiveQty)
                        ->money('IRR')
                        ->badge()
                        ->color('success')
                        ->icon('heroicon-o-banknotes')
                        ->columnSpan(1),
                ])
                ->columnSpanFull();
        }

        return $schema;
    }
}
