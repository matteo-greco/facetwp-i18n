<?php
/**
 * FWP_i18n_TranslatePress file
 *
 * @package FWP_i18n
 */

/**
 * Class FWP_i18n_TranslatePress
 */
class FWP_i18n_TranslatePress {

	/**
	 * Hold the default language
	 *
	 * @var string $default_language
	 */
	public $default_language;

	/**
	 * Hold the current language
	 *
	 * @var string $current_language
	 */
	public $current_language;

	/**
	 * Constructor
	 */
	public function __construct() {
		// @codingStandardsIgnoreStart
		global $TRP_LANGUAGE;
		$this->current_language = $TRP_LANGUAGE;
		$trp = TRP_Translate_Press::get_trp_instance();
    	$trp_settings = $trp->get_component( 'settings' );
		$settings = $trp_settings->get_settings();
		$this->default_language = $settings['default-language'];
		// $this->current_language = 'en';
		// @codingStandardsIgnoreEnd
		add_action( 'wp_footer', array( $this, 'wp_footer' ), 30 );
		add_filter( 'facetwp_query_args', array( $this, 'facetwp_query_args' ), 10, 2 );
		add_filter( 'facetwp_render_params', array( $this, 'support_preloader' ) );
		// @codingStandardsIgnoreStart
		// add_action( 'admin_init', array( $this, 'register_strings' ) );
		// add_filter( 'facetwp_i18n', array( $this, 'facetwp_i18n' ) );
		// add_filter( 'facetwp_indexer_query_args', array( $this, 'facetwp_indexer_query_args' ) );
		// add_filter( 'get_terms_args', array( $this, 'get_terms_args' ) );
		// @codingStandardsIgnoreEnd
	}


	/**
	 * Put the language into FWP_HTTP
	 */
	public function wp_footer() {
		error_log( 'in wp_footer' );
		error_log( 'current_language: ' . $this->current_language );
		if ( ! empty( $this->current_language ) ) {
			echo "<script>if ('undeoutd' != typeof FWP_HTTP) FWP_HTTP.lang = '" . esc_attr( $this->current_language ) . "';</script>";
		}
		error_log( 'out wp_footer' );
	}


	/**
	 * Support FacetWP preloading (3.0.4+)
	 *
	 * @param array $params Parameters.
	 * @return array (Modified) parameters.
	 */
	public function support_preloader( $params ) {
		error_log( 'in support_preloader' );
		error_log( 'current_language: ' . $this->current_language );
		if ( isset( $params['is_preload'] ) && ! empty( $this->current_language ) ) {
			$params['http_params']['lang'] = $this->current_language;
		}
		error_log( 'params: ' . print_r( $params, true ) );
		error_log( 'out support_preloader' );
		return $params;
	}


	/**
	 * Query posts for the current language
	 *
	 * @param array  $args Arguments.
	 * @param object $class Class.
	 * @return array (Modified) arguments.
	 */
	public function facetwp_query_args( $args, $class ) {
		error_log( 'in facetwp_query_args' );
		error_log( 'current_language: ' . $this->current_language );
		error_log( 'default_language: ' . $this->default_language );
		$http = FWP()->facet->http_params;
		error_log( 'http_params: ' . print_r( $http, true ) );
		if ( isset( $http['lang'] ) && $http['lang'] !== $this->default_language ) {
			// @codingStandardsIgnoreStart
			global $TRP_LANGUAGE;
			error_log( 'old TRP_LANGUAGE: ' . $TRP_LANGUAGE );
			$TRP_LANGUAGE = $http['lang'];
			error_log( 'new TRP_LANGUAGE: ' . $TRP_LANGUAGE );
			// @codingStandardsIgnoreEnd
		}
		error_log( 'args: ' . print_r( $args, true ) );
		error_log( 'out facetwp_query_args' );
		return $args;
	}


	/**
	 * Index all languages
	 *
	 * @param array $args Arguments.
	 * @return array (Modified) arguments.
	 */
	public function facetwp_indexer_query_args( $args ) {
		$args['lang'] = ''; // query posts in all languages.
		return $args;
	}


	/**
	 * Register dynamic strings
	 */
	public function register_strings() {
		// @codingStandardsIgnoreStart
		// $facets    = FWP()->helper->get_facets();
		// $whitelist = array( 'label', 'label_any', 'placeholder' );

		// if ( ! empty( $facets ) ) {
		// 	foreach ( $facets as $facet ) {
		// 		foreach ( $whitelist as $k ) {
		// 			if ( ! empty( $facet[ $k ] ) ) {
		// 				pll_register_string( 'FacetWP', $facet[ $k ] );
		// 			}
		// 		}
		// 	}
		// }
		// @codingStandardsIgnoreEnd
	}


	/**
	 * Handle string translations
	 *
	 * @param string $string String to translate.
	 * @return string (Translated) string.
	 */
	public function facetwp_i18n( $string ) {
		if ( isset( FWP()->facet->http_params['lang'] ) ) {
			$lang = FWP()->facet->http_params['lang'];

			$translations                                  = array();
			$translations['en_US']['Scegli una categoria'] = 'Pick a category';
			$translations['en_US']['Argenteria antica']    = 'Antique silverware';

			if ( isset( $translations[ $lang ][ $string ] ) ) {
				return $translations[ $lang ][ $string ];
			}
		}

		return $string;
	}


	/**
	 * Grab all taxonomy terms when indexing
	 *
	 * @param array $args Arguments.
	 * @return array (Modified) arguments.
	 */
	public function get_terms_args( $args ) {
		if ( '' !== get_option( 'facetwp_indexing', '' ) ) {
			$args['lang'] = '';
		}

		return $args;
	}
}

new FWP_i18n_TranslatePress();
