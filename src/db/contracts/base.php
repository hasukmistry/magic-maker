<?php
/**
 * Base interface.
 *
 * @package MagicMaker\Db\Contracts
 */

declare(strict_types=1);

namespace MagicMaker\Db\Contracts;

interface BaseInterface {
	/**
	 * When the plugin is activated.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public static function on_plugin_activate(): void;

	/**
	 * When the plugin is uninstalled.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public static function on_plugin_uninstall(): void;
}
