<?php
/**
 * Charitable PayPal_Express admin hooks.
 *
 * @package     Charitable PayPal_Express/Functions/Admin
 * @version     1.0.0
 * @author      Eric Daams
 * @copyright   Copyright (c) 2017, Studio 164a
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Add a direct link to the Extensions settings page from the plugin row.
 *
 * @see     Charitable_PayPal_Express\Admin\Admin::add_plugin_action_links()
 */
add_filter( 'plugin_action_links_' . plugin_basename( charitable_paypal_express()->get_path() ), array( \Charitable_PayPal_Express\Admin\Admin::get_instance(), 'add_plugin_action_links' ) );
