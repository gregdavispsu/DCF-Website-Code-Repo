<?php
/*
Plugin Name: SearchWP Shortcodes
Plugin URI: https://searchwp.com/
Description: Provides Shortcodes that generate both search forms and results pages for SearchWP search engines
Version: 1.2
Author: Jonathan Christopher
Author URI: https://searchwp.com/
Author URI: https://searchwp.com/
*/

if( !defined( 'ABSPATH' ) ) die();

class SearchWP_Shortcodes {

	public $query   = '';
	public $page    = 1;
	public $results = array();
	public $engine  = 'default';

	function __construct() {
		$this->page     = isset( $_REQUEST['swppg'] ) ? absint( $_REQUEST['swppg'] ) : 1;

		add_shortcode( 'searchwp_search_form',                  array( $this, 'search_form_output' ) );
		add_shortcode( 'searchwp_search_results_pagination',    array( $this, 'search_results_pagination_output' ) );
		add_shortcode( 'searchwp_search_results_none',          array( $this, 'search_results_none_output' ) );
		add_shortcode( 'searchwp_search_results',               array( $this, 'search_results_output' ) );
		add_shortcode( 'searchwp_search_result_link',           array( $this, 'search_result_link_output' ) );
		add_shortcode( 'searchwp_search_result_excerpt',        array( $this, 'search_result_excerpt_output' ) );
	}

	function maybe_set_search_query( $var = 'swpquery' ) {
		if( empty( $this->query ) ) {
			$var = sanitize_text_field( $var );
			$this->query = isset( $_REQUEST[$var] ) ? sanitize_text_field( $_REQUEST[$var] ) : '';
		}
	}

	function search_form_output( $atts ) {
		extract( shortcode_atts( array(
					'target'        => '',
					'engine'        => 'default',
					'var'           => 'swpquery',
					'button_text'   => __( 'Search' )
				), $atts ) );

		$engine         = esc_attr( $engine );
		$var            = esc_attr( $var );
		$button_text    = esc_attr( $button_text );

		$this->maybe_set_search_query( $var );
		$query = esc_attr( $this->query );

		ob_start(); ?>
		<div class="searchwp-search-form searchwp-supplemental-search-form">
			<form role="search" method="get" id="searchform" class="searchform" action="<?php echo esc_url( $target ); ?>">
				<div>
					<label class="screen-reader-text" for="swpquery"><?php _e( 'Search for:' ); ?></label>
					<input type="text" value="<?php echo $query; ?>" name="<?php echo $var; ?>" id="<?php echo $var; ?>">
					<input type="hidden" name="engine" value="<?php echo $engine; ?>" />
					<input type="submit" id="searchsubmit" value="<?php echo $button_text; ?>">
				</div>
			</form>
		</div>
		<?php return ob_get_clean();
	}

	function search_results_output( $atts, $content = null ) {
		global $post, $searchwp, $searchwp_shortcodes_posts_per_page;

		extract( shortcode_atts( array(
					'engine'            => 'default',
					'posts_per_page'    => 10,
					'var'               => 'swpquery',
				), $atts ) );

		$searchwp_shortcodes_posts_per_page = absint( $posts_per_page );

		$this->maybe_set_search_query( $var );
		$query = esc_attr( $this->query );

		if( class_exists( 'SearchWP' ) ) {
			$supplementalSearchEngineName = sanitize_text_field( $engine );

			// set up custom posts per page
			function searchwp_shortcodes_posts_per_page() {
				global $searchwp_shortcodes_posts_per_page;
				return $searchwp_shortcodes_posts_per_page;
			}
			add_filter( 'searchwp_posts_per_page', 'searchwp_shortcodes_posts_per_page' );

			// perform the search
			$this->results = $searchwp->search( $supplementalSearchEngineName, $query, $this->page );
		}

		ob_start();
		if( ! empty( $query ) && ! empty( $this->results ) ) {
			foreach ( $this->results as $post ) {
				setup_postdata( $post );
				echo do_shortcode( $content );
			}
			wp_reset_postdata();
		}
		return ob_get_clean();
	}

	function search_result_link_output( $atts ) {
		global $post;

		extract( shortcode_atts( array(
					'direct' => 'true',
				), $atts ) );

		$direct = 'true' != strtolower( (string) $direct ) ? false : true;

		if( $direct && isset( $post->post_type ) && 'attachment' == $post->post_type ) {
			$permalink = wp_get_attachment_url( $post->ID );
		} else {
			$permalink = get_permalink();
		}

		ob_start();
		echo '<a href="' . $permalink . '">' . get_the_title() . '</a>';
		return ob_get_clean();
	}

	function search_result_excerpt_output() {
		global $post;
		ob_start();
		the_excerpt();
		return ob_get_clean();
	}

	function search_results_none_output( $atts, $content = null ) {
		ob_start();
		if( ! empty( $this->query ) && empty( $this->results ) && ! empty( $content ) ) {
			echo $content;
		}
		return ob_get_clean();
	}

	function search_results_pagination_output( $atts, $content = null ) {
		global $post, $searchwp;

		extract( shortcode_atts( array(
					'engine'    => 'default',
					'direction' => 'prev',
					'link_text' => __( 'More' ),
					'var'       => 'swpquery',
				), $atts ) );

		$engine     = esc_attr( $engine );
		$direction  = esc_attr( $direction );
		$link_text  = esc_attr( $link_text );
		$var        = esc_attr( $var );

		$this->maybe_set_search_query( $var );
		$query = esc_attr( $this->query );

		if( 'next' != strtolower( $direction ) ) {
			$direction = 'prev';
		}

		$prevPage = $this->page > 1 ? $this->page - 1 : false;
		$nextPage = $this->page < $searchwp->maxNumPages ? $this->page + 1 : false;

		ob_start(); ?>
		<?php if( $searchwp->maxNumPages > 1 ) : ?>
			<?php if( 'prev' == strtolower( $direction ) ) : ?>
				<?php if( $prevPage ) : ?>
					<div class="nav-previous">
						<a href="<?php echo get_permalink(); ?>?<?php echo $var; ?>=<?php echo urlencode( $query ); ?>&amp;swppg=<?php echo $prevPage; ?>&amp;engine=<?php echo $engine; ?>"><?php echo $link_text; ?></a>
					</div>
				<?php endif; ?>
			<?php else: ?>
				<?php if( $nextPage ) : ?>
					<div class="nav-next">
						<a href="<?php echo get_permalink(); ?>?<?php echo $var; ?>=<?php echo urlencode( $query ); ?>&amp;swppg=<?php echo $nextPage; ?>&amp;engine=<?php echo $engine; ?>"><?php echo $link_text; ?></a>
					</div>
				<?php endif; ?>
			<?php endif; ?>
		<?php endif; ?>
		<?php return ob_get_clean();
	}

}

new SearchWP_Shortcodes();
