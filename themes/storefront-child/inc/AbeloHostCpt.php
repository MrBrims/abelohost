<?php

/**
 * A class for creating a custom record type,
 * custom taxonomy and meta fields
 */

if (!class_exists('AbeloHostCpt')) {
	class AbeloHostCpt
	{
		//Class constructor to register custom post type
		public function __construct()
		{
			add_action('init', [$this, 'custom_post_type']);
			add_action('add_meta_boxes', [$this, 'add_metabox']);
			add_action('add_meta_boxes', [$this, 'remove_default_metaboxes'], 20);
			add_action('init', [$this, 'register_city_meta_fields']);
			add_action('save_post', [$this, 'save_metabox'], 10, 2);
		}

		// Class method to remove default meta boxes
		public function remove_default_metaboxes()
		{
			remove_meta_box('postcustom', 'cities', 'normal');
		}

		//Class method to add custom meta box
		public function add_metabox()
		{
			add_meta_box(
				'coordinate_setting',
				'Coordinate City',
				[$this, 'metabox_city_html'],
				'cities',
				'normal',
				'default',
			);
		}

		//Class method to save custom meta box
		public function save_metabox($post_id, $post)
		{

			if (!isset($_POST['_abelohost']) || !wp_verify_nonce($_POST['_abelohost'], 'abelohostfields')) {
				return $post_id;
			}

			if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
				return $post_id;
			}

			if ($post->post_type != 'cities') {
				return $post_id;
			}

			$post_type = get_post_type_object($post->post_type);
			if (!current_user_can($post_type->cap->edit_post, $post_id)) {
				return $post_id;
			}

			if (is_null($_POST['abelohost-latitude'])) {
				delete_post_meta($post_id, 'abelohost-latitude');
			} else {
				update_post_meta($post_id, 'abelohost-latitude', sanitize_text_field($_POST['abelohost-latitude']));
			}

			if (is_null($_POST['abelohost-longitude'])) {
				delete_post_meta($post_id, 'abelohost-longitude');
			} else {
				update_post_meta($post_id, 'abelohost-longitude', sanitize_text_field($_POST['abelohost-longitude']));
			}
		}

		//Class method to display custom meta box
		public function metabox_city_html($post)
		{
			$latitude = get_post_meta($post->ID, 'abelohost-latitude', true);
			$longitude = get_post_meta($post->ID, 'abelohost-longitude', true);

			wp_nonce_field('abelohostfields', '_abelohost');

			echo '
			<p>
				<label for="abelohost-latitude">Latitude</label>
					<input type="text" id="abelohost-latitude" name="abelohost-latitude" value="' . esc_html($latitude) . '">
			</p>
			<p>
				<label for="abelohost-longitude">Longitude</label>
					<input type="text" id="abelohost-longitude" name="abelohost-longitude" value="' . esc_html($longitude) . '">
			</p>
			';
		}


		//Class method to register custom meta fields
		public function register_city_meta_fields()
		{
			register_meta('post', 'abelohost-latitude', [
				'type'         => 'string',
				'description'  => 'Latitude of the city',
				'single'       => true,
				'show_in_rest' => true,
			]);

			register_meta('post', 'abelohost-longitude', [
				'type'         => 'string',
				'description'  => 'Longitude of the city',
				'single'       => true,
				'show_in_rest' => true,
			]);
		}

		//Class method to create custom post type
		public function custom_post_type()
		{

			//Register custom post type
			$labelsSities = [
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
			$argsCities = [
				'label' 				=> null,
				'labels' 				=> $labelsSities,
				'public' 				=> true,
				'supports' 			=> ['title', 'custom-fields'],
				'rest_base' 		=> 'cities',
				'rest_controller_class' => 'WP_REST_Posts_Controller',
				'has_archive' 	=> true,
				'show_in_rest' 	=> true,
				'rewrite' 			=> ['slug' => 'cities']
			];
			register_post_type('cities', $argsCities);


			// Register custom taxonomy
			$taxonomy_name = 'countries';
			$post_types = ['cities'];

			$labels = [
				'name'              => esc_html_x('Countries', 'taxonomy general name', 'storefront-child'),
				'singular_name'     => esc_html_x('Country', 'taxonomy singular name', 'storefront-child'),
				'search_items'      => esc_html__('Search Countries', 'storefront-child'),
				'all_items'         => esc_html__('All Countries', 'storefront-child'),
				'parent_item'       => esc_html__('Parent Country', 'storefront-child'),
				'parent_item_colon' => esc_html__('Parent Country:', 'storefront-child'),
				'edit_item'         => esc_html__('Edit Country', 'storefront-child'),
				'update_item'       => esc_html__('Update Country', 'storefront-child'),
				'add_new_item'      => esc_html__('Add New Country', 'storefront-child'),
				'new_item_name'     => esc_html__('New Country Name', 'storefront-child'),
				'menu_name'         => esc_html__('Countries', 'storefront-child'),
			];

			$args = [
				'hierarchical'      => true,
				'labels'            => $labels,
				'show_ui'           => true,
				'show_admin_column' => true,
				'show_in_rest'      => true,
				'query_var'         => true,
				'rewrite'           => ['slug' => 'country'],
				'capabilities' => [
					'manage_terms' => 'manage_categories',
					'edit_terms'   => 'manage_categories',
					'delete_terms' => 'manage_categories',
					'assign_terms' => 'edit_posts',
				],
			];

			register_taxonomy($taxonomy_name, $post_types, $args);
		}
	}
}

if (class_exists('AbelohostCpt')) {
	$AbeloHostCpt = new AbelohostCpt();
}
