<?php
/**
 * Plugin Name: Soliloquy - Featured Content Addon
 * Plugin URI:  http://soliloquywp.com
 * Description: Enables featured content sliders in Soliloquy.
 * Author:      Thomas Griffin
 * Author URI:  http://thomasgriffinmedia.com
 * Version:     2.1.8
 * Text Domain: soliloquy-fc
 * Domain Path: languages
 *
 * Soliloquy is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * any later version.
 *
 * Soliloquy is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Soliloquy. If not, see <http://www.gnu.org/licenses/>.
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Define necessary addon constants.
define( 'SOLILOQUY_FC_PLUGIN_NAME', 'Soliloquy - Featured Content Addon' );
define( 'SOLILOQUY_FC_PLUGIN_VERSION', '2.1.8' );
define( 'SOLILOQUY_FC_PLUGIN_SLUG', 'soliloquy-featured-content' );

add_action( 'plugins_loaded', 'soliloquy_fc_plugins_loaded' );
/**
 * Ensures the full Soliloquy plugin is active before proceeding.
 *
 * @since 1.0.0
 *
 * @return null Return early if Soliloquy is not active.
 */
function soliloquy_fc_plugins_loaded() {

    // Bail if the main class does not exist.
    if ( ! class_exists( 'Soliloquy' ) ) {
        return;
    }

    // Fire up the addon.
    add_action( 'soliloquy_init', 'soliloquy_fc_plugin_init' );

}

/**
 * Loads all of the addon hooks and filters.
 *
 * @since 1.0.0
 */
function soliloquy_fc_plugin_init() {

    // Add hooks and filters.
    add_action( 'soliloquy_updater', 'soliloquy_fc_updater' );
    add_action( 'soliloquy_metabox_styles', 'soliloquy_fc_styles' );
    add_action( 'soliloquy_metabox_scripts', 'soliloquy_fc_scripts' );
    add_filter( 'soliloquy_defaults', 'soliloquy_fc_defaults', 10, 2 );
    add_filter( 'soliloquy_slider_types', 'soliloquy_fc_type' );
    add_action( 'soliloquy_display_fc', 'soliloquy_fc_settings' );
    add_filter( 'soliloquy_save_settings', 'soliloquy_fc_save', 10, 2 );
    add_action( 'save_post', 'soliloquy_fc_flush_global_caches', 999, 2 );
    add_action( 'soliloquy_flush_caches', 'soliloquy_fc_flush_caches', 10, 2 );
    add_filter( 'soliloquy_pre_data', 'soliloquy_fc_data', 10, 2 );
    add_filter( 'soliloquy_output_classes', 'soliloquy_fc_class', 10, 2 );

    // Add ajax callbacks.
    add_action( 'wp_ajax_soliloquy_fc_refresh_terms', 'soliloquy_fc_refresh_terms' );
    add_action( 'wp_ajax_soliloquy_fc_refresh_posts', 'soliloquy_fc_refresh_posts' );

}

/**
 * Initializes the addon updater.
 *
 * @since 1.0.0
 *
 * @param string $key The user license key.
 */
function soliloquy_fc_updater( $key ) {

    $args = array(
        'plugin_name' => SOLILOQUY_FC_PLUGIN_NAME,
        'plugin_slug' => SOLILOQUY_FC_PLUGIN_SLUG,
        'plugin_path' => plugin_basename( __FILE__ ),
        'plugin_url'  => trailingslashit( WP_PLUGIN_URL ) . SOLILOQUY_FC_PLUGIN_SLUG,
        'remote_url'  => 'http://soliloquywp.com/',
        'version'     => SOLILOQUY_FC_PLUGIN_VERSION,
        'key'         => $key
    );
    $soliloquy_fc_updater = new Soliloquy_Updater( $args );

}

/**
 * Registers and enqueues featured content styles.
 *
 * @since 1.0.0
 */
function soliloquy_fc_styles() {

    // Register featured content styles.
    wp_register_style( SOLILOQUY_FC_PLUGIN_SLUG . '-chosen', plugins_url( 'css/chosen.min.css', __FILE__ ), array(), SOLILOQUY_FC_PLUGIN_VERSION );
    wp_register_style( SOLILOQUY_FC_PLUGIN_SLUG . '-style', plugins_url( 'css/admin.css', __FILE__ ), array( SOLILOQUY_FC_PLUGIN_SLUG . '-chosen' ), SOLILOQUY_FC_PLUGIN_VERSION );

    // Enqueue featured content styles.
    wp_enqueue_style( SOLILOQUY_FC_PLUGIN_SLUG . '-chosen' );
    wp_enqueue_style( SOLILOQUY_FC_PLUGIN_SLUG . '-style' );

}

/**
 * Registers and enqueues featured content scripts.
 *
 * @since 1.0.0
 */
function soliloquy_fc_scripts() {

    // Register featured content scripts.
    wp_register_script( SOLILOQUY_FC_PLUGIN_SLUG . '-chosen', plugins_url( 'js/chosen.jquery.min.js', __FILE__ ), array( 'jquery' ), SOLILOQUY_FC_PLUGIN_VERSION, true );
    wp_register_script( SOLILOQUY_FC_PLUGIN_SLUG . '-script', plugins_url( 'js/fc.js', __FILE__ ), array( 'jquery', SOLILOQUY_FC_PLUGIN_SLUG . '-chosen' ), SOLILOQUY_FC_PLUGIN_VERSION, true );

    // Enqueue featured content scripts.
    wp_enqueue_script( SOLILOQUY_FC_PLUGIN_SLUG . '-chosen' );
    wp_enqueue_script( SOLILOQUY_FC_PLUGIN_SLUG . '-script' );

    // Localize script with nonces.
    wp_localize_script(
        SOLILOQUY_FC_PLUGIN_SLUG . '-script',
        'soliloquy_fc_metabox',
        array(
            'refresh_nonce' => wp_create_nonce( 'soliloquy-fc-refresh' ),
            'term_nonce'    => wp_create_nonce( 'soliloquy-fc-term-refresh' )
        )
    );

}

/**
 * Applies a default to the addon setting.
 *
 * @since 1.0.0
 *
 * @param array $defaults  Array of default config values.
 * @param int $post_id     The current post ID.
 * @return array $defaults Amended array of default config values.
 */
