<?php

use App\Enums\RegionType;
use App\Models\Base\Setting;
use App\Models\Location\City;
use App\Models\Location\Country;
use App\Models\Location\Region;
use Filament\Forms\Components\Select;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use App\Models\User;
use App\Models\ACL\Role;
use BezhanSalleh\FilamentShield\Support\Utils;
use Illuminate\Support\Facades\DB;
use App\Models\Padafand\EvaluationActionSection;
use App\Models\Location\Province;
use App\Models\Organization\OrganizationalStructure;
use App\Models\Padafand\EvaluationAndMonitoring;
use App\Models\Padafand\EvaluationAndMonitoringScore;
use App\Models\Webgis\ThirdPartyApiToken;
use Filament\Facades\Filament;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;
use Morilog\Jalali\Jalalian;

function check_array_length($array, $len = 0)
{
    return $array && is_array($array) && count($array) > $len;
}

function convert_json_to_array_from_file($fake_data_path)
{
    if (file_exists($fake_data_path)) {
        $json_data = File::get($fake_data_path);
        return json_decode($json_data, true);
    }
    return [];
}

function trimToSpace($string, $space_number = 2)
{
    $firstSpacePos = strpos($string, ' ');

    if ($firstSpacePos === false) {
        return $string;
    }

    $secondSpacePos = strpos($string, ' ', $firstSpacePos + $space_number);

    if ($secondSpacePos === false) {
        return $string;
    }

    return substr($string, 0, $secondSpacePos) . '...';
}

function pascalToTitle($string)
{
    $result = preg_replace('/([a-z])([A-Z])/', '$1 $2', $string);
    return ucwords($result);
}

function generateRandomIranLocation()
{
    $minLat = 25.0;
    $maxLat = 39.0;
    $minLong = 44.0;
    $maxLong = 63.0;
    $randomLat = rand($minLat * 1000000, $maxLat * 1000000) / 1000000;
    $randomLong = rand($minLong * 1000000, $maxLong * 1000000) / 1000000;

    return [
        'start_area_accident_lat' => $randomLat,
        'start_area_accident_long' => $randomLong,
        'end_area_accident_lat' => $randomLat,
        'end_area_accident_long' => $randomLong,
    ];
}


function get_weather_api_token()
{
    $token = ThirdPartyApiToken::where("api_name", 'WEATHER')->whereDate('expires_at', '>', date('Y-m-d H:i:s'))->first();
    if ($token) {
        return [
            "token" => $token->token,
            "expires_at" => $token->expires_at
        ];
    } else {

        $username = config('padafand_weather.username', 'padafand');
        $password = config('padafand_weather.password', 'padafand@irimo2024');
        $url = config('padafand_weather.url', 'https://webservice.irimo.ir/sajjadeh/dal');
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url . "/login?username=$username&password=$password",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '{
            }',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Cookie: JSESSIONID=228CCEDF6AF0B3228E33DED6EED893F8'
            ),
        ));


        $response = json_decode(curl_exec($curl), true);
        if (curl_errno($curl)) {
            $error = curl_error($curl);
            curl_close($curl);
            return null;
        }
        $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);
        if ($statusCode == 200 || $statusCode == '200') {
            if (isset($response['claims']['exp']) && isset($response['claims']['token'])) {
                $expires_at = date('Y-m-d H:i:s', $response['claims']['exp']);
                $token = isset($response['claims']['token']) ? $response['claims']['token'] : null;
                ThirdPartyApiToken::create([
                    "api_name" => 'WEATHER',
                    "token" => $token,
                    "data" => $response,
                    "expires_at" => $expires_at
                ]);
                return [
                    "token" => $token,
                    "expires_at" => $expires_at
                ];
            } else {
                return null;
            }
        } else {
            return null;
        }
    }
}


