<?php
/**
 * The Template for displaying all single posts.
 *
 * @package rj-mojo
 */
get_header('two');

$category = get_queried_object();
$color = get_term_meta($category->term_id, 'exhibit_category-color', true);
$color = $color ? $color : "#A3D9C3";
$image_2 = get_term_meta($category->term_id, 'exhibit_category-image-id-2', true);
$image_3 = get_term_meta($category->term_id, 'exhibit_category-image-id-3', true);
?>


<?php if ( 'product' == get_post_type() ) { ?>
    
<?php }else{ ?>
    <section id="rj-air-play">
        <div class="container py-5 position-relative">
            <div class="rj-air-play-main">
                <div class="rj-air-play-col" style="background-color: <?php echo esc_attr($color); ?>">
                    <?php if ($image_2): ?>
                        <div class="rj-category-image">
                            <?php echo wp_get_attachment_image($image_2); ?>
                        </div>
                    <?php endif; ?>
                    <h2 class="rj-play-heading text-center"><?php echo esc_html($category->name); ?></h2>
                    <p class="rj-play-para">
                        <?php echo esc_html($category->description); ?>
                    </p>
                    <?php
                    $args = array(
                        'tax_query' => array(
                            array(
                                'taxonomy'  => $category->taxonomy,
                                'field'     => 'term_id',
                                'terms'     => $category->term_id,
                            )
                        ),
                        'posts_per_page' => 8,
                    );
                    $query = new WP_Query($args); 
                    ?>

                    <div class="d-flex align-items-start">
                        <div class="nav flex-column nav-pills w-100" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                            <?php $i = 1; while ($query->have_posts()) : $query->the_post(); ?>
                                <button class="nav-link <?php if ($i == 1) { echo 'active'; } ?>" id="v-pills-<?php echo esc_attr($post->ID); ?>-tab" data-bs-toggle="pill" data-bs-target="#v-pills-<?php echo esc_attr($post->ID); ?>" type="button" role="tab" aria-controls="v-pills-<?php echo esc_attr($post->ID); ?>" aria-selected="true">
                                    <?php echo esc_html(get_the_title()); ?>
                                </button>
                            <?php $i++; endwhile; wp_reset_postdata(); ?>
                        </div>
                    </div>
                </div>

                <div class="rj-air-play-section">
                    <div class="tab-content" id="v-pills-tabContent">
                        <?php $i = 1; while ($query->have_posts()) : $query->the_post(); ?>
                            <div class="tab-pane fade <?php if ($i == 1) { echo 'show active'; } ?>" 
                                id="v-pills-<?php echo esc_attr($post->ID); ?>" 
                                role="tabpanel" 
                                aria-labelledby="v-pills-<?php echo esc_attr($post->ID); ?>-tab" 
                                tabindex="0">
                                <h3 class="rj-play-heading-text"><?php echo esc_html(get_the_title()); ?></h3>
                                <div class="post-item">
                                    <p><?php the_excerpt(); ?></p>
                                    <div class="slick-slider">
                                        <?php
                                        $images = get_post_meta(get_the_ID(), 'my_selected_images', true);
                                        if ($images) {
                                            foreach ($images as $image_id) {
                                                echo wp_get_attachment_image($image_id, 'full');
                                            }
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        <?php $i++; endwhile; wp_reset_postdata(); ?>
                    </div>
                </div>
            </div>

            <div class="rj-girl-img">
            <?php if ($image_3): ?>
                <?php echo wp_get_attachment_image($image_3, 'medium'); ?>
            <?php endif; ?>
            </div>
        </div>
    </section>
<?php } ?>

    

<?php get_footer(); ?>
