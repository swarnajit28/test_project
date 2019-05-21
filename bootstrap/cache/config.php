<?php return array (
  'app' => 
  array (
    'name' => 'simplify',
    'env' => 'local',
    'debug' => true,
    'url' => 'http://localhost/laravel/Simplify',
    'timezone' => 'UTC',
    'locale' => 'en',
    'fallback_locale' => 'en',
    'key' => 'base64:qDSsf8uwKgkK7FU/4+BH7vDMK+TlJAzm14GgCKrzOFE=',
    'cipher' => 'AES-256-CBC',
    'log' => 'single',
    'log_level' => 'debug',
    'providers' => 
    array (
      0 => 'Illuminate\\Auth\\AuthServiceProvider',
      1 => 'Illuminate\\Broadcasting\\BroadcastServiceProvider',
      2 => 'Illuminate\\Bus\\BusServiceProvider',
      3 => 'Illuminate\\Cache\\CacheServiceProvider',
      4 => 'Illuminate\\Foundation\\Providers\\ConsoleSupportServiceProvider',
      5 => 'Illuminate\\Cookie\\CookieServiceProvider',
      6 => 'Illuminate\\Database\\DatabaseServiceProvider',
      7 => 'Illuminate\\Encryption\\EncryptionServiceProvider',
      8 => 'Illuminate\\Filesystem\\FilesystemServiceProvider',
      9 => 'Illuminate\\Foundation\\Providers\\FoundationServiceProvider',
      10 => 'Illuminate\\Hashing\\HashServiceProvider',
      11 => 'Illuminate\\Mail\\MailServiceProvider',
      12 => 'Illuminate\\Notifications\\NotificationServiceProvider',
      13 => 'Illuminate\\Pagination\\PaginationServiceProvider',
      14 => 'Illuminate\\Pipeline\\PipelineServiceProvider',
      15 => 'Illuminate\\Queue\\QueueServiceProvider',
      16 => 'Illuminate\\Redis\\RedisServiceProvider',
      17 => 'Illuminate\\Auth\\Passwords\\PasswordResetServiceProvider',
      18 => 'Illuminate\\Session\\SessionServiceProvider',
      19 => 'Illuminate\\Translation\\TranslationServiceProvider',
      20 => 'Illuminate\\Validation\\ValidationServiceProvider',
      21 => 'Illuminate\\View\\ViewServiceProvider',
      22 => 'Barryvdh\\Debugbar\\ServiceProvider',
      23 => 'App\\Providers\\AppServiceProvider',
      24 => 'App\\Providers\\AuthServiceProvider',
      25 => 'App\\Providers\\EventServiceProvider',
      26 => 'App\\Providers\\RouteServiceProvider',
    ),
    'aliases' => 
    array (
      'App' => 'Illuminate\\Support\\Facades\\App',
      'Artisan' => 'Illuminate\\Support\\Facades\\Artisan',
      'Auth' => 'Illuminate\\Support\\Facades\\Auth',
      'Blade' => 'Illuminate\\Support\\Facades\\Blade',
      'Broadcast' => 'Illuminate\\Support\\Facades\\Broadcast',
      'Bus' => 'Illuminate\\Support\\Facades\\Bus',
      'Cache' => 'Illuminate\\Support\\Facades\\Cache',
      'Config' => 'Illuminate\\Support\\Facades\\Config',
      'Cookie' => 'Illuminate\\Support\\Facades\\Cookie',
      'Crypt' => 'Illuminate\\Support\\Facades\\Crypt',
      'DB' => 'Illuminate\\Support\\Facades\\DB',
      'Eloquent' => 'Illuminate\\Database\\Eloquent\\Model',
      'Event' => 'Illuminate\\Support\\Facades\\Event',
      'File' => 'Illuminate\\Support\\Facades\\File',
      'Gate' => 'Illuminate\\Support\\Facades\\Gate',
      'Hash' => 'Illuminate\\Support\\Facades\\Hash',
      'Lang' => 'Illuminate\\Support\\Facades\\Lang',
      'Log' => 'Illuminate\\Support\\Facades\\Log',
      'Mail' => 'Illuminate\\Support\\Facades\\Mail',
      'Notification' => 'Illuminate\\Support\\Facades\\Notification',
      'Password' => 'Illuminate\\Support\\Facades\\Password',
      'Queue' => 'Illuminate\\Support\\Facades\\Queue',
      'Redirect' => 'Illuminate\\Support\\Facades\\Redirect',
      'Redis' => 'Illuminate\\Support\\Facades\\Redis',
      'Request' => 'Illuminate\\Support\\Facades\\Request',
      'Response' => 'Illuminate\\Support\\Facades\\Response',
      'Route' => 'Illuminate\\Support\\Facades\\Route',
      'Schema' => 'Illuminate\\Support\\Facades\\Schema',
      'Session' => 'Illuminate\\Support\\Facades\\Session',
      'Storage' => 'Illuminate\\Support\\Facades\\Storage',
      'URL' => 'Illuminate\\Support\\Facades\\URL',
      'Validator' => 'Illuminate\\Support\\Facades\\Validator',
      'View' => 'Illuminate\\Support\\Facades\\View',
      'helper' => 'App\\Helpers\\MyFuncs',
      'Debugbar' => 'Barryvdh\\Debugbar\\Facade',
      'Carbon' => 'Carbon\\Carbon',
    ),
  ),
  'auth' => 
  array (
    'defaults' => 
    array (
      'guard' => 'web',
      'passwords' => 'users',
    ),
    'guards' => 
    array (
      'web' => 
      array (
        'driver' => 'session',
        'provider' => 'users',
      ),
      'api' => 
      array (
        'driver' => 'token',
        'provider' => 'users',
      ),
    ),
    'providers' => 
    array (
      'users' => 
      array (
        'driver' => 'eloquent',
        'model' => 'App\\User',
      ),
    ),
    'passwords' => 
    array (
      'users' => 
      array (
        'provider' => 'users',
        'table' => 'password_resets',
        'expire' => 60,
      ),
    ),
  ),
  'broadcasting' => 
  array (
    'default' => 'log',
    'connections' => 
    array (
      'pusher' => 
      array (
        'driver' => 'pusher',
        'key' => '',
        'secret' => '',
        'app_id' => '',
        'options' => 
        array (
          'cluster' => 'mt1',
          'encrypted' => true,
        ),
      ),
      'redis' => 
      array (
        'driver' => 'redis',
        'connection' => 'default',
      ),
      'log' => 
      array (
        'driver' => 'log',
      ),
      'null' => 
      array (
        'driver' => 'null',
      ),
    ),
  ),
  'cache' => 
  array (
    'default' => 'database',
    'stores' => 
    array (
      'apc' => 
      array (
        'driver' => 'apc',
      ),
      'array' => 
      array (
        'driver' => 'array',
      ),
      'database' => 
      array (
        'driver' => 'database',
        'table' => 'cache',
        'connection' => NULL,
      ),
      'file' => 
      array (
        'driver' => 'file',
        'path' => '/var/www/html/laravel/Simplify/storage/framework/cache/data',
      ),
      'memcached' => 
      array (
        'driver' => 'memcached',
        'persistent_id' => NULL,
        'sasl' => 
        array (
          0 => NULL,
          1 => NULL,
        ),
        'options' => 
        array (
        ),
        'servers' => 
        array (
          0 => 
          array (
            'host' => '127.0.0.1',
            'port' => 11211,
            'weight' => 100,
          ),
        ),
      ),
      'redis' => 
      array (
        'driver' => 'redis',
        'connection' => 'default',
      ),
    ),
    'prefix' => 'simplify_cache',
  ),
  'database' => 
  array (
    'default' => 'mysql',
    'connections' => 
    array (
      'sqlite' => 
      array (
        'driver' => 'sqlite',
        'database' => 'laravel_simplify',
        'prefix' => '',
      ),
      'mysql' => 
      array (
        'driver' => 'mysql',
        'host' => '192.168.0.11',
        'port' => '3306',
        'database' => 'laravel_simplify',
        'username' => 'developer',
        'password' => 'Mass4Pass',
        'unix_socket' => '',
        'charset' => 'utf8mb4',
        'collation' => 'utf8mb4_unicode_ci',
        'prefix' => '',
        'strict' => false,
        'engine' => NULL,
      ),
      'pgsql' => 
      array (
        'driver' => 'pgsql',
        'host' => '192.168.0.11',
        'port' => '3306',
        'database' => 'laravel_simplify',
        'username' => 'developer',
        'password' => 'Mass4Pass',
        'charset' => 'utf8',
        'prefix' => '',
        'schema' => 'public',
        'sslmode' => 'prefer',
      ),
      'sqlsrv' => 
      array (
        'driver' => 'sqlsrv',
        'host' => '192.168.0.11',
        'port' => '3306',
        'database' => 'laravel_simplify',
        'username' => 'developer',
        'password' => 'Mass4Pass',
        'charset' => 'utf8',
        'prefix' => '',
      ),
    ),
    'migrations' => 'migrations',
    'redis' => 
    array (
      'client' => 'predis',
      'default' => 
      array (
        'host' => '127.0.0.1',
        'password' => NULL,
        'port' => '6379',
        'database' => 0,
      ),
    ),
  ),
  'filesystems' => 
  array (
    'default' => 'local',
    'cloud' => 's3',
    'disks' => 
    array (
      'local' => 
      array (
        'driver' => 'local',
        'root' => '/var/www/html/laravel/Simplify/storage/app',
      ),
      'public' => 
      array (
        'driver' => 'local',
        'root' => '/var/www/html/laravel/Simplify/storage/app/public',
        'url' => 'http://localhost/laravel/Simplify/storage',
        'visibility' => 'public',
      ),
      's3' => 
      array (
        'driver' => 's3',
        'key' => NULL,
        'secret' => NULL,
        'region' => NULL,
        'bucket' => NULL,
      ),
    ),
  ),
  'formArray' => 
  array (
    'items_per_page' => 10,
    'FA_update_days' => 7,
    'changePassword' => 
    array (
      'old_password' => 'old_password',
      'password' => 'password',
      'password_confirmation' => 'password_confirmation',
    ),
    'addcustomer' => 
    array (
      'company_name' => 'company_name',
      'is_active' => 'is_active',
      'registration_number' => 'registration_number',
      'is_outside_FA' => 'is_outside_FA',
      'address_line_1' => 'address_line_1',
      'address_line_2' => 'address_line_2',
      'address_line_3' => 'address_line_3',
      'city' => 'city',
      'county' => 'county',
      'country' => 'country',
      'postal_code' => 'postal_code',
      'contact_person_name' => 'contact_person_name',
      'contact_person_phone1' => 'contact_person_phone1',
      'contact_person_phone2' => 'contact_person_phone2',
      'contact_person_email1' => 'contact_person_email1',
      'contact_person_email2' => 'contact_person_email2',
      'contact_person_note' => 'contact_person_note',
      'contact_person_job_title' => 'contact_person_job_title',
      'contact_person_job_role' => 'contact_person_job_role',
      'customer_attachment_name' => 'customer_attachment_name',
      'customer_attachment_file_name' => 'customer_attachment_file_name',
      'id' => 'id',
      'hide_attach' => 'hide_attach',
      'sales_person_id' => 'sales_person_id',
    ),
    'addleadcustomer' => 
    array (
      'lead' => 'lead',
      'company_name' => 'company_name',
      'is_active' => 'is_active',
      'registration_number' => 'registration_number',
      'is_outside_FA' => 'is_outside_FA',
      'address_line_1' => 'address_line_1',
      'address_line_2' => 'address_line_2',
      'address_line_3' => 'address_line_3',
      'city' => 'city',
      'county' => 'county',
      'country' => 'country',
      'postal_code' => 'postal_code',
      'contact_person_name' => 'contact_person_name',
      'contact_person_phone1' => 'contact_person_phone1',
      'contact_person_phone2' => 'contact_person_phone2',
      'contact_person_email1' => 'contact_person_email1',
      'contact_person_email2' => 'contact_person_email2',
      'contact_person_note' => 'contact_person_note',
      'contact_person_job_title' => 'contact_person_job_title',
      'contact_person_job_role' => 'contact_person_job_role',
      'customer_attachment_name' => 'customer_attachment_name',
      'customer_attachment_file_name' => 'customer_attachment_file_name',
      'id' => 'id',
      'hide_attach' => 'hide_attach',
      'sales_person_id' => 'sales_person_id',
    ),
    'addproduct' => 
    array (
      'id' => 'id',
      'product_category' => 'product_category',
      'product_name' => 'product_name',
      'product_description' => 'product_description',
      'margin_gbp' => 'margin_gbp',
      'rebate_gbp' => 'rebate_gbp',
      'end_margin' => 'end_margin',
      'commission' => 'commission',
    ),
    'adduser' => 
    array (
      'id' => 'id',
      'name' => 'name',
      'email' => 'email',
      'phone' => 'phone',
      'role' => 'role',
      'status' => 'status',
    ),
    'edituser' => 
    array (
      'user_id' => 'user_id',
      'name' => 'name',
      'email' => 'email',
      'phone' => 'phone',
      'role' => 'role',
      'status' => 'status',
    ),
    'addloantype' => 
    array (
      'loan_type_id' => 'loan_type_id',
      'loan_type' => 'loan_type',
      'loan_status' => 'loan_status',
      'color_code' => 'color_code',
      'key_details' => 'key_details',
    ),
    'addleadactivitymode' => 
    array (
      'activity_mode_id' => 'activity_mode_id',
      'activity_mode' => 'activity_mode',
      'mode_status' => 'mode_status',
    ),
    'addlead' => 
    array (
      'id' => 'id',
      'custom_id' => 'custom_id',
      'customer_id' => 'customer_id',
      'customer_contact_person_id' => 'customer_contact_person_id',
      'sales_person_id' => 'sales_person_id',
      'lead_source' => 'lead_source',
      'additional_info' => 'additional_info',
      'is_active' => 'is_active',
      'prod_id' => 'prod_id',
      'proposed_value' => 'proposed_value',
      'quantity' => 'quantity',
      'products' => 'products',
      'strength' => 'strength',
      'supportdoc' => 'supportdoc',
      'margin_value' => 'margin_value',
      'end_margin' => 'end_margin',
    ),
    'userchange_password' => 
    array (
      'user_id' => 'user_id',
      'old_password' => 'old_password',
      'password' => 'password',
      'password_confirmation' => 'password_confirmation',
    ),
    'addproductcategory' => 
    array (
      'product_category_id' => 'product_category_id',
      'product_category_name' => 'product_category_name',
      'product_category_status' => 'product_category_status',
    ),
    'addactivity' => 
    array (
      'lead_id' => 'user_id',
      'activity_type' => 'activity_type',
      'lead_activity_mode_id' => 'lead_activity_mode_id',
      'activity_note' => 'activity_note',
    ),
    'leaddetails' => 
    array (
      'id' => 'id',
    ),
    'onboardingform' => 
    array (
      'id' => 'id',
      'agency_name' => 'agency_name',
      'reg_no' => 'reg_no',
      'agency_client' => 'agency_client',
      'business_sector' => 'business_sector',
      'new_business' => 'new_business',
      'payraoll' => 'payraoll',
      'compliance' => 'compliance',
      'initially' => 'initially',
      'one_month' => 'one_month',
      'six_month' => 'six_month',
      'product' => 'product',
      'weekly_invoice' => 'weekly_invoice',
      'margin' => 'margin',
      'credit' => 'credit',
      'rebate' => 'rebate',
      'rebate_threshold' => 'rebate_threshold',
      'start_date' => 'start_date',
    ),
    'weeekly_target' => 
    array (
      'id' => 'id',
      'start_date' => 'start_date',
      'end_date' => 'end_date',
      'week_number' => 'week_number',
      'cis_paid' => 'cis_paid',
      'umbrella_paid' => 'umbrella_paid',
      'other_paid' => 'other_paid',
    ),
    'addpaymentoption' => 
    array (
      'payment_option_id' => 'payment_option_id',
      'payment_option_name' => 'payment_option_name',
      'payment_option_status' => 'payment_option_status',
      'is_reimbursable' => 'is_reimbursable',
    ),
    'addexpensetype' => 
    array (
      'expense_type_id' => 'expense_type_id',
      'expense_type_name' => 'expense_type_name',
      'expense_type_status' => 'expense_type_status',
    ),
    'businessExpense' => 
    array (
      'id' => 'id',
      'sales_person_id' => 'sales_person_id',
      'company_name' => 'company_name',
      'return_to' => 'return_to',
      'custom_id' => 'custom_id',
      'customer_id' => 'customer_id',
      'reporting_period' => 'reporting_period',
      'st_ex_date' => 'st_ex_date',
      'business_expense' => 'business_expense',
      'payment_option' => 'payment_option',
      'contact_person' => 'contact_person',
      'client_contact' => 'client_contact',
      'vat' => 'vat',
      'total' => 'total',
      'start_milage' => 'start_milage',
      'end_milage' => 'end_milage',
      'mileage_date' => 'mileage_date',
      'location' => 'location',
      'total_mileage' => 'total_mileage',
      'rate' => 'rate',
      'contact_person1' => 'contact_person1',
      'total_price' => 'total_price',
      'fuel_reimburse' => 'fuel_reimburse',
      'total_cash_reimbursement' => 'total_cash_reimbursement',
      'all_total_reimburse' => 'all_total_reimburse',
      'net_expense' => 'net_expense',
      'vat_reclaimed' => 'vat_reclaimed',
      'gross_expense' => 'gross_expense',
      'sort_code' => 'sort_code',
      'account_number' => 'account_number',
      'acknowledged_by' => 'acknowledged_by',
      'acknowledged_on' => 'acknowledged_on',
      'sign_type' => 'sign_type',
      'signed_file' => 'signed_file',
      'upload_file' => 'upload_file',
      'sign_date' => 'sign_date',
      'signature_file' => 'signature_file',
      'acknowledged_by1' => 'acknowledged_by1',
      'sign_type1' => 'sign_type1',
      'signed_file1' => 'signed_file1',
      'upload_file1' => 'upload_file1',
      'signature_file1' => 'signature_file1',
      'acknowledged_on1' => 'acknowledged_on1',
    ),
    'umbrellaCalculator' => 
    array (
      'your_name' => 'your_name',
      'individuals_name' => 'individuals_name',
      'individuals_email' => 'individuals_email',
      'rate_type' => 'rate_type',
      'rate_of_pay' => 'rate_of_pay',
      'total_hour_day' => 'total_hour_day',
      'include_pension' => 'include_pension',
      'input_margin' => 'input_margin',
    ),
    'documentStore' => 
    array (
      'doc_name' => 'doc_name',
      'doc_type' => 'doc_type',
      'file_name' => 'file_name',
      'doc_for' => 'doc_for',
    ),
  ),
  'mail' => 
  array (
    'driver' => 'smtp',
    'host' => 'smtp.gmail.com',
    'port' => '465',
    'from' => 
    array (
      'address' => 'msspl.tester.01@gmail.com',
      'name' => 'Simplifiy CRM',
    ),
    'encryption' => 'ssl',
    'username' => 'msspl.tester.01@gmail.com',
    'password' => 'Mass4Pass',
    'sendmail' => '/usr/sbin/sendmail -bs',
    'markdown' => 
    array (
      'theme' => 'default',
      'paths' => 
      array (
        0 => '/var/www/html/laravel/Simplify/resources/views/vendor/mail',
      ),
    ),
  ),
  'queue' => 
  array (
    'default' => 'sync',
    'connections' => 
    array (
      'sync' => 
      array (
        'driver' => 'sync',
      ),
      'database' => 
      array (
        'driver' => 'database',
        'table' => 'jobs',
        'queue' => 'default',
        'retry_after' => 90,
      ),
      'beanstalkd' => 
      array (
        'driver' => 'beanstalkd',
        'host' => 'localhost',
        'queue' => 'default',
        'retry_after' => 90,
      ),
      'sqs' => 
      array (
        'driver' => 'sqs',
        'key' => 'your-public-key',
        'secret' => 'your-secret-key',
        'prefix' => 'https://sqs.us-east-1.amazonaws.com/your-account-id',
        'queue' => 'your-queue-name',
        'region' => 'us-east-1',
      ),
      'redis' => 
      array (
        'driver' => 'redis',
        'connection' => 'default',
        'queue' => 'default',
        'retry_after' => 90,
      ),
    ),
    'failed' => 
    array (
      'database' => 'mysql',
      'table' => 'failed_jobs',
    ),
  ),
  'services' => 
  array (
    'mailgun' => 
    array (
      'domain' => NULL,
      'secret' => NULL,
    ),
    'ses' => 
    array (
      'key' => NULL,
      'secret' => NULL,
      'region' => 'us-east-1',
    ),
    'sparkpost' => 
    array (
      'secret' => NULL,
    ),
    'stripe' => 
    array (
      'model' => 'App\\User',
      'key' => NULL,
      'secret' => NULL,
    ),
  ),
  'session' => 
  array (
    'driver' => 'database',
    'lifetime' => '120',
    'expire_on_close' => false,
    'encrypt' => false,
    'files' => '/var/www/html/laravel/Simplify/storage/framework/sessions',
    'connection' => NULL,
    'table' => 'sessions',
    'store' => NULL,
    'lottery' => 
    array (
      0 => 2,
      1 => 100,
    ),
    'cookie' => 'simplify_session',
    'path' => '/',
    'domain' => NULL,
    'secure' => false,
    'http_only' => true,
    'same_site' => NULL,
  ),
  'view' => 
  array (
    'paths' => 
    array (
      0 => '/var/www/html/laravel/Simplify/resources/views',
    ),
    'compiled' => '/var/www/html/laravel/Simplify/storage/framework/views',
  ),
  'debugbar' => 
  array (
    'enabled' => NULL,
    'except' => 
    array (
    ),
    'storage' => 
    array (
      'enabled' => true,
      'driver' => 'file',
      'path' => '/var/www/html/laravel/Simplify/storage/debugbar',
      'connection' => NULL,
      'provider' => '',
    ),
    'include_vendors' => true,
    'capture_ajax' => true,
    'add_ajax_timing' => false,
    'error_handler' => false,
    'clockwork' => false,
    'collectors' => 
    array (
      'phpinfo' => true,
      'messages' => true,
      'time' => true,
      'memory' => true,
      'exceptions' => true,
      'log' => true,
      'db' => true,
      'views' => true,
      'route' => true,
      'auth' => true,
      'gate' => true,
      'session' => true,
      'symfony_request' => true,
      'mail' => true,
      'laravel' => false,
      'events' => false,
      'default_request' => false,
      'logs' => false,
      'files' => false,
      'config' => false,
      'cache' => false,
    ),
    'options' => 
    array (
      'auth' => 
      array (
        'show_name' => true,
      ),
      'db' => 
      array (
        'with_params' => true,
        'backtrace' => true,
        'timeline' => false,
        'explain' => 
        array (
          'enabled' => false,
          'types' => 
          array (
            0 => 'SELECT',
          ),
        ),
        'hints' => true,
      ),
      'mail' => 
      array (
        'full_log' => false,
      ),
      'views' => 
      array (
        'data' => false,
      ),
      'route' => 
      array (
        'label' => true,
      ),
      'logs' => 
      array (
        'file' => NULL,
      ),
      'cache' => 
      array (
        'values' => true,
      ),
    ),
    'inject' => true,
    'route_prefix' => '_debugbar',
    'route_domain' => NULL,
  ),
  'excel' => 
  array (
    'exports' => 
    array (
      'chunk_size' => 1000,
      'temp_path' => '/tmp',
      'pre_calculate_formulas' => false,
      'csv' => 
      array (
        'delimiter' => ',',
        'enclosure' => '"',
        'line_ending' => '
',
        'use_bom' => false,
        'include_separator_line' => false,
        'excel_compatibility' => false,
      ),
    ),
    'imports' => 
    array (
      'read_only' => true,
      'heading_row' => 
      array (
        'formatter' => 'slug',
      ),
    ),
    'extension_detector' => 
    array (
      'xlsx' => 'Xlsx',
      'xlsm' => 'Xlsx',
      'xltx' => 'Xlsx',
      'xltm' => 'Xlsx',
      'xls' => 'Xls',
      'xlt' => 'Xls',
      'ods' => 'Ods',
      'ots' => 'Ods',
      'slk' => 'Slk',
      'xml' => 'Xml',
      'gnumeric' => 'Gnumeric',
      'htm' => 'Html',
      'html' => 'Html',
      'csv' => 'Csv',
      'tsv' => 'Csv',
      'pdf' => 'Dompdf',
    ),
  ),
  'trustedproxy' => 
  array (
    'proxies' => 
    array (
      0 => '192.168.1.10',
    ),
    'headers' => 
    array (
      1 => 'FORWARDED',
      2 => 'X_FORWARDED_FOR',
      4 => 'X_FORWARDED_HOST',
      8 => 'X_FORWARDED_PROTO',
      16 => 'X_FORWARDED_PORT',
    ),
  ),
  'tinker' => 
  array (
    'dont_alias' => 
    array (
    ),
  ),
);
