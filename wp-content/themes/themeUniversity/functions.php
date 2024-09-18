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
function custom_login_logo_url_title()
{
	return 'Your Custom Title'; // Customize this text
}
add_filter('login_headertext', 'custom_login_logo_url_title');


function my_custom_login_logo_customizer($wp_customize)
{
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

function custom_login_logo()
{
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


function theme_customizer($wp_customize)
{
	$wp_customize->add_section('page_banner_section', array(
		'title' => __('Page Banner', 'textdomain'),
		'priority' => 30,
	));

	$wp_customize->add_setting('page_banner_image', array(
		'default' => get_theme_file_uri('images/library-hero.jpg'),
		'sanitize_callback' => 'esc_url_raw',
	));

	$wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'page_banner_image', array(
		'label' => __('Banner Image', 'textdomain'),
		'section' => 'page_banner_section',
		'settings' => 'page_banner_image',
	)));
}
add_action('customize_register', 'theme_customizer');
