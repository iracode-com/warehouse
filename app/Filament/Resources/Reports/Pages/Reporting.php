<?php

namespace App\Filament\Resources\Reports\Pages;

use App\Exports\ReportingExport;
use Filament\Infolists\Concerns\InteractsWithInfolists;
use Filament\Infolists\Contracts\HasInfolists;
use Filament\Notifications\Notification;
use Filament\Facades\Filament;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Resources\Pages\Page;
use Filament\Schemas\Components\Livewire;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Filament\Tables\Filters\QueryBuilder;
use Filament\Tables\Filters\QueryBuilder\Constraints\RelationshipConstraint;
use Filament\Tables\Filters\QueryBuilder\Constraints\RelationshipConstraint\Operators\IsRelatedToOperator;
use Illuminate\Support\Arr;
use App\Models\Reporting\Report;
use App\Filament\Resources\Reports\ReportResource;
use App\Support\Utils;
use Filament\Actions\Action;
use Filament\Actions\SelectAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use ReflectionClass;
// use function App\Support\formComponentsConfiguration;

class Reporting extends Page implements HasForms, HasTable, HasInfolists
{
    use InteractsWithForms,
        InteractsWithTable,
        InteractsWithInfolists;

    public ?array $data = [];
    public static array $models = [];
    public Report $record;
    public string $model;
    public string $activeTab = 'filtering';

    protected static string $resource = ReportResource::class;
    protected string $view = 'filament.resources.reports.pages.reporting';


    public function getTitle(): string|Htmlable
    {
        return __('Filtering');
    }

    public static function getModels(): array
    {
        $resources = Filament::getResources();
        $models = Arr::map($resources, fn($resource) => Arr::prepend([], app($resource)::getModel()));
        $models = Arr::flatten($models);
        $names = Arr::map($models, fn($model) => __(str($model)->afterLast('\\')->value()));

        return array_combine($models, $names);
    }

    public function mount(Report $record): void
    {
        $this->record = $record;
        $this->model = $record->reportable_type;
        $this->form->fill($record->toArray());
    }

    public function table(Table $table): Table
    {
        return $table
            ->query($this->model::query())
            ->columns($this->getColumns())
            // ->groups($this->getGroupColumns())
            ->filters([
                $this->getQueryBuilderFilter()
            ], layout: FiltersLayout::AboveContentCollapsible)
            ->headerActions([
                // SelectAction::make('model')->options(static::getModels()),
                Action::make('create')
                    ->label('ذخیره فیلترها بر روی فایل گزارش')
                    ->color('primary')
                    ->icon('heroicon-o-funnel')
                    ->action(function() {
                        \Log::info('Create button clicked!');
                        $this->submitFilters();
                    }),
                Action::make('export')
                    ->label('دانلود فایل گزارش')
                    ->color('success')
                    ->icon('heroicon-o-arrow-up-tray')
                    ->action(function () {
                        \Log::info('Export button clicked!', [
                            'active_tab' => $this->activeTab,
                            'report_id' => $this->record->id
                        ]);
                        
                        try {
                            // Refresh record to get latest data
                            $this->record->refresh();
                            
                            \Log::info('Starting export', [
                                'report_id' => $this->record->id,
                                'has_query' => !empty($this->record->query),
                                'query_has_record_ids' => isset($this->record->query['record_ids']),
                                'record_ids_count' => isset($this->record->query['record_ids']) ? count($this->record->query['record_ids']) : 0
                            ]);
                            
                            $export = new ReportingExport($this->record);
                            
                            Notification::make()
                                ->title('در حال دانلود...')
                                ->success()
                                ->send();

                            return $export->download();
                        } catch (\Exception $e) {
                            \Log::error('Export failed', [
                                'error' => $e->getMessage(),
                                'trace' => $e->getTraceAsString()
                            ]);
                            
                            return Notification::make()
                                ->title('خطا در خروجی')
                                ->body($e->getMessage())
                                ->danger()
                                ->send();
                        }
                    }),
            ])
            ->defaultGroup($this->getDefaultGroupingRow())
            ->checkIfRecordIsSelectableUsing(fn() => $this->activeTab == 'filtering')
            ->persistFiltersInSession()
            ->deferFilters()
            // ->deferLoading()
            ->recordActions([])
            ->toolbarActions([])
            ->defaultPaginationPageOption(25);
    }

