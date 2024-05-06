<?php
/**
 * Class to handle the things rest api.
 *
 * @package MagicMaker\RestApi
 */

declare(strict_types=1);

namespace MagicMaker\RestApi;

use MagicMaker\Db\Things;
use MagicMaker\Exception\Db_Result_Exception;
use MagicMaker\Exception\Insert_Exception;
use MagicMaker\WpAction\List_Things;
use WP_REST_Request;
use WP_REST_Response;

/**
 * Things_Api class
 *
 * @since 1.0.0
 */
class Things_Api extends Base {
	/**
	 * Processing error message
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	public const PROCESSING_ERROR_MESSAGE = 'An error occurred while processing the request.';

	/**
	 * Registers the routes
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function register_routes(): void {
		register_rest_route(
			self::ROUTE_NAMESPACE,
			'/things(?:/page/(?P<page>\d+))?',
			array(
				'methods'             => \WP_REST_Server::READABLE,
				'callback'            => array( $this, 'get_things' ),
				'permission_callback' => array( $this, 'verify_nonce' ),
			)
		);

		register_rest_route(
			self::ROUTE_NAMESPACE,
			'/things/search/(?P<search>[^/]+)(?:/page/(?P<page>\d+))?',
			array(
				'methods'             => \WP_REST_Server::READABLE,
				'callback'            => array( $this, 'get_things' ),
				'permission_callback' => array( $this, 'verify_nonce' ),
			)
		);

		register_rest_route(
			self::ROUTE_NAMESPACE,
			'/things/add',
			array(
				'methods'             => \WP_REST_Server::CREATABLE,
				'callback'            => array( $this, 'add_thing' ),
				'permission_callback' => array( $this, 'verify_nonce' ),
			)
		);
	}

	/**
	 * Get the things
	 *
	 * @since 1.0.0
	 *
	 * @param WP_REST_Request $request The request object.
	 *
	 * @return WP_REST_Response
	 */
	public function get_things( WP_REST_Request $request ): WP_REST_Response {
		try {
			$page     = $request->get_param( 'page' ) ?? 1;
			$per_page = List_Things::PER_PAGE;
			$offset   = ( $page - 1 ) * $per_page;

			$search_query = sanitize_text_field( $request->get_param( 'search' ) ) ?? '';

			$things       = Things::get_things( $offset, $per_page, $search_query );
			$total_things = Things::get_total_things( $search_query );

			$total_pages = ceil( $total_things / $per_page );

			return new WP_REST_Response(
				array(
					'things'     => $things,
					'pagination' => array(
						'current_page' => $page,
						'per_page'     => $per_page,
						'total_pages'  => $total_pages,
					),
				)
			);
		} catch ( Db_Result_Exception $exception ) {
			return new WP_REST_Response(
				array(
					'message' => $exception->getMessage(),
				),
				500
			);
		} catch ( \Throwable $exception ) {
			return new WP_REST_Response(
				array(
					'message' => self::PROCESSING_ERROR_MESSAGE,
				),
				500
			);
		}
	}

	/**
	 * Add a thing
	 *
	 * @since 1.0.0
	 *
	 * @param WP_REST_Request $request The request object.
	 *
	 * @return WP_REST_Response
	 *
	 * @throws Insert_Exception If the insert fails.
	 */
	public function add_thing( WP_REST_Request $request ): WP_REST_Response {
		try {
			$parameters = $request->get_params();

			// Extract and sanitize form data.
			$name = ! empty( $parameters['name'] ) ? sanitize_text_field( $parameters['name'] ) : '';

			if ( empty( $name ) ) {
				throw new Insert_Exception( __( 'Name is required.', 'magic-maker' ) );
			}

			$thing_id = Things::add_thing(
				array(
					'name' => $name,
				)
			);

			return new WP_REST_Response(
				array(
					'thing_id' => $thing_id,
					'success'  => true,
				),
			);
		} catch ( Insert_Exception $exception ) {
			return new WP_REST_Response(
				array(
					'message' => $exception->getMessage(),
				),
				500
			);
		} catch ( \Throwable $exception ) {
			return new WP_REST_Response(
				array(
					'message' => self::PROCESSING_ERROR_MESSAGE,
				),
				500
			);
		}
	}
}
