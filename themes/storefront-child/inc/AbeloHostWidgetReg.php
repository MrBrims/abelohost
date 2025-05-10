<?php
if (!class_exists('AbeloHostWidgetReg')) {
	class AbeloHostWidgetReg
	{
		public function __construct()
		{
			add_action('init', [$this, 'abelohostwidget_widget_block_init']);
			add_action('admin_enqueue_scripts', [$this, 'weather_localize']);
		}

		public function abelohostwidget_widget_block_init()
		{

			if (function_exists('wp_register_block_types_from_metadata_collection')) {
				wp_register_block_types_from_metadata_collection(dirname(__DIR__) . '/widget/build', dirname(__DIR__) . '/widget/build/blocks-manifest.php');
				return;
			}

			if (function_exists('wp_register_block_metadata_collection')) {
				wp_register_block_metadata_collection(dirname(__DIR__) . '/widget/build', dirname(__DIR__) . '/widget/build/blocks-manifest.php');
			}

			$manifest_data = require dirname(__DIR__) . '/widget/build/blocks-manifest.php';
			foreach (array_keys($manifest_data) as $block_type) {
				register_block_type(dirname(__DIR__) . "/widget/build/{$block_type}");
			}
		}

		public function weather_localize()
		{
			wp_localize_script('abelohostwidget-widget-editor-script', 'storefrontChildWeatherBlock', [
				'apiKey' => get_option('openweather_api_key', '')
			]);
		}
	}
}
if (class_exists('AbeloHostWidgetReg')) {
	$AbeloHostWidgetReg = new AbeloHostWidgetReg();
}
