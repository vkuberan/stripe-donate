<?php

namespace VeeKay\StripeDonate\Page;


/**
 * Class StripeSettings
 *
 * @package VeeKay\StripeDonate\Page
 */
class StripeSettings extends AbstractPage implements PageInterface {

	/**
	 * SqlImport constructor.
	 *
	 * @param Database\Importer $dbi
	 */
	public function __construct() {
	}

	/**
	 * @return string
	 */
	public function get_page_title() {

		return esc_html__( 'Stripe API', 'stripe-donate' );
	}

	/**
	 * Return the static slug string.
	 *
	 * @return string
	 */
	public function get_slug() {

		return 'stripe-api';
	}

	/**
	 * Callback function for menu item
	 */
	public function render() {

		require_once dirname(__DIR__) . '/templates/stripe-api.php';
	}

	/**
	 * @return string
	 */
	protected function get_submit_button_title() {

		return esc_html__( 'Update', 'stripe-donate' );
	}

	/**
	 * {@inheritdoc}
	 */
	public function save() {
		if ( ! wp_verify_nonce( $_POST[ $this->nonce_name ], $this->nonce_action ) ) {
			wp_die( 'Cheating Uh?' );
		}

		$is_test         = esc_url_raw( filter_input( INPUT_POST, 'test_stripe' ) );
		$test_pub_key    = esc_url_raw( filter_input( INPUT_POST, 'test_publishable_key' ) );
		$test_secret_key = esc_attr( filter_input( INPUT_POST, 'test_secret_key' ) );
		$live_pub_key    = esc_url_raw( filter_input( INPUT_POST, 'live_publishable_key' ) );
		$live_secret_key = esc_attr( filter_input( INPUT_POST, 'live_secret_key' ) );

		print($is_test . '<br />');
		print($test_pub_key . '<br />');
		print($test_secret_key . '<br />');
		print($live_pub_key . '<br />');
		print($live_secret_key . '<br />');
	}

}
