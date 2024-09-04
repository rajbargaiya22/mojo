<?php
/**
 * Custom Social Widget
 */

class rj_mojo_Social_Icons extends WP_Widget {

	function __construct() {
		parent::__construct(
			'rj_mojo_Social_Icons',
			__('RJ Social Icon', 'rj-mojo'),
			array( 'description' => __( 'Widget for Social icons section', 'rj-mojo' ), )
		);
	}

	public function widget( $rj_mojo_args, $rj_mojo_instance ) { ?>
		<div class="widget12">
			<?php
			$rj_mojo_title = isset( $rj_mojo_instance['title'] ) ? $rj_mojo_instance['title'] : '';
			$rj_mojo_facebook = isset( $rj_mojo_instance['facebook'] ) ? $rj_mojo_instance['facebook'] : '';
			$rj_mojo_whatsapp = isset( $rj_mojo_instance['whatsapp'] ) ? $rj_mojo_instance['whatsapp'] : '';
			$rj_mojo_instagram = isset( $rj_mojo_instance['instagram'] ) ? $rj_mojo_instance['instagram'] : '';
			$rj_mojo_twitter = isset( $rj_mojo_instance['twitter'] ) ? $rj_mojo_instance['twitter'] : '';
			$rj_mojo_linkedin = isset( $rj_mojo_instance['linkedin'] ) ? $rj_mojo_instance['linkedin'] : '';
			$rj_mojo_pinterest = isset( $rj_mojo_instance['pinterest'] ) ? $rj_mojo_instance['pinterest'] : '';
			$rj_mojo_tumblr = isset( $rj_mojo_instance['tumblr'] ) ? $rj_mojo_instance['tumblr'] : '';
			$rj_mojo_youtube = isset( $rj_mojo_instance['youtube'] ) ? $rj_mojo_instance['youtube'] : '';

			echo '<div class="rj-custom-social-icons">';
			if(!empty($rj_mojo_title) ){ ?><h3 class="section-main-head"><?php echo esc_html($rj_mojo_title); ?></h3><?php }
			if(!empty($rj_mojo_facebook) ){ ?>
					<a class="custom_facebook" href="<?php echo esc_url($rj_mojo_facebook); ?>" title="<?php echo esc_attr( 'Facebook','rj-mojo' );?>">
						<i class="fa-brands fa-facebook-f"></i>
					</a>
				<?php }
			if(!empty($rj_mojo_whatsapp) ){ ?>
				<a class="custom_whatsapp" href="https://wa.me/<?php echo esc_url($rj_mojo_whatsapp); ?>" title="<?php echo esc_attr( 'Whatsapp','rj-mojo' );?>">
					<i class="fa-brands fa-whatsapp"></i>
				</a>
			<?php }
			if(!empty($rj_mojo_twitter) ){ ?>
				<a class="custom_twitter" href="<?php echo esc_url($rj_mojo_twitter); ?>" title="<?php echo esc_attr( 'Twitter','rj-mojo' );?>">
					<i class="fab fa-twitter"></i>
				</a>
			<?php }
	        if(!empty($rj_mojo_linkedin) ){ ?>
						<a class="custom_linkedin" href="<?php echo esc_url($rj_mojo_linkedin); ?>" title="<?php echo esc_attr( 'Linkedin','rj-mojo' );?>">
							<i class="fab fa-linkedin-in"></i>
						</a>
					<?php }
	        if(!empty($rj_mojo_pinterest) ){ ?>
						<a class="custom_pinterest" href="<?php echo esc_url($rj_mojo_pinterest); ?>" title="<?php echo esc_attr( 'Pinterest','rj-mojo' );?>">
							<i class="fab fa-pinterest-p"></i>
						</a>
					<?php }
	        if(!empty($rj_mojo_tumblr) ){ ?>
						<a class="custom_tumblr" href="<?php echo esc_url($rj_mojo_tumblr); ?>"  title="<?php echo esc_attr( 'Tumblr','rj-mojo' );?>">
							<i class="fab fa-tumblr"></i>
						</a>
					<?php }
	        if(!empty($rj_mojo_instagram) ){ ?>
						<a class="custom_instagram" href="<?php echo esc_url($rj_mojo_instagram); ?>" title="<?php echo esc_attr( 'Instagram','rj-mojo' );?>">
							<i class="fab fa-instagram"></i>
						</a>
					<?php }
	        if(!empty($rj_mojo_youtube) ){ ?>
						<a class="custom_youtube" href="<?php echo esc_url($rj_mojo_youtube); ?>" title="<?php echo esc_attr( 'Youtube','rj-mojo' );?>">
							<i class="fab fa-youtube"></i>
						</a>
					<?php }
	        echo '</div>';
			?>
		</div>
		<?php
	}

