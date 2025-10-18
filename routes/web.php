<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Milon\Barcode\Facades\DNS2DFacade;

Route::get('/', function () {
    return redirect()->route('filament.admin.auth.login');
});

Route::get('/clear_cache', function () {
    Artisan::call('optimize');
    return ['success' => true];
});

Route::get('/convert-city-to-branches', function () {
    try {
        // تابع تبدیل مختصات DMS
        function parseDMS($dmsString) {
            if (empty($dmsString)) return [0, 0, 0.0];
            
            // فرمت: "49:40:57.17" یا "34:4:55.15"
            $parts = explode(':', $dmsString);
            if (count($parts) >= 3) {
                $deg = (int)$parts[0];
                $min = (int)$parts[1];
                $sec = (float)$parts[2];
                return [$deg, $min, $sec];
            }
            return [0, 0, 0.0];
        }
        
        // دریافت اطلاعات از جدول city
        $cities = DB::connection('mysql')->table('city')->get();
        
        $converted = 0;
        $errors = [];
        
        foreach ($cities as $city) {
            try {
                // تبدیل اطلاعات به فرمت جدول branches
                $branchData = [
                    'region_id' => intval($city->province_id) + 1000, // province_id به region_id تبدیل می‌شود
                    'parent_id' => $city->parent_branch_id,
                    'branch_type' => $city->branch_type,
                    'name' => $city->title,
                    'two_digit_code' => $city->two_digit_code ?? $city->two_digit_code_old ?? null,
                    'date_establishment' => $city->date_establishment,
                    'phone' => $city->phone,
                    'fax' => $city->fax,
                    'vhf_address' => $city->vhf_address,
                    'hf_address' => $city->hf_address,
                    'vhf_channel' => $city->vhf_channel,
                    'lat' => $city->lat,
                    'lon' => $city->lon,
                    'lat_deg' => parseDMS($city->width)[0], // عرض جغرافیایی DMS
                    'lat_min' => parseDMS($city->width)[1],
                    'lat_sec' => parseDMS($city->width)[2],
                    'lon_deg' => parseDMS($city->length)[0], // طول جغرافیایی DMS
                    'lon_min' => parseDMS($city->length)[1],
                    'lon_sec' => parseDMS($city->length)[2],
                    'city_border' => null, // JSON field
                    'height' => $city->height,
                    'img_header' => $city->img_header,
                    'img_building' => $city->img_building,
                    'address' => $city->address,
                    'description' => $city->description,
                    'postal_code' => $city->postal_code,
                    'coding' => $city->coding,
                    'closed_thursday' => $city->closed_thursday,
                    'closure_date' => null,
                    'closure_document' => null,
                    'date_closed_thursday' => $city->date_closed_thursday,
                    'date_closed_thursday_end' => $city->date_closed_thursday_end,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
                
                // بررسی وجود شعبه با همین کد
                $existingBranch = DB::table('branches')->where('two_digit_code', $city->two_digit_code)->first();
                
                if (!$existingBranch) {
                    // درج در جدول branches
                    DB::table('branches')->insert($branchData);
                    $converted++;
                } else {
                    $errors[] = "شعبه با کد {$city->two_digit_code} قبلاً وجود دارد";
                }
                
            } catch (\Exception $e) {
                $errors[] = "خطا در تبدیل شعبه {$city->title}: " . $e->getMessage();
            }
        }
        
        return response()->json([
            'success' => true,
            'message' => "تبدیل اطلاعات با موفقیت انجام شد",
            'converted_count' => $converted,
            'total_cities' => $cities->count(),
            'errors' => $errors
        ]);
        
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => "خطا در تبدیل اطلاعات: " . $e->getMessage()
        ], 500);
    }
});

