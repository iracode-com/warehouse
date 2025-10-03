<?php

return [
    // Navigation Groups
    'navigation_groups' => [
        'warehouse_management' => 'Warehouse Management',
        'location_management' => 'Location Management',
        'user_management' => 'User Management',
    ],

    'title' => 'Warehouse Title',
    'usage_type' => 'Warehouse Usage',
    'ownership_type' => 'Ownership Type',
    'province_id' => 'Province',
    'city_id' => 'City',
    'town_id' => 'Town',
    'village_id' => 'Village',
    'manager_name' => 'Manager Name',
    'manager_phone' => 'Manager Phone',
    'keeper_name' => 'Warehouse Keeper/User Name',
    'keeper_mobile' => 'Keeper Mobile',
    'telephone' => 'Telephone',
    'warehouse_info' => 'Warehouse Information',
    'address' => 'Full Address',
    'postal_address' => 'Postal Address',
    'province' => 'Province',
    'branch' => 'Branch',
    'base' => 'Base',
    'establishment_year' => 'Establishment Year',
    'construction_year' => 'Construction Year',
    'branch_establishment_year' => 'Branch Establishment Year',
    'population_census' => 'Current Population Census',
    'population_census_1395' => 'Population Census 1395',
    'area' => 'Total Warehouse Area',
    'under_construction_area' => 'Under Construction Area',
    'warehouse_area' => 'Warehouse Area',
    'warehouse_count' => 'Warehouse Count',
    'small_inventory_count' => 'Small Inventory Count',
    'large_inventory_count' => 'Large Inventory Count',
    'diesel_forklift_status' => 'Diesel Forklift',
    'gasoline_forklift_status' => 'Gasoline Forklift',
    'gas_forklift_status' => 'Gas Forklift',
    'forklift_standard' => 'Standard Status',
    'ramp_length' => 'Ramp Length',
    'ramp_height' => 'Ramp Height',
    'warehouse_insurance' => 'Warehouse Insurance',
    'building_insurance' => 'Building Insurance',
    'fire_suppression_system' => 'Fire Suppression System',
    'fire_alarm_system' => 'Fire Alarm System',
    'cctv_system' => 'CCTV System',
    'lighting_system' => 'Lighting System',
    'ram_rack' => 'Ram Rack',
    'ram_rack_count' => 'Ram Rack Count',
    'longitude' => 'Longitude',
    'latitude' => 'Latitude',
    'longitude_e' => 'Longitude (E)',
    'latitude_n' => 'Latitude (N)',
    'altitude' => 'Altitude',
    'gps_x' => 'GPS X',
    'gps_y' => 'GPS Y',
    'provincial_risk_percentage' => 'Provincial Risk Percentage',
    'approved_grade' => 'Approved Grade',
    'nearest_branch_1' => 'Nearest Branch 1',
    'distance_to_branch_1' => 'Distance to Branch 1',
    'nearest_branch_2' => 'Nearest Branch 2',
    'distance_to_branch_2' => 'Distance to Branch 2',

    // Usage types
    'usage_types' => [
        'emergency' => 'Emergency',
        'scrap_used' => 'Scrap and Used (Non-Emergency)',
        'auto_parts' => 'Auto Parts and Spare Parts',
        'ready_operations' => 'Ready Operations',
        'air_rescue_parts' => 'Air Rescue Parts',
        'rescue_equipment' => 'Rescue Equipment',
        'temporary' => 'Temporary',
    ],

    // Ownership types
    'ownership_types' => [
        'owned' => 'Owned',
        'rented' => 'Rented',
        'donated' => 'Donated',
    ],

    // Structure types
    'structure_types' => [
        'concrete' => 'Concrete',
        'metal' => 'Metal',
        'prefabricated' => 'Prefabricated',
    ],

    // Status options
    'status_options' => [
        'yes' => 'Yes',
        'no' => 'No',
    ],

    'health_status' => [
        'healthy' => 'Healthy',
        'defective' => 'Defective',
        'installing' => 'Installing',
    ],

    'standard_status' => [
        'standard' => 'Standard',
        'deficit' => 'Deficit',
        'surplus' => 'Surplus',
    ],

        // Tabs
        'tabs' => [
            'basic_info' => 'Basic Information',
            'technical_info' => 'Technical Information',
            'facilities_equipment' => 'Facilities & Equipment',
            'geographic_info' => 'Geographic Information',
            'additional_info' => 'Additional Information',
        ],

    // Branch tabs
    'branch_tabs' => [
        'basic_info' => 'Basic Information',
        'technical_info' => 'Technical Information',
        'additional_info' => 'Additional Information',
    ],

    // Region translations
    'region' => [
        'basic_info' => 'Basic Information',
        'geographic_info' => 'Geographic Information',
        'name' => 'Region Name',
        'type' => 'Region Type',
        'code' => 'Region Code',
        'parent' => 'Parent Region',
        'description' => 'Description',
        'is_active' => 'Active',
        'ordering' => 'Order',
        'lat' => 'Latitude',
        'lon' => 'Longitude',
        'height' => 'Height',
        'central_address' => 'Central Address',
        'central_postal_code' => 'Central Postal Code',
        'central_phone' => 'Central Phone',
        'central_mobile' => 'Central Mobile',
        'central_fax' => 'Central Fax',
        'central_email' => 'Central Email',
        
        'sections' => [
            'basic_info' => 'Basic Information',
            'basic_info_desc' => 'Main region information',
            'status_info' => 'Status',
            'status_info_desc' => 'Active/inactive status and ordering',
            'coordinates' => 'Geographic Coordinates',
            'coordinates_desc' => 'Geographic location of the region',
            'central_info' => 'Central Information',
            'central_info_desc' => 'Contact information and central address',
        ],
        
        'table' => [
            'name' => 'Name',
            'type' => 'Type',
            'parent' => 'Parent',
            'code' => 'Code',
            'is_active' => 'Active',
            'lat' => 'Latitude',
            'lon' => 'Longitude',
            'created_at' => 'Created At',
        ],
        
        'filters' => [
            'type' => 'Region Type',
            'is_active' => 'Active Status',
            'parent' => 'Parent Region',
            'all' => 'All',
            'active' => 'Active',
            'inactive' => 'Inactive',
        ],
        
        'actions' => [
            'create' => 'Create Region',
            'view' => 'View',
            'edit' => 'Edit',
            'delete' => 'Delete',
            'delete_selected' => 'Delete Selected',
        ],
        
        'navigation' => [
            'singular' => 'Region',
            'plural' => 'Regions',
        ],
    ],

    // Region Type translations
    'region_types' => [
        'country' => 'Country',
        'headquarter' => 'Headquarter',
        'province' => 'Province',
        'branch' => 'Branch',
        'town' => 'Town',
        'district' => 'District',
        'rural_district' => 'Rural District',
        'city' => 'City',
        'village' => 'Village',
    ],

    // User translations
    'user' => [
        'name' => 'Name',
        'family' => 'Family',
        'email' => 'Email',
        'mobile' => 'Mobile',
        'username' => 'Username',
        'password' => 'Password',
        'password_confirmation' => 'Confirm Password',
        
        'sections' => [
            'personal_info' => 'Personal Information',
            'personal_info_desc' => 'Basic user information',
            'authentication' => 'Authentication',
            'authentication_desc' => 'Password and login information',
        ],
        
        'table' => [
            'name' => 'Name',
            'family' => 'Family',
            'email' => 'Email',
            'mobile' => 'Mobile',
            'username' => 'Username',
            'created_at' => 'Created At',
            'email_copied' => 'Email copied',
            'mobile_copied' => 'Mobile copied',
        ],
        
        'filters' => [
            'email_verified' => 'Email Status',
            'all' => 'All',
            'verified' => 'Verified',
            'unverified' => 'Unverified',
        ],
        
        'actions' => [
            'create' => 'Create User',
            'view' => 'View',
            'edit' => 'Edit',
            'delete' => 'Delete',
            'delete_selected' => 'Delete Selected',
        ],
        
        'navigation' => [
            'singular' => 'User',
            'plural' => 'Users',
        ],
    ],

    // Branch translations
    'branch' => [
        'name' => 'Branch Name',
        'branch_type' => 'Branch Type',
        'two_digit_code' => 'Two Digit Code',
        'date_establishment' => 'Establishment Date',
        'phone' => 'Phone',
        'fax' => 'Fax',
        'address' => 'Address',
        'description' => 'Description',
        'postal_code' => 'Postal Code',
        'coding' => 'Coding',
        'vhf_address' => 'VHF Address Code',
        'hf_address' => 'HF Address Code',
        'vhf_channel' => 'VHF Channel',
        'lat' => 'Latitude (Decimal)',
        'lon' => 'Longitude (Decimal)',
        'lat_deg' => 'Latitude (Degrees)',
        'lat_min' => 'Latitude (Minutes)',
        'lat_sec' => 'Latitude (Seconds)',
        'lon_deg' => 'Longitude (Degrees)',
        'lon_min' => 'Longitude (Minutes)',
        'lon_sec' => 'Longitude (Seconds)',
        'height' => 'Altitude',
        'closed_thursday' => 'Closed on Thursday?',
        'closure_date' => 'Closure Date',
        'closure_document' => 'Closure Document',
        'date_closed_thursday' => 'Thursday Closure Start Date',
        'date_closed_thursday_end' => 'Thursday Closure End Date',
        'img_header' => 'Branch Header Image',
        'img_building' => 'Branch Building Image',
        
        // Branch types
        'branch_types' => [
            'county' => 'County',
            'headquarters' => 'Headquarters',
            'branch' => 'Branch',
            'independent_office' => 'Independent Office',
            'dependent_office' => 'Dependent Office',
            'urban_area' => 'Urban Area',
        ],
        
        // Sections
        'sections' => [
            'basic_info' => 'Basic Branch Information',
            'basic_info_desc' => 'Main information and branch contacts',
            'contact_info' => 'Contact Information',
            'contact_info_desc' => 'Phone numbers and branch address',
            'communication_info' => 'Communication Information',
            'communication_info_desc' => 'VHF and HF information',
            'geographic_info' => 'Geographic Information',
            'geographic_info_desc' => 'Branch geographic coordinates',
            'closure_info' => 'Closure Information',
            'closure_info_desc' => 'Thursday closure information',
            'images' => 'Images',
            'images_desc' => 'Branch images',
        ],
        
        // Table
        'table' => [
            'name' => 'Branch Name',
            'branch_type' => 'Branch Type',
            'phone' => 'Phone',
            'address' => 'Address',
            'closed_thursday' => 'Closed Thursday',
            'created_at' => 'Created At',
        ],
        
        // Navigation
        'navigation' => [
            'singular' => 'Branch',
            'plural' => 'Branches',
        ],

        // Actions
        'actions' => [
            'create' => 'Create New Branch',
            'view' => 'View',
            'edit' => 'Edit',
            'delete' => 'Delete',
            'delete_selected' => 'Delete Selected',
        ],
        
        // Filters
        'filters' => [
            'branch_type' => 'Branch Type',
            'closed_thursday' => 'Closed Thursday',
            'all' => 'All',
            'closed' => 'Closed',
            'open' => 'Open',
        ],
    ],

    // Sections
    'sections' => [
        'basic_info' => 'Basic Warehouse Information',
        'basic_info_desc' => 'General information and warehouse contacts',
        'location_info' => 'Location Information',
        'location_info_desc' => 'Geographic location and warehouse address',
        'temporal_info' => 'Temporal and Population Information',
        'temporal_info_desc' => 'Establishment years and population statistics',
        'ownership_area' => 'Ownership and Area Information',
        'ownership_area_desc' => 'Ownership type and warehouse area',
        'structure_count' => 'Structure Type and Count',
        'structure_count_desc' => 'Physical structure and warehouse count',
        'pallet_info' => 'Pallet Box Information',
        'pallet_info_desc' => 'Small and large inventory counts',
        'forklift_info' => 'Forklift Information',
        'forklift_info_desc' => 'Forklift status and specifications',
        'other_info' => 'Other Information',
        'other_info_desc' => 'Insurance, security and equipment',
        'geographic_info' => 'Geographic Information',
        'geographic_info_desc' => 'GPS coordinates and geographic location',
        'additional_info' => 'Additional Information',
        'additional_info_desc' => 'Grading and risk assessment',
        'keeper_info' => 'Warehouse Keeper/User Information',
        'keeper_info_desc' => 'Keeper contact information and address',
        'branch_distance' => 'Distance to Nearest Branch',
        'branch_distance_desc' => 'Distance to nearby branches',
    ],

    // Placeholders
    'placeholders' => [
        'select_option' => 'Select an option',
        'branch_name' => 'Branch name',
        'grade_example' => 'Example: A, B, C',
    ],

    // Units
    'units' => [
        'square_meter' => 'square meters',
        'kilometer' => 'kilometers',
        'degree' => 'degrees',
        'meter' => 'meters',
        'person' => 'people',
        'count' => 'units',
        'percent' => '%',
        'year_sh' => 'SH',
    ],

    // Table
    'table' => [
        'title' => 'Warehouse Title',
        'manager' => 'Manager Name',
        'usage' => 'Usage',
        'province' => 'Province',
        'city' => 'City',
        'branch' => 'Branch',
        'area' => 'Area',
        'ownership' => 'Ownership Type',
        'insurance' => 'Insurance',
        'created_at' => 'Created At',
        'view' => 'View',
        'edit' => 'Edit',
        'delete' => 'Delete',
        'delete_selected' => 'Delete Selected',
    ],

    // Filters
    'filters' => [
        'usage_type' => 'Warehouse Usage',
        'ownership_type' => 'Ownership Type',
        'province' => 'Province',
        'insurance' => 'Warehouse Insurance',
        'area_range' => 'Area',
        'area_from' => 'Area From',
        'area_to' => 'Area To',
        'all' => 'All',
        'has' => 'Has',
        'has_not' => 'Does not have',
    ],
];