function find_nearest_weather_stations($lat, $lon)
{
    $token = get_weather_api_token();
    $url = config('padafand_weather.url', 'https://webservice.irimo.ir/sajjadeh/dal');

    if (!$token) {
        return null;
    }

    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => $url . '/dws/nearest_st',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_SSL_VERIFYPEER => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => '{
            "params":[
                {
                    "name":"lat",
                    "value":"' . $lat . '"
                },
                {
                    "name":"lon",
                    "value":"' . $lon . '"
                }
            ]
        }',
        CURLOPT_HTTPHEADER => array(
            'Authorization: ' . $token['token'],
            'Content-Type: application/json',
            'Cookie: JSESSIONID=0B29E550EE85DDC77E5F3DC14698C7A4'
        ),
    ));

    $response = json_decode(curl_exec($curl), true);

    if (curl_errno($curl)) {
        $error = curl_error($curl);
        curl_close($curl);
        return null;
    }
    $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);
    if ($statusCode == 200 || $statusCode == '200') {
        return $response;
    } else {
        return null;
    }
}

function filter_station_data($stations, $weather_api_response)
{
    $filtered_weather_data = [];
    if ($stations && is_array($stations) && count($stations) > 0) {
        foreach ($stations as $station) {
            $station_id = isset($station['station_id']) ? $station['station_id'] : null;
            $station_name = isset($station['station_name']) ? $station['station_name'] : null;
            foreach (($station_id && $station_name ? $weather_api_response : []) as $weather_record) {
                if (isset($weather_record['wmo_code']) && $weather_record['wmo_code'] == $station_id) {
                    $filtered_weather_data[] = $weather_record;
                }
            }
        }
    }
    return $filtered_weather_data;
}


function find_province_by_lat_lng($latitude, $longitude)
{
    if (!is_numeric($latitude) || !is_numeric($longitude)) {
        return null;
    }
    $distance = 10;
    $query = Province::selectRaw('id, name_en, latitude, longitude,
                                  (6371 * acos(cos(radians(latitude)) * cos(radians(?)) * cos(radians(?) - radians(longitude)) + sin(radians(latitude)) * sin(radians(?)))) AS distance', [$latitude, $longitude, $latitude])
        ->havingRaw('distance <= ?', [$distance])
        ->orderBy('distance', 'asc');

    return $query->first();
}


function is_near_location($lat1, $lon1, $lat2, $lon2, $distance = 10)
{
    $lat1 = deg2rad($lat1);
    $lon1 = deg2rad($lon1);
    $lat2 = deg2rad($lat2);
    $lon2 = deg2rad($lon2);

    $dlat = $lat2 - $lat1;
    $dlon = $lon2 - $lon1;
    $a = sin($dlat / 2) * sin($dlat / 2) + cos($lat1) * cos($lat2) * sin($dlon / 2) * sin($dlon / 2);
    $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

    $distanceInKm = $c * 6371;
    return $distanceInKm <= $distance;
}


function nearest_model_records_by_lat_long($model, $latitude_field_name, $longitude_field_name, $latitude, $longitude, $distance)
{
    if (!is_numeric($latitude) || !is_numeric($longitude) || !is_numeric($distance)) {
        return null;
    }

    $query = $model::selectRaw(
        '*, 
        (6371 * acos(cos(radians(?)) * cos(radians(' . $latitude_field_name . ')) * cos(radians(' . $longitude_field_name . ') - radians(?)) + sin(radians(?)) * sin(radians(' . $latitude_field_name . ')))) AS distance',
        [$latitude, $longitude, $latitude]
    )
        ->havingRaw('distance <= ?', [$distance])
        ->orderBy('distance', 'asc');

    return $query->get();
}

function fetch_weather_data()
{
    $token = get_weather_api_token();
    if (!$token) {
        return null;
    }
    $url = config('padafand_weather.url', 'https://webservice.irimo.ir/sajjadeh/dal');
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => $url . '/dws/padafand',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_SSL_VERIFYPEER => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_HTTPHEADER => array(
            'Authorization: ' . $token['token'],
            'Cookie: JSESSIONID=920261D49D2D1BA5C49AF67028423736'
        ),
    ));


    $response = json_decode(curl_exec($curl), true);
    if (curl_errno($curl)) {
        $error = curl_error($curl);
        curl_close($curl);
        return null;
    }
    $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);
    return [
        'data' => $response,
        'status_code' => $statusCode
    ];
}