	// Widget Backend
	public function form( $rj_mojo_instance ) {

		$rj_mojo_title= ''; $rj_mojo_facebook = ''; $rj_mojo_twitter = ''; $rj_mojo_linkedin = '';  $rj_mojo_pinterest = '';$rj_mojo_tumblr = ''; $rj_mojo_instagram = ''; $rj_mojo_youtube = '';
		$rj_mojo_title = isset( $rj_mojo_instance['title'] ) ? $rj_mojo_instance['title'] : '';
		$rj_mojo_facebook = isset( $rj_mojo_instance['facebook'] ) ? $rj_mojo_instance['facebook'] : '';
		$rj_mojo_whatsapp = isset( $rj_mojo_instance['whatsapp'] ) ? $rj_mojo_instance['whatsapp'] : '';
		$rj_mojo_instagram = isset( $rj_mojo_instance['instagram'] ) ? $rj_mojo_instance['instagram'] : '';
		$rj_mojo_twitter = isset( $rj_mojo_instance['twitter'] ) ? $rj_mojo_instance['twitter'] : '';
		$rj_mojo_linkedin = isset( $rj_mojo_instance['linkedin'] ) ? $rj_mojo_instance['linkedin'] : '';
		$rj_mojo_pinterest = isset( $rj_mojo_instance['pinterest'] ) ? $rj_mojo_instance['pinterest'] : '';
		$rj_mojo_tumblr = isset( $rj_mojo_instance['tumblr'] ) ? $rj_mojo_instance['tumblr'] : '';
		$rj_mojo_youtube = isset( $rj_mojo_instance['youtube'] ) ? $rj_mojo_instance['youtube'] : '';
		?>
		<p>
        <label for="<?php echo esc_attr($this->get_field_id('title')); ?>">
					<?php esc_html_e('Title:','rj-mojo'); ?>
				</label>
        <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($rj_mojo_title); ?>">
    	</p>
		<p>
		<label for="<?php echo esc_attr($this->get_field_id('facebook')); ?>">
			<?php esc_html_e('Facebook:','rj-mojo'); ?>
		</label>
		<input class="widefat" id="<?php echo esc_attr($this->get_field_id('facebook')); ?>" name="<?php echo esc_attr($this->get_field_name('facebook')); ?>" type="text" value="<?php echo esc_attr($rj_mojo_facebook); ?>">
		</p>
		<p>
		<label for="<?php echo esc_attr($this->get_field_id('whatsapp')); ?>">
			<?php esc_html_e('Whatsapp:','rj-mojo'); ?>
		</label>
		<input class="widefat" id="<?php echo esc_attr($this->get_field_id('whatsapp')); ?>" name="<?php echo esc_attr($this->get_field_name('whatsapp')); ?>" type="text" value="<?php echo esc_attr($rj_mojo_facebook); ?>">
		</p>
		<p>
		<label for="<?php echo esc_attr($this->get_field_id('twitter')); ?>">
			<?php esc_html_e('Twitter:','rj-mojo'); ?>
		</label>
		<input class="widefat" id="<?php echo esc_attr($this->get_field_id('twitter')); ?>" name="<?php echo esc_attr($this->get_field_name('twitter')); ?>" type="text" value="<?php echo esc_attr($rj_mojo_twitter); ?>">
		</p>
		<p>
		<label for="<?php echo esc_attr($this->get_field_id('linkedin')); ?>">
			<?php esc_html_e('Linkedin:','rj-mojo'); ?>
		</label>
		<input class="widefat" id="<?php echo esc_attr($this->get_field_id('linkedin')); ?>" name="<?php echo esc_attr($this->get_field_name('linkedin')); ?>" type="text" value="<?php echo esc_attr($rj_mojo_linkedin); ?>">
		</p>
		<p>
		<label for="<?php echo esc_attr($this->get_field_id('instagram')); ?>">
			<?php esc_html_e('Instagram:','rj-mojo'); ?>
		</label>
		<input class="widefat" id="<?php echo esc_attr($this->get_field_id('instagram')); ?>" name="<?php echo esc_attr($this->get_field_name('instagram')); ?>" type="text" value="<?php echo esc_attr($rj_mojo_instagram); ?>">
		</p>
		<p>
		<label for="<?php echo esc_attr($this->get_field_id('pinterest')); ?>">
			<?php esc_html_e('Pinterest:','rj-mojo'); ?>
		</label>
		<input class="widefat" id="<?php echo esc_attr($this->get_field_id('pinterest')); ?>" name="<?php echo esc_attr($this->get_field_name('pinterest')); ?>" type="text" value="<?php echo esc_attr($rj_mojo_pinterest); ?>">
		</p>
		<p>
		<label for="<?php echo esc_attr($this->get_field_id('tumblr')); ?>">
			<?php esc_html_e('Tumblr:','rj-mojo'); ?>
		</label>
		<input class="widefat" id="<?php echo esc_attr($this->get_field_id('tumblr')); ?>" name="<?php echo esc_attr($this->get_field_name('tumblr')); ?>" type="text" value="<?php echo esc_attr($rj_mojo_tumblr); ?>">
		</p>
		<p>
		<label for="<?php echo esc_attr($this->get_field_id('youtube')); ?>"><?php esc_html_e('Youtube:','rj-mojo'); ?></label>
		<input class="widefat" id="<?php echo esc_attr($this->get_field_id('youtube')); ?>" name="<?php echo esc_attr($this->get_field_name('youtube')); ?>" type="text" value="<?php echo esc_attr($rj_mojo_youtube); ?>">
		</p>
		<?php
	}

