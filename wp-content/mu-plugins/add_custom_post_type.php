<?php
// Register Event Post Type
function register_event_post_type() {
    $labels = array(
        'name'                  => _x('Events', 'post type general name', 'textdomain'),
        'singular_name'         => _x('Event', 'post type singular name', 'textdomain'),
        'menu_name'             => _x('Events', 'admin menu', 'textdomain'),
        'name_admin_bar'        => _x('Event', 'add new on admin bar', 'textdomain'),
        'add_new'               => _x('Add New', 'event', 'textdomain'),
        'add_new_item'          => __('Add New Event', 'textdomain'),
        'new_item'              => __('New Event', 'textdomain'),
        'edit_item'             => __('Edit Event', 'textdomain'),
        'view_item'             => __('View Event', 'textdomain'),
        'all_items'             => __('All Events', 'textdomain'),
        'search_items'          => __('Search Events', 'textdomain'),
        'parent_item_colon'     => __('Parent Events:', 'textdomain'),
        'not_found'             => __('No events found.', 'textdomain'),
        'not_found_in_trash'    => __('No events found in Trash.', 'textdomain'),
    );

    $args = array(
        'labels'                => $labels,
        'public'                => true,
        'publicly_queryable'    => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'query_var'             => true,
        'rewrite'               => array('slug' => 'events'),
        'capability_type'       => 'post',
        'has_archive'           => true,
        'hierarchical'          => false,
        'menu_position'         => 20,
        'supports'              => array('title', 'editor', 'excerpt', 'thumbnail'),
        'show_in_rest'          => true,
    );

    register_post_type('events', $args);
}
add_action('init', 'register_event_post_type');

// Add Event Date Meta Box
function add_event_meta_boxes() {
    add_meta_box(
        'event_date_meta_box',
        'Event Date',
        'display_event_date_meta_box',
        'events',
        'side',
        'default'
    );
}
add_action('add_meta_boxes', 'add_event_meta_boxes');

// Display Event Date Meta Box
function display_event_date_meta_box($post) {
    $event_date = get_post_meta($post->ID, 'event_date', true);
    wp_nonce_field(basename(__FILE__), 'event_date_nonce');
    echo '<input type="date" name="event_date" value="' . esc_attr($event_date) . '" />';
}

// Save Event Meta Box Data
function save_event_meta_boxes($post_id) {
    if (!isset($_POST['event_date_nonce']) || !wp_verify_nonce($_POST['event_date_nonce'], basename(__FILE__))) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (isset($_POST['event_date'])) {
        update_post_meta(
            $post_id,
            'event_date',
            sanitize_text_field($_POST['event_date'])
        );
    }
}
add_action('save_post', 'save_event_meta_boxes');

// Events Shortcode
function events_shortcode() {
    ob_start();

    $homepageEvents = new WP_Query(array(
        'posts_per_page' => 2,
        'post_type' => 'events',
        'meta_key' => 'event_date',
        'orderby' => 'meta_value',
        'order' => 'ASC',
        'meta_query' => array(
            array(
                'key' => 'event_date',
                'value' => date('Y-m-d'),
                'compare' => '>=',
                'type' => 'DATE'
            )
        )
    ));

    if ($homepageEvents->have_posts()) :
        while ($homepageEvents->have_posts()) : $homepageEvents->the_post();
            $event_date = get_post_meta(get_the_ID(), 'event_date', true);
            $date_event = new DateTime($event_date);
            $month_event = $date_event->format('M');
            $day_event = $date_event->format('d');
?>
            <div class="event-summary">
                <a class="event-summary__date t-center" href="<?php echo esc_url(get_the_permalink()); ?>">
                    <span class="event-summary__month"><?php echo esc_html($month_event); ?> </span>
                    <span class="event-summary__day"><?php echo esc_html($day_event); ?></span>
                </a>
                <div class="event-summary__content">
                    <h5 class="event-summary__title headline headline--tiny">
                        <a href="<?php echo esc_url(get_the_permalink()); ?>"><?php echo esc_html(get_the_title()); ?></a>
                    </h5>
                    <p>
                        <?php
                        if (has_excerpt())
                            echo esc_html(get_the_excerpt());
                        else
                            echo wp_trim_words(get_the_content(), 18);
                        ?>
                        <a href="<?php echo esc_url(get_the_permalink()); ?>" class="nu gray">Learn more</a>
                    </p>
                </div>
            </div>
<?php
        endwhile;
        wp_reset_postdata();
    else :
        echo '<p>No events found.</p>';
    endif;

    return ob_get_clean();
}
add_shortcode('events_list', 'events_shortcode');

