<?php
/**
 * Class to manage things in the database.
 *
 * @package MagicMaker\Db
 */

declare(strict_types=1);

namespace MagicMaker\Db;

use MagicMaker\Exception\Create_Table_Exception;
use MagicMaker\Exception\Db_Result_Exception;
use MagicMaker\Exception\Insert_Exception;
use MagicMaker\Exception\Query_Exception;

/**
 * Things class
 *
 * @since 1.0.0
 */
class Things extends Base {
	/**
	 * The table name.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 *
	 * @access public
	 */
	public const TABLE_NAME = 'magic_maker_things';

	/**
	 * When the plugin is activated.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 *
	 * @throws Create_Table_Exception If the table creation fails.
	 */
	public static function on_plugin_activate(): void {
		$table_name      = self::get_db_table_name( self::TABLE_NAME );
		$charset_collate = self::get_db_collation();

		$query = "CREATE TABLE IF NOT EXISTS $table_name (
			id mediumint(9) NOT NULL AUTO_INCREMENT,
			name VARCHAR(50) NOT NULL,
			`created` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
			PRIMARY KEY (id)
		) $charset_collate;";

		self::create_table( $query );
	}

	/**
	 * Add things to the database.
	 *
	 * @since 1.0.0
	 *
	 * @param array $data The data to insert.
	 *
	 * @return int
	 *
	 * @throws Insert_Exception If the insert fails.
	 */
	public static function add_thing( array $data ): int {
		$table_name = self::get_db_table_name( self::TABLE_NAME );

		return self::insert( $table_name, $data );
	}

	/**
	 * Get things from the database.
	 *
	 * @since 1.0.0
	 *
	 * @param int    $offset       The offset.
	 * @param int    $per_page     The number of items per page.
	 * @param string $search_query The search query.
	 *
	 * @return array
	 *
	 * @throws Db_Result_Exception If the query fails.
	 */
	public static function get_things(
		int $offset,
		int $per_page,
		string $search_query = ''
	): array {
		$table_name = self::get_db_table_name( self::TABLE_NAME );

		return self::get( $table_name, $offset, $per_page, $search_query );
	}

	/**
	 * Get the total number of things in the database.
	 *
	 * @since 1.0.0
	 *
	 * @param string $search_query The search query.
	 *
	 * @return int
	 *
	 * @throws Db_Result_Exception If the query fails.
	 */
	public static function get_total_things( string $search_query = '' ): int {
		$table_name = self::get_db_table_name( self::TABLE_NAME );

		return self::get_total_items( $table_name, $search_query );
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
	public static function on_plugin_uninstall(): void {
		$table_name = self::get_db_table_name( self::TABLE_NAME );

		self::execute_raw_query( 'DROP TABLE IF EXISTS %s', array( $table_name ) );
	}
}
