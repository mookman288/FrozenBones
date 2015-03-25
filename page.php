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
					</section>
					<footer>
						<?php comments_template(); ?>
					</footer>
				<?php endwhile; else : ?>
					<section id="page-not-found">
						<?php _frozen_not_found(); ?>
					</section>
				<?php endif; ?>
			</section>
			<?php get_sidebar(); ?>
<?php get_footer(); ?>