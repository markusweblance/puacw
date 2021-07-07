<?php

/**
 * HTML for Pop-Up
 *
 *
 * @link
 * @since      1.0.0
 *
 * @package    Puacw_Woo_Cart
 * @subpackage Puacw_Woo_Cart/public/partials
 *
 * @var $_product WC_Product
 */


$puacw_opt = get_option( 'puacw-woo-cart' );
?>


<div class="puacw-pop-up__body">
    <div class="puacw-pop-up__header">
        <strong><?php empty( $puacw_opt['title'] ) ? esc_html_e( 'Cart', 'puacw-woo-cart' ) : esc_html_e( $puacw_opt['title'] ) ?></strong>
        <span class="puacw-pop-up__btn-close dashicons dashicons-no"></span>
    </div>
    <div class="puacw-pop-up__content">
		<?php $cart = WC()->cart;
		if ( ! $cart->is_empty() ) : ?>
            <div class="puacw-pop-up__items">
				<?php
				$cart_items            = $cart->get_cart();
				foreach ( $cart_items as $cart_item_key => $cart_item ):
					$_product = $cart_item['data'];
					$product_id        = $cart_item['product_id'];
					$product_permalink = $_product->is_visible() ? get_permalink( $_product->get_id() ) : '';
					?>
                    <div class="puacw-pop-up__item puacw-item">
                        <div class="puacw-item__del">
                            <span data-itemkey="<?php esc_attr_e( $cart_item_key ) ?>" class="dashicons dashicons-no"></span>
                        </div>
                        <div class="puacw-item__img">
							<?php
							$image_url = '';
							if ( $_product->get_image_id() ) {
								$image_url = wp_get_attachment_image_url( $_product->get_image_id(), 'thumbnail' );
							} elseif ( $_product->get_parent_id() ) {
								$parent_product = wc_get_product( $_product->get_parent_id() );
								if ( $parent_product ) {
									$image_url = wp_get_attachment_image_url( $parent_product->get_image_id(), 'thumbnail' );
								}
							}
							if ( ! $product_permalink ) {
								echo '<img src="' . esc_url( $image_url ) . '" alt="' . esc_attr( $_product->get_name() ) . '"/>';
							} else {
								echo sprintf( '<a href="%1s"><img src="%2s" alt="%3s"/></a>',
									esc_url( $product_permalink ),
									esc_url( $image_url ),
									esc_attr( $_product->get_name() )
								);
							}
							?>
                        </div>
                        <div class="puacw-item__title">
							<?php
							if ( ! $product_permalink ) {
								echo esc_html( $_product->get_name() );
							} else {
								echo sprintf( '<a href="%s">%s</a>',
									esc_url( get_permalink( $_product->get_id() ) ),
									esc_html( $_product->get_name() )
								);
							}
							?>
                        </div>
                        <div class="puacw-item__quantity">
                            <span data-itemkey="<?php esc_attr_e( $cart_item_key ) ?>" class="puacw-item__minus"></span>
                            <input type="text" value="<?php esc_attr_e( $cart_item['quantity'] ) ?>">
                            <span data-itemkey="<?php esc_attr_e( $cart_item_key ) ?>" class="puacw-item__plus"></span>
                        </div>
                        <div class="puacw-item__total">
							<?php echo wp_kses_post( $cart->get_product_subtotal( $_product, $cart_item['quantity'] ) ); ?>
                        </div>
                    </div>
				<?php endforeach; ?>
            </div>
		<?php else: ?>
            <div class="puacw-empty"><?php esc_html_e( 'Your cart is currently empty.', 'puacw-woo-cart' ); ?></div>
		<?php endif; ?>
    </div>
    <div class="puacw-pop-up__total">
		<?php echo wp_kses_post( apply_filters( 'puacw_total',
			sprintf( '  <strong class="puacw-pop-up-subtotal">%1s&nbsp;&nbsp;%2s</strong>',
				esc_html__( 'Total:', 'puacw-woo-cart' ),
				$cart->get_cart_subtotal()
			) ) ); ?>
        <a href="<?php echo esc_url( wc_get_checkout_url() ); ?>">
			<?php echo apply_filters( 'puacw_checkout_txt',
				sprintf( '<strong>%s</strong>', esc_html__( 'Сheckout', 'puacw-woo-cart' ) )
			); ?>
        </a>
    </div>
</div>

