<?php get_header(); ?>
			<section id="main" class="column eight">
				<header>
					<h1><?php _e('404: Not Found', 'bonestheme'); ?></h1>
				</header>
				<section id="content">
					<p><?php _e('This post does not exist. Please try the following: ', 'bonestheme'); ?></p>
					<ul>
						<li><?php _e('Double check the address for syntax errors.', 'bonestheme'); ?></li>
						<li><?php _e('Ensure that your cache is refreshed.', 'bonestheme'); ?></li>
						<li><?php _e('Use the search form below:', 'bonestheme'); ?></li>
					</ul>
					<?php get_search_form(); ?>
				</section>
				<footer>
					<p><?php _e('Still can\'t find what you\'re looking for? Return to the', 'bonestheme'); ?> <a href="<?php print(home_url()); ?>"><?php _e('homepage', 'bonestheme'); ?></a>.
				</footer>
			</section>
			<?php get_sidebar(); ?>
<?php get_footer(); ?>
