<?php
/**
 * The registration template for the Super Widget.
 *
 * @since 0.4.0
 * @version 1.1.0
 * @link http://ithemes.com/codex/page/Exchange_Template_Updates
 * @package IT_Exchange
 *
 * WARNING: Do not edit this file directly. To use
 * this template in a theme, simply copy over this
 * file's content to the exchange directory located
 * at your templates root.
*/
?>

<?php do_action( 'it_exchange_super_widget_registration_before_wrap' ); ?>
<div class="login it-exchange-sw-processing it-exchange-sw-processing-registration">
	<?php echo 'Please log in or register to make your gift.</br>'; ?>                 				<!-- 38solutions -->
	<span class="dcf-exchange-login-note"><?php echo 'Note: This is not the same as the Foundation Connection login.'; ?></i></span>           	<!-- 38solutions -->
	<?php do_action( 'it_exchange_super_widget_registration_begin_wrap' ); ?>
	<?php it_exchange_get_template_part( 'messages' ); ?>
	<?php do_action( 'it_exchange_super_widget_registration_before_form' ); ?>
	<?php if ( it_exchange( 'registration', 'is-enabled' ) ) : ?>
	<?php it_exchange( 'registration', 'form-open' ); ?>
		<?php do_action( 'it_exchange_super_widget_registration_begin_form' ); ?>
		<?php it_exchange_get_template_part( 'super-widget-registration/loops/fields' ); ?>
		<?php it_exchange_get_template_part( 'super-widget-registration/loops/actions' ); ?>
		<?php do_action( 'it_exchange_super_widget_registration_end_form' ); ?>
	<?php it_exchange( 'registration', 'form-close' ); ?>
	<?php else : ?>
		<?php it_exchange( 'registration', 'disabled-message' ); ?>
	<?php endif; ?>
	<?php do_action( 'it_exchange_super_widget_registration_after_form' ); ?>
	<?php do_action( 'it_exchange_super_widget_registration_end_wrap' ); ?>
</div>
	<?php do_action( 'it_exchange_super_widget_registration_after_wrap' ); ?>