Route::get('/convert-operational-centers-to-bases', function () {
    try {
        // تابع تبدیل مختصات DMS
        function parseDMS($dmsString) {
            if (empty($dmsString)) return [0, 0, 0.0];
            
            // فرمت: "49:40:57.17" یا "34:4:55.15"
            $parts = explode(':', $dmsString);
            if (count($parts) >= 3) {
                $deg = (int)$parts[0];
                $min = (int)$parts[1];
                $sec = (float)$parts[2];
                return [$deg, $min, $sec];
            }
            return [0, 0, 0.0];
        }
        
        // دریافت اطلاعات از جدول operational_centers
        $operationalCenters = DB::connection('mysql')->table('operational_centers')->get();
        
        $converted = 0;
        $errors = [];
        
        foreach ($operationalCenters as $center) {
            try {
                // تبدیل اطلاعات به فرمت جدول bases
                $baseData = [
                    'region_id' => intval($center->province_id) + 1000, // province_id به region_id تبدیل می‌شود
                    'type_operational_center' => $center->type_operational_center,
                    'account_type' => $center->account_type,
                    'name' => $center->title,
                    'coding_old' => $center->coding_old,
                    'coding' => $center->coding,
                    'three_digit_code_new' => $center->three_digit_code_new,
                    'activity_days' => $center->activity_days,
                    'date_activity_days' => $center->date_activity_days,
                    'type_ownership' => $center->type_ownership,
                    'type_structure' => $center->type_structure,
                    'start_activity' => $center->start_activity,
                    'branch_type' => null, // فیلد جدید
                    'end_activity' => $center->end_activity,
                    'memory_martyr' => $center->memory_martyr,
                    'seasonal_type' => $center->seasonal_type,
                    'occasional_id' => $center->occasional_id,
                    'three_digit_code' => $center->three_digit_code,
                    'license_state' => $center->license_status,
                    'phone' => $center->phone,
                    'fixed_number' => $center->fixed_number,
                    'mobile' => $center->mobile,
                    'fax' => $center->fax,
                    'satellite_phone' => $center->satellite_phone,
                    'lat' => $center->lat,
                    'lon' => $center->lon,
                    'lat_deg' => parseDMS($center->width)[0], // عرض جغرافیایی DMS
                    'lat_min' => parseDMS($center->width)[1],
                    'lat_sec' => parseDMS($center->width)[2],
                    'lon_deg' => parseDMS($center->length)[0], // طول جغرافیایی DMS
                    'lon_min' => parseDMS($center->length)[1],
                    'lon_sec' => parseDMS($center->length)[2],
                    'height' => $center->height,
                    'city_border' => null, // JSON field
                    'arena' => $center->arena,
                    'ayan' => $center->ayan,
                    'img_header' => $center->img_header,
                    'img_license' => $center->img_license,
                    'bfile1' => $center->bfile1,
                    'bfile2' => $center->bfile2,
                    'address' => $center->address,
                    'description' => $center->description,
                    'postal_code' => $center->postal_code,
                    'place_payment' => $center->place_payment,
                    'type_personnel_emis' => $center->type_personnel_emis,
                    'distance_to_branch' => $center->kilometer,
                    'is_active' => $center->status,
                    'status_emis' => $center->status_emis,
                    'status_equipment' => $center->status_equipment,
                    'status_dims' => $center->status_dims,
                    'status_air_relief' => $center->status_air_relief,
                    'status_memberrcs' => $center->status_memberrcs,
                    'status_emdadyar' => $center->status_emdadyar,
                    'status_webgis' => $center->status_webgis,
                    'raromis_id' => $center->raromis_id,
                    'member_id' => $center->member_id,
                    'emdadyar_id' => $center->emdadyar_id,
                    'update_emdadyar_id' => $center->update_emdadyar_id,
                    'not_conditions' => $center->not_conditions,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
                
                // بررسی وجود پایگاه با همین کد
                $existingBase = DB::table('bases')->where('coding', $center->coding)->first();
                
                if (!$existingBase) {
                    // درج در جدول bases
                    DB::table('bases')->insert($baseData);
                    $converted++;
                } else {
                    $errors[] = "پایگاه با کد {$center->coding} قبلاً وجود دارد";
                }
                
            } catch (\Exception $e) {
                $errors[] = "خطا در تبدیل پایگاه {$center->title}: " . $e->getMessage();
            }
        }
        
        return response()->json([
            'success' => true,
            'message' => "تبدیل مراکز عملیاتی با موفقیت انجام شد",
            'converted_count' => $converted,
            'total_centers' => $operationalCenters->count(),
            'errors' => $errors
        ]);
        
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => "خطا در تبدیل مراکز عملیاتی: " . $e->getMessage()
        ], 500);
    }
});

