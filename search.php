<?php
	/**
	 * The template for displaying search results pages
	 *
	 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
	 *
	 * @package WordPress
	 * @subpackage FrozenBones
	 * @since 1.0
	 * @version 1.0
	 */

	get_header();
?>
<?php
	//Declare global variables.
	global $wp_query;

	//Declare variables.
	$total	=	$wp_query -> found_posts;
	$total	=	($total !== 1) ? __("$total results found", 'bones-theme') : __("$total result found", 'bones-theme');
	$header	=	(!isset($_GET['s'])) ? "$total." : "$total " . __(sprintf("searching for <em>%s</em>.",
			stripslashes($_GET['s'])), 'bones-theme');

	//Indicate that only the excerpts should be loaded.
	$frozenExcerpt	=	true;
?>
			<section id="main">
				<header>
					<?php _frozen_breadcrumbs(); ?>
				</header>
				<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
					<?php get_template_part('templates/post/content', get_post_format()); ?>
				<?php endwhile; else : ?>
					<section id="search-not-found">
						<?php _frozen_not_found('Sorry, no search results were found.'); ?>
					</section>
				<?php endif; ?>
				<footer>
					<?php _frozen_page_navi(); ?>
				</footer>
			</section>
			<?php get_sidebar(); ?>
<?php get_footer(); ?>