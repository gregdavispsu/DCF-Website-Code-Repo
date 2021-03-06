<?php

/**
 * Class UB_Admin_Bar
 * @since 1.5
 * @var $wpdb WPDB
 */
global $wpdb;
class UB_Admin_Bar {

    /**
     * @const module name
     */
    const NAME = "custom-admin-bar";

    /**
     * @const Current Version Number
     */
    const VERSION = "1.5.1";

    /**
     * @const post type name
     */
    const POST_TYPE = "ub_admin_bar";

    /**
     * @const version key used for storing version number in database
     */
    const VERSION_KEY = 'ub_admin_bar_version';

    const OPTION_KEY = 'ub_admin_bar_menus';

    const MENU_OPTION_KEY = "ub_admin_bar_menu_";
    /**
     * @const style key for saving to options table
     */
    const STYLE = "ub_admin_bar_style";

    /**
     * @const order key for saving admin bar order to options table
     */
    const ORDER = "ub_admin_bar_order";
    /**
     * Constructs the class and hooks to action/filter hooks
     *
     * @since 1.5
     * @access public
     *
     */
    function __construct(){
        $this->_migrate_data();
        add_action("init", array( $this, "register_post_type" ));
        add_filter( 'ultimatebranding_settings_menu_adminbar_process', array( $this, 'update_data'), 10, 1 );
        add_action( "admin_bar_menu", array( $this, "reorder_menus" ), 99999 );
        add_action('admin_bar_menu', array( $this, "add_custom_menus" ), 1);
        add_action('admin_bar_menu', array( $this, "remove_menus_from_admin_bar" ), 999);
        add_action("wp_before_admin_bar_render", array( $this, "before_admin_bar_render" ));
        add_action("wp_after_admin_bar_render", array( $this, "after_admin_bar_render" ));
        add_action( "wp_ajax_ub_save_menu_ordering", array( $this, "ajax_save_menu_ordering" ) );

    }

    /**
     * @return void
     */
    function register_post_type(){
        $args = array(
            'labels'             => array(),
            'public'             => false,
            'publicly_queryable' => false,
            'show_ui'            => false,
            'show_in_menu'       => false,
            'query_var'          => false,
            'capability_type'    => 'page',
            'has_archive'        => true,
            'hierarchical'       => true,
            'menu_position'      => 999,
            'supports'           => array( )
        );
        register_post_type( self::POST_TYPE, $args );
    }
    /**
     * Updates data
     *
     * @since 1.5
     * @access public
     *
     * @hook ultimatebranding_settings_menu_adminbar_process
     *
     * @param bool $status
     * @return bool true on successful update, false on update failure
     */
    function update_data( $status ) {
        if( isset( $_POST['ub_admin_bar_restore_default_order'] ) ){
            ub_update_option( self::ORDER, "" );
            return true;
        }

        ub_update_option('wdcab', $_POST['wdcab']);

        ub_update_option( self::STYLE, strip_tags( $_POST['ub_admin_bar_style'] ) );

        $save_result = $this->_save_new_menus();
        $update_result = $this->_update_prev_menus();
        $remove_result = $this->_remove_menus();
        return $save_result && $status && $update_result && $remove_result;
    }

    /**
     * Saves newly added menus
     *
     * @since 1.5
     * @access private
     *
     * @return bool
     */
    private function _save_new_menus(){
        if( isset( $_POST['ub_ab_new'] ) ){
            $news = $_POST['ub_ab_new'];
            foreach( $news as $key =>  $new ){
                $new_sub = $new['links']['_last_'];
                unset( $new['links']['_last_'] );
                $new['title'] = empty( $new['title'] ) ? __("New Menu Title", "ub") :  $new['title'];

                $parent_id = $this->_insert_menu( $new );
                $sub_insert = true;
                // save new sub menu if any
                if( is_numeric($parent_id) && isset( $new_sub['title'] ) && !empty( $new_sub['title'] ) ){
                    $sub_insert =  $this->_insert_sub_menu( $new_sub, $parent_id );
                }
            }

            $this->_update_menus_record( $parent_id );
            return $parent_id && $sub_insert;
        }
        return true;
    }

    private static function _get_menu_composite_id($id)
    {
        return self::MENU_OPTION_KEY . $id;
    }
    /**
     * Updates row that keeps id of the menus
     *
     * @param $new_id
     */
    private function _update_menus_record( $new_id ){
        $prev_ids = ub_get_option( self::OPTION_KEY );
        $prev_ids[] = $new_id;
        ub_update_option( self::OPTION_KEY, $prev_ids );
    }

