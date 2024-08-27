<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package rj-mojo
 */

get_header(); ?>

<?php do_action( 'rj_bookmarks_page_post_top' ); ?>

<main class="">
    <div class="container rj-page-container">
      <div class="row">
        <div class="col-md-8">
          <?php while ( have_posts() ) : the_post();
              get_template_part( 'template-parts/content-page');
          endwhile; ?>
        </div>
        <aside class="col-md-4">
            <?php dynamic_sidebar('home-page');?>
        </aside>
      </div>

    </div>
</main>

<?php do_action( 'rj_bookmarks_page_post_bottom' ); ?>

<?php get_footer(); ?>
