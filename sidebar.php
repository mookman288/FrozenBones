				<aside role="complementary">
					<?php if (is_active_sidebar('sidebar_default')): ?>
						<?php dynamic_sidebar('sidebar_default'); ?>
					<?php else : ?>
						<h4>Pages: </h4>
						<?php _frozen_navigation(); ?>
					<?php endif; ?>
				</aside>