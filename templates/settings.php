<div class="wrap">
    <h2>Products Reviews</h2>
    <form method="post" action="options.php"> 
        <?php @settings_fields('wp_products_reviews-group'); ?>
        <?php @do_settings_fields('wp_products_reviews-group'); ?>

        <?php do_settings_sections('wp_products_reviews'); ?>

        <?php @submit_button(); ?>
    </form>
</div>