    public function getDefaultGroupingRow()
    {
        $groupingRows = $this->record->grouping_rows;

        if (empty($groupingRows)) {
            return null;
        }

        // If grouping_rows is an array, use the first element or join them
        if (is_array($groupingRows)) {
            $groupingRows = is_array($groupingRows[0] ?? null)
                ? implode(',', $groupingRows[0])
                : implode(',', $groupingRows);
        }

        return Group::make($groupingRows);
    }

    public function form(Schema $form): Schema
    {
        return $form
            ->columns(1)
            ->schema([
                Section::make('تنظیمات خروجی')
                    ->schema([
                        Select::make('export_type')->label('نوع خروجی')
                            ->options([
                                'xlsx' => 'اکسل XLSX',
                                // 'xls' => 'اکسل XLS',
                                // 'csv' => 'CSV',
                                'pdf' => 'PDF',
                                // 'chart' => 'نمودار'
                            ])->default('xlsx'),
                        // Select::make('font')->label('فونت')
                        //     ->options([
                        //         'iransans' => 'ایران سنس',
                        //         'yekanbakh' => 'یکان باخ',
                        //         'iranyekan' => 'ایران یکان',
                        //         'bnazanin' => 'بی نازنین',
                        //         'vazir' => 'وزیر'
                        //     ])->default('iransans'),
                        // Select::make('export_type')->label('نوع خروجی')
                        //     ->options([
                        //         'xlsx' => 'اکسل XLSX',
                        //         // 'xls' => 'اکسل XLS',
                        //         // 'csv' => 'CSV',
                        //         'pdf' => 'PDF',
                        //         // 'chart' => 'نمودار'
                        //     ])->default('xlsx'),
                        // Fieldset::make('اطلاعات هدر')->schema([
                        //     DatePicker::make('report_date')->label('تاریخ گزارش')->jalali()->prefixIcon('heroicon-o-calendar'),
                        //     FileUpload::make('logo')->image()->imageEditor(),
                        //     Textarea::make('header_description')->label('متن دلخواه')->columnSpanFull(),
                        // ]),
                        // Fieldset::make('اطلاعات فوتر')->schema([
                        //     Textarea::make('footer_description')->label('متن دلخواه')->columnSpanFull(),
                        // ]),
                    ])->columns()
            ])
            ->statePath('data');
    }

