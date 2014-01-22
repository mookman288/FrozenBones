<?php
/**
 * Frozen Bones
 * 
 * @author PxO Ink (http://pxoink.net)
 * @uses Eddie Machado's bones
 * @package bones
 */

//Declare definitions.
if (!defined('NO_HEADER_TEXT'))			define('NO_HEADER_TEXT', true);
if (!defined('HEADER_TEXTCOLOR'))		define('HEADER_TEXTCOLOR', '000000');
if (!defined('HEADER_IMAGE'))			define('HEADER_IMAGE', get_bloginfo('template_directory') . '/images/header.gif');
if (!defined('HEADER_IMAGE_WIDTH'))		define('HEADER_IMAGE_WIDTH', 1000);
if (!defined('HEADER_IMAGE_HEIGHT'))	define('HEADER_IMAGE_HEIGHT', 150);

//Declare requirements.
require_once(dirname(__FILE__) . '/lib/translation/translation.php');

//Initialize Frozen _frozen
add_action('after_setup_theme', 'FrozenBones', 16);

/**
 * Frozen Bones initialization.
 */
function	_frozen() {
	//Clean the head.
	add_action('init', '_frozen_cleanup');
	
	//Remove the WP version from RSS.
	add_filter('the_generator', '_frozen_rss_version');
	
	//Deep clean the head.
	add_filter('wp_head', '_frozen_cleanup_deep', 1);
	
	//Clean up the gallery output.
	add_filter('gallery_style', '_frozen_gallery_style');
	
	//Queue styles and scripts.
	add_action('wp_enqueue_scripts', '_frozen_queue', 999);
	
	//Setup the theme.
	_frozen_theme_support();
	
	//Clean up image code.
	add_filter('the_content', '_frozen_filter_ptags_on_images');
	
	//Clean up the excerpt.
	add_filter('excerpt_more', '_frozen_excerpt_more');
	
	//Register the sidebars.
	add_action('widgets_init', '_frozen_register_sidebars');
	
	//Register the search form.
	add_filter('get_search_form', '_frozen_search_form');
}

/**
 * Custom admin footer.
 */
function	_frozen_admin_footer() {
	//Echo the content.
	_e('<span id="footer-thankyou">Built using <a href="http://pxoink.net/projects/frozen-bones" target="_blank">Frozen Bones</a> based on <a href="http://themble.com/bones" target="_blank">Bones</a>.', 'bonestheme');
}

//Set the custom filter.
add_filter('admin_footer_text', '_frozen_admin_footer');

/**
 * Custom breadcrumbs based on Cazue breadcrumbs.
 */
function	_frozen_breadcrumbs() {
	?>
		<div id="breadcrumbs">
			<?php
				//Depending on the page. 
				if (!is_home() && !is_front_page()) {
					?>
						<a href="<?php print(home_url('/')); ?>">Home</a>
						<span class="seperator"> / </span>
						<?php 
							//Depending on the page.
							if (is_category() || is_single()) {
								//Get the category.
								the_category('<span class="seperator"> / </span>');
								
								//If this is a single. 
								if (is_single())	{
									?><span class="seperator"> / </span><?php 
								}
							} elseif (is_page()) {
								//If this post doesn't have a parent.
								if (!$post -> post_parent) {
									//Get ancestors.
									$ancestors	=	get_post_ancestors($post -> ID);
									
									//For each ancestor.
									foreach($ancestors as $anc) {
										?>
											<a href="<?php print(get_permalink($anc -> ID)); ?>"
											title="<?php print(get_the_title($anc)); ?>"><?php print(get_the_title($anc)); ?></a>
											<span class="seperator"> / </span>
										<?php 
									}
								}
							}
							
							//Get the title.
							the_title();
						?>
					<?php 
				}
			?>
		</ul>
	<?php 
}

/**
 * The initial Frozen _frozen cleanup. Simply comment out anything you want to appear.
 */
