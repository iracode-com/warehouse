<?php

namespace App\Filament\Resources;

use Filament\Schemas\Components\Group;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Actions\Action;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\PersonnelResource\Pages\ListPersonnels;
use App\Filament\Resources\PersonnelResource\Pages\CreatePersonnel;
use App\Filament\Resources\PersonnelResource\Pages\EditPersonnel;
use App\Filament\Resources\PersonnelResource\Pages;
use App\Filament\Resources\PersonnelResource\RelationManagers;
use App\Models\Personnel\PersonnelFileType;
use App\Models\Location\City;
use App\Models\Personnel\CooperationType;
use App\Models\Location\Country;
use App\Models\Courses\EducationDegree;
use App\Models\Courses\EducationField;
use App\Models\Personnel\EmploymentType;
use App\Models\Personnel\ExpertField;
use App\Models\Personnel\Personnel;
use App\Models\Personnel\PersonnelContactInformationType;
use App\Models\Organization\Position;
use App\Models\Location\Province;
use App\Models\Organization\OrganizationalStructure;
use App\Models\Padafand\PropertiesMachineryType;
use App\Models\User;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Support\Enums\Alignment;
use Filament\Support\Enums\IconPosition;
use Filament\Support\Enums\IconSize;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use JaOcero\RadioDeck\Forms\Components\RadioDeck;

