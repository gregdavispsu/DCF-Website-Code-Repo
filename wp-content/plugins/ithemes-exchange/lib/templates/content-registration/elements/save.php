<?php
/**
 * This is the default template part for the save
 * element in the content-registration template part.
 *
 * @since 1.1.0
 * @version 1.1.0
 * @package IT_Exchange
 *
 * WARNING: Do not edit this file directly. To use
 * this template in a theme, copy over this file
 * to the exchange/content-registration/elements/
 * directory located in your theme.
*/
?>

<?php do_action( 'it_exchange_content_registration_before_save_element' ); ?>
<div class="it-exchange-registration-save it-exchange-profile-save">
	<?php it_exchange( 'registration', 'save' ); ?>
</div>
<?php do_action( 'it_exchange_content_registration_after_save_element' ); ?>