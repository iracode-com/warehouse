<?php

namespace App\Filament\Resources\WarehouseManagers\Schemas;

use Filament\Forms;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;

class WarehouseManagerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->schema([
                Tabs::make('warehouse_manager_tabs')
                    ->tabs([
                        // Tab 1: اطلاعات شخصی (Personal Information)
                        Tab::make(__('warehouse.warehouse_manager.sections.personal_info'))
                            ->icon('heroicon-o-user')
                            ->schema([
                                Section::make(__('warehouse.warehouse_manager.sections.personal_info'))
                                    ->description(__('warehouse.warehouse_manager.sections.personal_info_desc'))
                                    ->icon('heroicon-o-user')
                                    ->iconColor('primary')
                                    ->schema([
                                        Grid::make(2)
                                            ->schema([
                                                Forms\Components\TextInput::make('first_name')
                                                    ->label(__('warehouse.warehouse_manager.first_name'))
                                                    ->required()
                                                    ->maxLength(255),
                                                
                                                Forms\Components\TextInput::make('last_name')
                                                    ->label(__('warehouse.warehouse_manager.last_name'))
                                                    ->required()
                                                    ->maxLength(255),
                                            ]),
                                        
                                        Grid::make(2)
                                            ->schema([
                                                Forms\Components\TextInput::make('national_id')
                                                    ->label(__('warehouse.warehouse_manager.national_id'))
                                                    ->required()
                                                    ->unique(ignoreRecord: true)
                                                    ->maxLength(10)
                                                    ->minLength(10)
                                                    ->numeric(),
                                                
                                                Forms\Components\TextInput::make('employee_id')
                                                    ->label(__('warehouse.warehouse_manager.employee_id'))
                                                    ->required()
                                                    ->unique(ignoreRecord: true)
                                                    ->maxLength(255),
                                            ]),
                                        
                                        Grid::make(3)
                                            ->schema([
                                                Forms\Components\DatePicker::make('birth_date')
                                                    ->label(__('warehouse.warehouse_manager.birth_date'))
                                                    ->required()
                                                    ->jalali()
                                                    ->maxDate(now()->subYears(18))
                                                    ->displayFormat('Y/m/d'),
                                                
                                                Forms\Components\Select::make('gender')
                                                    ->label(__('warehouse.warehouse_manager.gender'))
                                                    ->options(__('warehouse.warehouse_manager.genders'))
                                                    ->required()
                                                    ->searchable(),
                                                
                                                Forms\Components\TextInput::make('phone')
                                                    ->label(__('warehouse.warehouse_manager.phone'))
                                                    ->tel()
                                                    ->maxLength(255),
                                            ]),
                                        
                                        Grid::make(2)
                                            ->schema([
                                                Forms\Components\TextInput::make('mobile')
                                                    ->label(__('warehouse.warehouse_manager.mobile'))
                                                    ->required()
                                                    ->tel()
                                                    ->maxLength(255),
                                                
                                                Forms\Components\TextInput::make('email')
                                                    ->label(__('warehouse.warehouse_manager.email'))
                                                    ->email()
                                                    ->maxLength(255),
                                            ]),
                                        
                                        Forms\Components\Textarea::make('address')
                                            ->label(__('warehouse.warehouse_manager.address'))
                                            ->required()
                                            ->rows(3)
                                            ->columnSpanFull(),
                                        
                                        Forms\Components\TextInput::make('postal_code')
                                            ->label(__('warehouse.warehouse_manager.postal_code'))
                                            ->maxLength(10)
                                            ->numeric()
                                            ->columnSpanFull(),
                                    ])
                                    ->collapsible(),
                            ]),

                        // Tab 2: اطلاعات شغلی (Professional Information)
                        Tab::make(__('warehouse.warehouse_manager.sections.professional_info'))
                            ->icon('heroicon-o-briefcase')
                            ->schema([
                                Section::make(__('warehouse.warehouse_manager.sections.professional_info'))
                                    ->description(__('warehouse.warehouse_manager.sections.professional_info_desc'))
                                    ->icon('heroicon-o-briefcase')
                                    ->iconColor('success')
                                    ->schema([
                                        Grid::make(2)
                                            ->schema([
                                                Forms\Components\DatePicker::make('hire_date')
                                                    ->label(__('warehouse.warehouse_manager.hire_date'))
                                                    ->required()
                                                    ->jalali()
                                                    ->maxDate(now())
                                                    ->displayFormat('Y/m/d'),
                                                
                                                Forms\Components\Select::make('employment_status')
                                                    ->label(__('warehouse.warehouse_manager.employment_status'))
                                                    ->options(__('warehouse.warehouse_manager.employment_statuses'))
                                                    ->required()
                                                    ->searchable(),
                                            ]),
                                        
                                        Grid::make(2)
                                            ->schema([
                                                Forms\Components\TextInput::make('position')
                                                    ->label(__('warehouse.warehouse_manager.position'))
                                                    ->required()
                                                    ->maxLength(255),
                                                
                                                Forms\Components\TextInput::make('department')
                                                    ->label(__('warehouse.warehouse_manager.department'))
                                                    ->maxLength(255),
                                            ]),
                                        
                                        Forms\Components\TextInput::make('salary')
                                            ->label(__('warehouse.warehouse_manager.salary'))
                                            ->numeric()
                                            ->minValue(0)
                                            ->step(0.01)
                                            ->suffix('ریال')
                                            ->columnSpanFull(),
                                        
                                        Forms\Components\Textarea::make('job_description')
                                            ->label(__('warehouse.warehouse_manager.job_description'))
                                            ->rows(4)
                                            ->columnSpanFull(),
                                    ])
                                    ->collapsible(),
                            ]),

                        // Tab 3: تخصیص انبار (Warehouse Assignment)
                        Tab::make(__('warehouse.warehouse_manager.sections.warehouse_assignment'))
                            ->icon('heroicon-o-building-office')
                            ->schema([
                                Section::make(__('warehouse.warehouse_manager.sections.warehouse_assignment'))
                                    ->description(__('warehouse.warehouse_manager.sections.warehouse_assignment_desc'))
                                    ->icon('heroicon-o-building-office')
                                    ->iconColor('warning')
                                    ->schema([
                                        Grid::make(2)
                                            ->schema([
                                                Forms\Components\Select::make('warehouse_id')
                                                    ->label(__('warehouse.warehouse_manager.warehouse'))
                                                    ->relationship('warehouse', 'title')
                                                    ->required()
                                                    ->searchable()
                                                    ->preload(),
                                                
                                                Forms\Components\Select::make('user_id')
                                                    ->label(__('warehouse.warehouse_manager.user'))
                                                    ->relationship('user', 'name')
                                                    ->searchable()
                                                    ->preload(),
                                            ]),
                                        
                                        Grid::make(3)
                                            ->schema([
                                                Forms\Components\Toggle::make('is_primary_manager')
                                                    ->label(__('warehouse.warehouse_manager.is_primary_manager'))
                                                    ->default(false),
                                                
                                                Forms\Components\Toggle::make('can_approve_orders')
                                                    ->label(__('warehouse.warehouse_manager.can_approve_orders'))
                                                    ->default(false),
                                                
                                                Forms\Components\Toggle::make('can_manage_inventory')
                                                    ->label(__('warehouse.warehouse_manager.can_manage_inventory'))
                                                    ->default(true),
                                            ]),
                                    ])
                                    ->collapsible(),
                            ]),

                        // Tab 4: تماس اضطراری (Emergency Contact)
                        Tab::make(__('warehouse.warehouse_manager.sections.emergency_contact'))
                            ->icon('heroicon-o-phone')
                            ->schema([
                                Section::make(__('warehouse.warehouse_manager.sections.emergency_contact'))
                                    ->description(__('warehouse.warehouse_manager.sections.emergency_contact_desc'))
                                    ->icon('heroicon-o-phone')
                                    ->iconColor('danger')
                                    ->schema([
                                        Grid::make(2)
                                            ->schema([
                                                Forms\Components\TextInput::make('emergency_contact_name')
                                                    ->label(__('warehouse.warehouse_manager.emergency_contact_name'))
                                                    ->maxLength(255),
                                                
                                                Forms\Components\TextInput::make('emergency_contact_phone')
                                                    ->label(__('warehouse.warehouse_manager.emergency_contact_phone'))
                                                    ->tel()
                                                    ->maxLength(255),
                                            ]),
                                        
                                        Forms\Components\TextInput::make('emergency_contact_relation')
                                            ->label(__('warehouse.warehouse_manager.emergency_contact_relation'))
                                            ->maxLength(255)
                                            ->columnSpanFull(),
                                    ])
                                    ->collapsible(),
                            ]),

                        // Tab 5: اطلاعات تکمیلی (Additional Information)
                        Tab::make(__('warehouse.warehouse_manager.sections.additional_info'))
                            ->icon('heroicon-o-document-text')
                            ->schema([
                                Section::make(__('warehouse.warehouse_manager.sections.additional_info'))
                                    ->description(__('warehouse.warehouse_manager.sections.additional_info_desc'))
                                    ->icon('heroicon-o-document-text')
                                    ->iconColor('info')
                                    ->schema([
                                        Forms\Components\Textarea::make('notes')
                                            ->label(__('warehouse.warehouse_manager.notes'))
                                            ->rows(4)
                                            ->columnSpanFull(),
                                        
                                        Forms\Components\Repeater::make('certifications')
                                            ->label(__('warehouse.warehouse_manager.certifications'))
                                            ->schema([
                                                Forms\Components\TextInput::make('name')
                                                    ->label('نام گواهینامه')
                                                    ->required(),
                                                Forms\Components\TextInput::make('issuer')
                                                    ->label('صادرکننده'),
                                                Forms\Components\DatePicker::make('issue_date')
                                                    ->jalali()
                                                    ->label('تاریخ صدور'),
                                                Forms\Components\DatePicker::make('expiry_date')
                                                    ->jalali()
                                                    ->label('تاریخ انقضا'),
                                            ])
                                            ->columns(2)
                                            ->columnSpanFull(),
                                        
                                        Forms\Components\Repeater::make('skills')
                                            ->label(__('warehouse.warehouse_manager.skills'))
                                            ->schema([
                                                Forms\Components\TextInput::make('name')
                                                    ->label('نام مهارت')
                                                    ->required(),
                                                Forms\Components\Select::make('level')
                                                    ->label('سطح')
                                                    ->options([
                                                        'beginner' => 'مبتدی',
                                                        'intermediate' => 'متوسط',
                                                        'advanced' => 'پیشرفته',
                                                        'expert' => 'متخصص',
                                                    ]),
                                            ])
                                            ->columns(2)
                                            ->columnSpanFull(),
                                    ])
                                    ->collapsible(),
                            ]),
                    ])
                    ->persistTabInQueryString()
                    ->id('warehouse-manager-form-tabs'),
            ]);
    }
}
