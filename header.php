<?php require_once(get_template_directory() . '/head.php'); ?>
	<body <?php body_class(); ?>>
		<section id="container">
			<header>
				<?php headerNavigation(); ?>
				<a href="<?php print(home_url()); ?>" rel="nofollow"><?php 
					?><img src="<?php bloginfo('template_directory'); ?>/images/logo.png" 
					alt="<?php bloginfo('name'); ?>" /><?php 
				?></a>
				<div class="site-information">
					<span class="website"><?php bloginfo('name'); ?></span>
					<span class="description"><?php bloginfo('description'); ?></span>
				</div>
			</header>
			<?php mainNavigation(); ?>