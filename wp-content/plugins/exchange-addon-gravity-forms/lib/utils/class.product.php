<?php

/**
 * A utility class for iThemes Exchange products.
 */
class ITEGFP_Utils_Product {

    /**
     * @var $product IT_Exchange_Product
     */
    private $product;

    /**
     * Constructor.
     *
     * @since 1.0
     *
     * @param $product IT_Exchange_Product
     */
    public function __construct( $product ) {
        $this->product = $product;
    }

    /**
     * Get the post ID of the product.
     *
     * @since 1.0
     *
     * @return int
     */
    public function get_ID() {
        return $this->product->ID;
    }

    /**
     * Get the post title of the product.
     *
     * @since 1.0
     *
     * @return string
     */
    public function get_title() {
        return $this->product->post_title;
    }

    /**
     * Get the base price of the product.
     *
     * @since 1.0
     *
     * @return float
     */
    public function get_price() {
        return it_exchange_get_product_feature( $this->product->ID, 'base-price' );
    }
}