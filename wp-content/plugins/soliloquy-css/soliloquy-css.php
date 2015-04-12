<?php
/**
 * Plugin Name: Soliloquy - CSS Addon
 * Plugin URI:  http://soliloquywp.com
 * Description: Enables custom CSS output for Soliloquy sliders.
 * Author:      Thomas Griffin
 * Author URI:  http://thomasgriffinmedia.com
 * Version:     2.1.1
 * Text Domain: soliloquy-css
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
define( 'SOLILOQUY_CUSTOM_CSS_PLUGIN_NAME', 'Soliloquy - CSS Addon' );
define( 'SOLILOQUY_CUSTOM_CSS_PLUGIN_VERSION', '2.1.1' );
define( 'SOLILOQUY_CUSTOM_CSS_PLUGIN_SLUG', 'soliloquy-css' );

add_action( 'plugins_loaded', 'soliloquy_custom_css_plugins_loaded' );
/**
 * Ensures the full Soliloquy plugin is active before proceeding.
 *
 * @since 1.0.0
 *
 * @return null Return early if Soliloquy is not active.
 */
function soliloquy_custom_css_plugins_loaded() {

    // Bail if the main class does not exist.
    if ( ! class_exists( 'Soliloquy' ) ) {
        return;
    }

    // Fire up the addon.
    add_action( 'soliloquy_init', 'soliloquy_custom_css_plugin_init' );

}

/**
 * Loads all of the addon hooks and filters.
 *
 * @since 1.0.0
 */
function soliloquy_custom_css_plugin_init() {

    add_action( 'soliloquy_updater', 'soliloquy_custom_css_updater' );
    add_filter( 'soliloquy_defaults', 'soliloquy_custom_css_defaults', 10, 2 );
    add_action( 'soliloquy_misc_box', 'soliloquy_custom_css_setting', 999 );
    add_filter( 'soliloquy_save_settings', 'soliloquy_custom_css_save', 10, 2 );
    add_filter( 'soliloquy_output_start', 'soliloquy_custom_css_output', 0, 2 );

}

/**
 * Initializes the addon updater.
 *
 * @since 1.0.0
 *
 * @param string $key The user license key.
 */
function soliloquy_custom_css_updater( $key ) {

    $args = array(
        'plugin_name' => SOLILOQUY_CUSTOM_CSS_PLUGIN_NAME,
        'plugin_slug' => SOLILOQUY_CUSTOM_CSS_PLUGIN_SLUG,
        'plugin_path' => plugin_basename( __FILE__ ),
        'plugin_url'  => trailingslashit( WP_PLUGIN_URL ) . SOLILOQUY_CUSTOM_CSS_PLUGIN_SLUG,
        'remote_url'  => 'http://soliloquywp.com/',
        'version'     => SOLILOQUY_CUSTOM_CSS_PLUGIN_VERSION,
        'key'         => $key
    );
    $soliloquy_custom_css_updater = new Soliloquy_Updater( $args );

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
function soliloquy_custom_css_defaults( $defaults, $post_id ) {

    // Empty by default.
    $defaults['custom_css'] = '';
    return $defaults;

}

/**
 * Adds addon setting to the Misc tab.
 *
 * @since 1.0.0
 *
 * @param object $post The current post object.
 */
function soliloquy_custom_css_setting( $post ) {

    $instance = Soliloquy_Metaboxes::get_instance();
    ?>
    <tr id="soliloquy-config-custom-css-box">
        <th scope="row">
            <label for="soliloquy-config-custom-css"><?php _e( 'Custom Slider CSS', 'soliloquy-css' ); ?></label>
        </th>
        <td>
            <textarea id="soliloquy-config-custom-css" rows="10" cols="75" name="_soliloquy[custom_css]" placeholder="<?php printf( __( 'e.g. %s', 'soliloquy-css' ), '#soliloquy-container-' . $post->ID . ' { margin-bottom: 20px; }' ); ?>"><?php echo $instance->get_config( 'custom_css', $instance->get_config_default( 'custom_css' ) ); ?></textarea>
            <p class="description"><?php printf( __( 'All custom CSS for this slider should start with <code>%s</code>. <a href="%s" title="Need help?" target="_blank">Help?</a>', 'soliloquy-css' ), '#soliloquy-container-' . $post->ID, 'http://soliloquywp.com/docs/css-addon/' ); ?></p>
        </td>
    </tr>
    <?php

}

/**
 * Saves the addon setting.
 *
 * @since 1.0.0
 *
 * @param array $settings  Array of settings to be saved.
 * @param int $pos_tid     The current post ID.
 * @return array $settings Amended array of settings to be saved.
 */
function soliloquy_custom_css_save( $settings, $post_id ) {

    $settings['config']['custom_css'] = isset( $_POST['_soliloquy']['custom_css'] ) ? trim( esc_html( $_POST['_soliloquy']['custom_css'] ) ) : '';
    return $settings;

}

/**
 * Outputs the custom CSS to the specific slider.
 *
 * @since 1.0.0
 *
 * @param string $slider  The HTML output for the slider.
 * @param array $data     Data for the slider.
 * @return string $slider Amended slider HTML.
 */
function soliloquy_custom_css_output( $slider, $data ) {

    // If there is no style, return the default slider HTML.
    $instance = Soliloquy_Shortcode::get_instance();
    if ( ! $instance->get_config( 'custom_css', $data ) ) {
    	return $slider;
    }

    // Build out the custom CSS.
    $style = '<style type="text/css">' . $instance->minify( html_entity_decode( $data['config']['custom_css'] ) ) . '</style>';

    // Return the style prepended to the slider.
    return $style . $slider;

}