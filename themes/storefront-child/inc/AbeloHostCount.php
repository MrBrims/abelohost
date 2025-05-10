<?php

/**
 * Class for displaying a table with cities and their temperatures
 * Uses OpenWeatherMap API to fetch temperature data
 * Implements transient caching for API responses
 */

if (!class_exists('AbeloHostCount')) {
	class AbeloHostCount
	{
		/**
		 * Displays a table of countries with their associated cities and temperatures
		 * Queries the database for countries taxonomy terms
		 * For each country, queries associated cities posts
		 * Retrieves temperature for each city using OpenWeatherMap API
		 */
		public function display_countries_list()
		{
			global $wpdb;

			// Query database for all terms in 'countries' taxonomy
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
				// Start building the countries table
				echo '<table class="countries-table">';
				echo '<thead><tr><th>' . esc_html__('Country', 'storefront-child') . '</th>';
				echo '<th>' . esc_html__('Cities', 'storefront-child') . '</th></tr></thead>';
				echo '<tbody>';

				// Loop through each country and display its cities
				foreach ($countries as $country) {
					echo '<tr>';
					echo '<td><a href="' . esc_url(get_term_link($country->slug, 'countries')) . '">';
					echo esc_html($country->name) . '</a></td>';
					echo '<td>';

					// Query for posts in 'cities' post type associated with current country
					$cities = new WP_Query(array(
						'post_type' => 'cities',
						'posts_per_page' => -1, // Get all posts
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

							// Get temperature from OpenWeatherMap API (with caching)
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
						wp_reset_postdata(); // Reset post data after custom query
					} else {
						// No cities found for this country
						echo '<span class="no-cities">' . esc_html__('No cities', 'storefront-child') . '</span>';
					}

					echo '</td></tr>';
				}
				echo '</tbody></table>';
			} else {
				// No countries found in taxonomy
				echo '<p>' . esc_html__('Countries not found', 'storefront-child') . '</p>';
			}
		}

		/**
		 * Retrieves temperature for a given city using OpenWeatherMap API
		 * Implements transient caching to reduce API calls
		 * @param string $city_name Name of the city to check
		 * @return float|null Temperature in Celsius or null if unavailable
		 */
		private function get_city_temperature($city_name)
		{
			$api_key = get_option('openweather_api_key');
			$transient_key = 'weather_' . sanitize_title($city_name); // Create safe cache key

			// Check for cached temperature data
			$cached = get_transient($transient_key);
			if ($cached !== false) {
				return $cached; // Return cached value if available
			}

			// Make API request if no cache exists
			$response = wp_remote_get(
				"https://api.openweathermap.org/data/2.5/weather?q=" . urlencode($city_name) . "&units=metric&appid=" . $api_key
			);

			// Handle successful API response
			if (!is_wp_error($response) && 200 === wp_remote_retrieve_response_code($response)) {
				$body = json_decode(wp_remote_retrieve_body($response), true);
				$temp = isset($body['main']['temp']) ? $body['main']['temp'] : null;

				// Cache the temperature value for 1 hour
				set_transient($transient_key, $temp, HOUR_IN_SECONDS);
				return $temp;
			}

			return null; // Return null if API request failed
		}
	}
}

// Instantiate the class if it exists
if (class_exists('AbeloHostCount')) {
	$AbeloHostCount = new AbeloHostCount();
}
