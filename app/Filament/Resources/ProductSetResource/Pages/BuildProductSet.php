<?php

namespace App\Filament\Resources\ProductSetResource\Pages;

use App\Filament\Resources\ProductSetResource;
use App\Models\Item;
use App\Models\Warehouse;
use Filament\Actions;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\DB;

class BuildProductSet extends Page
{
    protected static string $resource = ProductSetResource::class;

    public ?array $data = [];

    public function getView(): string
    {
        return 'filament.resources.product-set-resource.pages.build-product-set';
    }

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('🔧 '.__('product-set.build_info'))
                    ->description(__('product-set.build_info_desc'))
                    ->icon('heroicon-o-wrench')
                    ->iconColor('primary')
                    ->columnSpanFull()
                    ->columns(3)
                    ->schema([
                        Forms\Components\Select::make('warehouse_id')
                            ->label(__('product-set.warehouse_id'))
                            ->options(Warehouse::pluck('title', 'id'))
                            ->required()
                            ->live()
                            ->searchable()
                            ->prefixIcon('heroicon-o-building-office-2')
                            ->afterStateUpdated(fn () => $this->checkAvailability())
                            ->columnSpan(1),

                        Forms\Components\TextInput::make('quantity_to_build')
                            ->label(__('product-set.quantity_to_build'))
                            ->numeric()
                            ->default(1)
                            ->minValue(1)
                            ->required()
                            ->live()
                            ->prefixIcon('heroicon-o-calculator')
                            ->suffix('عدد')
                            ->afterStateUpdated(fn () => $this->checkAvailability())
                            ->columnSpan(1),

                        Forms\Components\Placeholder::make('availability_status')
                            ->label('')
                            ->content(fn () => $this->getAvailabilityStatusHtml())
                            ->columnSpan(1),
                    ]),

