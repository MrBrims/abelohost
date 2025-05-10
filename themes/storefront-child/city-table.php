<?php

/**
 * Template Name: City List
 */

get_header(); ?>

<div id="primary">
	<main id="main" class="site-main" role="main">

		<?php
		while (have_posts()) :
			the_post();

			get_template_part('content', 'page');

			$AbeloHostCount->display_countries_list();

			do_action('storefront_page_after');

		endwhile; // End of the loop.
		?>

	</main><!-- #main -->
</div><!-- #primary -->

<?php
get_footer();