function	_frozen_cleanup() {
	//Remove the WordPress generator tag.
	remove_action('wp_head', 'wp_generator');

	//Remove WordPress version from CSS.
	add_filter('style_loader_src', '_frozen_remove_wp_ver_css_js', 9999);

	//Remove WordPress version from JS.
	add_filter('script_loader_src', '_frozen_remove_wp_ver_css_js', 9999);

	//Remove category feeds.
	//remove_action('wp_head', 'feed_links_extra', 3);

	//Remove post and comments feeds.
	//remove_action('wp_head', 'feed_links', 2);

	//Remove the RSD link.
	remove_action('wp_head', 'rsd_link');

	//Remove the windows live writer.
	remove_action('wp_head', 'wlwmanifest_link');

	//Remove the previous link.
	//remove_action('wp_head', 'parent_post_rel_link', 10, 0);

	//Remove the start link.
	//remove_action('wp_head', 'start_post_rel_link', 10, 0);

	//Remove the adjacent links.
	//remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);
}

/**
 * Additional cleaning within the head.
 */
function	_frozen_cleanup_deep() {
	//Declare global variables.
	global $wp_widget_factory;

	//If the filter exists.
	if (has_filter('wp_head', 'wp_widget_recent_comments_style')) {
		//Remove the filter.
		remove_filter('wp_head', 'wp_widget_recent_comments_style');
	}

	//If the widget exists.
	if (isset($wp_widget_factory -> widgets['WP_Widget_Recent_Comments'])) {
		//Remove the action.
		remove_action('wp_head', array($wp_widget_factory -> widgets['WP_Widget_Recent_Comments'], 'recent_comments_style'));
	}
}

/**
 * Populates columns added to post tables.
 *
 * @param integer $post_id
 */
function	_frozen_column_content($column, $post_id) {
	//If this is the column.
	if ($column == 'featured-image') {
		//Get thumb information.
		$thumb	=	get_post_thumbnail_id($post_id);

		//Get the image.
		$image	=	(!$thumb) ? null : wp_get_attachment_image_src($thumb, 'frozen-thumb-125');

		//If there's no image.
		if (!$image) {
			?>&ndash;<?php
		} else {
			?><img src="<?php print($image[0]); ?>" alt="Featured Image" /><?php
		}
	}
}

/**
* Adds columns to post tables.
*
* @param array $defaults
*/
function	_frozen_column_head($defaults) {
//Add to the defaults.
$defaults['featured-image']	=	'Featured Image';

//Return the defaults.
return $defaults;
}

//Ad filters.
add_filter('manage_posts_columns', '_frozen_column_head');
add_action('manage_posts_custom_column', '_frozen_column_content', 10, 2);

/**
 * Implement custom nested comments.
 * 
 * @param unknown_type $comment
 * @param unknown_type $args
 * @param unknown_type $depth
 */
function	_frozen_comments($comment, $args, $depth) {
	//Declare globals.
	$GLOBALS['comment']	=	$comment;
	?>
		<li <?php comment_class(); ?>>
			<article id="comment-<?php comment_ID(); ?>">
				<header>
					<?php get_avatar($comment, 40); ?>
					<cite class="card"><?php printf('Posted <time class="updated" datetime="%1$s" pubdate>%2$s</time> by <span class="author">%3$s</span>.', comment_time('Y-m-j'), comment_time(get_option('date_format')), get_comment_author_link()); ?></cite>
				</header>
				<section>
					<?php if ($comment -> comment_approved == '0') { ?>
						<p class="alert"><?php _e('Your comment is awaiting moderation.', 'bonestheme'); ?></p>
					<?php } ?>
					<?php comment_text(); ?>
				</section>
				<footer>
					<p><?php printf(__('<a href="%s" target="_blank">Permalink</a>%s | %s'), 
							htmlspecialchars(get_comment_link($comment -> comment_ID)), 
							edit_comment_link(__('Edit', 'bonestheme'),' | ',''), 
							comment_reply_link(array_merge($args, array('reply_text' => 'Reply', 
									'login_text' => 'Login', 
									'depth' => $depth, 
									'max_depth' => $args['max_depth'])))); ?></p>
				</footer>
			</article>
		</li>
	<?php 
}

/**
 * Disables various dashboard widgets.
 */
function _frozen_disable_dashboard_widgets() {
	//Remove meta boxes.
	remove_meta_box( 'dashboard_recent_drafts', 'dashboard', 'core' );
}

add_action('admin_menu', '_frozen_disable_dashboard_widgets');

