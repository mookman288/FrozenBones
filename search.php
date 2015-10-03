<?php get_header(); ?>
<?php
	//Declare global variables. 
	global $wp_query;
	
	//Declare variables. 
	$total	=	$wp_query -> found_posts;
	$total	=	($total !== 1) ? __("$total results found", 'bones-theme') : __("$total result found", 'bones-theme');
	$header	=	(!isset($_GET['s'])) ? "$total." : "$total " . __(sprintf("searching for <em>%s</em>.", 
			stripslashes($_GET['s'])), 'bones-theme');
?>
			<section id="main">		
				<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
					<header>
						<?php _frozen_breadcrumbs(); ?>
					</header>
					<article id="post-<?php the_ID(); ?>" role="article">
						<header>
							<h3>
								<a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>">
									<?php the_title(); ?>
								</a>
							</h3>
							<p class="card">
								<?php 
									printf(_('Posted <time datetime="%1$s" pubdate>%2$s</time> by <span class="author">%3$s</span>.'), 
									get_the_time('Y-m-j'), 
									get_the_time(get_option('date_format')), 
									_frozen_get_the_author_posts_link());
								?>
							</p>
							<?php if (has_post_thumbnail()) { ?>
								<div class="featured-image">
									<?php the_post_thumbnail('full'); ?>
								</div>
							<?php } ?>
						</header>
						<section class="content">
							<?php the_content(); ?>
						</section>
						<?php if (strlen(get_the_category_list()) > 1 || has_tag()) { ?>
							<footer>
								<section>
									<?php if (strlen(get_the_category_list()) > 1) { ?>
										<p class="cats"><?php printf(__('Filed under %s.'), get_the_category_list(', ')); ?></p>
									<?php } ?>
									<?php if (has_tag()) { ?>
										<p class="tags">
											<?php 
												the_tags(sprintf('<span class="tags-title">%s</span> ', __( 'Tags:', 'bonestheme' )), 
												', ', ''); 
											?>
										</p>
									<?php } ?>
								</section>
							</footer>
						<?php } ?>
					</article>
				<?php endwhile; else : ?>
					<section class="no-search-results">
						<?php _frozen_not_found('Sorry, no search results were found.'); ?>
					</section>
				<?php endif; ?>
				<footer>
					<?php _frozen_page_navi(); ?>
				</footer>
			</section>
			<?php get_sidebar(); ?>
<?php get_footer(); ?>