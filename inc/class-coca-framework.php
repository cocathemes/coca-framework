<?php
/**
 * @package   Coca-Framework
 * @author    Bang Tien Manh
 * @link      http://www.cocathemes.com
 * @copyright 2014 CocaThemes
 */

class Coca_Framework {

	/**
	 * Plugin version, used for cache-busting of style and script file references.
	 *
	 * @since 1.7.0
	 * @type string
	 */
	const VERSION = '1.8.2';

	/**
	 * Initialize the plugin.
	 *
	 * @since 1.7.0
	 */
	public function init() {

		// Needs to run every time in case theme has been changed
		add_action( 'admin_init', array( $this, 'set_theme_option' ) );

	}

	/**
	 * Sets option defaults
	 *
	 * @since 1.7.0
	 */
	function set_theme_option() {

		// Load settings
        $coca_framework_settings = get_option( 'coca-framework' );

        // Updates the unique option id in the database if it has changed
        if ( function_exists( 'coca_framework_option_name' ) ) {
			coca_framework_option_name();
        }
        elseif ( has_action( 'coca_framework_option_name' ) ) {
			do_action( 'coca_framework_option_name' );
        }
        // If the developer hasn't explicitly set an option id, we'll use a default
        else {
            $default_themename = get_option( 'stylesheet' );
            $default_themename = preg_replace( "/\W/", "_", strtolower($default_themename ) );
            $default_themename = 'coca_framework_' . $default_themename;
            if ( isset( $coca_framework_settings['id'] ) ) {
				if ( $coca_framework_settings['id'] == $default_themename ) {
					// All good, using default theme id
				} else {
					$coca_framework_settings['id'] = $default_themename;
					update_option( 'coca-framework', $coca_framework_settings );
				}
            }
            else {
				$coca_framework_settings['id'] = $default_themename;
				update_option( 'coca-framework', $coca_framework_settings );
            }
        }

	}

	/**
	 * Wrapper for coca_framework_options()
	 *
	 * Allows for manipulating or setting options via 'of_options' filter
	 * For example:
	 *
	 * <code>
	 * add_filter( 'cf_options', function( $options ) {
	 *     $options[] = array(
	 *         'name' => 'Input Text Mini',
	 *         'desc' => 'A mini text input field.',
	 *         'id' => 'example_text_mini',
	 *         'std' => 'Default',
	 *         'class' => 'mini',
	 *         'type' => 'text'
	 *     );
	 *
	 *     return $options;
	 * });
	 * </code>
	 *
	 * Also allows for setting options via a return statement in the
	 * options.php file.  For example (in options.php):
	 *
	 * <code>
	 * return array(...);
	 * </code>
	 *
	 * @return array (by reference)
	 */
	static function &_coca_framework_options() {
		static $options = null;

		if ( !$options ) {
	        // Load options from options.php file (if it exists)
	        $location = apply_filters( 'coca_framework_location', array('options.php') );
	        if ( $optionsfile = locate_template( $location ) ) {
	            $maybe_options = require_once $optionsfile;
	            if ( is_array( $maybe_options ) ) {
					$options = $maybe_options;
	            } else if ( function_exists( 'coca_framework_options' ) ) {
					$options = coca_framework_options();
				}
	        }

	        // Allow setting/manipulating options via filters
	        $options = apply_filters( 'cf_options', $options );
		}

		return $options;
	}

}