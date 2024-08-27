<?php
/**
 * The template for displaying image attachments.
 *
 * @package rj-mojo
 */

get_header(); ?>

<main>
  <div class="container">
     <?php
      if (have_posts()) :
         while (have_posts()) : the_post(); ?>
           <figure class="entry-attachment">
             <div class="attachment">
               <?php
               $attachment_size = apply_filters('rj_bookmarks_attachment_size', array(1200, 1200));
               printf('<a href="%1$s" rel="attachment" title="%2$s">%2$s</a>',
                 esc_url(wp_get_attachment_url()),
                 wp_get_attachment_image(get_the_ID(), $attachment_size)
               ); ?>
             </div>
             <?php if (has_excerpt()) : ?>
               <figcaption class="entry-caption"><?php the_excerpt(); ?></figcaption>
             <?php endif; ?>
           </figure>
         <?php
        endwhile;
        wp_reset_postdata();
     else :
         get_template_part('no-results');
     endif; ?>
   </div>
</main>

<?php get_footer(); ?>
