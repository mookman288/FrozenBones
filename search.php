<?php get_header(); ?>
			<section id="main" class="column left eight">		
				<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
					<header>
						<?php _frozen_breadcrumbs(); ?>
					</header>
					<section id="page-<?php the_ID(); ?>">
						<header>
							<h1><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>">
								<?php the_title(); ?>
							</a></h1>
						</header>
						<section id="content">
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
					<section id="no-search-results">
						<header>
							<h1><?php _e('Sorry, no search results were found.', 'bonestheme'); ?></h1>
						</header>
						<section class="content">
							<ul>
								<li><?php _e('Double check the search for syntax errors.', 'bonestheme'); ?></li>
								<li><?php _e('Ensure that your cache is refreshed.', 'bonestheme'); ?></li>
								<li><?php _e('Try your search again: ', 'bonestheme'); ?></li>
							</ul>
							<?php get_search_form(); ?>
						</section>
						<footer>
							<p><?php _e('Still can\'t find what you\'re looking for? Return to the', 'bonestheme'); ?> <a href="<?php print(home_url()); ?>"><?php _e('homepage', 'bonestheme'); ?></a>.
						</footer>
					</section>
				<?php endif; ?>
			</section>
			<?php get_sidebar(); ?>
<?php get_footer(); ?>