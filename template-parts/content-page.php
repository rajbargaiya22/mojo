<?php
/**
 * The template part for displaying page content
 *
 * @package RJ Bookmarks
 */

$headh1 = get_theme_mod('rj_bookmarks_site_title', true); ?>

<div>
  <?php echo ($headh1 == 0) ? '<h1 class="rj-paged-title">' : '<h2 class="rj-paged-title">';
     the_title();
  echo ($headh1 == 0) ? '</h1>' : '</h2>'; ?>
  <?php if(has_post_thumbnail()) {?>
    <img class="page-image" src="<?php the_post_thumbnail_url('full'); ?>" alt="<?php the_title(); ?>">
  <?php }?>
  <?php the_content();?>
  <?php wp_link_pages(); ?>
  <?php
    if ( comments_open() || get_comments_number() ) :
      comments_template();
    endif;
  ?>
</div>
