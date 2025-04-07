<?php
// app/config/config.php

// Database Configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'xpense_tracker');

// Base URL
define('BASE_URL', 'http://localhost/xpense-tracker/');

// Path Configuration
define('ROOT_PATH', dirname(dirname(__DIR__)));
define('APP_PATH', ROOT_PATH . '/app');

define('MODEL_PATH', APP_PATH . '/models');
define('VIEW_PATH', APP_PATH . '/views');
define('CONTROLLER_PATH', APP_PATH . '/controllers');

define('PUBLIC_PATH', ROOT_PATH . '/public');
// define('CACHE_PATH', APP_PATH . '/cache');
// define('ASSET_PATH', PUBLIC_PATH . '/assets');
// define('UPLOAD_PATH', PUBLIC_PATH . '/uploads');

// // Session Configuration
// define('SESSION_NAME', 'xpense_tracker');
// define('SESSION_LIFETIME', 86400); // 24 hours

// Error Reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// // Set Default Timezone
// date_default_timezone_set('UTC');

// API Keys
define('EXCHANGE_API_KEY', 'your_api_key_here');
define('EXCHANGE_API_URL', 'https://v6.exchangerate-api.com/v6/' . EXCHANGE_API_KEY . '/latest/');
