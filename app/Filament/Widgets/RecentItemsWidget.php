<?php

namespace App\Filament\Widgets;

use App\Models\Item;
use Filament\Actions\Action;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class RecentItemsWidget extends BaseWidget
{
    protected int | string | array $columnSpan = 'full';
    
    public function getHeading(): string
    {
        return 'آخرین اقلام اضافه شده';
    }
    
    public function table(Table $table): Table
    {
        return $table
            ->query(
                Item::query()
                    ->with(['productProfile', 'warehouse'])
                    ->latest()
                    ->limit(10)
            )
            ->columns([
                Tables\Columns\TextColumn::make('productProfile.name')
                    ->label('نام محصول')
                    ->searchable()
                    ->sortable()
                    ->limit(30),
                    
                Tables\Columns\TextColumn::make('productProfile.sku')
                    ->label('کد کالا')
                    ->searchable()
                    ->sortable()
                    ->copyable(),
                    
                Tables\Columns\TextColumn::make('serial_number')
                    ->label('شماره سریال')
                    ->searchable()
                    ->limit(20),
                    
                Tables\Columns\TextColumn::make('current_stock')
                    ->label('موجودی فعلی')
                    ->numeric()
                    ->sortable()
                    ->badge()
                    ->color(fn (int $state): string => match (true) {
                        $state <= 10 => 'danger',
                        $state <= 50 => 'warning',
                        default => 'success',
                    }),
                    
                Tables\Columns\TextColumn::make('warehouse.name')
                    ->label('انبار')
                    ->searchable()
                    ->sortable()
                    ->limit(20),
                    
                Tables\Columns\TextColumn::make('status')
                    ->label('وضعیت')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'active' => 'success',
                        'inactive' => 'danger',
                        'discontinued' => 'warning',
                        'recalled' => 'danger',
                    }),
                    
                Tables\Columns\TextColumn::make('created_at')
                    ->label('تاریخ ایجاد')
                    ->dateTime('Y-m-d H:i')
                    ->sortable(),
            ])
            ->actions([
                Action::make('view')
                    ->label('مشاهده')
                    ->icon('heroicon-m-eye')
                    ->url(fn (Item $record): string => route('filament.admin.resources.items.view', $record))
                    ->openUrlInNewTab(),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
