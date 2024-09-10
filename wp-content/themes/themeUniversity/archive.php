<?php get_header(); ?>

<div class="page-banner">
    <div class="page-banner__bg-image" style="background-image: url(<?php echo get_theme_file_uri('/images/ocean.jpg'); ?>);"></div>
    <div class="page-banner__content container container--narrow">
        <h1 class="page-banner__title"><?php the_archive_title(); ?></h1>
        <div class="page-banner__intro">
            <p><?php the_archive_description(); ?></p>
        </div>
    </div>
</div>

<div class="container container--narrow page-section">
    <?php
    if (have_posts()) :
        while (have_posts()) : the_post();
            $start_day = get_post_meta(get_the_ID(), 'start_day', true);
            $date_event = new DateTime($start_day);
            $month_event = $date_event->format('M');
            $day_event = $date_event->format('d');
    ?>
        <div class="event-summary">
            <a class="event-summary__date t-center" href="<?php the_permalink(); ?>">
                <span class="event-summary__month"><?php echo esc_html($month_event); ?></span>
                <span class="event-summary__day"><?php echo esc_html($day_event); ?></span>
            </a>
            <div class="event-summary__content">
                <h2 class="headline headline--medium headline--post-title">
                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                </h2>
                <div class="generic-content">
                    <?php the_excerpt(); ?>
                    <p><a class="btn btn--blue" href="<?php the_permalink(); ?>">Continue reading &raquo;</a></p>
                </div>
            </div>
        </div>
    <?php
        endwhile;
        echo paginate_links();
    else :
        echo '<p>No events found.</p>';
    endif;
    ?>
</div>

<?php get_footer(); ?>
