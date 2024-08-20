<?php
/**
 * Related posts based on categories and tags.
 *
 */

 // $sidebar = get_theme_mod('rj_bookmarks_single_post_sidebar', true);
 //
 // if ($sidebar == 0){
 //   $post_count = 4;
 // }else {
 //   $post_count = 3;
 // }

$rj_bookmarks_related_args = array(
    // 'posts_per_page'    => absint( get_theme_mod( 'rj_bookmarks_related_posts_per_page', $post_count ) ),
    'post__not_in'      => array( get_the_ID() ),
);

$rj_bookmarks_related_tax_terms = wp_get_post_terms( get_the_ID(), 'category' );
$rj_bookmarks_related_terms_ids = array();
foreach( $rj_bookmarks_related_tax_terms as $tax_term ) {
	$rj_bookmarks_related_terms_ids[] = $tax_term->term_id;
}

$rj_bookmarks_related_args['category__in'] = $rj_bookmarks_related_terms_ids;

if(get_theme_mod('rj_bookmarks_related_post',true)==1){

$rj_bookmarks_related_posts = new WP_Query( $rj_bookmarks_related_args );

if ( $rj_bookmarks_related_posts->have_posts() ) : ?>
    <div class="rj-related-post">
        <h2 class="section-main-head">
          <?php echo esc_html(get_theme_mod('rj_bookmarks_related_post_title','Related Articles')); ?>
        </h2>
        <div class="rj-related-article">
          <?php while ( $rj_bookmarks_related_posts->have_posts() ) : $rj_bookmarks_related_posts->the_post(); ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class('rj-related-article-container'); ?>>
              <?php
              $image_id = get_post_thumbnail_id();
              $image_alt = get_post_meta($image_id, '_wp_attachment_image_alt', TRUE);
              $image_title = get_the_title($image_id);

              if(has_post_thumbnail()) { ?>
                <img src="<?php echo get_the_post_thumbnail_url(); ?>" alt="<?php echo esc_attr(($image_alt) ? $image_alt : get_the_title() ); ?>" title="<?php echo esc_attr(($image_title) ? $image_title : get_the_title() ); ?>">
              <?php }else { ?>
                <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/dummy-cat-image.jpg'); ?>" alt="<?php echo esc_attr(get_the_title()); ?>" title="<?php echo esc_attr(get_the_title()); ?>">
              <?php } ?>
              <div class="">

                <?php
                if(get_theme_mod('rj_bookmarks_related_post_cats', true) != '0'){
                  $categories = get_the_category();
                  if ( ! empty( $categories ) ) {
                    $first_category = $categories[0]; ?>

                    <a href="<?php echo esc_attr( esc_url( get_category_link( $first_category->term_id ) ) ); ?>" class="rj-post-cat d-inline-block mt-4 me-2" title="<?php echo esc_attr( $first_category->name ); ?>">
                      <?php echo esc_html($first_category->name ); ?>
                    </a>
                    <?php
                    // echo esc_html( $first_category->name );
                    /* foreach ($categories as $key => $category) { ?>
                      <a href="<?php echo esc_attr( esc_url( get_category_link( $categories[0]->term_id ) ) ); ?>" class="rj-post-cat me-2" title="<?php echo esc_attr( $category->name ); ?>">
                        <?php echo esc_html( $category->name ); ?>
                      </a>
                    <?php } */
                    }
                  }
                ?>

                <h3 class="rj-post-title mt-2">
                  <a href="<?php echo get_the_permalink(); ?>" title="<?php echo get_the_title(); ?>">
                    <?php echo get_the_title(); ?>
                  </a>
                </h3>
                <ul class="rj-post-metas mb-0">
                  <li>
                    <svg width="12" height="13" viewBox="0 0 12 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                      <path d="M5.99984 12.3333C2.77809 12.3333 0.166504 9.72171 0.166504 6.49996C0.166504 3.27821 2.77809 0.666626 5.99984 0.666626C9.22159 0.666626 11.8332 3.27821 11.8332 6.49996C11.8332 9.72171 9.22159 12.3333 5.99984 12.3333ZM5.99984 11.1666C7.23751 11.1666 8.4245 10.675 9.29967 9.79979C10.1748 8.92462 10.6665 7.73764 10.6665 6.49996C10.6665 5.26228 10.1748 4.0753 9.29967 3.20013C8.4245 2.32496 7.23751 1.83329 5.99984 1.83329C4.76216 1.83329 3.57518 2.32496 2.70001 3.20013C1.82484 4.0753 1.33317 5.26228 1.33317 6.49996C1.33317 7.73764 1.82484 8.92462 2.70001 9.79979C3.57518 10.675 4.76216 11.1666 5.99984 11.1666ZM6.58317 6.49996H8.9165V7.66663H5.4165V3.58329H6.58317V6.49996Z" fill="#8F90A6"/>
                    </svg>
                    <?php
                      $post_content = get_the_content();
                      $reading_time = rj_bookmarks_calculate_reading_time($post_content);
                      echo esc_html($reading_time . "Min Read", 'rj-bookmarks');
                    ?>
                  </li>
                  <li>
                      <svg width="14" height="9" viewBox="0 0 14 9" fill="none" xmlns="http://www.w3.org/2000/svg">
                      <path d="M2.59625 2.41317C3.68005 1.51482 5.2371 0.5625 7.00023 0.5625C8.76337 0.5625 10.3204 1.51482 11.4042 2.41317C11.9523 2.86749 12.3948 3.3208 12.7004 3.66052C12.8535 3.83067 12.9729 3.97306 13.0547 4.07384C13.0956 4.12425 13.1272 4.16431 13.1489 4.19229C13.1598 4.20628 13.1682 4.21726 13.1741 4.22501L13.1811 4.23418L13.1831 4.2369L13.1838 4.23779C13.1838 4.23779 13.1842 4.23836 12.8336 4.5C13.1842 4.76164 13.184 4.76189 13.184 4.76189L13.1831 4.7631L13.1811 4.76582L13.1741 4.77499C13.1682 4.78274 13.1598 4.79372 13.1489 4.80771C13.1272 4.83569 13.0956 4.87575 13.0547 4.92616C12.9729 5.02694 12.8535 5.16933 12.7004 5.33948C12.3948 5.6792 11.9523 6.13251 11.4042 6.58683C10.3204 7.48518 8.76337 8.4375 7.00023 8.4375C5.2371 8.4375 3.68005 7.48518 2.59625 6.58683C2.04814 6.13251 1.60567 5.6792 1.30006 5.33948C1.147 5.16933 1.0276 5.02694 0.945771 4.92616C0.904841 4.87575 0.873263 4.83569 0.851526 4.80771C0.840656 4.79372 0.832242 4.78274 0.826345 4.77499L0.819394 4.76582L0.817348 4.7631L0.816684 4.76221C0.816684 4.76221 0.816262 4.76164 1.1669 4.5C0.816262 4.23836 0.816443 4.23811 0.816443 4.23811L0.817348 4.2369L0.819394 4.23418L0.826345 4.22501C0.832242 4.21726 0.840656 4.20628 0.851526 4.19229C0.873263 4.16431 0.904841 4.12425 0.945771 4.07384C1.0276 3.97306 1.147 3.83067 1.30006 3.66052C1.60567 3.3208 2.04814 2.86749 2.59625 2.41317ZM1.1669 4.5L0.816443 4.23811C0.70063 4.39332 0.700448 4.60644 0.816262 4.76164L1.1669 4.5ZM1.72888 4.5C1.79036 4.5729 1.86455 4.65865 1.95057 4.75427C2.23532 5.0708 2.64712 5.49249 3.15464 5.91317C4.1821 6.76482 5.54171 7.5625 7.00023 7.5625C8.45876 7.5625 9.81837 6.76482 10.8458 5.91317C11.3533 5.49249 11.7651 5.0708 12.0499 4.75427C12.1359 4.65865 12.2101 4.5729 12.2716 4.5C12.2101 4.4271 12.1359 4.34135 12.0499 4.24573C11.7651 3.9292 11.3533 3.50751 10.8458 3.08683C9.81837 2.23518 8.45876 1.4375 7.00023 1.4375C5.54171 1.4375 4.1821 2.23518 3.15464 3.08683C2.64712 3.50751 2.23532 3.9292 1.95057 4.24573C1.86455 4.34135 1.79036 4.4271 1.72888 4.5ZM12.8336 4.5L13.184 4.76189C13.2998 4.60668 13.3 4.39356 13.1842 4.23836L12.8336 4.5Z" fill="#8F90A6"/>
                      <path d="M5.1044 4.5C5.1044 3.45297 5.9532 2.60417 7.00023 2.60417C8.04727 2.60417 8.89607 3.45297 8.89607 4.5C8.89607 5.54703 8.04727 6.39583 7.00023 6.39583C5.9532 6.39583 5.1044 5.54703 5.1044 4.5ZM7.00023 3.47917C6.43645 3.47917 5.9794 3.93622 5.9794 4.5C5.9794 5.06378 6.43645 5.52083 7.00023 5.52083C7.56402 5.52083 8.02107 5.06378 8.02107 4.5C8.02107 3.93622 7.56402 3.47917 7.00023 3.47917Z" fill="#8F90A6"/>
                      </svg>
                      <?php // echo rj_bookmarks_get_reading_count(get_the_ID()) . "Views"; ?>
                    </li>
                </ul>
              </div>
            </article>
          <?php endwhile; ?>
        </div>
    </div>
<?php endif;
wp_reset_postdata();

}
