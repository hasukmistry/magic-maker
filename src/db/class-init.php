<?php
/**
 * Init class to initialize the database.
 *
 * @package MagicMaker\Db
 */

declare(strict_types=1);

namespace MagicMaker\Db;

use MagicMaker\Exception\Query_Exception;
use MagicMaker\Exception\Create_Table_Exception;

/**
 * Init class
 *
 * @since 1.0.0
 */
class Init {
	/**
	 * When the plugin is activated.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 *
	 * @throws Create_Table_Exception If the table creation fails.
	 */
	public static function activate(): void {
		Things::on_plugin_activate();
	}

	/**
	 * When the plugin is uninstalled.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 *
	 * @throws Query_Exception If the query fails.
	 */
	public static function uninstall(): void {
		Things::on_plugin_uninstall();
	}
}
