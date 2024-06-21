<?php

/**
 * Plugin Name: ThawaniPay C
 * Plugin URI: https://github.com/w7shdev/thawanipay-c
 * Author: w7shdev
 * Author URI: https://github.com/w7shdev/thawanipay-c
 * Description: Thawani Payments Gateway for Woocommerce with support for blocks.
 * Version: 0.1.0
 * License: MIT
License URL: https://mit-license.org/
 * text-domain: thawani
 * Domain Path : /languages
 *
 *
 *@package WooCommerce\Thawani
 */

require_once plugin_dir_path(__FILE__) . '/vendor/autoload.php';

use \Thawani\WC_Gateway_ThawaniGateway;
use \Thawani\ThawaniCheckoutBlockSupport;
use Thawani\RestAPI;

if (!defined('ABSPATH'))
    exit;

// define the path of the plugin
define('THAWANI_GW_DIR', plugin_dir_path(__FILE__));

/**
 * Check if the WooCommerce plugin is active
 */
if (!in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins'))))
    return;

function add_thawani_gateway($gateways)
{
    $gateways[] = '\Thawani\WC_Gateway_ThawaniGateway';
    return $gateways;
}

add_filter('woocommerce_payment_gateways', 'add_thawani_gateway');

/**
 * loading the translation files to support arabic version
 * @since 1.1.0
 */
add_action('init', 'thawani_gw_load_textDomain');

/**
 * Load plugin textdomain.
 */
function thawani_gw_load_textDomain()
{
    load_plugin_textdomain('thawani', false, dirname(plugin_basename(__FILE__)) . '/languages');
}

/*
* add message to tell the block is not compatible with customization.
*/
add_action( 'before_woocommerce_init', 'thawani_cart_checkout_blocks_compatibility' );
function thawani_cart_checkout_blocks_compatibility() {

    if( class_exists( '\Automattic\WooCommerce\Utilities\FeaturesUtil' ) ) {
        \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility(
				'cart_checkout_blocks',
				__FILE__,
				false
			);
    }

}

/*
* add support for woocommerce blocks
*/
add_action( 'woocommerce_blocks_loaded', 'thawani_gateway_block_support' );
function thawani_gateway_block_support() {

	if( ! class_exists( 'Automattic\WooCommerce\Blocks\Payments\Integrations\AbstractPaymentMethodType' ) ) {
		return;
	}
	add_action(
		'woocommerce_blocks_payment_method_type_registration',
		function( Automattic\WooCommerce\Blocks\Payments\PaymentMethodRegistry $payment_method_registry ) {
			$payment_method_registry->register( new ThawaniCheckoutBlockSupport());
		}
	);

}
