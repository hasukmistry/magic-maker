<?php
/**
 * Base class to work with database.
 *
 * @package MagicMaker\Db
 */

declare(strict_types=1);

namespace MagicMaker\Db;

use MagicMaker\Db\Contracts\BaseInterface;
use MagicMaker\Exception\Create_Table_Exception;
use MagicMaker\Exception\Db_Result_Exception;
use MagicMaker\Exception\Insert_Exception;
use MagicMaker\Exception\Query_Exception;

/**
 * Base class
 *
 * @since 1.0.0
 */
abstract class Base implements BaseInterface {
	/**
	 * Cache group.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	protected const CACHE_GROUP = 'magic-maker';

	/**
	 * Cache default TTL.
	 *
	 * @since 1.0.0
	 *
	 * @var int
	 */
	protected const CACHE_DEFAULT_TTL = 20;

	/**
	 * Get the table name.
	 *
	 * @since 1.0.0
	 *
	 * @param string $table_name The table name.
	 *
	 * @return string
	 */
	protected static function get_db_table_name( string $table_name ): string {
		global $wpdb;

		return $wpdb->prefix . $table_name;
	}

	/**
	 * Get the database charset.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	protected static function get_db_collation(): string {
		global $wpdb;

		return $wpdb->get_charset_collate();
	}

	/**
	 * Execute the query.
	 *
	 * @since 1.0.0
	 *
	 * @param string $query The query to execute.
	 * @param array  $args  The arguments to pass to the query.
	 *
	 * @return void
	 *
	 * @throws Query_Exception If the query fails.
	 */
	protected static function execute_raw_query( string $query, array $args = array() ): void {
		global $wpdb;

		try {
			// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.NotPrepared
			$wpdb->query( $wpdb->prepare( $query, $args ) );
		} catch ( Query_Exception $e ) {
			throw new Query_Exception( esc_html( $e->getMessage() ) );
		}
	}

	/**
	 * Execute the insert query.
	 *
	 * @since 1.0.0
	 *
	 * @param string $table_name The table name.
	 * @param array  $data       The data to insert.
	 *
	 * @return int
	 *
	 * @throws Insert_Exception If the insert fails.
	 */
	protected static function insert( string $table_name, array $data ): int {
		global $wpdb;

		try {
			// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery
			$result = $wpdb->insert(
				$table_name,
				$data
			);

			if ( false !== $result ) {
				return $wpdb->insert_id;
			}

			throw new Insert_Exception( esc_html( $wpdb->last_error ) );
		} catch ( Insert_Exception $e ) {
			throw new Insert_Exception( esc_html( $e->getMessage() ) );
		}
	}

	/**
	 * Get the data from the database from a given table.
	 *
	 * @since 1.0.0
	 *
	 * @param string $table_name   The table name.
	 * @param int    $offset       The offset.
	 * @param int    $per_page     The number of items per page.
	 * @param string $search_query The search query.
	 *
	 * @return array
	 *
	 * @throws Db_Result_Exception If the query fails.
	 */
	protected static function get(
		string $table_name,
		int $offset,
		int $per_page,
		string $search_query = ''
	): array {
		global $wpdb;

		try {
			$key    = 'items-' . sanitize_title( $table_name . $search_query );
			$cached = wp_cache_get( $key, self::CACHE_GROUP );

			// wp_cache_delete( $key, self::CACHE_GROUP );

			if ( $cached ) {
				return $cached;
			}

			if ( ! empty( $search_query ) ) {
				// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery
				$results = $wpdb->get_results(
					$wpdb->prepare( 'SELECT * FROM %i WHERE `name` LIKE %s LIMIT %d, %d', $table_name, '%%' . $wpdb->esc_like( $search_query ) . '%%', $offset, $per_page )
				);
			} else {
				// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery
				$results = $wpdb->get_results(
					$wpdb->prepare( 'SELECT * FROM %i LIMIT %d, %d', $table_name, $offset, $per_page )
				);
			}

			wp_cache_set( $key, $results, self::CACHE_GROUP, self::CACHE_DEFAULT_TTL );

			return $results;
		} catch ( Db_Result_Exception $e ) {
			throw new Db_Result_Exception( esc_html( $e->getMessage() ) );
		}
	}

	/**
	 * Get the total number of items in the database for a given table.
	 *
	 * @since 1.0.0
	 *
	 * @param string $table_name   The table name.
	 * @param string $search_query The search query.
	 *
	 * @return int
	 *
	 * @throws Db_Result_Exception If the query fails.
	 */
	protected static function get_total_items(
		string $table_name,
		string $search_query = ''
	): int {
		global $wpdb;

		try {
			$key    = 'total-items-' . sanitize_title( $table_name . $search_query );
			$cached = wp_cache_get( $key, self::CACHE_GROUP );

			wp_cache_delete( $key, self::CACHE_GROUP );

			if ( $cached ) {
				return $cached;
			}

			if ( ! empty( $search_query ) ) {
				// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery
				$value = $wpdb->get_var(
					$wpdb->prepare( 'SELECT COUNT(*) FROM %i WHERE `name` LIKE %s', $table_name, '%%' . $wpdb->esc_like( $search_query ) . '%%' )
				);
			} else {
				// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery
				$value = $wpdb->get_var(
					$wpdb->prepare( 'SELECT COUNT(*) FROM %i', $table_name )
				);
			}

			// Check if $value is NULL (no items in the table).
			if ( null === $value ) {
				$value = 0;
			}

			wp_cache_set( $key, $value, self::CACHE_GROUP, self::CACHE_DEFAULT_TTL );

			return absint( $value );
		} catch ( Db_Result_Exception $e ) {
			throw new Db_Result_Exception( esc_html( $e->getMessage() ) );
		}
	}

	/**
	 * Execute the create table query.
	 *
	 * @since 1.0.0
	 *
	 * @param string $query The query to execute.
	 *
	 * @return void
	 *
	 * @throws Create_Table_Exception If the create table fails.
	 */
	protected static function create_table( string $query ): void {
		try {
			require_once ABSPATH . 'wp-admin/includes/upgrade.php';

			dbDelta( $query );
		} catch ( Create_Table_Exception $e ) {
			throw new Create_Table_Exception( esc_html( $e->getMessage() ) );
		}
	}
}
