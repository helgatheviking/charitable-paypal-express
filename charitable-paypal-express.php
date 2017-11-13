<?php
/**
 * Plugin Name: 		Charitable - PayPal_Express
 * Plugin URI:			http://github.com/helgatheviking/charitable-paypal-express
 * Description:			Adds the ability for your donors to make their donations through PayPal Express
 * Version: 			1.0.0
 * Author: 				WP Charitable
 * Author URI: 			https://www.wpcharitable.com
 * Requires at least: 	4.8
 * Tested up to: 		4.8.3
 *
 * Text Domain: 		charitable-paypal-express
 * Domain Path: 		/languages/
 *
 * @package 			Charitable PayPal Express
 * @category 			Core
 * @author 				WP Charitable
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Load plugin class, but only if Charitable is found and activated.
 *
 * @return 	false|Charitable_PayPal_Express Whether the class was loaded.
 */
function charitable_paypal_express_load() {
	require_once( 'includes/class-charitable-paypal-express.php' );

	$loaded = false;

	/* Check for Charitable */
	if ( ! class_exists( 'Charitable' ) ) {

		if ( ! class_exists( 'Charitable_Extension_Activation' ) ) {

			require_once 'includes/admin/class-charitable-extension-activation.php';

		}

		$activation = new Charitable_Extension_Activation( plugin_dir_path( __FILE__ ), basename( __FILE__ ) );
		$activation = $activation->run();

	} else {

		$loaded = new Charitable_PayPal_Express( __FILE__ );

	}

	return $loaded;
}

add_action( 'plugins_loaded', 'charitable_paypal_express_load', 1 );
