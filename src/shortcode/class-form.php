<?php
/**
 * Form class to add the form shortcode.
 *
 * @package MagicMaker\Shortcode
 */

declare(strict_types=1);

namespace MagicMaker\Shortcode;

/**
 * Form class
 *
 * @since 1.0.0
 */
class Form {
	/**
	 * Template name
	 *
	 * @var string
	 */
	public const TEMPLATE_NAME = 'templates/my-form.php';

	/**
	 * Invoke the class.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function __invoke(): void {
		add_shortcode( 'my_form', array( $this, 'my_shortcode_form' ) );
	}

	/**
	 * Render the form.
	 *
	 * @since 1.0.0
	 *
	 * @param array $atts Shortcode attributes.
	 *
	 * @return string
	 */
	public function my_shortcode_form( $atts ): string {
		$atts = shortcode_atts(
			array(
				'title' => 'Magic Maker: Add Things',
			),
			$atts
		);

		// accessible in the template.
		$form_title = $atts['title'];

		$template_path = MAGIC_MAKER_PLUGIN_PATH . '/' . self::TEMPLATE_NAME;

		ob_start();

		include_once $template_path;

		return ob_get_clean();
	}
}