/**
 * Strips certain content from the Read More text.
 * @param strings $more
 * @return string
 */
function	_frozen_excerpt_more($more) {
	//Declare global variables.
	global	$post;

	//Return the modified content.
	return '&hellip; <a class="read-more" href="'. get_permalink($post -> ID) . '" title="'. __('Read', 'bonestheme') . get_the_title($post -> ID).'">'. __('Read more', 'bonestheme') .'</a>';
}

/**
 * Remove paragraph tags from around images.
 *
 * @uses http://css-tricks.com/snippets/wordpress/remove-paragraph-tags-from-around-images/
 * @param string $content
 */
function	_frozen_filter_ptags_on_images($content){
	//Return stripped content.
	return preg_replace('/<p>\s*(<a .*>)?\s*(<img .* \/>)\s*(<\/a>)?\s*<\/p>/iU', '\1\2\3', $content);
}

/**
 * Generates a gallery based on slug.
 * 
 * @param string $slug
 * @param string $paging
 * @param string $order
 * @param string $by
 * @return string
 */
function	_frozen_gallery_generate($slug = false, $paging = true, $order = 'date', $by = 'DESC') {
	//Declare variables.
	$args	=	array('category_name' => $slug, 'post_type' => 'post', 'post_status' => 'publish', 
			'nopaging' => (!$paging) ? true : false, 'order_by' => $order, 'order' => $by, 
			'tax_query' => array('include_children' => true));
	$query	=	new WP_Query($args);
	
	//Start the output buffer.
	ob_start();
	
	//If there is no slug.
	if (!$slug) {
		?>You must include a category slug.<?php 
	} else {
		//The Loop
		if ($query -> have_posts()) :
			while ($query -> have_posts()) : $query -> the_post();
				?>
					<a href="<?php the_permalink(); ?>" class="post-image" data-caption="<?php the_title(); ?>"><?php the_post_thumbnail('frozen-thumb-400'); ?></a>
				<?php 
			endwhile;
		endif;
	}
	
	//Return the HTML.
	return ob_get_clean();
}

/**
 * Shortcode for a generated gallery.
 *
 * @param array $atts
 * @param string $content
 * @return string
 */
function	_frozen_gallery_shortcode($atts, $content = null) {
	//Declare variables.
	$attributes	=	array('category' => false, 
			'paging' => true, 
			'order' => 'date', 
			'by' => 'DESC', 
			'display' => false);
	
	//Update the attributes.
	extract(shortcode_atts($attributes, $atts));
	
	//Generate the gallery.
	$gallery	=	_frozen_gallery_generate($category, $paging, $order, $by);
	
	//If there is no display.
	if (!$display) {
		//Return the gallery.
		return $gallery;
	} else {
		//Print the gallery.
		print($gallery);
	}
}

/**
 * Remove additional CSS from the gallery.
 * 
 * @param string $css
 */
function	_frozen_gallery_style($css) {
	//Return the CSS.
	return preg_replace( "!<style type='text/css'>(.*?)</style>!s", '', $css);
}

/**
 * Modified author posts link.
 *
 * @return boolean|string
 */
function	_frozen_get_the_author_posts_link() {
	//Declare globals.
	global	$authordata;

	//If there is no author data, return false.
	if (!is_object($authordata))	return false;

	//Create the link.
	$link	=	sprintf('<a href="%1$s" title="%2$s" rel="author">%3$s</a>',
			get_author_posts_url($authordata -> ID, $authordata -> user_nicename),
			esc_attr(sprintf(__('All posts by %s'), get_the_author())),
			get_the_author());

	//Return the link.
	return $link;
}

/**
 * Adds header image style support. 
 */
function _frozen_header_style() {
	?>
		<style type="text/css">
			header[role=banner] {
				background-image: url('<?php header_image(); ?>');
				}
		</style>
	<?php
}

/**
 * Adds header image style support for the administrative section.
 */
function _frozen_header_style_admin() {
	?>
		<style type="text/css">
			header[role=banner] {
				background-image: url('<?php get_bloginfo('template-directory') ?>/images/logo.png');
			}
		</style>
	<?php
}

//Add custom image header support. 
add_custom_image_header('_frozen_header_style', '_frozen_header_style_admin');

