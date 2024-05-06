<?php
/**
 * Plugin Name: Magic Maker
 * Plugin URI:
 * Description: Make Magic
 * Version: 1.0.0
 * Requires at least: 5.6
 * Requires PHP: 8.0
 * Author: Hasmukh Mistry
 * Author URI:
 * License: GPL-2.0+
 * License URI:
 * Update URI:
 * Text Domain: magic-maker
 * Domain Path:
 */

declare(strict_types=1);

use MagicMaker\Plugin;
use MagicMaker\Db;

// Define the plugin path
define( 'MAGIC_MAKER_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );

// Define the plugin url
define( 'MAGIC_MAKER_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

if ( ! class_exists( Plugin::class ) && is_readable( __DIR__ . '/vendor/autoload.php' ) ) {
	/** @noinspection PhpIncludeInspection */
	require_once __DIR__ . '/vendor/autoload.php';
}

if ( class_exists( Db\Init::class ) ) {
	register_activation_hook( __FILE__, array( Db\Init::class, 'activate' ) );
	register_uninstall_hook( __FILE__, array( Db\Init::class, 'uninstall' ) );
}

class_exists( Plugin::class ) && Plugin::instance()->init();
