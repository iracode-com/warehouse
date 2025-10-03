<?php

namespace App\Filament\Resources\WarehouseSheds\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class WarehouseShedsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('نام سوله')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('code')
                    ->label('کد سوله')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('area')
                    ->label('مساحت (متر مربع)')
                    ->numeric()
                    ->sortable()
                    ->suffix(' متر مربع'),
                TextColumn::make('length')
                    ->label('طول (متر)')
                    ->numeric()
                    ->sortable()
                    ->suffix(' متر'),
                TextColumn::make('width')
                    ->label('عرض (متر)')
                    ->numeric()
                    ->sortable()
                    ->suffix(' متر'),
                TextColumn::make('height')
                    ->label('ارتفاع (متر)')
                    ->numeric()
                    ->sortable()
                    ->suffix(' متر'),
                TextColumn::make('structure_type')
                    ->label('نوع سازه')
                    ->formatStateUsing(function ($state) {
                        return match($state) {
                            'steel' => 'فلزی',
                            'concrete' => 'بتنی',
                            'prefabricated' => 'پیش‌ساخته',
                            'mixed' => 'ترکیبی',
                            default => $state,
                        };
                    })
                    ->sortable(),
                TextColumn::make('address')
                    ->label('آدرس')
                    ->searchable()
                    ->limit(50),
                IconColumn::make('status')
                    ->label('وضعیت')
                    ->boolean(),
                TextColumn::make('created_at')
                    ->label('تاریخ ایجاد')
                    ->jalaliDateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label('تاریخ به‌روزرسانی')
                    ->jalaliDateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
