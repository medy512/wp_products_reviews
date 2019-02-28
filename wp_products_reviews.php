<?php
/*
Plugin Name: Products with Reviews
Plugin URI: https://github.com/medy512/wp_products_reviews
Description: Products with Reviews, With this plugin you can add products within your WordPress website, and assign ratings, target groups to the product, and displays in the widget
Version: 1.1
Author: Mehdi Hadiany
Author URI:
License:
*/
// exit if file is called directly
if ( ! defined( 'ABSPATH' ) ) {

	exit;

}

if(!class_exists('WP_Products_Reviews'))
{
	class WP_Products_Reviews
	{
		/**
		 * Construct the plugin object
		 */
		public function __construct()
		{
			// Initialize Settings
			require_once(sprintf("%s/settings.php", dirname(__FILE__)));
			$WP_Products_Reviews_Settings = new WP_Products_Reviews_Settings();

			// Register custom post types
			require_once(sprintf("%s/post-types/product_reviews_template.php", dirname(__FILE__)));
			$Products_Reviews_Template = new Products_Reviews_Template();

			// Register widget
			require_once(sprintf("%s/widgets/product_reviews_widget.php", dirname(__FILE__)));
			$Product_Reviews_Widget = new Product_Reviews_Widget();
			// register My_Widget
			add_action( 'widgets_init', function(){ register_widget( 'Product_Reviews_Widget' ); });

			$plugin = plugin_basename(__FILE__);
			add_filter("plugin_action_links_$plugin", array( $this, 'plugin_settings_link' ));

			add_action( 'wp_enqueue_scripts', array(&$this, 'load_dashicons_front_end') );

		} // END public function __construct

		/**
		 * Activate the plugin
		 */
		public static function activate()
		{
			// Do nothing
		} // END public static function activate

		/**
		 * Deactivate the plugin
		 */
		public static function deactivate()
		{
			// Do nothing
		} // END public static function deactivate

		/**
    	 * Enable Dashicons for frontend
    	 */
    	public function load_dashicons_front_end() {
    	    wp_enqueue_style( 'dashicons' );
    	    wp_enqueue_style( 'PR-Widget', plugin_dir_url( __FILE__ ) . 'assets/style.css'  );
        }

		// Add the settings link to the plugins page
		function plugin_settings_link($links)
		{
			$settings_link = '<a href="options-general.php?page=wp_product_reviews">Settings</a>';
			array_unshift($links, $settings_link);
			return $links;
		}

	} // END class WP_Products_Reviews
} // END if(!class_exists('WP_Products_Reviews'))

if(class_exists('WP_Products_Reviews'))
{
	// Installation and uninstallation hooks
	register_activation_hook(__FILE__, array('WP_Products_Reviews', 'activate'));
	register_deactivation_hook(__FILE__, array('WP_Products_Reviews', 'deactivate'));

	// instantiate the plugin class
	$wp_products_reviews = new WP_Products_Reviews();

}
