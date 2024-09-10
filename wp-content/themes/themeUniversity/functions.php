<?php
if (!function_exists('them_style')) {
	function them_style()
	{

		wp_enqueue_script('main-university-js', get_theme_file_uri('/build/index.js'), array('jquery'), '1.0', true);
		wp_enqueue_style('custom-google-fonts', '//fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i');
		wp_enqueue_style('font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
		wp_enqueue_style('university_main_styles', get_theme_file_uri('/build/style-index.css'));
		wp_enqueue_style('university_extra_styles', get_theme_file_uri('/build/index.css'));
	}
}
add_action('wp_enqueue_scripts', 'them_style');

if (!function_exists('mytheme_register_nav_menu')) {

	function mytheme_register_nav_menu()
	{
		register_nav_menus(array(
			'primary_menu' => __('Menu chinh cua trang', 'university'),
			'footer_menu_1' => __('Menu footer vi tri 1', 'university'),
			'footer_menu_2' => __('Menu footer vi tri 2', 'university'),
		));
	}

	add_action('after_setup_theme', 'mytheme_register_nav_menu', 0);
}

function my_theme_setup()
{
	add_theme_support('post-thumbnails');
}
add_action('after_setup_theme', 'my_theme_setup');

// event
function register_event_post_type()
{
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
		'show_in_rest'          => true, // If using Gutenberg editor
	);

	register_post_type('events', $args);
}
add_action('init', 'register_event_post_type');

