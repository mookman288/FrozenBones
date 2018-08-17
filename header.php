<?php
	//Get the custom logo ID.
	$logoID	=	get_theme_mod('custom_logo');

	//If there is a logo ID.
	if ($logoID) {
		//Get the attachment image source.
		$attachments	=	wp_get_attachment_image_src($logoID, 'full');

		//If there is an attachment.
		if (isset($attachments[0]) && $attachments[0]) {
			//Set the image.
			$image		=	$attachments[0];
		}
	}

	//Set the image.
	$image	=	(!isset($image)) ? sprintf("%s/images/logo.png", get_bloginfo('template_directory')) : $image;
?>
<?php require_once(get_template_directory() . '/head.php'); ?>
	<body <?php body_class(); ?>>
		<section id="container">
			<header>
				<?php headerNavigation(); ?>
				<a href="<?php print(home_url()); ?>" rel="nofollow"><?php
					?><img src="<?php print($image); ?>"
					alt="<?php bloginfo('name'); ?> <?php _e('logo'); ?>" /><?php
				?></a>
			<?php if (!is_home() || !is_front_page()) { ?>
				<div class="site-information">
					<span class="website"><?php bloginfo('name'); ?></span>
					<span class="description"><?php bloginfo('description'); ?></span>
				</div>
			<?php } ?>
			</header>
			<?php mainNavigation(); ?>