function array_contains_item($array, $key, $value)
{
    foreach ($array as $sub_array) {
        if (is_array($sub_array) && isset($sub_array[$key]) && $sub_array[$key] == $value) {
            return true;
        }
    }
    return false;
}


function generate_fake_human_data($count = 50, $gender_filter = null)
{
    if (!is_numeric($count) || (is_numeric($count) && $count <= 0) || !in_array($gender_filter, [1, 2, null])) {
        return [];
    }
    $male_names = ['علی', 'حسین', 'محمد', 'رضا', 'امیر', 'مصطفی', 'مهدی', 'حسن', 'مجتبی', 'ایمان', 'کیوان', 'محسن', 'شهریار', 'سعید', 'حمید', 'احسان', 'حبیب', 'عادل', 'آرمان', 'بابک', 'سامان', 'پدرام', 'کاوه'];
    $female_names = ['زهرا', 'فاطمه', 'سمیرا', 'مهسا', 'شیما', 'الناز', 'سارا', 'نرگس', 'مرضیه', 'لیلا', 'هانیه', 'مریم', 'پریسا', 'بهاره', 'نازنین', 'ستاره', 'نگین', 'مهتاب', 'فرزانه', 'ثریا', 'سپیده', 'پگاه', 'شیرین', 'شبنم', 'شکوفه'];

    $families = ['محمدی', 'رضایی', 'احمدی', 'کریمی', 'موسوی', 'حسینی', 'صادقی', 'صالحی', 'عابدی', 'زارعی', 'قاسمی', 'عباسی', 'عاشوری', 'نیکنام', 'رستمی', 'علوی', 'شفیعی', 'جمالی', 'امیری', 'هاشمی', 'کیانی', 'پاشایی', 'شیرازی', 'یزدانی', 'محمودی'];

    $people = [];

    for ($i = 0; $i < $count; $i++) {

        // 1 => Male and 2 => Female
        $gender = is_null($gender_filter)
            ? rand(1, 2)
            : $gender_filter;

        if ($gender == 1) {
            $name = $male_names[array_rand($male_names)];
        } else {
            $name = $female_names[array_rand($female_names)];
        }

        $family = $families[array_rand($families)];

        $people[] = [
            'name' => $name,
            'family' => $family,
            'gender' => $gender
        ];
    }
    return $people;
}


function getCurrentUserOrganizationalStructure($structureId)
{
    $onlyCanAccessToSelfOrganization = env('ONLY_CAN_ACCESS_TO_SELF_ORGANIZATIONS', false);
    if (!$structureId) {
        return [];
    }

    $structure = OrganizationalStructure::find($structureId);
    if (!$structure) {
        return [];
    }

    $result = [$structure->id => $structure->name];

    if (!$onlyCanAccessToSelfOrganization) {
        $queue = [$structure->id];

        while ($queue) {
            $parentId = array_shift($queue);
            $children = OrganizationalStructure::where('parent_id', $parentId)->get();
            foreach ($children as $child) {
                $result[$child->id] = $child->name;
                $queue[] = $child->id;
            }
        }
    }

    return $result;
}



// function getOrganizationalStructureSelectBox($items, $default = null, $label = null, $isRequired = true, $isSearchable = true, $isLive = false)
// {
//     $component = Select::make('organizational_structure_id')
//         ->options($items)
//         ->default($default)
//         ->label($label ?? __("Organization"));


//     if ($isRequired) $component->required();
//     if ($isSearchable) $component->searchable();
//     if ($isLive) $component->live();

//     return $component;
// }

