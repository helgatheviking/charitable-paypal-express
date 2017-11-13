<?php
/**
 * Charitable PayPal_Express template
 *
 * @version     1.0.0
 * @package     Charitable PayPal_Express/Classes/Charitable_PayPal_Express_Template
 * @author      Eric Daams
 * @copyright   Copyright (c) 2017, Studio 164a
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 */

namespace Charitable_PayPal_Express\Public;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Charitable_PayPal_Express_Template
 *
 * @since       1.0.0
 */
class Template extends \Charitable_Template {

	/**
	 * Set theme template path.
	 *
	 * @since   1.0.0
	 *
	 * @return  string
	 */
	public function get_theme_template_path() {
		return trailingslashit( apply_filters( 'charitable_paypal_express_theme_template_path', 'charitable/charitable-paypal-express' ) );
	}

	/**
	 * Return the base template path.
	 *
	 * @since   1.0.0
	 *
	 * @return  string
	 */
	public function get_base_template_path() {
		return charitable_paypal_express()->get_path( 'templates' );
	}
}

