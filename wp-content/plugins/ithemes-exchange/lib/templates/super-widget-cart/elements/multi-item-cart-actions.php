<?php
/**
 * This is the default template for the
 * super-widget-cart multi-item-cart-actions element.
 *
 * @since 1.1.0
 * @version 1.1.0
 * @package IT_Exchange
 *
 * WARNING: Do not edit this file directly. To use
 * this template in a theme, copy over this file
 * to the exchange/super-widget-cart/elements directory
 * located in your theme.
*/
?>

<?php do_action( 'it_exchange_super_widget_cart_before_multi_item_actions_element' ); ?>
<?php do_action( 'it_exchange_super_widget_cartbefore_multi_item_actions_wrap' ); ?>
<div class="cart-actions-wrapper two-actions">
	<?php do_action( 'it_exchange_super_widget_cart_begin_multi_item_actions_wrap' ); ?>
	<div class="cart-action view-cart">
		<?php it_exchange( 'cart', 'view-cart', array( 'class' => 'sw-cart-focus-cart', 'focus' => 'cart' ) ); ?>
	</div>
	<div class="cart-action checkout">
		<?php it_exchange( 'cart', 'checkout', array( 'class' => 'sw-cart-focus-checkout', 'focus' => 'checkout' ) ); ?>
	</div>
	<?php do_action( 'it_exchange_super_widget_cart_end_multi_item_actions_wrap' ); ?>
</div>
<?php do_action( 'it_exchange_super_widget_cart_after_multi_item_actions_wrap' ); ?>
<?php do_action( 'it_exchange_super_widget_cart_after_multi_item_actions_element' ); ?>