                Section::make('📦 '.__('product-set.items_info'))
                    ->description('بررسی دقیق موجودی هر کالا در انبار انتخابی')
                    ->icon('heroicon-o-shopping-cart')
                    ->iconColor('success')
                    ->columnSpanFull()
                    ->collapsed(false)
                    ->schema($this->getItemsFormSchema()),
            ])
            ->statePath('data');
    }

    protected function getItemsFormSchema(): array
    {
        if ($this->record->items->isEmpty()) {
            return [
                Forms\Components\Placeholder::make('no_items')
                    ->content('⚠️ این ست هیچ کالایی ندارد')
                    ->columnSpanFull(),
            ];
        }

        $schema = [];
        foreach ($this->record->items as $index => $setItem) {
            $itemLabel = ($setItem->item->productProfile->name ?? 'کالا').
                         ' | سریال: '.($setItem->item->serial_number ?? '-');

            $schema[] = Section::make($itemLabel)
                ->description('موجودی: '.number_format($setItem->item->current_stock ?? 0, 0))
                ->icon('heroicon-o-cube')
                ->badge('کالا '.($index + 1))
                ->collapsible()
                ->collapsed($index > 2)
                ->columns(4)
                ->schema([
                    Forms\Components\Placeholder::make("quantity_{$index}")
                        ->label('تعداد در ست')
                        ->content(fn () => '<div class="text-lg font-bold text-blue-600">'.
                            number_format($setItem->quantity, 2).' × '.
                            number_format($setItem->coefficient, 4).' = '.
                            '<span class="text-green-600">'.number_format($setItem->quantity * $setItem->coefficient, 2).'</span>'.
                            '</div>')
                        ->columnSpan(2),

                    Forms\Components\Placeholder::make("required_{$index}")
                        ->label('مورد نیاز کل')
                        ->content(fn () => $this->getRequiredQuantityHtml($setItem))
                        ->columnSpan(1),

                    Forms\Components\Placeholder::make("available_{$index}")
                        ->label('موجودی انبار')
                        ->content(fn () => $this->getAvailableQuantityHtml($setItem))
                        ->columnSpan(1),

                    Forms\Components\Select::make("expiry_{$index}")
                        ->label('انتخاب تاریخ انقضاء')
                        ->multiple()
                        ->options(fn () => $this->getExpiryDateOptionsForItem($setItem))
                        ->helperText('اقلام با تاریخ انقضاء نزدیک‌تر در اولویت هستند')
                        ->prefixIcon('heroicon-o-calendar')
                        ->columnSpanFull(),
                ])
                ->columnSpanFull();
        }

        return $schema;
    }

    protected function getAvailabilityStatusHtml(): string
    {
        $warehouseId = $this->data['warehouse_id'] ?? null;
        $quantity = $this->data['quantity_to_build'] ?? 1;

        if (! $warehouseId) {
            return '<div class="flex items-center gap-2 p-3 rounded-lg bg-yellow-50 border border-yellow-200">
                <svg class="w-5 h-5 text-yellow-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                <span class="font-medium text-yellow-800">لطفاً انبار را انتخاب کنید</span>
            </div>';
        }

        if ($this->record->canBuildFromWarehouse($warehouseId, $quantity)) {
            return '<div class="flex items-center gap-2 p-3 rounded-lg bg-green-50 border border-green-200">
                <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                <span class="font-bold text-green-800">✓ موجودی کافی است - آماده ساخت!</span>
            </div>';
        }

        return '<div class="flex items-center gap-2 p-3 rounded-lg bg-red-50 border border-red-200">
            <svg class="w-5 h-5 text-red-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>
            <span class="font-bold text-red-800">✗ موجودی کافی نیست</span>
        </div>';
    }

    protected function getRequiredQuantityHtml($setItem): string
    {
        $quantity = $this->data['quantity_to_build'] ?? 1;
        $required = $setItem->quantity * $setItem->coefficient * $quantity;

        return '<div class="text-center p-2 rounded-lg bg-orange-50 border border-orange-200">
            <div class="text-2xl font-bold text-orange-600">'.number_format($required, 2).'</div>
            <div class="text-xs text-orange-700">'.($setItem->unit ?? 'واحد').'</div>
        </div>';
    }

    protected function getAvailableQuantityHtml($setItem): string
    {
        $warehouseId = $this->data['warehouse_id'] ?? null;

        if (! $warehouseId) {
            return '<div class="text-center p-2 rounded-lg bg-gray-100">
                <div class="text-sm text-gray-500">-</div>
            </div>';
        }

        $available = $setItem->item->current_stock ?? 0;

        $quantity = $this->data['quantity_to_build'] ?? 1;
        $required = $setItem->quantity * $setItem->coefficient * $quantity;
        $isEnough = $available >= $required;
        $color = $isEnough ? 'green' : 'red';

        return '<div class="text-center p-2 rounded-lg bg-'.$color.'-50 border border-'.$color.'-200">
            <div class="text-2xl font-bold text-'.$color.'-600">'.number_format($available, 2).'</div>
            <div class="text-xs text-'.$color.'-700">'.($setItem->unit ?? 'واحد').'</div>
        </div>';
    }

    protected function getExpiryDateOptionsForItem($setItem): array
    {
        $warehouseId = $this->data['warehouse_id'] ?? null;

        if (! $warehouseId) {
            return [];
        }

        if (! $setItem->item || ! $setItem->item->product_profile_id) {
            return [];
        }

        $items = Item::where('warehouse_id', $warehouseId)
            ->where('product_profile_id', $setItem->item->product_profile_id)
            ->whereNotNull('expiry_date')
            ->where('current_stock', '>', 0)
            ->orderBy('expiry_date', 'asc')
            ->get();

        return $items->mapWithKeys(function ($inventoryItem) {
            $label = '📅 '.($inventoryItem->expiry_date ? $inventoryItem->expiry_date->format('Y/m/d') : 'ندارد');
            $label .= ' | 📦 موجودی: '.number_format($inventoryItem->current_stock, 2);
            if ($inventoryItem->batch_number) {
                $label .= ' | 🏷️ بچ: '.$inventoryItem->batch_number;
            }

            return [$inventoryItem->id => $label];
        })->toArray();
    }

    protected function getDefaultItems(): array
    {
        return $this->record->items->map(function ($setItem) {
            return [
                'item_id' => $setItem->item_id,
                'quantity' => $setItem->quantity,
                'coefficient' => $setItem->coefficient,
            ];
        })->toArray();
    }

    protected function checkAvailability(): void
    {
        // This will trigger re-rendering
    }

    public function build(): void
    {
        $data = $this->form->getState();

        if (! $this->record->canBuildFromWarehouse($data['warehouse_id'], $data['quantity_to_build'])) {
            Notification::make()
                ->title(__('product-set.insufficient_inventory'))
                ->danger()
                ->send();

            return;
        }

        try {
            DB::beginTransaction();

            foreach ($this->record->items as $item) {
                $requiredQuantity = $item->quantity * $item->coefficient * $data['quantity_to_build'];

                // Deduct from inventory using FIFO (First Expiry First Out)
                $this->deductFromInventory(
                    $data['warehouse_id'],
                    $item->product_profile_id,
                    $requiredQuantity
                );
            }

            // Create the set item in inventory
            Item::create([
                'warehouse_id' => $data['warehouse_id'],
                'product_profile_id' => $this->record->product_profile_id,
                'current_stock' => $data['quantity_to_build'],
                'is_active' => true,
            ]);

            DB::commit();

            Notification::make()
                ->title(__('product-set.built_successfully'))
                ->success()
                ->send();

            $this->redirect(ProductSetResource::getUrl('index'));

        } catch (\Exception $e) {
            DB::rollBack();

            Notification::make()
                ->title('خطا در ساخت ست')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }

    protected function deductFromInventory(int $warehouseId, int $productId, float $quantity): void
    {
        $items = Item::where('warehouse_id', $warehouseId)
            ->where('product_profile_id', $productId)
            ->where('current_stock', '>', 0)
            ->orderBy('expiry_date', 'asc')
            ->get();

        $remaining = $quantity;

        foreach ($items as $item) {
            if ($remaining <= 0) {
                break;
            }

            $toDeduct = min($item->current_stock, $remaining);
            $item->current_stock -= $toDeduct;
            $item->save();

            $remaining -= $toDeduct;
        }
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('build')
                ->label(__('product-set.build_set'))
                ->icon('heroicon-o-wrench')
                ->color('success')
                ->action('build')
                ->requiresConfirmation(),
        ];
    }
}
