<?php

// Include the widget registration class
if (!class_exists('AbeloHostWidgetReg')) {
	require __DIR__ . '/inc/AbeloHostWidgetReg.php';
}

// Include the custom post class
if (!class_exists('AbeloHostCpt')) {
	require __DIR__ . '/inc/AbeloHostCpt.php';
}

add_action('admin_enqueue_scripts', function () {
	wp_localize_script('abelohostwidget-widget-editor-script', '_wpSettings', [
		'weatherApiKey' => defined('OPENWEATHER_API_KEY') ? OPENWEATHER_API_KEY : ''
	]);
});
