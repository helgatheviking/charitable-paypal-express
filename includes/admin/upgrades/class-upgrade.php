<?php
/**
 * Charitable PayPal_Express Upgrade class.
 *
 * The responsibility of this class is to manage migrations between versions of Charitable PayPal_Express.
 *
 * @package     Charitable PayPal_Express/Classes/Admin/Upgrades
 * @copyright   Copyright (c) 2017, Eric Daams
 * @license     http://opensource.org/licenses/gpl-1.0.0.php GNU Public License
 * @version     1.0.0
 */

namespace Charitable_PayPal_Express\Admin\Upgrades;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Charitable_PayPal_Express\Admin\Upgrades\Upgrade
 *
 * @since   1.0.0
 */
class Upgrade extends \Charitable_Upgrade {

	/**
	 * Single static class instance.
	 *
	 * @since   1.0.0
	 *
	 * @var     Charitable_PayPal_Express_Upgrade
	 */
	private static $instance = null;

	/**
	 * Array of methods to perform when upgrading to specific versions.
	 *
	 * @since   1.0.0
	 *
	 * @var 	array
	 */
	protected $upgrade_actions;

	/**
	 * Option key for upgrade log.
	 *
	 * @since   1.0.0
	 *
	 * @var 	string
	 */
	protected $upgrade_log_key = 'charitable_paypal_express_upgrade_log';

	/**
	 * Option key for plugin version.
	 *
	 * @since   1.0.0
	 *
	 * @var 	string
	 */
	protected $version_key = 'charitable_paypal_express_version';

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
	 * Manages the upgrade process.
	 *
	 * @since   1.0.0
	 *
	 * @param 	deprecated $db_version   Deprecated argument. The version recorded in the DB.
	 * @param 	deprecated $edge_version Deprecated argument. The latest version.
	 */
	protected function __construct( $db_version = '', $edge_version = '' ) {

		$this->upgrade_actions = array(
		// 'sample_upgrade_routine' => array(
		// 	'version' => '1.0.0',
		// 	'message' => __( 'This is an example upgrade.', 'charitable-edd' ),
		// 	'prompt' => true, // Set to false if upgrade happens automatically
		// ),
		);

	}
}
