<?php
/*
Plugin Name: FacetWP - Multilingual support
Description: Multilingual support for FacetWP
Version: 0.2.2
Author: FacetWP, LLC
Author URI: https://facetwp.com/
GitHub URI: facetwp/facetwp-i18n
*/

defined( 'ABSPATH' ) || exit;

/**
 * Class FWP_i18n
 */
class FWP_i18n {

	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'init' ) );
	}


	/**
	 * Intialize
	 */
	public function init() {
		if ( function_exists( 'FWP' ) ) {
			if ( function_exists( 'pll_register_string' ) ) {
				include dirname( __FILE__ ) . '/includes/class-polylang.php';
			}

			if ( defined( 'ICL_SITEPRESS_VERSION' ) ) {
				include dirname( __FILE__ ) . '/includes/class-wpml.php';
			}

			if ( function_exists( 'trp_enable_translatepress' ) ) {
				include dirname( __FILE__ ) . '/includes/class-translatepress.php';
			}
		}
	}
}

new FWP_i18n();
