<?php
/**
 * The template for listing the things.
 *
 * @package MagicMaker/Templates
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use MagicMaker\Db\Things;
use MagicMaker\WpAction\List_Things;

$custom_per_page = List_Things::PER_PAGE;

if ( isset( $_GET['nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['nonce'] ) ), 'pagination_nonce' ) ) {
	// Nonce verification successful, proceed with pagination.
	$page_number = isset( $_GET['current'] ) ? absint( $_GET['current'] ) : 1;
	$offset      = ( $page_number - 1 ) * $custom_per_page;
} else {
	$page_number = 1;
	$offset      = 0;
}

// Retrieve query variable from the current URL.
$search_query = sanitize_text_field( get_query_var( 'search_param' ) ) ?? '';

$things       = Things::get_things( $offset, $custom_per_page, $search_query );
$total_things = Things::get_total_things( $search_query );
?>
<div class="things">
	<h2><?php echo esc_html( $list_title ); ?></h2>

	<?php echo do_shortcode( '[my_list_search_form title="' . esc_attr( $search_form_title ) . '"]' ); ?>

	<?php if ( $total_things > 0 ) : ?>

		<ul>

			<?php foreach ( $things as $thing ) : ?>

				<li class="thing">

					<label><?php echo esc_html( $thing->name ); ?></label>

				</li>

			<?php endforeach; ?>

		</ul>

	<?php else : ?>

		<p>

			<?php if ( $search_query ) : ?>

				<?php echo esc_html( $search_query ); ?> <?php esc_html_e( 'not found.', 'magic-maker' ); ?>

			<?php else : ?>

				<?php esc_html_e( 'No things found.', 'magic-maker' ); ?>

			<?php endif; ?>

		</p>

	<?php endif; ?>

	<?php
	if ( $total_things > $custom_per_page ) :
		$total_pages = ceil( $total_things / $custom_per_page );

		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo paginate_links(
			array(
				'base'      => add_query_arg(
					array(
						'current' => '%#%',
						'nonce'   => wp_create_nonce( 'pagination_nonce' ),
					)
				),
				'format'    => '',
				'prev_text' => '&laquo; Previous',
				'next_text' => 'Next &raquo;',
				'total'     => $total_pages,
				'current'   => $page_number,
			)
		);
	endif;
	?>
</div>
