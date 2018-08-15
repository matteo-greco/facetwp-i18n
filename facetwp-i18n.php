<?php
/*
Plugin Name: FacetWP - Multilingual support
Description: Multilingual support for FacetWP
Version: 0.2
Author: FacetWP, LLC
Author URI: https://facetwp.com/
GitHub URI: facetwp/facetwp-i18n
*/

defined( 'ABSPATH' ) or exit;

class FWP_i18n
{

    function __construct() {
        add_action( 'init' , array( $this, 'init' ) );
    }


    /**
     * Intialize
     */
    function init() {
        $this->load_textdomain();

        if ( function_exists( 'FWP' ) ) {
            if ( function_exists( 'pll_register_string' ) ) {
                include( dirname( __FILE__ ) . '/includes/class-polylang.php' );
            }

            if ( defined( 'ICL_SITEPRESS_VERSION' ) ) {
                include( dirname( __FILE__ ) . '/includes/class-wpml.php' );
            }
        }
    }


    /**
     * Text domain
     */
    function load_textdomain() {
        $locale = apply_filters( 'plugin_locale', get_locale(), 'fwp' );
        $mofile = WP_LANG_DIR . '/facetwp/fwp-' . $locale . '.mo';

        if ( file_exists( $mofile ) ) {
            load_textdomain( 'fwp', $mofile );
        }
        else {
            load_plugin_textdomain( 'fwp', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
        }
    }
}

new FWP_i18n();
