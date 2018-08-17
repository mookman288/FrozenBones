					<article id="post-<?php the_ID(); ?>" role="article"<?php post_class(); ?>>
						<header>
						<?php if (!is_single() || is_home() || is_front_page()) { ?>
							<h2>
								<a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>">
									<?php the_title(); ?>
								</a>
							</h2>
						<?php } else { ?>
							<h1>
								<a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>">
									<?php the_title(); ?>
								</a>
							</h1>
						<?php } ?>
							<p class="card">
							<?php
								printf(
									_('Posted <time datetime="%1$s" pubdate>%2$s</time> by <span class="author">%3$s</span>.'),
									get_the_time('Y-m-j'),
									get_the_time(get_option('date_format')),
									_frozen_get_the_author_posts_link()
								);
							?>
							</p>
						<?php if (has_post_thumbnail() && get_the_post_thumbnail() !== '') { ?>
							<div class="featured-image">
								<?php (!is_single()) ? the_post_thumbnail('frozen-thumb-400') : the_post_thumbnail('full'); ?>
							</div>
						<?php } ?>
						</header>
						<section class="content">
							<?php if (!isset($frozenExcerpt)) { the_content(); } else { the_excerpt(); } ?>
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
					</article>