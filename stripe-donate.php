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

namespace VeeKay\StripeDonate;


// Make sure we don't expose any info if called directly
if ( !function_exists( 'add_action' ) ) {
	echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
	exit;
}

add_action('plugins_loaded', __NAMESPACE__.'\initialize');

/**
 * @wp-hook plugins_loaded
 *
 * @throws \Throwable   When WP_DEBUG=TRUE exceptions will be thrown.
 */

function initialize()
{
    try {
        load_plugin_textdomain( 'stripe-donate' );

        if (! checkPluginRequirements()) {
            return false;
        }

        (new GoogleTagManager())
            ->set('config', ConfigBuilder::fromFile(__FILE__)->freeze())
            ->register(new App\Provider\AssetProvider())
            ->register(new App\Provider\FormProvider())
            ->register(new App\Provider\DataLayerProvider())
            ->register(new App\Provider\RendererProvider())
            ->register(new App\Provider\SettingsProvider())
            ->boot();
    } catch (\Throwable $exception) {
        if (defined('WP_DEBUG') && WP_DEBUG) {
            throw $exception;
        }

        return false;
    }

    return true;
}


/**
 * @return bool
 */
function checkPluginRequirements()
{
    $min_php_version = '7.2';
    $current_php_version = phpversion();
    if (! version_compare($current_php_version, $min_php_version, '>=')) {
        adminNotice(
            sprintf(
            /* translators: %1$s is the min PHP-version, %2$s the current PHP-version */
                __(
                    'Stripe Donate requires PHP version %1$1s or higher. You are running version %2$2s.',
                    'stripe-donate'
                ),
                $min_php_version,
                $current_php_version
            )
        );

        return false;
    }

    if (! class_exists(GoogleTagManager::class)) {
        $autoloader = __DIR__.'/vendor/autoload.php';
        if (! file_exists($autoloader)) {
            adminNotice(
                __(
                    'Could not find a working autoloader for Stripe Donate.',
                    'stripe-donate'
                )
            );

            return false;
        }

        /** @noinspection PhpIncludeInspection */
        require $autoloader;
    }

    return true;
}

/**
 * @param string $message
 */
function adminNotice(string $message)
{
    add_action(
        'admin_notices',
        function () use ($message) {
            printf(
                '<div class="notice notice-error"><p>%1$s</p></div>',
                esc_html($message)
            );
        }
    );
}