    /**
     * Returns last menu id
     *
     * @since 1.7.3
     * @access private
     *
     * @return int
     */
    private function _get_last_menu_id(){
        global $wpdb;
        if(is_multisite() && function_exists('is_plugin_active_for_network') && is_plugin_active_for_network('ultimate-branding/ultimate-branding.php')) {
            return (int) $wpdb->get_var("SELECT MAX(meta_id) FROM $wpdb->sitemeta WHERE `meta_key` LIKE '". self::MENU_OPTION_KEY ."%'");
        }else{
            return (int) $wpdb->get_var("SELECT MAX(option_id) FROM $wpdb->options WHERE `option_name` LIKE '". self::MENU_OPTION_KEY ."%'");
        }
    }
    /**
     * Updates previous menus
     *
     * @since 1.5
     * @access private
     *
     * @return bool
     */
    private function  _update_prev_menus(){
        $result = array();
        if( isset( $_POST['ub_ab_prev'] ) ) {
            $menus = $_POST['ub_ab_prev'];
            //var_dump($menus);die;
            foreach( $menus  as $menu_id => $menu ){
                $new = $menu['links']['_last_'];
                unset( $menu['links']['_last_'] );
                $links = (array) $menu['links'];

                // add new submenu
                if( isset( $new['title'] ) && !empty( $new['title'] ) ){
//                    if( $new['url_type'] === "admin" ){
//                        $new['url'] = network_admin_url( $new['url'] );
//                    }elseif( $new['url_type'] === "site" ){
//                        $new['url'] = network_site_url( $new['url'] );
//                    }
                     $links[] = $new;
                }

                $menu['links'] = $links;

                // update parent menu
                $result[] = $this->_update_menu($menu_id, $menu);
            }
        }

        return true;
    }

    /**
     * Removes menus
     *
     * @since 1.5
     * @access private
     *
     * @return bool
     */
    private function _remove_menus(){
        $result = array();
        if( isset( $_POST['ub_ab_delete_links'] ) ){
            $links = $_POST["ub_ab_delete_links"];
            $links = explode( ",", $links );
            $saved_ids = (array) maybe_unserialize( ub_get_option( self::OPTION_KEY ) );
            $links = array_map('trim', $links);
            $saved_ids = array_diff( $saved_ids, $links );
            foreach( $links as $key => $link_id ){
                ub_delete_option( self::_get_menu_composite_id( $link_id ) );
            }
            ub_update_option( self::OPTION_KEY, $saved_ids );
        }

        return !in_array(false, $result);
    }


    /**
     * Inserts new menu
     *
     * @since 1.5
     * @access private
     *
     * @param $menu
     * @return int|WP_Error
     */
    private function _insert_menu( $menu ){
        $parent_id = $this->_get_last_menu_id();
        $composite_id  =  $this->_get_menu_composite_id( $parent_id ) ;
        return ub_update_option($composite_id, serialize( $menu ) ) ? $parent_id : false;
    }

    /**
     * Inserts sub menu
     *
     * @since 1.5
     * @access private
     *
     * @param $sub_menu
     * @param $parent_id
     * @param int $order
     * @return int|WP_Error
     */
    private function _insert_sub_menu($sub_menu, $parent_id){

//        switch( $sub_menu['url_type'] ){
//            case "site":
//                $sub_menu['url'] = network_site_url( $sub_menu['url'] );
//            break;
//            case "admin":
//                $sub_menu['url'] = network_admin_url( $sub_menu['url'] );
//            break;
//        }
        $composite_id = self::_get_menu_composite_id( $parent_id );
        $menu = unserialize( ub_get_option( $composite_id ) );
        $links = isset(  $menu['links'] ) ? (array) $menu['links'] : array();
        $links[] = $sub_menu;
        $menu["links"] = $links;
        return ub_update_option( $composite_id, serialize( $menu ));
    }

    /**
     * Updates a single menu
     *
     * @since 1.5
     * @access private
     *
     * @param $id
     * @param $menu
     * @param int $main_order
     * @return int|WP_Error
     */
    private function _update_menu($id, $menu)
    {
        return ub_update_option( self::_get_menu_composite_id( $id ), $menu );
    }



    /**
     * Returns number of parent menus
     *
     * @since 1.5
     * @access public
     *
     * @return int number of parent menus
     */
    public static function number_of_menus(){
        global $wpdb;
        $table = $wpdb->base_prefix . "posts";
        $q = $wpdb->prepare( "SELECT COUNT(*) FROM `{$table}` WHERE `post_type` = %s AND `post_parent` = 0 ", self::POST_TYPE);
        restore_current_blog();
        return (int) $wpdb->get_var( $q );
    }