function getCurrentUserOrganizationalStructures(User|null $user = null, array $include = null, array $exclude = null)
{
    $user = $user ?? auth()->user();

    if (!$user) {
        return [];
    }

    $onlyCanAccessToSelfOrganization = env('ONLY_CAN_ACCESS_TO_SELF_ORGANIZATIONS', false);

    $structureIds = $user->organizationalInformations->pluck('structure_id')->filter()->toArray();

    if (empty($structureIds)) {
        return [];
    }

    $result = [];
    $processedStructures = [];

    foreach ($structureIds as $structureId) {
        if (isset($processedStructures[$structureId])) {
            continue;
        }

        $structure = OrganizationalStructure::find($structureId);
        if (!$structure) {
            continue;
        }

        $result[$structure->id] = $structure->name;
        $processedStructures[$structure->id] = true;

        if (!$onlyCanAccessToSelfOrganization) {
            $queue = [$structure->id];

            while ($queue) {
                $parentId = array_shift($queue);
                $children = OrganizationalStructure::where('parent_id', $parentId)->get();

                foreach ($children as $child) {
                    if (!isset($processedStructures[$child->id])) {
                        $result[$child->id] = $child->name;
                        $processedStructures[$child->id] = true;
                        $queue[] = $child->id;
                    }
                }
            }
        }
    }

    if (is_array($include)) {
        $includeKeysAssoc = array_flip($include);
        $result = array_intersect_key($result, $includeKeysAssoc);
    }

    if (is_array($exclude)) {
        $excludeKeysAssoc = array_flip($exclude);
        $result = array_diff_key($result, $excludeKeysAssoc);
    }

    return $result;
}

function modelNameToShieldNamespace($modelName, $singleWord = true)
{
    // Convert PascalCase to snake_case
    $snakeCase = strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $modelName));

    // Split by underscore
    $parts = explode('_', $snakeCase);

    // Handle single word case
    if (count($parts) === 1) {
        return $singleWord ? $parts[0] : $parts[0] . '::' . $parts[0];
    }

    // For multiple words, use first part as namespace, rest as class
    $namespace = array_shift($parts);
    $className = implode('_', $parts);

    return $namespace . '::' . $className;
}

function getOrganizationalStructureSelectBox($items, $default = null, $label = null, $isRequired = true, $isSearchable = true, $isLive = false, $isMultiple = false)
{
    $component = Select::make($isMultiple ? 'organizational_structure_ids' : 'organizational_structure_id')
        ->options($items)
        ->default($default)
        ->label($label ?? __("Organization"));

    if ($isMultiple) {
        $component->multiple();
    }

    if ($isRequired)
        $component->required();
    if ($isSearchable)
        $component->searchable();
    if ($isLive)
        $component->live();

    return $component;
}

function getCurrentUserRegions(string $type)
{
    $user = auth()->user();
    $regions = Region::where('type', $type)
        ->where('status', true)
        ->orderBy('ordering')
        ->get()
        ->pluck('name', 'id');
    return $regions;
}

function getRegionsSelectBox(string $type, string $name, string $label)
{
    $component = Select::make($name)
        ->options(getCurrentUserRegions($type))
        ->label($label ?? __("Region"));

    return $component;
}

