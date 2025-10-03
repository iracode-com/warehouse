<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\CategoryAttribute;
use App\Models\Rule;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Creating category hierarchy...');

        // Create root categories (level 1)
        $reliefCategory = Category::create([
            'code' => 'MAIN-0001',
            'name' => 'امدادی',
            'description' => 'کالاهای امدادی و کمک‌رسانی',
            'hierarchy_level' => 1,
            'parent_id' => null,
            'order_index' => 1,
            'status' => 'active',
            'icon' => 'heroicon-o-heart',
            'color' => '#ef4444',
            'is_leaf' => false,
            'children_count' => 0,
            'items_count' => 0,
        ]);

        $scrapCategory = Category::create([
            'code' => 'MAIN-0002',
            'name' => 'اسقاط و مستعمل',
            'description' => 'کالاهای اسقاط و مستعمل',
            'hierarchy_level' => 1,
            'parent_id' => null,
            'order_index' => 2,
            'status' => 'active',
            'icon' => 'heroicon-o-trash',
            'color' => '#6b7280',
            'is_leaf' => false,
            'children_count' => 0,
            'items_count' => 0,
        ]);

        $sparePartsCategory = Category::create([
            'code' => 'MAIN-0003',
            'name' => 'لوازم و قطعات یدکی',
            'description' => 'قطعات یدکی و لوازم جانبی',
            'hierarchy_level' => 1,
            'parent_id' => null,
            'order_index' => 3,
            'status' => 'active',
            'icon' => 'heroicon-o-wrench-screwdriver',
            'color' => '#3b82f6',
            'is_leaf' => false,
            'children_count' => 0,
            'items_count' => 0,
        ]);

        // Create sub-categories (level 2)
        $rescueEquipment = Category::create([
            'code' => 'SUB-0001',
            'name' => 'تجهیزات امداد و نجات',
            'description' => 'تجهیزات مورد نیاز برای امداد و نجات',
            'hierarchy_level' => 2,
            'parent_id' => $reliefCategory->id,
            'order_index' => 1,
            'status' => 'active',
            'icon' => 'heroicon-o-shield-check',
            'color' => '#10b981',
            'is_leaf' => false,
            'children_count' => 0,
            'items_count' => 0,
        ]);

        $aerialAid = Category::create([
            'code' => 'SUB-0002',
            'name' => 'لوازم امداد هوایی',
            'description' => 'تجهیزات مخصوص امداد هوایی',
            'hierarchy_level' => 2,
            'parent_id' => $reliefCategory->id,
            'order_index' => 2,
            'status' => 'active',
            'icon' => 'heroicon-o-airplane',
            'color' => '#8b5cf6',
            'is_leaf' => false,
            'children_count' => 0,
            'items_count' => 0,
        ]);

        $operationalReady = Category::create([
            'code' => 'SUB-0003',
            'name' => 'آماده عملیات',
            'description' => 'کالاهای آماده برای عملیات',
            'hierarchy_level' => 2,
            'parent_id' => $reliefCategory->id,
            'order_index' => 3,
            'status' => 'active',
            'icon' => 'heroicon-o-check-circle',
            'color' => '#f59e0b',
            'is_leaf' => false,
            'children_count' => 0,
            'items_count' => 0,
        ]);

        // Create sub-sub-categories (level 3)
        $tents = Category::create([
            'code' => 'SUBSUB-0001',
            'name' => 'چادر و سرپناه',
            'description' => 'انواع چادر و سرپناه',
            'hierarchy_level' => 3,
            'parent_id' => $rescueEquipment->id,
            'order_index' => 1,
            'status' => 'active',
            'icon' => 'heroicon-o-home',
            'color' => '#06b6d4',
            'is_leaf' => false,
            'children_count' => 0,
            'items_count' => 0,
        ]);

        $sleeping = Category::create([
            'code' => 'SUBSUB-0002',
            'name' => 'کیسه خواب و پتو',
            'description' => 'وسایل خواب و گرمایش',
            'hierarchy_level' => 3,
            'parent_id' => $rescueEquipment->id,
            'order_index' => 2,
            'status' => 'active',
            'icon' => 'heroicon-o-moon',
            'color' => '#84cc16',
            'is_leaf' => false,
            'children_count' => 0,
            'items_count' => 0,
        ]);

        $medical = Category::create([
            'code' => 'SUBSUB-0003',
            'name' => 'ابزارهای پزشکی',
            'description' => 'تجهیزات و ابزارهای پزشکی',
            'hierarchy_level' => 3,
            'parent_id' => $rescueEquipment->id,
            'order_index' => 3,
            'status' => 'active',
            'icon' => 'heroicon-o-heart',
            'color' => '#f97316',
            'is_leaf' => false,
            'children_count' => 0,
            'items_count' => 0,
        ]);

        // Create items (level 4)
        $familyTent = Category::create([
            'code' => 'ITEM-0001',
            'name' => 'چادر خانوادگی 4 نفره',
            'description' => 'چادر مناسب برای خانواده 4 نفره',
            'hierarchy_level' => 4,
            'parent_id' => $tents->id,
            'order_index' => 1,
            'status' => 'active',
            'icon' => 'heroicon-o-home',
            'color' => '#06b6d4',
            'is_leaf' => true,
            'children_count' => 0,
            'items_count' => 1,
        ]);

        $singleTent = Category::create([
            'code' => 'ITEM-0002',
            'name' => 'چادر تک نفره استاندارد',
            'description' => 'چادر مناسب برای یک نفر',
            'hierarchy_level' => 4,
            'parent_id' => $tents->id,
            'order_index' => 2,
            'status' => 'active',
            'icon' => 'heroicon-o-home',
            'color' => '#06b6d4',
            'is_leaf' => true,
            'children_count' => 0,
            'items_count' => 1,
        ]);

        $warmBlanket = Category::create([
            'code' => 'ITEM-0003',
            'name' => 'پتو گرم 2 متری',
            'description' => 'پتو گرم مناسب برای زمستان',
            'hierarchy_level' => 4,
            'parent_id' => $sleeping->id,
            'order_index' => 1,
            'status' => 'active',
            'icon' => 'heroicon-o-moon',
            'color' => '#84cc16',
            'is_leaf' => true,
            'children_count' => 0,
            'items_count' => 1,
        ]);

        $sleepingBag = Category::create([
            'code' => 'ITEM-0004',
            'name' => 'کیسه خواب زمستانی',
            'description' => 'کیسه خواب مناسب برای هوای سرد',
            'hierarchy_level' => 4,
            'parent_id' => $sleeping->id,
            'order_index' => 2,
            'status' => 'active',
            'icon' => 'heroicon-o-moon',
            'color' => '#84cc16',
            'is_leaf' => true,
            'children_count' => 0,
            'items_count' => 1,
        ]);

        $firstAidKit = Category::create([
            'code' => 'ITEM-0005',
            'name' => 'جعبه کمک‌های اولیه کامل',
            'description' => 'جعبه کامل کمک‌های اولیه',
            'hierarchy_level' => 4,
            'parent_id' => $medical->id,
            'order_index' => 1,
            'status' => 'active',
            'icon' => 'heroicon-o-heart',
            'color' => '#f97316',
            'is_leaf' => true,
            'children_count' => 0,
            'items_count' => 1,
        ]);

        $oxygenMask = Category::create([
            'code' => 'ITEM-0006',
            'name' => 'ماسک اکسیژن قابل حمل',
            'description' => 'ماسک اکسیژن قابل حمل برای امداد',
            'hierarchy_level' => 4,
            'parent_id' => $medical->id,
            'order_index' => 2,
            'status' => 'active',
            'icon' => 'heroicon-o-heart',
            'color' => '#f97316',
            'is_leaf' => true,
            'children_count' => 0,
            'items_count' => 1,
        ]);

        // Update full paths
        $this->updateFullPaths();

        // Create attributes for categories
        $this->createAttributes();

        // Create rules
        $this->createRules();

        $this->command->info('Category hierarchy created successfully!');
    }

    /**
     * Update full paths for all categories
     */
    protected function updateFullPaths(): void
    {
        $categories = Category::all();
        
        foreach ($categories as $category) {
            $category->updateFullPath();
        }
    }

    /**
     * Create attributes for categories
     */
    protected function createAttributes(): void
    {
        $this->command->info('Creating category attributes...');

        // Attributes for relief items
        $reliefCategory = Category::where('code', 'MAIN-0001')->first();
        
        CategoryAttribute::create([
            'category_id' => $reliefCategory->id,
            'name' => 'risk_level',
            'label' => 'سطح ریسک',
            'type' => 'select',
            'options' => [
                'low' => 'کم',
                'medium' => 'متوسط',
                'high' => 'بالا',
                'critical' => 'بحرانی',
            ],
            'is_required' => true,
            'is_searchable' => true,
            'is_filterable' => true,
            'order_index' => 1,
        ]);

        CategoryAttribute::create([
            'category_id' => $reliefCategory->id,
            'name' => 'storage_capacity',
            'label' => 'ظرفیت ذخیره‌سازی (مترمکعب)',
            'type' => 'number',
            'is_required' => false,
            'is_searchable' => true,
            'is_filterable' => true,
            'order_index' => 2,
        ]);

        // Attributes for medical items
        $medicalCategory = Category::where('code', 'SUBSUB-0003')->first();
        
        CategoryAttribute::create([
            'category_id' => $medicalCategory->id,
            'name' => 'expiration_date',
            'label' => 'تاریخ انقضا',
            'type' => 'date',
            'is_required' => true,
            'is_searchable' => true,
            'is_filterable' => true,
            'order_index' => 1,
        ]);

        CategoryAttribute::create([
            'category_id' => $medicalCategory->id,
            'name' => 'safety_standard',
            'label' => 'استاندارد ایمنی',
            'type' => 'select',
            'options' => [
                'iso' => 'ISO',
                'ce' => 'CE',
                'fda' => 'FDA',
                'who' => 'WHO',
            ],
            'is_required' => true,
            'is_searchable' => true,
            'is_filterable' => true,
            'order_index' => 2,
        ]);

        // Attributes for tents
        $tentsCategory = Category::where('code', 'SUBSUB-0001')->first();
        
        CategoryAttribute::create([
            'category_id' => $tentsCategory->id,
            'name' => 'capacity',
            'label' => 'ظرفیت (نفر)',
            'type' => 'number',
            'is_required' => true,
            'is_searchable' => true,
            'is_filterable' => true,
            'order_index' => 1,
        ]);

        CategoryAttribute::create([
            'category_id' => $tentsCategory->id,
            'name' => 'weather_resistance',
            'label' => 'مقاومت در برابر آب و هوا',
            'type' => 'select',
            'options' => [
                'basic' => 'پایه',
                'standard' => 'استاندارد',
                'premium' => 'پیشرفته',
                'extreme' => 'شدید',
            ],
            'is_required' => true,
            'is_searchable' => true,
            'is_filterable' => true,
            'order_index' => 2,
        ]);
    }

    /**
     * Create rules for categories
     */
    protected function createRules(): void
    {
        $this->command->info('Creating rules...');

        // Rule for low stock
        Rule::create([
            'name' => 'موجودی کم',
            'description' => 'هشدار زمانی که موجودی کالا کمتر از حد مجاز باشد',
            'category_id' => null, // Applies to all categories
            'attribute_id' => null,
            'rule_type' => 'numeric',
            'condition_type' => 'less_than',
            'condition_value' => '10',
            'alert_type' => 'warning',
            'alert_title' => 'هشدار موجودی کم',
            'alert_message' => 'موجودی کالای {category_name} کمتر از حد مجاز است.',
            'priority' => 5,
            'is_active' => true,
            'is_realtime' => false,
            'check_interval' => 3600,
        ]);

        // Rule for expiration date
        $expirationAttribute = CategoryAttribute::where('name', 'expiration_date')->first();
        
        if ($expirationAttribute) {
            Rule::create([
                'name' => 'تاریخ انقضای نزدیک',
                'description' => 'هشدار زمانی که تاریخ انقضا نزدیک باشد',
                'category_id' => $expirationAttribute->category_id,
                'attribute_id' => $expirationAttribute->id,
                'rule_type' => 'date',
                'condition_type' => 'less_than',
                'condition_value' => now()->addDays(30)->format('Y-m-d'),
                'alert_type' => 'error',
                'alert_title' => 'تاریخ انقضای نزدیک',
                'alert_message' => 'کالای {category_name} تا 30 روز آینده منقضی می‌شود.',
                'priority' => 7,
                'is_active' => true,
                'is_realtime' => true,
                'check_interval' => 0,
            ]);
        }

        // Rule for critical items
        Rule::create([
            'name' => 'کالای بحرانی',
            'description' => 'هشدار برای کالاهای بحرانی',
            'category_id' => null,
            'attribute_id' => null,
            'rule_type' => 'custom',
            'condition_type' => 'equals',
            'condition_value' => 'critical',
            'alert_type' => 'critical',
            'alert_title' => 'کالای بحرانی',
            'alert_message' => 'کالای {category_name} در وضعیت بحرانی قرار دارد.',
            'priority' => 10,
            'is_active' => true,
            'is_realtime' => true,
            'check_interval' => 0,
            'metadata' => [
                'check_risk_level' => true,
                'risk_level' => 'critical',
            ],
        ]);
    }
}
