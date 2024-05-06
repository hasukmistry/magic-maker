<?php
/**
 * Plugin class to load plugin.
 *
 * @package MagicMaker
 */

declare(strict_types=1);

namespace MagicMaker;

use MagicMaker\Core\Service;

/**
 * Plugin class
 *
 * @since 1.0.0
 */
class Plugin {
	/**
	 * Plugin instance.
	 *
	 * @since 1.0.0
	 *
	 * @var Plugin
	 */
	private static $instance;

	/**
	 * Get the plugin instance.
	 *
	 * @since 1.0.0
	 *
	 * @return Plugin
	 */
	public static function instance(): Plugin {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Initialize the plugin.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 *
	 * @throws InvalidArgumentException If the file is not found.
	 */
	public function init(): void {
		if ( wp_installing() ) {
			return;
		}

		// Spin up the service config and container.
		add_action( 'wp_loaded', array( $this, 'load' ) );
	}

	/**
	 * Load the plugin.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 *
	 * @throws InvalidArgumentException If the file is not found.
	 */
	public function load(): void {
		$config_dir  = apply_filters( 'wp_dev_config_dir', MAGIC_MAKER_PLUGIN_PATH . '/config' );
		$config_file = apply_filters( 'wp_dev_config_file', 'services.yaml' );

		// Bail early if the config file has not been created yet.
		if ( ! file_exists( $config_dir . '/' . $config_file ) ) {
			return;
		}

		// Set config service.
		Service::instance()
			->set_config(
				$config_dir,
				$config_file
			)
			->set_services();
	}
}
