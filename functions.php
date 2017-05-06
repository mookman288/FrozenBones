<?php
	/**
	 * FrozenBones uses a centralized classless, yet pluggable interface for implementation. This
	 * makes development easy for novice and veteran developers alike. By prepending Frozen
	 * functions with a namespace-like phrase (_frozen) and by making each function pluggable,
	 * child themes and plugins will not encounter conflicts or overwrites.
	 *
	 * @author PxO Ink (http://pxoink.net)
	 * @uses Eddie Machado's bones
	 * @uses _s
	 * @package bones
	 */

	//Declare requirements.
	require_once(dirname(__FILE__) . '/lib/translation/translation.php');

	//Initialize Frozen _frozen
	add_action('after_setup_theme', 'FrozenBones', 16);

	//If the function exists.
	if (!function_exists('_frozen')) {
		/**
		 * FrozenBones initialization.
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
			add_filter('the_content', '_frozen_filter_paragraphs_on_images');

			//Clean up the excerpt.
			add_filter('excerpt_more', '_frozen_excerpt_more');

			//Register the sidebars.
			add_action('widgets_init', '_frozen_register_sidebars');

			//Register the search form.
			add_filter('get_search_form', '_frozen_search_form');

			//If within the admin.
			if (is_admin()) {
				//Register the administrative options.
				add_action('admin_menu', '_frozen_admin_options');
				add_action('admin_init', '_frozen_admin_register_options');
			}
		}
	}

	//If the function exists.
	if (!function_exists('_frozen_admin_fields')) {
		/**
		 * Administrative settings custom fields.
		 */
		function	_frozen_admin_fields() {

		}
	}

	//If the function exists.
	if (!function_exists('_frozen_admin_footer')) {
		/**
		 * Custom admin footer.
		 */
		function	_frozen_admin_footer() {
			//Echo the content.
			_e('<span id="footer-thankyou">Built using <a href="http://pxoink.net/projects/frozen-bones" target="_blank">FrozenBones</a> based on <a href="http://themble.com/bones" target="_blank">Bones</a>.', 'bonestheme');
		}
	}

	//Set the custom filter.
	add_filter('admin_footer_text', '_frozen_admin_footer');

	//If the function exists.
	if (!function_exists('_frozen_admin_options')) {
		/**
		 * Administrative option registers.
		 */
		function	_frozen_admin_options() {
			//Create a new administrative menu page.
			//add_menu_page('Frozen Plugin Settings', 'Frozen Settings', 'administrator', __FILE__, '_frozen_admin_options_page', plugins_url('/images/favicon.png', __FILE__));
		}
	}

	//If the function exists.
	if (!function_exists('_frozen_admin_options_page')) {
		/**
		 * Administrative option page.
		 */
		function	_frozen_admin_options_page() {
			//Declare variables.
			$options			=	array();
			$options['option']	=	get_option('option_name');

			?>
				<div class="wrap">
					<?php screen_icon(); ?>
					<h2>FrozenBones Settings</h2>
					<form method="post" action="<?php print($_SERVER['REQUEST_URI']); ?>">
						<?php
							//Add the option group.
							settings_fields('option_group');

							//Add the setting section.
							do_settings_sections('setting_admin');

							//Submit button.
							submit_button();
						?>
					</form>
				</div>
			<?php
		}
	}

	//If the function exists.
	if (!function_exists('_frozen_admin_register_options')) {
		/**
		 * Registers administrative options.
		 */
		function	_frozen_admin_register_options() {
			//Register settings.
			register_setting('option_group', 'option_name', 'intval');

			//Add settings sections.
			add_settings_section('setting_admin', 'Title', '_frozen_admin_section', 'setting_admin');

			//Add settings fields.
			add_settings_field('setting_field', 'Title', '_frozen_admin_fields', 'setting_admin', 'setting_admin');
		}
	}

	//If the function exists.
	if (!function_exists('_frozen_admin_section')) {
		/**
		 * Administrative settings sections.
		 */
		function	_frozen_admin_section() {

		}
	}

	//If the function exists.
	if (!function_exists('_frozen_breadcrumbs')) {
		/**
		 * Custom breadcrumbs based on Cazue breadcrumbs.
		 */
		function	_frozen_breadcrumbs() {
			//Depending on the page.
			if (!is_home() && !is_front_page()) {
				?>
					<ul class="breadcrumbs">
						<li>
							<a href="<?php print(home_url('/')); ?>">Home</a>
						</li>
						<?php
							//Depending on the page.
							if (is_category() || is_single()) {
								//Get the category.
								$cats	=	get_the_category();

								//For each catgory.
								foreach($cats as $cat) {
									?>
										<li>
											<a href="<?php print(get_category_link($cat -> term_id)); ?>"
												title="<?php _e($cat -> name); ?>">
												<?php _e($cat -> name); ?></a>
										</li>
									<?php
								}
							} elseif (is_page()) {
								//If this post doesn't have a parent.
								if (!$post -> post_parent) {
									//Get ancestors.
									$ancestors	=	get_post_ancestors($post -> ID);

									//Sort the ancestors.
									krsort($ancestors);

									//For each ancestor.
									foreach($ancestors as $anc) {
										?>
											<li>
												<a href="<?php print(get_permalink($anc)); ?>"
													title="<?php print(get_the_title($anc)); ?>">
													<?php print(get_the_title($anc)); ?></a>
											</li>
										<?php
									}
								}
							}
						?>
						<li class="current">
						<?php
							//Get the title.
							the_title();
						?>
						</li>
					</ul>
				<?php
			}
		}
	}

	//If the function exists.
	if (!function_exists('_frozen_cleanup')) {
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
	}

	//If the function exists.
	if (!function_exists('_frozen_cleanup_deep')) {
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
	}

	//If the function exists.
	if (!function_exists('_frozen_column_content')) {
		/**
		 * Populates columns added to post tables.
		 *
		 * @param string $column
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
	}

	//If the function exists.
	if (!function_exists('_frozen_column_head')) {
		/**
		* Adds columns to post tables.
		*
		* @param array $defaults
		*/
		function	_frozen_column_head($defaults = array()) {
			//Add to the defaults.
			$defaults['featured-image']	=	'Featured Image';

			//Return the defaults.
			return $defaults;
		}
	}

	//Add filters.
	add_filter('manage_posts_columns', '_frozen_column_head');
	add_action('manage_posts_custom_column', '_frozen_column_content', 10, 2);

	//If the function exists.
	if (!function_exists('_frozen_comments')) {
		/**
		 * Implement custom nested comments.
		 *
		 * @param string $comment
		 * @param string $args
		 * @param string $depth
		 */
		function	_frozen_comments($comment, $args, $depth) {
			//Declare globals.
			$GLOBALS['comment']	=	$comment;
			?>
				<li <?php comment_class(); ?>>
					<article id="comment-<?php comment_ID(); ?>">
						<header>
							<?php get_avatar($comment, 40); ?>
							<cite class="card">
								<?php printf('Posted <time class="updated" datetime="%1$s" pubdate>%2$s</time> by <span class="author">%3$s</span>.',
								comment_time('Y-m-j'),
								comment_time(get_option('date_format')),
								get_comment_author_link()); ?>
							</cite>
						</header>
						<section>
							<?php if ($comment -> comment_approved == '0') { ?>
								<p class="alert"><?php _e('Your comment is awaiting moderation.', 'bonestheme'); ?></p>
							<?php } ?>
							<?php comment_text(); ?>
						</section>
						<footer>
							<?php printf(__('<a href="%s" target="_blank">Permalink</a>%s %s'),
							htmlspecialchars(get_comment_link($comment -> comment_ID)),
							edit_comment_link(__('Edit', 'bonestheme'), ' ', ''),
							comment_reply_link(array_merge($args, array('reply_text' => 'Reply',
							'login_text' => 'Login',
							'depth' => $depth,
							'max_depth' => $args['max_depth'])))); ?>
						</footer>
					</article>
				</li>
			<?php
		}
	}

	//If the function exists.
	if (!function_exists('_frozen_disable_dashboard_widgets')) {
		/**
		 * Disables various dashboard widgets.
		 */
		function _frozen_disable_dashboard_widgets() {
			//Remove meta boxes.
			remove_meta_box('dashboard_recent_drafts', 'dashboard', 'core');
		}
	}

	//Add action.
	add_action('admin_menu', '_frozen_disable_dashboard_widgets');

	//If the function exists.
	if (!function_exists('_frozen_excerpt_more')) {
		/**
		 * Strips certain content from the Read More text.
		 *
		 * @param strings $more
		 * @return string
		 */
		function	_frozen_excerpt_more($more) {
			//Declare global variables.
			global	$post;

			//Return the modified content.
			return sprintf('&hellip; <a class="read-more" href="%s" title="%s %s">%s</a>', get_permalink($post -> ID),
					 __('Read', 'bonestheme'), get_the_title($post -> ID), __('Read more', 'bonestheme'));
		}
	}

	//If the function exists.
	if (!function_exists('_frozen_filter_paragraphs_on_images')) {
		/**
		 * Remove paragraph tags from around images.
		 *
		 * @uses http://css-tricks.com/snippets/wordpress/remove-paragraph-tags-from-around-images/
		 * @param string $content
		 */
		function	_frozen_filter_paragraphs_on_images($content){
			//Return stripped content.
			return preg_replace('/<p>\s*(<a .*>)?\s*(<img .* \/>)\s*(<\/a>)?\s*<\/p>/iU', '\1\2\3', $content);
		}
	}

	//If the function exists.
	if (!function_exists('_frozen_gallery_generate')) {
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
	}

	//If the function exists.
	if (!function_exists('_frozen_gallery_shortcode')) {
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
	}

	//If the function exists.
	if (!function_exists('_frozen_gallery_style')) {
		/**
		 * Remove additional CSS from the gallery.
		 *
		 * @param string $css
		 * @return string
		 */
		function	_frozen_gallery_style($css = '') {
			//Return the CSS.
			return preg_replace("!<style type='text/css'>(.*?)</style>!s", '', $css);
		}
	}

	//If the function exists.
	if (!function_exists('_frozen_get_the_author_posts_link')) {
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
	}

	//If the function exists.
	if (!function_exists('_frozen_header_style')) {
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
	}

	//If the function exists.
	if (!function_exists('_frozen_header_style_admin')) {
		/**
		 * Adds header image style support for the administrative section.
		 */
		function _frozen_header_style_admin() {
			?>
				<style type="text/css">
					header[role=banner] {
						background-image: url('<?php print(get_template_directory_uri()); ?>/images/logo.png');
					}
				</style>
			<?php
		}
	}

	//If the function exists.
	if (!function_exists('_frozen_login_css')) {
		/**
		 * Queue login CSS.
		 */
		function	_frozen_login_css() {
			//Queue the login CSS.
			wp_enqueue_style('frozen_login_css', get_template_directory_uri() . '/css/login.css', false);
		}
	}

	//If the function exists.
	if (!function_exists('_frozen_login_title')) {
		/**
		 * Modify the login alt text.
		 */
		function	_frozen_login_title() {
			//Return the site name.
			return get_option('blogname');
		}
	}

	//If the function exists.
	if (!function_exists('_frozen_login_url')) {
		/**
		 * Modify the login url.
		 */
		function	_frozen_login_url() {
			//Return the home url.
			return home_url();
		}
	}

	//Add the login modifications.
	add_action('login_enqueue_scripts', '_frozen_login_css', 10);
	add_filter('login_headertitle', '_frozen_login_title');
	add_filter('login_headerurl', '_frozen_login_url');

	//If the function exists.
	if (!function_exists('_frozen_navigation')) {
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
	}

	//If the function exists.
	if (!function_exists('_frozen_not_found')) {
		/**
		 * Displays the not found HTML.
		 *
		 * @param string $header
		 */
		function	_frozen_not_found($header = '404: Not Found!') {
			//Start output buffer.
			ob_start();

			?>
							<header>
								<h1><?php _e($header, 'bonestheme'); ?></h1>
							</header>
							<section class="content">
								<p><?php _e('Please try the following: ', 'bonestheme'); ?></p>
								<ul>
									<li>
										<?php _e('Double check the address or search terms for syntax errors.', 'bonestheme'); ?>
									</li>
									<li><?php _e('Ensure that your cache is refreshed.', 'bonestheme'); ?></li>
									<li><?php _e('Use the search form below or adjust your search:', 'bonestheme'); ?></li>
								</ul>
								<?php get_search_form(); ?>
							</section>
							<footer>
								<p>
									<?php _e("Still can't find what you're looking for? Return to the", 'bonestheme'); ?>
									<a href="<?php print(home_url()); ?>"><?php _e('homepage', 'bonestheme'); ?>.
								</a>
							</footer>
			<?php

			//Print the output.
			print(ob_get_clean());
		}
	}

	//If the function exists.
	if (!function_exists('_frozen_page_navi')) {
		/**
		 * Custom page navigation.
		 */
		function	_frozen_page_navi() {
			//Declare globals.
			global	$wp_query, $wp_rewrite;

			//Declare variables.
			$links	=	paginate_links(array(
					'base' => (!$wp_rewrite -> using_permalinks()) ?
					add_query_arg('paged', '%#%') :
        			user_trailingslashit(trailingslashit(remove_query_arg('s', get_pagenum_link(1))).'page/%#%/', 'paged'),
					'format' => '',
					'current' => max(1, get_query_var('paged')),
					'total' => $wp_query -> max_num_pages,
					'prev_text' => '&lt;',
					'next_text' => '&gt;',
					'type' => 'array',
					'end_size' => 5,
					'mid_size' => 5,
					'add_args' => array()
			));

			//If there are no pages, return.
			if ($wp_query -> max_num_pages < 1)	return;

			//Start output buffer.
			ob_start();

			//If there are links.
			if (is_array($links) && count($links) > 0) {
				?>
					<div class="pagination">
						<ul class="pagination">
						<?php
							foreach($links as $link) {
								?>
									<li>
										<?php print($link); ?>
									</li>
								<?php
							}
						?>
						</ul>
					</div>
				<?php
			}

			//Print the output.
			print(ob_get_clean());
		}
	}

	//If the function exists.
	if (!function_exists('_frozen_post_field')) {
		/**
		 * Custom post field.
		 */
		function	_frozen_post_fields() {
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
	}

	//If the function exists.
	if (!function_exists('_frozen_post_metaboxes')) {
		/**
		 * Adds metaboxes to the sidebar.
		 */
		function	_frozen_post_metaboxes() {
			//Add a meta box.
			//add_meta_box('id', 'title', '_frozen_post_fields', 'post', 'side');
		}
	}

	//Add meta boxes.
	//add_action('add_meta_boxes', '_frozen_post_metaboxes');

	//If the function exists.
	if (!function_exists('_frozen_post_save_fields')) {
		/**
		 * Saves custom fields.
		 *
		 * @param integer $post_id
		 * @param object $post
		 * @return number|null
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
	}

	//If the function exists.
	if (!function_exists('_frozen_queue')) {
		/**
		 * Queue scripts and styles.
		 */
		function	_frozen_queue() {
			//Declare global variables.
			global	$wp_styles;

			//Declare variables.
			$styleDir	=	get_template_directory_uri() . "/css";
			$scriptDir	=	get_template_directory_uri() . "/js";
			$stylePath	=	get_template_directory() . "/css";
			$scriptPath	=	get_template_directory() . "/js";

			//If this isn't the admin panel.
			if (!is_admin()) {
				//Get all CSS files.
				$css	=	preg_grep('/login\.css/', glob("$stylePath/*.{css}", GLOB_BRACE), PREG_GREP_INVERT);

				//If there are CSS files.
				if (is_array($css) && count($css) > 0) {
					//For each CSS file.
					foreach($css as $file) {
						//Get the filename.
						$file		=	basename($file);
						$fileName	=	preg_replace('/[^a-z0-9]+/', '-', strtolower($file));

						//Register the stylesheet.
						wp_register_style($fileName, "$styleDir/$file", array(), null, 'all');

						//Queue the stylesheet.
						wp_enqueue_style($fileName);
					}
				}

				//Queue jQuery.
				wp_enqueue_script('jquery');

				//Get all JS files.
				$js		=	glob("$scriptPath/*.{js}", GLOB_BRACE);

				//If there are JS files.
				if (is_array($js) && count($js) > 0) {
					//For each JS file.
					foreach($js as $file) {
						//Get the filename.
						$file		=	basename($file);
						$fileName	=	preg_replace('/[^a-z0-9]+/', '-', strtolower($file));

						//Register scripts.
						wp_register_script($fileName, "$scriptDir/$file", array('jquery'), null, true);

						//Queue custom JS.
						wp_enqueue_script($fileName);
					}
				}

				//Queue the comment script for threaded comments.
				if (is_singular() AND comments_open() AND (get_option('thread_comments') == 1))	wp_enqueue_script('comment-reply');
			}
		}
	}

	//If the function exists.
	if (!function_exists('_frozen_register_sidebars')) {
		/**
		 * Function to register various sidebars.
		 */
		function	_frozen_register_sidebars() {
			register_sidebar(array(
					'id' => 'sidebar_default',
					'name' => __('Default Sidebar', 'bonestheme' ),
					'description' => __('The default sidebar.', 'bonestheme' ),
					'before_widget' => '<div id="%1$s" class="widget %2$s">',
					'after_widget' => '</div>',
					'before_title' => '<h4 class="widgettitle">',
					'after_title' => '</h4>',
			));
		}
	}

	//If the function exists.
	if (!function_exists('_frozen_related_posts')) {
		/**
		 * Displays related posts.
		 *
		 * @param number $posts
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
	}

	//If the function exists.
	if (!function_exists('_frozen_rss_version')) {
		/**
		 * Override for WordPress RSS version.
		 *
		 * @return string
		 */
		function	_frozen_rss_version() {
			//Return nothing.
			return '';
		}
	}

	//Add the filter for the admin footer.
	add_filter('admin_footer_text', '_frozen_admin_footer');

	//If the function exists.
	if (!function_exists('_frozen_remove_wp_ver_css_js')) {
		/**
		 * Remove the WP version from the
		 *
		 * @param string $src
		 * @return string
		 */
		function	_frozen_remove_wp_ver_css_js($src) {
			//If the version is found, change the src.
			$src	=	(strpos($src, 'ver=')) ? remove_query_arg('ver', $src) : $src;

			//Return the src.
			return $src;
		}
	}

	//If the function exists.
	if (!function_exists('_frozen_search_form')) {
		/**
		 * Custom WordPress search.
		 *
		 * @param string $form
		 */
		function	_frozen_search_form($form = '') {
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
	}

	//If the function exists.
	if (!function_exists('_frozen_theme_support')) {
		/**
		 * Add various theme support functions.
		 */
		function	_frozen_theme_support() {
			//Add theme support for title tags.
			add_theme_support('title-tag');

			//Add post thumbnail support.
			add_theme_support('post-thumbnails');

			//Set the default thumbnail size.
			set_post_thumbnail_size(125, 125, true);

			//Add custom image header support.
			add_theme_support('custom_header', array(
				'wp-head-callback' => '_frozen_header_style',
				'admin-head-callback' => '_frozen_header_style_admin'
			));

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

			//Add HTML5 theme support.
			add_theme_support('html5', array('comment-list', 'comment-form', 'search-form', 'gallery', 'caption'));

			//Add support for selective refresh on widgets.
			add_theme_support('customize-selective-refresh-widgets');

			//Register navigation menues.
			register_nav_menus(array(
				'sub-nav' => __('Header Navigation', 'bonestheme'),
				'main-nav' => __('Main Navigation', 'bonestheme'),
				'footer-nav' => __('Footer Navigation', 'bonestheme')
			));

			/*
			 * Jetpack
			 */

			//Add responsive video support.
			add_theme_support('jetpack-responsive-videos');
		}
	}

	//If the function exists.
	if (!function_exists('_frozen_wasteland')) {
		/**
		 * Returns nothing.
		 *
		 * @return void
		 */
		function	_frozen_wasteland() {
			//Return nothing.
			return;
		}
	}

	//If the function exists.
	if (!function_exists('_frozen_wpsearch')) {
		/**
		 * Custom WordPress search.
		 *
		 * @param string $form
		 * @return string
		 */
		function	_frozen_wpsearch($form = false) {
			//Return the form.
			return _frozen_search_form($form);
		}
	}

	//If the function exists.
	if (!function_exists('FrozenBones')) {
		/**
		 * Instantiate the initialization.
		 */
		function	FrozenBones() {
			//Run initialization.
			_frozen();
		}
	}

	//Add thumbnail sizes.
	add_image_size('frozen-thumb-400', 400, 655353, true);
	add_image_size('frozen-thumb-280', 280, 655353, true);
	add_image_size('frozen-thumb-185', 185, 655353, true);
	add_image_size('frozen-thumb-125', 125, 125, true);

	//If the function exists.
	if (!function_exists('headerNavigation')) {
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
					'items_wrap' => '<nav id="%1$s">
										<ul id="%2$s" class="cfx">
											<li class="nod mobile"><a href="javascript:void(0)">&equiv;&equiv;</a></li>
											%3$s
										</ul>
									</nav>',
					'depth' => 0,
					'fallback_cb' => '_frozen_wasteland'
					));
		}
	}

	//If the function exists.
	if (!function_exists('footerNavigation')) {
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
					'depth' => 1,
					'fallback_cb' => '_frozen_wasteland'
					));
		}
	}

	//If the function exists.
	if (!function_exists('mainNavigation')) {
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
					'depth' => 0,
					'fallback_cb' => '_frozen_navigation'
					));
		}
	}

	//Add shortcodes to text widgets.
	add_filter('widget_text', 'do_shortcode');
?>