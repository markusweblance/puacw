<?php

/**
 * HTML for counter and shopping cart
 *
 * @link
 * @since      1.0.0
 *
 * @package    Puacw_Woo_Cart
 * @subpackage Puacw_Woo_Cart/public/partials
 */
?>

<div class="puacw-basket">
    <a id="puacw-target" href=""><?php
		echo apply_filters( 'puacw_cart_counter_title', esc_html__( 'Cart', 'puacw-woo-cart' ) );
		?>
    </a>
    <span class="puacw__counter">
        <?php
        $cart  = WC()->cart;
        $count = $cart->get_cart_contents_count();
        esc_html_e( $count );
        ?>
    </span>
</div>