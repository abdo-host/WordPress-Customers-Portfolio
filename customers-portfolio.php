<?php

/*
 * @link              #
 * @since             1.0.0
 * @package           Customers Portfolio
 *
 * @wordpress-plugin
 * Plugin Name:       Customers Portfolio
 * Plugin URI:        https://github.com/abdo-host/WordPress-Customers-Portfolio.git
 * Description:       WordPress plugin for building your customer portfolio
 * Version:           1.0.0
 * Author:            Tatwerat Team
 * Author URI:        https://github.com/abdo-host
 * License:           General Public License 2.0
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       customers-portfolio
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	wp_die();
}

// don't load directly
if ( ! defined( 'ABSPATH' ) ) {
	wp_die();
}

if ( ! function_exists( 'add_action' ) ) {
	wp_die();
}

if ( defined( 'WP_DEBUG' ) and WP_DEBUG == true ) {
	error_reporting( E_ALL );
}

define( 'Customers_Portfolio_VERSION', '1.0.0' );
define( 'Customers_Portfolio_URL', plugins_url( basename( plugin_dir_path( __FILE__ ) ), basename( __FILE__ ) ) );
define( 'Customers_Portfolio_FILE', __FILE__ );
define( 'Customers_Portfolio_PATH', plugin_dir_path( __FILE__ ) );
define( 'Customers_Portfolio_DS', DIRECTORY_SEPARATOR );
define( 'Customers_Portfolio_ROOT_PATH', realpath( __DIR__ . '/' ) . Customers_Portfolio_DS );

require( Customers_Portfolio_ROOT_PATH . '/vendor/autoload.php' );

/*
 * Load plugin textdomain.
 */
function customers_portfolio_load_textdomain(): void {
	load_plugin_textdomain( 'customers-portfolio', false, basename( dirname( __FILE__ ) ) . '/languages/' );
}

add_action( 'plugins_loaded', 'customers_portfolio_load_textdomain' );

// use classes
use CustomersPortfolio\Customer_Portfolio_PostType;

if ( ! class_exists( 'Customers_Portfolio_Class' ) ) {

	/*
	 * Main plugin class
	 *
	 * @package AH-Survey
	 * @author tatwerat
	 */

	class Customers_Portfolio_Class {

		public static $debug = false;
		protected $wpdb;
		protected $current_user;
		private static $instance = false;

		public function __construct() {

			global $wpdb;
			$this->wpdb = $wpdb;

			add_action( 'plugins_loaded', [ $this, 'on_plugins_loaded' ], 1 );
			add_action( 'init', [ Customer_Portfolio_PostType::class, 'custom_post_type_customer' ], 0 );
			add_action( 'init', [ Customer_Portfolio_PostType::class, 'custom_taxonomy_categories' ], 0 );
			add_action( 'init', [ Customer_Portfolio_PostType::class, 'custom_taxonomy_countries' ], 0 );
			add_shortcode('customers-portfolio-widget', [$this,'customers_portfolio_shortcode']);

		}

		/**
		 * On plugin loaded
		 */
		public function on_plugins_loaded() {
			// Debug class
			if ( self::$debug ) {
				add_action( 'init', [ $this, 'debug' ] );
			}
		}

		/**
		 * On plugin loaded
		 */
		function customers_portfolio_shortcode(): string {
			return '<div class="customer-portfolio-wrapper"><div id="customer-portfolio-root">' . esc_html__( 'Loading ...', 'customers-portfolio' ) . '</div></div>';
		}

		public static function get_instance() {
			if ( ! self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}


	}

	Customers_Portfolio_Class::get_instance();

}