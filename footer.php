			<footer>
				<?php footerNavigation(); ?>
				<p class="copyright">
					<?php _e('Copyright', 'bonestheme'); ?> &copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?>
					<?php _e('.'); ?> <?php _e('All Rights Reserved.'); ?>
				</p>
			</footer>
<?php require_once(get_template_directory() . '/foot.php'); ?>