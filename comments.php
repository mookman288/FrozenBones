<?php
	//If the user attempts to connect to this page directly.
	if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME'])) {
		//Redirect.
		header('Location: ' . $_SERVER['DOCUMENT_ROOT'], TRUE, 301);
		
		//Die.
		die();
	} 
	
	//If a password is required. 
	if (post_password_required()) {
		?>
			<p class="alert warning">
				<?php _e('This post is password protected. You must enter a password to view comments.', 'bonestheme'); ?>
			</p>
		<?php return;
	}
?>
<section class="comments">
	<?php if (have_comments()) { ?>
		<h3>
			<?php comments_number(__('No Comments', 'bonestheme'),
					__('One Comment', 'bonestheme'),
					_n('% Comment', '% Comments', 
							get_comments_number(), 
							'bonestheme'));?></h3>
		<?php if (previous_comments_link('&lt; Older Comments') || next_comments_link('&gt; Newer Comments')) { ?>
			<menu class="comment-menu">
				<li><?php previous_comments_link('&lt; Older Comments'); ?></li>
				<li><?php next_comments_link('&gt; Newer Comments'); ?></li>
			</menu>
		<?php } ?>
		<ol id="comment-list">
			<?php wp_list_comments('type=comment&callback=_frozen_comments'); ?>
		</ol>
		<?php if (previous_comments_link('&lt; Older Comments') || next_comments_link('&gt; Newer Comments')) { ?>
			<menu class="comment-menu">
				<li><?php previous_comments_link('&lt; Older Comments'); ?></li>
				<li><?php next_comments_link('&gt; Newer Comments'); ?></li>
			</menu>
		<?php } ?>
	<?php } if (comments_open()) { ?>
		<h3><?php comment_form_title(__('Comment Below', 'bonestheme'), __('Comment Below', 'bonestheme')); ?></h3>
		<?php if (get_option('comment_registration') && !is_user_logged_in()) { ?>
			<p class="alert warning">
				<?php 
					printf(__('You must be %1$slogged in%2$s to post a comment.', 'bonestheme'), 
					'<a href="<?php print(wp_login_url(get_permalink())); ?>">', '</a>' ); 
				?>
			</p>
		<?php } ?>
		<?php //The comment form is unreliable because it posts to wp-comments-post, when it should post to page. ?>
		<form id="comment-form" action="<?php print(get_option('siteurl')); ?>/wp-comments-post.php" method="post">
			<?php if (is_user_logged_in()) { ?>
				<p>
					<?php _e('Logged in as', 'bonestheme'); ?> 
					<a href="<?php print(get_option('siteurl')); ?>/wp-admin/profile.php"><?php print($user_identity); ?></a>. 
					<a href="<?php print(wp_logout_url(get_permalink())); ?>"><?php _e('Logout', 'bonestheme'); ?></a>?
				</p>
			<?php } else { ?>
			
				<label for="author"><?php _e('Name', 'bonestheme'); ?><?php if ($req) { ?><span title="Required" class="req">*</span><?php } ?>:</label>
				<input type="text" name="author" id="author" value="<?php print(esc_attr($comment_author)); ?>" 
				placeholder="<?php _e('Name/Handle', 'bonestheme'); ?>" tabindex="1" size="40" required 
				<?php if ($req) print(' aria-required="true"'); ?> />
				
				<label for="email"><?php _e('Email', 'bonestheme'); ?><?php if ($req) { ?><span title="Required" class="req">*</span><?php } ?>:</label>
				<input type="email" name="email" id="email" value="<?php print(esc_attr($comment_author_email)); ?>" 
				placeholder="<?php _e('Email Address', 'bonestheme'); ?>" tabindex="2" size="40" required 
				<?php if ($req) print(' aria-required="true"'); ?> />
				
				<label for="url"><?php _e('Website', 'bonestheme'); ?>:</label>
				<input type="url" name="url" id="url" value="<?php print(esc_attr($comment_author_url)); ?>" 
				placeholder="<?php _e('Website Address', 'bonestheme'); ?>" tabindex="3" size="40" />
				
			<?php } ?>
			
			<label for="comment">Comment<?php if ($req) { ?><span title="Required" class="req">*</span><?php } ?>:</label><br />
			<textarea id="comment" name="comment" cols="40" rows="5" tabindex="4" 
			placeholder="<?php _e("What's on your mind?", 'bonestheme'); ?>"></textarea>
			
			<?php comment_id_fields(); ?>
			<input id="submit" type="submit" name="submit" tabindex="5" value="<?php _e('Comment', 'bonestheme'); ?>" /> 
			<?php cancel_comment_reply_link(); ?>
			<?php do_action( 'comment_form', $post->ID ); ?>
		</form>
	<?php } ?>
</section>
