<?php
/**
 * The header for RJ Mojo.
 *
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package rj-bookmarks
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

?><!DOCTYPE html>
<?php do_action('rj_bookmarks_html'); ?>
<html <?php language_attributes(); ?>>
<head>
<?php do_action('rj_bookmarks_head_top'); ?>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">

<meta name="description" content="<?php echo esc_attr(get_bloginfo( 'description' )) ?>">

<!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" /> -->

<!-- <meta http-equiv="refresh" content="2"> -->
<?php wp_head(); ?>
<?php do_action('rj_bookmarks_head_bottom'); ?>
</head>

<body <?php body_class(); ?>>
<?php do_action('rj_bookmarks_body_top'); ?>
<?php wp_body_open(); ?>


<header class="rj-header-2">
	<?php get_template_part('template-parts/header/top-header'); ?>
</header>

<?php
/*
if (get_theme_mod('rj_bookmarks_header_settings', true) !='0'){
	get_template_part('template-parts/rj-theme-settings');
} */ ?>