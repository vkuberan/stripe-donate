<?php

namespace VeeKay\StripeDonate\Page;
  

/**
 * Class StripeSettings
 *
 * @package VeeKay\StripeDonate\Page
 */
class StripeSettings extends AbstractPage implements PageInterface {

  /**
   * @return string
   */
  public function get_page_title() {
    return esc_html__( 'Stripe API', 'stripe-donate' );
  }

  /**
   * @return string
   */
  public function get_menu_title() {
    return esc_html__( 'Settings', 'stripe-donate' );
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
    $options = [
      'is_test' => get_option( 'stripe_donation_is_test' ),
      'test_pub_key' => get_option( 'stripe_donation_test_pub_key' ),
      'test_secret_key' => get_option( 'stripe_donation_test_secret_key' ),
      'live_pub_key' => get_option( 'stripe_donation_live_pub_key' ),
      'live_secret_key' => get_option( 'stripe_donation_live_secret_key' )
	];

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
    $is_test         = esc_attr( filter_input( INPUT_POST, 'test_stripe' ) );
    $test_pub_key    = esc_attr( filter_input( INPUT_POST, 'test_publishable_key' ) );
    $test_secret_key = esc_attr( filter_input( INPUT_POST, 'test_secret_key' ) );
    $live_pub_key    = esc_attr( filter_input( INPUT_POST, 'live_publishable_key' ) );
    $live_secret_key = esc_attr( filter_input( INPUT_POST, 'live_secret_key' ) );

    update_option( 'stripe_donation_is_test', $is_test );
    update_option( 'stripe_donation_test_pub_key', $test_pub_key );
    update_option( 'stripe_donation_test_secret_key', $test_secret_key );
    update_option( 'stripe_donation_live_pub_key', $live_pub_key );
    update_option( 'stripe_donation_live_secret_key', $live_secret_key );

  }

}
