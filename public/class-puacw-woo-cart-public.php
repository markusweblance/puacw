<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       
 * @since      1.0.0
 *
 * @package    Puacw_Woo_Cart
 * @subpackage Puacw_Woo_Cart/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Puacw_Woo_Cart
 * @subpackage Puacw_Woo_Cart/public
 * @author     mark <test@test.ru>
 */
class Puacw_Woo_Cart_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $plugin_name The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $version The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @param string $plugin_name The name of the plugin.
	 * @param string $version The version of this plugin.
	 *
	 * @since    1.0.0
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {


		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/puacw-woo-cart-public.css', array(), $this->version, 'all' );

		$opt        = get_option( $this->plugin_name );
		$border_color   = empty( $opt['border_color'] ) ? '#e9e9e9' : $opt['border_color'];
		$bg_del_color = empty( $opt['bg_color_del'] ) ? '#e9e9e9' : $opt['bg_color_del'];
		$bg_change_q = empty( $opt['bg_color_quantity'] ) ? '#e9e9e9' : $opt['bg_color_quantity'];
		$bg_pu = empty( $opt['pop_up_bg'] ) ? '#ffffff' : $opt['pop_up_bg'];
		$custom_css = "
		.puacw-pop-up .puacw-pop-up__body{
			border: 1px solid {$border_color};
		}
		.puacw-pop-up .puacw-pop-up__total{
		    border-top: 1px solid {$border_color};
		}
		.puacw-pop-up .puacw-pop-up__header{
		     border-bottom: 1px solid {$border_color};
		}
		.puacw-pop-up .puacw-pop-up__btn-close:hover,
		.puacw-pop-up .puacw-item__del span:hover{
		    background-color: {$bg_del_color};
		}
		.puacw-pop-up .puacw-item__quantity span:hover{
             background-color: {$bg_change_q};
        }
        .puacw-pop-up .puacw-pop-up__body {
            background-color: {$bg_pu};
        }
		";

		wp_add_inline_style( $this->plugin_name, $custom_css );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {


		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/puacw-woo-cart-public.js', array( 'jquery' ), $this->version, true );

		wp_localize_script( $this->plugin_name, 'puacw_ajax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );

	}

	/**
	 *
	 */
	public function basket_counter() {
		include_once 'partials/puacw-display-basket-counter.php';
	}

	/**
	 * @return mixed
	 */
	public function refresh_cart() {
		$cart  = WC()->cart;
		$count = $cart->get_cart_contents_count();
		ob_start(); ?>
        <span class="puacw__counter"><?php esc_html_e( $count ) ?></span>
		<?php
		$fragments['.puacw__counter'] = ob_get_clean();

		return $fragments;
	}


	public function add_popup() {
		echo '<div id="puacw-pop-up""><div class="puacw-pop-up">';
		include_once 'partials/puacw-display-pop-up.php';
		echo '</div></div>';
	}

	/** Ajax обновление корзины в модальном окне
	 *
	 * @param array $fragments
	 *
	 * @return array
	 */
	public function modal_add_to_cart_fragments( $fragments ) {
		ob_start();
		?>
        <div class="puacw-pop-up">
			<?php include_once 'partials/puacw-display-pop-up.php'; ?>
        </div>
		<?php
		$fragments['.puacw-pop-up'] = ob_get_clean();

		return $fragments;
	}

	/**
	 *
	 */
	public function del_cart_item() {
		$cart_item_key = sanitize_text_field( $_POST['cart_item_key'] );
		if ( $cart_item = WC()->cart->get_cart_item( $cart_item_key ) ) {
			WC()->cart->remove_cart_item( $cart_item_key );
		}
		WC_AJAX::get_refreshed_fragments();
	}

	/**
	 *
	 */
	public function cart_item_quantity() {
		$cart_item_key = sanitize_text_field( $_POST['cart_item_key'] );
		$quantity      = sanitize_text_field( $_POST['quantity'] );
		if ( $cart_item = WC()->cart->get_cart_item( $cart_item_key ) && $quantity > 0 ) {
			WC()->cart->set_quantity( $cart_item_key, $quantity, true );
		} else {
			WC()->cart->remove_cart_item( $cart_item_key );
		}
		WC_AJAX::get_refreshed_fragments();
	}
}