function soliloquy_fc_defaults( $defaults, $post_id ) {

    $defaults['fc_post_types']       = array( 'post' );
    $defaults['fc_terms']            = array();
    $defaults['fc_query']            = 'include';
    $defaults['fc_inc_ex']           = array();
    $defaults['fc_orderby']          = 'date';
    $defaults['fc_order']            = 'DESC';
    $defaults['fc_number']           = 5;
    $defaults['fc_offset']           = 0;
    $defaults['fc_status']           = 'publish';
    $defaults['fc_post_url']         = 1;
    $defaults['fc_post_title']       = 1;
    $defaults['fc_post_title_link']  = 1;
    $defaults['fc_content_type']     = 'post_excerpt';
    $defaults['fc_content_length']   = 40;
    $defaults['fc_content_ellipses'] = 1;
    $defaults['fc_read_more']        = 1;
    $defaults['fc_read_more_text']   = __( 'Continue Reading...', 'soliloquy-fc' );
    $defaults['fc_fallback']         = '';
    return $defaults;

}

/**
 * Adds the "Featured Content" slider type to the list of available options.
 *
 * @since 1.0.0
 *
 * @param array $types  Types of sliders to select.
 * @return array $types Amended types of sliders to select.
 */
function soliloquy_fc_type( $types ) {

    $types['fc'] = __( 'Featured Content', 'soliloquy-fc' );
    return $types;

}

/**
 * Callback for displaying the UI for setting fc options.
 *
 * @since 1.0.0
 *
 * @param object $post The current post object.
 */