Route::get('/convert-groups-to-categories', function () {
    try {
        $converted = 0;
        $errors = [];
        
        // مرحله 1: تبدیل supergroup به categories (سطح 1)
        $supergroups = DB::connection('mysql')->table('supergroup')->get();
        $supergroupMap = []; // نگاشت کد supergroup به id جدید
        
        foreach ($supergroups as $supergroup) {
            try {
                $categoryData = [
                    'code' => str_pad($supergroup->code, 2, '0', STR_PAD_LEFT),
                    'name' => $supergroup->name,
                    'description' => null,
                    'hierarchy_level' => 1,
                    'parent_id' => null,
                    'order_index' => $supergroup->code,
                    'status' => $supergroup->state ? 'active' : 'inactive',
                    'icon' => null,
                    'color' => null,
                    'metadata' => json_encode([
                        'useraccess_id' => $supergroup->useraccess_id,
                        'original_table' => 'supergroup',
                        'original_id' => $supergroup->code
                    ]),
                    'is_leaf' => 0,
                    'full_path' => str_pad($supergroup->code, 2, '0', STR_PAD_LEFT),
                    'children_count' => 0,
                    'items_count' => 0,
                    'category_type' => 'supergroup',
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
                
                $existingCategory = DB::table('categories')->where('code', $categoryData['code'])->first();
                
                if (!$existingCategory) {
                    $newId = DB::table('categories')->insertGetId($categoryData);
                    $supergroupMap[$supergroup->code] = $newId;
                    $converted++;
                } else {
                    $supergroupMap[$supergroup->code] = $existingCategory->id;
                    $errors[] = "دسته با کد {$categoryData['code']} قبلاً وجود دارد";
                }
                
            } catch (\Exception $e) {
                $errors[] = "خطا در تبدیل supergroup {$supergroup->name}: " . $e->getMessage();
            }
        }
        
        // مرحله 2: تبدیل middlegroup به categories (سطح 2)
        $middlegroups = DB::connection('mysql')->table('middlegroup')->get();
        $middlegroupMap = []; // نگاشت کد middlegroup به id جدید
        
        foreach ($middlegroups as $middlegroup) {
            try {
                $parentId = $supergroupMap[$middlegroup->supergroup_id] ?? null;
                if (!$parentId) {
                    $errors[] = "پدر middlegroup {$middlegroup->name} یافت نشد";
                    continue;
                }
                
                $categoryData = [
                    'code' => str_pad($middlegroup->supergroup_id, 2, '0', STR_PAD_LEFT) . str_pad($middlegroup->code, 2, '0', STR_PAD_LEFT),
                    'name' => $middlegroup->name,
                    'description' => null,
                    'hierarchy_level' => 2,
                    'parent_id' => $parentId,
                    'order_index' => $middlegroup->code,
                    'status' => $middlegroup->state ? 'active' : 'inactive',
                    'icon' => null,
                    'color' => null,
                    'metadata' => json_encode([
                        'original_table' => 'middlegroup',
                        'original_id' => $middlegroup->id,
                        'supergroup_id' => $middlegroup->supergroup_id
                    ]),
                    'is_leaf' => 0,
                    'full_path' => str_pad($middlegroup->supergroup_id, 2, '0', STR_PAD_LEFT) . '/' . str_pad($middlegroup->code, 2, '0', STR_PAD_LEFT),
                    'children_count' => 0,
                    'items_count' => 0,
                    'category_type' => 'middlegroup',
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
                
                $existingCategory = DB::table('categories')->where('code', $categoryData['code'])->first();
                
                if (!$existingCategory) {
                    $newId = DB::table('categories')->insertGetId($categoryData);
                    $middlegroupMap[$middlegroup->id] = $newId;
                    $converted++;
                } else {
                    $middlegroupMap[$middlegroup->id] = $existingCategory->id;
                    $errors[] = "دسته با کد {$categoryData['code']} قبلاً وجود دارد";
                }
                
            } catch (\Exception $e) {
                $errors[] = "خطا در تبدیل middlegroup {$middlegroup->name}: " . $e->getMessage();
            }
        }
        
        // مرحله 3: تبدیل subgroup به categories (سطح 3)
        $subgroups = DB::connection('mysql')->table('subgroup')->get();
        
        foreach ($subgroups as $subgroup) {
            try {
                $parentId = $middlegroupMap[$subgroup->middlegroup_id] ?? null;
                if (!$parentId) {
                    $errors[] = "پدر subgroup {$subgroup->name} یافت نشد";
                    continue;
                }
                
                // دریافت اطلاعات middlegroup برای ساخت کد کامل
                $middlegroup = DB::connection('mysql')->table('middlegroup')->where('id', $subgroup->middlegroup_id)->first();
                if (!$middlegroup) {
                    $errors[] = "اطلاعات middlegroup برای subgroup {$subgroup->name} یافت نشد";
                    continue;
                }
                
                $categoryData = [
                    'code' => str_pad($middlegroup->supergroup_id, 2, '0', STR_PAD_LEFT) . 
                             str_pad($middlegroup->code, 2, '0', STR_PAD_LEFT) . 
                             str_pad($subgroup->code, 2, '0', STR_PAD_LEFT),
                    'name' => $subgroup->name,
                    'description' => null,
                    'hierarchy_level' => 3,
                    'parent_id' => $parentId,
                    'order_index' => $subgroup->code,
                    'status' => $subgroup->state ? 'active' : 'inactive',
                    'icon' => null,
                    'color' => null,
                    'metadata' => json_encode([
                        'original_table' => 'subgroup',
                        'original_id' => $subgroup->id,
                        'middlegroup_id' => $subgroup->middlegroup_id
                    ]),
                    'is_leaf' => 1, // subgroup ها معمولاً نهایی هستند
                    'full_path' => str_pad($middlegroup->supergroup_id, 2, '0', STR_PAD_LEFT) . '/' . 
                                 str_pad($middlegroup->code, 2, '0', STR_PAD_LEFT) . '/' . 
                                 str_pad($subgroup->code, 2, '0', STR_PAD_LEFT),
                    'children_count' => 0,
                    'items_count' => 0,
                    'category_type' => 'subgroup',
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
                
                $existingCategory = DB::table('categories')->where('code', $categoryData['code'])->first();
                
                if (!$existingCategory) {
                    DB::table('categories')->insert($categoryData);
                    $converted++;
                } else {
                    $errors[] = "دسته با کد {$categoryData['code']} قبلاً وجود دارد";
                }
                
            } catch (\Exception $e) {
                $errors[] = "خطا در تبدیل subgroup {$subgroup->name}: " . $e->getMessage();
            }
        }
        
        return response()->json([
            'success' => true,
            'message' => "تبدیل گروه‌ها به دسته‌بندی‌ها با موفقیت انجام شد",
            'converted_count' => $converted,
            'total_supergroups' => $supergroups->count(),
            'total_middlegroups' => $middlegroups->count(),
            'total_subgroups' => $subgroups->count(),
            'errors' => $errors
        ]);
        
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => "خطا در تبدیل گروه‌ها: " . $e->getMessage()
        ], 500);
    }
});

Route::get('/convert-product-certificates-to-profiles', function () {
    try {
        $converted = 0;
        $errors = [];
        
        // دریافت اطلاعات از جدول product_certificate
        $certificates = DB::connection('mysql')->table('product_certificate')->get();
        
        foreach ($certificates as $certificate) {
            try {
                // تبدیل اطلاعات به فرمت جدول product_profiles
                $profileData = [
                    'sku' => 'CERT-' . str_pad($certificate->id, 6, '0', STR_PAD_LEFT), // تولید SKU منحصر به فرد
                    'name' => $certificate->name,
                    'description' => $certificate->system_name,
                    'category_id' => $certificate->subgroup_id, // subgroup_id به category_id تبدیل می‌شود
                    'category_type' => 'subgroup',
                    'packaging_type_id' => null,
                    'product_type' => $certificate->type,
                    'brand_id' => null,
                    'brand' => $certificate->company_name,
                    'model' => $certificate->model_commodity,
                    'barcode' => null,
                    'qr_code' => null,
                    'weight' => !empty($certificate->weight) ? (float)$certificate->weight : null,
                    'length' => !empty($certificate->along) ? (float)$certificate->along : null,
                    'width' => !empty($certificate->width) ? (float)$certificate->width : null,
                    'height' => !empty($certificate->height) ? (float)$certificate->height : null,
                    'volume' => !empty($certificate->volume) ? (float)$certificate->volume : null,
                    'unit_of_measure' => $certificate->unit,
                    'unit_of_measure_id' => null,
                    'primary_unit' => $certificate->unit,
                    'primary_unit_id' => null,
                    'secondary_unit' => null,
                    'secondary_unit_id' => null,
                    'manufacturer' => $certificate->company_name,
                    'country_of_origin' => $certificate->country_origin,
                    'shelf_life_days' => null,
                    'standard_cost' => !empty($certificate->estimate) ? (float)$certificate->estimate : null,
                    'pricing_method' => 'standard',
                    'feature_1' => $certificate->color,
                    'feature_2' => $certificate->size,
                    'has_expiry_date' => 0,
                    'consumption_status' => $certificate->status_consumer ? 'consumable' : 'non_consumable',
                    'is_flammable' => 0,
                    'has_return_policy' => $certificate->state_sale,
                    'product_address' => null,
                    'minimum_stock_by_location' => null,
                    'reorder_point_by_location' => null,
                    'has_technical_specs' => 1,
                    'technical_specs' => buildTechnicalSpecs($certificate),
                    'has_storage_conditions' => $certificate->status_conditions ? 1 : 0,
                    'storage_conditions' => $certificate->description_condition,
                    'has_inspection' => $certificate->laboratory_test_status ? 1 : 0,
                    'inspection_details' => $certificate->description_laboratory_test,
                    'has_similar_products' => 0,
                    'similar_products' => null,
                    'estimated_value' => !empty($certificate->estimate) ? (float)$certificate->estimate : null,
                    'annual_inflation_rate' => 0.00,
                    'related_warehouses' => null,
                    'additional_description' => $certificate->other_features,
                    'status' => $certificate->status ? 'active' : 'inactive',
                    'custom_attributes' => buildCustomAttributes($certificate),
                    'images' => json_encode([['url' => $certificate->image, 'type' => 'main']]),
                    'documents' => json_encode([['url' => $certificate->file, 'type' => 'certificate']]),
                    'specifications' => buildSpecifications($certificate),
                    'is_active' => $certificate->status,
                    'created_at' => $certificate->date_insert ?? now(),
                    'updated_at' => $certificate->date_update ?? now(),
                ];
                
                // بررسی وجود پروفایل با همین SKU
                $existingProfile = DB::table('product_profiles')->where('sku', $profileData['sku'])->first();
                
                if (!$existingProfile) {
                    // درج در جدول product_profiles
                    DB::table('product_profiles')->insert($profileData);
                    $converted++;
                } else {
                    $errors[] = "پروفایل با SKU {$profileData['sku']} قبلاً وجود دارد";
                }
                
            } catch (\Exception $e) {
                $errors[] = "خطا در تبدیل شناسنامه {$certificate->name}: " . $e->getMessage();
            }
        }
        
        return response()->json([
            'success' => true,
            'message' => "تبدیل شناسنامه‌های کالا با موفقیت انجام شد",
            'converted_count' => $converted,
            'total_certificates' => $certificates->count(),
            'errors' => $errors
        ]);
        
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => "خطا در تبدیل شناسنامه‌ها: " . $e->getMessage()
        ], 500);
    }
});

