<?php
define( 'DB_NAME', 'limerickstore' );
define( 'DB_USER', 'limerick_local' );
define( 'DB_PASSWORD', 'local-password-change-me' );
define( 'DB_HOST', 'db' );
define( 'DB_CHARSET', 'utf8' );
define( 'DB_COLLATE', '' );

define( 'WP_HOME', 'http://localhost:8080' );
define( 'WP_SITEURL', 'http://localhost:8080' );

define( 'AUTH_KEY',         'local-placeholder' );
define( 'SECURE_AUTH_KEY',  'local-placeholder' );
define( 'LOGGED_IN_KEY',    'local-placeholder' );
define( 'NONCE_KEY',        'local-placeholder' );
define( 'AUTH_SALT',        'local-placeholder' );
define( 'SECURE_AUTH_SALT', 'local-placeholder' );
define( 'LOGGED_IN_SALT',   'local-placeholder' );
define( 'NONCE_SALT',       'local-placeholder' );

$table_prefix = 'lm_';

define( 'WP_DEBUG', false );
define( 'WP_DEBUG_LOG', true );
define( 'WP_DEBUG_DISPLAY', false );

define( 'FS_METHOD', 'direct' );
define( 'DISABLE_WP_CRON', true );
define( 'DISALLOW_FILE_EDIT', true );

if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

require_once ABSPATH . 'wp-settings.php';
