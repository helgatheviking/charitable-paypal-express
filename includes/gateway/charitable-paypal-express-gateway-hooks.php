<?php
/**
 * Charitable PayPal_Express Gateway Hooks
 *
 * @package     Charitable PayPal_Express/Functions/Gateway
 * @version     1.0.0
 * @author      Eric Daams
 * @copyright   Copyright (c) 2017, Studio 164a
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Register our new gateway.
 *
 * @see     \Charitable_PayPal_Express\Gateway\PayPal_Express::register_gateway()
 */
add_filter( 'charitable_payment_gateways', array( 'Charitable_PayPal_Express\Gateway\PayPal_Express', 'register_gateway' ) );

/**
 * Validate the donation form submission before processing.
 *
 * @see     \Charitable_PayPal_Express\Gateway\PayPal_Express::validate_donation()
 */
add_filter( 'charitable_validate_donation_form_submission_gateway', array( 'Charitable_PayPal_Express\Gateway\PayPal_Express', 'validate_donation' ), 10, 3 );

/**
 * Process the donation.
 *
 * @see     \Charitable_PayPal_Express\Gateway\PayPal_Express::process_donation()
 */
add_filter( 'charitable_process_donation_paypal-express', array( 'Charitable_PayPal_Express\Gateway\PayPal_Express', 'process_donation' ), 10, 3 );

