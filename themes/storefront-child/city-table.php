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

			get_template_part('parts/search-form');

			do_action('abelo_before_countries_list');

			$AbeloHostCount->display_countries_list();

			do_action('abelo_after_countries_list');

		endwhile; // End of the loop.
		?>

	</main><!-- #main -->
</div><!-- #primary -->

<?php
get_footer();
