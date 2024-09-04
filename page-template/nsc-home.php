<?php
/*
* Templates Name: RJ Homepage
*
*
* @package rj-mojo
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header(); ?>
	<main id="rj-primary">
		<div class="container">
			<div class="row">
				<div class="col-md-8">
					<?php get_template_part('template-parts/home/section-slider'); ?>
				</div>
				<div class="col-md-4">

				</div>
			</div>
		</div>
	<?php
	get_footer();
