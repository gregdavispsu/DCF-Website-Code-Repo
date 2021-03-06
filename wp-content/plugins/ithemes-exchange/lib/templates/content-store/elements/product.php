<?php
/**
 * This is the default template part for a
 * product in the store.
 *
 * @since 0.4.0
 * @version 
 * @link http://ithemes.com/codex/page/Exchange_Template_Updates
 * @package IT_Exchange
 *
 * WARNING: Do not edit this file directly. To use
 * this template in a theme, simply copy over this
 * file's content to the exchange directory located
 * at your templates root.
*/
?>

<?php do_action( 'it_exchange_content_store_before_product_element' ); ?>
<li class="it-exchange-product <?php it_exchange( 'store', 'product-classes' ); ?>">
	<?php do_action( 'it_exchange_content_store_begin_product_element' ); ?>
	<?php it_exchange_get_template_part( 'content-store/loops/product-images' ); ?>
	<div class="it-exchange-product-details">
		<?php it_exchange_get_template_part( 'content-store/loops/product-info' ); ?>
	</div>
	<?php do_action( 'it_exchange_content_store_end_product_element' ); ?>
</li>
<?php do_action( 'it_exchange_content_store_after_product_element' ); ?>