/**
 * Queue login CSS. 
 */
function	_frozen_login_css() {
	//Queue the login CSS. 
	wp_enqueue_style('frozen_login_css', get_bloginfo('template_directory') . '/css/login.css', false );
}

/**
 * Modify the login alt text.
 */
function	_frozen_login_title() {
	//Return the site name.
	return get_option('blogname');
}

/**
 * Modify the login url. 
 */
function	_frozen_login_url() {
	//Return the home url.
	return home_url();
}

//Add the login modifications.
add_action('login_enqueue_scripts', '_frozen_login_css', 10);
add_filter('login_headertitle', '_frozen_login_title');
add_filter('login_headerurl', '_frozen_login_url');

/**
 * Fallback navigation.
 */
function	_frozen_navigation() {
	?>
		<nav class="nav">
			<ul>
				<?php wp_list_pages(array('title_li' => '')); ?>
			</ul>
		</nav>
	<?php 
}

/**
 * Custom page navigation.
 */
function	_frozen_page_navi() {
	//Declare globals.
	global	$wp_query;
	
	//Declare variables.
	$num	=	655353655353;
	
	//If there are no pages, return.
	if ($wp_query -> max_num_pages < 1)	return;

	//Start output buffer.
	ob_start();
	
	?>
		<span class="pagination">
			<?php 
				print(paginate_links(array(
						'base' => str_replace($num, '%#%', esc_url(get_pagenum_link($num))),
						'format' => '',
						'current' => max(1, get_query_var('paged')),
						'total' => $wp_query->max_num_pages,
						'prev_text' => '>>',
						'next_text' => '<<',
						'type' => 'plain',
						'end_size' => 3,
						'mid_size' => 3
						)));
			?>
		</span>
	<?php 
	
	//Print the output.
	print(ob_get_clean());
}

/**
 * Custom post field.
 */
function	_frozen_post_field_() {
	//Declare global variables.
	global $post;
	
	//Declare variables.
	$meta	=	get_post_meta($post -> ID, '', true);
	
	//Start output buffer.
	ob_start();

	//Nonce.
	wp_nonce_field('post-field', '_frozen_nonce[]');

	?>

	<?php

	//Print the output.
	print(ob_get_clean());
}

/**
 * Adds metaboxes to the sidebar.
 */
function	_frozen_post_metaboxes() {
	//Add a meta box.
	//add_meta_box('id', 'title', '_frozen_post_field_', 'post', 'side');
}

//Add meta boxes.
//add_action('add_meta_boxes', '_frozen_post_metaboxes');

/**
 * Saves custom fields.
 *
 * @param integer $post_id
 * @param object $post
 */
function	_frozen_post_save_fields($post_id, $post) {
	//If there is a nonce.
	if (isset($_POST['_frozen_nonce']) && is_array('_frozen_nonce')) {
		//For each nonce.
		foreach($_POST['_frozen_nonce'] as $nonce) {
			if (!wp_verify_nonce($nonce, 'post-field')) {
				//Return the post ID.
				return $post -> ID;
			} elseif (!current_user_can('edit_post', $post -> ID)) {
				//Return the post ID.
				return $post -> ID;
			}
		}

		//If this is a revision.
		if ($post -> post_type == 'revision') return;

		//If there are post fields.
		if (isset($_POST['_frozen_post_fields']) && is_array($_POST['_frozen_post_fields'])) {
			//For each custom post field.
			foreach($_POST['_frozen_post_fields'] as $field => $value) {
				//If there is value.
				if (strlen($value) > 0 || !is_null($value)) {
					//Update the meta.
					update_post_meta($post -> ID, $field, $value);
				} else {
					//Delete the meta.
					delete_post_meta($post -> ID, $field, $value);
				}
			}
		}
	}
}

/**
 * Queue scripts and styles.
 */
