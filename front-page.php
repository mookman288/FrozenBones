<?php
	/**
	 * The front page template file
	 *
	 * If the user has selected a static page for their homepage, this is what will
	 * appear.
	 *
	 * Learn more: https://codex.wordpress.org/Template_Hierarchy
	 *
	 * @package WordPress
	 * @subpackage FrozenBones
	 * @since 1.0
	 * @version 1.0
	 */

//If this is the static front page.
if (get_option('show_on_front') != 'posts') {
	get_header();
?>
			<section id="main">
			<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
				<?php get_template_part('templates/page/content', 'front-page'); ?>
			<?php endwhile; else : ?>
				<section id="page-not-found">
					<?php _frozen_not_found(); ?>
				</section>
			<?php endif; ?>
			</section>
			<?php get_sidebar(); ?>
<?php get_footer(); ?>
<?php } else { ?>
	<?php require_once(get_template_directory() . '/index.php'); ?>
<?php } ?>