<?php
/**
 * iThemes Exchange Customer Pricing Add-on
 * @package IT_Exchange_Addon_Customer_Pricing
 * @since 1.0.0
*/

/**
 * Shows the nag when needed.
 *
 * @since 1.0.0
 *
 * @return void
*/
function it_exchange_customer_pricing_addon_show_version_nag() {
	if ( version_compare( $GLOBALS['it_exchange']['version'], '1.5.0', '<' ) ) {
		?>
		<div id="it-exchange-add-on-min-version-nag" class="it-exchange-nag">
			<?php printf( __( 'The Customer Pricing add-on requires iThemes Exchange version 1.5.0 or greater. %sPlease upgrade Exchange%s.', 'it-l10n-exchange-addon-customer-pricing' ), '<a href="' . admin_url( 'update-core.php' ) . '">', '</a>' ); ?>
		</div>
		<script type="text/javascript">
			jQuery( document ).ready( function() {
				if ( jQuery( '.wrap > h2' ).length == '1' ) {
					jQuery("#it-exchange-add-on-min-version-nag").insertAfter('.wrap > h2').addClass( 'after-h2' );
				}
			});
		</script>
		<?php
	}
}
add_action( 'admin_notices', 'it_exchange_customer_pricing_addon_show_version_nag' );

/**
 * Adds actions to the plugins page for the iThemes Exchange Customer Pricing plugin
 *
 * @since 1.0.0
 *
 * @param array $meta Existing meta
 * @param string $plugin_file the wp plugin slug (path)
 * @param array $plugin_data the data WP harvested from the plugin header
 * @param string $context 
 * @return array
*/
function it_exchange_customer_pricing_plugin_row_actions( $actions, $plugin_file, $plugin_data, $context ) {
	
	$actions['setup_addon'] = '<a href="' . get_admin_url( NULL, 'admin.php?page=it-exchange-addons&add-on-settings=customer-pricing' ) . '">' . __( 'Setup Add-on', 'it-l10n-exchange-addon-customer-pricing' ) . '</a>';
	
	return $actions;
	
}
add_filter( 'plugin_action_links_exchange-addon-customer-pricing/exchange-addon-customer-pricing.php', 'it_exchange_customer_pricing_plugin_row_actions', 10, 4 );

/**
 * Enqueues Customer Pricing scripts to WordPress Dashboard
 *
 * @since 1.0.0
 *
 * @param string $hook_suffix WordPress passed variable
 * @return void
*/
function it_exchange_customer_pricing_addon_admin_wp_enqueue_scripts( $hook_suffix ) {
	global $post;
	
	if ( isset( $_REQUEST['post_type'] ) ) {
		$post_type = $_REQUEST['post_type'];
	} else {
		if ( isset( $_REQUEST['post'] ) )
			$post_id = (int) $_REQUEST['post'];
		elseif ( isset( $_REQUEST['post_ID'] ) )
			$post_id = (int) $_REQUEST['post_ID'];
		else
			$post_id = 0;

		if ( $post_id )
			$post = get_post( $post_id );

		if ( isset( $post ) && !empty( $post ) )
			$post_type = $post->post_type;
	}
	
	if ( isset( $post_type ) && 'it_exchange_prod' === $post_type ) {
		$deps = array( 'post', 'jquery-ui-sortable', 'jquery-ui-droppable', 'jquery-ui-tabs', 'jquery-ui-tooltip', 'jquery-ui-datepicker', 'autosave' );
		wp_enqueue_script( 'it-exchange-customer-pricing-addon-add-edit-product', ITUtility::get_url_from_file( dirname( __FILE__ ) ) . '/admin/js/add-edit-product.js', $deps );
	}
}
add_action( 'admin_enqueue_scripts', 'it_exchange_customer_pricing_addon_admin_wp_enqueue_scripts' );

/**
 * Enqueues Customer Pricing styles to WordPress Dashboard
 *
 * @since 1.0.0
 *
 * @return void
*/
function it_exchange_customer_pricing_addon_admin_wp_enqueue_styles() {
	global $post, $hook_suffix;

	if ( isset( $_REQUEST['post_type'] ) ) {
		$post_type = $_REQUEST['post_type'];
	} else {
		if ( isset( $_REQUEST['post'] ) ) {
			$post_id = (int) $_REQUEST['post'];
		} else if ( isset( $_REQUEST['post_ID'] ) ) {
			$post_id = (int) $_REQUEST['post_ID'];
		} else {
			$post_id = 0;
		}


		if ( $post_id )
			$post = get_post( $post_id );

		if ( isset( $post ) && !empty( $post ) )
			$post_type = $post->post_type;
	}

	// Exchange Product pages
	if ( isset( $post_type ) && 'it_exchange_prod' === $post_type ) {
		wp_enqueue_style( 'it-exchange-customer-pricing-addon-add-edit-product', ITUtility::get_url_from_file( dirname( __FILE__ ) ) . '/admin/styles/add-edit-product.css' );
	}
}
add_action( 'admin_print_styles', 'it_exchange_customer_pricing_addon_admin_wp_enqueue_styles' );

