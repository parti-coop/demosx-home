<?php
ini_set( 'display_errors', 0 );

// ===================================================
// Load database info and local development parameters
// ===================================================
if ( file_exists( dirname( __FILE__ ) . '/../production-config.php' ) ) {
    define( 'WP_LOCAL_DEV', false );
    include( dirname( __FILE__ ) . '/../production-config.php' );
} else {
    define( 'WP_LOCAL_DEV', true );
    include( dirname( __FILE__ ) . '/../development-config.php' );
}

// ================================================================================
// Database Charset &b The Database Collate type to use in creating database tables
// ================================================================================
define('DB_CHARSET', 'utf8');
define('DB_COLLATE', '');

// ======================
// Hide errors by default
// ======================
define( 'WP_DEBUG_DISPLAY', false );
define( 'WP_DEBUG', false );

// =========================
// Disable automatic updates
// =========================
define( 'AUTOMATIC_UPDATER_DISABLED', true );

// =======================
// Load WordPress Settings
// =======================
$table_prefix  = 'wp_';

if ( !defined('ABSPATH') ) {
	define('ABSPATH', dirname(__FILE__) . '/' );
}

require_once(ABSPATH . 'wp-settings.php');
