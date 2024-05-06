<?php
/**
 * Base class to handle the wp action.
 *
 * @package MagicMaker\WpAction
 */

declare(strict_types=1);

namespace MagicMaker\WpAction;

/**
 * Base class
 *
 * @since 1.0.0
 */
abstract class Base {
	/**
	 * Safe redirect
	 *
	 * @since 1.0.0
	 *
	 * @param string $redirect_uri The redirect uri.
	 *
	 * @return void
	 */
	public function safe_redirect( string $redirect_uri ): void {
		wp_safe_redirect( $redirect_uri );
		exit;
	}
}
