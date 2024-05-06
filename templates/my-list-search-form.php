<?php
/**
 * The template for the search form.
 *
 * @package MagicMaker/Templates
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use MagicMaker\WpAction\List_Things;

$search_query = sanitize_text_field( get_query_var( 'search_param' ) ) ?? '';

$notice = get_transient( List_Things::NOTICE_ACTION );
$nonce  = wp_create_nonce( List_Things::SEARCH_NONCE );
?>
<form class="magic-maker-search-form" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="GET">
	<h3><?php echo esc_html( $search_form_title ); ?></h3>

	<!-- Start: Notice -->
	<?php if ( ! empty( $notice ) ) : ?>

		<?php
			// Delete transient to prevent displaying the notice multiple times.
			delete_transient( List_Things::NOTICE_ACTION );
		?>

		<div class="notice notice-<?php echo esc_attr( $notice['type'] ); ?>">

			<p><?php echo esc_html( $notice['message'] ); ?></p>

		</div>

	<?php endif; ?>
	<!-- End: Notice -->

	<div class="field">
		<input type="text" name="q" id="q" placeholder="<?php echo esc_html__( 'Search', 'magic-maker' ); ?>" value="<?php echo esc_attr( $search_query ); ?>" />
	</div>

	<div class="actions">
		<input type="hidden" name="action" value="<?php echo esc_attr( List_Things::SEARCH_ACTION ); ?>" />
		<input type="hidden" name="search_nonce" value="<?php echo esc_attr( $nonce ); ?>" />
		<input type="submit" name="submit" value="Search" />
	</div>
</form>
