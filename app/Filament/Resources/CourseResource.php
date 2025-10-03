<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CourseResource\Pages;
use App\Models\Course;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;

class CourseResource extends Resource
{
    protected static ?string $model = Course::class;

    public static function getNavigationIcon(): ?string
    {
        return 'heroicon-o-academic-cap';
    }

    public static function getNavigationLabel(): string
    {
        return __('course.navigation.plural');
    }

    public static function getModelLabel(): string
    {
        return __('course.navigation.singular');
    }

    public static function getPluralModelLabel(): string
    {
        return __('course.navigation.plural');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('position.navigation.group');
    }

    public static function getNavigationSort(): ?int
    {
        return 4;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('course.sections.basic_info'))
                    ->description(__('course.sections.basic_info_desc'))
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label(__('course.fields.name'))
                            ->required()
                            ->maxLength(255),
                        
                        Forms\Components\Textarea::make('description')
                            ->label(__('course.fields.description'))
                            ->rows(3)
                            ->columnSpanFull(),
                        
                        Forms\Components\TextInput::make('duration_hours')
                            ->label(__('course.fields.duration_hours'))
                            ->numeric()
                            ->minValue(1),
                        
                        Forms\Components\TextInput::make('instructor')
                            ->label(__('course.fields.instructor'))
                            ->maxLength(255),
                        
                        Forms\Components\TextInput::make('institution')
                            ->label(__('course.fields.institution'))
                            ->maxLength(255),
                        
                        Forms\Components\DatePicker::make('completion_date')
                            ->label(__('course.fields.completion_date')),
                        
                        Forms\Components\TextInput::make('certificate_number')
                            ->label(__('course.fields.certificate_number'))
                            ->maxLength(255),
                        
                        Forms\Components\Toggle::make('status')
                            ->label(__('course.fields.status'))
                            ->default(true)
                            ->required(),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('course.table.name'))
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('description')
                    ->label(__('course.table.description'))
                    ->limit(50)
                    ->searchable(),
                
                Tables\Columns\TextColumn::make('duration_hours')
                    ->label(__('course.table.duration_hours'))
                    ->numeric()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('instructor')
                    ->label(__('course.table.instructor'))
                    ->searchable(),
                
                Tables\Columns\TextColumn::make('institution')
                    ->label(__('course.table.institution'))
                    ->searchable(),
                
                Tables\Columns\TextColumn::make('completion_date')
                    ->label(__('course.table.completion_date'))
                    ->jalaliDate()
                    ->sortable(),
                
                Tables\Columns\IconColumn::make('status')
                    ->label(__('course.table.status'))
                    ->boolean()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('course.table.created_at'))
                    ->jalaliDateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('status')
                    ->label(__('course.filters.status'))
                    ->boolean()
                    ->trueLabel(__('course.filters.active'))
                    ->falseLabel(__('course.filters.inactive'))
                    ->native(false),
            ])
            ->actions([
                EditAction::make(),
                ViewAction::make(),
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
            'index' => Pages\ListCourses::route('/'),
            'create' => Pages\CreateCourse::route('/create'),
            'edit' => Pages\EditCourse::route('/{record}/edit'),
        ];
    }
}
