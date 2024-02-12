<?php

namespace CustomersPortfolio;

class Customer_Portfolio_PostType {

	private static $instance = false;

	public function __construct() {
		add_filter( 'manage_customers-portfolio_posts_columns', [ $this, 'custom_columns_head' ] );
		add_action( 'manage_customers-portfolio_posts_custom_column', [ $this, 'custom_columns_content' ], 10, 2 );
	}

	public static function custom_post_type_customer() {
		$labels = [
			'name'               => esc_html_x( 'Customers', 'Post Type General Name', 'customers-portfolio' ),
			'singular_name'      => esc_html_x( 'Customer', 'Post Type Singular Nam', 'customers-portfolio' ),
			'menu_name'          => esc_html__( 'Customers', 'customers-portfolio' ),
			'all_items'          => esc_html__( 'All Customers', 'customers-portfolio' ),
			'add_new_item'       => esc_html__( 'Add New Customer', 'customers-portfolio' ),
			'add_new'            => esc_html__( 'Add New', 'customers-portfolio' ),
			'edit_item'          => esc_html__( 'Edit Customer', 'customers-portfolio' ),
			'update_item'        => esc_html__( 'Update Customer', 'customers-portfolio' ),
			'view_item'          => esc_html__( 'View Customer', 'customers-portfolio' ),
			'search_items'       => esc_html__( 'Search Customers', 'customers-portfolio' ),
			'not_found'          => esc_html__( 'Not Found', 'customers-portfolio' ),
			'not_found_in_trash' => esc_html__( 'Not found in Trash', 'customers-portfolio' ),
		];

		$args = [
			'label'               => esc_html__( 'Customer', 'customers-portfolio' ),
			'description'         => esc_html__( 'Customers custom post type', 'customers-portfolio' ),
			'labels'              => $labels,
			'supports'            => [ 'title', 'editor', 'thumbnail' ],
			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'menu_position'       => 5,
			'show_in_admin_bar'   => true,
			'show_in_nav_menus'   => false,
			'can_export'          => true,
			'has_archive'         => false,
			'exclude_from_search' => true,
			'publicly_queryable'  => true,
			'capability_type'     => 'post',
		];

		register_post_type( 'customers-portfolio', $args );
	}

	public static function custom_taxonomy_categories() {
		$labels = [
			'name'                       => esc_html_x( 'Category', 'Taxonomy General Name', 'customers-portfolio' ),
			'singular_name'              => esc_html_x( 'Category', 'Taxonomy Singular Name', 'customers-portfolio' ),
			'menu_name'                  => esc_html__( 'Category', 'customers-portfolio' ),
			'all_items'                  => esc_html__( 'All Categories', 'customers-portfolio' ),
			'parent_item'                => esc_html__( 'Parent Category', 'customers-portfolio' ),
			'parent_item_colon'          => esc_html__( 'Parent Category:', 'customers-portfolio' ),
			'new_item_name'              => esc_html__( 'New Category Name', 'customers-portfolio' ),
			'add_new_item'               => esc_html__( 'Add New Category', 'customers-portfolio' ),
			'edit_item'                  => esc_html__( 'Edit Category', 'customers-portfolio' ),
			'update_item'                => esc_html__( 'Update Category', 'customers-portfolio' ),
			'view_item'                  => esc_html__( 'View Category', 'customers-portfolio' ),
			'separate_items_with_commas' => esc_html__( 'Separate categories with commas', 'customers-portfolio' ),
			'add_or_remove_items'        => esc_html__( 'Add or remove categories', 'customers-portfolio' ),
			'choose_from_most_used'      => esc_html__( 'Choose from the most used categories', 'customers-portfolio' ),
			'popular_items'              => esc_html__( 'Popular Category', 'customers-portfolio' ),
			'search_items'               => esc_html__( 'Search Category', 'customers-portfolio' ),
			'not_found'                  => esc_html__( 'Not Found', 'customers-portfolio' ),
			'no_terms'                   => esc_html__( 'No categories', 'customers-portfolio' ),
			'items_list'                 => esc_html__( 'Category list', 'customers-portfolio' ),
			'items_list_navigation'      => esc_html__( 'Category list navigation', 'customers-portfolio' ),
		];

		$args = [
			'labels'            => $labels,
			'hierarchical'      => true,
			'public'            => true,
			'show_ui'           => true,
			'show_admin_column' => true,
			'show_in_nav_menus' => false,
			'show_tagcloud'     => false,
		];

		register_taxonomy( 'customers-portfolio-category', [ 'customers-portfolio' ], $args );
	}

	public static function custom_taxonomy_countries() {
		$labels = [
			'name'                       => esc_html_x( 'Country', 'Taxonomy General Name', 'customers-portfolio' ),
			'singular_name'              => esc_html_x( 'Country', 'Taxonomy Singular Name', 'customers-portfolio' ),
			'menu_name'                  => esc_html__( 'Country', 'customers-portfolio' ),
			'all_items'                  => esc_html__( 'All Countries', 'customers-portfolio' ),
			'parent_item'                => esc_html__( 'Parent Country', 'customers-portfolio' ),
			'parent_item_colon'          => esc_html__( 'Parent Country:', 'customers-portfolio' ),
			'new_item_name'              => esc_html__( 'New Country Name', 'customers-portfolio' ),
			'add_new_item'               => esc_html__( 'Add New Country', 'customers-portfolio' ),
			'edit_item'                  => esc_html__( 'Edit Country', 'customers-portfolio' ),
			'update_item'                => esc_html__( 'Update Country', 'customers-portfolio' ),
			'view_item'                  => esc_html__( 'View Country', 'customers-portfolio' ),
			'separate_items_with_commas' => esc_html__( 'Separate countries with commas', 'customers-portfolio' ),
			'add_or_remove_items'        => esc_html__( 'Add or remove countries', 'customers-portfolio' ),
			'choose_from_most_used'      => esc_html__( 'Choose from the most used countries', 'customers-portfolio' ),
			'popular_items'              => esc_html__( 'Popular Country', 'customers-portfolio' ),
			'search_items'               => esc_html__( 'Search Country', 'customers-portfolio' ),
			'not_found'                  => esc_html__( 'Not Found', 'customers-portfolio' ),
			'no_terms'                   => esc_html__( 'No countries', 'customers-portfolio' ),
			'items_list'                 => esc_html__( 'Country list', 'customers-portfolio' ),
			'items_list_navigation'      => esc_html__( 'Country list navigation', 'customers-portfolio' ),
		];

		$args = [
			'labels'            => $labels,
			'hierarchical'      => true,
			'public'            => true,
			'show_ui'           => true,
			'show_admin_column' => true,
			'show_in_nav_menus' => true,
			'show_tagcloud'     => false,
		];

		register_taxonomy( 'customers-portfolio-country', [ 'customers-portfolio' ], $args );
	}

	public function custom_columns_head( $columns ) {
		$columns['featured_image'] = 'Featured Image';

		return $columns;
	}

	public function custom_columns_content( $column_name, $post_ID ) {
		if ( $column_name == 'featured_image' ) {
			$featured_image = get_the_post_thumbnail( $post_ID, 'medium', [ 'style' => 'height:50px;width:auto;' ] );
			echo $featured_image ? $featured_image : '';
		}
	}

	public static function get_instance() {
		if ( ! self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

}

Customer_Portfolio_PostType::get_instance();