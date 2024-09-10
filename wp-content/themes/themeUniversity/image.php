<?php
get_header();

?>

<div class="page-banner">
  <div class="page-banner__bg-image" style="background-image: url(<?php echo get_theme_file_uri('images/library-hero.jpg') ?>)"></div>
  <div class="page-banner__content container container--narrow">
    <h1 class="page-banner__title">Welcome to our blog!</h1>
    <div class="page-banner__intro">
      <p>Keep up with our latest news.</p>
    </div>
  </div>
</div>

<?php
 while (have_posts()):
    the_post();
 ?>
<div class="container container--narrow page-section">
  <div class="post-item">
    <h2 class="headline headline--medium headline--post-title">
      <a href="<?php echo get_permalink()?>"><?php echo get_the_title() ?></a>
    </h2>

    <div class="metabox">
      <p>
        Posted by
        <a href="" title="Posts by admin" rel="author"><?php the_author(); ?></a>
        on <?php the_date( 'j n, y' ); ?> at <?php the_time('g:i a'); ?>
       
        <a href="" rel="category tag"><?php  get_the_category_list(' / ') ?></a>
      </p>
    </div>

    <div class="generic-content">
      <p>
      <?php  the_excerpt()?>
      </p>
      <p>
        <a class="btn btn--blue" href="<?php echo get_permalink()?>">Continue reading &raquo;</a>
      </p>
    </div>
  </div>

</div>


<?php endwhile; ?>
<?php echo paginate_links() ?>
<?php

get_footer();