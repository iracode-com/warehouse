<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DocumentResource\Pages;
use App\Models\Document;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

class DocumentResource extends Resource
{
    protected static ?string $model = Document::class;

    public static function getNavigationGroup(): ?string
    {
        return 'مدیریت اسناد';
    }

    public static function getNavigationIcon(): ?string
    {
        return 'heroicon-o-document-text';
    }

    public static function getNavigationLabel(): string
    {
        return 'اسناد';
    }

    public static function getModelLabel(): string
    {
        return 'سند';
    }

    public static function getPluralModelLabel(): string
    {
        return 'اسناد';
    }

    public static function getNavigationSort(): ?int
    {
        return 1;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('اطلاعات سند')
                    ->description('اطلاعات پایه سند')
                    ->icon('heroicon-o-document-text')
                    ->iconColor('primary')
                    ->collapsible()
                    ->collapsed(false)
                    ->columnSpanFull()
                    ->columns(3)
                    ->schema([
                        Forms\Components\TextInput::make('document_number')
                            ->label('شماره سند')
                            ->default(fn () => 'DOC-'.\Morilog\Jalali\Jalalian::forge('today')->format('Ymd').'-'.(now()->hour * 3600 + now()->minute * 60 + now()->second).'-'.rand(1000, 9999))
                            ->disabled()
                            ->dehydrated(),

                        Forms\Components\DatePicker::make('document_date')
                            ->label('تاریخ سند')
                            ->default(now())
                            ->jalali()
                            ->required(),

                        Forms\Components\Select::make('document_type')
                            ->label('نوع سند')
                            ->options(Document::getTypeOptions())
                            ->required()
                            ->live(),

                        Forms\Components\Select::make('status')
                            ->label('وضعیت')
                            ->options(Document::getStatusOptions())
                            ->default(Document::STATUS_DRAFT)
                            ->required(),
                    ]),

                Section::make('اطلاعات انبار')
                    ->description('انبار مبدا و مقصد')
                    ->icon('heroicon-o-building-office-2')
                    ->iconColor('info')
                    ->collapsible()
                    ->collapsed(false)
                    ->columnSpanFull()
                    ->columns(3)
                    ->visible(fn ($get) => in_array($get('document_type'), [Document::TYPE_ISSUE, Document::TYPE_TRANSFER]) ||
                        in_array($get('document_type'), [Document::TYPE_RECEIPT, Document::TYPE_TRANSFER])
                    )
                    ->schema([
                        Forms\Components\Select::make('source_warehouse_id')
                            ->label('انبار مبدا')
                            ->relationship('sourceWarehouse', 'title')
                            ->searchable()
                            ->preload()
                            ->visible(fn ($get) => in_array($get('document_type'), [Document::TYPE_ISSUE, Document::TYPE_TRANSFER])),

                        Forms\Components\Select::make('destination_warehouse_id')
                            ->label('انبار مقصد')
                            ->relationship('destinationWarehouse', 'title')
                            ->searchable()
                            ->preload()
                            ->visible(fn ($get) => in_array($get('document_type'), [Document::TYPE_RECEIPT, Document::TYPE_TRANSFER])),
                    ]),

                Section::make('اطلاعات طرف')
                    ->description('اطلاعات طرف حساب')
                    ->icon('heroicon-o-user-group')
                    ->iconColor('success')
                    ->collapsible()
                    ->collapsed(false)
                    ->columnSpanFull()
                    ->visible(fn ($get) => in_array($get('document_type'), [Document::TYPE_RECEIPT, Document::TYPE_ISSUE]))
                    ->schema([
                        Forms\Components\Select::make('party_type')
                            ->label('نوع طرف')
                            ->options(Document::getPartyTypeOptions())
                            ->required()
                            ->columnSpan(1),

                        Forms\Components\TextInput::make('party_code')
                            ->label('کد طرف')
                            ->columnSpan(1),

                        Forms\Components\TextInput::make('party_name')
                            ->label('نام طرف')
                            ->required()
                            ->columnSpan(2),

                        Forms\Components\TextInput::make('party_phone')
                            ->label('تلفن')
                            ->columnSpan(1),

                        Forms\Components\Textarea::make('party_address')
                            ->label('آدرس')
                            ->rows(2)
                            ->columnSpan(3),
                    ]),

                Section::make('اطلاعات مرجع')
                    ->description('شماره مرجع و فاکتور')
                    ->icon('heroicon-o-document-duplicate')
                    ->iconColor('warning')
                    ->collapsible()
                    ->collapsed(false)
                    ->columnSpanFull()
                    ->columns(3)
                    ->schema([
                        Forms\Components\TextInput::make('reference_number')
                            ->label('شماره مرجع/سفارش')
                            ->required()
                            ->columnSpan(1),

                        Forms\Components\TextInput::make('invoice_number')
                            ->label('شماره فاکتور')
                            ->required()
                            ->columnSpan(1),

                        Forms\Components\DatePicker::make('invoice_date')
                            ->label('تاریخ فاکتور')
                            ->jalali()
                            ->required()
                            ->columnSpan(1),
                    ]),

                Section::make('اقلام کالا')
                    ->description('کالاهای موجود در این سند')
                    ->icon('heroicon-o-cube')
                    ->iconColor('success')
                    ->collapsible()
                    ->collapsed(false)
                    ->columnSpanFull()
                    ->schema([
                        Forms\Components\Repeater::make('items')
                            ->relationship('items')
                            ->label('اقلام')
                            ->schema([
                                Forms\Components\Select::make('product_profile_id')
                                    ->label('پروفایل کالا')
                                    ->relationship('productProfile', 'name')
                                    ->getOptionLabelFromRecordUsing(fn ($record) => $record->name.' | SKU: '.($record->sku ?? '-').' | برند: '.($record->brand ?? '-')
                                    )
                                    ->required()
                                    ->searchable(['name', 'sku', 'brand'])
                                    ->preload()
                                    ->live()
                                    ->afterStateUpdated(function ($state, $set) {
                                        if ($state) {
                                            $productProfile = \App\Models\ProductProfile::find($state);
                                            if ($productProfile) {
                                                $set('unit_id', $productProfile->unit_id ?? null);
                                                $set('unit_price', $productProfile->unit_cost ?? 0);
                                            }
                                        }
                                    })
                                    ->columnSpanFull(),

                                Grid::make(2)
                                    ->schema([
                                        Forms\Components\TextInput::make('quantity')
                                            ->label('مقدار')
                                            ->numeric()
                                            ->default(1)
                                            ->minValue(0.001)
                                            ->required()
                                            ->live(debounce: 500)
                                            ->columnSpan(1),

                                        Forms\Components\TextInput::make('unit_price')
                                            ->label('قیمت واحد')
                                            ->numeric()
                                            ->default(0)
                                            ->prefix('ریال')
                                            ->live(debounce: 500)
                                            ->columnSpan(1),
                                    ]),

                                Grid::make(4)
                                    ->schema([
                                        Forms\Components\TextInput::make('discount_percentage')
                                            ->label('تخفیف (%)')
                                            ->numeric()
                                            ->default(0)
                                            ->minValue(0)
                                            ->maxValue(100)
                                            ->suffix('%')
                                            ->columnSpan(1),

                                        Forms\Components\TextInput::make('tax_percentage')
                                            ->label('مالیات (%)')
                                            ->numeric()
                                            ->default(0)
                                            ->minValue(0)
                                            ->maxValue(100)
                                            ->suffix('%')
                                            ->columnSpan(1),

                                        Forms\Components\Select::make('unit_id')
                                            ->label('واحد')
                                            ->relationship('unit', 'name')
                                            ->searchable()
                                            ->preload()
                                            ->columnSpan(2),
                                    ]),

                                Forms\Components\Repeater::make('item_images')
                                    ->label('تصاویر کالا')
                                    ->schema([
                                        Grid::make(2)
                                            ->schema([
                                                Forms\Components\TextInput::make('title')
                                                    ->label('عنوان تصویر')
                                                    ->required()
                                                    ->maxLength(255)
                                                    ->columnSpan(1),

                                                Forms\Components\FileUpload::make('image')
                                                    ->label('تصویر')
                                                    ->required()
                                                    ->image()
                                                    ->maxSize(5120) // 5MB
                                                    ->directory('documents/item-images')
                                                    ->visibility('private')
                                                    ->columnSpan(1),
                                            ]),

                                        Forms\Components\Textarea::make('description')
                                            ->label('یادداشت تصویر')
                                            ->rows(2)
                                            ->columnSpanFull(),
                                    ])
                                    ->defaultItems(1)
                                    ->addActionLabel('➕ افزودن تصویر')
                                    ->reorderable()
                                    ->reorderableWithButtons()
                                    ->collapsible()
                                    ->collapsed(true)
                                    ->itemLabel(fn (array $state): ?string => $state['title'] ?? 'تصویر جدید')
                                    ->cloneable()
                                    ->columnSpanFull()
                                    ->deleteAction(
                                        fn ($action) => $action->requiresConfirmation()
                                    ),

                                Forms\Components\Textarea::make('notes')
                                    ->label('یادداشت')
                                    ->rows(2)
                                    ->columnSpanFull(),
                            ])
                            ->defaultItems(1)
                            ->addActionLabel('➕ افزودن کالا')
                            ->reorderable()
                            ->reorderableWithButtons()
                            ->collapsible()
                            ->collapsed(false)
                            ->itemLabel(fn (array $state): ?string => $state['product_profile_id']
                                ? (\App\Models\ProductProfile::find($state['product_profile_id'])?->name ?? 'پروفایل کالا').
                                  ' ('.number_format($state['quantity'] ?? 0, 2).')'
                                : 'کالا جدید'
                            )
                            ->columnSpanFull(),
                    ]),

                Section::make('فایل‌های پیوست')
                    ->description('آپلود اسناد و مدارک')
                    ->icon('heroicon-o-paper-clip')
                    ->iconColor('info')
                    ->collapsible()
                    ->collapsed(true)
                    ->columnSpanFull()
                    ->schema([
                        Forms\Components\Repeater::make('attachments')
                            ->label('فایل‌های پیوست')
                            ->schema([
                                Grid::make(2)
                                    ->schema([
                                        Forms\Components\TextInput::make('title')
                                            ->label('عنوان فایل')
                                            ->required()
                                            ->maxLength(255)
                                            ->columnSpan(1),

                                        Forms\Components\FileUpload::make('file')
                                            ->label('فایل')
                                            ->required()
                                            ->acceptedFileTypes(['application/pdf', 'image/*', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'])
                                            ->maxSize(10240) // 10MB
                                            ->directory('documents/attachments')
                                            ->visibility('private')
                                            ->columnSpan(1),
                                    ]),

                                Forms\Components\Textarea::make('description')
                                    ->label('توضیحات فایل')
                                    ->rows(2)
                                    ->columnSpanFull(),
                            ])
                            ->defaultItems(1)
                            ->addActionLabel('➕ افزودن فایل')
                            ->reorderable()
                            ->reorderableWithButtons()
                            ->collapsible()
                            ->collapsed(false)
                            ->itemLabel(fn (array $state): ?string => $state['title'] ?? 'فایل جدید')
                            ->cloneable()
                            ->columnSpanFull()
                            ->deleteAction(
                                fn ($action) => $action->requiresConfirmation()
                            ),
                    ]),

                Section::make('توضیحات و یادداشت')
                    ->description('اطلاعات تکمیلی')
                    ->icon('heroicon-o-chat-bubble-left-right')
                    ->iconColor('gray')
                    ->collapsible()
                    ->collapsed(true)
                    ->columnSpanFull()
                    ->schema([
                        Forms\Components\Textarea::make('description')
                            ->label('شرح سند')
                            ->rows(3)
                            ->columnSpanFull(),

                        Forms\Components\KeyValue::make('notes')
                            ->label('یادداشت‌ها')
                            ->keyLabel('کلید')
                            ->valueLabel('مقدار')
                            ->addActionLabel('افزودن یادداشت')
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('document_number')
                    ->label('شماره سند')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('document_type')
                    ->label('نوع سند')
                    ->formatStateUsing(fn ($state) => Document::getTypeOptions()[$state] ?? $state)
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'receipt' => 'success',
                        'issue' => 'danger',
                        'transfer' => 'info',
                        'adjustment' => 'gray',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('document_date')
                    ->label('تاریخ سند')
                    ->jalali()
                    ->sortable(),

                Tables\Columns\TextColumn::make('party_name')
                    ->label('طرف')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('sourceWarehouse.title')
                    ->label('انبار مبدا')
                    ->sortable(),

                Tables\Columns\TextColumn::make('destinationWarehouse.title')
                    ->label('انبار مقصد')
                    ->sortable(),

                Tables\Columns\TextColumn::make('items_count')
                    ->label('تعداد اقلام')
                    ->counts('items'),

                Tables\Columns\TextColumn::make('final_amount')
                    ->label('مبلغ نهایی')
                    ->money('IRR')
                    ->sortable(),

                Tables\Columns\TextColumn::make('status')
                    ->label('وضعیت')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'draft' => 'gray',
                        'approved' => 'success',
                        'cancelled' => 'danger',
                    }),

                Tables\Columns\TextColumn::make('creator.name')
                    ->label('ثبت‌کننده')
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('تاریخ ثبت')
                    ->jalali()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('document_type')
                    ->label('نوع سند')
                    ->options(Document::getTypeOptions()),

                Tables\Filters\SelectFilter::make('status')
                    ->label('وضعیت')
                    ->options(Document::getStatusOptions()),

                Tables\Filters\SelectFilter::make('source_warehouse_id')
                    ->label('انبار مبدا')
                    ->relationship('sourceWarehouse', 'title')
                    ->searchable()
                    ->preload(),

                Tables\Filters\SelectFilter::make('destination_warehouse_id')
                    ->label('انبار مقصد')
                    ->relationship('destinationWarehouse', 'title')
                    ->searchable()
                    ->preload(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListDocuments::route('/'),
            'create' => Pages\CreateDocument::route('/create'),
            'edit' => Pages\EditDocument::route('/{record}/edit'),
        ];
    }
}
