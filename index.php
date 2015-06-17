<?php get_header(); ?>
			<section id="main">
				<header>
					<?php _frozen_breadcrumbs(); ?>
				</header>
				<section>
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
						<footer>
							<?php comments_template(); ?>
						</footer>
					<?php endwhile; else : ?>
						<article class="post-not-found">
							<?php _frozen_not_found(); ?>
						</article>
					<?php endif; ?>
				</section>
				<footer>
					<?php _frozen_page_navi(); ?>
				</footer>
			</section>
			<?php get_sidebar(); ?>
<?php get_footer(); ?>