function soliloquy_fc_settings( $post ) {

    // Load the settings for the addon.
    $instance = Soliloquy_Metaboxes::get_instance();
    ?>
    <div id="soliloquy-fc">
        <p class="soliloquy-intro"><?php _e( 'The settings below adjust the Featured Content settings for the slider.', 'soliloquy-fc' ); ?></p>
        <h2><?php _e( 'Query Settings', 'soliloquy-fc' ); ?></h2>
        <table class="form-table">
            <tbody>
                <tr id="soliloquy-config-fc-post-type-box">
                    <th scope="row">
                        <label for="soliloquy-config-fc-post-type"><?php _e( 'Select Your Post Type(s)', 'soliloquy-fc' ); ?></label>
                    </th>
                    <td>
                        <select id="soliloquy-config-fc-post-type" class="soliloquy-fc-chosen" name="_soliloquy[fc_post_types][]" multiple="multiple" data-placeholder="<?php esc_attr_e( 'Select post type(s) to query (defaults to post)...', 'soliloquy-fc' ); ?>">
                        <?php
                            $post_types = get_post_types( array( 'public' => true ) );
                            foreach ( (array) $post_types as $post_type ) {
                                if ( in_array( $post_type, soliloquy_fc_exclude_post_types() ) ) {
                                    continue;
                                }

                                $object = get_post_type_object( $post_type );
                                echo '<option value="' . esc_attr( $post_type ) . '"' . selected( $post_type, in_array( $post_type, (array) $instance->get_config( 'fc_post_types', $instance->get_config_default( 'fc_post_types' ) ) ) ? $post_type : '', false ) . '>' . esc_html( $object->labels->singular_name ) . '</option>';
                            }
                        ?>
                        </select>
                        <p class="description"><?php _e( 'Determines the post types to query.', 'soliloquy-fc' ); ?></p>
                    </td>
                </tr>
                <tr id="soliloquy-config-fc-terms-box">
                    <th scope="row">
                        <label for="soliloquy-config-fc-terms"><?php _e( 'Select Your Taxonomy Term(s)', 'soliloquy-fc' ); ?></label>
                    </th>
                    <td>
                        <select id="soliloquy-config-fc-terms" class="soliloquy-fc-chosen" name="_soliloquy[fc_terms][]" multiple="multiple" data-placeholder="<?php esc_attr_e( 'Select taxonomy terms(s) to query (defaults to none)...', 'soliloquy-fc' ); ?>">
                        <?php
                            $taxonomies = get_taxonomies( array( 'public' => true ), 'objects' );
                            foreach ( (array) $taxonomies as $taxonomy ) {
                                if ( in_array( $taxonomy, soliloquy_fc_exclude_taxonomies() ) ) {
                                    continue;
                                }

                                $terms = get_terms( $taxonomy->name );
                                echo '<optgroup label="' . esc_attr( $taxonomy->labels->name ) . '">';
                                    foreach ( $terms as $term ) {
                                        echo '<option value="' . esc_attr( strtolower( $taxonomy->name ) . '|' . $term->term_id . '|' . $term->slug ) . '"' . selected( strtolower( $taxonomy->name ) . '|' . $term->term_id . '|' . $term->slug, in_array( strtolower( $taxonomy->name ) . '|' . $term->term_id . '|' . $term->slug, (array) $instance->get_config( 'fc_terms', $instance->get_config_default( 'fc_terms' ) ) ) ? strtolower( $taxonomy->name ) . '|' . $term->term_id . '|' . $term->slug : '', false ) . '>' . esc_html( ucwords( $term->name ) ) . '</option>';
                                    }
                                echo '</optgroup>';
                            }
                        ?>
                        </select>
                        <p class="description"><?php _e( 'Determines the taxonomy terms that should be queried based on post type selection.', 'soliloquy-fc' ); ?></p>
                    </td>
                </tr>
                <tr id="soliloquy-config-fc-inc-ex-box">
                    <th scope="row">
                        <label for="soliloquy-config-fc-inc-ex">
                            <select id="soliloquy-config-fc-query" class="soliloquy-fc-chosen" name="_soliloquy[fc_query]">
                                <option value="include" <?php selected( 'include', $instance->get_config( 'fc_query', $instance->get_config_default( 'fc_query' ) ) ); ?>><?php _e( 'Include', 'soliloquy-fc' ); ?></option>
                                <option value="exclude" <?php selected( 'exclude', $instance->get_config( 'fc_query', $instance->get_config_default( 'fc_query' ) ) ); ?>><?php _e( 'Exclude', 'soliloquy-fc' ); ?></option>
                            </select>
                            <?php _e( ' ONLY the following items:', 'soliloquy-fc' ); ?>
                        </label>
                    </th>
                    <td>
                        <select id="soliloquy-config-fc-inc-ex" class="soliloquy-fc-chosen" name="_soliloquy[fc_inc_ex][]" multiple="multiple" data-placeholder="<?php esc_attr_e( 'Make your selection (defaults to none)...', 'soliloquy-fc' ); ?>">
                        <?php
                            $post_types = get_post_types( array( 'public' => true ) );
                            foreach ( (array) $post_types as $post_type ) {
                                if ( in_array( $post_type, soliloquy_fc_exclude_post_types() ) ) {
                                    continue;
                                }

                                $object = get_post_type_object( $post_type );
                                $posts  = get_posts( array( 'post_type' => $post_type, 'posts_per_page' => apply_filters( 'soliloquy_fc_max_queried_posts', 500 ), 'no_found_rows' => true, 'cache_results' => false ) );
                                echo '<optgroup label="' . esc_attr( $object->labels->name ) . '">';
                                    foreach ( (array) $posts as $item ) {
                                        echo '<option value="' . absint( $item->ID ) . '"' . selected( $item->ID, in_array( $item->ID, (array) $instance->get_config( 'fc_inc_ex', $instance->get_config_default( 'fc_inc_ex' ) ) ) ? $item->ID : '', false ) . '>' . esc_html( ucwords( $item->post_title ) ) . '</option>';
                                    }
                                echo '</optgroup>';
                            }
                        ?>
                        </select>
                        <p class="description"><?php _e( 'Will include or exclude ONLY the selected post(s).', 'soliloquy-fc' ); ?></p>
                    </td>
                </tr>
                <tr id="soliloquy-config-fc-orderby-box">
                    <th scope="row">
                        <label for="soliloquy-config-fc-orderby"><?php _e( 'Sort Posts By', 'soliloquy-fc' ); ?></label>
                    </th>
                    <td>
                        <select id="soliloquy-config-fc-orderby" class="soliloquy-fc-chosen" name="_soliloquy[fc_orderby]">
                        <?php
                            foreach ( (array) soliloquy_fc_orderby() as $array => $data )
                                echo '<option value="' . esc_attr( $data['value'] ) . '"' . selected( $data['value'], $instance->get_config( 'fc_orderby', $instance->get_config_default( 'fc_orderby' ) ), false ) . '>' . esc_html( $data['name'] ) . '</option>';
                        ?>
                        </select>
                        <p class="description"><?php _e( 'Determines how the posts are sorted in the slider.', 'soliloquy-fc' ); ?></p>
                    </td>
                </tr>
                <tr id="soliloquy-config-fc-order-box">
                    <th scope="row">
                        <label for="soliloquy-config-fc-order"><?php _e( 'Order Posts By', 'soliloquy-fc' ); ?></label>
                    </th>
                    <td>
                        <select id="soliloquy-config-fc-order" class="soliloquy-fc-chosen" name="_soliloquy[fc_order]">
                        <?php
                            foreach ( (array) soliloquy_fc_order() as $array => $data )
                                echo '<option value="' . esc_attr( $data['value'] ) . '"' . selected( $data['value'], $instance->get_config( 'fc_order', $instance->get_config_default( 'fc_order' ) ), false ) . '>' . esc_html( $data['name'] ) . '</option>';
                        ?>
                        </select>
                        <p class="description"><?php _e( 'Determines how the posts are ordered in the slider.', 'soliloquy-fc' ); ?></p>
                    </td>
                </tr>
                <tr id="soliloquy-config-fc-number-box">
                    <th scope="row">
                        <label for="soliloquy-config-fc-number"><?php _e( 'Number of Slides', 'soliloquy-fc' ); ?></label>
                    </th>
                    <td>
                        <input id="soliloquy-config-fc-number" type="number" name="_soliloquy[fc_number]" value="<?php echo $instance->get_config( 'fc_number', $instance->get_config_default( 'fc_number' ) ); ?>" />
                        <p class="description"><?php _e( 'The number of slides in your Featured Content slider.', 'soliloquy-fc' ); ?></p>
                    </td>
                </tr>
                <tr id="soliloquy-config-fc-offset-box">
                    <th scope="row">
                        <label for="soliloquy-config-fc-offset"><?php _e( 'Posts Offset', 'soliloquy-fc' ); ?></label>
                    </th>
                    <td>
                        <input id="soliloquy-config-fc-offset" type="number" name="_soliloquy[fc_offset]" value="<?php echo absint( $instance->get_config( 'fc_offset', $instance->get_config_default( 'fc_offset' ) ) ); ?>" />
                        <p class="description"><?php _e( 'The number of posts to offset in the query.', 'soliloquy-fc' ); ?></p>
                    </td>
                </tr>
                <tr id="soliloquy-config-fc-status-box">
                    <th scope="row">
                        <label for="soliloquy-config-fc-status"><?php _e( 'Post Status', 'soliloquy-fc' ); ?></label>
                    </th>
                    <td>
                        <select id="soliloquy-config-fc-status" class="soliloquy-fc-chosen" name="_soliloquy[fc_status]">
                        <?php
                            foreach ( (array) soliloquy_fc_statuses() as $status ) {
                                echo '<option value="' . esc_attr( $status->name ) . '"' . selected( $status->name, $instance->get_config( 'fc_status', $instance->get_config_default( 'fc_status' ) ), false ) . '>' . esc_html( $status->label ) . '</option>';
                            }
                        ?>
                        </select>
                        <p class="description"><?php _e( 'Determines the post status to use for the query.', 'soliloquy-fc' ); ?></p>
                    </td>
                </tr>
                <?php do_action( 'soliloquy_fc_box', $post ); ?>
            </tbody>
        </table>

        <h2><?php _e( 'Content Settings', 'soliloquy-fc' ); ?></h2>
        <table class="form-table">
            <tbody>
                <tr id="soliloquy-config-fc-post-url-box">
                    <th scope="row">
                        <label for="soliloquy-config-fc-post-url"><?php _e( 'Link Image to Post URL?', 'soliloquy' ); ?></label>
                    </th>
                    <td>
                        <input id="soliloquy-config-fc-post-url" type="checkbox" name="_soliloquy[fc_post_url]" value="<?php echo $instance->get_config( 'fc_post_url', $instance->get_config_default( 'fc_post_url' ) ); ?>" <?php checked( $instance->get_config( 'fc_post_url', $instance->get_config_default( 'fc_post_url' ) ), 1 ); ?> />
                        <span class="description"><?php _e( 'Links to the image to the post URL.', 'soliloquy' ); ?></span>
                    </td>
                </tr>
                <tr id="soliloquy-config-fc-post-title-box">
                    <th scope="row">
                        <label for="soliloquy-config-fc-post-title"><?php _e( 'Display Post Title?', 'soliloquy' ); ?></label>
                    </th>
                    <td>
                        <input id="soliloquy-config-fc-post-title" type="checkbox" name="_soliloquy[fc_post_title]" value="<?php echo $instance->get_config( 'fc_post_title', $instance->get_config_default( 'fc_post_title' ) ); ?>" <?php checked( $instance->get_config( 'fc_post_title', $instance->get_config_default( 'fc_post_title' ) ), 1 ); ?> />
                        <span class="description"><?php _e( 'Displays the post title over the image.', 'soliloquy' ); ?></span>
                    </td>
                </tr>
                <tr id="soliloquy-config-fc-post-title-link-box">
                    <th scope="row">
                        <label for="soliloquy-config-fc-post-title-link"><?php _e( 'Link Post Title to Post URL?', 'soliloquy' ); ?></label>
                    </th>
                    <td>
                        <input id="soliloquy-config-fc-post-title-link" type="checkbox" name="_soliloquy[fc_post_title_link]" value="<?php echo $instance->get_config( 'fc_post_title_link', $instance->get_config_default( 'fc_post_title_link' ) ); ?>" <?php checked( $instance->get_config( 'fc_post_title_link', $instance->get_config_default( 'fc_post_title_link' ) ), 1 ); ?> />
                        <span class="description"><?php _e( 'Links the post title to the post URL.', 'soliloquy' ); ?></span>
                    </td>
                </tr>
                <tr id="soliloquy-config-fc-content-type-box">
                    <th scope="row">
                        <label for="soliloquy-config-fc-content-type"><?php _e( 'Post Content to Display', 'soliloquy-fc' ); ?></label>
                    </th>
                    <td>
                        <select id="soliloquy-config-fc-content-type" class="soliloquy-fc-chosen" name="_soliloquy[fc_content_type]">
                        <?php
                            foreach ( (array) soliloquy_fc_content_types() as $array => $data )
                                echo '<option value="' . esc_attr( $data['value'] ) . '"' . selected( $data['value'], $instance->get_config( 'fc_content_type', $instance->get_config_default( 'fc_content_type' ) ), false ) . '>' . esc_html( $data['name'] ) . '</option>';
                        ?>
                        </select>
                        <p class="description"><?php _e( 'Determines the type of content to retrieve and output in the caption.', 'soliloquy-fc' ); ?></p>
                    </td>
                </tr>
                <tr id="soliloquy-config-fc-content-length-box">
                    <th scope="row">
                        <label for="soliloquy-config-fc-content-length"><?php _e( 'Number of Words in Content', 'soliloquy-fc' ); ?></label>
                    </th>
                    <td>
                        <input id="soliloquy-config-fc-content-length" type="number" name="_soliloquy[fc_content_length]" value="<?php echo $instance->get_config( 'fc_content_length', $instance->get_config_default( 'fc_content_length' ) ); ?>" />
                        <p class="description"><?php _e( 'Sets the number of words for trimming the post content.', 'soliloquy-fc' ); ?></p>
                    </td>
                </tr>
                <tr id="soliloquy-config-fc-content-ellipses-box">
                    <th scope="row">
                        <label for="soliloquy-config-fc-content-ellipses"><?php _e( 'Append Ellipses to Post Content?', 'soliloquy' ); ?></label>
                    </th>
                    <td>
                        <input id="soliloquy-config-fc-content-ellipses" type="checkbox" name="_soliloquy[fc_content_ellipses]" value="<?php echo $instance->get_config( 'fc_content_ellipses', $instance->get_config_default( 'fc_content_ellipses' ) ); ?>" <?php checked( $instance->get_config( 'fc_content_ellipses', $instance->get_config_default( 'fc_content_ellipses' ) ), 1 ); ?> />
                        <span class="description"><?php _e( 'Places an ellipses at the end of the post content.', 'soliloquy' ); ?></span>
                    </td>
                </tr>
                <tr id="soliloquy-config-fc-read-more-box">
                    <th scope="row">
                        <label for="soliloquy-config-fc-read-more"><?php _e( 'Display Read More Link?', 'soliloquy' ); ?></label>
                    </th>
                    <td>
                        <input id="soliloquy-config-fc-read-more" type="checkbox" name="_soliloquy[fc_read_more]" value="<?php echo $instance->get_config( 'fc_read_more', $instance->get_config_default( 'fc_read_more' ) ); ?>" <?php checked( $instance->get_config( 'fc_read_more', $instance->get_config_default( 'fc_read_more' ) ), 1 ); ?> />
                        <span class="description"><?php _e( 'Displays a "read more" link after the post content.', 'soliloquy' ); ?></span>
                    </td>
                </tr>
                <tr id="soliloquy-config-fc-read-more-text-box">
                    <th scope="row">
                        <label for="soliloquy-config-fc-read-more-text"><?php _e( 'Read More Text', 'soliloquy' ); ?></label>
                    </th>
                    <td>
                        <input id="soliloquy-config-fc-read-more-text" type="text" name="_soliloquy[fc_read_more_text]" value="<?php echo $instance->get_config( 'fc_read_more_text', $instance->get_config_default( 'fc_read_more_text' ) ); ?>" />
                        <span class="description"><?php _e( 'Sets the read more link text.', 'soliloquy' ); ?></span>
                    </td>
                </tr>
                <tr id="soliloquy-config-fc-fallback-box">
                    <th scope="row">
                        <label for="soliloquy-config-fc-fallback"><?php _e( 'Fallback Image URL', 'soliloquy-fc' ); ?></label>
                    </th>
                    <td>
                        <input id="soliloquy-config-fc-fallback" type="text" name="_soliloquy[fc_fallback]" value="<?php echo $instance->get_config( 'fc_fallback', $instance->get_config_default( 'fc_fallback' ) ); ?>" />
                        <p class="description"><?php _e( 'This image URL is used if no image URL can be found for a post.', 'soliloquy-fc' ); ?></p>
                    </td>
                </tr>
                <?php do_action( 'soliloquy_fc_box', $post ); ?>
            </tbody>
        </table>
    </div>
    <?php

}

