				<aside role="complementary">
					<?php if (is_active_sidebar('sidebar_default')) { ?>
						<?php dynamic_sidebar('sidebar_default'); ?>
					<?php } else { ?>
						<h2><?php _e('Pages', 'bonestheme'); ?></h2>
						<?php _frozen_navigation(); ?>
					<?php } ?>
				</aside>