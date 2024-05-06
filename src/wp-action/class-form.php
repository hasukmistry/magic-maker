<?php
/**
 * Form class to handle the form action.
 *
 * @package MagicMaker\WpAction
 */

declare(strict_types=1);

namespace MagicMaker\WpAction;

use MagicMaker\Db\Things;
use MagicMaker\Exception\Insert_Exception;

/**
 * Form class
 *
 * @since 1.0.0
 */
class Form extends Base {
	/**
	 * Save nonce
	 *
	 * @var string
	 */
	public const SAVE_NONCE = 'my_form_nonce';

	/**
	 * Save action
	 *
	 * @var string
	 */
	public const SAVE_ACTION = 'my_form_save';

	/**
	 * Notice action
	 *
	 * @var string
	 */
	public const NOTICE_ACTION = 'my_form_notice_action';

	/**
	 * Invoke the class.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function __invoke(): void {
		add_action(
			sprintf( 'admin_post_%s', self::SAVE_ACTION ),
			array( $this, 'handle_form' )
		);

		add_action(
			sprintf( 'admin_post_nopriv_%s', self::SAVE_ACTION ),
			array( $this, 'handle_form' )
		);
	}

	/**
	 * Handle the form action.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function handle_form(): void {
		if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), self::SAVE_NONCE ) ) {
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

		try {
			// Extract and sanitize form data.
			$name = ! empty( $_POST['name'] ) ? sanitize_text_field( wp_unslash( $_POST['name'] ) ) : '';

			Things::add_thing(
				array(
					'name' => $name,
				)
			);

			// Set transient for 10 seconds to indicate successful form submission.
			set_transient(
				self::NOTICE_ACTION,
				array(
					'message' => 'Things added successfully!',
					'type'    => 'success',
				),
				10
			);

			$this->safe_redirect( wp_get_referer() );
		} catch ( Insert_Exception $e ) {
			set_transient(
				self::NOTICE_ACTION,
				array(
					'message' => esc_html( $e->getMessage() ),
					'type'    => 'error',
				),
				10
			);

			$this->safe_redirect( wp_get_referer() );
		}
	}
}
