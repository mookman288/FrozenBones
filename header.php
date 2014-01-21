<?php require_once(get_template_directory() . '/head.php'); ?>
	<body <?php body_class(); ?>>
		<section id="container">
			<header>
				<?php headerNavigation(); ?>
				<a href="<?php print(home_url()); ?>" rel="nofollow">
					<img src="<?php bloginfo('template_directory'); ?>/images/logo.png" alt="<?php bloginfo('name'); ?>" />
				</a>
				<span class="nod website"><?php bloginfo('name'); ?></span>
				<span class="nod description"><?php bloginfo('description'); ?></span>
			
			</header>
			<?php mainNavigation(); ?>