// تابع کمکی برای ساخت مشخصات فنی
function buildTechnicalSpecs($certificate) {
    $specs = [];
    
    if (!empty($certificate->thickness)) $specs['thickness'] = $certificate->thickness;
    if (!empty($certificate->inner_diameter)) $specs['inner_diameter'] = $certificate->inner_diameter;
    if (!empty($certificate->external_diameter)) $specs['external_diameter'] = $certificate->external_diameter;
    if (!empty($certificate->working_pressure)) $specs['working_pressure'] = $certificate->working_pressure;
    if (!empty($certificate->device_power)) $specs['device_power'] = $certificate->device_power;
    if (!empty($certificate->power_open_arms)) $specs['power_open_arms'] = $certificate->power_open_arms;
    if (!empty($certificate->power_closing_arms)) $specs['power_closing_arms'] = $certificate->power_closing_arms;
    if (!empty($certificate->opening_arms)) $specs['opening_arms'] = $certificate->opening_arms;
    if (!empty($certificate->vim)) $specs['vim'] = $certificate->vim;
    if (!empty($certificate->operating_frequency)) $specs['operating_frequency'] = $certificate->operating_frequency;
    if (!empty($certificate->ac_output)) $specs['ac_output'] = $certificate->ac_output;
    if (!empty($certificate->direct_output)) $specs['direct_output'] = $certificate->direct_output;
    if (!empty($certificate->type_oil)) $specs['type_oil'] = $certificate->type_oil;
    if (!empty($certificate->volume_oil)) $specs['volume_oil'] = $certificate->volume_oil;
    if (!empty($certificate->spare_parts)) $specs['spare_parts'] = $certificate->spare_parts;
    
    return !empty($specs) ? json_encode($specs) : null;
}

