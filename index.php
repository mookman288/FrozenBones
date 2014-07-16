<?php get_header(); ?>
			<section id="main" class="column left eight">
				<header>
					<?php _frozen_breadcrumbs(); ?>
				</header>
				<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
					<article id="post-<?php the_ID(); ?>" role="article">
						<header>
							<h1><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h1>
							<p class="card"><?php printf(_('Posted <time class="updated" datetime="%1$s" pubdate>%2$s</time> by <span class="author">%3$s</span>.'), get_the_time('Y-m-j'), get_the_time(get_option('date_format')), _frozen_get_the_author_posts_link()); ?></p>
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
					</article>
					<footer>
						<?php comments_template(); ?>
					</footer>
				<?php endwhile; else : ?>
					<article id="post-not-found">
						<header>
							<h1><?php _e('404: Not Found!', 'bonestheme'); ?></h1>
						</header>
						<section class="content">
							<p><?php _e('This post does not exist. Please try the following: ', 'bonestheme'); ?></p>
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
					</article>
				<?php endif; ?>
			</section>
			<?php get_sidebar(); ?>
<?php get_footer(); ?>
