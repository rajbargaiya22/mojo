<?php
/*
* Template Name: RJ Exhibits
*
* @package rj-mojo
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
get_template_part('template-parts/rj-banner');
$category = get_queried_object();
?>

<section id="exhibits-section">
    <div class="exhibits-section container">
        <?php
        $terms = get_terms(array(
            'taxonomy' => 'exhibit_category',
            'hide_empty' => false, 
            'order' => 'ASC', 
            'parent'     => 0 
        ));
        if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
            foreach ( $terms as $term ) {
                $term_link = get_term_link( $term );
                $image_1 = get_term_meta($term->term_id, 'exhibit_category-image-id-1', true);
                ?>
                <div class="exhibit-item">
                    <div class="exhibit-content">
                        <a href="<?php echo esc_url($term_link); ?>">
                            <h2 class="exhibit-heading"><?php echo esc_html($term->name); ?></h2>
                        </a>
                        <?php if ( ! empty( $term->description ) ) : ?>
                            <div class="exhibit-para"><?php echo esc_html($term->description); ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="rj-category-images">
                        <?php if ($image_1): ?>
                            <div class="rj-category-image">
                                <?php echo wp_get_attachment_image($image_1, 'medium'); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php
            }
        } 
        ?>
    </div>
    <!-- Bottom Image Section -->
    <div class="rj-bottom-img">
        <img src="<?php echo esc_url(get_theme_mod('rj_mojo_bottom_image', get_template_directory_uri() . "/assets/images/exhibit-bottom-img.png")); ?>" 
             alt="<?php echo esc_attr(get_theme_mod('rj_mojo_slide_title', true)); ?>" 
             title="Exhibit Bottom Image">
    </div>
</section>

<?php get_footer(); ?>
