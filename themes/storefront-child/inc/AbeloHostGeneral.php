<?php

/**
 * Class for connecting scripts and styles
 */
if (!class_exists('AbeloHostGeneral')) {
	class AbeloHostGeneral
	{
		/**
		 * Initialize AJAX hooks for both logged-in and non-logged-in users
		 */
		public function __construct()
		{
			add_action('wp_enqueue_scripts', [$this, 'child_theme_scripts']);
		}

		public function child_theme_scripts()
		{
			// Подключение основного скрипта темы
			wp_enqueue_script(
				'child-theme-main',
				get_stylesheet_directory_uri() . '/js/main.js',
				filemtime(get_stylesheet_directory() . '/js/main.js'),
				true
			);
		}
	}
}
if (class_exists('AbeloHostGeneral')) {
	$AbeloHostGeneral = new AbeloHostGeneral();
}
