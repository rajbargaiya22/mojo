<?php
/*
* Template Name: RJ Home
*
* @package rj-bookmarks
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header(); 

$abou_bg = get_template_directory_uri() . '/assets/images/about.png';
?>

<div id="rj-mojo-home" class="rj-mojo-home  wow bounceInRight delay-1000" data-wow-duration="3s">
	<?php get_template_part('template-parts/rj-banner');?>
	<?php get_template_part('template-parts/rj-about');?>
</div>
<?php get_footer();
