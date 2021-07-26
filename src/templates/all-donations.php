<?php
/**
 * Template for displaying sql export page
 */

// Prevent direct access.
if ( ! defined( 'STRIPE_DONATE_BASEDIR' ) ) {
	echo "Hi there!  I'm just a part of plugin, not much I can do when called directly.";
	exit;
}
?>

<h2><?php esc_html_e( 'All Donations', 'stripe-donate' ); ?></h2>
<p>
	<?php esc_html_e( 'Stripe Donate is a simple plugin created for demo purpose.', 'stripe-donate' ); ?>
</p>