/**
 * Enqueues Customer Pricing scripts to WordPress frontend
 *
 * @since 1.0.0
 *
 * @param string $current_view WordPress passed variable
 * @return void
*/
function it_exchange_customer_pricing_addon_load_public_scripts( $current_view ) {
	// Frontend Customer Pricing Dashboard CSS & JS
	wp_enqueue_script( 'it-exchange-customer-pricing-addon-public-js', ITUtility::get_url_from_file( dirname( __FILE__ ) . '/assets/js/customer-pricing.js' ), array( 'jquery' ), false, true );
	
	if ( is_ssl() )
		$ajax_url = admin_url( 'admin-ajax.php', 'https' );
	else
		$ajax_url = admin_url( 'admin-ajax.php', 'http' );
	wp_localize_script( 'it-exchange-customer-pricing-addon-public-js', 'it_exchange_customer_pricing_ajax_object', array( 'ajax_url' => $ajax_url ) );

	wp_enqueue_style( 'it-exchange-customer-pricing-addon-public-css', ITUtility::get_url_from_file( dirname( __FILE__ ) . '/assets/styles/customer-pricing.css' ) );
}
add_action( 'wp_enqueue_scripts', 'it_exchange_customer_pricing_addon_load_public_scripts' );

function it_exchange_customer_pricing_before_print_metabox_base_price( $product ) {
	
	$enabled = it_exchange_get_product_feature( $product->ID, 'customer-pricing', array( 'setting' => 'enabled' ) );
	$hide = ( 'yes' == $enabled ) ? ' hide-if-js' : '';

	$html  = '<div id="base-price-customer-pricing-disabled" class="base-price-customer-pricing-toggle' . $hide . '">';
	
	echo $html;
}
add_action( 'it_exchange_before_print_metabox_base_price', 'it_exchange_customer_pricing_before_print_metabox_base_price' );

/**
 * Replaces default base_price metabox content with customer-pricing metabox content
 *
 * @since 1.0.0
 *
 * @param object $product iThemes Exchange Product Object
 * @return void
*/
function it_exchange_customer_pricing_after_print_metabox_base_price( $product ) {
	$description = __( 'Price', 'it-l10n-exchange-addon-customer-pricing' );
	$description = apply_filters( 'it_exchange_base-price_addon_metabox_description', $description );
	
	$enabled = it_exchange_get_product_feature( $product->ID, 'customer-pricing', array( 'setting' => 'enabled' ) );
	$hide = ( 'no' == $enabled ) ? ' hide-if-js' : '';
	
	$html  = '</div>'; //ending starting div from it_exchange_customer_pricing_before_print_metabox_base_price
	$html .= '<div id="base-price-customer-pricing-enabled" class="base-price-customer-pricing-toggle' . $hide . '">';	
	$html .= '<label for="base-price">' . esc_html( $description );
	$html .= '<span class="tip" title="' . __( 'To change the price of this product, go the Customer Pricing in the Advanced Options section.', 'it-l10n-exchange-addon-customer-pricing' ) . '">i</span>';
	$html .= '</label>';
	$html .= '<input type="text" class="customer-pricing-enabled" value="' . __( 'Custom', 'it-l10n-exchange-addon-customer-pricing' ) . '" disabled />';
	$html .= '</div>';
	
	echo $html;
}
add_action( 'it_exchange_after_print_metabox_base_price', 'it_exchange_customer_pricing_after_print_metabox_base_price' );

/**
 * Adds Customer Pricing Template Path to iThemes Exchange Template paths
 *
 * @since 1.0.0
 * @param array $possible_template_paths iThemes Exchange existing Template paths array
 * @param array $template_names
 * @return array
*/
function it_exchange_customer_pricing_addon_template_path( $possible_template_paths, $template_names ) {
	$possible_template_paths[] = dirname( __FILE__ ) . '/templates/';
	return $possible_template_paths;
}
add_filter( 'it_exchange_possible_template_paths', 'it_exchange_customer_pricing_addon_template_path', 10, 2 );