// تابع کمکی برای ساخت ویژگی‌های سفارشی
function buildCustomAttributes($certificate) {
    $attributes = [];
    
    if (!empty($certificate->system)) $attributes['system'] = $certificate->system;
    if (!empty($certificate->brigade)) $attributes['brigade'] = $certificate->brigade;
    if (!empty($certificate->fuel_type)) $attributes['fuel_type'] = $certificate->fuel_type;
    if (!empty($certificate->service_list)) $attributes['service_list'] = $certificate->service_list;
    if (!empty($certificate->standard_list)) $attributes['standard_list'] = $certificate->standard_list;
    if (!empty($certificate->product_quality)) $attributes['product_quality'] = $certificate->product_quality;
    if (!empty($certificate->user_services)) $attributes['user_services'] = $certificate->user_services;
    if (!empty($certificate->ability)) $attributes['ability'] = $certificate->ability;
    
    // G03 فیلدها
    if (!empty($certificate->g03_model)) $attributes['g03_model'] = $certificate->g03_model;
    if (!empty($certificate->g03_number_cylinders)) $attributes['g03_number_cylinders'] = $certificate->g03_number_cylinders;
    if ($certificate->g03_number_wheel > 0) $attributes['g03_number_wheel'] = $certificate->g03_number_wheel;
    if (!empty($certificate->g03_driving_force)) $attributes['g03_driving_force'] = $certificate->g03_driving_force;
    if (!empty($certificate->g03_type)) $attributes['g03_type'] = $certificate->g03_type;
    if (!empty($certificate->g03_brigade)) $attributes['g03_brigade'] = $certificate->g03_brigade;
    if (!empty($certificate->g03_fuel_type)) $attributes['g03_fuel_type'] = $certificate->g03_fuel_type;
    if ($certificate->g03_capacity_person > 0) $attributes['g03_capacity_person'] = $certificate->g03_capacity_person;
    if ($certificate->g03_capacity_tone > 0) $attributes['g03_capacity_tone'] = $certificate->g03_capacity_tone;
    if ($certificate->g03_number_axles > 0) $attributes['g03_number_axles'] = $certificate->g03_number_axles;
    
    return !empty($attributes) ? json_encode($attributes) : null;
}

// تابع کمکی برای ساخت مشخصات
function buildSpecifications($certificate) {
    $specs = [];
    
    // مشخصات فیزیکی
    if (!empty($certificate->weight)) $specs['physical']['weight'] = $certificate->weight;
    if (!empty($certificate->length)) $specs['physical']['length'] = $certificate->length;
    if (!empty($certificate->width)) $specs['physical']['width'] = $certificate->width;
    if (!empty($certificate->height)) $specs['physical']['height'] = $certificate->height;
    if (!empty($certificate->volume)) $specs['physical']['volume'] = $certificate->volume;
    if (!empty($certificate->color)) $specs['physical']['color'] = $certificate->color;
    if (!empty($certificate->size)) $specs['physical']['size'] = $certificate->size;
    
    // مشخصات فنی
    if (!empty($certificate->thickness)) $specs['technical']['thickness'] = $certificate->thickness;
    if (!empty($certificate->working_pressure)) $specs['technical']['working_pressure'] = $certificate->working_pressure;
    if (!empty($certificate->device_power)) $specs['technical']['device_power'] = $certificate->device_power;
    
    // شرایط نگهداری
    if ($certificate->status_conditions) {
        $specs['storage'] = [
            'has_conditions' => true,
            'description' => $certificate->description_condition
        ];
    }
    
    // لوازم مصرفی
    if ($certificate->status_consumer) {
        $specs['consumables'] = [
            'has_consumables' => true,
            'type' => $certificate->type_consumer,
            'number' => $certificate->number_consumer,
            'estimate' => $certificate->estimates_consumer,
            'description' => $certificate->description_consumer
        ];
    }
    
    // شرایط اسقاط
    if ($certificate->conditions_waiver) {
        $specs['disposal'] = [
            'has_conditions' => true,
            'description' => $certificate->description_waiver
        ];
    }
    
    // شرایط سرویس
    if ($certificate->status_terms_service) {
        $specs['service'] = [
            'has_conditions' => true,
            'description' => $certificate->description_service,
            'terms' => $certificate->description_terms_service
        ];
    }
    
    // تست آزمایشگاهی
    if ($certificate->laboratory_test_status) {
        $specs['testing'] = [
            'has_lab_test' => true,
            'description' => $certificate->description_laboratory_test
        ];
    }
    
    // متعلقات
    if ($certificate->belonging_status) {
        $specs['accessories'] = [
            'has_accessories' => true,
            'description' => $certificate->belonging_description
        ];
    }
    
    return !empty($specs) ? json_encode($specs) : null;
}

