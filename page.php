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
					</section>
				<?php endwhile; else : ?>
					<section id="page-not-found">
						<?php _frozen_not_found(); ?>
					</section>
				<?php endif; ?>
				<footer>
					<?php _frozen_page_navi(); ?>
				</footer>
			</section>
			<?php get_sidebar(); ?>
<?php get_footer(); ?>