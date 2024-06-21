<?php

namespace Thawani;
use Automattic\WooCommerce\Blocks\Payments\Integrations\AbstractPaymentMethodType;



class ThawaniCheckoutBlockSupport extends AbstractPaymentMethodType  {
    protected $name ='thawani_gw';
    /**
    * initialize the class for the checkout block support..
    */
    public function initialize() {
        $this->settings = get_option('woocommerce_thawani_gw_settings', []);
    }
    public function is_active() {
        return ! empty( $this->settings[ 'enabled' ] ) && 'yes' === $this->settings[ 'enabled' ];
    }
    public function get_payment_method_script_handles() {

        wp_register_script(
            'wc-thawani-blocks-integration',
            plugin_dir_url( __DIR__ ) . 'build/index.js',
            array(
                'wc-blocks-registry',
                'wc-settings',
                'wp-element',
                'wp-html-entities',
            ),
            null,
            true
        );

        return array( 'wc-thawani-blocks-integration' );

    }

    public function get_payment_method_data() {
        return array(
            'title'        => $this->get_setting( 'title' ),
            'description'  => $this->get_setting( 'description' ),
        );
    }
}

