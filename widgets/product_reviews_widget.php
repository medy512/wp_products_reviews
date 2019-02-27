<?php
if(!class_exists('Product_Reviews_Widget'))
{
	/**
	 * A Product_Reviews_Widget class that registers widget
	 */
	class Product_Reviews_Widget extends WP_Widget
	{
		public function __construct() {
    		// Instantiate the parent object
    		$widget_ops = array(
        		'classname' => 'products_reviews',
        		'description' => 'Sidebar Widget for Products Reviews',
        	);
        	parent::__construct( 'products_reviews', 'Product Reviews', $widget_ops );
    	}

    	public function widget( $args, $instance ) {

    	    // Widget output
    		echo $args['before_widget'];

        	if ( ! empty( $instance['title'] ) ) {
        		echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
        	}

        	$target_group = (isset($_GET['target']) && $_GET['target'] != '')?$this->is_target($_GET['target'], $instance['target_group']):$this->is_target($instance['target_group']);

        		$p_args = array(
                	'post_type' => 'products_review',
                	'orderby'   => 'meta_value_num',
                	'meta_key'  => 'ratings',
                	'tax_query' => array(
                		array(
                			'taxonomy' => 'target-group',
                			'field'    => 'slug',
                			'terms'    => $target_group,
                		),
                	),
                );

                $products_reviews = new WP_Query( $p_args );

                $result = '';

                // The Loop
                if ( $products_reviews->have_posts() ) {
                	$result .= '<ul class="pr_items">';
                	while ( $products_reviews->have_posts() ) {
                		$products_reviews->the_post();

                		global $post;

                		$title = get_the_title();
                		$imgUrl = get_the_post_thumbnail_url();
                		$img = (isset($imgUrl) && $imgUrl != '')?$imgUrl:get_option('default_image_url');
                		$image = (isset($img) && $img != '')?'<img src="' . $img . '" alt="' . $title . '" />':'';
                		$ratings_value = get_post_meta($post->ID, 'ratings', true);
                		$ratings_value = (isset($ratings_value) && $ratings_value > 0)?$ratings_value:1;
                		$empty = 5 - $ratings_value;

                		// get stars HTML output based on what values are saved in the database
                		$filled = $this->stars('filled', $ratings_value);
                		$empty_stars = $this->stars('empty', $empty);

                		$ratings = $filled . $empty_stars;

                		$result .= '<li class="pr_item"><div class="pr_img" style="background: url(\'' . $img . '\') no-repeat;">' . $image . '</div>';
                		$result .= '<div class="pr_ratings">' . $ratings . '</div>';
                		$result .= '<div class="pr_title">' . $title . '</div></li>';

                	}
                	$result .= '</ul>';

                	echo $result;

                	/* Restore original Post Data */
                	wp_reset_postdata();
                } else {
                	echo esc_html__( 'No products found!' );
                }

        	echo $args['after_widget'];
    	}

    	/**
    	 * Stars Output using DashIcons
    	 * $type = filled / empty
    	 * $number = 1 / 2 / 3 / 4 / 5
    	 */
    	public function stars($type = 'filled', $number = 1){
    	    $output = '';
    	    for($i=1; $i <= $number; $i++){
    	        $output .= '<span class="dashicons dashicons-star-' . $type . '"></span>';
    	    }

    	    return $output;
    	}

    	/**
    	 * Checking if Target passed in URL exists
    	 * $target = passed in the URL using $_GET['target']
    	 * $default = Default value from Settings
    	 */
    	public function is_target($target = '', $default = ''){
    	    $is_target_exists = get_term_by('slug', $target, 'target-group');

    	    $default = ($default != '')?$default:get_option('previews_target');

    	    return (isset($is_target_exists->slug) && $is_target_exists->slug != '')?$is_target_exists->slug:$default;
    	}

    	public function update( $new_instance, $old_instance ) {
    		// Save widget options
    		$instance = array();
        	$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';

        	$instance['target_group'] = ( ! empty ( $new_instance['target_group'] ) ) ? $new_instance['target_group'] : '';

        	return $instance;
    	}

    	public function form( $instance ) {
    		// Output admin widget options form
    		$title = ! empty( $instance['title'] ) ? $instance['title'] : esc_html__( 'Cool Stuff' );
    		$target_group = ! empty( $instance['target_group'] ) ? $instance['target_group'] : esc_html__( '' );
    		$target_group = esc_attr($target_group);
    		$terms = get_terms( 'target-group', array(
                        'hide_empty' => false,
                    ) );

            $options = '<option value="">Select Target Group</option>';

            if(isset($terms) && count($terms) > 0){
                foreach($terms as $term){
                    $selected = ($term->slug == $target_group)?'selected':'';
                    $options .= '<option value="' . $term->slug . '" ' . $selected . '>' . $term->name . '</option>';
                }
            } ?>
    		<p>
    		    <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"> <?php esc_attr_e( 'Widget Title:' ); ?> </label>
    		    <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>"  type="text" value="<?php echo esc_attr( $title ); ?>" />
        	</p>
        	<p>
    		    <label for="<?php echo esc_attr( $this->get_field_id( 'target_group' ) ); ?>"> <?php esc_attr_e( 'Target Group:' ); ?> </label>
    		    <select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'target_group' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'target_group' ) ); ?>">
    		        <?php echo $options; ?>
    		    </select>
        	</p><?php
    	}

	} // END class Products_Reviews_Template
} // END if(!class_exists('Products_Reviews_Template'))
