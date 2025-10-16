<?php

namespace App\Support;

use Filament\Facades\Filament;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Schema;
use ReflectionClass;
use Illuminate\Database\Eloquent\Relations\Relation;

class Utils
{
    public static function translate($key): string
    {
        return __(
            str($key)
                ->headline()
                ->lower()
                ->ucfirst()
                ->before('.')
                ->value()
        );
    }

    public static function getModelColumns($model, $excludeForeignKeys = false): array
    {
        $modelObject = app($model);
        $columns = $modelObject->getConnection()->getSchemaBuilder()->getColumns($modelObject->getTable());


        // Check if the model has hidden columns defined
        $hiddenColumns = $modelObject->getHidden();

        if (!empty($hiddenColumns)) {
            $columns = collect($columns)->reject(function ($column) use ($hiddenColumns) {
                return in_array($column['name'], $hiddenColumns);
            })->values()->toArray();
        }

        if ($excludeForeignKeys) {
            $columns = collect($columns)->reject(fn($column) => str_ends_with($column['name'], '_id'))->values()->toArray();
        }

        return $columns;
    }

    public static function getModelFillables($model): array
    {
        return app($model)->getFillable();
    }

    public static function getModelRelationships(string $model, array $filterTypes = []): array
    {
        $instance = app($model);
        $reflector = new ReflectionClass($model);

        $relationTypes = [
            'HasOne',
            'HasMany',
            'BelongsTo',
            'BelongsToMany',
            'MorphToMany',
            'MorphMany',
            'MorphTo',
        ];

        // اگر کاربر نوع خاصی خواسته، فقط همونا
        $allowedTypes = empty($filterTypes)
            ? $relationTypes
            : array_intersect($relationTypes, $filterTypes);

        $relationships = collect($reflector->getMethods())
            ->filter(function ($method) use ($instance, $allowedTypes) {
                if ($method->class !== get_class($instance))
                    return false;
                if (!$method->isPublic() || $method->getNumberOfParameters() > 0)
                    return false;

                try {
                    $result = $method->invoke($instance);
                    if ($result instanceof Relation) {
                        $relationClass = class_basename(get_class($result));
                        return in_array($relationClass, $allowedTypes);
                    }
                } catch (\Throwable $e) {
                    // بعضی متدها ممکنه ارور بدن، نادیده می‌گیریم
                }

                return false;
            })
            ->mapWithKeys(function ($method) use ($instance) {
                try {
                    $result = $method->invoke($instance);
                    $relationClass = class_basename(get_class($result));
                    $relatedModel = $result->getRelated();

                    // گرفتن یک رکورد نمونه از مدل مرتبط
                    $record = $relatedModel::first()?->toArray();
                    if (!$record) {
                        return [
                            $method->name => []
                        ];
                    }

                    $record = is_array(Arr::first($record))
                        ? Arr::first($record)
                        : $record;

                    return [
                        $method->name => array_keys($record),
                    ];
                } catch (\Throwable $e) {
                    return [
                        $method->name => []
                    ];
                }
            })
            ->toArray();

        // پیدا کردن fqcn از لیست منابع فیلامنت
        $fqcn = static::getResources()[$model]['fqcn'] ?? null;

        return [
            'model' => $model,
            'fqcn' => $fqcn,
            'relations' => $relationships,
        ];
    }

    public static function getForeignKeys($model): array
    {
        $foreignKeys = Schema::getConnection()->getSchemaBuilder()->getForeignKeys(app($model)->getTable());
        return collect($foreignKeys)
            ->map(fn($column) => $column['columns'])
            ->flatten()
            ->toArray();
    }

    public static function getResources(): ?array
    {
        $resources = Filament::getResources();
        return collect($resources)
            ->mapWithKeys(function ($resource) {
                return [
                    $resource::getModel() => [
                        'model' => $resource::getModel(),
                        'fqcn' => $resource,
                    ],
                ];
            })
            ->sortKeys()
            ->toArray();
    }
}