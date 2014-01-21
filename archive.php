<?php get_header(); ?>
			<section id="main" class="column eight">
				<header>
					<?php if (is_category()) { ?>
						<h1 class="category-title"><?php _e('Categorized By', 'bonestheme'); ?> <?php single_cat_title(); ?></h1>
					<?php } elseif (is_tag()) { ?>
						<h1 class="tag-title"><?php _e('Tagged With', 'bonestheme'); ?> <?php single_tag_title(); ?></h1>
					<?php } elseif (is_author()) {
						//Declare global variables.
						global	$post;
						
						//Get the author ID.
						$author_id	=	$post -> post_author;
					?>
						<h1 class="author-title"><?php _e('Posts By', 'bonestheme'); ?> <?php the_author_meta('display_name', $author_id); ?></h1>
					<?php } elseif (is_day()) { ?>
						<h1 class="time-title day-title"><?php _e('Daily Archives For', 'bonestheme'); ?> <?php the_time('l, F jS, Y'); ?></h1>
					<?php } elseif (is_month()) { ?>
							<h1 class="time-title month-title"><?php _e('Monthly Archives For ', 'bonestheme'); ?> <?php the_time('F, Y'); ?></h1>
					<?php } elseif (is_year()) { ?>
							<h1 class="time-title year-title"><?php _e('Yearly Archives For', 'bonestheme'); ?> <?php the_time('Y'); ?></h1>
					<?php } else { ?>
						<h1><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h1>
					<?php } ?>
				</header>
				<section id="content">
					<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
						<?php if (is_page()) { ?>
							<section id="page-<?php the_ID(); ?>">
								<header>
									<h1><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h1>
								</header>
								<section id="content">
									<?php the_excerpt(); ?>
								</section>
								<footer>
									<section>
										<p class="cats"><?php printf(_('Filed under %4$s.'), get_the_category_list(', ')); ?></p>
										<p class="tags"><?php the_tags('<span class="tags-title">' . __( 'Tags:', 'bonestheme' ) . '</span> ', ', ', ''); ?></p>
									</section>
									<aside>
										<?php _frozen_page_navi(); ?>
									</aside>
								</footer>
							</section>
						<?php } else { ?>
							<article id="post-<?php the_ID(); ?>" role="article">
								<header>
									<h2><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>
									<p class="card"><?php printf(_('Posted <time class="updated" datetime="%1$s" pubdate>%2$s</time> by <span class="author">%3$s</span>.', 'bonestheme' ), get_the_time('Y-m-j'), get_the_time(get_option('date_format')), bones_get_the_author_posts_link()); ?></p>
								</header>
								<section>
									<?php the_post_thumbnail('frozen-thumb-280'); ?>
									<?php the_excerpt(); ?>
								</section>
								<footer>
									<section>
										<p class="cats"><?php printf(_('Filed under %4$s.'), get_the_category_list(', ')); ?></p>
										<p class="tags"><?php the_tags('<span class="tags-title">' . __('Tags:', 'bonestheme') . '</span> ', ', ', ''); ?></p>
									</section>
									<aside>
										<?php _frozen_page_navi(); ?>
									</aside>
								</footer>
							</article>
						<?php } ?>
					<?php endwhile; else : ?>
						<section id="page-not-found">
						<header>
							<h2><?php _e('Sorry, nothing was found.', 'bonestheme'); ?></h2>
						</header>
						<section id="content">
							<p><?php _e('There were no results. Please try the following: ', 'bonestheme'); ?></p>
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
				<footer>
					<?php _frozen_page_navi(); ?>
				</footer>
			<?php get_sidebar(); ?>
<?php get_footer(); ?>