Route::get('/convert-store-properties-to-warehouses', function () {
    try {
        // تابع تبدیل مختصات DMS
        function parseDMS($dmsString) {
            if (empty($dmsString)) return [0, 0, 0.0];
            
            // فرمت: "49:40:57.17" یا "34:4:55.15"
            $parts = explode(':', $dmsString);
            if (count($parts) >= 3) {
                $deg = (int)$parts[0];
                $min = (int)$parts[1];
                $sec = (float)$parts[2];
                return [$deg, $min, $sec];
            }
            return [0, 0, 0.0];
        }
        
        // دریافت اطلاعات از جدول store_property
        $storeProperties = DB::connection('mysql')->table('store_property')->get();
        
        $converted = 0;
        $errors = [];
        
        foreach ($storeProperties as $store) {
            try {
                // بررسی وجود branch_id در جدول branches
                $branchId = null;
                if ($store->branch_id > 0) {
                    $existingBranch = DB::table('branches')->where('id', $store->branch_id)->first();
                    if ($existingBranch) {
                        $branchId = $store->branch_id;
                    } else {
                        $errors[] = "شعبه با ID {$store->branch_id} برای انبار {$store->title} یافت نشد";
                    }
                }
                
                // بررسی وجود base_id در جدول bases
                $baseId = null;
                if ($store->base_id > 0) {
                    $existingBase = DB::table('bases')->where('id', $store->base_id)->first();
                    if ($existingBase) {
                        $baseId = $store->base_id;
                    } else {
                        $errors[] = "پایگاه با ID {$store->base_id} برای انبار {$store->title} یافت نشد";
                    }
                }
                
                // تبدیل اطلاعات به فرمت جدول warehouses
                $warehouseData = [
                    'branch_id' => $branchId,
                    'base_id' => $baseId,
                    'shed_id' => null,
                    'province_id' => intval($store->province_id) + 1000, // province_id به region_id تبدیل می‌شود
                    'city_id' => null,
                    'town_id' => null,
                    'village_id' => null,
                    'title' => $store->title,
                    'telephone' => $store->phone,
                    'manager_name' => null,
                    'manager_phone' => null,
                    'usage_type' => mapUsageType($store->type_store),
                    'warehouse_info' => null,
                    'establishment_year' => !empty($store->year) ? (int)$store->year : 0,
                    'construction_year' => !empty($store->year_make) ? (int)$store->year_make : 0,
                    'population_census' => !empty($store->enum) ? (int)$store->enum : null,
                    'ownership_type' => mapOwnershipType($store->ownership),
                    'warehouse_standard' => null,
                    'shed_number' => $store->number > 0 ? (string)$store->number : '0',
                    'area' => !empty($store->area) ? (float)$store->area : null,
                    'under_construction_area' => !empty($store->area_make) ? (float)$store->area_make : null,
                    'structure_type' => mapStructureType($store->place),
                    'warehouse_count' => $store->number,
                    'small_inventory_count' => !empty($store->number_min) ? (int)$store->number_min : null,
                    'large_inventory_count' => !empty($store->number_max) ? (int)$store->number_max : null,
                    'diesel_forklift_healthy_count' => $store->disel_normal,
                    'diesel_forklift_defective_count' => $store->disel_mayub,
                    'gasoline_forklift_healthy_count' => $store->benzin_normal,
                    'gasoline_forklift_defective_count' => $store->benzin_mayub,
                    'gas_forklift_healthy_count' => $store->gaz_normal,
                    'gas_forklift_defective_count' => $store->gaz_mayub,
                    'electrical_forklift_healthy_count' => null,
                    'electrical_forklift_defective_count' => null,
                    'dual_fuel_forklift_healthy_count' => null,
                    'dual_fuel_forklift_defective_count' => null,
                    'ramp_length' => null,
                    'ramp_height' => null,
                    'warehouse_insurance' => $store->bime ? 'yes' : 'no',
                    'building_insurance' => $store->bime_abnie > 0 ? 'yes' : 'no',
                    'fire_suppression_system' => $store->fire ? 'healthy' : 'defective',
                    'fire_alarm_system' => $store->fire_alarm ? 'healthy' : 'defective',
                    'ram_rack' => $store->raak ? 'no' : 'yes',
                    'ram_rack_count' => $store->raak ? null : $store->num_raak,
                    'fire_extinguishers_count' => null,
                    'cctv_system' => $store->camera ? 'healthy' : 'defective',
                    'lighting_system' => $store->light ? 'healthy' : 'defective',
                    'flooring_type' => null,
                    'window_condition' => null,
                    'loading_platform' => null,
                    'external_fencing' => null,
                    'ventilation_system' => null,
                    'wall_distance' => null,
                    'security_guard' => null,
                    'longitude' => $store->lng,
                    'latitude' => $store->lat,
                    'longitude_e' => parseDMS($store->lenght)[0] + parseDMS($store->lenght)[1]/60 + parseDMS($store->lenght)[2]/3600,
                    'latitude_n' => parseDMS($store->width)[0] + parseDMS($store->width)[1]/60 + parseDMS($store->width)[2]/3600,
                    'altitude' => !empty($store->height) ? (float)$store->height : null,
                    'address' => $store->address,
                    'building_length' => null,
                    'building_width' => null,
                    'building_height' => null,
                    'building_metrage' => null,
                    'branch_establishment_year' => null,
                    'population_census_1395' => null,
                    'provincial_risk_percentage' => null,
                    'approved_grade' => !empty($store->degree) ? $store->degree : null,
                    'warehouse_area' => !empty($store->area) ? (float)$store->area : null,
                    'gps_x' => $store->lng,
                    'gps_y' => $store->lat,
                    'natural_hazards' => null,
                    'urban_location' => null,
                    'main_road_access' => null,
                    'heavy_vehicle_access' => null,
                    'terminal_proximity' => null,
                    'parking_facilities' => null,
                    'utilities' => null,
                    'neighboring_organizations' => null,
                    'keeper_name' => null,
                    'keeper_mobile' => null,
                    'postal_address' => $store->address,
                    'nearest_branch_1_id' => null,
                    'distance_to_branch_1' => null,
                    'nearest_branch_2_id' => null,
                    'distance_to_branch_2' => null,
                    'status' => $store->state,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
                
                // بررسی وجود انبار با همین عنوان
                $existingWarehouse = DB::table('warehouses')->where('title', $store->title)->first();
                
                if (!$existingWarehouse) {
                    try {
                        // درج در جدول warehouses
                        DB::table('warehouses')->insert($warehouseData);
                        $converted++;
                    } catch (\Exception $e) {
                        $errors[] = "خطا در درج انبار {$store->title}: " . $e->getMessage();
                    }
                } else {
                    $errors[] = "انبار با عنوان {$store->title} قبلاً وجود دارد";
                }
                
            } catch (\Exception $e) {
                $errors[] = "خطا در تبدیل انبار {$store->title}: " . $e->getMessage();
            }
        }
        
        return response()->json([
            'success' => true,
            'message' => "تبدیل مشخصات انبارها با موفقیت انجام شد",
            'converted_count' => $converted,
            'total_stores' => $storeProperties->count(),
            'errors' => $errors
        ]);
        
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => "خطا در تبدیل مشخصات انبارها: " . $e->getMessage()
        ], 500);
    }
});