	public function update( $rj_mojo_new_instance, $rj_mojo_old_instance ) {
		$rj_mojo_instance = array();
		$rj_mojo_instance['title'] = (!empty($rj_mojo_new_instance['title']) ) ? strip_tags($rj_mojo_new_instance['title']) : '';
        $rj_mojo_instance['facebook'] = (!empty($rj_mojo_new_instance['facebook']) ) ? esc_url_raw($rj_mojo_new_instance['facebook']) : '';
		$rj_mojo_instance['whatsapp'] = (!empty($rj_mojo_new_instance['whatsapp']) ) ? esc_url_raw($rj_mojo_new_instance['whatsapp']) : '';
        $rj_mojo_instance['twitter'] = (!empty($rj_mojo_new_instance['twitter']) ) ? esc_url_raw($rj_mojo_new_instance['twitter']) : '';
        $rj_mojo_instance['instagram'] = (!empty($rj_mojo_new_instance['instagram']) ) ? esc_url_raw($rj_mojo_new_instance['instagram']) : '';
        $rj_mojo_instance['linkedin'] = (!empty($rj_mojo_new_instance['linkedin']) ) ? esc_url_raw($rj_mojo_new_instance['linkedin']) : '';
        $rj_mojo_instance['pinterest'] = (!empty($rj_mojo_new_instance['pinterest']) ) ? esc_url_raw($rj_mojo_new_instance['pinterest']) : '';
        $rj_mojo_instance['tumblr'] = (!empty($rj_mojo_new_instance['tumblr']) ) ? esc_url_raw($rj_mojo_new_instance['tumblr']) : '';
     	$rj_mojo_instance['youtube'] = (!empty($rj_mojo_new_instance['youtube']) ) ? esc_url_raw($rj_mojo_new_instance['youtube']) : '';

		return $rj_mojo_instance;
	}
}

function rj_mojo_custom_load_widget() {
	register_widget( 'rj_mojo_Social_Icons' );
}
add_action( 'widgets_init', 'rj_mojo_custom_load_widget' );