/**
 * Saves the addon settings.
 *
 * @since 1.0.0
 *
 * @param array $settings  Array of settings to be saved.
 * @param int $post_id     The current post ID.
 * @return array $settings Amended array of settings to be saved.
 */
function soliloquy_fc_save( $settings, $post_id ) {

    // If not saving a featured content slider, do nothing.
    if ( ! isset( $_POST['_soliloquy']['type_fc'] ) ) {
        return $settings;
    }

    // Save the settings.
    $settings['config']['fc_post_types']       = stripslashes_deep( $_POST['_soliloquy']['fc_post_types'] );
    $settings['config']['fc_terms']            = isset( $_POST['_soliloquy']['fc_terms'] ) ? stripslashes_deep( $_POST['_soliloquy']['fc_terms'] ) : array();
    $settings['config']['fc_query']            = preg_replace( '#[^a-z0-9-_]#', '', $_POST['_soliloquy']['fc_query'] );
    $settings['config']['fc_inc_ex']           = isset( $_POST['_soliloquy']['fc_inc_ex'] ) ? stripslashes_deep( $_POST['_soliloquy']['fc_inc_ex'] ) : array();
    $settings['config']['fc_orderby']          = preg_replace( '#[^a-z0-9-_]#', '', $_POST['_soliloquy']['fc_orderby'] );
    $settings['config']['fc_order']            = esc_attr( $_POST['_soliloquy']['fc_order'] );
    $settings['config']['fc_number']           = absint( $_POST['_soliloquy']['fc_number'] );
    $settings['config']['fc_offset']           = absint( $_POST['_soliloquy']['fc_offset'] );
    $settings['config']['fc_status']           = preg_replace( '#[^a-z0-9-_]#', '', $_POST['_soliloquy']['fc_status'] );
    $settings['config']['fc_post_url']         = isset( $_POST['_soliloquy']['fc_post_url'] ) ? 1 : 0;
    $settings['config']['fc_post_title']       = isset( $_POST['_soliloquy']['fc_post_title'] ) ? 1 : 0;
    $settings['config']['fc_post_title_link']  = isset( $_POST['_soliloquy']['fc_post_title_link'] ) ? 1 : 0;
    $settings['config']['fc_content_type']     = preg_replace( '#[^a-z0-9-_]#', '', $_POST['_soliloquy']['fc_content_type'] );
    $settings['config']['fc_content_length']   = absint( $_POST['_soliloquy']['fc_content_length'] );
    $settings['config']['fc_content_ellipses'] = isset( $_POST['_soliloquy']['fc_content_ellipses'] ) ? 1 : 0;
    $settings['config']['fc_read_more']        = isset( $_POST['_soliloquy']['fc_read_more'] ) ? 1 : 0;
    $settings['config']['fc_read_more_text']   = trim( strip_tags( $_POST['_soliloquy']['fc_read_more_text'] ) );
    $settings['config']['fc_fallback']         = esc_url( $_POST['_soliloquy']['fc_fallback'] );
    return $settings;

}

