<?php
/**
 * The template for displaying the footer.
 *
 * @package rj- blog
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} 
$phoneNumber = "8010670818";
?>
	  <footer class="rj-footer">
		<section id="rj-contact-section" class="rj-contact-section">
			<div class=" container">
				<div class="row justify-content-center">
					<div class="col-xl-4 col-lg-4 col-md-6 pb-lg-0 pb-3">
						<div class="rj-location-img">
							<img src="<?php echo esc_url(get_theme_mod('rj_bookmarks_location_image', get_template_directory_uri() . "/assets/images/location.png")); ?>" alt="<?php echo esc_attr(get_theme_mod('rj_bookmarks_slide_title', true)); ?>" title="Location Image">
						</div>
						<h5><?php echo esc_html(get_theme_mod('rj_bookmarks_location_text','Location'));?></h5>
						<a href="http://maps.google.com/maps?q=Dhandhania House East High Court Civil Lines 440001 Nagpur" target="_blank">
						<?php echo (get_theme_mod('rj_bookmarks_address_text','Museum Of Joy <br> Dhandhania House East High Court Civil Lines 440001 Nagpur'));?></a>
					</div><?php /*
					<div class="col-xl-4 col-lg-4 col-md-6">
						<div class="rj-time-img">
							<img src=<?php echo get_template_directory_uri() . "/assets/images/time.png" ?> alt="world">
						</div>
						<h5><?php esc_html_e('Time', 'rj-bookmarks'); ?></h5>
						<p><?php esc_html_e('Mon - 2:00pm to 7:30pm Tue - Sun 11:00 am to 7:30 pm', 'rj-bookmarks'); ?></p> 
					</div>*/?>
					<div class="col-xl-4 col-lg-4 col-md-6">
						<div class="rj-contact-img">
							<img src="<?php echo esc_url(get_theme_mod('rj_bookmarks_contact_image', get_template_directory_uri() . "/assets/images/contact.png")); ?>" alt="<?php echo esc_attr(get_theme_mod('rj_bookmarks_slide_title', true)); ?>" title="contact Image">
						</div>
						<h5><?php echo esc_html(get_theme_mod('rj_bookmarks_contact_text','Contact'));?></h5>
						<div class="rj-phone-number"><a href="tel:<?php echo $phoneNumber; ?>"><?php echo $phoneNumber; ?></a></div>
						<div class="rj-mail-text">
						<a href="mailto:<?php echo esc_attr(get_theme_mod('rj_bookmarks_email_address',''));?>">
							<?php echo esc_html(get_theme_mod('rj_bookmarks_email_address','mojo.nagpur@gmail.com'));?></a>
						</div>
						<!-- Button trigger modal -->
						<button type="button" class="contact-btn" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
						Contact Us
						</button>

						<!-- Modal -->
						<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
							<div class="modal-dialog modal-dialog-scrollable">">
								<div class="modal-content text-start">
								<div class="modal-header">
									<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
								</div>
									<div class="modal-body">
										<form id="booking-form" action="" method="post">
												<label for="name">Name</label>
												<input type="text" id="name" name="name" placeholder="Enter Name" required>

												<div class="d-flex align-items-center gap-2">
													<span>
														<label for="contact_no">Mobile</label>
														<input type="tel" id="contact_no" name="contact_no" placeholder="Enter mobile number" required>
													</span>
													<span>
														<label for="email">Email</label>
														<input type="email" id="email" name="email" placeholder="Enter email" required>
													</span>
												</div>
												<?php /*<label for="booking_date">Date of Visit</label>
												<input type="date" id="booking_date" name="booking_date" required>

												<label for="no_of_children">Children</label>
												<input type="number" id="no_of_children" name="no_of_children" placeholder="Enter the number of children" required>

												<label for="no_of_elders">Elders:</label>
												<input type="number" id="no_of_elders" name="no_of_elders" placeholder="Enter the number of elders" required>
												*/?>
												<div class="rj-message-box">
													<span>
														<label class="rj-message-text" for="message">Message:</label>
														<textarea id="message" name="message" placeholder="Enter any additional message" required></textarea>
													</span>
												</div>
												<div class="modal-footer">
													<button class="rj-submit-button" type="submit" name="submit_booking">Submit</button>
												</div>
										</form>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>	
	 	</section>
	    <div class="rj-footer-col">
			<div class="rj-footer-grid" >
				<?php foreach ( $GLOBALS['wp_registered_sidebars'] as $sidebar ) {
					if ( is_active_sidebar( $sidebar['id'] ) && str_starts_with($sidebar['name'], 'Footer')) { ?>
						<div class="rj-foot-column">
							<?php dynamic_sidebar($sidebar['id']); ?>
						</div>
					<?php }
				} ?>
			</div>
	    </div>
		<div class="rj-copyright">
			<?php dynamic_sidebar( 'copyright-text' ); ?>
			<img src=<?php echo get_template_directory_uri() . "/assets/images/copyright.png" ?> alt="copyright">
		</div>
	  </footer>
		<?php do_action('rj_bookmarks_body_bottom');
		wp_footer(); ?>
	</body>
</html>
