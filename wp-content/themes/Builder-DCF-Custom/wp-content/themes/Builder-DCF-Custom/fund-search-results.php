<?php

/* Template Name: SearchWP Fund Search Results */

	// perform the search


function cmp1($a, $b)  /* 38solutions ticket ticket #189 */
{
	return strcmp($a->post_title, $b->post_title);
}	
	

global $post;
 
// retrieve our search query if applicable
$query = isset( $_REQUEST['swpquery'] ) ? sanitize_text_field( $_REQUEST['swpquery'] ) : '';
 
// retrieve our pagination if applicable
$swppg = isset( $_REQUEST['swppg'] ) ? absint( $_REQUEST['swppg'] ) : 1;
 
// begin SearchWP Supplemental Search Engine results retrieval

usort($posts, "cmp1"); /* 38solutions ticket ticket #189 */

if( class_exists( 'SearchWP' ) ) {
	// instantiate SearchWP
	$engine = SearchWP::instance();
	$supplementalSearchEngineName = 'fund_search'; // taken from the SearchWP settings screen
 
	// set up custom posts per page
	function mySearchEnginePostsPerPage() {
		return 2000; // 2000 posts per page
	}
	add_filter( 'searchwp_posts_per_page', 'mySearchEnginePostsPerPage' );
	
	$posts = $engine->search( $supplementalSearchEngineName, $query, $swppg );
	
	
	// set up pagination
	$prevPage = $swppg > 1 ? $swppg - 1 : false;
	$nextPage = $swppg < $engine->maxNumPages ? $swppg + 1 : false;
}
// end SearchWP Supplemental Search Engine results retrieval


get_header(); ?>
 
<div id="main-content" class="main-content">
	<div id="primary" class="content-area">
		<div id="content" class="site-content" role="main">
		<br></br>
 
			<!-- begin search form -->
			<div class="search-box">
				<form role="search" method="get" class="search-form" action="<?php echo get_permalink(); ?>">
					<label>
						<span class="screen-reader-text">Search for:</span>
						<input type="search" class="search-text-box" placeholder="Fund …" value="" name="swpquery" title="Search for:">
					</label>
					<input type="submit" class="search-submit" value="Search">
				</form>
			</div>
			<!-- end search form -->
 
			<?php if( !empty( $query ) ) : ?>
				<!-- <?php	usort($posts, "cmp1"); ?> -->
				<header class="page-header">
					<h1 class="page-title">Search Results</h1>
				</header>
 
				<?php if( !empty( $posts ) ) : ?>
					<?php /* The Loop (start) */ ?>
					 
					<?php foreach ( $posts as $post ): setup_postdata( $post ); ?>
						<div class="search-results">
							<h3>
								<a href="<?php the_permalink(); ?>">
									<?php the_title(); ?>
								</a>
							</h3>
							<?php the_excerpt(); ?>
						</div>		
					<?php endforeach; ?>
					<?php /* The Loop (end) */ ?>
				<?php else: ?>
					<p>We’re sorry, but no funds match your search term:  <strong><?php echo $query; ?></strong></p>
					<p>Please try again, or call 302.504.5226 for assistance.</p>
				<?php endif; ?>
 
				<!-- begin pagination -->
				<?php if( $engine->maxNumPages > 1 ) : ?>
				  <nav class="navigation paging-navigation" role="navigation">
					<div id="nav-below" class="navigation loop-utility loop-utility-below">
					<!-- <div class="nav-links"> -->
					  <?php if( $prevPage ) : ?>
						<div class="nav-previous">
						  <a href="<?php echo get_permalink( 514 ); ?>?swpquery=<?php echo urlencode( $query ); ?>&amp;swppg=<?php echo $prevPage; ?>"><span class="meta-nav">&larr;</span> Previous</a>
						</div>
					  <?php endif; ?>
					  <?php if( $nextPage ) : ?>
						<div class="nav-next">
						  <a href="<?php echo get_permalink( 514 ); ?>?swpquery=<?php echo urlencode( $query ); ?>&amp;swppg=<?php echo $nextPage; ?>">Next <span class="meta-nav">&rarr;</span></a>
						</div>
					  <?php endif; ?>
					</div><!-- .nav-links -->
				  </nav><!-- .navigation -->
				<?php endif; ?>
				<!-- end pagination -->
 
			<?php endif; ?>
 
		</div><!-- #content -->
	</div><!-- #primary -->
	<?php get_sidebar( 'content' ); ?>
</div><!-- #main-content -->
 
<?php
get_sidebar();
get_footer();
 