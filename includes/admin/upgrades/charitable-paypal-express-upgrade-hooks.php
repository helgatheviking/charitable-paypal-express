<?php
/**
 * Charitable PayPal_Express Upgrade Hooks.
 *
 * Action/filter hooks used for Charitable PayPal_Express Upgrades.
 *
 * @package     Charitable PayPal_Express/Functions/Upgrades
 * @version     1.0.0
 * @author      Eric Daams
 * @copyright   Copyright (c) 2017, Studio 164a
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Check if there is an upgrade that needs to happen and if so, display a notice to begin upgrading.
 *
 * @see     Charitable_PayPal_Express\Admin\Upgrades\Upgrade::add_upgrade_notice()
 */
add_action( 'admin_notices', array( \Charitable_PayPal_Express\Admin\Upgrades\Upgrade::get_instance(), 'add_upgrade_notice' ) );
