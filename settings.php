<?php
// exit if file is called directly
if ( ! defined( 'ABSPATH' ) ) {

	exit;

}

if(!class_exists('WP_Products_Reviews_Settings'))
{
	class WP_Products_Reviews_Settings
	{
		/**
		 * Construct the plugin object
		 */
		public function __construct()
		{
			// register actions
            add_action('admin_init', array(&$this, 'admin_init'));
        	add_action('admin_menu', array(&$this, 'add_menu'));
		} // END public function __construct

        /**
         * hook into WP's admin_init action hook
         */
        public function admin_init()
        {
        	// register your plugin's settings
        	register_setting('wp_products_reviews-group', 'previews_target');
        	register_setting('wp_products_reviews-group', 'default_image_url');

        	// add your settings section
        	add_settings_section(
        	    'wp_products_reviews-section',
        	    'WP Products Reviews Settings',
        	    array(&$this, 'settings_section_wp_products_reviews'),
        	    'wp_products_reviews'
        	);

        	// add your setting's fields
            add_settings_field(
                'wp_products_reviews-previews_target',
                'Default Target Group',
                array(&$this, 'settings_field_select'),
                'wp_products_reviews',
                'wp_products_reviews-section',
                array(
                    'field' => 'previews_target'
                )
            );

           	// add your setting's fields
            add_settings_field(
                'wp_products_reviews-default_image_url',
                'Default Product Placeholder Image URL: ',
                array(&$this, 'settings_field_input_text'),
                'wp_products_reviews',
                'wp_products_reviews-section',
                array(
                    'field' => 'default_image_url'
                )
            );
            // Possibly do additional admin_init tasks
        } // END public static function activate

        public function settings_section_wp_products_reviews()
        {
            // Think of this as help text for the section.
            echo 'These settings do things for the Products Reviews.';
        }

        /**
         * This function provides text inputs for settings fields
         */
        public function settings_field_select($args)
        {
            // Get the field name from the $args array
            $field = $args['field'];
            // Get the value of this setting
            $value = get_option($field);
            // echo a proper input type="text"
            $terms = get_terms( 'target-group', array(
                        'hide_empty' => false,
                    ) );

            $options = '<option value="">Select Target Group</option>';

            if(isset($terms) && count($terms) > 0){
                foreach($terms as $term){
                    $selected = ($term->slug == $value)?'selected':'';
                    $options .= '<option value="' . $term->slug . '" ' . $selected . '>' . $term->name . '</option>';
                }
            }

            echo sprintf('<select name="%s" id="%s">%s</select>', $field, $field, $options);
        } // END public function settings_field_select($args)

         /**
         * This function provides text inputs for settings fields
         */
        public function settings_field_input_text($args)
        {
            // Get the field name from the $args array
            $field = $args['field'];
            // Get the value of this setting
            $value = get_option($field);
            // echo a proper input type="text"

            echo sprintf('<input type="text" name="%s" id="%s" value="%s" />', $field, $field, $value);
        } // END public function settings_field_input_text($args)

        /**
         * add a menu
         */
        public function add_menu()
        {
            // Add a page to manage this plugin's settings
        	add_options_page(
        	    'Products Reviews Settings',
        	    'Products Reviews',
        	    'manage_options',
        	    'wp_products_reviews',
        	    array(&$this, 'plugin_settings_page')
        	);
        } // END public function add_menu()

        /**
         * Menu Callback
         */
        public function plugin_settings_page()
        {
        	if(!current_user_can('manage_options'))
        	{
        		wp_die(__('You do not have sufficient permissions to access this page.'));
        	}

        	// Render the settings template
        	include(sprintf("%s/templates/settings.php", dirname(__FILE__)));
        } // END public function plugin_settings_page()
    } // END class WP_Products_Reviews_Settings
} // END if(!class_exists('WP_Products_Reviews_Settings'))
