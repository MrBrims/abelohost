<?php

/**
 * Class for working with AbeloHost hosting API in WordPress admin panel
 * 
 * Creates an API settings page in the "Cities"
 */

if (!class_exists('AbeloHostApi')) {
	class AbeloHostApi
	{
		// Class constructor - registers hooks WordPress
		public function __construct()
		{
			add_action('admin_menu', [$this, 'settings_page_weather']);
			add_action('admin_init', [$this, 'register_settings']);
		}

		// Function to create a settings page in the WordPress admin panel
		public function settings_page_weather()
		{
			add_submenu_page(
				'edit.php?post_type=cities',
				'Weather API Settings',
				'API Settings',
				'manage_options',
				'cities_api',
				[$this, 'render_settings_page']
			);
		}

		// Function to register settings for the API settings page
		public function register_settings()
		{
			register_setting('weather_settings_group', 'openweather_api_key');

			add_settings_section(
				'weather_api_section',
				'API Settings',
				null,
				'cities_api'
			);

			add_settings_field(
				'openweather_api_key',
				'OpenWeatherMap API Key',
				[$this, 'render_api_key_field'],
				'cities_api',
				'weather_api_section'
			);
		}

		// Function to render the settings page for the API settings page
		public function render_settings_page()
		{
?>
			<div class="wrap">
				<h1>Weather Widget Settings</h1>
				<form method="post" action="options.php">
					<?php
					settings_fields('weather_settings_group');
					do_settings_sections('cities_api');
					submit_button();
					?>
				</form>
			</div>
<?php
		}

		// Function to render the API key field for the API settings page
		public function render_api_key_field()
		{
			$api_key = get_option('openweather_api_key');
			echo '<input type="text" name="openweather_api_key" value="'
				. esc_attr($api_key) . '" class="regular-text">';
		}
	}
}
if (class_exists('AbeloHostApi')) {
	$AbeloHostApi = new AbeloHostApi();
}
