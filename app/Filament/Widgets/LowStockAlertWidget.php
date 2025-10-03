<?php

namespace App\Filament\Widgets;

use App\Models\Item;
use Filament\Actions\Action;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class LowStockAlertWidget extends BaseWidget
{
    protected int | string | array $columnSpan = 'full';
    
    public function getHeading(): string
    {
        return 'هشدار موجودی کم';
    }
    
    public function table(Table $table): Table
    {
        return $table
            ->query(
                Item::query()
                    ->with(['productProfile', 'warehouse'])
                    ->whereColumn('current_stock', '<=', 'min_stock')
                    ->where('status', 'active')
            )
            ->columns([
                Tables\Columns\TextColumn::make('productProfile.name')
                    ->label('نام محصول')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->limit(30),
                    
                Tables\Columns\TextColumn::make('productProfile.sku')
                    ->label('کد کالا')
                    ->searchable()
                    ->sortable()
                    ->copyable(),
                    
                Tables\Columns\TextColumn::make('current_stock')
                    ->label('موجودی فعلی')
                    ->numeric()
                    ->sortable()
                    ->badge()
                    ->color('danger'),
                    
                Tables\Columns\TextColumn::make('min_stock')
                    ->label('حداقل موجودی')
                    ->numeric()
                    ->sortable()
                    ->badge()
                    ->color('warning'),
                    
                Tables\Columns\TextColumn::make('warehouse.name')
                    ->label('انبار')
                    ->searchable()
                    ->sortable()
                    ->limit(20),
                    
                Tables\Columns\TextColumn::make('status')
                    ->label('وضعیت')
                    ->badge()
                    ->color('danger'),
                    
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('آخرین بروزرسانی')
                    ->dateTime('Y-m-d H:i')
                    ->sortable(),
            ])
            ->actions([
                Action::make('view')
                    ->label('مشاهده')
                    ->icon('heroicon-m-eye')
                    ->url(fn (Item $record): string => route('filament.admin.resources.items.view', $record))
                    ->openUrlInNewTab(),
                    
                Action::make('edit')
                    ->label('ویرایش')
                    ->icon('heroicon-m-pencil')
                    ->url(fn (Item $record): string => route('filament.admin.resources.items.edit', $record))
                    ->openUrlInNewTab(),
            ])
            ->defaultSort('current_stock', 'asc')
            ->emptyStateHeading('هیچ هشداری وجود ندارد')
            ->emptyStateDescription('همه اقلام موجودی کافی دارند')
            ->emptyStateIcon('heroicon-o-check-circle');
    }
}