function	_frozen_queue() {
	//Declare global variables.
	global	$wp_styles;

	//Declare variables.
	$styleDir	=	sprintf("%s/css", get_bloginfo('template_directory'));
	$scriptDir	=	sprintf("%s/js", get_bloginfo('template_directory'));

	//If this isn't the admin panel.
	if (!is_admin()) {
		//Register stylesheets.
		wp_register_style('frozen-fonts', "$styleDir/fonts.css", array(), '', 'all');
		wp_register_style('frozen-reset', "$styleDir/reset.css", array(), '', 'all');
		wp_register_style('frozen-clear', "$styleDir/clear.css", array(), '', 'all');
		wp_register_style('frozen-stylesheet', "$styleDir/stylesheet.css", array(), '', 'all');
		wp_register_style('frozen-responsive', "$styleDir/responsive.css", array(), '', 'all');
		wp_register_style('frozen-icebox', "$styleDir/icebox.css", array(), '', 'all');

		//Register scripts.
		wp_register_script('html5shiv', "$scriptDir/html5shiv.js", array('jquery'), '3.7.0', true);
		wp_register_script('modernizr', "$scriptDir/modernizr.custom.min.js", array('jquery'), '2.6.2', true);
		wp_register_script('fixes', "$scriptDir/fixes.js", array('jquery'), '1.0.0', true);
		wp_register_script('responsive', "$scriptDir/responsive.js", array('fixes'), '1.0.0', true);
		wp_register_script('icebox', "$scriptDir/icebox.js", array('fixes'), '1.0.0', true);
		wp_register_script('frozen', "$scriptDir/functions.js", array('icebox'), '1.0.0', true);

		//Queue CSS.
		wp_enqueue_style('frozen-fonts');
		wp_enqueue_style('frozen-reset');
		wp_enqueue_style('frozen-clear');
		wp_enqueue_style('frozen-stylesheet');
		wp_enqueue_style('frozen-responsive');
		wp_enqueue_style('frozen-icebox');
		
		//Queue jQuery.
		wp_enqueue_script('jquery');

		//Queue the comment script for threaded comments.
		if (is_singular() AND comments_open() AND (get_option('thread_comments') == 1))	wp_enqueue_script('comment-reply');

		//Queue custom JS.
		wp_enqueue_script('html5shiv');
		wp_enqueue_script('fixes');
		wp_enqueue_script('responsive');
		wp_enqueue_script('icebox');
		wp_enqueue_script('frozen');
	}
}

/**
 * Function to register various sidebars. 
 */
