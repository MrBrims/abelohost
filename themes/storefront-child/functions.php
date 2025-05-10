<?php

// Include the widget registration class
if (!class_exists('AbeloHostWidgetReg')) {
	require __DIR__ . '/inc/AbeloHostWidgetReg.php';
}


// Include the custom post class
if (!class_exists('AbeloHostCpt')) {
	require __DIR__ . '/inc/AbeloHostCpt.php';
}
