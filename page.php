<?php get_header(); ?>
			<section id="main" class="column left eight">
				<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
					<header>
						<?php _frozen_breadcrumbs(); ?>
					</header>
					<section id="page-<?php the_ID(); ?>">
						<header>
							<h1><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h1>
						</header>
						<section id="content">
							<?php the_content(); ?>
						</section>
						<footer>
							<section>
								<p class="cats"><?php filed_under(); ?></p>
								<p class="tags"><?php the_tags('<span class="tags-title">' . __( 'Tags:', 'bonestheme' ) . '</span> ', ', ', ''); ?></p>
							</section>
							<aside>
								<?php _frozen_page_navi(); ?>
							</aside>
						</footer>
					</section>
					<footer>
						<?php comments_template(); ?>
					</footer>
				<?php endwhile; else : ?>
					<section id="page-not-found">
						<header>
							<h1><?php _e('404: Not Found!', 'bonestheme'); ?></h1>
						</header>
						<section class="content">
							<p><?php _e('This page does not exist. Please try the following: ', 'bonestheme'); ?></p>
							<ul>
								<li><?php _e('Double check the address for syntax errors.', 'bonestheme'); ?></li>
								<li><?php _e('Ensure that your cache is refreshed.', 'bonestheme'); ?></li>
								<li><?php _e('Use the search form below:', 'bonestheme'); ?></li>
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