function	_frozen_register_sidebars() {
	register_sidebar(array(
			'id' => 'sidebar1',
			'name' => __('Default Sidebar', 'bonestheme' ),
			'description' => __('The default sidebar.', 'bonestheme' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<h4 class="widgettitle">',
			'after_title' => '</h4>',
	));
}

/**
 * Displays related posts.
 */
function	_frozen_related_posts($posts = '5') {
	//Declare global variables.
	global	$post;
	
	//Declare variables.
	$tags		=	wp_get_post_tags($post -> ID);
	$tagSet		=	'';
	$related	=	false;
	
	//If there are tags.
	if ($tags) {
		//For each tag.
		foreach($tags as $tag) {
			//Increment the set of tags.
			$tagSet	.=	$tag -> slug . ',';
		}
		
		//Get related posts.
		$related	=	get_posts(array(
				'tag' => $tagSet,
				'numberposts' => $posts,
				'post__not_in' => array($post -> ID)
				));
	}
	
	//Start output buffer.
	ob_start();
	
	//If there are related posts.
	if ($related) {
		?>
			<menu id="related-posts">
		<?php
			//For each related post.
			foreach($related as $post) {
				//Setup post data.
				setup_postdata($post);
				?>
					<li><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></li>
				<?php
			}
		?>
			</menu>
		<?php
	} else {
		?><p id="no-related-posts"><?php _e('No related posts found.', 'bonestheme'); ?></p><?php 
	}
	
	//Reset the query.
	wp_reset_query();
	
	//Print the html.
	print(ob_get_clean());
}

/**
 * Override for WordPress RSS version. 
 */
function	_frozen_rss_version() {
	//Return nothing.
	return '';
}

//Add the filter for the admin footer.
add_filter('admin_footer_text', '_frozen_admin_footer');

/**
 * Remove the WP version from the
 *
 * @param $src
 * @return $src
 */
function	_frozen_remove_wp_ver_css_js($src) {
	//If the version is found, change the src.
	$src	=	(strpos($src, 'ver=')) ? remove_query_arg('ver', $src) : $src;

	//Return the src.
	return $src;
}

/**
 * Custom WordPress search.
 *
 * @param string $form
 */
function	_frozen_search_form($form = false) {
	//Start the output buffer.
	ob_start();

	?>
		<form role="search" method="get" action="<?php print(home_url('/')); ?>" class="search">
			<label for="s"><?php _e('Search', 'bonestheme'); ?>:</label>
			<input id="s" type="text" name="s" value="<?php print(get_search_query()); ?>" placeholder="<?php _e("this site"); ?>&hellip;" />
			<input type="submit" value="<?php _e('Search'); ?>" />
		</form>
	<?php

	//Get the output buffer.
	$form	=	ob_get_clean();

	//Return the form.
	return $form;
}

/**
 * Add various theme support functions.
 */
function	_frozen_theme_support() {
	//Add post thumbnail support.
	add_theme_support('post-thumbnails');

	//Set the default thumbnail size. 
	set_post_thumbnail_size(125, 125, true);

	//@bransonwerner custom background.
	add_theme_support('custom-background', array(
			'default-image' => '',
			'default-color' => '', 
			'wp-head-callback' => '_custom_background_cb', 
			'admin-head-callback' => '', 
			'admin-preview-callback' => ''
			));

	//Add automatic RSS feed support.
	add_theme_support('automatic-feed-links');

	//Add custom menu support.
	add_theme_support('menus');

	//Register navigation menues. 
	register_nav_menus(array(
			'sub-nav' => __('Header Navigation', 'bonestheme'),
			'main-nav' => __('Main Navigation', 'bonestheme'),
			'footer-nav' => __('Footer Navigation', 'bonestheme')
			));
}

/**
 * Returns nothing.
 * @return void
 */
function	_frozen_wasteland() {
	//Return nothing.
	return;
}

/**
 * Custom WordPress search.
 * 
 * @param string $form
 */
function	_frozen_wpsearch($form = false) {
	//Return the form.
	return _frozen_search_form($form);
}

/**
 * Instantiate the initialization.
 */
function	FrozenBones() {
	//Run initialization.
	_frozen();
}

//Add thumbnail sizes. 
add_image_size('frozen-thumb-400', 400, 655353, true);
add_image_size('frozen-thumb-280', 280, 655353, true);
add_image_size('frozen-thumb-185', 185, 655353, true);
add_image_size('frozen-thumb-125', 125, 125, true);

/**
 * Header navigation.
 */
function	headerNavigation() {
	//Output navigation.
	wp_nav_menu(array(
			'container' => false,
			'container_class' => false,
			'menu' => __('Header Navigation', 'bonestheme'),
			'menu_class' => 'nav-header',
			'theme_location' => 'sub-nav',
			'before' => '',
			'after' => '',
			'link_before' => '',
			'link_after' => '',
			'items_wrap' => '<menu id="%1$s" class="%2$s">%3$s</menu>',
			'depth' => 0,
			'fallback_cb' => '_frozen_wasteland'
			));
}

/**
 * Footer navigation.
 */
function	footerNavigation() {
	//Output navigation.
	wp_nav_menu(array(
			'container' => false,
			'container_class' => false,
			'menu' => __('Footer Navigation', 'bonestheme'),
			'menu_class' => 'nav-footer',
			'theme_location' => 'footer-nav',
			'before' => '',
			'after' => '',
			'link_before' => '',
			'link_after' => '',
			'items_wrap' => '<nav id="%1$s"><ul id="%2$s">%3$s</ul></nav>',
			'depth' => 0,
			'fallback_cb' => '_frozen_wasteland'
			));
}

/**
 * Main navigation.
 */
function	mainNavigation() {
	//Output navigation. 
	wp_nav_menu(array(
			'container' => false,
			'container_class' => false,
			'menu' => __('Main Navigation', 'bonestheme'),
			'menu_class' => 'nav-main',
			'theme_location' => 'main-nav',
			'before' => '',
			'after' => '',
			'link_before' => '',
			'link_after' => '',
			'items_wrap' => '<nav id="%1$s"><ul id="%2$s">%3$s</ul></nav>',
			'depth' => 1,
			'fallback_cb' => '_frozen_navigation'
			));
}

?>