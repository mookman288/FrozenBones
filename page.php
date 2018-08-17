<?php
	/**
	 * The template for displaying all pages
	 *
	 * This is the template that displays all pages by default.
	 * Please note that this is the WordPress construct of pages
	 * and that other 'pages' on your WordPress site may use a
	 * different template.
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
			<section id="main">
				<header>
					<?php _frozen_breadcrumbs(); ?>
				</header>
			<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
				<?php get_template_part('templates/page/content', 'page'); ?>
			<?php endwhile; else : ?>
				<section id="page-not-found">
					<?php _frozen_not_found(); ?>
				</section>
			<?php endif; ?>
			</section>
			<?php get_sidebar(); ?>
<?php get_footer(); ?>