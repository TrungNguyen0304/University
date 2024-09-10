<?php
get_header();
?>

<div class="page-banner">
    <div class="page-banner__bg-image"
        style="background-image: url(<?php echo get_theme_file_uri('images/library-hero.jpg') ?>)"></div>
    <div class="page-banner__content container t-center c-white">
        <h1 class="headline headline--large">Welcome!</h1>
        <h2 class="headline headline--medium">We think you&rsquo;ll like it here.</h2>
        <h3 class="headline headline--small">Why don&rsquo;t you check out the <strong>major</strong> you&rsquo;re
            interested in?</h3>
        <a href="#" class="btn btn--large btn--blue">Find Your Major</a>
    </div>
</div>

<div class="full-width-split group">
    <div class="full-width-split__one">
        <div class="full-width-split__inner">
            <h2 class="headline headline--small-plus t-center">Upcoming Events</h2>
            <?php
            // Query các bài viết loại 'event'
            $homepageEvents = new WP_Query(array(
                'posts_per_page' => 2,
                'post_type' => 'events',
                'meta_key' => 'event_date',
                'orderby' => 'meta_value_num',
                'order' => 'ASC',
                'meta_query' => array(
                    array(
                        'key' => 'event_date', // Trường meta để so sánh là 'event_date'
                        'value' => date('Y-m-d'), // Lấy giá trị là ngày hôm nay
                        'compare' => '>=', // So sánh để lấy các sự kiện diễn ra từ hôm nay trở về sau
                        'type' => 'DATE' // Đặt kiểu dữ liệu là DATE để so sánh đúng
                    )
                )
            ));

            // Vòng lặp để hiển thị các sự kiện
            while ($homepageEvents->have_posts()) :
                $homepageEvents->the_post();

                // Lấy giá trị 'start_day' từ meta field
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
            <?php endwhile; ?>

            <?php wp_reset_postdata(); // Đặt lại dữ liệu bài viết sau khi vòng lặp 
            ?>

            <p class="t-center no-margin">
                <a href="<?php echo get_post_type_archive_link('event'); ?>" class="btn btn--blue">View All Events</a>
            </p>
        </div>
    </div>


    <div class="full-width-split__two">
        <div class="full-width-split__inner">
            <h2 class="headline headline--small-plus t-center">Latest Blogs</h2>

            <?php
            // Query to get recent blogs
            $blogPosts = new WP_Query(array(
                'posts_per_page' => 2,
                'post_type' => 'blog',
                'orderby' => 'meta_value_num',
                'order' => 'ASC'

            ));

            // Loop through the blogs
            while ($blogPosts->have_posts()) : $blogPosts->the_post();
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
            <?php endwhile; ?>

            <?php
            // Reset post data
            wp_reset_postdata();
            ?>

            <p class="t-center no-margin">
                <a href="<?php echo esc_url(get_post_type_archive_link('blog')); ?>" class="btn btn--yellow">View All Blogs</a>
            </p>
        </div>
    </div>

</div>





<div class="hero-slider">
    <div data-glide-el="track" class="glide__track">
        <div class="glide__slides">
            <?php
            $sliderPosts = new WP_Query(array(
                'posts_per_page' => 3,
                'post_type' => 'slide'
            ));

            if ($sliderPosts->have_posts()) {
                while ($sliderPosts->have_posts()) {
                    $sliderPosts->the_post();
                    $backgroundImage = get_the_post_thumbnail_url(get_the_ID(), 'full');
                    $additionalImages = get_post_meta(get_the_ID(), 'additional_images', true);
            ?>
                    <div class="hero-slider__slide" style="background-image: url(<?php echo esc_url($backgroundImage); ?>)">
                        <div class="hero-slider__interior container">
                            <div class="hero-slider__overlay">
                                <h2 class="headline headline--medium t-center"><?php the_title(); ?></h2>
                                <p class="t-center"><?php echo wp_trim_words(get_the_excerpt(), 18); ?></p>
                                <p class="t-center no-margin"><a href="<?php echo esc_url(get_the_permalink()); ?>" class="btn btn--blue">Learn more</a></p>
                                <?php if ($additionalImages): ?>
                                    <div class="additional-images">
                                        <?php foreach ($additionalImages as $image): ?>
                                            <img src="<?php echo esc_url(trim($image)); ?>" alt="Additional Slide Image" />
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
            <?php
                }
            } else {
                echo '<p>No slides found.</p>';
            }
            wp_reset_postdata();
            ?>
        </div>
        <div class="slider__bullets glide__bullets" data-glide-el="controls[nav]"></div>
    </div>
</div>


<?php
get_footer();