Route::get('/check-foreign-keys-before-warehouse-conversion', function () {
    try {
        // بررسی وجود branches
        $branchesCount = DB::table('branches')->count();
        $basesCount = DB::table('bases')->count();
        
        // بررسی store_property
        $storeProperties = DB::connection('mysql')->table('store_property')->get();
        
        $missingBranches = [];
        $missingBases = [];
        
        foreach ($storeProperties as $store) {
            if ($store->branch_id > 0) {
                $existingBranch = DB::table('branches')->where('id', $store->branch_id)->first();
                if (!$existingBranch) {
                    $missingBranches[] = $store->branch_id;
                }
            }
            
            if ($store->base_id > 0) {
                $existingBase = DB::table('bases')->where('id', $store->base_id)->first();
                if (!$existingBase) {
                    $missingBases[] = $store->base_id;
                }
            }
        }
        
        return response()->json([
            'success' => true,
            'message' => "بررسی foreign key ها",
            'branches_count' => $branchesCount,
            'bases_count' => $basesCount,
            'store_properties_count' => $storeProperties->count(),
            'missing_branches' => array_unique($missingBranches),
            'missing_bases' => array_unique($missingBases),
            'missing_branches_count' => count(array_unique($missingBranches)),
            'missing_bases_count' => count(array_unique($missingBases))
        ]);
        
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => "خطا در بررسی foreign key ها: " . $e->getMessage()
        ], 500);
    }
});

Route::get('/convert-product-registrations-to-items', function () {
    try {
        // دریافت اطلاعات از جدول product_registration
        $registrations = DB::connection('mysql')->table('product_registration')->take(rand(10000, 60000))->get();
        
        $converted = 0;
        $errors = [];
        
        foreach ($registrations as $registration) {
            try {
                // بررسی وجود product_profile_id در جدول product_profiles
                $productProfileId = null;
                if ($registration->certificate_id > 0) {
                    // جستجو در product_profiles بر اساس certificate_id
                    $existingProfile = DB::table('product_profiles')
                        ->where('id', $registration->certificate_id)
                        ->first();
                    
                    if ($existingProfile) {
                        $productProfileId = $registration->certificate_id;
                    } else {
                        $errors[] = "شناسنامه کالا با ID {$registration->certificate_id} برای ثبت {$registration->id} یافت نشد";
                    }
                }
                
                // بررسی وجود warehouse_id در جدول warehouses
                $warehouseId = null;
                if ($registration->store_rowid > 0) {
                    $existingWarehouse = DB::table('warehouses')
                        ->where('id', $registration->store_rowid)
                        ->first();
                    
                    if ($existingWarehouse) {
                        $warehouseId = $registration->store_rowid;
                    } else {
                        $errors[] = "انبار با ID {$registration->store_rowid} برای ثبت {$registration->id} یافت نشد";
                    }
                }
                
                // تبدیل اطلاعات به فرمت جدول items
                $itemData = [
                    'product_profile_id' => $productProfileId,
                    'source_document_id' => null, // در صورت نیاز می‌تواند از register_id_move استفاده شود
                    'serial_number' => $registration->serial_number ?? $registration->serial_number1 ?? $registration->serial_number2,
                    'barcode' => $registration->g03_ZIP_codes, // بارکد پستی
                    'qr_code' => null,
                    'current_stock' => (int)$registration->count,
                    'min_stock' => 0,
                    'max_stock' => null,
                    'unit_cost' => null,
                    'selling_price' => null,
                    'status' => mapItemStatus($registration),
                    'manufacture_date' => parseDate($registration->production_date),
                    'production_date' => parseDate($registration->production_date),
                    'expiry_date' => parseDate($registration->expiration_date),
                    'purchase_date' => parseDate($registration->delivery_date),
                    'batch_number' => $registration->number_estate, // شماره اموال به عنوان batch_number
                    'warehouse_id' => $warehouseId,
                    'zone_id' => null,
                    'rack_id' => null,
                    'shelf_level_id' => null,
                    'pallet_id' => null,
                    'notes' => buildItemNotes($registration),
                    'is_active' => $registration->state_exist,
                    'created_at' => $registration->date_insert ?? now(),
                    'updated_at' => $registration->date_update ?? now(),
                ];
                
                // بررسی وجود item با همین serial_number
                $existingItem = null;
                if (!empty($itemData['serial_number'])) {
                    $existingItem = DB::table('items')
                        ->where('serial_number', $itemData['serial_number'])
                        ->first();
                }
                
                if (!$existingItem) {
                    try {
                        // درج در جدول items
                        DB::table('items')->insert($itemData);
                        $converted++;
                    } catch (\Exception $e) {
                        $errors[] = "خطا در درج کالا {$registration->id}: " . $e->getMessage();
                    }
                } else {
                    $errors[] = "کالا با شماره سریال {$itemData['serial_number']} قبلاً وجود دارد";
                }
                
            } catch (\Exception $e) {
                $errors[] = "خطا در تبدیل ثبت کالا {$registration->id}: " . $e->getMessage();
            }
        }
        
        return response()->json([
            'success' => true,
            'message' => "تبدیل ثبت موجودی کالاها با موفقیت انجام شد",
            'converted_count' => $converted,
            'total_registrations' => $registrations->count(),
            'errors' => $errors
        ]);
        
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => "خطا در تبدیل ثبت موجودی: " . $e->getMessage()
        ], 500);
    }
});

