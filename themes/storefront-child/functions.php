<?php

define('ABELOHOST_PATH', get_stylesheet_directory());

if (!class_exists('AbeloHostCpt')) {
	require ABELOHOST_PATH . '/inc/cpt.php';
}
