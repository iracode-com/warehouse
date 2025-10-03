<?php

namespace App\Filament\Resources\WarehouseManagers\Tables;

use App\Models\WarehouseManager;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\DeleteAction;
use Filament\Tables;
use Filament\Tables\Table;

class WarehouseManagersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('full_name')
                    ->label(__('warehouse.warehouse_manager.table.name'))
                    ->searchable(['first_name', 'last_name'])
                    ->sortable()
                    ->weight('bold')
                    ->description(fn (WarehouseManager $record): string => $record->employee_id),
                
                Tables\Columns\TextColumn::make('employee_id')
                    ->label(__('warehouse.warehouse_manager.table.employee_id'))
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('info'),
                
                Tables\Columns\TextColumn::make('position')
                    ->label(__('warehouse.warehouse_manager.table.position'))
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                
                Tables\Columns\TextColumn::make('warehouse.title')
                    ->label(__('warehouse.warehouse_manager.table.warehouse'))
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('success'),
                
                Tables\Columns\TextColumn::make('employment_status')
                    ->label(__('warehouse.warehouse_manager.table.employment_status'))
                    ->formatStateUsing(fn (string $state): string => __('warehouse.warehouse_manager.employment_statuses.' . $state))
                    ->badge()
                    ->color(fn (string $state): string => match($state) {
                        'active' => 'success',
                        'inactive' => 'warning',
                        'terminated' => 'danger',
                        'retired' => 'gray',
                        default => 'gray',
                    }),
                
                Tables\Columns\TextColumn::make('hire_date')
                    ->label(__('warehouse.warehouse_manager.table.hire_date'))
                    ->date('Y/m/d')
                    ->sortable()
                    ->toggleable(),
                
                Tables\Columns\TextColumn::make('years_of_service')
                    ->label(__('warehouse.warehouse_manager.table.years_of_service'))
                    ->numeric()
                    ->sortable()
                    ->suffix(' سال')
                    ->toggleable(),
                
                Tables\Columns\IconColumn::make('is_primary_manager')
                    ->label(__('warehouse.warehouse_manager.table.is_primary'))
                    ->boolean()
                    ->trueIcon('heroicon-o-star')
                    ->falseIcon('heroicon-o-star')
                    ->trueColor('warning')
                    ->falseColor('gray'),
                
                Tables\Columns\TextColumn::make('mobile')
                    ->label('موبایل')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('warehouse.warehouse_manager.table.created_at'))
                    ->dateTime('j F Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('employment_status')
                    ->label(__('warehouse.warehouse_manager.filters.employment_status'))
                    ->multiple()
                    ->options(__('warehouse.warehouse_manager.employment_statuses')),
                
                Tables\Filters\SelectFilter::make('warehouse_id')
                    ->label(__('warehouse.warehouse_manager.filters.warehouse'))
                    ->relationship('warehouse', 'title')
                    ->multiple()
                    ->searchable(),
                
                Tables\Filters\TernaryFilter::make('is_primary_manager')
                    ->label(__('warehouse.warehouse_manager.filters.is_primary'))
                    ->placeholder(__('warehouse.filters.all'))
                    ->trueLabel('اصلی')
                    ->falseLabel('غیراصلی'),
                
                Tables\Filters\TernaryFilter::make('can_approve_orders')
                    ->label(__('warehouse.warehouse_manager.filters.can_approve'))
                    ->placeholder(__('warehouse.filters.all'))
                    ->trueLabel('دارد')
                    ->falseLabel('ندارد'),
                
                Tables\Filters\TernaryFilter::make('can_manage_inventory')
                    ->label(__('warehouse.warehouse_manager.filters.can_manage'))
                    ->placeholder(__('warehouse.filters.all'))
                    ->trueLabel('دارد')
                    ->falseLabel('ندارد'),
            ])
            ->actions([
                ViewAction::make()
                    ->label(__('warehouse.warehouse_manager.actions.view'))
                    ->icon('heroicon-o-eye'),
                EditAction::make()
                    ->label(__('warehouse.warehouse_manager.actions.edit'))
                    ->icon('heroicon-o-pencil'),
                DeleteAction::make()
                    ->label(__('warehouse.warehouse_manager.actions.delete'))
                    ->icon('heroicon-o-trash')
                    ->requiresConfirmation(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->label(__('warehouse.warehouse_manager.actions.delete_selected'))
                        ->requiresConfirmation(),
                ])
            ])
            ->defaultSort('created_at', 'desc')
            ->striped()
            ->paginated([10, 25, 50, 100]);
    }
}