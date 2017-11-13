<?php
/**
 * The main Charitable PayPal_Express class.
 *
 * The responsibility of this class is to load all the plugin's functionality.
 *
 * @package     Charitable PayPal_Express
 * @copyright   Copyright (c) 2017, Eric Daams
 * @license     http://opensource.org/licenses/gpl-1.0.0.php GNU Public License
 * @since       1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) { exit; }

if ( ! class_exists( 'Charitable_PayPal_Express' ) ) :

	/**
	 * Charitable_PayPal_Express
	 *
	 * @since   1.0.0
	 */
	class Charitable_PayPal_Express {

		/**
		 * Plugin version.
		 *
		 * @since   1.0.0
		 *
		 * @var string
		 */
		const VERSION = '1.0.0';

		/**
		 * Database version. A date in the format: YYYYMMDD
		 *
		 * @since   1.0.0
		 *
		 * @var string
		 */
		const DB_VERSION = '20171112';

		/**
		 * The product name.
		 *
		 * @since   1.0.0
		 *
		 * @var string
		 */
		const NAME = 'Charitable PayPal_Express';

		/**
		 * The product author.
		 *
		 * @since   1.0.0
		 *
		 * @var string
		 */
		const AUTHOR = 'Studio 164a';

		/**
		 * Single static instance of this class.
		 *
		 * @since   1.0.0
		 *
	     * @var 	Charitable_PayPal_Express
	     */
		private static $instance = null;

		/**
		 * The root file of the plugin.
		 *
		 * @since   1.0.0
		 *
		 * @var     string
		 */
		private $plugin_file;

		/**
		 * The root directory of the plugin.
		 *
		 * @since   1.0.0
		 *
		 * @var     string
		 */
		private $directory_path;

		/**
		 * The root directory of the plugin as a URL.
		 *
		 * @since   1.0.0
		 *
		 * @var     string
		 */
		private $directory_url;

		/**
		 * Create class instance.
		 *
		 * @since   1.0.0
		 *
		 * @param 	string $plugin_file Absolute path to the main plugin file.
		 */
		public function __construct( $plugin_file ) {
			$this->plugin_file      = $plugin_file;
			$this->directory_path   = plugin_dir_path( $plugin_file );
			$this->directory_url    = plugin_dir_url( $plugin_file );

			add_action( 'charitable_start', array( $this, 'start' ), 6 );
		}

		/**
		 * Returns the original instance of this class.
		 *
		 * @since   1.0.0
		 *
		 * @return  Charitable
		 */
		public static function get_instance() {
			return self::$instance;
		}

		/**
		 * Run the startup sequence on the charitable_start hook.
		 *
		 * This is only ever executed once.
		 *
		 * @since   1.0.0
		 *
		 * @return  void
		 */
		public function start() {
			// If we've already started (i.e. run this function once before), do not pass go.
			if ( $this->started() ) {
				return;
			}

			// Set static instance.
			self::$instance = $this;

			$this->load_dependencies();

			$this->maybe_start_admin();

			$this->setup_licensing();

			$this->setup_i18n();

			// Hook in here to do something when the plugin is first loaded.
			do_action( 'charitable_paypal_express_start', $this );
		}

		/**
		 * Include necessary files.
		 *
		 * @since   1.0.0
		 *
		 * @return  void
		 */
		private function load_dependencies() {
			require_once( $this->get_path( 'includes' ) . 'autoloader/autoloader.php' );

			require_once( $this->get_path( 'includes' ) . 'charitable-paypal-express-core-functions.php' );
			require_once( $this->get_path( 'includes' ) . 'gateway/charitable-paypal-express-gateway-hooks.php' );

			/* Recurring Donations */
			if ( class_exists( 'Charitable_Recurring' ) ) {
				require_once( $this->get_path( 'includes' ) . 'recurring/charitable-paypal-express-recurring-hooks.php' );
			}

		}

		/**
		 * Load the admin-only functionality.
		 *
		 * @since   1.0.0
		 *
		 * @return  void
		 */
		private function maybe_start_admin() {
			if ( ! is_admin() ) {
				return;
			}

			require_once( $this->get_path( 'includes' ) . 'admin/charitable-paypal-express-admin-hooks.php' );
		}


		/**
		 * Set up licensing for the extension.
		 *
		 * @since   1.0.0
		 *
		 * @return  void
		 */
		private function setup_licensing() {
			charitable_get_helper( 'licenses' )->register_licensed_product(
				Charitable_PayPal_Express::NAME,
				Charitable_PayPal_Express::AUTHOR,
				Charitable_PayPal_Express::VERSION,
				$this->plugin_file
			);
		}

		/**
		 * Set up the internationalisation for the plugin.
		 *
		 * @since   1.0.0
		 *
		 * @return  void
		 */
		private function setup_i18n() {
			if ( class_exists( 'Charitable_i18n' ) ) {
				\Charitable_PayPal_Express\i18n\i18n::get_instance();
			}
		}

		/**
		 * Returns whether we are currently in the start phase of the plugin.
		 *
		 * @since   1.0.0
		 *
		 * @return  bool
		 */
		public function is_start() {
			return current_filter() == 'charitable_paypal_express_start';
		}

		/**
		 * Returns whether the plugin has already started.
		 *
		 * @since   1.0.0
		 *
		 * @return  bool
		 */
		public function started() {
			return did_action( 'charitable_paypal_express_start' ) || current_filter() == 'charitable_paypal_express_start';
		}

		/**
		 * Returns the plugin's version number.
		 *
		 * @since   1.0.0
		 *
		 * @return  string
		 */
		public function get_version() {
			return self::VERSION;
		}

		/**
		 * Returns plugin paths.
		 *
		 * @since   1.0.0
		 *
		 * @param   string  $type          If empty, returns the path to the plugin.
		 * @param   boolean $absolute_path If true, returns the file system path. If false, returns it as a URL.
		 * @return  string
		 */
		public function get_path( $type = '', $absolute_path = true ) {
			$base = $absolute_path ? $this->directory_path : $this->directory_url;

			switch ( $type ) {
				case 'includes' :
					$path = $base . 'includes/';
					break;

				case 'templates' :
					$path = $base . 'templates/';
					break;

				case 'directory' :
					$path = $base;
					break;

				default :
					$path = $this->plugin_file;
			}

			return $path;
		}

		/**
		 * Throw error on object clone.
		 *
		 * This class is specifically designed to be instantiated once. You can retrieve the instance using charitable()
		 *
		 * @since   1.0.0
		 *
		 * @return  void
		 */
		public function __clone() {
			charitable_get_deprecated()->doing_it_wrong(
				__FUNCTION__,
				__( 'Cheatin&#8217; huh?', 'charitable-paypal-express' ),
				'1.0.0'
			);
		}

		/**
		 * Disable unserializing of the class.
		 *
		 * @since   1.0.0
		 *
		 * @return  void
		 */
		public function __wakeup() {
			charitable_get_deprecated()->doing_it_wrong(
				__FUNCTION__,
				__( 'Cheatin&#8217; huh?', 'charitable-paypal-express' ),
				'1.0.0'
			);
		}
	}

endif;