// تابع کمکی برای تبدیل وضعیت کالا
function mapItemStatus($registration) {
    if (!$registration->state_exist) {
        return 'inactive';
    }
    
    if ($registration->product_status == 0) {
        return 'discontinued';
    }
    
    if ($registration->health_status == 0) {
        return 'recalled';
    }
    
    return 'active';
}

// تابع کمکی برای تبدیل تاریخ
function parseDate($dateString) {
    if (empty($dateString)) return null;
    
    // فرمت تاریخ: 1400/01/01 یا 2021/01/01
    if (strpos($dateString, '/') !== false) {
        $parts = explode('/', $dateString);
        if (count($parts) == 3) {
            $year = (int)$parts[0];
            $month = (int)$parts[1];
            $day = (int)$parts[2];
            
            // اگر سال 4 رقمی و بزرگتر از 2000 باشد، میلادی است
            if ($year > 2000) {
                return "{$year}-{$month}-{$day}";
            } else {
                // تبدیل از شمسی به میلادی (تقریبی)
                $gregorianYear = $year + 621;
                return "{$gregorianYear}-{$month}-{$day}";
            }
        }
    }
    
    return null;
}

// تابع کمکی برای ساخت یادداشت‌ها
function buildItemNotes($registration) {
    $notes = [];
    
    if (!empty($registration->standard_list)) {
        $notes[] = "استانداردها: " . $registration->standard_list;
    }
    
    if (!empty($registration->belonging_list)) {
        $notes[] = "متعلقات: " . $registration->belonging_list;
    }
    
    if (!empty($registration->keeping_place_text)) {
        $notes[] = "محل نگهداری: " . $registration->keeping_place_text;
    }
    
    // G03 فیلدها
    if (!empty($registration->g03_Number_plates)) {
        $notes[] = "شماره پلاک: " . $registration->g03_Number_plates;
    }
    
    if (!empty($registration->g03_Engine_Number)) {
        $notes[] = "شماره موتور: " . $registration->g03_Engine_Number;
    }
    
    if (!empty($registration->g03_chassis_number)) {
        $notes[] = "شماره شاسی: " . $registration->g03_chassis_number;
    }
    
    if (!empty($registration->g03_operating_km)) {
        $notes[] = "کیلومتر کارکرد: " . $registration->g03_operating_km;
    }
    
    if (!empty($registration->g03_vin_code)) {
        $notes[] = "کد VIN: " . $registration->g03_vin_code;
    }
    
    if (!empty($registration->g03_Fuel_card_number)) {
        $notes[] = "شماره کارت سوخت: " . $registration->g03_Fuel_card_number;
    }
    
    if (!empty($registration->g03_type_car)) {
        $notes[] = "نوع خودرو: " . $registration->g03_type_car;
    }
    
    if (!empty($registration->g03_Model)) {
        $notes[] = "مدل: " . $registration->g03_Model;
    }
    
    if (!empty($registration->g03_Special_number)) {
        $notes[] = "شماره اختصاصی: " . $registration->g03_Special_number;
    }
    
    // G06 فیلدها
    if (!empty($registration->g06_adjusted_wireless)) {
        $notes[] = "تنظیم بی‌سیم: " . $registration->g06_adjusted_wireless;
    }
    
    if (!empty($registration->g06_height_mast)) {
        $notes[] = "ارتفاع دکل: " . $registration->g06_height_mast;
    }
    
    if (!empty($registration->g06_name_repeater_reference)) {
        $notes[] = "نام تکرارکننده: " . $registration->g06_name_repeater_reference;
    }
    
    if (!empty($registration->g06_wireless_id)) {
        $notes[] = "شناسه بی‌سیم: " . $registration->g06_wireless_id;
    }
    
    if (!empty($registration->g06_simcard_number)) {
        $notes[] = "شماره سیم‌کارت: " . $registration->g06_simcard_number;
    }
    
    if (!empty($registration->g06_one_operator)) {
        $notes[] = "اپراتور: " . $registration->g06_one_operator;
    }
    
    return !empty($notes) ? implode("\n", $notes) : null;
}

// تابع کمکی برای تبدیل نوع کاربری
function mapUsageType($typeStore) {
    $mapping = [
        'اضطراری' => 'emergency',
        'ضایعات' => 'scrap_used',
        'قطعات' => 'auto_parts',
        'عملیات' => 'ready_operations',
        'هوایی' => 'air_rescue_parts',
        'تجهیزات' => 'rescue_equipment',
        'موقت' => 'temporary',
    ];
    
    return $mapping[$typeStore] ?? 'emergency';
}

// تابع کمکی برای تبدیل نوع مالکیت
function mapOwnershipType($ownership) {
    $mapping = [
        1 => 'owned',
        2 => 'rented',
        3 => 'donated',
    ];
    
    return $mapping[$ownership] ?? 'owned';
}

// تابع کمکی برای تبدیل نوع سازه
function mapStructureType($place) {
    $mapping = [
        1 => 'concrete',
        2 => 'metal',
        3 => 'prefabricated',
    ];
    
    return $mapping[$place] ?? 'concrete';
}

Route::get('sync-permissions', function () {
    $super_admin_role = \App\Models\Role::where('name', 'like', '%super_admin%')->first();
    foreach (\App\Models\Permission::all()->toArray() as $permission) {
        $p_id = $permission['id'];
        $role_id = $super_admin_role['id'];
        \App\Models\RoleHasPermission::firstOrCreate([
            'permission_id' => $p_id,
            'role_id' => $role_id
        ], [
            'permission_id' => $p_id,
            'role_id' => $role_id
        ]);
    }
    return ['message' => 'done'];
});
