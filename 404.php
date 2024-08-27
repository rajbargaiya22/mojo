<?php
/**
 * The template for displaying 404 pages (Error Page).
 *
 * @package rj-mojo
 */

get_header();
$headh1 = get_theme_mod('rj_mojo_site_title', true); ?>

<main class="error-page py-4 py-lg-5">
	<div class="container">
		<?php echo ($headh1 == 0) ? "<h1>" : "<h2>" ?>
				<?php echo esc_html(get_theme_mod('rj_mojo_404_page_title',__('404 Not Found','rj-mojo')));?>
		<?php echo ($headh1 == 0) ? "</h1>" : "</h2>" ?>
			<p class="text-404">
				<?php echo esc_html(get_theme_mod('rj_mojo_404_page_content',__('Looks like you have taken a wrong turn, Dont worry, it happens to the best of us.','rj-mojo')));?>
			</p>
			<?php if( get_theme_mod('rj_mojo_404_page_button_text','Go Back') != ''){ ?>
				<div class="more-btn">
			        <a href="<?php echo wp_get_referer() ?: home_url(); ?>" title="<?php echo esc_html(get_theme_mod('rj_mojo_404_page_button_text',__('Go Back','rj-mojo')));?>">
								<?php echo esc_html(get_theme_mod('rj_mojo_404_page_button_text',__('Go Back','rj-mojo')));?>
								<span class="screen-reader-text">
									<?php echo esc_html(get_theme_mod('rj_mojo_404_page_button_text',__('Go Back','rj-mojo'))); ?>
								</span>
							</a>
			    </div>

					<?php /*
					<button id="backButton">Back</button>
					*/ ?>

			<?php } ?>
		<div class="clearfix"></div>
	</div>
</main>

<?php get_footer(); ?>
