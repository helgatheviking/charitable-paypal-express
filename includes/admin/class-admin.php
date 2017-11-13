<?php
/**
 * The class responsible for adding & saving extra settings in the Charitable admin.
 *
 * @package     Charitable PayPal_Express/Classes/Admin
 * @version     1.0.0
 * @author      Eric Daams
 * @copyright   Copyright (c) 2017, Studio 164a
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 */

namespace Charitable_PayPal_Express\Admin;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Charitable_PayPal_Express\Admin\Admin
 *
 * @since       1.0.0
 */
class Admin {

	/**
	 * The single static class instance.
	 *
	 * @since   1.0.0
	 *
	 * @var     Charitable_PayPal_Express\Admin\Admin
	 */
	private static $instance = null;

	/**
	 * Create class object. Private constructor.
	 *
	 * @since   1.0.0
	 *
	 */
	private function __construct() {
		require_once( 'upgrades/charitable-paypal-express-upgrade-hooks.php' );
	}

	/**
	 * Create and return the class object.
	 *
	 * @since   1.0.0
	 */
	public static function get_instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Add custom links to the plugin actions.
	 *
	 * @since   1.0.0
	 *
	 * @param   string[] $links Links to be added to plugin actions row.
	 * @return  string[]
	 */
	public function add_plugin_action_links( $links ) {
		if ( \Charitable_Gateways::get_instance()->is_active_gateway( 'paypal-express' ) ) {

			$links[] = '<a href="' . admin_url( 'admin.php?page=charitable-settings&tab=gateways&group=gateways_paypal-express' ) . '">' . __( 'Settings', 'charitable-paypal-express' ) . '</a>';

		} else {

			$activate_url = esc_url( add_query_arg( array(
				'charitable_action' => 'enable_gateway',
				'gateway_id'        => 'paypal-express',
				'_nonce'            => wp_create_nonce( 'gateway' ),
			), admin_url( 'admin.php?page=charitable-settings&tab=gateways' ) ) );

			$links[] = '<a href="' . $activate_url . '">' . __( 'Activate PayPal Express Gateway', 'charitable-paypal-express' ) . '</a>';

		}
		return $links;
	}

}

