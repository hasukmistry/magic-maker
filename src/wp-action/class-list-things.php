<?php
/**
 * List_Things class to handle the search form action.
 *
 * @package MagicMaker\WpAction
 */

declare(strict_types=1);

namespace MagicMaker\WpAction;

/**
 * List_Things class
 *
 * @since 1.0.0
 */
class List_Things extends Base {
	/**
	 * Search nonce
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	public const SEARCH_NONCE = 'my_list_nonce';

	/**
	 * Search action
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	public const SEARCH_ACTION = 'my_list_search';

	/**
	 * Notice action
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	public const NOTICE_ACTION = 'my_list_notice_action';

	/**
	 * Per page
	 *
	 * @since 1.0.0
	 *
	 * @var int
	 */
	public const PER_PAGE = 10;

	/**
	 * Invoke the class.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function __invoke(): void {
		add_action(
			sprintf( 'admin_post_%s', self::SEARCH_ACTION ),
			array( $this, 'handle_search_form' )
		);

		add_action(
			sprintf( 'admin_post_nopriv_%s', self::SEARCH_ACTION ),
			array( $this, 'handle_search_form' )
		);

		add_filter( 'query_vars', array( $this, 'query_vars' ) );
	}

	/**
	 * Handle the search form action.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function handle_search_form(): void {
		if ( ! isset( $_REQUEST['search_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_REQUEST['search_nonce'] ) ), self::SEARCH_NONCE ) ) {
			// Set transient for 10 seconds to indicate nonce failure.
			set_transient(
				self::NOTICE_ACTION,
				array(
					'message' => 'Nonce verification failed!',
					'type'    => 'error',
				),
				10
			);

			$this->safe_redirect( wp_get_referer() );
		}

		$redirect_url = remove_query_arg( 'nonce', wp_get_referer() );

		// Extract and sanitize form data.
		if ( ! empty( $_REQUEST['q'] ) ) {
			$q = sanitize_text_field( wp_unslash( $_REQUEST['q'] ) );

			$redirect_url = add_query_arg( 'search_param', $q, $redirect_url );

			$this->safe_redirect( $redirect_url );
		}

		$this->safe_redirect( $redirect_url );
	}

	/**
	 * Query vars.
	 *
	 * @since 1.0.0
	 *
	 * @param array $vars The query vars.
	 *
	 * @return array
	 */
	public function query_vars( array $vars ): array {
		// bail early if the query variable is already set.
		if ( in_array( 'search_param', $vars, true ) ) {
			return $vars;
		}

		$vars[] = 'search_param';

		return $vars;
	}
}
