<?php
/**
 * List_Things class to add the list shortcode.
 *
 * @package MagicMaker\Shortcode
 */

declare(strict_types=1);

namespace MagicMaker\Shortcode;

/**
 * List_Things class
 *
 * @since 1.0.0
 */
class List_Things {
	/**
	 * Template name
	 *
	 * @var string
	 */
	public const TEMPLATE_NAME = 'templates/my-list-things.php';

	/**
	 * Search template name
	 *
	 * @var string
	 */
	public const SEARCH_TEMPLATE_NAME = 'templates/my-list-search-form.php';

	/**
	 * Invoke the class.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function __invoke(): void {
		add_shortcode( 'my_list', array( $this, 'my_shortcode_list' ) );
		add_shortcode( 'my_list_search_form', array( $this, 'my_list_search_form' ) );
	}

	/**
	 * Render the list.
	 *
	 * @since 1.0.0
	 *
	 * @param array $atts Shortcode attributes.
	 *
	 * @return string
	 */
	public function my_shortcode_list( $atts ): string {
		$atts = shortcode_atts(
			array(
				'title'             => __( 'Magic Maker: All Things', 'magic-maker' ),
				'search-form-title' => __( 'Magic Maker: Search Things', 'magic-maker' ),
			),
			$atts
		);

		// accessible in the template.
		$list_title        = $atts['title'];
		$search_form_title = $atts['search-form-title'];

		$template_path = MAGIC_MAKER_PLUGIN_PATH . '/' . self::TEMPLATE_NAME;

		ob_start();

		include_once $template_path;

		return ob_get_clean();
	}

	/**
	 * Render the search form.
	 *
	 * @since 1.0.0
	 *
	 * @param array $atts Shortcode attributes.
	 *
	 * @return string
	 */
	public function my_list_search_form( $atts ): string {
		$atts = shortcode_atts(
			array(
				'title' => 'Magic Maker: Search Things',
			),
			$atts
		);

		// accessible in the template.
		$search_form_title = $atts['title'];

		$template_path = MAGIC_MAKER_PLUGIN_PATH . '/' . self::SEARCH_TEMPLATE_NAME;

		ob_start();

		include_once $template_path;

		return ob_get_clean();
	}
}
