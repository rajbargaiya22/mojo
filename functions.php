<?php
/**
 * RJ mojo functions and definitions.
 *
 *
 * @package rj-mojo
 */

 if ( ! defined('rj_mojo_path')) {
   define('rj_mojo_path', get_template_directory());
 }

 if ( ! defined('rj_mojo_theme_path')) {
   define('rj_mojo_theme_path', get_template_directory_uri());
 }

function rj_mojo_enqueue_scripts() {
	wp_enqueue_style( 'rj-style', get_stylesheet_uri());
	wp_enqueue_style( 'rj-poppins-font', 'https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap' );
	wp_enqueue_style( 'rj-bootstrap', get_template_directory_uri(). '/assets/css/bootstrap.css' );
	wp_enqueue_style( 'rj-slick-slider', get_template_directory_uri(). '/assets/css/slick-theme.css' );
	wp_enqueue_style( 'rj-owl-carousel', get_template_directory_uri(). '/assets/css/owl.carousel.css' );

	wp_enqueue_script('rj-bootstrap-js', get_template_directory_uri(). '/assets/js/bootstrap.js', false, false);
	wp_enqueue_script('rj-fontawesome-js', get_template_directory_uri(). '/assets/js/fontawesome-all-min.js', false, false);
	wp_enqueue_script('rj-jquery-js', get_template_directory_uri(). '/assets/js/jquery-min.js', false, false);
	wp_enqueue_script('rj-slick-slider-js', get_template_directory_uri(). '/assets/js/slick.min.js', array('jquery'), false, false);
	wp_enqueue_script('rj-owl-carousel-js', get_template_directory_uri(). '/assets/js/owl.carousel.js',  array('jquery'), false, false);
    wp_enqueue_script('rj-nsc-custom.js', get_template_directory_uri(). '/assets/js/nsc-custom.js', false, false);
    wp_enqueue_script( 'wow-jquery', get_template_directory_uri() . '/assets/js/wow.js', array('jquery'),'' ,true );

	// wp_enqueue_script('jquery');


	 wp_localize_script('rj-custom-js', 'ajax_search_params', array(
			 'ajaxurl' => admin_url('admin-ajax.php'),
			 'nonce' => wp_create_nonce('ajax-search-nonce'),
	 ));

	 if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
	 	wp_enqueue_script( 'comment-reply' );
 	}

}
add_action( 'wp_enqueue_scripts', 'rj_mojo_enqueue_scripts' );

function rj_mojoload_admin_style() {
    wp_enqueue_style('rj-contact-list', get_template_directory_uri() . '/admin/assets/rj-contact-list.css');
    wp_enqueue_script('custom-time-slot-js', get_template_directory_uri() . '/admin/assets/rj-admin.js', array('jquery'), filemtime(get_template_directory() . '/admin/assets/rj-admin.js'), true);
}
add_action('admin_enqueue_scripts', 'rj_mojoload_admin_style');



if ( !function_exists( 'rj_mojo_theme_setup' )) {
  function rj_mojo_theme_setup(){

	load_theme_textdomain('rj-mojo', get_template_directory() . "/languages" );

    add_theme_support( 'automatic-feed-links' );
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support('html5', array( 'navigation-widgets', 'search-form', 'gallery', 'caption', 'style', 'script', 'comment-list', 'search-form', 'comment-form', ) );
    // add_theme_support( 'post-formats', array( 'gallery', 'image', 'link', 'quote', 'video', 'audio', 'status', 'aside', ) );

    add_theme_support(
  				'custom-logo',
  				array(
  					'width'       => 180,
  					'height'      => 60,
  					'flex-width'  => true,
  					'flex-height' => true,
  				)
  			);

    add_theme_support( 'customize-selective-refresh-widgets' );
		add_theme_support( 'wp-block-styles' );
		add_theme_support( 'responsive-embeds' );
		add_theme_support( 'custom-header' );
		add_theme_support( 'custom-background', array(
		'default-color' => '#111827'
		) );
		add_theme_support( 'align-wide' );

		add_editor_style( array( 'css/rj-editor-style.css' ) );

		register_nav_menus( array(
            'primary' => __( 'Primary Menu', 'rj-mojo' ),
        ) );
        
	}
}
add_action( 'after_setup_theme', 'rj_mojo_theme_setup' );

require get_template_directory() . '/inc/widgets/widgets-area.php';
require get_template_directory() . '/inc/widgets/social-icons.php';
require get_template_directory() . '/inc/widgets/posts-categories.php';
require get_template_directory() . '/inc/rj-block-patterns/rj-block-pattern-register.php';
require get_template_directory() . '/admin/rj-contact-list.php';
require get_template_directory() . '/inc/posts-rj-exhibits-image.php';

// require get_template_directory() . '/theme-wizard/config.php';

//  customizer
function rj_mojo_customizer_sanitize_choices( $input, $setting ) {
    global $wp_customize;
    $control = $wp_customize->get_control( $setting->id );
    if ( array_key_exists( $input, $control->choices ) ) {
        return $input;
    } else {
        return $setting->default;
    }
}

if ( ! function_exists( 'rj_mojo_toggle_sanitization' ) ) {
	function rj_mojo_toggle_sanitization( $input ) {
		if ( true === $input ) {
			return 1;
		} else {
			return 0;
		}
	}
}

require get_template_directory() . '/inc/rj-customizer/rj-customizer.php';

// Function to add nonce to the form
function rj_mojo_add_custom_meta_box_nonce() {
    wp_nonce_field('custom_meta_box_nonce', 'custom_meta_box_nonce');
}
add_action('post_submitbox_misc_actions', 'rj_mojo_add_custom_meta_box_nonce');

//  register post type Exhibits
function rj_mojo_register_top_articles_post_type() {
    register_post_type('rj-exhibit',
   	 array(
   		 'labels'  	=> array(
   			 'name'      	=> __('Exhibits', 'rj-mojo'),
   			 'singular_name' => __('Exhibit', 'rj-mojo'),
   		 ),
   			 'public'  	=> true,
   			 'has_archive' => true,
   			 'capability_type' => 'post',
   			 'supports' => array( 'title', 'editor', 'thumbnail'),
   	 )
    );
}
add_action('init', 'rj_mojo_register_top_articles_post_type');

// Register custom taxonomy 'exhibit_category' for the custom post type
function create_exhibit_category_taxonomy() {
    register_taxonomy(
        'exhibit_category',
        'rj-exhibit',
        array(
            'label' => __('Categories'),
            'rewrite' => array('slug' => 'exhibit-category'),
            'hierarchical' => true,
        )
    );
}
add_action('init', 'create_exhibit_category_taxonomy');

?>
