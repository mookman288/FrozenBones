					<main id="page-<?php the_ID(); ?>" role="main" <?php post_class(); ?>>
						<header>
							<h1>
								<a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>">
									<?php the_title(); ?>
								</a>
							</h1>
						<?php if (has_post_thumbnail() && get_the_post_thumbnail() !== '') { ?>
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
							<?php if (strlen(get_the_category_list()) > 1) { ?>
								<p class="cats"><?php printf(__('Filed under %s.'), get_the_category_list(', ')); ?></p>
							<?php } ?>
							<?php if (has_tag()) { ?>
								<p class="tags">
									<?php
										the_tags(sprintf('<span class="tags-title">%s</span> ', __('Tags:', 'bonestheme')),
										', ', '');
									?>
								</p>
							<?php } ?>
						</footer>
					<?php } ?>
					</main>