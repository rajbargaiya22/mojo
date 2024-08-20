<?php
/*
* Template Name: RJ Exhibits
*
* @package rj-bookmarks
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


get_template_part('template-parts/rj-banner');
?>


<section id="exhibits-section">
    <?php
        $args = array(
            'post_type'      => 'rj-exhibit', 
            'posts_per_page' => 8,
        );
        $exhibit_query = new WP_Query($args);
        if ($exhibit_query->have_posts()) : 
            while ($exhibit_query->have_posts()) : $exhibit_query->the_post(); 
            ?>
                <div class="exhibit-item">
                    <div class="exhibit-content">
                        <h2 class="exhibit-heading"><a href="<?php the_permalink(); ?>"><?php the_title();?></a></h2>
                        <?php /*the_post_thumbnail(); */?>
                        <p class="exhibit-para"><?php the_content(); ?></p>
                    </div>
                    <div class="text-end">
                        <?php $image_url = get_post_meta(get_the_ID(), 'rj_website_url', true);
                            if ($image_url) {
                            echo '<img src="' . esc_url($image_url) . '" alt="Exhibit Image" style="max-width: 100%; height: auto;" />';
                        }?>
                    </div>
                </div>
               
            <?php
            endwhile; 
            wp_reset_postdata(); 
        endif;
        ?>
</section>

<section id="exhibits-section">
    <?php
    $terms = get_terms(array(
        'taxonomy' => 'exhibit_category',
        'hide_empty' => false, 
    ));

    if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
        foreach ( $terms as $term ) {
            echo '<a href="' . esc_url( get_term_link( $term ) ) . '">';
            echo esc_html( $term->name );
        }
    }
    ?>
</section>

<?php get_footer();