    public function getColumns(): array
    {
        $columns = Utils::getModelColumns($this->model, true);
        $relationships = Utils::getModelRelationships($this->model);
        $modelItems = ['columns' => $columns, 'relationships' => $relationships];


        $queryBuilder = [];
        foreach ($modelItems['columns'] as $item) {
            $column = TextColumn::make($item['name'])->toggleable();

            if ($item['type_name'] == 'json') {
                continue;
            }

            if (in_array($item['name'], Utils::getForeignKeys($this->model))) {
                continue;
            }

            $constantArray = [];
            if ($item['type_name'] == 'tinyint' && $this->model) {
                $reflection = new ReflectionClass($this->model);
                $candidateConstants = [
                    str($item['name'])->plural()->upper()->value(),
                    str($item['name'])->upper()->value(),
                    str($item['name'])->singular()->upper()->value(),
                    str($item['name'])->singular()->value(),
                    str($item['name'])->plural()->value(),
                ];
                foreach ($candidateConstants as $candidateConstant) {
                    if ($reflection->hasConstant($candidateConstant)) {
                        $constantArray = $reflection->getConstant($candidateConstant);
                        break;
                    }
                }
            }


            $column->label(__(pascalToTitle(str_replace('_', ' ', str_replace('_id', '', $item['name'])))));
            
            $queryBuilder[] = match ($item['type_name']) {
                'int', 'bigint', 'smallint', 'mediumint', 'decimal', 'float', 'real', 'double' => $column->numeric(),
                'varchar', 'text', 'char', 'tinytext', 'mediumtext', 'longtext' => $column->searchable(),
                'date', 'datetime', 'timestamp', 'time', 'year' => $column->jalaliDateTime(),
                'tinyint' => $column->getStateUsing(function ($record) use ($constantArray, $item) {
                    try {
                        $value = array_key_exists($record[$item['name']], $constantArray) ? $constantArray[$record[$item['name']]] : $record[$item['name']];
                        return $value;
                    } catch (\Throwable $th) {
                        return $record[$item['name']];
                    }
                }),
                default => $column,
            };
        }

        $relationColumns = [];
        foreach ($modelItems['relationships']['relations'] as $key => $item) {
            if (is_array($item) && (in_array('name', $item) || in_array('title', $item) || in_array('id', $item))) {
                $modelFieldName = match (true) {
                    in_array('name', $item) => 'name',
                    in_array('title', $item) => 'title',
                    in_array('id', $item) => 'id',
                };

                $name = $key . '.' . $modelFieldName;
                // $label = __(pascalToTitle(Utils::translate($key))) . ' - ' . Utils::translate($modelFieldName);
                $label = __(pascalToTitle(Utils::translate($key)));
                $relationColumns[] = TextColumn::make($name)
                    ->label($label)
                    ->searchable();
            }
            // foreach ($item as $column) {
            //     $name           = $key . '.' . $column;
            //     $label          = Utils::translate($key) . ' - ' . Utils::translate($column);
            //     $queryBuilder[] = TextColumn::make($name)
            //         ->label($label)
            //         ->toggleable(isToggledHiddenByDefault: true)
            //         ->searchable();
            // }
        }

        // Add relation columns after id (id is the first column)
        array_splice($queryBuilder, 1, 0, $relationColumns);



        return $queryBuilder;
    }

    public function getGroupColumns(): array
    {
        $columns = Utils::getModelColumns($this->model);
        $relationships = Utils::getModelRelationships($this->model);
        $modelItems = ['columns' => $columns, 'relationships' => $relationships];

        $groups = [];
        foreach ($modelItems['columns'] as $item) {
            $column = Group::make($item['name'])->label(Utils::translate($item['name']));
            $groups[] = $column;
        }

        return $groups;
    }

    public function getQueryBuilderFilter(): QueryBuilder|null
    {
        $columns = Utils::getModelColumns($this->model, true);
        $relationships = Utils::getModelRelationships($this->model);
        $modelItems = ['columns' => $columns, 'relationships' => $relationships];

        $queryBuilder = [];

        foreach ($modelItems['columns'] as $item) {
            if ($item['type_name'] == 'json') {
                continue;
            }

            if (in_array($item['name'], Utils::getForeignKeys($this->model))) {
                continue;
            }

            if ($item['type_name'] == 'tinyint' && $this->model) {
                $reflection = new ReflectionClass($this->model);
                $candidateConstants = [
                    str($item['name'])->plural()->upper()->value(),
                    str($item['name'])->upper()->value(),
                    str($item['name'])->singular()->upper()->value(),
                    str($item['name'])->singular()->value(),
                    str($item['name'])->plural()->value(),
                ];

                $constantArray = [];
                $hasConstant = false;
                foreach ($candidateConstants as $candidateConstant) {
                    if ($reflection->hasConstant($candidateConstant)) {
                        $constantArray = $reflection->getConstant($candidateConstant);
                        $hasConstant = true;
                        break;
                    }
                }

                if ($hasConstant) {
                    $queryBuilder[] = QueryBuilder\Constraints\SelectConstraint::make($item['name'])
                        ->options($constantArray)
                        ->getOptionLabelUsing(fn($value) => $constantArray[$value] ?? $value);
                } else {
                    $queryBuilder[] = QueryBuilder\Constraints\BooleanConstraint::make($item['name']);
                }
            } else {
                $constraint = match ($item['type_name']) {
                'int', 'bigint', 'smallint', 'mediumint', 'decimal', 'float', 'real', 'double' => QueryBuilder\Constraints\NumberConstraint::make($item['name']),
                'date', 'datetime', 'timestamp', 'time', 'year' => QueryBuilder\Constraints\DateConstraint::make($item['name']),
                    'boolean' => QueryBuilder\Constraints\BooleanConstraint::make($item['name']),
                default => QueryBuilder\Constraints\TextConstraint::make($item['name']),
            };

                $queryBuilder[] = $constraint;
            }
        }

        foreach ($modelItems['relationships']['relations'] as $key => $item) {
            if (is_array($item) && (in_array('name', $item) || in_array('title', $item) || in_array('id', $item))) {
                $modelFieldName = match (true) {
                    in_array('name', $item) => 'name',
                    in_array('title', $item) => 'title',
                    in_array('id', $item) => 'id',
                };

                $name = $key . '.' . $modelFieldName;
                $label = __(pascalToTitle(Utils::translate($key)));

                $queryBuilder[] = QueryBuilder\Constraints\TextConstraint::make($name)
                    ->label($label)
                    ->relationship(name: $key, titleAttribute: $modelFieldName);
            }
        }

        return QueryBuilder::make()->constraints($queryBuilder);
    }