function add_event_meta_boxes()
{
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

function display_event_date_meta_box($post)
{
	$event_date = get_post_meta($post->ID, 'event_date', true);
	echo '<input type="date" name="event_date" value="' . esc_attr($event_date) . '" />';
}


function save_event_meta_boxes($post_id)
{
	if (array_key_exists('event_date', $_POST)) {
		update_post_meta(
			$post_id,
			'event_date',
			sanitize_text_field($_POST['event_date'])
		);
	}
}
add_action('save_post', 'save_event_meta_boxes');

function events_shortcode()
{
	ob_start();

	$homepageEvents = new WP_Query(array(
		'posts_per_page' => 2,
		'post_type' => 'events',
		'meta_key' => 'event_date',
		'orderby' => 'meta_value_num',
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
			$start_day = get_post_meta(get_the_ID(), 'start_day', true);
			$date_event = new DateTime($start_day);
			$month_event = $date_event->format('M');
			$day_event = $date_event->format('d');
?>
			<div class="event-summary">
				<a class="event-summary__date t-center" href="<?php echo get_the_permalink(); ?>">
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

// Custom Post TypeCustom 


function university_post_types()
{
	register_post_type('blog', array(
		'show_in_rest' => true, // Bật REST API cho loại bài viết này
		'supports' => array('title', 'editor', 'excerpt'), // Hỗ trợ tiêu đề, nội dung và trích dẫn
		'rewrite' => array('slug' => 'blogs'), // Đặt slug của loại bài viết
		'has_archive' => true, // Bật chế độ lưu trữ cho loại bài viết này
		'public' => true, // Đặt loại bài viết này là công khai
		'labels' => array(
			'name' => 'Blogs',
			'add_new_item' => 'Add New Blog',
			'edit_item' => 'Edit Blog',
			'all_items' => 'All Blogs',
			'singular_name' => 'Blog'
		),
		'menu_icon' => 'dashicons-admin-post' // Biểu tượng cho loại bài viết trong bảng điều khiển
	));
}

add_action('init', 'university_post_types');

// 

if (! function_exists('register_slide_post_type')) {
	function register_slide_post_type()
	{
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
			'show_in_rest'       => true, // If using Gutenberg editor
		);

		register_post_type('slide', $args);
	}
	add_action('init', 'register_slide_post_type');
}

function add_slide_meta_boxes()
{
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

function slide_images_meta_box_callback($post)
{
	wp_nonce_field(basename(__FILE__), 'slide_images_nonce');
	$stored_meta = get_post_meta($post->ID, 'additional_images', true);
	$additional_images = !empty($stored_meta) ? maybe_unserialize($stored_meta) : array();
	?>
	<div id="slide-images-container">
		<p>
			<label for="additional_images">Additional Images (comma separated URLs)</label><br>
			<input type="text" name="additional_images" id="additional_images" value="<?php echo esc_attr(implode(',', $additional_images)); ?>" size="70" />
			<input type="button" id="upload_image_button" class="button" value="Upload Image" />
		</p>
		<div id="additional-images-preview">
			<?php foreach ($additional_images as $image_url) : ?>
				<div class="image-item">
					<img src="<?php echo esc_url($image_url); ?>" style="max-width: 150px; height: auto;" />
					<input type="button" class="remove-image-button button" value="Remove" />
				</div>
			<?php endforeach; ?>
		</div>
	</div>
	<?php
}

function save_slide_meta_box_data($post_id)
{
	if (! isset($_POST['slide_images_nonce']) || ! wp_verify_nonce($_POST['slide_images_nonce'], basename(__FILE__))) {
		return $post_id;
	}

	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
		return $post_id;
	}

	if (! current_user_can('edit_post', $post_id)) {
		return $post_id;
	}

	$new_meta_value = isset($_POST['additional_images']) ? array_map('esc_url_raw', explode(',', $_POST['additional_images'])) : array();
	update_post_meta($post_id, 'additional_images', $new_meta_value);
}
add_action('save_post', 'save_slide_meta_box_data');

// Shortcode for displaying slides
function slides_shortcode()
{
	ob_start();

	$homepageSlides = new WP_Query(array(
		'posts_per_page' => 2,
		'post_type' => 'slide',
		'meta_key' => 'slide_date', // Adjust if you use a date field
		'orderby' => 'meta_value',
		'order' => 'ASC',
		'meta_query' => array(
			array(
				'key' => 'slide_date', // Adjust if you use a date field
				'value' => date('Y-m-d'),
				'compare' => '>=',
				'type' => 'DATE'
			)
		)
	));

	if ($homepageSlides->have_posts()) :
		while ($homepageSlides->have_posts()) : $homepageSlides->the_post();
			$additional_images = get_post_meta(get_the_ID(), 'additional_images', true);
	?>
			<div class="slide-summary">
				<a class="slide-summary__link t-center" href="<?php echo get_the_permalink(); ?>">
					<?php if (!empty($additional_images)) : ?>
						<?php foreach ($additional_images as $image_url) : ?>
							<img src="<?php echo esc_url($image_url); ?>" style="max-width: 100%; height: auto;" />
						<?php endforeach; ?>
					<?php endif; ?>
				</a>
				<div class="slide-summary__content">
					<h5 class="slide-summary__title headline headline--tiny">
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
		echo '<p>No slides found.</p>';
	endif;

	return ob_get_clean();
}
add_shortcode('slides_list', 'slides_shortcode');



// Change the login logo title
function custom_login_logo_url_title() {
    return 'Your Custom Title'; // Customize this text
}
add_filter('login_headertext', 'custom_login_logo_url_title');


function my_custom_login_logo_customizer($wp_customize) {
    $wp_customize->add_section('my_custom_login_logo_section', array(
        'title'       => __('Login Logo', 'themeUniversity'),
        'priority'    => 30,
        'description' => 'Customize the login logo.',
    ));

    $wp_customize->add_setting('my_custom_login_logo', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ));

    if (class_exists('WP_Customize_Image_Control')) {
        $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'my_custom_login_logo', array(
            'label'    => __('Upload Login Logo', 'themeUniversity'),
            'section'  => 'my_custom_login_logo_section',
            'settings' => 'my_custom_login_logo',
        )));
    }
}
add_action('customize_register', 'my_custom_login_logo_customizer');

function custom_login_logo() {
    $custom_logo_url = get_theme_mod('my_custom_login_logo');
    if ($custom_logo_url) {
        echo '<style type="text/css">
            #login h1 a {
                background-image: url(' . esc_url($custom_logo_url) . ');
                background-size: contain;
                width: 100%;
                height: 80px;
            }
        </style>';
    }
}
add_action('login_enqueue_scripts', 'custom_login_logo');