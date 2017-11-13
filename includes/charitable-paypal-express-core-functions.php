<?php
/**
 * Charitable PayPal_Express Core Functions.
 *
 * General core functions.
 *
 * @author      Studio164a
 * @category    Core
 * @package     Charitable PayPal_Express
 * @subpackage  Functions
 * @version     1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * This returns the original Charitable_PayPal_Express object.
 *
 * Use this whenever you want to get an instance of the class. There is no
 * reason to instantiate a new object, though you can do so if you're stubborn :)
 *
 * @since   1.0.0
 *
 * @return  Charitable_PayPal_Express
 */
function charitable_paypal_express() {
	return \Charitable_PayPal_Express::get_instance();
}

/**
 * Displays a template.
 *
 * @since   1.0.0
 *
 * @param   string|array $template_name A single template name or an ordered array of template.
 * @param   array        $args          Optional array of arguments to pass to the view.
 * @return  \Charitable_PayPal_Express\Template\Template
 */
function charitable_paypal_express_template( $template_name, array $args = array() ) {
	if ( empty( $args ) ) {
		$template = new \Charitable_PayPal_Express\Template\Template( $template_name );
	} else {
		$template = new \Charitable_PayPal_Express\Template\Template( $template_name, false );
		$template->set_view_args( $args );
		$template->render();
	}

	return $template;
}