/**
 * Flushes the Featured Content data caches globally on save of any post.
 *
 * @since 1.0.0
 *
 * @param int $post_id The current post ID.
 * @param object $post The current post object.
 */
function soliloquy_fc_flush_global_caches( $post_id, $post ) {

    // Run a few checks before clearing the caches globally.
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }

    if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
        return;
    }

    if ( defined( 'DOING_CRON' ) && DOING_CRON ) {
        return;
    }

    if ( wp_is_post_revision( $post_id ) ) {
        return;
    }

    // Bail out if the user doesn't have the correct permissions to update the post.
    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }

    // Good to go - let's flush the caches.
    $sliders = Soliloquy::get_instance()->_get_sliders();
    if ( $sliders ) {
        foreach ( $sliders as $slider ) {
            // Delete the ID cache.
            delete_transient( '_sol_cache_' . $slider['id'] );
            delete_transient( '_sol_fc_' . $slider['id'] );

            // Delete the slug cache.
            $slug = get_post_meta( $slider['id'], '_sol_slider_data', true );
            if ( ! empty( $slug['config']['slug'] ) ) {
                delete_transient( '_sol_cache_' . $slug['config']['slug'] );
                delete_transient( '_sol_fc_' . $slug['config']['slug'] );
            }
        }
    }

    // Flush the cache for the slider for this post too.
    delete_transient( '_sol_cache_' . $post_id );
    delete_transient( '_sol_fc_' . $post_id );

}

/**
 * Flushes the Featured Content data caches on save.
 *
 * @since 1.0.0
 *
 * @param int $post_id The current post ID.
 * @param string $slug The current slider slug.
 */
function soliloquy_fc_flush_caches( $post_id, $slug ) {

    delete_transient( '_sol_fc_' . $post_id );
    delete_transient( '_sol_fc_' . $slug );

}

/**
 * Filters the data to pull images from Featured Content for Featured Content sliders.
 *
 * @since 1.0.0
 *
 * @param array $data  Array of slider data.
 * @param int $id      The slider ID.
 * @return array $data Amended array of slider data.
 */
function soliloquy_fc_data( $data, $id ) {

    // Return early if not an Featured Content slider.
    $instance = Soliloquy_Shortcode::get_instance();
    if ( 'fc' !== $instance->get_config( 'type', $data ) ) {
        return $data;
    }

    // Prepare and run the query for grabbing our featured content.
    $query   = soliloquy_fc_prepare_query( $id, $data );
    $fc_data = soliloquy_fc_get_data( $query, $id, $data );

    // If there was an error with the query, simply return default data.
    if ( ! $fc_data ) {
        return $data;
    }

    // Insert the featured content data into the slider data.
    $data = soliloquy_fc_insert_data( $data, $fc_data );

    // Return the modified data.
    return apply_filters( 'soliloquy_fc_data', $data, $id );

}

/**
 * Prepares the query args for the featured content query.
 *
 * @since 1.0.0
 *
 * @param mixed $id   The current slider ID.
 * @param array $data Array of slider data.
 * @return array      Array of query args for the featured content slider.
 */
function soliloquy_fc_prepare_query( $id, $data ) {

    // Prepare vairables.
    $instance   = Soliloquy_Shortcode::get_instance();
    $query_args = array();

    // Set any default query args that are not appropriate for our query.
    $query_args['post_parent']    = null;
    $query_args['post_mime_type'] = null;
    $query_args['cache_results']  = false;
    $query_args['no_found_rows']  = true;

    // Set our user defined query args.
    $query_args['post_type']      = (array) $instance->get_config( 'fc_post_types', $data );
    $query_args['posts_per_page'] = $instance->get_config( 'fc_number', $data );
    $query_args['orderby']        = $instance->get_config( 'fc_orderby', $data );
    $query_args['order']          = $instance->get_config( 'fc_order', $data );
    $query_args['offset']         = $instance->get_config( 'fc_offset', $data );
    $query_args['post_status']    = $instance->get_config( 'fc_status', $data );

    // Set post__in or post__not_in query params.
    $inc_ex = $instance->get_config( 'fc_inc_ex', $data );
    if ( ! empty( $inc_ex ) ) {
        $exception              = 'include' == $instance->get_config( 'fc_query', $data ) ? 'post__in' : 'post__not_in';
        $query_args[$exception] = array_map( 'absint', (array) $inc_ex );
    }

    // Set our custom taxonomy query parameters if necessary.
    $terms = $instance->get_config( 'fc_terms', $data );
    if ( ! empty( $terms ) ) {
        // Set our taxonomy relation parameter.
        $relation['relation'] = 'AND';

        // Loop through each term and parse out the data.
        foreach ( $terms as $term ) {
            $data         = explode( '|', $term );
            $taxonomies[] = $data[0];
            $terms[]      = $data;
        }

        // Loop through each taxonony and build out the taxonomy query.
        foreach ( array_unique( $taxonomies ) as $tax ) {
            $tax_terms = array();
            foreach ( $terms as $term ) {
                if ( $tax == $term[0] ) {
                    $tax_terms[] = $term[2];
                }
            }
            $relation[] = array(
                'taxonomy'         => $tax,
                'field'            => 'slug',
                'terms'            => $tax_terms,
                'operator'         => 'IN',
                'include_children' => false,
            );
        }
        $query_args['tax_query'] = $relation;
    }

    // Allow dev to optionally allow query filters.
    $query_args['suppress_filters'] = apply_filters( 'soliloquy_fc_suppress_filters', true, $query_args, $id, $data );

    // Filter and return the query args.
    return apply_filters( 'soliloquy_fc_query_args', $query_args, $id, $data );

}