    /**
     * Retrieves menus from database
     *
     * @since 1.5
     * @access public
     *
     * @return array UB_Admin_Bar_Menu|bool false
     */
    public static  function menus(){
        global $wpdb;
        $ids = maybe_unserialize( ub_get_option( self::OPTION_KEY ) );
        $menus = array();
        if( $ids ){
            foreach( $ids as $id){
                $composite_id = self::_get_menu_composite_id( $id ) ;
                if( $m =  ub_get_option( $composite_id )  ){
                    $menus[]  = new UB_Admin_Bar_Menu( $id , maybe_unserialize( $m ) );
                }
            }
        }

        return $menus;
    }

    /**
     * Migrates data from versions less than 1.5
     *
     * @since 1.5
     * @access public
     *
     * @return void
     */
    private function _migrate_data(){
        global $wpdb;
        if(  ub_get_option( self::VERSION_KEY ) < self::VERSION  || ub_get_option( self::VERSION_KEY ) === false){

            /**
             * Migrate data from versions before 1.7
             */
            $prev_bar = ub_get_option("wdcab");
            if( !empty( $prev_bar["title"] ) ){
                $prev_subs = $prev_bar['links'];
                unset($prev_bar['links']);
                $parent = array(
                    'title' => $prev_bar['title'],
                    'title_link' =>  "url",
                    'url' =>  $prev_bar["title_link"],
                    "target" => isset( $prev_bar['title_link_target'] ) && $prev_bar['title_link_target'] === "_blank" ? "on" : "_self"
                );
                $parent_id = $this->_insert_menu($parent);
                if( count( $prev_subs ) > 0 ){
                    foreach( $prev_subs as $key =>  $sub ){
                        $this->_insert_sub_menu($sub, $parent_id);
                    }
                }
                $this->_update_menus_record( $parent_id );

            }

            /**
             * Migrate data from versions before 1.7.2
             */
            $table = is_multisite() ? $wpdb->base_prefix . "posts" : $wpdb->posts;
            /**
             * Migrating parent menus
             */
            $parent_menus = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $table WHERE `post_parent`=0 AND `post_type` = %s", self::POST_TYPE ) );
            if( $parent_menus ){
                /**
                 * @var $parent_menu WP_Post
                 */
                foreach( $parent_menus as $parent_menu ){
                    $parent = array(
                        'title' => $parent_menu->post_title,
                        'title_link' =>  "url",
                        'url' =>  $parent_menu->post_content,
                        "target" => $parent_menu->post_excerpt === "_blank" ? "on" : "",
                    );
                    $parent_id = $this->_insert_menu( $parent );
                    /**
                     * Migrating submenus
                     */
                    $subs = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $table WHERE `post_parent` = %d AND `post_type`=%s", $parent_menu->ID, self::POST_TYPE ) );
                    if( $subs ){
                        /**
                         * @var $sub WP_Post
                         */
                        foreach( $subs as $sub ){
                            $sub_menu = array(
                                'title' => $sub->post_title,
                                'url_type' =>  $sub->ping_status,
                                'url' =>  $sub->post_content,
                                "target" => $sub->post_excerpt === "_blank" ? "on" : "",
                            );
                            $this->_insert_sub_menu( $sub_menu, $parent_id );
                        }
                    }
                    $this->_update_menus_record( $parent_id );
                }
            }
            ub_update_option( self::VERSION_KEY, self::VERSION );
        }

    }

    /**
     * Returns menus style
     *
     * @since 1.6
     * @access public
     *
     * @param bool $editor, true, it's in editor mode
     * @return array|mixed|string|void
     */
    public static function styles($editor = false){
        $style = <<<UBSTYLE
.ub_admin_bar_image{
    max-width: 100%;
    max-height: 28px;
    padding: 2px 0;
}
UBSTYLE;

        $save_style = ub_get_option(self::STYLE);
        if( $editor ){
            return  $save_style;
        }

        $styles = empty( $save_style ) ? $style : $save_style;
        return self::_prefix_styles( $styles );
    }

    /**
     * Adds #ub_admin_bar_wrap prefix to the define styles
     *
     * @since 1.6
     * @access private
     *
     * @param $styles
     * @return array|string
     */
    private static function _prefix_styles( $styles ){
        if( !empty( $styles ) ){
            $styles = array_filter( explode( "}", $styles) );
            $output = array();
            foreach( $styles as $style ){
                $output[] =  "#ub_admin_bar_wrap " . $style . "}";
            }

            return implode( "", $output);
        }
        return $styles;
    }