function evaluation_action($resourcename)
{
    $should_render = false;
    if ($resourcename) {
        $section = EvaluationActionSection::where('resource_name', $resourcename)->where('status', 1)->first();
        $should_render = $section ? true : false;
    }
    return $should_render ? Action::make('set_evaluation_score')
        ->label(__('Set Evaluation Score'))
        ->action(function (array $data, $livewire, Table $table) use ($section) {
            if (
                !EvaluationAndMonitoringScore::where('evaluation_and_monitoring_id', $data['evaluation_and_monitoring_id'])
                    ->where('action_title', $section->evaluation_action_title_id)
                    ->where('score', $data['score'])
                    ->where('evaluation_datetime', $data['evaluation_datetime'])
                    ->where('created_by', auth()->id())
                    ->first()
            ) {
                EvaluationAndMonitoringScore::create($data);
                Notification::make()
                    ->title(__("Set Evaluation Score"))
                    ->body(__("Inserted Successfully"))
                    ->success()
                    ->send();
            } else {
                Notification::make()
                    ->title(__("Set Evaluation Score"))
                    ->body(__("Evaluation Score Already Exists"))
                    ->warning()
                    ->send();
            }
        })
        ->icon('heroicon-m-check-circle')
        ->schema([
            Hidden::make('action_title')
                ->live()
                ->default($section->evaluation_action_title_id),
            Select::make('evaluation_and_monitoring_id')
                ->options(function () {
                    $evaluation_and_monitorings = EvaluationAndMonitoring::whereIn('organizational_structure_id', array_keys(getCurrentUserOrganizationalStructures(), auth()->user()?->OrganizationalInformation?->structure_id))->where('status', 0)->get();
                    $evaluation_and_monitorings_options = [];
                    $evaluation_and_monitorings->map(function ($item) use (&$evaluation_and_monitorings_options) {
                        $organizational_structure = OrganizationalStructure::find($item->organizational_structure_id);
                        $evaluation_and_monitorings_options[$item->id] = " ارزیابی سال " . $item->evaluation_identity . ($organizational_structure ? " شناسه " . $item->id . " - " . $organizational_structure->name : "");
                    });
                    return $evaluation_and_monitorings_options;
                })
                ->required()
                ->searchable()
                ->label(__('Evaluation Title')),
            DateTimePicker::make('evaluation_datetime')
                ->label(__("Evaluation Datetime"))
                ->required()
                ->jalali(),
            TextInput::make('score')
                ->label(__("Score"))
                ->required()
                ->numeric(),
        ]) : Action::make('set_evaluation_score')
            ->label(__('Set Evaluation Score'))
            ->visible(false);
}

function getPanelResources(): array
{
    return array_values(Filament::getCurrentOrDefaultPanel()->getResources());
}

function get_years_mapping($before = 100, $after = 100): array
{
    $years = [];
    $currentGregorianYear = (int) date('Y');

    $startYear = $currentGregorianYear - $before;
    $endYear = $currentGregorianYear + $after;

    for ($gregorianYear = $startYear; $gregorianYear <= $endYear; $gregorianYear++) {
        $persianDate = Jalalian::fromDateTime($gregorianYear . '-01-01');
        $persianYear = $persianDate->getYear();
        $years[$persianYear] = $persianYear;
    }

    return $years;
}

function is_super_admin($user)
{
    if (!$user || ($user && !is_a($user, User::class))) {
        return false;
    }
    $super_admin_role = Role::where('name', Utils::getSuperAdminName())->first();
    return $user && DB::table('model_has_roles')->where('model_type', 'like', '%App\Models\User%')->where('model_id', $user?->id)->where('role_id', $super_admin_role->id)->exists();
}