/**
 * Runs and caches the query to grab featured content data.
 *
 * @since 1.0.0
 *
 * @param array $data Array of query args.
 * @param mixed $id   The current slider ID.
 * @param array $data Array of slider data.
 * @return bool|array False if no items founds, array of data on success.
 */
function soliloquy_fc_get_data( $query, $id, $data ) {

    // If using a random selection for posts, don't cache the query.
    if ( isset( $query['orderby'] ) && 'rand' == $query['orderby'] ) {
        return maybe_unserialize( $fc_data = _soliloquy_fc_get_data( $query, $id, $data ) );
    }

    // Attempt to return the transient first, otherwise generate the new query to retrieve the data.
    if ( false === ( $fc_data = get_transient( '_sol_fc_' . $id ) ) ) {
        $fc_data = _soliloquy_fc_get_data( $query, $id, $data );
        if ( $fc_data ) {
            set_transient( '_sol_fc_' . $id, maybe_serialize( $fc_data ), DAY_IN_SECONDS );
        }
    }

    // Return the slider data.
    return maybe_unserialize( $fc_data );

}

/**
 * Performs the custom query to grab featured content if the transient doesn't exist.
 *
 * @since 1.0.0
 *
 * @param array $data Array of query args.
 * @param mixed $id   The current slider ID.
 * @param array $data Array of slider data.
 * @return array|bool Array of data on success, false on failure.
 */
function _soliloquy_fc_get_data( $query, $id, $data ) {

    // Grab the posts based on the featured content query.
    $posts = get_posts( $query );

    // If there is an error or no posts are returned, return false.
    if ( ! $posts || empty( $posts ) ) {
        return false;
    }

    // Return the post data.
    return apply_filters( 'soliloquy_fc_post_data', $posts, $query, $id, $data );

}

/**
 * Inserts the Featured Content data into the slider.
 *
 * @since 1.0.0
 *
 * @param array $data  Array of slider data.
 * @param array $fc    Array of Featured Content image data objects.
 * @return array $data Amended array of slider data.
 */
function soliloquy_fc_insert_data( $data, $fc ) {

    // Empty out the current slider data.
    $data['slider'] = array();

    // Loop through and insert the Featured Content data.
    $instance = Soliloquy_Shortcode::get_instance();
    foreach ( $fc as $i => $post ) {
        // Prepare variables.
        $id              = ! empty( $post->ID ) ? $post->ID : $i;
        $prep            = array();
        $prep['status']  = 'active';
        $prep['src']     = soliloquy_fc_get_featured_image( $post, $data );
        $prep['title']   = $prep['alt'] = ! empty( $post->post_title ) ? $post->post_title : '';
        $prep['caption'] = soliloquy_fc_get_caption( $post, $data );
        $prep['link']    = $instance->get_config( 'fc_post_url', $data ) ? get_permalink( $post->ID ) : '';
        $prep['type']    = 'image';

        // Allow image to be filtered for each image.
        $prep = apply_filters( 'soliloquy_fc_image', $prep, $fc, $data );

        // Insert the image into the slider.
        $data['slider'][$id] = $prep;
    }

    // Return and allow filtering of final data.
    return apply_filters( 'soliloquy_fc_slider_data', $data, $fc );

}

/**
 * Retrieves the featured image for the specified post.
 *
 * @since 1.0.0
 *
 * @return string The featured image URL to use for the slide.
 */
function soliloquy_fc_get_featured_image( $post, $data ) {

    // Attempt to grab the featured image for the post.
    $instance = Soliloquy_Shortcode::get_instance();
    $thumb_id = apply_filters( 'soliloquy_fc_thumbnail_id', get_post_thumbnail_id( $post->ID ), $post, $data );
    $src      = '';

    // If we have been able to get the featured image ID, return the image based on that.
    if ( $thumb_id ) {
        $size  = $instance->get_config( 'slider_size', $data );
        $image = wp_get_attachment_image_src( $thumb_id, ( 'default' !== $size ? $size : 'full' ) );
        if ( ! $image || empty( $image[0] ) ) {
            $fallback = $instance->get_config( 'fc_fallback', $data );
            if ( ! empty( $fallback ) ) {
                $src = esc_url( $fallback );
            } else {
                $src = '';
            }
        } else {
            $src = $image[0];
        }
    } else {
        // Attempt to grab the first image from the post if no featured image is set.
        preg_match_all( '|<img.*?src=[\'"](.*?)[\'"].*?>|i', get_post_field( 'post_content', $post->ID ), $matches );

        // If we have found an image, use that image, otherwise attempt the fallback URL.
        if ( ! empty( $matches[1][0] ) ) {
            $src = esc_url( $matches[1][0] );
        } else {
            $fallback = $instance->get_config( 'fc_fallback', $data );
            if ( ! empty( $fallback ) ) {
                $src = esc_url( $fallback );
            } else {
                $src = '';
            }
        }
    }

    // Return the image and allow filtering of the URL.
    return apply_filters( 'soliloquy_fc_image_src', $src, $post, $data );

}

/**
 * Retrieves the caption for the specified post.
 *
 * @since 1.0.0
 *
 * @return string The caption to use for the slide.
 */
