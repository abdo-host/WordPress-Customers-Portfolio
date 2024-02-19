<?php

/*
 * @link              #
 * @since             1.1.0
 * @package           Customers Portfolio
 *
 * @wordpress-plugin
 * Plugin Name:       Customers Portfolio
 * Plugin URI:        https://github.com/abdo-host/WordPress-Customers-Portfolio.git
 * Description:       WordPress plugin for building your customer portfolio
 * Version:           1.1.0
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

define( 'Customers_Portfolio_VERSION', '1.1.0' );
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
			add_action( 'init', [ $this, 'customers_portfolio_post_type_rest_support' ], 25 );
			add_action( 'wp_enqueue_scripts', [ $this, 'front_enqueue_scripts' ] );
			add_shortcode( 'customers-portfolio-widget', [ $this, 'customers_portfolio_shortcode' ] );
		}

		/**
		 * On plugin loaded
		 * @return void
		 */
		public function on_plugins_loaded() {
			// Debug class
			if ( self::$debug ) {
				add_action( 'init', [ $this, 'debug' ] );
			}
		}

		/**
		 * Plugin shortcode
		 * @return string
		 */
		function customers_portfolio_shortcode(): string {
			return '<div class="customers-portfolio-wrapper"><div id="customers-portfolio-root">' . esc_html__( 'Loading ...', 'customers-portfolio' ) . '</div></div>';
		}

		/**
		 * Enqueue scripts and styles.
		 *
		 * @return void
		 */
		function front_enqueue_scripts() {
			wp_enqueue_style( 'customers-portfolio-style', plugin_dir_url( __FILE__ ) . 'build/index.css' );
			wp_enqueue_script( 'customers-portfolio-script', plugin_dir_url( __FILE__ ) . 'build/index.js', array( 'wp-element' ), '1.0.0', true );
			wp_localize_script( 'customers-portfolio-script', 'customers_portfolio_scripts_object', [
				'customers_portfolio_nonce' => wp_create_nonce( "customers_portfolio_nonce" ),
				'wp_ajax_url'               => admin_url( 'admin-ajax.php' ),
				'customers_portfolio_title' => esc_html__( 'Customers Portfolio', 'customers-portfolio' ),
				'find_customer'             => esc_html__( 'Find customer ...', 'customers-portfolio' ),
				'all_countries'             => esc_html__( 'All Countries', 'customers-portfolio' ),
				'all_categories'            => esc_html__( 'All Categories', 'customers-portfolio' ),
				'taxonomy_countries'        => $this->get_taxonomy_list( 'customers-portfolio-country' ),
				'taxonomy_categories'       => $this->get_taxonomy_list( 'customers-portfolio-category' ),
				'customers_portfolio_list'  => $this->get_post_type_data( 'customers-portfolio' ),
			] );
		}

		/**
		 * Get taxonomy data.
		 *
		 * @param string $taxonomy
		 *
		 * @return array
		 */
		public function get_taxonomy_list( string $taxonomy ): array {
			$terms_list = [];
			$terms      = get_terms( [
				'taxonomy'   => $taxonomy,
				'hide_empty' => false,
			] );
			if ( ! empty( $terms ) ) {
				foreach ( $terms as $term ) {
					$terms_list[] = [
						'id'   => $term->term_id,
						'name' => $term->name,
						'slug' => $term->slug,
					];
				}
			}

			return $terms_list;
		}

		/**
		 * Get post type data.
		 *
		 * @param string $post_type
		 *
		 * @return array
		 */
		public function get_post_type_data( string $post_type ): array {
			$posts_list = [];
			$query      = new WP_Query( [
				'post_type'      => $post_type,
				'posts_per_page' => - 1,
			] );
			if ( $query->have_posts() ) {
				while ( $query->have_posts() ) {
					$query->the_post();
					$post_ID      = get_the_ID();
					$country      = $this->get_taxonomy_data( $post_ID, 'customers-portfolio-country' );
					$category     = $this->get_taxonomy_data( $post_ID, 'customers-portfolio-category' );
					$posts_list[] = [
						'id'       => $post_ID,
						'name'     => get_the_title(),
						'logo'     => get_the_post_thumbnail_url(),
						'country'  => $country,
						'category' => $category
					];
				}
			}
			wp_reset_postdata();

			return $posts_list;
		}

		/**
		 * Add post type to rest API.
		 */
		function customers_portfolio_post_type_rest_support() {
			global $wp_post_types;
			$wp_post_types['customers-portfolio']->show_in_rest          = true;
			$wp_post_types['customers-portfolio']->rest_base             = 'customers-portfolio';
			$wp_post_types['customers-portfolio']->rest_controller_class = 'WP_REST_Posts_Controller';
		}

		public function get_taxonomy_data( $post_id, $taxonomy ) {
			$terms = get_the_terms( $post_id, $taxonomy );
			if ( $terms and count( $terms ) ) {
				return $terms[0];
			} else {
				return null;
			}
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