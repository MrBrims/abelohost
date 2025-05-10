<?php
if (!class_exists('AbeloHostApi')) {
	class AbeloHostApi
	{
		public function __construct()
		{
			add_action('admin_menu', [$this, 'settings_page_weather']);
			add_action('admin_init', [$this, 'register_settings']);
		}

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