    public function submitFilters(): void
    {
        try {
            \Log::info('submitFilters called', [
                'report_id' => $this->record->id,
                'model' => $this->model
            ]);
            
            $visibleColumns = array_keys($this->table->getVisibleColumns());

            // Get the actual filtered query from the table (includes all filters applied)
            // This is the key: we need to get the query that already has filters applied by Livewire
            $query = $this->getFilteredTableQuery();
            
            \Log::info('Query obtained', [
                'query_sql' => $query->toSql(),
                'query_bindings' => $query->getBindings()
            ]);
            
            // Debug: check total records before filter
            $totalInDb = $this->model::count();

            // Eager load ALL relationships that might be used in columns
            $relationships = [];
            foreach ($visibleColumns as $column) {
                if (str_contains($column, '.')) {
                    $relationName = explode('.', $column)[0];
                    if (!in_array($relationName, $relationships)) {
                        $relationships[] = $relationName;
                    }
                }
            }

            // Get filtered record IDs (the most reliable way)
            $recordIds = $query->pluck('id')->toArray();
            $recordsCount = count($recordIds);
            
            \Log::info('Record IDs collected', [
                'count' => $recordsCount,
                'first_10' => array_slice($recordIds, 0, 10)
            ]);
            
            // Prepare query data
            $queryData = [
                'record_ids' => $recordIds, // Save filtered record IDs
                'columns' => $visibleColumns,
                'relationships' => $relationships,
            ];
            
            // Show notification with counts
            // Notification::make()
            //     ->title(__('Filters Applied'))
            //     ->body("Total in DB: {$totalInDb} | Filtered: {$recordsCount} | IDs Sample: " . implode(',', array_slice($recordIds, 0, 3)))
            //     ->info()
            //     ->send();

            // Save query configuration with filtered IDs
            $this->record->update([
                'data' => null, // Don't save data, we'll generate it on export
                'header' => $visibleColumns,
                'grouping_rows' => $this->table->getGrouping()?->getId(),
                'records_count' => $recordsCount,
                'query' => $queryData,
                'step' => 1
            ]);
            
            // Verify it was saved
            $this->record->refresh();
            \Log::info('Query data saved to report', [
                'report_id' => $this->record->id,
                'query_data' => $this->record->query,
                'records_count' => $recordsCount,
                'first_5_ids' => array_slice($recordIds, 0, 5)
            ]);

            Notification::make()->title(__('Saved.'))->success()->send();
            // $this->activeTab = 'settings';
            
        } catch (\Exception $e) {
            \Log::error('submitFilters failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            Notification::make()
                ->title('خطا در ذخیره فیلترها')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }

    public function submitSettings(): void
    {
        $this->record->update([
            ...$this->form->getState(),
            'step' => 2
        ]);

        Notification::make()->title(__('Saved.'))->success()->send();
        $this->activeTab = 'preview';
    }

    public function updatedModel(): void
    {
        $this->resetTable();
    }
}