<?php

error_reporting ( E_ALL );
ini_set("sendmail_from", "info@charitybay.com");

date_default_timezone_set ('Europe/Dublin');

define ( "__SITE_ROOT" , dirname( __FILE__ ) );
define ( "__DB_HOST" , "localhost" );
define ( "__DB_USER" , "CS4313_G5" );
define ( "__DB_PASS" , "ghbn56" );
define ( "__DB_NAME" , "CS4313_G5" );
define ( "__DEBUG" , true );

ob_start( "ob_gzhandler" );

// Interfaces
require_once ( __SITE_ROOT . "/system/interfaces/Observable.php" );
require_once ( __SITE_ROOT . "/system/interfaces/Observer.php" );
require_once ( __SITE_ROOT . "/system/interfaces/Singleton.php" );

// Base
require_once ( __SITE_ROOT . "/system/Base.php" );

// Singletons
require_once ( __SITE_ROOT . "/system/DatabaseManager.php" );
require_once ( __SITE_ROOT . "/system/SessionManager.php" );
require_once ( __SITE_ROOT . "/system/Modules.php" );
require_once ( __SITE_ROOT . "/system/ViewCommon.php" );
require_once ( __SITE_ROOT . "/system/Registry.php" );

// Extend from Base
require_once ( __SITE_ROOT . "/system/Plugin.php" );
require_once ( __SITE_ROOT . "/system/Controller.php" );
require_once ( __SITE_ROOT . "/system/View.php" );

// Google Translation
require_once ( __SITE_ROOT . "/system/GTranslate.php" );
