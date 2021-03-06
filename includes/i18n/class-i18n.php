<?php
/**
 * Sets up translations for Charitable PayPal_Express.
 *
 * @package     Charitable/Classes/Charitable_i18n
 * @version     1.0.0
 * @author      Eric Daams
 * @copyright   Copyright (c) 2017, Studio 164a
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 */

namespace Charitable_PayPal_Express\i18n;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) { exit; }

/* Ensure that Charitable_i18n exists */
if ( ! class_exists( '\Charitable_i18n' ) ) :
	return;
endif;

/**
 * Charitable_PayPal_Express\i18n\i18n
 *
 * @since       1.0.0
 */
class i18n extends \Charitable_i18n {

	/**
	 * Plugin textdomain.
	 *
	 * @since   1.0.0
	 *
	 * @var     string
	 */
	protected $textdomain = 'charitable-paypal-express';

	/**
	 * Set up the class.
	 *
	 * @since   1.0.0
	 */
	protected function __construct() {
		$this->languages_directory = apply_filters( 'charitable_paypal_express_languages_directory', 'charitable-paypal-express/languages' );
		$this->locale = apply_filters( 'plugin_locale', get_locale(), $this->textdomain );
		$this->mofile = sprintf( '%1$s-%2$s.mo', $this->textdomain, $this->locale );

		$this->load_textdomain();
	}
}

