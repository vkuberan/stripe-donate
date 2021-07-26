<?php

namespace VeeKay\StripeDonate\Page;


/**
 * Class StripeSettings
 *
 * @package VeeKay\StripeDonate\Page
 */
class StripeDonations extends AbstractPage implements PageInterface {

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

		return esc_html__( 'All Donations', 'stripe-donate' );
	}

	/**
	 * @return string
	 */
	public function get_menu_title() {

		return esc_html__( 'All Donations', 'stripe-donate' );
	}

	/**
	 * Return the static slug string.
	 *
	 * @return string
	 */
	public function get_slug() {

		return 'stripe-all-donations';
	}

	/**
	 * Callback function for menu item
	 */
	public function render() {

		require_once dirname(__DIR__) . '/templates/all-donations.php';
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
	
    }

}
