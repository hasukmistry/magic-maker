<?php
/**
 * The template for displaying the form.
 *
 * @package MagicMaker/Templates
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use MagicMaker\WpAction\Form;

$notice = get_transient( Form::NOTICE_ACTION );
$nonce  = wp_create_nonce( Form::SAVE_NONCE );
?>
<form class="magic-maker-form" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="POST">
	<h2><?php echo esc_html( $form_title ); ?></h2>

	<!-- Start: Notice -->
	<?php if ( ! empty( $notice ) ) : ?>

		<?php
			// Delete transient to prevent displaying the notice multiple times.
			delete_transient( Form::NOTICE_ACTION );
		?>

		<div class="notice notice-<?php echo esc_attr( $notice['type'] ); ?>">

			<p><?php echo esc_html( $notice['message'] ); ?></p>

		</div>

	<?php endif; ?>
	<!-- End: Notice -->

	<div class="field">
		<label><?php echo esc_html__( 'Name :', 'magic-maker' ); ?></label>
		<input type="text" name="name" id="name" required />
	</div>

	<div class="actions">
		<input type="hidden" name="action" value="<?php echo esc_attr( Form::SAVE_ACTION ); ?>" />
		<input type="hidden" name="nonce" value="<?php echo esc_attr( $nonce ); ?>" />

		<input type="submit" name="submit" value="<?php echo esc_html__( 'Submit', 'magic-maker' ); ?>" />
	</div>
</form>
