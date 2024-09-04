<?php
/*
*
* @package rj-mojo
*/
?>

<section id="top-section" class="top-section">
    <div class="rj-home-grid">
        <?php get_template_part('template-parts/header/site-logo'); ?>
        <div class="rj-make-ideas">
            <div class="girl-image-box">
                <div class="light"></div>
                <img src="<?php echo esc_url(get_theme_mod('rj_mojo_make_ideas_image', get_template_directory_uri() . "/assets/images/girl.png")); ?>" alt="<?php echo esc_attr(get_theme_mod('rj_mojo_slide_title', true)); ?>" title="Girl Image">
            </div>
            <h2><?php echo esc_html(get_theme_mod('rj_mojo_make_ideas_text','Make your ideas alive!'));?></h2> 
        </div>
        <?php /*		
        <div class="rj-book-seat">
            <a href="<?php echo esc_url(get_theme_mod('rj_mojo_book_url',false));?>">
            <h2 class="rj-visit-text"><?php esc_html_e('Book Visit', 'rj-mojo'); ?></h2></a>
        </div> */?>
        <div class="rj-imagination-fly">	
            <?php // get_template_part('template-parts/star'); ?>
            <img src="<?php echo esc_url(get_theme_mod('rj_mojo_star_image', get_template_directory_uri() . "/assets/images/star.gif")); ?>" alt="<?php echo esc_attr(get_theme_mod('rj_mojo_slide_title', true)); ?>" title="Star Image">
            <h2><?php echo esc_html(get_theme_mod('rj_mojo_star_text','Let your imagination fly'));?></h2>
        </div>

        <div class="rj-menus-header d-flex align-items-center 
        <?php if( get_theme_mod( 'rj_mojo_sticky_header', true) == 1) { echo 'header-sticky'; }else{ echo 'close-sticky'; } ?>">
        <a href="https://www.facebook.com/profile.php?id=61565623700874&sk=about" target="_blank">
            <img src="<?php echo esc_url(get_theme_mod('rj_mojo_facebook_image', get_template_directory_uri() . '/assets/images/facebook.png')); ?>" alt="<?php echo esc_attr(get_theme_mod('rj_mojo_slide_title', 'Facebook Image')); ?>" title="Facebook Image">
        </a>
        <a href="https://www.instagram.com/mojonagpur/?hl=en" target="_blank">
            <img src="<?php echo esc_url(get_theme_mod('rj_mojo_instagram_image', get_template_directory_uri() . '/assets/images/instagram.png')); ?>" alt="<?php echo esc_attr(get_theme_mod('rj_mojo_slide_title', 'Instagram Image')); ?>" title="Instagram Image">
        </a>

            <?php /* get_template_part('template-parts/header/menu'); */?>
        </div>

        <div class="rj-tree-text">
            <h2><?php echo esc_html(get_theme_mod('rj_mojo_tree_text','Play, Discover, Build and Grow'));?></h2>
            
            <img src="<?php echo esc_url(get_theme_mod('rj_mojo_chemical_image', get_template_directory_uri() . "/assets/images/tree.png")); ?>" alt="<?php echo esc_attr(get_theme_mod('rj_mojo_slide_title', true)); ?>" title="Chemical Image">
            <?php // get_template_part('template-parts/tree'); ?>
        </div>

        <div class="rj-subject-text">
            <span class="chemical-component" ><?php get_template_part('template-parts/component'); ?></span>
            <ul>
                <li><?php echo esc_html(get_theme_mod('rj_mojo_science_text','Science'));?></li>
                <li><?php echo esc_html(get_theme_mod('rj_mojo_technology_text','Technology'));?></li>
                <li><?php echo esc_html(get_theme_mod('rj_mojo_engineering_text','Engineering'));?></li>
                <li><?php echo esc_html(get_theme_mod('rj_mojo_arts_text','Arts'));?></li>
                <li><?php echo esc_html(get_theme_mod('rj_mojo_mathematics_text','Mathematics'));?></li>
            </ul>	
           <img src="<?php echo esc_url(get_theme_mod('rj_mojo_chemical_image', get_template_directory_uri() . "/assets/images/chemical.png")); ?>" alt="<?php echo esc_attr(get_theme_mod('rj_mojo_slide_title', true)); ?>" title="Chemical Image">
        </div>
        <div class="rj-world-text">
            <div class="rotating-earth">
                <img class="world-text" src="<?php echo esc_url(get_theme_mod('rj_mojo_world_image', get_template_directory_uri() . "/assets/images/world-text.png")); ?>" alt="<?php echo esc_attr(get_theme_mod('rj_mojo_slide_title', true)); ?>" title="World Image">
            </div>
            <?php // get_template_part('template-parts/earth'); ?>
            <h2><?php echo esc_html(get_theme_mod('rj_mojo_world_text','Changing the world is childs play'));?></h2>
        </div>
        
        <div class="rj-formula"> 
            <h2><?php echo esc_html(get_theme_mod('rj_mojo_formula_text','The Future belongs to the curious.'));?></h2>
            <?php /*
            <img src="<?php echo esc_url(get_theme_mod('rj_mojo_formula_image', get_template_directory_uri() . "/assets/images/formula.png")); ?>" alt="<?php echo esc_attr(get_theme_mod('rj_mojo_slide_title', true)); ?>" title="formula Image">
            */ ?>
            <div class="maths-formula">
                <!-- <p id="rj-math-formula">E=Mc<sup>2</sup></p> -->
                <p id="rj-math-formula"></p>
                <?php //get_template_part('template-parts/formula'); ?>
                <img src="<?php echo esc_url(get_theme_mod('rj_mojo_formula_image', get_template_directory_uri() . "/assets/images/rj-formula.png")); ?>" alt="<?php echo esc_attr(get_theme_mod('rj_mojo_slide_title', true)); ?>" title="Formula Image">
            </div>
        </div>
    </div>
    <div class="rj-rocket-img">
    <img src="<?php echo esc_url(get_theme_mod('rj_mojo_about_image1', get_template_directory_uri() . "/assets/images/bottom-image.png")); ?>" alt="<?php echo esc_attr(get_theme_mod('rj_mojo_slide_title1', true)); ?>" title="about Image"  class="bottom-image">
        <img src="<?php echo esc_url(get_theme_mod('rj_mojo_about_image2', get_template_directory_uri() . "/assets/images/rocket.png")); ?>" alt="<?php echo esc_attr(get_theme_mod('rj_mojo_slide_title2', true)); ?>" title="about Image" class="rocket-image">
    </div>
    
</section>


