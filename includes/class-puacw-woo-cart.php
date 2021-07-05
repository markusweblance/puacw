<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       
 * @since      1.0.0
 *
 * @package    Puacw_Woo_Cart
 * @subpackage Puacw_Woo_Cart/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Puacw_Woo_Cart
 * @subpackage Puacw_Woo_Cart/includes
 * @author     mark <test@test.ru>
 */
class Puacw_Woo_Cart {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Puacw_Woo_Cart_Loader $loader Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string $plugin_name The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string $version The current version of the plugin.
	 */
	protected $version;

	/**
	 * Plugin Public Class
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var object $version
	 */
	protected $plugin_public;

	/**
	 * Plugin Admin Class
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var object $version
	 */
	protected $plugin_admin;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'PUACW_WOO_CART_VERSION' ) ) {
			$this->version = PUACW_WOO_CART_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'puacw-woo-cart';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_admin_filters();
		$this->define_public_hooks();
		$this->define_public_filters();
		$this->define_public_shortcodes();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Puacw_Woo_Cart_Loader. Orchestrates the hooks of the plugin.
	 * - Puacw_Woo_Cart_i18n. Defines internationalization functionality.
	 * - Puacw_Woo_Cart_Admin. Defines all hooks for the admin area.
	 * - Puacw_Woo_Cart_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-puacw-woo-cart-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-puacw-woo-cart-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-puacw-woo-cart-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-puacw-woo-cart-public.php';

		$this->loader        = new Puacw_Woo_Cart_Loader();
		$this->plugin_public = new Puacw_Woo_Cart_Public( $this->get_plugin_name(), $this->get_version() );
		$this->plugin_admin  = new Puacw_Woo_Cart_Admin( $this->get_plugin_name(), $this->get_version() );
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Puacw_Woo_Cart_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Puacw_Woo_Cart_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$this->loader->add_action( 'admin_enqueue_scripts', $this->plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $this->plugin_admin, 'enqueue_scripts' );
		$this->loader->add_action( 'admin_menu', $this->plugin_admin, 'add_plugin_admin_menu' );
		$this->loader->add_action( 'admin_init', $this->plugin_admin, 'admin_settings' );


	}

	/**
	 * Register all of the filters related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_filters() {

		// Add Settings link to the plugin
		$plugin_basename = plugin_basename( plugin_dir_path( __DIR__ ) . $this->plugin_name . '.php' );
		$this->loader->add_filter( "plugin_action_links_$plugin_basename", $this->plugin_admin, 'add_action_links' );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$this->loader->add_action( 'wp_enqueue_scripts', $this->plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $this->plugin_public, 'enqueue_scripts' );
		$this->loader->add_action( 'wp_footer', $this->plugin_public, 'add_popup' );
		$this->loader->add_action( 'wp_ajax_puacw_del_item', $this->plugin_public, 'del_cart_item' );
		$this->loader->add_action( 'wp_ajax_nopriv_puacw_del_item', $this->plugin_public, 'del_cart_item' );
		$this->loader->add_action( 'wp_ajax_puacw_item_quantity', $this->plugin_public, 'cart_item_quantity' );
		$this->loader->add_action( 'wp_ajax_nopriv_puacw_item_quantity', $this->plugin_public, 'cart_item_quantity' );

	}

	/**
	 * Add shortcodes
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_shortcodes() {

		$this->loader->add_shortcode( 'puacw_basket_counter', $this->plugin_public, 'basket_counter' );
	}

	/**
	 * Register all of the filters related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_filters() {
		$this->loader->add_filter( 'woocommerce_add_to_cart_fragments', $this->plugin_public, 'refresh_cart' );
		$this->loader->add_filter( 'woocommerce_add_to_cart_fragments', $this->plugin_public, 'modal_add_to_cart_fragments' );
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @return    string    The name of the plugin.
	 * @since     1.0.0
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @return    Puacw_Woo_Cart_Loader    Orchestrates the hooks of the plugin.
	 * @since     1.0.0
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @return    string    The version number of the plugin.
	 * @since     1.0.0
	 */
	public function get_version() {
		return $this->version;
	}

}
