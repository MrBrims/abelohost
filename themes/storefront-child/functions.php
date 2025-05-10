<?php

// Include the widget registration class
if (!class_exists('AbeloHostWidgetReg')) {
	require __DIR__ . '/inc/AbeloHostWidgetReg.php';
}

// Include the custom post class
if (!class_exists('AbeloHostCpt')) {
	require __DIR__ . '/inc/AbeloHostCpt.php';
}

// Include the API settings class
if (!class_exists('AbeloHostApi')) {
	require __DIR__ . '/inc/AbeloHostApi.php';
}

// Include the count class
if (!class_exists('AbeloHostCount')) {
	require __DIR__ . '/inc/AbeloHostCount.php';
}
