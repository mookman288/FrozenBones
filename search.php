<?php get_header(); ?>
			<section id="main">		
				<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
					<header>
						<?php _frozen_breadcrumbs(); ?>
					</header>
					<section id="page-<?php the_ID(); ?>">
						<header>
							<h1>
								<a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>">
									<?php the_title(); ?>
								</a>
							</h1>
						</header>
						<section class="content">
							<?php the_content(); ?>
							<?php get_search_form(); ?>
						</section>
						<footer>
							<?php _frozen_page_navi(); ?>
						</footer>
					</section>
					<footer>
						<?php comments_template(); ?>
					</footer>
				<?php endwhile; else : ?>
					<section class="no-search-results">
						<?php _frozen_not_found('Sorry, no search results were found.'); ?>
					</section>
				<?php endif; ?>
			</section>
			<?php get_sidebar(); ?>
<?php get_footer(); ?>