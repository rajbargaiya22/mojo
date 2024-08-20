<?php
/**
 * The Template for displaying search results.
 *
 * @package rj-bookmarks
 */

get_header();
get_template_part('template-parts/breadcrumb'); ?>

<section class="rj-result-page">
    <div class="container">
			<div class="row">
				<div class="col-md-8">

					<?php get_search_form(); ?>

					<div class="bookmarks-list">
		        <?php
		        // Retrieve search query, category, and location from URL parameters
		        $search = isset($_GET['s']) ? sanitize_text_field($_GET['s']) : '';
		        $category = isset($_GET['rj-category']) ? sanitize_text_field($_GET['rj-category']) : '';
		        $location = isset($_GET['rj-location']) ? sanitize_text_field($_GET['rj-location']) : '';

						$rj_bookmarks_paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;

						$args = array(
						    'post_type' => 'post',
								'paged' => $rj_bookmarks_paged,
						);

						if (!empty($search)) {
						    $args['s'] = $search;
						}

						$tax_query = array(
						    'relation' => 'AND', // Set the relation to 'AND'
						);

						if (!empty($category)) {
						    $tax_query[] = array(
						        'taxonomy' => 'category',
						        'field'    => 'slug',
						        'terms'    => $category,
						    );
						}

						if (!empty($location)) {
						    $tax_query[] = array(
						        'taxonomy' => 'rj_location',
						        'field'    => 'slug',
						        'terms'    => $location,
						    );
						}

						if (!empty($tax_query)) {
						    $args['tax_query'] = $tax_query;
						}

						$query = new WP_Query($args);

		        // Check if there are posts in the query
		        if ($query->have_posts()) :
		            while ($query->have_posts()) :
		                $query->the_post(); ?>

										<div class="rj-bookmarka-container">
											<div class="d-flex align-items-start gap-3">
												<?php $image_id = get_post_thumbnail_id();
												 $image_alt = get_post_meta($image_id, '_wp_attachment_image_alt', TRUE);
												 $image_title = get_the_title($image_id);
												 $image_url = get_the_post_thumbnail_url( get_the_ID(), 'medium' ); ?>

												 <?php if ($image_url){ ?>
													 <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr(($image_alt) ? $image_alt : get_the_title() ); ?>" title="<?php echo esc_attr(($image_title) ? $image_title : get_the_title() ); ?>">
												 <?php }else{ ?>
													 <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/dummy-post-image.webp'); ?>" alt="<?php echo esc_attr(($image_alt) ? $image_alt : get_the_title() ); ?>" title="<?php echo esc_attr(($image_title) ? $image_title : get_the_title() ); ?>">
												 <?php } ?>

												 <div class="bookmark-content">
													 <?php $categories = get_the_category(); ?>

													 <h3 class="rj-bookmark-title">
														 <a href="<?php echo get_the_permalink(); ?>" title="<?php echo esc_attr( $categories[0]->name );  ?>">
															 <?php echo get_the_title(); ?>
														 </a>
													 </h3>

													 <div class="d-flex gap-2">
														 <p class="mb-0 font-500-13">
															 <span class="gray-color">
																 <?php echo esc_html("Submitted By "); ?>
															 </span>
															 <span class="blue-color">
																 <?php echo  get_the_author(); ?>
															 </span>
														 </p>

														 <span class="font-500-13 d-flex gap-2">
															 <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="14">
																 <path d="M256 0a256 256 0 1 1 0 512A256 256 0 1 1 256 0zM232 120V256c0 8 4 15.5 10.7 20l96 64c11 7.4 25.9 4.4 33.3-6.7s4.4-25.9-6.7-33.3L280 243.2V120c0-13.3-10.7-24-24-24s-24 10.7-24 24z"/></svg>
																 <span class="blue-color">
																	 <?php echo get_the_date(); ?>
																 </span>
														 </span>

														 <button type="button" name="button" class="d-flex align-items-center gap-2 font-500-13">
															 <svg width="14" viewBox="0 0 512 512"><path d="M307 34.8c-11.5 5.1-19 16.6-19 29.2v64H176C78.8 128 0 206.8 0 304C0 417.3 81.5 467.9 100.2 478.1c2.5 1.4 5.3 1.9 8.1 1.9c10.9 0 19.7-8.9 19.7-19.7c0-7.5-4.3-14.4-9.8-19.5C108.8 431.9 96 414.4 96 384c0-53 43-96 96-96h96v64c0 12.6 7.4 24.1 19 29.2s25 3 34.4-5.4l160-144c6.7-6.1 10.6-14.7 10.6-23.8s-3.8-17.7-10.6-23.8l-160-144c-9.4-8.5-22.9-10.6-34.4-5.4z"/></svg>
															 <span class="blue-color">
																 <?php echo esc_html('Share'); ?>
															 </span>

															 <div class="d-none">
																 <a href="#">
																	 <svg viewBox="0 0 448 512"><path d="M64 32C28.7 32 0 60.7 0 96V416c0 35.3 28.7 64 64 64h98.2V334.2H109.4V256h52.8V222.3c0-87.1 39.4-127.5 125-127.5c16.2 0 44.2 3.2 55.7 6.4V172c-6-.6-16.5-1-29.6-1c-42 0-58.2 15.9-58.2 57.2V256h83.6l-14.4 78.2H255V480H384c35.3 0 64-28.7 64-64V96c0-35.3-28.7-64-64-64H64z"/></svg>
																 </a>
																 <a href="#">
																	 <svg viewBox="0 0 448 512"><path d="M64 32C28.7 32 0 60.7 0 96V416c0 35.3 28.7 64 64 64H384c35.3 0 64-28.7 64-64V96c0-35.3-28.7-64-64-64H64zm297.1 84L257.3 234.6 379.4 396H283.8L209 298.1 123.3 396H75.8l111-126.9L69.7 116h98l67.7 89.5L313.6 116h47.5zM323.3 367.6L153.4 142.9H125.1L296.9 367.6h26.3z"/></svg>
																 </a>
																 <a href="#">
																	 <svg viewBox="0 0 448 512"><path d="M384 32H64C28.7 32 0 60.7 0 96V416c0 35.3 28.7 64 64 64h72.6l-2.2-.8c-5.4-48.1-3.1-57.5 15.7-134.7c3.9-16 8.5-35 13.9-57.9c0 0-7.3-14.8-7.3-36.5c0-70.7 75.5-78 75.5-25c0 13.5-5.4 31.1-11.2 49.8c-3.3 10.6-6.6 21.5-9.1 32c-5.7 24.5 12.3 44.4 36.4 44.4c43.7 0 77.2-46 77.2-112.4c0-58.8-42.3-99.9-102.6-99.9C153 139 112 191.4 112 245.6c0 21.1 8.2 43.7 18.3 56c2 2.4 2.3 4.5 1.7 7c-1.1 4.7-3.1 12.9-4.7 19.2c-1 4-1.8 7.3-2.1 8.6c-1.1 4.5-3.5 5.5-8.2 3.3c-30.6-14.3-49.8-59.1-49.8-95.1C67.2 167.1 123.4 96 229.4 96c85.2 0 151.4 60.7 151.4 141.8c0 84.6-53.3 152.7-127.4 152.7c-24.9 0-48.3-12.9-56.3-28.2c0 0-12.3 46.9-15.3 58.4c-5 19.3-17.6 42.9-27.4 59.3H384c35.3 0 64-28.7 64-64V96c0-35.3-28.7-64-64-64z"/></svg>
																 </a>
															 </div>
														 </button>

														 <p class="font-500-13 d-flex gap-2 mb-0">
															 <svg viewBox="0 0 512 512" width="14"><path d="M168.2 384.9c-15-5.4-31.7-3.1-44.6 6.4c-8.2 6-22.3 14.8-39.4 22.7c5.6-14.7 9.9-31.3 11.3-49.4c1-12.9-3.3-25.7-11.8-35.5C60.4 302.8 48 272 48 240c0-79.5 83.3-160 208-160s208 80.5 208 160s-83.3 160-208 160c-31.6 0-61.3-5.5-87.8-15.1zM26.3 423.8c-1.6 2.7-3.3 5.4-5.1 8.1l-.3 .5c-1.6 2.3-3.2 4.6-4.8 6.9c-3.5 4.7-7.3 9.3-11.3 13.5c-4.6 4.6-5.9 11.4-3.4 17.4c2.5 6 8.3 9.9 14.8 9.9c5.1 0 10.2-.3 15.3-.8l.7-.1c4.4-.5 8.8-1.1 13.2-1.9c.8-.1 1.6-.3 2.4-.5c17.8-3.5 34.9-9.5 50.1-16.1c22.9-10 42.4-21.9 54.3-30.6c31.8 11.5 67 17.9 104.1 17.9c141.4 0 256-93.1 256-208S397.4 32 256 32S0 125.1 0 240c0 45.1 17.7 86.8 47.7 120.9c-1.9 24.5-11.4 46.3-21.4 62.9zM144 272a32 32 0 1 0 0-64 32 32 0 1 0 0 64zm144-32a32 32 0 1 0 -64 0 32 32 0 1 0 64 0zm80 32a32 32 0 1 0 0-64 32 32 0 1 0 0 64z"/></svg>
															 <span class="blue-color">
																 <?php echo get_comments_number($post->ID); ?>
															 </span>
														 </p>

														 <span class="font-500-13 d-flex gap-2">
															 <svg viewBox="0 0 576 512" width="14"><path d="M565.6 36.2C572.1 40.7 576 48.1 576 56V392c0 10-6.2 18.9-15.5 22.4l-168 64c-5.2 2-10.9 2.1-16.1 .3L192.5 417.5l-160 61c-7.4 2.8-15.7 1.8-22.2-2.7S0 463.9 0 456V120c0-10 6.1-18.9 15.5-22.4l168-64c5.2-2 10.9-2.1 16.1-.3L383.5 94.5l160-61c7.4-2.8 15.7-1.8 22.2 2.7zM48 136.5V421.2l120-45.7V90.8L48 136.5zM360 422.7V137.3l-144-48V374.7l144 48zm48-1.5l120-45.7V90.8L408 136.5V421.2z"/></svg>
															 <span class="blue-color">
																 <?php echo esc_html(get_post_meta($post->ID, 'rj_website_country', true)); ?>
															 </span>
														 </span>
													 </div>

													 <p class="d-flex font-500-13 gap-2">
														 <svg width="14" viewBox="0 0 512 512"><path d="M352 256c0 22.2-1.2 43.6-3.3 64H163.3c-2.2-20.4-3.3-41.8-3.3-64s1.2-43.6 3.3-64H348.7c2.2 20.4 3.3 41.8 3.3 64zm28.8-64H503.9c5.3 20.5 8.1 41.9 8.1 64s-2.8 43.5-8.1 64H380.8c2.1-20.6 3.2-42 3.2-64s-1.1-43.4-3.2-64zm112.6-32H376.7c-10-63.9-29.8-117.4-55.3-151.6c78.3 20.7 142 77.5 171.9 151.6zm-149.1 0H167.7c6.1-36.4 15.5-68.6 27-94.7c10.5-23.6 22.2-40.7 33.5-51.5C239.4 3.2 248.7 0 256 0s16.6 3.2 27.8 13.8c11.3 10.8 23 27.9 33.5 51.5c11.6 26 20.9 58.2 27 94.7zm-209 0H18.6C48.6 85.9 112.2 29.1 190.6 8.4C165.1 42.6 145.3 96.1 135.3 160zM8.1 192H131.2c-2.1 20.6-3.2 42-3.2 64s1.1 43.4 3.2 64H8.1C2.8 299.5 0 278.1 0 256s2.8-43.5 8.1-64zM194.7 446.6c-11.6-26-20.9-58.2-27-94.6H344.3c-6.1 36.4-15.5 68.6-27 94.6c-10.5 23.6-22.2 40.7-33.5 51.5C272.6 508.8 263.3 512 256 512s-16.6-3.2-27.8-13.8c-11.3-10.8-23-27.9-33.5-51.5zM135.3 352c10 63.9 29.8 117.4 55.3 151.6C112.2 482.9 48.6 426.1 18.6 352H135.3zm358.1 0c-30 74.1-93.6 130.9-171.9 151.6c25.5-34.2 45.2-87.7 55.3-151.6H493.4z"/></svg>
														 <a href="<?php echo esc_url(get_post_meta($post->ID, 'rj_website_url', true)); ?>" class="blue-color">
															 <?php echo esc_html(get_post_meta($post->ID, 'rj_website_url', true)); ?>
														 </a>
													 </p>

													 <a href="tel:<?php echo esc_attr(get_post_meta($post->ID, 'rj_website_contact', true)); ?>" class="d-flex gap-2 font-500-13 para-color">
														 <svg viewBox="0 0 512 512" width="14">
															 <path d="M164.9 24.6c-7.7-18.6-28-28.5-47.4-23.2l-88 24C12.1 30.2 0 46 0 64C0 311.4 200.6 512 448 512c18 0 33.8-12.1 38.6-29.5l24-88c5.3-19.4-4.6-39.7-23.2-47.4l-96-40c-16.3-6.8-35.2-2.1-46.3 11.6L304.7 368C234.3 334.7 177.3 277.7 144 207.3L193.3 167c13.7-11.2 18.4-30 11.6-46.3l-40-96z"/></svg>
															 <span>
																 <?php echo esc_html(get_post_meta($post->ID, 'rj_website_contact', true)); ?>
															 </span>
													 </a>

													 <a href="mailto:<?php echo esc_attr(get_post_meta($post->ID, 'rj_website_mail', true)); ?>" class="d-flex gap-2 font-500-13 para-color">
														 <svg viewBox="0 0 512 512" width="14"><path d="M48 64C21.5 64 0 85.5 0 112c0 15.1 7.1 29.3 19.2 38.4L236.8 313.6c11.4 8.5 27 8.5 38.4 0L492.8 150.4c12.1-9.1 19.2-23.3 19.2-38.4c0-26.5-21.5-48-48-48H48zM0 176V384c0 35.3 28.7 64 64 64H448c35.3 0 64-28.7 64-64V176L294.4 339.2c-22.8 17.1-54 17.1-76.8 0L0 176z"/></svg>
														 <span>
															 <?php echo esc_html(get_post_meta($post->ID, 'rj_website_mail', true)); ?>
														 </span>
													 </a>

													 <p class="d-flex gap-2 font-500-13 para-color">
														 <svg width="14" viewBox="0 0 512 512"><path d="M96 0C60.7 0 32 28.7 32 64V448c0 35.3 28.7 64 64 64H384c35.3 0 64-28.7 64-64V64c0-35.3-28.7-64-64-64H96zM208 288h64c44.2 0 80 35.8 80 80c0 8.8-7.2 16-16 16H144c-8.8 0-16-7.2-16-16c0-44.2 35.8-80 80-80zm-32-96a64 64 0 1 1 128 0 64 64 0 1 1 -128 0zM512 80c0-8.8-7.2-16-16-16s-16 7.2-16 16v64c0 8.8 7.2 16 16 16s16-7.2 16-16V80zM496 192c-8.8 0-16 7.2-16 16v64c0 8.8 7.2 16 16 16s16-7.2 16-16V208c0-8.8-7.2-16-16-16zm16 144c0-8.8-7.2-16-16-16s-16 7.2-16 16v64c0 8.8 7.2 16 16 16s16-7.2 16-16V336z"/></svg>
														 <span>
															 <?php echo esc_html(get_post_meta($post->ID, 'rj_website_address', true)); ?>
														 </span>
													 </p>
												 </div>
											</div>

											<?php $content = get_the_content();  ?>
											<div class="font-500-13 para-color">
												<?php  echo $content; ?>
											</div>

									 </div>


		            		<?php endwhile; ?>
			            <div class="rj-post-navigation">
			                <?php
			                the_posts_pagination(array(
			                    'prev_text'          => __('Previous page', 'rj-bookmarks'),
			                    'next_text'          => __('Next page', 'rj-bookmarks'),
			                    'before_page_number' => '<span class="meta-nav screen-reader-text">' . __('Page', 'rj-bookmarks') . ' </span>',
			                ));
			                ?>
			            </div>

				        <?php else : ?>
				            <?php
				            // If no posts found, include the no-results template part
				            get_template_part('template-parts/content', 'none');
				            ?>
				        <?php endif; ?>

		        <div class="clearfix"></div>
		    	</div>

				</div>
				<aside class="col-md-4 rj-sidebar">
					<?php dynamic_sidebar('home-page');?>
				</aside>
			</div>
  </div>
</section>

<?php get_footer(); ?>
