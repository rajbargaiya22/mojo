<?php
/*
* Template Name: RJ Book Visit
*
* @package rj-mojo
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header('two'); 
$rj_ticket_bg = get_theme_mod('rj_mojo_make_ideas_image', get_template_directory_uri() . "/assets/images/book-bg.png");
?>
 
<section id="book-visit" class="py-5 position-relative text-md-start text-center">
	<div class="container">
		<div class="row">
			<div class="col-lg-3 col-md-4">
				<h2 class="rj-visit-heading"><?php echo esc_html(get_theme_mod('rj_mojo_book_visit_heading','Book Your Visit'));?></h2>
				<div class="rj-booking" style="background-image:url(<?php echo ($rj_ticket_bg) ?>); padding: clamp(10px, 2vw + 1rem, 50px); background-size: 100% 100%; height:722px">
					<div class="rj-ticket-logo">
						<img src="<?php echo esc_url(get_theme_mod('rj_mojo_booking_logo_image', get_template_directory_uri() . "/assets/images/footer-logo.png")); ?>" alt="<?php echo esc_attr(get_theme_mod('rj_mojo_booking_logo_slide_title', true)); ?>" title="logo-visit" class="rj-book-logo-img">
						<h2 class="mt-5 mb-3"><?php echo esc_html(get_theme_mod('rj_mojo_pricing_text','Ticket Pricing'));?></h2>
						<img src="<?php echo esc_url(get_theme_mod('rj_mojo_booking_child_image', get_template_directory_uri() . "/assets/images/child-img.png")); ?>" alt="<?php echo esc_attr(get_theme_mod('rj_mojo_booking_child_title', true)); ?>" title="child" class="rj-ticket-child-img">
						<p class="rj-chlid-year"><?php echo esc_html(get_theme_mod('rj_mojo_child_year_text','999 onwards for children, 2-17 years'));?></p>
						<p class="rj-chlid-info"><?php echo esc_html(get_theme_mod('rj_mojo_child_info_text','Children under 12 years must be accompanied by a grown-up'));?></p>
						<hr>

						<img src="<?php echo esc_url(get_theme_mod('rj_mojo_booking_elder_image', get_template_directory_uri() . "/assets/images/elder-img.png")); ?>" alt="<?php echo esc_attr(get_theme_mod('rj_mojo_booking_elder_title', true)); ?>" title="elder" class="rj-ticket-elder-img">
						<p class="rj-elder-year"><?php echo esc_html(get_theme_mod('rj_mojo_elder_year_text','499 onwards for grown-ups, 18 years.'));?></p>
						<p class="rj-elder-info"><?php echo esc_html(get_theme_mod('rj_mojo_elder_info_text','All grown-ups must be accompanied by a child'));?></p>
					</div>
				</div>		
			</div>
			
			<div class="col-lg-9 col-md-8"> 
				<p class="rj-visit-para"><?php /* echo esc_html(get_theme_mod('rj_mojo_book_visit_text','Welcome to our dreamworld for kids! Here, creativity and curiosity thrive. Every corner is an adventure, and every activity sparks wonder. Join us to let your child’s imagination run wild and see their dreams come to life!')); */?>
				</p> 
				<form id="booking-form" action="" method="post">
					<div class="">
						<label for="name">Name</label>
						<input type="text" id="name" name="name" placeholder="Enter Name" required>
					</div>
					<div class="rj-mobile-email">
						<label for="contact_no">Mobile</label>
						<input type="tel" id="contact_no" name="contact_no" placeholder="Enter mobile number" required>
					</div>

					<div class="rj-mobile-email1">
						<label for="email">Email</label>
						<input type="email" id="email" name="email" placeholder="Enter email" required>
					</div>
					<div>
						<label for="booking_date">Date of Visit</label>
						<input type="date" id="booking_date" name="booking_date" required>
					</div>
					<div class="d-flex flex-column">
						<label for="time_slot">Choose Time Slot</label>
						<select name="time_slot" class=rj-time-table>
							<?php
							$saved_time_slots = get_option('rj_mojo_time_slots', array());

							function convert_to_12_hour_format($time) {
								return date('g:i A', strtotime($time));
							}

							foreach ($saved_time_slots as $key => $time) {
								$start_time = convert_to_12_hour_format($time['start']);
								$end_time = convert_to_12_hour_format($time['end']);
								?>
								<option class="rj-value" value="<?php echo esc_attr($time['start'] . '-' . $time['end']); ?>">
									<?php echo esc_html($start_time . ' - ' . $end_time); ?>
								</option>
							<?php } ?>
						</select>
					</div>
					<div>
						<label for="no_of_children">Children</label>
						<div class="d-flex align-items-center children-data">
							<button type="button" id="decrement" aria-label="Decrease number of children">-</button>
							<input type="number" id="no_of_children" name="no_of_children" placeholder="Enter the number of children" value="0" min="0" required>
							<button type="button" id="increment" aria-label="Increase number of children">+</button>
						</div>
						<p class="rj-chlid-text mt-2"><?php echo esc_html(get_theme_mod('rj_mojo_child_text','Children under 12 years must be accompanied by a grown-up.'));?></p>
					</div>
					<input type="hidden" name="child_rate" id="child_rate" value="<?php echo get_option('children_rate', true); ?>">
					<input type="hidden" name="grown_rate" id="grown_rate" value="<?php echo get_option('grown_up_rate', true); ?>">
					<div>
					<label for="no_of_elders">Elders:</label>
						<div class="d-flex align-items-center elders-data">
							<button type="button" class="decrement" aria-label="Decrease number of elders">-</button>
							<input type="number" id="no_of_elders" name="no_of_elders" placeholder="Enter the number of elders" value="0" min="0" required>
							<button type="button" class="increment" aria-label="Increase number of elders">+</button>
						</div>
						<p class="rj-chlid-text mt-2"><?php echo esc_html(get_theme_mod('rj_mojo_child_text','All grown-ups must be accompanied by a child'));?></p>
					</div>
					<div class="">
						<label for="total_cost">Total Cost</label>
						<input type="text" id="total_cost" name="total_cost" placeholder="Total Cost" readonly>
					</div>

					<input type="hidden" name="booking_form_nonce" value="<?php echo wp_create_nonce('submit_booking_form'); ?>">
					<div class="modal-footer">
						<button class="rj-submit-button" type="submit" name="submit_booking">Book</button>
					</div>
				</form>
			</div>
		</div>
		<div class="rj-booking-boy">
			<img src="<?php echo esc_url(get_theme_mod('rj_mojo_make_ideas_image', get_template_directory_uri() . "/assets/images/book-visit.png")); ?>" alt="<?php echo esc_attr(get_theme_mod('rj_mojo_slide_title', true)); ?>" title="book-visit">
		</div>
	</div>
</section>

<?php get_footer();