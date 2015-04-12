<?php

/**
 * A utility class for easy management of the Exchange shopping cart.
 */
class ITEGFP_Utils_Cart {

    /**
     * @var $cache array
     */
    private $cache;

    /**
     * Clear the current items int he shopping cart.
     *
     * @since 1.0
     */
    public function clear() {
        it_exchange_empty_shopping_cart();
    }

    /**
     * Cache the current items in the shopping cart.
     *
     * @since 1.0
     */
    public function cache() {
        $this->cache = it_exchange_get_cart_products();
    }

    /**
     * Restore the cached items to the shopping cart.
     *
     * @since 1.0
     */
    public function restore() {
        foreach ( $this->cache as $key => $item ) {
            it_exchange_add_cart_product( $key, $item );
        }
    }

    /**
     * Add products from Gravity Form's product field to the Exchange shopping cart.
     *
     * @since 1.0
     *
     * @param $entry array
     * @param $form  array
     */
    public function add_products( $entry, $form ) {
        $product_fields = GFCommon::get_product_fields( $form, $entry );
        
        foreach ( $form[ 'fields' ] as $field ) {
            switch ( $field[ 'type' ] ) {
                case 'product':
                    if ( !empty( $field[ 'itExchangeProduct' ] ) ) {
                        foreach ( $product_fields[ 'products' ] as $product ) {
                            // Add the product to the shopping cart session.
                            // The cart is used by the it_exchange_generate_transaction_object() function.
                            it_exchange_add_product_to_shopping_cart( $field[ 'itExchangeProduct' ], $product[ 'quantity' ] );
                        }
                    }
                    break;
            }
        }
    }

}