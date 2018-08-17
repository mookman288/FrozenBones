<?php
	/**
	 * The template for displaying all single posts
	 *
	 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
	 *
	 * @package WordPress
	 * @subpackage FrozenBones
	 * @since 1.0
	 * @version 1.0
	 */

	get_header();
?>
			<section id="main">
				<header>
					<?php _frozen_breadcrumbs(); ?>
				</header>
			<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
				<?php get_template_part('templates/post/content', 'page'); ?>
			<?php endwhile; else : ?>
				<section id="page-not-found">
					<?php _frozen_not_found(); ?>
				</section>
			<?php endif; ?>
				<footer>
					<?php _frozen_related_posts(2); ?>
				</footer>
			</section>
			<?php get_sidebar(); ?>
<?php get_footer(); ?>
