<?php
/*
* Template Name: RJ Book Visit
*
* @package rj-bookmarks
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header('two'); 

?>

<section id="book-visit" class="py-5">
	<div class="container">
		<div class="row">
			<div class="col-lg-5">

			</div>
			<div class="col-lg-7">
				<h2 class="rj-visit-heading"><?php echo esc_html(get_theme_mod('rj_bookmarks_book_visit_heading','Book Your Visit'));?></h2> 
				<p class="rj-visit-para"><?php echo esc_html(get_theme_mod('rj_bookmarks_book_visit_text','Welcome to our dreamworld for kids! Here, creativity and curiosity thrive. Every corner is an adventure, and every activity sparks wonder. Join us to let your child’s imagination run wild and see their dreams come to life!'));?>
				</p> 
				<form id="booking-form" action="" method="post">
					<label for="name">Name</label>
					<input type="text" id="name" name="name" placeholder="Enter Name" required>

					<div class="d-lg-flex align-items-center gap-2 py-3">
						<span class="rj-mobile-email">
							<label for="contact_no">Mobile</label>
							<input type="tel" id="contact_no" name="contact_no" placeholder="Enter mobile number" required>
						</span>
						<span class="rj-mobile-email1">
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
</section>



<?php get_footer();