function soliloquy_fc_get_caption( $post, $data ) {

    // Prepare variables.
    $instance = Soliloquy_Shortcode::get_instance();
    $output   = '';
    $title    = false;
    $above    = false;

    // Since our title is first, check to see if we should build that in first.
    if ( $instance->get_config( 'fc_post_title', $data ) ) {
        if ( ! empty( $post->post_title ) ) {
            $title   = true;
            $output  = apply_filters( 'soliloquy_fc_before_title', $output, $post, $data );
            $output .= '<h2 class="soliloquy-fc-title">';
                // Possibly link the title.
                if ( $instance->get_config( 'fc_post_title_link', $data ) ) {
                    $output .= '<a class="soliloquy-fc-title-link" href="' . get_permalink( $post->ID ) . '" title="' . esc_attr( $post->post_title ) . '">' . $post->post_title . '</a>';
                } else {
                    $output .= $post->post_title;
                }
            $output .= '</h2>';
            $output  = apply_filters( 'soliloquy_fc_after_title', $output, $post, $data );
        }
    }

    // Now that we have built our title, let's possibly build out our caption.
    $content = $instance->get_config( 'fc_content_type', $data );

    // If the post excerpt, build out the post excerpt caption.
    if ( 'post_excerpt' == $content ) {
        if ( ! empty( $post->post_excerpt ) ) {
            $above   = true;
            $output  = apply_filters( 'soliloquy_fc_before_caption', $output, $post, $data );
            $excerpt = apply_filters( 'soliloquy_fc_post_excerpt', $post->post_excerpt, $post, $data );
            $output .= '<div class="soliloquy-fc-content' . ( $title ? ' soliloquy-fc-title-above' : '' ) . '"><p>' . $excerpt;
            $output  = apply_filters( 'soliloquy_fc_after_caption', $output, $post, $data );
        }
    }

    // If the post content, build out the post content caption.
    if ( 'post_content' == $content ) {
        if ( ! empty( $post->post_content ) ) {
            $above    = true;
            $output   = apply_filters( 'soliloquy_fc_before_caption', $output, $post, $data );
            $pcontent = wp_trim_words( $post->post_content, $instance->get_config( 'fc_content_length', $data ), ( $instance->get_config( 'fc_content_ellipses', $data ) ? '...' : '' ) );
            $pcontent = apply_filters( 'soliloquy_fc_post_content', $pcontent, $post, $data );
            $output  .= '<div class="soliloquy-fc-content' . ( $title ? ' soliloquy-fc-title-above' : '' ) . '"><p>' . $pcontent;
            $output   = apply_filters( 'soliloquy_fc_after_caption', $output, $post, $data );
        }
    }

    // Possibly display the read more link.
    if ( $instance->get_config( 'fc_read_more', $data ) ) {
        $output  = apply_filters( 'soliloquy_fc_before_read_more', $output, $post, $data );
        $readmo  = apply_filters( 'soliloquy_fc_read_more', ' <a class="soliloquy-fc-read-more' . ( $above ? ' soliloquy-fc-content-above' : '' ) . '" href="' . get_permalink( $post->ID ) . '" title="' . esc_attr( $post->post_title ) . '">' . $instance->get_config( 'fc_read_more_text', $data ) . '</a>', $post, $data );
        $output .= ( 'post_excerpt' == $content && ! empty( $post->post_excerpt ) || 'post_content' == $content && ! empty( $post->post_content ) ) ? $readmo . '</p></div>' : $readmo;
        $output  = apply_filters( 'soliloquy_fc_after_read_more', $output, $post, $data );
    }

    // If the output is not empty, wrap it in our caption wrapper.
    if ( ! empty( $output ) ) {
        $output = '<div class="soliloquy-fc-caption">' . $output . '</div>';
    }

    // Return and apply a filter to the caption.
    return apply_filters( 'soliloquy_fc_caption', $output, $post, $data );

}

/**
 * Adds a custom slider class to denote a featured content slider.
 *
 * @since 1.0.0
 *
 * @param array $classes  Array of slider classes.
 * @param array $data     Array of slider data.
 * @return array $classes Amended array of slider classes.
 */
function soliloquy_fc_class( $classes, $data ) {

    // Return early if not an Instagram slider.
    $instance = Soliloquy_Shortcode::get_instance();
    if ( 'fc' !== $instance->get_config( 'type', $data ) ) {
        return $classes;
    }

    // Add custom Instagram class.
    $classes[] = 'soliloquy-fc-slider';
    return $classes;

}

/**
 * Callback for post types to exclude from the dropdown select box.
 *
 * @since 1.0.0
 *
 * @return array Array of post types to exclude.
 */
function soliloquy_fc_exclude_post_types() {

    $post_types = apply_filters( 'soliloquy_fc_excluded_post_types', array( 'attachment', 'soliloquy', 'envira' ) );
    return (array) $post_types;

}

/**
 * Callback for taxonomies to exclude from the dropdown select box.
 *
 * @since 1.0.0
 *
 * @return array Array of taxonomies to exclude.
 */
function soliloquy_fc_exclude_taxonomies() {

    $taxonomies = apply_filters( 'soliloquy_fc_excluded_taxonomies', array( 'nav_menu' ) );
    return (array) $taxonomies;

}

/**
 * Returns the available orderby options for the query.
 *
 * @since 1.0.0
 *
 * @return array Array of orderby data.
 */
function soliloquy_fc_orderby() {

    $orderby = array(
        array(
            'name'  => __( 'Date', 'soliloquy-fc' ),
            'value' => 'date'
        ),
        array(
            'name'  => __( 'ID', 'soliloquy-fc' ),
            'value' => 'ID'
        ),
        array(
            'name'  => __( 'Author', 'soliloquy-fc' ),
            'value' => 'author'
        ),
        array(
            'name'  => __( 'Title', 'soliloquy-fc' ),
            'value' => 'title'
        ),
        array(
            'name'  => __( 'Menu Order', 'soliloquy-fc' ),
            'value' => 'menu_order'
        ),
        array(
            'name'  => __( 'Random', 'soliloquy-fc' ),
            'value' => 'rand'
        ),
        array(
            'name'  => __( 'Comment Count', 'soliloquy-fc' ),
            'value' => 'comment_count'
        ),
        array(
            'name'  => __( 'Post Name', 'soliloquy-fc' ),
            'value' => 'name'
        ),
        array(
            'name'  => __( 'Modified Date', 'soliloquy-fc' ),
            'value' => 'modified'
        )
    );

    return apply_filters( 'soliloquy_fc_orderby', $orderby );

}

/**
 * Returns the available order options for the query.
 *
 * @since 1.0.0
 *
 * @return array Array of order data.
 */
function soliloquy_fc_order() {

    $order = array(
        array(
            'name'  => __( 'Descending Order', 'soliloquy-fc' ),
            'value' => 'DESC'
        ),
        array(
            'name'  => __( 'Ascending Order', 'soliloquy-fc' ),
            'value' => 'ASC'
        )
    );

    return apply_filters( 'soliloquy_fc_order', $order );

}

/**
 * Returns the available post status options for the query.
 *
 * @since 1.0.0
 *
 * @return array Array of post status data.
 */
