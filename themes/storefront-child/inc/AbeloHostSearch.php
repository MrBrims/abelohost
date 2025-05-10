<?php

/**
 * Handles AJAX search functionality for city posts
 */
if (!class_exists('AbeloHostSearch')) {
	class AbeloHostSearch
	{
		public function __construct()
		{
			add_action('wp_ajax_nopriv_ajax_search', [$this, 'ajax_search']);
			add_action('wp_ajax_ajax_search', [$this, 'ajax_search']);
		}

		/**
		 * Handle AJAX search requests
		 * Processes search term, queries cities, and returns formatted results
		 */
		function ajax_search()
		{
			$args = array(
				'post_type'      => ['cities'], // Search only in 'cities' post type
				'post_status'    => 'publish', // Only published posts
				'order'          => 'DESC', // Most recent first
				'orderby'        => 'relevance', // WordPress will calculate relevance
				's'              => $_POST['term'],
				'posts_per_page' => -1 // Limit to 5 results
			);
			$query = new WP_Query($args);
			if ($query->have_posts()) {
				while ($query->have_posts()) : $query->the_post();
					get_template_part('parts/loop-search-item');
				endwhile;
				echo '<li class="ajax-search__item"><input class="ajax-search__submit" type="submit" value="' . esc_html__('View more result', 'storefront-child') . '" aria-label="Search button"></li>';
			} else {
				echo '<p class="ajax-search__null">' . esc_html__('No result', 'storefront-child') . '</p>';
			}
			exit;
		}
	}
}
if (class_exists('AbeloHostSearch')) {
	$AbeloHostSearch = new AbeloHostSearch();
}
