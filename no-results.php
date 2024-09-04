<?php
/**
 * The template part for displaying a message that posts cannot be found.
 *
 * @package rj-mojo
 */
get_header('two');
?>

<section class=" py-3 py-lg-5">
  <h2 class="entry-title"><?php echo esc_html(get_theme_mod('rj_mojo_no_results_page_title',__('Nothing Found','rj-mojo')));?></h2>

<?php if ( is_home() && current_user_can( 'publish_posts' ) ) : ?>
	<p><?php printf( esc_html__( 'Ready to publish your first post? Get started here.', 'rj-mojo' ), esc_url( admin_url( 'post-new.php' ) ) ); ?></p>
	<?php elseif ( is_search() ) : ?>
	<p><?php echo esc_html(get_theme_mod('rj_mojo_no_results_page_content',__('Sorry, but nothing matched your search terms. Please try again with some different keywords.','rj-mojo')));?></p>
  <button type="button" class="header-search" data-bs-toggle="modal" data-bs-target="#rj-header-search-popup">
  	<?php esc_html_e( 'Click here for search', 'rj-mojo' ); ?>
    <span class="screen-reader-text">
      <?php esc_html_e( 'Click here for search', 'rj-mojo' ); ?>
    </span>
	</button>

	<?php // get_search_form(); ?>
	<?php else : ?>
	<p><?php esc_html_e( 'Dont worry&hellip it happens to the best of us.', 'rj-mojo' ); ?></p><br />
	<div class="more-btn">
		<a href="<?php echo esc_url(home_url() ); ?>" title="<?php esc_attr_e( 'Back to Home Page','rj-mojo' );?>">
      <?php esc_html_e( 'Back to Home Page', 'rj-mojo' ); ?>
      <span class="screen-reader-text">
        <?php esc_html_e( 'Back to Home Page','rj-mojo' );?>
      </span>
    </a>
	</div>
</section>
<?php endif; ?>
