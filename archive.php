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
?>

<section id="rj-air-play">
    <div class="container py-5">
        <div class="rj-air-play-main"> 
            <div class="rj-air-play-col" style="background-color: <?php echo $color; ?>">
                <h2 class="rj-play-heading text-center"><?php echo $category->name; ?></h2>
                <p class="rj-play-para">
                    <?php echo $category->description; ?>
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
                $query = new WP_Query( $args );?>
                <div class="d-flex align-items-start">
                    <div class="nav flex-column nav-pills w-100" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                        <?php $i = 1; while ( $query->have_posts() ) :  $query->the_post();  ?>
                        <button class="nav-link <?php if($i==1){ echo 'active';} ?>" id="v-pills-<?php echo esc_attr($post->ID); ?>-tab" data-bs-toggle="pill" data-bs-target="#v-pills-<?php echo esc_attr($post->ID); ?>" type="button" role="tab" aria-controls="v-pills-<?php echo esc_attr($post->ID); ?>" aria-selected="true">
                            <?php echo esc_html(get_the_title()); ?>
                        </button>
                        <?php $i++; endwhile;  ?>
                    </div>
                </div>
            </div>

            <div class="rj-air-play-section">
                <div class="tab-content" id="v-pills-tabContent">
                <?php $i = 1; while ( $query->have_posts() ) :  $query->the_post();  ?>
                <div class="tab-pane fade <?php if($i==1){ echo 'show active'; } ?>" id="v-pills-<?php echo esc_attr($post->ID); ?>" role="tabpanel" aria-labelledby="v-pills-<?php echo esc_attr($post->ID); ?>-tab" tabindex="0">
                    <h3 class="rj-play-heading-text"><?php echo esc_html(get_the_title()); ?></h3>
                    <div class="slick-slider">
                        <?php while (have_posts()): the_post(); ?>
                            <div class="post-item">
                                <p><?php the_excerpt(); ?></p>
                                <?php the_post_thumbnail(); ?>
                            </div>
                        <?php endwhile; ?>
                    </div>
                </div>
                <?php $i++; endwhile;  ?>
                </div>
            </div>

            
        </div>
    </div>
</section>

<?php get_footer(); ?>