function it_exchange_customer_pricing_store_product_product_info_loop_elements( $parts ) {
	$product = $GLOBALS['it_exchange']['product'];
	
	if ( false !== $key = array_search( 'base-price', $parts ) ) {
		if ( it_exchange_product_has_feature( $product->ID, 'customer-pricing', array( 'setting' => 'enabled' ) ) ) {
			if ( 'yes' === $enabled = it_exchange_get_product_feature( $product->ID, 'customer-pricing', array( 'setting' => 'enabled' ) ) ) {
				$parts[$key] = 'customer-pricing';	
			}
		}
	}
	return $parts;
}
add_filter( 'it_exchange_get_store_product_product_info_loop_elements', 'it_exchange_customer_pricing_store_product_product_info_loop_elements' );

/**
 * Replaces base-price content product element with customer-pricing element, if found
 *
 * @since 1.0.0
 *
 * @param array $parts Element array for temmplate parts
 * @return array Modified array with new customer-pricing element (if base-price was found).
*/
function it_exchange_customer_pricing_content_product_product_info_loop_elements( $parts ) {
	$product = $GLOBALS['it_exchange']['product'];
	
	if ( false !== $key = array_search( 'base-price', $parts ) ) {
		if ( it_exchange_product_has_feature( $product->ID, 'customer-pricing', array( 'setting' => 'enabled' ) ) ) {
			if ( 'yes' === $enabled = it_exchange_get_product_feature( $product->ID, 'customer-pricing', array( 'setting' => 'enabled' ) ) ) {
				$parts[$key] = 'customer-pricing';	
			}
		}
	}
	return $parts;
}
add_filter( 'it_exchange_get_content_product_product_info_loop_elements', 'it_exchange_customer_pricing_content_product_product_info_loop_elements' );

/**
 * Replaces base-price with customer's set price from session data
 *
 * @since 1.0.0
 *
 * @param string $db_base_price default Base Price
 * @param array $product iThemes Exchange Product
 * @param bool $format Whether or not the price should be formatted 
 * @return string $db_base_price modified, if customer price has been set for product
*/
function it_exchange_get_customer_pricing_cart_product_base_price( $db_base_price, $product, $format ) {	
	if ( $customer_prices = it_exchange_get_session_data( 'customer-prices' ) ) {

		// Use isset incase price is set to 0.00
		if ( isset( $customer_prices[$product['product_id']] ) ) {
			$db_base_price = it_exchange_convert_from_database_number( $customer_prices[$product['product_id']] );
			
			if ( $format )
				$db_base_price = it_exchange_format_price( $db_base_price );
		}
		
	}
	return $db_base_price;
}
add_filter( 'it_exchange_get_cart_product_base_price', 'it_exchange_get_customer_pricing_cart_product_base_price', 10, 3 );

/**
 * Replaces base-price with default customer-pricing setting on Products page in WP Dashboard
 * Or the lowest price option if no default has been set
 *
 * @since 1.0.0
 *
 * @param string $base_price default Base Price
 * @param int $product_id iThemes Exchange Product ID
 * @param array $options Any options being passed through function 
 * @return string $base_price modified, if  customer pricing has been enabled for product
*/
function it_exchange_customer_pricing_get_product_feature_base_price( $base_price, $product_id, $options ) {
	if ( it_exchange_product_has_feature( $product_id, 'customer-pricing', array( 'setting' => 'enabled' ) ) ) {
		if ( 'yes' === $enabled = it_exchange_get_product_feature( $product_id, 'customer-pricing', array( 'setting' => 'enabled' ) ) ) {
			$price_options = it_exchange_get_product_feature( $product_id, 'customer-pricing', array( 'setting' => 'pricing-options' ) );
			if ( !empty( $price_options ) ) {
				foreach( $price_options as $price_option ) {
					$db_price = $price_option['price'];
					$price = it_exchange_convert_from_database_number( $price_option['price'] );
					if ( 0 == $base_price || $price < $base_price )
						$base_price = $price;
					if ( 'checked' === $price_option['default'] ) {
						$base_price = $price;
						break;
					}
				}
			} else {
				$base_price = $base_price;	
			}
		}
	}
	return $base_price;
}
add_filter( 'it_exchange_get_product_feature_base-price', 'it_exchange_customer_pricing_get_product_feature_base_price', 10, 3 );