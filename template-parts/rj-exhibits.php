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
    <div class="exhibits-section container">
        <?php
        $terms = get_terms(array(
            'taxonomy' => 'exhibit_category',
            'hide_empty' => false, 
        ));
        if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
            foreach ( $terms as $term ) {
                $term_link = get_term_link( $term );
                $image_id = get_term_meta($term->term_id, 'exhibit_category-image-id', true);
                $image_url = wp_get_attachment_image_url($image_id);
                echo '<div class="exhibit-item">';
                    echo '<div class="exhibit-content">';
                        echo '<a href="' . esc_url( $term_link ) . '">';
                        echo '<h2 class="exhibit-heading">' . esc_html( $term->name ) . '</h2>';
                        echo '</a>';
                        if ( ! empty( $term->description ) ) {
                            echo '<div class="exhibit-para">' . esc_html( $term->description ) . '</div>';
                        }
                    echo '</div>'; 
                    if ( ! empty( $image_url ) ) {
                        echo '<div class="text-end exhibit-img">';
                            echo '<img src="' . esc_url( $image_url ) . '" alt="' . esc_attr( $term->name ) . '" style="max-width: 100%; height: auto;" />';
                        echo '</div>';
                    }
                echo '</div>'; 
                
            }
        }
        ?>
    </div>
</section>



<?php get_footer();