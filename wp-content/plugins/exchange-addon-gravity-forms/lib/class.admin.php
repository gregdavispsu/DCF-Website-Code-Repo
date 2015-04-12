<?php

/**
 * Add custom functionality to the Gravity Forms edit screen.
 */
class ITEGFP_Admin {

    const ACTION = 'itegfp-variants';

    /**
     * @var string $ajax
     */
    private $ajax;

    /**
     * @var string $nonce
     */
    private $nonce;

	/**
	 * Constructor.
	 *
	 * @since 1.0
	 */
    public function __construct() {
        $this->ajax = admin_url( 'admin-ajax.php' );

        $this->nonce = wp_create_nonce( self::ACTION );

        if ( is_admin() && isset( $_GET['page'] ) && $_GET['page'] == 'gf_edit_forms' ) {
            add_action( 'admin_enqueue_scripts', array( $this, 'scripts' ) );
        }
    }

    /**
     * Load JS script(s) that will customize the behavior of some Gravity Form fields.
     *
     * @since 1.0
     */
    public function scripts() {
        wp_enqueue_script( 'itegfp-admin' );

        wp_localize_script( 'itegfp-admin', 'ITEGFP', array(
            'action' => self::ACTION,
            'nonce'  => $this->nonce,
            'ajax'   => $this->ajax,
        ) );
    }
}