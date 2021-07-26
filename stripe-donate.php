<?php # -*- coding: utf-8 -*-
/**
 * Plugin Name: Stripe Donate
 * Description: Simple Wordpress donation plugin using Stripe as its payment processor.
 * Plugin URI:  https://github.com/vkuberan/stripe-donate
 * Version:     1.0
 * Author:      Velmurugan Kuberan
 * Author URI:  https://github.com/vkuberan
 * Licence:     GPLv3
 * Text Domain: stripe-donate
 * Code is based on inpsyde google-tag-manager
 * https://github.com/inpsyde/google-tag-manager/
 */

use VeeKay\StripeDonate\Database;
use VeeKay\StripeDonate\Page;

// Make sure we don't expose any info if called directly
if ( !function_exists( 'add_action' ) ) {
	echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
	exit;
}

register_activation_hook( __FILE__, 'stripe_donate_activate' );

/**
 * Validate requirements on activation
 *
 * Runs on plugin activation.
 * Check if php min 5.6.0 if not deactivate the plugin.
 *
 * @since 3.1.1
 *
 * @return void
 */
function stripe_donate_activate() {

	$required_php_version = '5.6.0';
	$correct_php_version  = version_compare( PHP_VERSION, $required_php_version, '>=' );

	load_plugin_textdomain('stripe-donate');

	if ( ! $correct_php_version ) {
		deactivate_plugins( basename( __FILE__ ) );

		wp_die(
			'<p>' .
			sprintf(
			// translators: %1$s will replace with the PHP version of the client.
				esc_attr__(
					'This plugin can not be activated because it requires at least PHP version %1$s. ',
					'stripe-donate'
				),
				$required_php_version
			)
			. '</p> <a href="' . admin_url( 'plugins.php' ) . '">'
			. esc_attr__( 'back', 'stripe-donate' ) . '</a>'
		);

	}

}

add_action('plugins_loaded', 'initialize');

function initialize()
{
    load_plugin_textdomain('stripe-donate');

    define( 'STRIPE_DONATE_BASEDIR', plugin_dir_url( __FILE__ ) );
    
	$file     = __DIR__ . '/vendor/autoload.php';

	/** @noinspection PhpIncludeInspection */
	include_once $file;

    //handle backend;
	if ( is_admin() ) {

        $user_cap = apply_filters( 'stripe_donate_access_capability', 'manage_options' );

        if ( ! current_user_can( $user_cap ) || ! file_exists( $file ) ) {
            return false;
        }

        $page_manager = new Page\Manager();

        $page_manager->add_page( new Page\StripeSettings() );

        add_action( 'admin_menu', [ $page_manager, 'register_pages' ] );
        
        add_action( 'admin_head', [ $page_manager, 'remove_submenu_pages' ] );
    
	}
}