function soliloquy_fc_statuses() {

    $statuses = get_post_stati( array( 'internal' => false ), 'objects' );
    return apply_filters( 'soliloquy_fc_statuses', $statuses );

}

/**
 * Returns the available content type options for the query output.
 *
 * @since 1.0.0
 *
 * @return array Array of content type data.
 */
function soliloquy_fc_content_types() {

    $types = array(
        array(
            'name'  => __( 'No Content', 'soliloquy-fc' ),
            'value' => 'none'
        ),
        array(
            'name'  => __( 'Post Content', 'soliloquy-fc' ),
            'value' => 'post_content'
        ),
        array(
            'name'  => __( 'Post Excerpt', 'soliloquy-fc' ),
            'value' => 'post_excerpt'
        )
    );

    return apply_filters( 'soliloquy_fc_content_types', $types );

}

/**
 * Refreshes the term list to show available terms for the selected post type.
 *
 * @since 1.0.0
 */
function soliloquy_fc_refresh_terms() {

    // Run a security check first.
    check_ajax_referer( 'soliloquy-fc-term-refresh', 'nonce' );

    // Die early if no post type is set.
    if ( empty( $_POST['post_type'] ) ) {
        echo json_encode( array( 'error' => true ) );
        die;
    }

    // Prepare variables.
    $taxonomies = array();
    $instance   = Soliloquy_Metaboxes::get_instance();

    // If we have more than one post type selected, we need to show terms based on it they share taxonomies.
    if ( count( $_POST['post_type'] ) > 1 ) {
        foreach ( $_POST['post_type'] as $type ) {
            $taxonomies[] = get_object_taxonomies( $type, 'objects' );
        }

        // If no taxonomies can be found, return an error.
        if ( empty( $taxonomies ) ) {
            echo json_encode( array( 'error' => true ) );
            die;
        }

        // Loop through the taxonomies and see if they share post type objects.
        $output = '';
        foreach ( $taxonomies as $array ) {
            foreach ( $array as $taxonomy ) {
                // If the post_type and object_type arrays match, they share post types.
                if ( $_POST['post_type'] == $taxonomy->object_type ) {
                    $terms = get_terms( $taxonomy );

                    $output .= '<optgroup label="' . esc_attr( $taxonomy->labels->name ) . '">';
                        foreach ( $terms as $term ) {
                            $output .= '<option value="' . esc_attr( strtolower( $taxonomy->name ) . '|' . $term->term_id . '|' . $term->slug ) . '"' . selected( strtolower( $taxonomy->name ) . '|' . $term->term_id . '|' . $term->slug, in_array( strtolower( $taxonomy->name ) . '|' . $term->term_id . '|' . $term->slug, (array) $instance->get_config( 'fc_terms', $instance->get_config_default( 'fc_terms' ) ) ) ? strtolower( $taxonomy->name ) . '|' . $term->term_id . '|' . $term->slug : '', false ) . '>' . esc_html( ucwords( $term->name ) ) . '</option>';
                        }
                    $output .= '</optgroup>';
                } else {
                    continue;
                }
            }
        }

        // Send the output back to the script. If it is empty, send back an error, otherwise send back the HTML.
        if ( empty( $output ) ) {
            echo json_encode( array( 'error' => true ) );
            die;
        } else {
            echo json_encode( $output );
            die;
        }
    } else {
        // We only have one post type. Try to grab taxonomies for it.
        foreach ( $_POST['post_type'] as $type ) {
            $taxonomies[] = get_object_taxonomies( $type, 'objects' );
        }

        // If no taxonomies can be found, return an error.
        if ( empty( $taxonomies ) ) {
            echo json_encode( array( 'error' => true ) );
            die;
        }

        // Loop through the taxonomies and build the HTML output.
        $output = '';
        foreach ( $taxonomies as $array ) {
            foreach ( $array as $taxonomy ) {
                $terms = get_terms( $taxonomy->name );

                $output .= '<optgroup label="' . esc_attr( $taxonomy->labels->name ) . '">';
                    foreach ( $terms as $term ) {
                        $output .= '<option value="' . esc_attr( strtolower( $taxonomy->name ) . '|' . $term->term_id . '|' . $term->slug ) . '"' . selected( strtolower( $taxonomy->name ) . '|' . $term->term_id . '|' . $term->slug, in_array( strtolower( $taxonomy->name ) . '|' . $term->term_id . '|' . $term->slug, (array) $instance->get_config( 'fc_terms', $instance->get_config_default( 'fc_terms' ) ) ) ? strtolower( $taxonomy->name ) . '|' . $term->term_id . '|' . $term->slug : '', false ) . '>' . esc_html( ucwords( $term->name ) ) . '</option>';
                    }
                $output .= '</optgroup>';
            }
        }

        // Send the output back to the script. If it is empty, send back an error, otherwise send back the HTML.
        if ( empty( $output ) ) {
            echo json_encode( array( 'error' => true ) );
            die;
        } else {
            echo json_encode( $output );
            die;
        }
    }

    // If we can't grab something, just send back an error.
    echo json_encode( array( 'error' => true ) );
    die;

}

/**
 * Refreshes the individual post selection list for the selected post type.
 *
 * @since 1.0.0
 */
function soliloquy_fc_refresh_posts() {

    // Run a security check first.
    check_ajax_referer( 'soliloquy-fc-refresh', 'nonce' );

    // Die early if no post type is set.
    if ( empty( $_POST['post_type'] ) ) {
        echo json_encode( array( 'error' => true ) );
        die;
    }

    // There is only going to be one post type in this array, so we can reliably grab available posts this way.
    $posts = get_posts( array( 'post_type' => $_POST['post_type'][0], 'posts_per_page' => apply_filters( 'soliloquy_fc_max_queried_posts', 500 ), 'no_found_rows' => true, 'cache_results' => false ) );

    // If we have posts, loop through them and build out the HTML output.
    if ( $posts ) {
        $instance = Soliloquy_Metaboxes::get_instance();
        $object   = get_post_type_object( $_POST['post_type'][0] );
        $output   = '<optgroup label="' . esc_attr( $object->labels->name ) . '">';
            foreach ( (array) $posts as $post ) {
                $output .= '<option value="' . absint( $post->ID ) . '"' . selected( $post->ID, in_array( $post->ID, (array) $instance->get_config( 'fc_inc_ex', $instance->get_config_default( 'fc_inc_ex' ) ) ) ? $post->ID : '', false ) . '>' . esc_html( ucwords( $post->post_title ) ) . '</option>';
            }
        $output .= '</optgroup>';

        echo json_encode( $output );
        die;
    }

    // Output an error if we can't find anything.
    echo json_encode( array( 'error' => true ) );
    die;

}