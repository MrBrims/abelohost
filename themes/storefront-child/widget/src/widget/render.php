<p <?php echo get_block_wrapper_attributes(); ?>>
	<?php
	$city_id = isset($attributes['option']) ? absint($attributes['option']) : 0;

	if ($city_id) {
		$city_title = get_the_title($city_id);
		$post_type = get_post_type($city_id);

		if ($post_type === 'cities' && !empty($city_title)) {
			echo esc_html($city_title);
		} else {
			esc_html_e('City not found', 'storefront-child');
		}
	} else {
		esc_html_e('Please select a city', 'storefront-child');
	}
	?>
</p>