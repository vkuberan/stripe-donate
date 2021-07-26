<?php
/**
 * Template for displaying sql import page
 */

// Prevent direct access.
if ( ! defined( 'STRIPE_DONATE_BASEDIR' ) ) {
	echo "Hi there!  I'm just a part of plugin, not much I can do when called directly.";
	exit;
}
?>

<form action="" method="post">
	<table class="form-table">

    <tbody>

        <tr>
			<th>
				<strong>
					<?php esc_html_e( 'Enable Test Account', 'stripe-donate' ); ?>
				</strong>
			</th>

			<td>
                <input type="checkbox"  
					<?php if ($options['is_test'] == 'on') echo 'checked'; ?>
					name="test_stripe" 
					id="test_stripe">
            </td>
		</tr>

		<tr>
			<th>
				<strong>
					<?php esc_html_e( 'Test Publishable Key', 'stripe-donate' ); ?>
				</strong>
			</th>

			<td><input type="text" value="<?php echo $options['test_pub_key'] ?>" class="regular-text" name="test_publishable_key" id="test_publishable_key"></td>
		</tr>

		<tr>
			<th>
				<strong>
					<?php esc_html_e( 'Test Secret Key', 'stripe-donate' ); ?>
				</strong>
			</th>

			<td><input type="text" value="<?php echo $options['test_secret_key'] ?>" class="regular-text" name="test_secret_key" id="test_secret_key"></td>
		</tr>

		<tr>
			<th>
				<strong>
					<?php esc_html_e( 'Live Publishable Key', 'stripe-donate' ); ?>
				</strong>
			</th>

			<td><input type="text" value="<?php echo $options['live_pub_key'] ?>" class="regular-text" name="live_publishable_key" id="live_publishable_key"></td>
		</tr>

		<tr>
			<th>
				<strong>
					<?php esc_html_e( 'Live Secret Key', 'stripe-donate' ); ?>
				</strong>
			</th>

			<td><input type="text" value="<?php echo $options['live_secret_key'] ?>" class="regular-text" name="live_secret_key" id="live_secret_key"></td>
		</tr>
		
		</tbody>
	</table>
	<?php $this->show_submit_button(); ?>
</form>