    /**
     * Saves new menu order into database
     *
     * @since 1.6
     * @access public
     *
     * @return void
     */
    public function ajax_save_menu_ordering(){
        $order = $_POST['order'];

        $result = array(
            "status" => false
        );

        if( is_array( $order ) && count( $order ) > 0 ){

            ub_update_option( self::ORDER, $order );
            $result = array(
                "status" => true
            ) ;
        }

        header('Content-Type: application/json');
        echo json_encode($result);
        wp_die();
    }

    /**
     * Returns menus' order
     *
     * @since 1.6
     * @access public
     *
     * @return mixed|void
     */
    public static function order(){
        return ub_get_option( self::ORDER );
    }


    /**
     * Renders before admin bad renderer
     *
     * @hook wp_before_admin_bar_render
     *
     * @since 1.6
     * @access public
     */
    function before_admin_bar_render(){
        ?>
        <style type="text/css">
            <?php echo UB_Admin_Bar::styles();?>
        </style>

        <div id='ub_admin_bar_wrap'>

    <?php
    }

    /**
     * Renders after admin bad renderer
     *
     * @hook wp_after_admin_bar_render
     *
     * @since 1.6
     * @access public
     */
    function after_admin_bar_render(){
        ?>
        </div>
        <?php
    }


    /**
     * Keeps the menus in order based on saved orderings
     *
     * @since 1.6
     * @access public
     *
     * @param $wp_admin_bar instance of WP_Admin_Bar passed by refrence
     * @hook admin_bar_menu
     *
     * @return void
     */
    public function reorder_menus(){
        /**
         * @var $wp_admin_bar WP_Admin_Bar
         */
        global $wp_admin_bar;
        $order = UB_Admin_Bar::order();
        if( !$order || !is_array( $order ) ) return;

        $nodes = $wp_admin_bar->get_nodes();
        // remove all nodes
        foreach( $nodes as $node_id =>  $node ){
            $wp_admin_bar->remove_node( $node_id );
        }

        // add ordered nodes
        foreach( $order as $o ){
            if( isset( $nodes[$o] ) ){
                $wp_admin_bar->add_node( $nodes[ $o ] );
                unset( $nodes[ $o ] );
            }
        }

        // add rest of the nodes
        if( count( $nodes ) > 0 ){
            foreach( $nodes as $node ){
                $wp_admin_bar->add_node($node);
            }
        }

    }

    /**
     * Adds custom menus to the admin bar
     *
     * @since 1.0
     * @access public
     *
     * @param $wp_admin_bar WP_Admin_Bar passed by refrence
     * @hook admin_bar_menu
     *
     * @return void
     */
    function add_custom_menus() {
        /**
         * @var $wp_admin_bar WP_Admin_Bar
         */
        global $wp_admin_bar;

        $enabled = ub_get_option("wdcab");
        $enabled = (bool) $enabled['enabled'];
        if( !$enabled ) return;

        /**
         * @var $menu UB_Admin_Bar_Menu
         * @var $sub UB_Admin_Bar_Menu
         */
        $menus = UB_Admin_Bar::menus();
        if( is_array( $menus ) && !empty( $menus )){
            foreach( $menus as $menu ){
                $wp_admin_bar->add_menu(array(
                        'id' => "ub_admin_bar_" . $menu->id,
                        'title' => $menu->title_image,
                        'href' => $menu->link_url,
                        'meta' => array(
                            'target' => $menu->target
                        ),
                    )
                );
                $submenus = $menu->subs;
                if( $submenus ){
                    foreach( $submenus as $sub ){
                        $wp_admin_bar->add_menu(array(
                                'parent' => "ub_admin_bar_" . $menu->id,
                                'id' => $sub->external_id ,
                                'title' => $sub->title_image,
                                'href' => $sub->link_url,
                                'meta' => array(
                                    'target' => $sub->target
                                ),
                            )
                        );
                    }
                }

            }
        }

    }

    /**
     * Removes selected default menus from admin bar
     *
     * @since 1.0
     * @access public
     *
     * @return void
     */
    function remove_menus_from_admin_bar() {
        global $wp_version;
        $version = preg_replace('/-.*$/', '', $wp_version);
        if (version_compare($version, '3.3', '>=')) {
            global $wp_admin_bar;
            $opts = ub_get_option('wdcab');
            $disabled = is_array(@$opts['disabled_menus']) ? $opts['disabled_menus'] : array();
            foreach ($disabled as $id) {
                $wp_admin_bar->remove_node($id);
            }
        }
    }


}

new UB_Admin_Bar;