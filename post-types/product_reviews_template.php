<?php
if(!class_exists('Products_Reviews_Template'))
{
	/**
	 * A Products_Reviews_Template class that provides one additional meta field
	 */
	class Products_Reviews_Template
	{
		const POST_TYPE	= "products_review";
		private $_meta	= array(
			'ratings',
		);

    	/**
    	 * The Constructor
    	 */
    	public function __construct()
    	{
    		// register actions
    		add_action('init', array(&$this, 'init'));
    		add_action('admin_init', array(&$this, 'admin_init'));
    	} // END public function __construct()

    	/**
    	 * hook into WP's init action hook
    	 */
    	public function init()
    	{
    		// Initialize Post Type
    		$this->create_post_type();
    		$this->create_group_taxonomy();
    		add_action('save_post', array(&$this, 'save_post'));
    	} // END public function init()

    	/**
    	 * Create the post type
    	 */
    	public function create_post_type()
    	{

    	    $labels = array(
        		'name'               => _x( 'Products', 'Products Reviews'),
        		'singular_name'      => _x( 'Product', 'Products Reviews'),
        		'menu_name'          => _x( 'Products', 'Admin Menu'),
        		'name_admin_bar'     => _x( 'Product', 'Add new on admin bar'),
        		'add_new'            => _x( 'Add New', 'product'),
        		'add_new_item'       => __( 'Add New Product'),
        		'new_item'           => __( 'New Product'),
        		'edit_item'          => __( 'Edit Product'),
        		'view_item'          => __( 'View Product'),
        		'all_items'          => __( 'All Product'),
        		'search_items'       => __( 'Search Product'),
        		'parent_item_colon'  => __( 'Parent Product:'),
        		'not_found'          => __( 'No Product found.'),
        		'not_found_in_trash' => __( 'No Product found in Trash.')
        	);

    		register_post_type(self::POST_TYPE,
    			array(
    				'labels' => $labels,
    				'public' => true,
    				'has_archive' => false,
    				'description' => __("Products with Reviews Post Type"),
    				'rewrite' => array( 'slug' => 'product-review' ),
    				'supports' => array(
    					'title', 'thumbnail',
    				),
    			)
    		);
    	}

    	/**
    	 * Create the Target Taxonomy
    	 */
    	public function create_group_taxonomy()
    	{
    	    $labels = array(
        		'name'              => _x( 'Target Groups', 'Target Groups'),
        		'singular_name'     => _x( 'Target Group', 'taxonomy singular name'),
        		'search_items'      => __( 'Search Target Groups'),
        		'all_items'         => __( 'All Target Groups'),
        		'parent_item'       => __( 'Parent Target Group'),
        		'parent_item_colon' => __( 'Parent Target Group:'),
        		'edit_item'         => __( 'Edit Target Group'),
        		'update_item'       => __( 'Update Target Group'),
        		'add_new_item'      => __( 'Add New Target Group'),
        		'new_item_name'     => __( 'New Target Group Name'),
        		'menu_name'         => __( 'Target Groups'),
        	);

        	$args = array(
        		'hierarchical'      => true,
        		'labels'            => $labels,
        		'show_ui'           => true,
        		'show_admin_column' => true,
        		'query_var'         => true,
        		'rewrite'           => array( 'slug' => 'target-group' ),
        	);

        	register_taxonomy( 'target-group', array( self::POST_TYPE ), $args );
    	}

    	/**
    	 * Save the metaboxes for this custom post type
    	 */
    	public function save_post($post_id)
    	{
            // verify if this is an auto save routine.
            // If it is our form has not been submitted, so we dont want to do anything
            if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
            {
                return;
            }

    		if(isset($_POST['post_type']) && $_POST['post_type'] == self::POST_TYPE && current_user_can('edit_post', $post_id))
    		{
    			foreach($this->_meta as $field_name)
    			{
    				// Update the post's meta field
    				update_post_meta($post_id, $field_name, $_POST[$field_name]);
    			}
    		}
    		else
    		{
    			return;
    		} // if($_POST['post_type'] == self::POST_TYPE && current_user_can('edit_post', $post_id))
    	} // END public function save_post($post_id)

    	/**
    	 * hook into WP's admin_init action hook
    	 */
    	public function admin_init()
    	{
    		// Add metaboxes
    		add_action('add_meta_boxes', array(&$this, 'add_meta_boxes'));
    	} // END public function admin_init()

    	/**
    	 * hook into WP's add_meta_boxes action hook
    	 */
    	public function add_meta_boxes()
    	{
    		// Add this metabox to every selected post
    		add_meta_box(
    			sprintf('wp_product_reviews_%s_section', self::POST_TYPE),
    			sprintf('%s Information', ucwords(str_replace("_", " ", self::POST_TYPE))),
    			array(&$this, 'add_inner_meta_boxes'),
    			self::POST_TYPE
    	    );
    	} // END public function add_meta_boxes()

		/**
		 * called off of the add meta box
		 */
		public function add_inner_meta_boxes($post)
		{
			// Render the job order metabox
			include(sprintf("%s/../templates/%s_metabox.php", dirname(__FILE__), self::POST_TYPE));
		} // END public function add_inner_meta_boxes($post)

	} // END class Products_Reviews_Template
} // END if(!class_exists('Products_Reviews_Template'))
