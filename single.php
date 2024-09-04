<?php
/**
 * The Template for displaying all single posts.
 *
 * @package rj-mojo
 */

 get_header('two'); 

?>

<section id="rj-air-play">
    <div class="container py-5">
        <div class="rj-air-play-main"> 
            <div class="">
                <div class="rj-air-play-col">
                    <h2 class="rj-play-heading text-center"><?php echo esc_html(get_theme_mod('rj_mojo_air_play_heading', 'Air Play')); ?></h2>
                    <p class="rj-play-para"><?php echo esc_html(get_theme_mod('rj_mojo_air_play_text', 'Ever wondered how planes stay up in the sky? Or how air can hold things up? Discover the exciting atmosphere of flight and physics!')); ?></p>
                    <?php
                    $categories = get_terms(array(
                        'taxonomy' => 'category',
                        'hide_empty' => true,
                    ));
                    ?>
                    <div class="d-flex align-items-start">
                        <div class="nav flex-column nav-pills me-3 w-100" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                            <?php $active = 'active'; foreach ($categories as $category) { ?>
                            <button class="nav-link <?php echo esc_attr($active); ?>" id="v-pills-<?php echo esc_attr($category->term_id); ?>-tab" data-bs-toggle="pill" data-bs-target="#v-pills-<?php echo esc_attr($category->term_id); ?>" type="button" role="tab" aria-controls="v-pills-<?php echo esc_attr($category->term_id); ?>" aria-selected="true"><?php echo esc_html($category->name); ?></button>
                            <?php $active = ''; }  ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="rj-air-play-section">
                <div class="tab-content" id="v-pills-tabContent">
                    <?php $active = 'show active';
                    foreach ($categories as $category) {
                        $args = array(
                            'category' => $category->term_id,
                            'posts_per_page' => 5,
                        );
                        $posts = get_posts($args);
                        ?>
                        <div class="tab-pane fade <?php echo esc_attr($active); ?>" id="v-pills-<?php echo esc_attr($category->term_id); ?>" role="tabpanel" aria-labelledby="v-pills-<?php echo esc_attr($category->term_id); ?>-tab" tabindex="0">
                            <h3 class="rj-play-heading-text"><?php echo esc_html($category->name); ?></h3>
                            <?php if ($posts): ?>
                                <?php foreach ($posts as $post): setup_postdata($post); ?>
                                    <div class="post-item">
                                        <h4><?php the_title(); ?></h4>
                                        <p><?php the_excerpt(); ?></p>
                                        <?php the_post_thumbnail(); ?>
                                    </div>
                                <?php endforeach; wp_reset_postdata(); ?>
                            <?php endif; ?>
                        </div>
                        <?php $active = ''; } ?>
                </div>
            </div>
        </div>
    </div>
</section>


<?php get_footer();