function font_aweasome_list()
{
    return [
        // Solid Icons
        'fontawesome-solid fa-user' => 'کاربر',
        'fontawesome-solid fa-users' => 'گروه کاربران',
        'fontawesome-solid fa-envelope' => 'پست الکترونیک',
        'fontawesome-solid fa-phone' => 'تلفن',
        'fontawesome-solid fa-home' => 'خانه',
        'fontawesome-solid fa-star' => 'ستاره',
        'fontawesome-solid fa-heart' => 'قلب',
        'fontawesome-solid fa-calendar' => 'تقویم',
        'fontawesome-solid fa-clock' => 'ساعت',
        'fontawesome-solid fa-bell' => 'زنگ اخبار',
        'fontawesome-solid fa-cog' => 'تنظیمات',
        'fontawesome-solid fa-lock' => 'قفل',
        'fontawesome-solid fa-unlock' => 'باز کردن قفل',
        'fontawesome-solid fa-search' => 'جستجو',
        'fontawesome-solid fa-shopping-cart' => 'سبد خرید',
        'fontawesome-solid fa-credit-card' => 'کارت اعتباری',
        'fontawesome-solid fa-chart-line' => 'نمودار خط',
        'fontawesome-solid fa-comment' => 'پیام',
        'fontawesome-solid fa-globe' => 'جهان',
        'fontawesome-solid fa-map-marker' => 'نشانگر نقشه',
        'fontawesome-solid fa-download' => 'بارگیری',
        'fontawesome-solid fa-upload' => 'بارگذاری',
        'fontawesome-solid fa-paperclip' => 'گیره کاغذی',
        'fontawesome-solid fa-trash' => 'سطل زباله',
        'fontawesome-solid fa-edit' => 'ویرایش',
        'fontawesome-solid fa-save' => 'ذخیره',
        'fontawesome-solid fa-print' => 'چاپ',
        'fontawesome-solid fa-share-alt' => 'اشتراک‌گذاری',
        'fontawesome-solid fa-bookmark' => 'نشانک',
        'fontawesome-solid fa-camera' => 'دوربین',
        'fontawesome-solid fa-music' => 'موسیقی',
        'fontawesome-solid fa-film' => 'فیلم',
        'fontawesome-solid fa-gift' => 'هدیه',
        'fontawesome-solid fa-archive' => 'بایگانی',
        'fontawesome-solid fa-exclamation-circle' => 'علامت هشدار',
        'fontawesome-solid fa-check-circle' => 'تایید',
        'fontawesome-solid fa-times-circle' => 'لغو',
        'fontawesome-solid fa-info-circle' => 'اطلاعات',
        'fontawesome-solid fa-question-circle' => 'سوال',

        // Regular Icons
        'fontawesome-regular fa-user' => 'کاربر (outline)',
        'fontawesome-regular fa-envelope' => 'پست الکترونیک (outline)',
        'fontawesome-regular fa-heart' => 'قلب (outline)',
        'fontawesome-regular fa-calendar' => 'تقویم (outline)',
        'fontawesome-regular fa-clock' => 'ساعت (outline)',
        'fontawesome-regular fa-star' => 'ستاره (outline)',
        'fontawesome-regular fa-bell' => 'زنگ اخبار (outline)',
        'fontawesome-regular fa-comment' => 'پیام (outline)',
        'fontawesome-regular fa-bookmark' => 'نشانک (outline)',
        'fontawesome-regular fa-save' => 'ذخیره (outline)',
        'fontawesome-regular fa-edit' => 'ویرایش (outline)',
        'fontawesome-regular fa-trash' => 'سطل زباله (outline)',
        'fontawesome-regular fa-credit-card' => 'کارت اعتباری (outline)',
        'fontawesome-regular fa-check-circle' => 'تایید (outline)',
        'fontawesome-regular fa-times-circle' => 'لغو (outline)',
        'fontawesome-regular fa-question-circle' => 'سوال (outline)',
        'fontawesome-regular fa-info-circle' => 'اطلاعات (outline)',
        'fontawesome-regular fa-paper-plane' => 'هواپیمای کاغذی (outline)',
        'fontawesome-regular fa-lightbulb' => 'لامپ (outline)',
        'fontawesome-regular fa-life-ring' => 'حلقه نجات (outline)'
    ];
}

function get_latest_assets()
{
    $assetsPath = public_path('build/assets');
    $cssFile = null;
    $cssThemeFile = null;
    $jsFile = null;

    if (File::isDirectory($assetsPath)) {
        $files = File::files($assetsPath);

        foreach ($files as $file) {
            if ($file->getExtension() === 'css') {
                if (!$cssFile || $file->getMTime() > $cssFile->getMTime()) {
                    $cssFile = $file;
                }
            }

            if ($file->getExtension() === 'css' && str_contains($file->getFilename(), 'theme')) {
                if (!$cssThemeFile || $file->getMTime() > $cssThemeFile->getMTime()) {
                    $cssThemeFile = $file;
                }
            }

            if ($file->getExtension() === 'js') {
                if (!$jsFile || $file->getMTime() > $jsFile->getMTime()) {
                    $jsFile = $file;
                }
            }
        }
    }

    return [
        'css' => $cssFile ? asset('build/assets/' . $cssFile->getFilename()) : null,
        'js' => $jsFile ? asset('build/assets/' . $jsFile->getFilename()) : null,
        'css_theme' => $cssThemeFile ? asset('build/assets/' . $cssThemeFile->getFilename()) : null,
    ];
}


