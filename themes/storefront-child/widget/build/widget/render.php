<?php

$api_key = get_option('openweather_api_key');

if (empty($api_key)) {
	echo '<div class="notice notice-error">' . esc_html__('OpenWeatherMap API key is not configured', 'storefront-child') . '</div>';
	return;
}


$transient_key = 'weather_data_' . $attributes['option'];
$weather_data = get_transient($transient_key);

if (!$weather_data) {
	$city_id = $attributes['option'] ?? 0;
	$city = get_post($city_id);

	if (!$city || $city->post_type !== 'cities') {
		echo '<div class="notice notice-error">' . esc_html__('City not found', 'storefront-child') . '</div>';
		return;
	}

	$lat = get_post_meta($city_id, 'abelohost-latitude', true);
	$lon = get_post_meta($city_id, 'abelohost-longitude', true);

	// Используем константу из wp-config.php
	$api_url = "https://api.openweathermap.org/data/2.5/weather?lat=$lat&lon=$lon&appid=" . $api_key . "&units=metric&lang=en";

	// Making a request
	$response = wp_remote_get($api_url);

	// Error handling
	if (is_wp_error($response)) {
		echo '<div class="notice notice-error">' . esc_html($response->get_error_message()) . '</div>';
		return;
	}

	$body = wp_remote_retrieve_body($response);
	$weather_data = json_decode($body, true);

	// Save to cache
	if ($weather_data && $weather_data['cod'] === 200) {
		set_transient($transient_key, $weather_data, 30 * MINUTE_IN_SECONDS);
	}
}
// Data display
?>

<div <?php echo get_block_wrapper_attributes(); ?>>
	<h3><?php esc_html_e('City weather', 'storefront-child'); ?></h3>

	<?php if (isset($weather_data['cod']) && $weather_data['cod'] === 200) : ?>
		<div class="weather-widget">
			<h2><?php echo esc_html(get_the_title($attributes['option'])); ?></h2>
			<div class="weather-info">
				<p>
					<?php esc_html_e('Temperature:', 'storefront-child'); ?>
					<?php echo round($weather_data['main']['temp']); ?>°C
				</p>
				<p>
					<?php esc_html_e('Conditions:', 'storefront-child'); ?>
					<?php echo esc_html($weather_data['weather'][0]['description']); ?>
				</p>
				<p>
					<?php esc_html_e('Humidity:', 'storefront-child'); ?>
					<?php echo esc_html($weather_data['main']['humidity']); ?>%
				</p>
				<p>
					<?php esc_html_e('Wind Speed:', 'storefront-child'); ?>
					<?php echo esc_html($weather_data['wind']['speed']); ?> m/s
				</p>
			</div>
		</div>
	<?php else : ?>
		<div class="notice notice-error">
			<?php esc_html_e('Weather data unavailable', 'storefront-child'); ?>
		</div>
	<?php endif; ?>
</div>