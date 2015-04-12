<?php

function render_content() {
	
?>
	<?php if ( have_posts() ) : ?>
		<div class="loop">
			<div class="loop-content">
				<?php while ( have_posts() ) : // The Loop ?>
					<?php the_post(); ?>
					<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
						<!-- title, meta, and date info -->
						<div class="entry-header clearfix">
					
								<?php if ( has_post_thumbnail() ) : ?>
									<div class="it-featured-image">
										<a href="<?php the_permalink(); ?>">
											<?php the_post_thumbnail( 'index_thumbnail', array( 'class' => 'index-thumbnail' ) ); ?>
										</a>
									</div>
									
								<div class="title-meta-wrapper">
									<h3 class="entry-title clearfix">
										<!-- Use this instead? <h3 id="post-<?php the_ID(); ?>"><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title(); ?>"><?php the_title(); ?></a></h3> -->
										<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
									</h3>
									
		
									<div class="entry-meta-wrapper clearfix">
										<div class="entry-meta">
											<?php printf( __( 'Posted by %s', 'it-l10n-Builder-Everett' ), '<span class="author">' . builder_get_author_link() . '</span>&nbsp;' ); ?>
										</div>
										
										<div class="entry-meta date">
											<span class="weekday"> &middot; <?php the_time( 'l' ); ?><span class="weekday-comma">,</span></span>
											<span class="month"><?php the_time( 'F' ); ?></span>
											<span class="day"><?php the_time( 'j' ); ?><span class="day-suffix"><?php the_time( 'S' ); ?></span><span class="day-comma">,</span></span>
											<span class="year"><?php the_time( 'Y' ); ?></span>&nbsp;
										</div>
										
										<div class="entry-meta">
											<?php do_action( 'builder_comments_popup_link', '<span class="comments">&middot; ', '</span>', __( '%s', 'it-l10n-Builder-Everett' ), __( 'No Comments', 'it-l10n-Builder-Everett' ), __( '1 Comment', 'it-l10n-Builder-Everett' ), __( '% Comments', 'it-l10n-Builder-Everett' ) ); ?>
										</div>
									</div>
								</div>									
									
								<?php else: ?>
								
								<div class="title-meta-wrapper-no-image">
									<h3 class="entry-title clearfix">
										<!-- Use this instead? <h3 id="post-<?php the_ID(); ?>"><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title(); ?>"><?php the_title(); ?></a></h3> -->
										<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
									</h3>
									
		
									<div class="entry-meta-wrapper clearfix">
										<div class="entry-meta">
											<?php printf( __( 'Posted by %s', 'it-l10n-Builder-Everett' ), '<span class="author">' . builder_get_author_link() . '</span>&nbsp;' ); ?>
										</div>
										
										<div class="entry-meta date">
											<span class="weekday"> &middot; <?php the_time( 'l' ); ?><span class="weekday-comma">,</span></span>
											<span class="month"><?php the_time( 'F' ); ?></span>
											<span class="day"><?php the_time( 'j' ); ?><span class="day-suffix"><?php the_time( 'S' ); ?></span><span class="day-comma">,</span></span>
											<span class="year"><?php the_time( 'Y' ); ?></span>&nbsp;
										</div>
										
										<div class="entry-meta">
											<?php do_action( 'builder_comments_popup_link', '<span class="comments">&middot; ', '</span>', __( '%s', 'it-l10n-Builder-Everett' ), __( 'No Comments', 'it-l10n-Builder-Everett' ), __( '1 Comment', 'it-l10n-Builder-Everett' ), __( '% Comments', 'it-l10n-Builder-Everett' ) ); ?>
										</div>
									</div>
								</div>
									
								<?php endif; ?>
							
						</div>
						
						<!-- post content -->
						<div class="entry-content clearfix">
							<?php the_content( __( 'Read More &rarr;', 'it-l10n-Builder-Everett' ) ); ?>
						</div>
						
						<!-- categories, tags and comments -->
						<div class="entry-footer clearfix">	
							<div class="entry-meta alignleft">
								<?php wp_link_pages( array( 'before' => '<div class="entry-utility entry-pages">' . __( 'Pages:', 'it-l10n-Builder-Everett' ) . '', 'after' => '</div>', 'next_or_number' => 'number' ) ); ?>		
							</div>
						</div>
					</div>
					<!-- end .post -->					
				<?php endwhile; // end of one post ?>
			</div>
			
			<div class="loop-footer">
				<!-- Previous/Next page navigation -->
				<div class="loop-utility clearfix">
					<div class="alignleft"><?php previous_posts_link( __( '&larr; Previous Page', 'it-l10n-Builder-Everett' ) ); ?></div>
					<div class="alignright"><?php next_posts_link( __( 'Next Page &rarr;', 'it-l10n-Builder-Everett' ) ); ?></div>
				</div>
			</div>
		</div>
	<?php else : // do not delete ?>
		<?php do_action( 'builder_template_show_not_found' ); ?>
	<?php endif; // do not delete ?>
<?php
	
}

add_action( 'builder_layout_engine_render_content', 'render_content' );

do_action( 'builder_layout_engine_render', basename( __FILE__ ) );
