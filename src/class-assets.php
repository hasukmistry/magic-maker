<?php
/**
 * Assets class to load assets.
 *
 * @package MagicMaker
 */

declare(strict_types=1);

namespace MagicMaker;

use MagicMaker\RestApi\Base;

/**
 * Assets class
 *
 * @since 1.0.0
 */
class Assets {
	/**
	 * Invoke the class.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function __invoke(): void {
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
	}

	/**
	 * Enqueue the scripts.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function enqueue_scripts(): void {
		wp_enqueue_style(
			'magic-maker-style',
			MAGIC_MAKER_PLUGIN_URL . '/assets/css/style.css',
			array(),
			'1.0.0'
		);

		wp_register_script(
			'magic-maker-script',
			MAGIC_MAKER_PLUGIN_URL . '/assets/js/script.js',
			array(),
			'1.0.0',
			true
		);

		wp_localize_script(
			'magic-maker-script',
			'magicMaker',
			array(
				'rest' => array(
					'url'   => esc_url_raw( rest_url( Base::ROUTE_NAMESPACE ) ),
					'nonce' => wp_create_nonce( Base::NONCE ),
				),
			)
		);

		wp_enqueue_script( 'magic-maker-script' );
	}
}
