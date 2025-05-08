<?php
if (!class_exists('AbeloHostCpt')) {
	class AbeloHostCpt
	{
		//Class constructor to register custom post type
		public function __construct()
		{
			add_action('init', [$this, 'custom_post_type']);
		}

		//Class method to create custom post type
		public function custom_post_type()
		{

			//Register custom post type
			$labelSities = [
				'name'                  => esc_html_x('Cities', 'Post type general name', 'storefront-child'),
				'singular_name'         => esc_html_x('City', 'Post type singular name', 'storefront-child'),
				'menu_name'             => esc_html_x('Cities', 'Admin Menu text', 'storefront-child'),
				'name_admin_bar'        => esc_html_x('City', 'Add New on Toolbar', 'storefront-child'),
				'add_new'               => esc_html__('Add New', 'storefront-child'),
				'add_new_item'          => esc_html__('Add New City', 'storefront-child'),
				'new_item'              => esc_html__('New City', 'storefront-child'),
				'edit_item'             => esc_html__('Edit City', 'storefront-child'),
				'view_item'             => esc_html__('View City', 'storefront-child'),
				'all_items'             => esc_html__('All Cities', 'storefront-child'),
				'search_items'          => esc_html__('Search Cities', 'storefront-child'),
				'parent_item_colon'     => esc_html__('Parent Cities:', 'storefront-child'),
				'not_found'             => esc_html__('No Cities found.', 'storefront-child'),
				'not_found_in_trash'    => esc_html__('No Cities found in Trash.', 'storefront-child'),
				'featured_image'        => esc_html_x('City Cover Image', 'Overrides the “Featured Image” phrase for this post type. Added in 4.3', 'storefront-child'),
				'set_featured_image'    => esc_html_x('Set cover image', 'Overrides the “Set featured image” phrase for this post type. Added in 4.3', 'storefront-child'),
				'remove_featured_image' => esc_html_x('Remove cover image', 'Overrides the “Remove featured image” phrase for this post type. Added in 4.3', 'storefront-child'),
				'use_featured_image'    => esc_html_x('Use as cover image', 'Overrides the “Use as featured image” phrase for this post type. Added in 4.3', 'storefront-child'),
				'archives'              => esc_html_x('City archives', 'The post type archive label used in nav menus. Default “Post Archives”. Added in 4.4', 'storefront-child'),
				'insert_into_item'      => esc_html_x('Insert into City', 'Overrides the “Insert into post”/”Insert into page” phrase (used when inserting media into a post). Added in 4.4', 'storefront-child'),
				'uploaded_to_this_item' => esc_html_x('Uploaded to this City', 'Overrides the “Uploaded to this post”/”Uploaded to this page” phrase (used when viewing media attached to a post). Added in 4.4', 'storefront-child'),
				'filter_items_list'     => esc_html_x('Filter Cities list', 'Screen reader text for the filter links heading on the post type listing screen. Default “Filter posts list”/”Filter pages list”. Added in 4.4', 'storefront-child'),
				'items_list_navigation' => esc_html_x('Cities list navigation', 'Screen reader text for the pagination heading on the post type listing screen. Default “Posts list navigation”/”Pages list navigation”. Added in 4.4', 'storefront-child'),
				'items_list'            => esc_html_x('Cities list', 'Screen reader text for the items list heading on the post type listing screen. Default “Posts list”/”Pages list”. Added in 4.4', 'storefront-child'),
			];
			$argCities = [
				'label' 			=> null,
				'labels' 			=> $labelSities,
				'public' 			=> true,
				'supports' 		=> ['title'],
				'has_archive' => true,
				'rewrite' 		=> ['slug' => 'cities']
			];
			register_post_type('cities', $argCities);

			//Register custom taxonomy
			$labelsTaxonomy = [
				'name'              => esc_html_x('Сountries', 'taxonomy general name', 'storefront-child'),
				'singular_name'     => esc_html_x('Country', 'taxonomy singular name', 'storefront-child'),
				'search_items'      => esc_html__('Search Сountries', 'storefront-child'),
				'all_items'         => esc_html__('All Сountries', 'storefront-child'),
				'view_item'         => esc_html__('View Country', 'storefront-child'),
				'parent_item'       => esc_html__('Parent Genre', 'storefront-child'),
				'parent_item_colon' => esc_html__('Parent Country:', 'storefront-child'),
				'edit_item'         => esc_html__('Edit Country', 'storefront-child'),
				'update_item'       => esc_html__('Update Country', 'storefront-child'),
				'add_new_item'      => esc_html__('Add New Country', 'storefront-child'),
				'new_item_name'     => esc_html__('New Country Name', 'storefront-child'),
				'not_found'         => esc_html__('No Сountries Found', 'storefront-child'),
				'back_to_items'     => esc_html__('← Back to Country', 'storefront-child'),
				'menu_name'         => esc_html__('Сountries', 'storefront-child'),
			];
			$argsTaxonomy = [
				'hierarchical' 			=> true,
				'show_ui' 					=> true,
				'show_admin_column' => true,
				'query_var' 				=> true,
				'rewrite' 					=> ['slug' => 'countries'],
				'labels' 						=> $labelsTaxonomy,
			];
			register_taxonomy('сountries', 'cities', $argsTaxonomy);
		}
	}
}

if (class_exists('AbelohostCpt')) {
	$AbeloHostCpt = new AbelohostCpt();
}
