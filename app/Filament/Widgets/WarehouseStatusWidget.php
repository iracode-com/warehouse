<?php

namespace App\Filament\Widgets;

use App\Models\Warehouse;
use Filament\Actions\Action;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class WarehouseStatusWidget extends BaseWidget
{
    protected int | string | array $columnSpan = 'full';
    
    public function getHeading(): string
    {
        return 'وضعیت انبارها';
    }
    
    public function table(Table $table): Table
    {
        return $table
            ->query(
                Warehouse::query()
                    ->withCount(['items', 'zones', 'racks'])
            )
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('نام انبار')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                    
                Tables\Columns\TextColumn::make('location')
                    ->label('مکان')
                    ->searchable()
                    ->limit(30),
                    
                Tables\Columns\TextColumn::make('capacity')
                    ->label('ظرفیت')
                    ->numeric()
                    ->sortable()
                    ->formatStateUsing(fn (int $state): string => number_format($state)),
                    
                Tables\Columns\TextColumn::make('items_count')
                    ->label('تعداد اقلام')
                    ->numeric()
                    ->sortable()
                    ->badge()
                    ->color(fn (int $state): string => match (true) {
                        $state == 0 => 'gray',
                        $state <= 50 => 'warning',
                        $state <= 200 => 'success',
                        default => 'info',
                    }),
                    
                Tables\Columns\TextColumn::make('zones_count')
                    ->label('تعداد مناطق')
                    ->numeric()
                    ->sortable()
                    ->badge()
                    ->color('primary'),
                    
                Tables\Columns\TextColumn::make('racks_count')
                    ->label('تعداد قفسه‌ها')
                    ->numeric()
                    ->sortable()
                    ->badge()
                    ->color('secondary'),
                    
                Tables\Columns\TextColumn::make('status')
                    ->label('وضعیت')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'active' => 'success',
                        'inactive' => 'danger',
                        'maintenance' => 'warning',
                    }),
                    
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('آخرین بروزرسانی')
                    ->dateTime('Y-m-d H:i')
                    ->sortable(),
            ])
            ->actions([
                Action::make('view')
                    ->label('مشاهده')
                    ->icon('heroicon-m-eye')
                    ->url(fn (Warehouse $record): string => route('filament.admin.resources.warehouses.view', $record))
                    ->openUrlInNewTab(),
            ])
            ->defaultSort('updated_at', 'desc');
    }
}
