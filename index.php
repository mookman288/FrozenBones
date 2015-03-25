<?php get_header(); ?>
			<section id="main">
				<header>
					<?php _frozen_breadcrumbs(); ?>
				</header>
				<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
					<article id="post-<?php the_ID(); ?>" role="article">
						<header>
							<h1>
								<a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>">
									<?php the_title(); ?>
								</a>
							</h1>
							<p class="card">
								<?php 
									printf(_('Posted <time datetime="%1$s" pubdate>%2$s</time> by <span class="author">%3$s</span>.'), 
									get_the_time('Y-m-j'), 
									get_the_time(get_option('date_format')), 
									_frozen_get_the_author_posts_link());
								?>
							</p>
						</header>
						<section class="content">
							<?php the_content(); ?>
						</section>
						<footer>
							<section>
								<p class="cats"><?php printf(__('Filed under %s.'), get_the_category_list(', ')); ?></p>
								<p class="tags">
									<?php 
										the_tags(sprintf('<span class="tags-title">%s</span> ', __( 'Tags:', 'bonestheme' )), 
										', ', ''); 
									?>
								</p>
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
					<article class="post-not-found">
						<?php _frozen_not_found(); ?>
					</article>
				<?php endif; ?>
			</section>
			<?php get_sidebar(); ?>
<?php get_footer(); ?>