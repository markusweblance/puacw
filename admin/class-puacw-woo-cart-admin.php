<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       
 * @since      1.0.0
 *
 * @package    Puacw_Woo_Cart
 * @subpackage Puacw_Woo_Cart/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Puacw_Woo_Cart
 * @subpackage Puacw_Woo_Cart/admin
 * @author     mark <test@test.ru>
 */
class Puacw_Woo_Cart_Admin {

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
	 * @param string $plugin_name The name of this plugin.
	 * @param string $version The version of this plugin.
	 *
	 * @since    1.0.0
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/puacw-woo-cart-admin.css',
			array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( 'wp-color-picker' );
		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/puacw-woo-cart-admin.js',
			array( 'jquery', 'wp-color-picker' ), $this->version, true );

	}

	/**
	 * Register the administration menu for this plugin into the WordPress Dashboard menu.
	 */
	public function add_plugin_admin_menu() {

		add_options_page( 'Pop-Up Woo Cart', 'Pop-Up Woo Cart', 'manage_options', $this->plugin_name, array(
				$this,
				'display_plugin_setup_page'
			)
		);
	}

	/**
	 * Add settings action link to the plugins page.
	 *
	 * @param $links
	 *
	 * @return array
	 */
	public function add_action_links( $links ) {

		$settings_link = array(
			'<a href="' . admin_url( 'options-general.php?page=' . $this->plugin_name ) . '">' . __( 'Settings', $this->plugin_name ) . '</a>',
		);

		return array_merge( $settings_link, $links );

	}

	/**
	 * Render the settings page for this plugin.
	 */
	public function display_plugin_setup_page() {
		?>
        <div class="puacw-admin">
            <div class="puacw-admin__body">
                <h2><?php echo get_admin_page_title() ?></h2>
                <hr/>
                <div class="puacw-admin__content">
                    <div class="puacw-admin__left">
                        <form action="options.php" method="POST">
							<?php
							settings_fields( $this->plugin_name . '_group' );
							do_settings_sections( 'puacw_page' );
							submit_button();
							?>
                        </form>
                    </div>
                    <div class="puacw-admin__right postbox">
                        <p>
                            To display a cart with a counter of products, use the shortcode
                            <code class="language-php">[puacw_basket_counter]</code>in the editor.<br/>
                            <code class="language-php">echo do_shortcode('[puacw_basket_counter] ')</code>
                            in your theme files.
                        </p>
                        <p>
                            <strong>Use filters:</strong><br/>
                            <code class="language-php">puacw_cart_counter_title</code> – to replace text
                            <strong>"Cart"</strong> in front of the counter.<br/><br/>
                            <code class="language-php">
                                add_filter('puacw_cart_counter_title', 'my_func');<br/>
                                function my_func(){<br/>
                                return '&lt;img src="my.jpg"&gt;';<br/>
                                }
                            </code><br/><br/>
                            <code class="language-php">puacw_total</code> – to change the display <strong>Total:
                                amount</strong><br/><br/>
                            <code class="language-php">
                                add_filter('puacw_total', 'my_total');<br/>
                                function my_total(){<br/>
                                $cart = WC()->cart;<br/>
                                return sprintf( ' &lt;strong class="puacw-pop-up-subtotal"&gt;%1s %2s</strong>',<br/>
                                'My text:',<br/>
                                $cart->get_cart_subtotal()<br/>
                                );<br/>
                                }
                            </code><br/><br/>
                            <code class="language-php">puacw_checkout_txt</code> – to change link text
                            <strong>Checkout</strong><br/><br/>
                            <code class="language-php">
                                add_filter('puacw_checkout_txt', 'my_link');<br/>
                                function my_link(){<br/>
                                return sprintf( '&lt;strong&gt;%s&lt;/strong&gt;', 'My link' );<br/>
                                }
                            </code>
                        </p>
                    </div>
                </div>
            </div>
        </div>
	<?php }

	/**
	 * Add settings fields
	 */
	public function admin_settings() {

		register_setting( $this->plugin_name . '_group', $this->plugin_name, array(
			$this,
			' plugin_options_validate'
		) );

		add_settings_section( 'puacw_section_1', __( 'Basic Settings', $this->plugin_name ), '', 'puacw_page' );

		add_settings_field( 'pop_up_title', __( 'Pop-Up title', $this->plugin_name ), array(
			$this,
			'fill_pop_up_title'
		), 'puacw_page', 'puacw_section_1' );

		add_settings_field( 'border_color', __( 'Border Color', $this->plugin_name ), array(
			$this,
			'fill_border_color'
		), 'puacw_page', 'puacw_section_1' );

		add_settings_field( 'bg_color_del', __( 'Background of buttons for deleting product and closing pop-up', $this->plugin_name ), array(
			$this,
			'fill_bg_color_del'
		), 'puacw_page', 'puacw_section_1' );

		add_settings_field( 'bg_color_quantity', __( 'Background of quantity buttons', $this->plugin_name ), array(
			$this,
			'fill_bg_color_quantity'
		), 'puacw_page', 'puacw_section_1' );
		add_settings_field( 'pop_up_bg', __( 'Pop-Up Background', $this->plugin_name ), array(
			$this,
			'fill_pop_up_bg'
		), 'puacw_page', 'puacw_section_1' );
	}

	/**
	 * Validate functionalities
	 */
	public function plugin_options_validate( $input ) {
		return $input; //no validations for now
	}

	/**
	 * Add Pop-Up title
	 */
	public function fill_pop_up_title() {
		$val = get_option( $this->plugin_name );
		$val = $val ? $val['title'] : null;
		?>
        <input type="text" name="<?php echo esc_attr($this->plugin_name) ?>[title]" value="<?php echo esc_attr( $val ) ?>"/>
		<?php
	}

	/**
	 * Add border color
	 */
	public function fill_border_color() {
		$val = get_option( $this->plugin_name );
		$val = $val ? $val['border_color'] : '#e9e9e9';
		?>
        <input type="text" name="<?php echo esc_attr($this->plugin_name) ?>[border_color]" value="<?php echo esc_attr( $val ) ?>"
               class="puacw-border-color-field" data-default-color="#e9e9e9"/>
		<?php
	}

	/**
	 * Add background color for buttons (del product & close Pop-Up)
	 */
	public function fill_bg_color_del() {
		$val = get_option( $this->plugin_name );
		$val = $val ? $val['bg_color_del'] : '#8b0000';
		?>
        <input type="text" name="<?php echo esc_attr($this->plugin_name) ?>[bg_color_del]" value="<?php echo esc_attr( $val ) ?>"
               class="puacw-border-color-field" data-default-color="#8b0000"/>
		<?php
	}

	/**
	 * Add background color for quantity buttons
	 */
	public function fill_bg_color_quantity() {
		$val = get_option( $this->plugin_name );
		$val = $val ? $val['bg_color_quantity'] : '#8fbc8f';
		?>
        <input type="text" name="<?php echo esc_attr($this->plugin_name) ?>[bg_color_quantity]"
               value="<?php echo esc_attr( $val ) ?>"
               class="puacw-border-color-field" data-default-color="#8fbc8f"/>
		<?php
	}

	/**
	 * Add background color for Pop-Up
	 */
	public function fill_pop_up_bg() {
		$val = get_option( $this->plugin_name );
		$val = $val ? $val['pop_up_bg'] : '#ffffff';
		?>
        <input type="text" name="<?php echo esc_attr($this->plugin_name) ?>[pop_up_bg]" value="<?php echo esc_attr( $val ) ?>"
               class="puacw-border-color-field" data-default-color="#ffffff"/>
		<?php
	}
}
