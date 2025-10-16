<?php

namespace App\Filament\Resources\GoodsReceipts;

use App\Filament\Resources\GoodsReceipts\Pages;
use App\Models\Document;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Table;

class GoodsReceiptResource extends Resource
{
    protected static ?string $model = Document::class;

    public static function getNavigationGroup(): ?string
    {
        return 'مدیریت اسناد';
    }

    public static function getNavigationIcon(): ?string
    {
        return 'heroicon-o-arrow-down-tray';
    }

    public static function getNavigationLabel(): string
    {
        return 'رسید کالا';
    }

    public static function getModelLabel(): string
    {
        return 'رسید کالا';
    }

    public static function getPluralModelLabel(): string
    {
        return 'رسیدهای کالا';
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
                    ->description('اطلاعات پایه رسید کالا')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->iconColor('success')
                    ->collapsible()
                    ->collapsed(false)
                    ->columnSpanFull()
                    ->columns(3)
                    ->schema([
                        Forms\Components\TextInput::make('document_number')
                            ->label('شماره رسید')
                            ->default(fn () => 'REC-'.\Morilog\Jalali\Jalalian::forge('today')->format('Ymd').'-'.(now()->hour * 3600 + now()->minute * 60 + now()->second).'-'.rand(1000, 9999))
                            ->disabled()
                            ->dehydrated(),

                        Forms\Components\DatePicker::make('document_date')
                            ->label('تاریخ رسید')
                            ->default(now())
                            ->jalali()
                            ->required(),

                        Forms\Components\Select::make('status')
                            ->label('وضعیت')
                            ->options(Document::getStatusOptions())
                            ->default(Document::STATUS_DRAFT)
                            ->required(),
                    ]),

                Section::make('اطلاعات انبار')
                    ->description('انبار مقصد')
                    ->icon('heroicon-o-building-office-2')
                    ->iconColor('info')
                    ->collapsible()
                    ->collapsed(false)
                    ->columnSpanFull()
                    ->columns(2)
                    ->schema([
                        Forms\Components\Select::make('destination_warehouse_id')
                            ->label('انبار مقصد')
                            ->relationship('destinationWarehouse', 'title')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->columnSpanFull(),
                    ]),

                Section::make('اطلاعات طرف حساب')
                    ->description('اطلاعات تامین‌کننده')
                    ->icon('heroicon-o-user-group')
                    ->iconColor('success')
                    ->collapsible()
                    ->collapsed(false)
                    ->columnSpanFull()
                    ->columns(3)
                    ->schema([
                        Forms\Components\Select::make('supplier_id')
                            ->label('تامین‌کننده')
                            ->relationship('supplier', 'name')
                            ->getOptionLabelFromRecordUsing(fn ($record) => 
                                $record->name . ' (' . $record->code . ')'
                            )
                            ->searchable(['name', 'code'])
                            ->preload()
                            ->required()
                            ->columnSpan(2),

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
                    ->description('کالاهای دریافتی')
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
                                    ->getOptionLabelFromRecordUsing(fn ($record) => 
                                        $record->name . ' | SKU: ' . ($record->sku ?? '-') . ' | برند: ' . ($record->brand ?? '-')
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
                                ? (\App\Models\ProductProfile::find($state['product_profile_id'])?->name ?? 'پروفایل کالا') .
                                  ' (' . number_format($state['quantity'] ?? 0, 2) . ')'
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
                            ->label('شرح رسید')
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
            ->modifyQueryUsing(fn ($query) => $query->where('document_type', Document::TYPE_RECEIPT))
            ->columns([
                Tables\Columns\TextColumn::make('document_number')
                    ->label('شماره رسید')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('document_date')
                    ->label('تاریخ رسید')
                    ->jalaliDateTime()
                    ->sortable(),

                Tables\Columns\TextColumn::make('supplier.name')
                    ->label('تامین‌کننده')
                    ->searchable()
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
                    ->jalaliDateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('وضعیت')
                    ->options(Document::getStatusOptions()),

                Tables\Filters\SelectFilter::make('destination_warehouse_id')
                    ->label('انبار مقصد')
                    ->relationship('destinationWarehouse', 'title')
                    ->searchable()
                    ->preload(),
            ])
            ->actions([
                ViewAction::make(),
                EditAction::make(),
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
            'index' => Pages\ListGoodsReceipts::route('/'),
            'create' => Pages\CreateGoodsReceipt::route('/create'),
            'edit' => Pages\EditGoodsReceipt::route('/{record}/edit'),
        ];
    }

    protected static function getRedirectAfterCreate(): string
    {
        return static::getUrl('index');
    }

    protected static function getRedirectAfterEdit(): string
    {
        return static::getUrl('index');
    }
}