// Register Blog Post Type
function university_post_types() {
    register_post_type('blog', array(
        'show_in_rest' => true,
        'supports' => array('title', 'editor', 'excerpt', 'thumbnail'),
        'rewrite' => array('slug' => 'blogs'),
        'has_archive' => true,
        'public' => true,
        'labels' => array(
            'name' => 'Blogs',
            'add_new_item' => 'Add New Blog',
            'edit_item' => 'Edit Blog',
            'all_items' => 'All Blogs',
            'singular_name' => 'Blog'
        ),
        'menu_icon' => 'dashicons-admin-post'
    ));
}
add_action('init', 'university_post_types');

// Register Custom Taxonomies
function register_custom_taxonomies() {
    $labels = array(
        'name'              => _x('Categories', 'taxonomy general name', 'textdomain'),
        'singular_name'     => _x('Category', 'taxonomy singular name', 'textdomain'),
        'search_items'      => __('Search Categories', 'textdomain'),
        'all_items'         => __('All Categories', 'textdomain'),
        'parent_item'       => __('Parent Category', 'textdomain'),
        'parent_item_colon' => __('Parent Category:', 'textdomain'),
        'edit_item'         => __('Edit Category', 'textdomain'),
        'update_item'       => __('Update Category', 'textdomain'),
        'add_new_item'      => __('Add New Category', 'textdomain'),
        'new_item_name'     => __('New Category Name', 'textdomain'),
        'menu_name'         => __('Categories', 'textdomain'),
    );

    $args = array(
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'category'),
        'show_in_rest'      => true,
    );

    register_taxonomy('category', array('blog'), $args);
}
add_action('init', 'register_custom_taxonomies');

// Register Slide Post Type
function register_slide_post_type() {
    $labels = array(
        'name'               => _x('Slides', 'post type general name', 'textdomain'),
        'singular_name'      => _x('Slide', 'post type singular name', 'textdomain'),
        'menu_name'          => _x('Slides', 'admin menu', 'textdomain'),
        'name_admin_bar'     => _x('Slide', 'add new on admin bar', 'textdomain'),
        'add_new'            => _x('Add New', 'slide', 'textdomain'),
        'add_new_item'       => __('Add New Slide', 'textdomain'),
        'new_item'           => __('New Slide', 'textdomain'),
        'edit_item'          => __('Edit Slide', 'textdomain'),
        'view_item'          => __('View Slide', 'textdomain'),
        'all_items'          => __('All Slides', 'textdomain'),
        'search_items'       => __('Search Slides', 'textdomain'),
        'parent_item_colon'  => __('Parent Slides:', 'textdomain'),
        'not_found'          => __('No slides found.', 'textdomain'),
        'not_found_in_trash' => __('No slides found in Trash.', 'textdomain'),
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array('slug' => 'slides'),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => 20,
        'supports'           => array('title', 'editor', 'thumbnail', 'excerpt'),
        'show_in_rest'       => true,
    );

    register_post_type('slide', $args);
}
add_action('init', 'register_slide_post_type');

// Add Slide Meta Boxes
function add_slide_meta_boxes() {
    add_meta_box(
        'slide_images_meta_box',
        'Additional Images',
        'slide_images_meta_box_callback',
        'slide',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'add_slide_meta_boxes');

// Save Slide Meta Box Data
function save_slide_meta_box_data($post_id) {
    if (!isset($_POST['slide_images_nonce']) || !wp_verify_nonce($_POST['slide_images_nonce'], basename(__FILE__))) {
        return $post_id;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return $post_id;
    }

    if (!current_user_can('edit_post', $post_id)) {
        return $post_id;
    }

    $new_meta_value = isset($_POST['additional_images']) ? array_map('esc_url_raw', explode(',', $_POST['additional_images'])) : array();
    update_post_meta($post_id, 'additional_images', $new_meta_value);
}
add_action('save_post', 'save_slide_meta_box_data');
?>
