<?php
// Hackes
define('URL', $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']).'/');
define('BASE_DIR', str_replace("\\","/",realpath(dirname(__FILE__))."/"));
// Dynamic Protocol Settings
$protocol = "http://";
if($_SERVER["SERVER_PORT"] == 443){	$protocol = "https://";}
// HTTP Protocol
define('HTTP_SERVER', $protocol . URL);
define('HTTPS_SERVER', $protocol . URL);

// Directory
define('DIR_APPLICATION', BASE_DIR. 'catalog/');
define('DIR_SYSTEM', BASE_DIR. 'system/');
define('DIR_IMAGE', BASE_DIR. 'image/');
define('DIR_LANGUAGE', BASE_DIR. 'catalog/language/');
define('DIR_TEMPLATE', BASE_DIR. 'catalog/view/theme/');
define('DIR_CONFIG', BASE_DIR. 'system/config/');
define('DIR_CACHE', BASE_DIR. 'system/storage/cache/');
define('DIR_DOWNLOAD', BASE_DIR. 'system/storage/download/');
define('DIR_LOGS', BASE_DIR. 'system/storage/logs/');
define('DIR_MODIFICATION', BASE_DIR. 'system/storage/modification/');
define('DIR_UPLOAD', BASE_DIR. 'system/storage/upload/');

// DB
define('DB_DRIVER', 'mysqli');
define('DB_HOSTNAME', 'localhost');
// define('DB_USERNAME', 'root');
// define('DB_PASSWORD', '');
// define('DB_DATABASE', 'datumstruct');
define('DB_USERNAME', 'datumstr_ecom');
define('DB_PASSWORD', 'imba#2020!!');
define('DB_DATABASE', 'datumstr_ecom');

define('DB_PORT', '3306');
define('DB_PREFIX', 'datumstruct_');

define('ADVANCE_PASSWORD', false);
define('ADMIN_FOLDER', 'admin');
