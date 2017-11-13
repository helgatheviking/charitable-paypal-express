<?php
/**
 * Charitable Recurring Stripe standard Hooks.
 *
 * Action/filter hooks used for adding support for recurring donations to Stripe gateway.
 *
 * @package     Charitable PayPal Express/Functions/Recurring Donations
 * @version     1.0.0
 * @author      Eric Daams
 * @copyright   Copyright (c) 2017, Eric Daams
 * @license     http://opensource.org/licenses/gpl-3.0.php GNU Public License
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Maybe process a recurring donation.
 *
 * @see     \Charitable_PayPal_Express\Recurring\Recurring_Support::maybe_process_recurring_donation()
 */
add_filter( 'charitable_process_donation_paypal-express', array( \Charitable_PayPal_Express\Recurring\Recurring_Support::get_instance(), 'maybe_process_recurring_donation' ), 2, 3 );

/**
 * Create a plan in the gateway.
 *
 * @see     \Charitable_PayPal_Express\Recurring\Recurring_Support::create_recurring_donation_plan()
 */
add_filter( 'charitable_recurring_create_gateway_plan_paypal-express', array( \Charitable_PayPal_Express\Recurring\Recurring_Support::get_instance(), 'create_recurring_donation_plan' ), 10, 4 );

/**
 * Handle Stripe webhooks.
 *
 * @see     \Charitable_PayPal_Express\Recurring\Recurring_Support::process_webhooks()
 */
add_action( 'charitable_paypal-express_ipn_event', array( \Charitable_PayPal_Express\Recurring\Recurring_Support::get_instance(), 'process_webhooks' ), 10, 2 );
