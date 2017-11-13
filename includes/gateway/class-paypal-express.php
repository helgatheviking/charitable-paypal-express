<?php
/**
 * PayPal_Express Gateway class
 *
 * @version     1.0.0
 * @package     Charitable PayPal_Express/Classes/Gateway
 * @author      Eric Daams
 * @copyright   Copyright (c) 2017, Studio 164a
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 */

namespace Charitable_PayPal_Express\Gateway;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * PayPal_Express Gateway
 *
 * @since       1.0.0
 */
class PayPal_Express extends \Charitable_Gateway {

	/**
	 * The gateway ID.
	 *
	 * @since   1.0.0
	 *
	 * @var     string
	 */
	const ID = 'paypal-express';

	/**
	 * Flags whether the gateway requires credit card fields added to the donation form.
	 *
	 * @since   1.0.0
	 *
	 * @var     boolean
	 */
	protected $credit_card_form;

	/**
	 * Instantiate the gateway class, defining its key values.
	 *
	 * @since   1.0.0
	 */
	public function __construct() {
		$this->name = apply_filters( 'charitable_gateway_paypal_express_name', __( 'PayPal Express', 'charitable-paypal-express' ) );

		$this->defaults = array(
			'label' => __( 'PayPal Express', 'charitable-paypal-express' ),
		);

		$this->supports = array(
			'1.3.0',
			'recurring',
			'cancel_recurring',
			'refund'
		);

		/**
		 * Needed for backwards compatibility with Charitable < 1.3
		 */
		$this->credit_card_form = true;
	}

	/**
	 * Returns the current gateway's ID.
	 *
	 * @since   1.0.0
	 *
	 * @return  string
	 */
	public static function get_gateway_id() {
		return self::ID;
	}

	/**
	 * Register gateway settings.
	 *
	 * @since   1.0.0
	 *
	 * @param   array[] $settings Default array of settings for the gateway.
	 * @return  array[]
	 */
	public function gateway_settings( $settings ) {

		$settings['api'] = array(
			'type' 	   => 'notice',
			'title'	   => __( 'API Credentials', 'charitable-paypal-express' ),
			'priority' => 10,
			'notice_type'  => 'warning',
			'content' 	   => __( 'Enter your PayPal API credentials to process refunds via PayPal. Learn how to find your <a href="https://developer.paypal.com/webapps/developer/docs/classic/api/apiCredentials/#creating-an-api-signature">PayPal API Credentials</a>.', 'charitable-paypal-express' ),
		);

		$settings['api_username'] = array(
			'type' 	   => 'text',
			'title'	   => __( 'API Username', 'charitable-paypal-express' ),
			'priority' => 20,
			'default'  => '',
			'help' 	   => __( 'Get your API credentials from PayPal.', 'charitable-paypal-express' ),
		);

		$settings['api_password'] = array(
			'type' 	   => 'text',
			'title'	   => __( 'API Password', 'charitable-paypal-express' ),
			'priority' => 30,
			'default'  => '',
			'help' 	   => __( 'Get your API credentials from PayPal.', 'charitable-paypal-express' ),
		);

		$settings['api_signature'] = array(
			'type' 	   => 'text',
			'title'	   => __( 'API Signature', 'charitable-paypal-express' ),
			'priority' => 40,
			'default'  => '',
			'help' 	   => __( 'Get your API credentials from PayPal.', 'charitable-paypal-express' ),
		);

		$settings['transaction_mode'] = array(
			'type'      => 'radio',
			'title'     => __( 'PayPal Transaction Type', 'charitable-paypal-express' ),
			'priority'  => 50,
			'options'   => array(
				'donations' => __( 'Donations', 'charitable-paypal-express' ),
				'standard'  => __( 'Standard Transaction', 'charitable-paypal-express' ),
			),
			'default'   => 'donations',
			'help'      => sprintf( '%s<br /><a href="%s" target="_blank">%s</a>',
				__( 'PayPal offers discounted fees to registered non-profit organizations. You must create a PayPal Business account to apply.', 'charitable-paypal-express' ),
				'https://cms.paypal.com/us/cgi-bin/?cmd=_render-content&content_ID=merchant%2Fdonations',
				__( 'Find out more.', 'charitable-paypal-express' )
			),
		);

		$settings['disable_ipn_verification'] = array(
			'type' 	   => 'checkbox',
			'title'	   => __( 'Disable IPN Verification', 'charitable-paypal-express' ),
			'priority' => 60,
			'default'  => 0,
			'help' 	   => __( 'If you are having problems with donations not getting marked as Paid, disabling IPN verification might fix the problem. However, it is important to be aware that this is a <strong>less secure</strong> method for verifying donations.', 'charitable-paypal-express' ),
		);

		return $settings;
	}

	/**
	 * Register the payment gateway class.
	 *
	 * @since   1.0.0
	 *
	 * @param   string[] $gateways The list of registered gateways.
	 * @return  string[]
	 */
	public static function register_gateway( $gateways ) {
		$gateways['paypal-express'] = '\Charitable_PayPal_Express\Gateway\PayPal_Express';
		return $gateways;
	}

	/**
	 * Return the keys to use.
	 *
	 * This will return the test keys if test mode is enabled. Otherwise, returns
	 * the production keys.
	 *
	 * @since   1.0.0
	 *
	 * @return  string[]
	 */
	public function get_keys() {
		$keys = array();

		if ( charitable_get_option( 'test_mode' ) ) {
			$keys['secret_key'] = trim( $this->get_value( 'test_secret_key' ) );
			$keys['public_key'] = trim( $this->get_value( 'test_public_key' ) );
		} else {
			$keys['secret_key'] = trim( $this->get_value( 'live_secret_key' ) );
			$keys['public_key'] = trim( $this->get_value( 'live_public_key' ) );
		}

		return $keys;
	}