class PersonnelResource extends Resource
{
    protected static ?string $model = Personnel::class;

    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'family', 'mobile', 'phone', 'national_code', 'identity_code', 'personnel_code'];
    }

    public static function getNavigationGroup(): ?string
    {
        return __('warehouse.navigation_groups.user_management');
    }

    public static function getNavigationLabel(): string
    {
        return __('Human Resources');
    }

    public static function getPluralLabel(): ?string
    {
        return __('Personnel');
    }

    public static function getLabel(): ?string
    {
        return __('Personnel');
    }

    public static function requiredFields()
    {
        return [
            Group::make()
                ->columns(3)
                ->schema([
                    TextInput::make('name')
                        ->label(__("Name"))
                        ->required()
                        ->maxLength(100),
                    TextInput::make('family')
                        ->label(__("Family Name"))
                        ->required()
                        ->maxLength(100),
                    Select::make('gender')
                        ->label(__("Gender"))
                        ->searchable()
                        ->options(Personnel::GENDERS)
                        ->default(1)
                        ->required(),
                    Select::make('marital_status')
                        ->searchable()
                        ->label(__("Marital Status"))
                        ->options(Personnel::MARITAL_STATUSES)
                        ->default(1)
                        ->required(),
                    Select::make('is_iranian')
                        ->searchable()
                        ->label(__("Nationality Type"))
                        ->live()
                        ->options(Personnel::IS_IRANIAN)
                        ->required(),
                    Select::make('job_field')
                        ->searchable()
                        ->required()
                        ->label(__("Personnel Job Field"))
                        ->options(Personnel::JOB_FIELDS),
                    Toggle::make('status')
                        ->label(__('Is Active'))
                        ->required()
                        ->default(1),
                ])
        ];
    }

    public static function insertActionWithRequiredFields()
    {
        return Action::make('create')
            ->label(__("Personnel"))
            ->color('gray')
            ->icon('heroicon-o-plus')
            ->schema(static::requiredFields())
            ->action(function (Component $component, $data) {
                $personnels = Personnel::where('status', 1)->get();
                $personnels_array = [];
                $personnels->map(function ($item) use (&$personnels_array) {
                    $personnels_array[$item->id] = $item->name . ' ' . $item->family;
                });
                Personnel::query()->create($data);
                $component->refreshSelectedOptionLabel();
                $component->options($personnels_array);
            });
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Schemas\Components\Section::make(__('اطلاعات شخصی'))
                    ->description(__('اطلاعات هویتی و تماس پرسنل'))
                    ->icon('heroicon-o-user')
                    ->iconColor('primary')
                    ->columnSpanFull()
                    ->schema([
                        \Filament\Schemas\Components\Grid::make(3)
                            ->schema([
                                TextInput::make('name')
                                    ->label(__("نام"))
                                    ->required()
                                    ->maxLength(100),
                                
                                TextInput::make('family')
                                    ->label(__("نام خانوادگی"))
                                    ->required()
                                    ->maxLength(100),
                                
                                Select::make('gender')
                                    ->label(__("جنسیت"))
                                    ->searchable()
                                    ->options(Personnel::GENDERS)
                                    ->default(1)
                                    ->required(),
                            ]),
                        
                        \Filament\Schemas\Components\Grid::make(2)
                            ->schema([
                                Select::make('province_id')
                                    ->label(__("نام استان"))
                                    ->searchable()
                                    ->live()
                                    ->required()
                                    ->options(Province::where('status', 1)->get()->pluck('name', 'id')),
                                
                                Select::make('city_id')
                                    ->label(__("نام شهرستان"))
                                    ->searchable()
                                    ->required()
                                    ->options(City::where('status', 1)->get()->pluck('name', 'id')),
                            ]),
                        
                        \Filament\Schemas\Components\Grid::make(2)
                            ->schema([
                                TextInput::make('mobile')
                                    ->label(__("شماره همراه"))
                                    ->maxLength(11),
                                
                                TextInput::make('phone')
                                    ->label(__("شماره ثابت محل کار"))
                                    ->tel()
                                    ->maxLength(11),
                            ]),
                        
                        \Filament\Schemas\Components\Grid::make(2)
                            ->schema([
                                DatePicker::make('birth_date')
                                    ->jalali()
                                    ->label(__("تاریخ تولد"))
                                    ->live()
                                    ->afterStateUpdated(function ($state, callable $set) {
                                        if ($state) {
                                            $age = \Carbon\Carbon::parse($state)->age;
                                            $set('age', $age);
                                        }
                                    }),
                                
                                TextInput::make('age')
                                    ->label(__("سن"))
                                    ->disabled()
                                    ->dehydrated(false),
                            ]),
                        
                        TextInput::make('national_code')
                            ->label(__("کد ملی"))
                            ->maxLength(10)
                            ->unique(ignoreRecord: true),
                    ])
                    ->collapsible(),

                \Filament\Schemas\Components\Section::make(__('اطلاعات تحصیلی'))
                    ->description(__('مدارک و رشته تحصیلی'))
                    ->icon('heroicon-o-academic-cap')
                    ->iconColor('success')
                    ->columnSpanFull()
                    ->schema([
                        \Filament\Schemas\Components\Grid::make(2)
                            ->schema([
                                Select::make('education_degree_id')
                                    ->searchable()
                                    ->label(__("آخرین مدرک تحصیلی"))
                                    ->options(EducationDegree::where('status', 1)->get()->pluck('name', 'id')),
                                
                                Select::make('education_field_id')
                                    ->searchable()
                                    ->label(__("رشته تحصیلی"))
                                    ->options(EducationField::where('status', 1)->get()->pluck('name', 'id')),
                            ]),
                    ])
                    ->collapsible(),

                \Filament\Schemas\Components\Section::make(__('اطلاعات شغلی'))
                    ->description(__('نوع استخدام و پست کارگزینی'))
                    ->icon('heroicon-o-briefcase')
                    ->iconColor('warning')
                    ->columnSpanFull()
                    ->schema([
                        \Filament\Schemas\Components\Grid::make(2)
                            ->schema([
                                Select::make('employment_type_id')
                                    ->searchable()
                                    ->label(__("نوع استخدام"))
                                    ->options(Personnel::EMPLOYMENT_TYPES),
                                
                                Select::make('position_id')
                                    ->searchable()
                                    ->label(__("پست کارگزینی"))
                                    ->options(Position::where('status', 1)->get()->pluck('name', 'id')),
                            ]),
                        
                        Select::make('activity_location_ids')
                            ->label(__("محل فعالیت فعلی"))
                            ->multiple()
                            ->relationship('activity_locations', 'name')
                            ->searchable()
                            ->preload()
                            ->columnSpanFull(),
                        
                        Select::make('course_ids')
                            ->label(__("دوره های گذرانده شده"))
                            ->multiple()
                            ->relationship('courses', 'name')
                            ->searchable()
                            ->preload()
                            ->columnSpanFull(),
                    ])
                    ->collapsible(),

                \Filament\Schemas\Components\Section::make(__('فایل‌ها و تصاویر'))
                    ->description(__('عکس پرسنلی و فایل رزومه'))
                    ->icon('heroicon-o-photo')
                    ->iconColor('info')
                    ->columnSpanFull()
                    ->schema([
                        \Filament\Schemas\Components\Grid::make(2)
                            ->schema([
                                FileUpload::make('personnel_photo')
                                    ->label(__("عکس پرسنلی"))
                                    ->image()
                                    ->directory('personnel/photos')
                                    ->imageEditor(),
                                
                                FileUpload::make('resume_file')
                                    ->label(__("ارسال فایل رزومه"))
                                    ->directory('personnel/resumes')
                                    ->downloadable(),
                            ]),
                    ])
                    ->collapsible(),

                \Filament\Schemas\Components\Section::make(__('وضعیت و ارتباطات'))
                    ->description(__('وضعیت فعال/غیرفعال و روش‌های ارتباطی'))
                    ->icon('heroicon-o-signal')
                    ->iconColor('secondary')
                    ->columnSpanFull()
                    ->schema([
                        \Filament\Schemas\Components\Grid::make(2)
                            ->schema([
                                Toggle::make('status')
                                    ->label(__('وضعیت (فعال/غیر فعال)'))
                                    ->required()
                                    ->default(1),
                                
                                Select::make('sms_delivery_method')
                                    ->label(__("ارسال پیامک از طریق"))
                                    ->options(__('common-options.message_sending')),
                            ]),
                        
                        \Filament\Schemas\Components\Grid::make(2)
                            ->schema([
                                Select::make('warehouse_management_level')
                                    ->label(__("انبارداری"))
                                    ->options(__('common-options.warehouse_management')),
                                
                                Select::make('software_skills')
                                    ->label(__("نرم افزار انبار"))
                                    ->options(__('common-options.software_level')),
                            ]),
                        
                        \Filament\Schemas\Components\Grid::make(2)
                            ->schema([
                                Toggle::make('icdl_certificate')
                                    ->label(__("ICDL مهارت 7 گانه")),
                                
                                Toggle::make('forklift_license')
                                    ->label(__("گواهینامه لیفتراک")),
                            ]),
                        
                        \Filament\Schemas\Components\Grid::make(2)
                            ->schema([
                                Toggle::make('prefers_sms')
                                    ->label(__("پیامک")),
                                
                                Toggle::make('prefers_bale')
                                    ->label(__("پیام رسان های داخلی")),
                            ]),
                    ])
                    ->collapsible(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label(__("نام"))
                    ->searchable()
                    ->sortable(),
                
                TextColumn::make('family')
                    ->label(__("نام خانوادگی"))
                    ->searchable()
                    ->sortable(),
                
                TextColumn::make('gender')
                    ->getStateUsing(function ($record) {
                        return Personnel::GENDERS[$record->gender] ?? '';
                    })
                    ->label(__("جنسیت")),
                
                TextColumn::make('province.name')
                    ->label(__("استان"))
                    ->getStateUsing(function ($record) {
                        return $record->province?->name;
                    })
                    ->searchable(),
                
                TextColumn::make('city.name')
                    ->label(__("شهرستان"))
                    ->getStateUsing(function ($record) {
                        return $record->city?->name;
                    })
                    ->searchable(),
                
                TextColumn::make('mobile')
                    ->label(__("شماره همراه"))
                    ->searchable(),
                
                TextColumn::make('phone')
                    ->label(__("شماره ثابت"))
                    ->searchable(),
                
                TextColumn::make('birth_date')
                    ->label(__("تاریخ تولد"))
                    ->jalaliDate()
                    ->sortable(),
                
                TextColumn::make('age')
                    ->label(__("سن"))
                    ->getStateUsing(function ($record) {
                        if ($record->birth_date) {
                            return \Carbon\Carbon::parse($record->birth_date)->age;
                        }
                        return null;
                    }),
                
                TextColumn::make('national_code')
                    ->label(__("کد ملی"))
                    ->searchable(),
                
                TextColumn::make('education_degree.name')
                    ->label(__("آخرین مدرک تحصیلی"))
                    ->getStateUsing(function ($record) {
                        return $record->education_degree?->name;
                    })
                    ->searchable(),
                
                TextColumn::make('education_field.name')
                    ->label(__("رشته تحصیلی"))
                    ->getStateUsing(function ($record) {
                        return $record->education_field?->name;
                    })
                    ->searchable(),
                
                TextColumn::make('employment_type_id')
                    ->label(__("نوع استخدام"))
                    ->getStateUsing(function ($record) {
                        return Personnel::EMPLOYMENT_TYPES[$record->employment_type_id] ?? '';
                    }),
                
                TextColumn::make('position.name')
                    ->label(__("پست کارگزینی"))
                    ->getStateUsing(function ($record) {
                        return $record->position?->name;
                    })
                    ->searchable()
                    ->limit(50),
                
                ToggleColumn::make('status')
                    ->label(__('وضعیت'))
                    ->sortable(),
                
                TextColumn::make('created_at')
                    ->jalaliDateTime()
                    ->label(__("تاریخ ایجاد"))
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
                    DeleteBulkAction::make()
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
            'index' => ListPersonnels::route('/'),
            'create' => CreatePersonnel::route('/create'),
            'edit' => EditPersonnel::route('/{record}/edit'),
        ];
    }
}
