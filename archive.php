<?php
	/**
	 * The template for displaying archive pages.
	 *
	 * @link https://codex.wordpress.org/Template_Hierarchy
	 *
	 * @package WordPress
	 * @subpackage FrozenBones
	 * @since 1.0
	 * @version 1.0
	 */

	get_header();
?>
			<section id="main" class="page-archive">
				<header>
					<?php _frozen_breadcrumbs(); ?>
					<?php
						//Show the archive title.
						the_archive_title('<h1>', '</h1>' );
					?>
				</header>
				<?php
					//Show the archive description.
					the_archive_description('<section class="archive-description">', '</section>');
				?>
				<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
					<?php get_template_part('templates/post/content', get_post_format()); ?>
				<?php endwhile; else : ?>
					<article class="post-404">
						<?php _frozen_not_found(); ?>
					</article>
				<?php endif; ?>
				<footer>
					<?php _frozen_page_navi(); ?>
				</footer>
			</section>
			<?php get_sidebar(); ?>
<?php get_footer(); ?>