function search_by_id(int $searchId, array $data)
{
    $result = array_filter($data, function ($item) use ($searchId) {
        return $item['id'] === $searchId;
    });

    $result = array_values($result);

    return $result;
}


function get_provinces_with_centers()
{
    return [
        "آذربایجان شرقی" => "تبریز",
        "آذربایجان غربی" => "ارومیه",
        "اردبیل" => "اردبیل",
        "اصفهان" => "اصفهان",
        "البرز" => "کرج",
        "ایلام" => "ایلام",
        "بوشهر" => "بوشهر",
        "تهران" => "تهران",
        "چهارمحال و بختیاری" => "شهرکرد",
        "خراسان جنوبی" => "بیرجند",
        "خراسان رضوی" => "مشهد",
        "خراسان شمالی" => "بجنورد",
        "خوزستان" => "اهواز",
        "زنجان" => "زنجان",
        "سمنان" => "سمنان",
        "سیستان و بلوچستان" => "زاهدان",
        "فارس" => "شیراز",
        "قزوین" => "قزوین",
        "قم" => "قم",
        "کردستان" => "سنندج",
        "کرمان" => "کرمان",
        "کرمانشاه" => "کرمانشاه",
        "کهگیلویه و بویراحمد" => "یاسوج",
        "گلستان" => "گرگان",
        "گیلان" => "رشت",
        "لرستان" => "خرم‌آباد",
        "مازندران" => "ساری",
        "مرکزی" => "اراک",
        "هرمزگان" => "بندرعباس",
        "همدان" => "همدان",
        "یزد" => "یزد"
    ];
}


function getAllowedRegions($parentId, $model = City::class)
{
    return $model::where('status', 1)
        ->where('parent_id', $parentId)
        ->get()
        ->pluck('id')
        ->toArray();
}

function getIranId()
{
    return Country::where('name', 'like', '%ایران%')->first()?->id;
}

function generateLorem(int $wordCount = 150): string
{
    $filePath = 'lorem/lorem.txt';

    try {
        // Resolve full path using public_path
        $fullPath = public_path($filePath);

        // Check if file exists
        if (!file_exists($fullPath)) {
            throw new \Exception("Lorem file not found at: {$fullPath}");
        }

        // Read the file content
        $content = file_get_contents($fullPath);

        // Validate content
        if (empty($content)) {
            throw new \Exception("Lorem file is empty at: {$fullPath}");
        }

        // Split content into words using explode
        $words = array_filter(explode(' ', trim($content)));

        // Ensure we have words to work with
        if (empty($words)) {
            throw new \Exception("No valid words found in lorem file at: {$fullPath}");
        }

        // Shuffle words for randomization
        shuffle($words);

        // If requested more words than available, repeat the array
        if ($wordCount > count($words)) {
            $multiplier = ceil($wordCount / count($words));
            $words = array_merge(...array_fill(0, $multiplier, $words));
        }

        // Get the requested number of words
        $selectedWords = array_slice($words, 0, $wordCount);

        return implode(' ', $selectedWords);

    } catch (\Exception $e) {
        // Log the error for debugging with detailed message
        \Log::error('Lorem generation failed: ' . $e->getMessage() . ' | File path attempted: ' . public_path($filePath));

        // Fallback to default lorem ipsum
        return 'لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ، و با استفاده از طراحان گرافیک است';
    }
}


function getUsersList()
{
    $users = \App\Models\User::all();
    $users_array = [];
    $users->map(function ($item) use (&$users_array) {
        $users_array[$item->id] = $item->name . ' ' . $item->family . ' ' . $item->username;
    });
    return $users_array;
}


if (! function_exists('setting')) {
    function setting($column)
    {
        if (! Schema::hasTable('settings')) {
            return null;
        }

        $setting = Setting::query()->where('name', $column)->first()?->payload;

        if ($setting && json_validate($setting)) {
            $setting = json_decode($setting, true);
        }

        return $setting ?? null;
    }
}