	/**
	 * Return the submitted value for a gateway field.
	 *
	 * @since   1.0.0
	 *
	 * @param   string  $key    The key of the field to get.
	 * @param   mixed[] $values Set of values to find the values in.
	 * @return  string|false
	 */
	public function get_gateway_value( $key, $values ) {
		return isset( $values['gateways']['paypal-express'][ $key ] ) ? $values['gateways']['paypal-express'][ $key ] : false;
	}

	/**
	 * Return the submitted value for a gateway field.
	 *
	 * @since   1.0.0
	 *
	 * @param   string 						  $key       The key of the field to get.
	 * @param   Charitable_Donation_Processor $processor Donation processor object.
	 * @return  string|false
	 */
	public function get_gateway_value_from_processor( $key, Charitable_Donation_Processor $processor ) {
		return $this->get_gateway_value( $key, $processor->get_donation_data() );
	}

	/**
	 * Validate the submitted credit card details.
	 *
	 * @since   1.0.0
	 *
	 * @param   boolean $valid   Whether the donation is valid.
	 * @param   string  $gateway The gateway for the donation.
	 * @param   mixed[] $values  Submitted donation values.
	 * @return  boolean
	 */
	public static function validate_donation( $valid, $gateway, $values ) {
		if ( 'paypal-express' != $gateway ) {
			return $valid;
		}

		if ( ! isset( $values['gateways']['paypal-express'] ) ) {
			return false;
		}

		/**
		 * Check that the donation is valid.
		 *
		 * @todo
		 */

		return $valid;
	}

	/**
	 * Process the donation with the gateway.
	 *
	 * @since   1.0.0
	 *
	 * @param   mixed                         $return      Response to be returned.
	 * @param   int                           $donation_id The donation ID.
	 * @param   Charitable_Donation_Processor $processor   Donation processor object.
	 * @return  boolean|array
	 */
	public static function process_donation( $return, $donation_id, $processor ) {

		$gateway     = new self();

		$donation    = charitable_get_donation( $donation_id );
		$donor       = $donation->get_donor();
		$values      = $processor->get_donation_data();

		// API keys
		// $keys        = $gateway->get_keys();

		// Donation fields
		// $donation_key = $donation->get_donation_key();
		// $item_name    = sprintf( __( 'Donation %d', 'charitable-payu-money' ), $donation->ID );;
		// $description  = $donation->get_campaigns_donated_to();
		// $amount 	  = $donation->get_total_donation_amount( true );

		// Donor fields
		// $first_name   = $donor->get_donor_meta( 'first_name' );
		// $last_name    = $donor->get_donor_meta( 'last_name' );
		// $address      = $donor->get_donor_meta( 'address' );
		// $address_2    = $donor->get_donor_meta( 'address_2' );
		// $email 		  = $donor->get_donor_meta( 'email' );
		// $city         = $donor->get_donor_meta( 'city' );
		// $state        = $donor->get_donor_meta( 'state' );
		// $country      = $donor->get_donor_meta( 'country' );
		// $postcode     = $donor->get_donor_meta( 'postcode' );
		// $phone        = $donor->get_donor_meta( 'phone' );

		// URL fields
		// $return_url = charitable_get_permalink( 'donation_receipt_page', array( 'donation_id' => $donation->ID ) );
		// $cancel_url = charitable_get_permalink( 'donation_cancel_page', array( 'donation_id' => $donation->ID ) );
		// $notify_url = function_exists( 'charitable_get_ipn_url' )
		// 	? charitable_get_ipn_url( Charitable_Gateway_Sparrow::ID )
		// 	: Charitable_Donation_Processor::get_instance()->get_ipn_url( Charitable_Gateway_Sparrow::ID );
		
		// Credit card fields
		// $cc_expiration = $this->get_gateway_value( 'cc_expiration', $values );
		// $cc_number     = $this->get_gateway_value( 'cc_number', $values );
		// $cc_year       = $cc_expiration['year'];
		// $cc_month      = $cc_expiration['month'];
		// $cc_cvc		   = $this->get_gateway_value( 'cc_cvc', $values );

		/**
		 * Create donation charge through gateway.
		 *
		 * @todo
		 *
		 * You should return one of three values.
		 *
		 * 1. If the donation fails to be processed and the user should be
		 *    returned to the donation page, return false.
		 * 2. If the donation succeeds and the user should be directed to
		 *    the donation receipt, return true.
		 * 3. If the user should be redirected elsewhere (for example,
		 *    a gateway-hosted payment page), you should return an array
		 *    like this:

			array(
				'redirect' => $redirect_url,
				'safe' => false // Set to false if you are redirecting away from the site.
			);
		 *
		 */

		return true;
	}

	/**
	 * Process an IPN request.
	 *
	 * @since   1.0.0
	 *
	 * @return  void
	 */
	public static function process_ipn() {
		/**
		 * Process the IPN.
		 *
		 * @todo
		 */
	}

	/**
	 * Redirect back to the donation form, sending the donation ID back.
	 *
	 * @since   1.0.0
	 *
	 * @param   int $donation_id The donation ID.
	 * @return  void
	 */
	private function redirect_to_donation_form( $donation_id ) {
		charitable_get_session()->add_notices();
		$redirect_url = esc_url( add_query_arg( array( 'donation_id' => $donation_id ), wp_get_referer() ) );
		wp_safe_redirect( $redirect_url );
		die();
	}
}
