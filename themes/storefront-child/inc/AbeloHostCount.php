<?php
if (!class_exists('AbeloHostCount')) {
	class AbeloHostCount
	{
		public function display_countries_list()
		{
			global $wpdb;

			$countries = $wpdb->get_results(
				$wpdb->prepare("
                    SELECT t.name, t.slug 
                    FROM {$wpdb->terms} t 
                    INNER JOIN {$wpdb->term_taxonomy} tt 
                    ON t.term_id = tt.term_id 
                    WHERE tt.taxonomy = %s 
                    ORDER BY t.name ASC
                ", 'countries')
			);

			if (!empty($countries)) {
				echo '<table class="countries-table">';
				echo '<thead><tr><th>' . esc_html__('Country', 'storefront-child') . '</th>';
				echo '<th>' . esc_html__('Cities', 'storefront-child') . '</th></tr></thead>';
				echo '<tbody>';

				foreach ($countries as $country) {
					echo '<tr>';
					echo '<td><a href="' . esc_url(get_term_link($country->slug, 'countries')) . '">';
					echo esc_html($country->name) . '</a></td>';
					echo '<td>';

					$cities = new WP_Query(array(
						'post_type' => 'cities',
						'posts_per_page' => -1,
						'tax_query' => array(
							array(
								'taxonomy' => 'countries',
								'field' => 'slug',
								'terms' => $country->slug
							)
						)
					));

					if ($cities->have_posts()) {
						echo '<ul class="cities-list">';
						while ($cities->have_posts()) {
							$cities->the_post();
							$city_name = get_the_title();
							$temperature = $this->get_city_temperature($city_name);

							echo '<li>';
							echo '<a href="' . esc_url(get_permalink()) . '">';
							echo esc_html($city_name);
							echo '</a>';

							if ($temperature) {
								echo '<span class="temperature">';
								echo ' ' . round($temperature) . '°C';
								echo '</span>';
							}

							echo '</li>';
						}
						echo '</ul>';
						wp_reset_postdata();
					} else {
						echo '<span class="no-cities">' . esc_html__('No cities', 'storefront-child') . '</span>';
					}

					echo '</td></tr>';
				}
				echo '</tbody></table>';
			} else {
				echo '<p>' . esc_html__('Countries not found', 'storefront-child') . '</p>';
			}
		}

		private function get_city_temperature($city_name)
		{
			$api_key = get_option('openweather_api_key');
			$transient_key = 'weather_' . sanitize_title($city_name);

			// Проверяем кэш
			$cached = get_transient($transient_key);
			if ($cached !== false) {
				return $cached;
			}

			$response = wp_remote_get(
				"https://api.openweathermap.org/data/2.5/weather?q=" . urlencode($city_name) . "&units=metric&appid=" . $api_key
			);

			if (!is_wp_error($response) && 200 === wp_remote_retrieve_response_code($response)) {
				$body = json_decode(wp_remote_retrieve_body($response), true);
				$temp = isset($body['main']['temp']) ? $body['main']['temp'] : null;

				// Кэшируем на 1 час
				set_transient($transient_key, $temp, HOUR_IN_SECONDS);
				return $temp;
			}

			return null;
		}
	}
}
if (class_exists('AbeloHostCount')) {
	$AbeloHostCount = new AbeloHostCount();
}
