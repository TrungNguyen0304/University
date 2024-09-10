<?php

/**
 * The template for displaying all single events
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package WordPress
 * @subpackage Twenty_Twenty_One
 * @since Twenty Twenty-One 1.0
 */

get_header(); ?>

<div class="page-banner">
    <div class="page-banner__bg-image"
        style="background-image: url(<?php echo get_theme_file_uri('/images/ocean.jpg'); ?>);"></div>
    <div class="page-banner__content container container--narrow">
        <h1 class="page-banner__title"><?php the_title(); ?></h1>
        <div class="page-banner__intro">
            <!-- Nếu bạn muốn hiển thị thêm thông tin như ngày tháng, bạn có thể thêm vào đây -->
            <?php
          
            ?>
        </div>
    </div>
</div>

<div class="container container--narrow page-section">
    <?php
    // Start the Loop
    while (have_posts()) :
        the_post();
    ?>
        <div class="event-content">
            <h2><?php the_title(); ?></h2>
            <div class="event-description">
                <?php the_content(); ?>
            </div>
        </div>
    <?php
    endwhile; // End of the loop.
    ?>
</div>

<?php get_footer(); ?>