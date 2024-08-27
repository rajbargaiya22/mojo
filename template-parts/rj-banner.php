<?php
/*
* Template Name: RJ Banner
*
* @package rj-bookmarks
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header(); 

?>

<section id="top-section" class="top-section">
    <div class="rj-home-grid">
        <?php get_template_part('template-parts/header/site-logo'); ?>
        <div class="rj-make-ideas">
            <div class="girl-image-box">
                <div class="light"></div>
                <img src="<?php echo esc_url(get_theme_mod('rj_bookmarks_make_ideas_image', get_template_directory_uri() . "/assets/images/girl.png")); ?>" alt="<?php echo esc_attr(get_theme_mod('rj_bookmarks_slide_title', true)); ?>" title="Girl Image">
            </div>
            <h2><?php echo esc_html(get_theme_mod('rj_bookmarks_make_ideas_text','Make your ideas alive!'));?></h2> 
        </div>
        <?php /*		
        <div class="rj-book-seat">
            <a href="<?php echo esc_url(get_theme_mod('rj_bookmarks_book_url',false));?>">
            <h2 class="rj-visit-text"><?php esc_html_e('Book Visit', 'rj-bookmarks'); ?></h2></a>
        </div> */?>
        <div class="rj-imagination-fly">	
            <?php get_template_part('template-parts/star'); ?>
            <h2><?php echo esc_html(get_theme_mod('rj_bookmarks_star_text','Let your imagination fly'));?></h2>
        </div>

        <div class="rj-menus-header d-flex align-items-center <?php if( get_theme_mod( 'rj_bookmarks_sticky_header', true) == 1) { ?> header-sticky"<?php } else { ?>close-sticky <?php } ?>">
        <a href="<?php echo esc_url(get_theme_mod('rj_bookmarks_product_facebook_url',false));?>"> 
            <img src="<?php echo esc_url(get_theme_mod('rj_bookmarks_facebook_image', get_template_directory_uri() . "/assets/images/facebook.png")); ?>" alt="<?php echo esc_attr(get_theme_mod('rj_bookmarks_slide_title', true)); ?>" title="Facebook Image">
        </a>
        <a href="<?php echo esc_url(get_theme_mod('rj_bookmarks_product_instagram_url',false));?>">
            <img src="<?php echo esc_url(get_theme_mod('rj_bookmarks_instagram_image', get_template_directory_uri() . "/assets/images/instagram.png")); ?>" alt="<?php echo esc_attr(get_theme_mod('rj_bookmarks_slide_title', true)); ?>" title="Instagram Image">
        </a>
            <?php /* get_template_part('template-parts/header/menu'); */?>
        </div>

        <div class="rj-tree-text">
            <h2><?php echo esc_html(get_theme_mod('rj_bookmarks_tree_text','Play, Discover, Build and Grow'));?></h2>
            
            <img src="<?php echo esc_url(get_theme_mod('rj_bookmarks_chemical_image', get_template_directory_uri() . "/assets/images/tree.png")); ?>" alt="<?php echo esc_attr(get_theme_mod('rj_bookmarks_slide_title', true)); ?>" title="Chemical Image">
            <?php // get_template_part('template-parts/tree'); ?>
        </div>

        <div class="rj-subject-text">
            <span class="chemical-component" ><?php get_template_part('template-parts/component'); ?></span>
            <ul>
                <li><?php echo esc_html(get_theme_mod('rj_bookmarks_science_text','Science'));?></li>
                <li><?php echo esc_html(get_theme_mod('rj_bookmarks_technology_text','Technology'));?></li>
                <li><?php echo esc_html(get_theme_mod('rj_bookmarks_engineering_text','Engineering'));?></li>
                <li><?php echo esc_html(get_theme_mod('rj_bookmarks_arts_text','Arts'));?></li>
                <li><?php echo esc_html(get_theme_mod('rj_bookmarks_mathematics_text','Mathematics'));?></li>
            </ul>	
           <img src="<?php echo esc_url(get_theme_mod('rj_bookmarks_chemical_image', get_template_directory_uri() . "/assets/images/chemical.png")); ?>" alt="<?php echo esc_attr(get_theme_mod('rj_bookmarks_slide_title', true)); ?>" title="Chemical Image">
            
        </div>
        
        <div class="rj-world-text">

            <div class="rotating-earth">

                <img class="world-text" src="<?php echo esc_url(get_theme_mod('rj_bookmarks_world_image', get_template_directory_uri() . "/assets/images/world-text.png")); ?>" alt="<?php echo esc_attr(get_theme_mod('rj_bookmarks_slide_title', true)); ?>" title="World Image">
            </div>

            
            <?php // get_template_part('template-parts/earth'); ?>
            
            <h2><?php echo esc_html(get_theme_mod('rj_bookmarks_world_text','Changing the world is childs play'));?></h2>
        </div>
        
        <div class="rj-formula"> 
            <h2><?php echo esc_html(get_theme_mod('rj_bookmarks_formula_text','The Future belongs to the curious.'));?></h2>
            <?php /*
            <img src="<?php echo esc_url(get_theme_mod('rj_bookmarks_formula_image', get_template_directory_uri() . "/assets/images/formula.png")); ?>" alt="<?php echo esc_attr(get_theme_mod('rj_bookmarks_slide_title', true)); ?>" title="formula Image">
            */ ?>
            <div class="maths-formula">
                <!-- <p id="rj-math-formula">E=Mc<sup>2</sup></p> -->
                <p id="rj-math-formula"></p>
                <?php get_template_part('template-parts/formula'); ?>
            </div>
        </div>
    </div>
    <div class="rj-rocket-img">
    <img src="<?php echo esc_url(get_theme_mod('rj_bookmarks_about_image1', get_template_directory_uri() . "/assets/images/bottom-image.png")); ?>" alt="<?php echo esc_attr(get_theme_mod('rj_bookmarks_slide_title1', true)); ?>" title="about Image"  class="bottom-image">
        <img src="<?php echo esc_url(get_theme_mod('rj_bookmarks_about_image2', get_template_directory_uri() . "/assets/images/rocket.png")); ?>" alt="<?php echo esc_attr(get_theme_mod('rj_bookmarks_slide_title2', true)); ?>" title="about Image" class="rocket-image">
    </div>
</section>


