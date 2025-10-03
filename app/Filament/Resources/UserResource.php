<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Table;
use BackedEnum;

class UserResource extends Resource
{
    protected static ?string $model = User::class;


    protected static ?string $navigationLabel = null;

    protected static ?string $modelLabel = null;

    protected static ?string $pluralModelLabel = null;

    public static function getNavigationLabel(): string
    {
        return __('warehouse.user.navigation.plural');
    }

    public static function getModelLabel(): string
    {
        return __('warehouse.user.navigation.singular');
    }

    public static function getPluralModelLabel(): string
    {
        return __('warehouse.user.navigation.plural');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('warehouse.navigation_groups.user_management');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->schema([
                Section::make(__('warehouse.user.sections.personal_info'))
                    ->description(__('warehouse.user.sections.personal_info_desc'))
                    ->icon('heroicon-o-user')
                    ->iconColor('primary')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->label(__('warehouse.user.name'))
                                    ->required()
                                    ->maxLength(255),
                                
                                Forms\Components\TextInput::make('family')
                                    ->label(__('warehouse.user.family'))
                                    ->required()
                                    ->maxLength(255),
                            ]),
                        
                        Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('email')
                                    ->label(__('warehouse.user.email'))
                                    ->email()
                                    ->nullable()
                                    ->unique(ignoreRecord: true)
                                    ->maxLength(255),
                                
                                Forms\Components\TextInput::make('mobile')
                                    ->label(__('warehouse.user.mobile'))
                                    ->tel()
                                    ->required()
                                    ->unique(ignoreRecord: true)
                                    ->maxLength(20),
                            ]),
                        
                        Forms\Components\TextInput::make('username')
                            ->label(__('warehouse.user.username'))
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                    ])
                    ->collapsible(),

                Section::make(__('warehouse.user.sections.authentication'))
                    ->description(__('warehouse.user.sections.authentication_desc'))
                    ->icon('heroicon-o-lock-closed')
                    ->iconColor('success')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('password')
                                    ->label(__('warehouse.user.password'))
                                    ->password()
                                    ->required(fn (string $context): bool => $context === 'create')
                                    ->minLength(8)
                                    ->dehydrated(fn ($state) => filled($state))
                                    ->dehydrateStateUsing(fn ($state) => bcrypt($state)),
                                
                                Forms\Components\TextInput::make('password_confirmation')
                                    ->label(__('warehouse.user.password_confirmation'))
                                    ->password()
                                    ->required(fn (string $context): bool => $context === 'create')
                                    ->minLength(8)
                                    ->same('password')
                                    ->dehydrated(false),
                            ]),
                    ])
                    ->collapsible(),

                Section::make(__('warehouse.user.sections.roles'))
                    ->description(__('warehouse.user.sections.roles_desc'))
                    ->icon('heroicon-o-shield-check')
                    ->iconColor('warning')
                    ->schema([
                        Forms\Components\Select::make('roles')
                            ->label(__('warehouse.user.roles'))
                            ->multiple()
                            ->relationship('roles', 'name')
                            ->searchable()
                            ->preload()
                            ->helperText(__('warehouse.user.roles_helper')),
                    ])
                    ->collapsible(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('warehouse.user.table.name'))
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                
                Tables\Columns\TextColumn::make('family')
                    ->label(__('warehouse.user.table.family'))
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('email')
                    ->label(__('warehouse.user.table.email'))
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->copyMessage(__('warehouse.user.table.email_copied')),
                
                Tables\Columns\TextColumn::make('mobile')
                    ->label(__('warehouse.user.table.mobile'))
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->copyMessage(__('warehouse.user.table.mobile_copied')),
                
                Tables\Columns\TextColumn::make('username')
                    ->label(__('warehouse.user.table.username'))
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('roles.name')
                    ->label(__('warehouse.user.table.roles'))
                    ->badge()
                    ->separator(',')
                    ->color('success')
                    ->toggleable(),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('warehouse.user.table.created_at'))
                    ->dateTime('j F Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('email_verified_at')
                    ->label(__('warehouse.user.filters.email_verified'))
                    ->placeholder(__('warehouse.user.filters.all'))
                    ->trueLabel(__('warehouse.user.filters.verified'))
                    ->falseLabel(__('warehouse.user.filters.unverified')),
            ])
            ->actions([
                ViewAction::make()
                    ->label(__('warehouse.user.actions.view'))
                    ->icon('heroicon-o-eye'),
                EditAction::make()
                    ->label(__('warehouse.user.actions.edit'))
                    ->icon('heroicon-o-pencil'),
                DeleteAction::make()
                    ->label(__('warehouse.user.actions.delete'))
                    ->icon('heroicon-o-trash')
                    ->requiresConfirmation(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->label(__('warehouse.user.actions.delete_selected'))
                        ->requiresConfirmation(),
                ])
            ])
            ->defaultSort('created_at', 'desc')
            ->striped()
            ->paginated([10, 25, 50, 100]);
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'view' => Pages\ViewUser::route('/{record}'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
