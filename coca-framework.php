<?php
/**
 * Coca-Framework theme framework.
 *
 * @package Coca-Framework
 * @author Bang Tien Manh
 * @link http://cocathemes.com
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

function coca_framework_init() {

	//  If user can't edit theme options, exit
	if ( !current_user_can( 'edit_theme_options' ) )
		return;

	// Load translation files
	load_plugin_textdomain( 'coca-framework', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

	// Loads the required Options Framework classes.
	require plugin_dir_path( __FILE__ ) . 'inc/class-coca-framework.php';
	require plugin_dir_path( __FILE__ ) . 'inc/class-coca-framework-admin.php';
	require plugin_dir_path( __FILE__ ) . 'inc/class-coca-interface.php';
	require plugin_dir_path( __FILE__ ) . 'inc/class-coca-media-uploader.php';
	require plugin_dir_path( __FILE__ ) . 'inc/class-coca-sanitization.php';

	// Instantiate the main plugin class.
	$coca_framework = new Coca_Framework;
	$coca_framework->init();

	// Instantiate the options page.
	$coca_framework_admin = new Coca_Framework_Admin;
	$coca_framework_admin->init();

	// Instantiate the media uploader class
	$coca_framework_media_uploader = new Coca_Framework_Media_Uploader;
	$coca_framework_media_uploader->init();

}
add_action( 'init', 'coca_framework_init', 20 );

/**
 * Helper function to return the theme option value.
 * If no value has been saved, it returns $default.
 * Needed because options are saved as serialized strings.
 *
 * Not in a class to support backwards compatibility in themes.
 */

if ( ! function_exists( 'cf_get_option' ) ) :

function cf_get_option( $name, $default = false ) {
	$config = get_option( 'coca-framework' );

	if ( ! isset( $config['id'] ) ) {
		return $default;
	}

	$options = get_option( $config['id'] );

	if ( isset( $options[$name] ) ) {
		return $options[$name];
	}

	return $default;
}

endif;