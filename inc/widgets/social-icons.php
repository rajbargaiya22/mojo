<?php
/**
 * Custom Social Widget
 */

class RJ_Bookmarks_Social_Icons extends WP_Widget {

	function __construct() {
		parent::__construct(
			'RJ_Bookmarks_Social_Icons',
			__('RJ Social Icon', 'rj-bookmarks'),
			array( 'description' => __( 'Widget for Social icons section', 'rj-bookmarks' ), )
		);
	}

	public function widget( $rj_bookmarks_args, $rj_bookmarks_instance ) { ?>
		<div class="widget12">
			<?php
			$rj_bookmarks_title = isset( $rj_bookmarks_instance['title'] ) ? $rj_bookmarks_instance['title'] : '';
			$rj_bookmarks_facebook = isset( $rj_bookmarks_instance['facebook'] ) ? $rj_bookmarks_instance['facebook'] : '';
			$rj_bookmarks_instagram = isset( $rj_bookmarks_instance['instagram'] ) ? $rj_bookmarks_instance['instagram'] : '';
			$rj_bookmarks_twitter = isset( $rj_bookmarks_instance['twitter'] ) ? $rj_bookmarks_instance['twitter'] : '';
			$rj_bookmarks_linkedin = isset( $rj_bookmarks_instance['linkedin'] ) ? $rj_bookmarks_instance['linkedin'] : '';
			$rj_bookmarks_pinterest = isset( $rj_bookmarks_instance['pinterest'] ) ? $rj_bookmarks_instance['pinterest'] : '';
			$rj_bookmarks_tumblr = isset( $rj_bookmarks_instance['tumblr'] ) ? $rj_bookmarks_instance['tumblr'] : '';
			$rj_bookmarks_youtube = isset( $rj_bookmarks_instance['youtube'] ) ? $rj_bookmarks_instance['youtube'] : '';

			echo '<div class="rj-custom-social-icons">';
			if(!empty($rj_bookmarks_title) ){ ?><h3 class="section-main-head"><?php echo esc_html($rj_bookmarks_title); ?></h3><?php }
					if(!empty($rj_bookmarks_facebook) ){ ?>
						<a class="custom_facebook" href="<?php echo esc_url($rj_bookmarks_facebook); ?>" title="<?php echo esc_attr( 'Facebook','rj-bookmarks' );?>">
							<i class="fa-brands fa-facebook-f"></i>
						</a>
					<?php }
					if(!empty($rj_bookmarks_twitter) ){ ?>
						<a class="custom_twitter" href="<?php echo esc_url($rj_bookmarks_twitter); ?>" title="<?php echo esc_attr( 'Twitter','rj-bookmarks' );?>">
							<i class="fab fa-twitter"></i>
						</a>
					<?php }
	        if(!empty($rj_bookmarks_linkedin) ){ ?>
						<a class="custom_linkedin" href="<?php echo esc_url($rj_bookmarks_linkedin); ?>" title="<?php echo esc_attr( 'Linkedin','rj-bookmarks' );?>">
							<i class="fab fa-linkedin-in"></i>
						</a>
					<?php }
	        if(!empty($rj_bookmarks_pinterest) ){ ?>
						<a class="custom_pinterest" href="<?php echo esc_url($rj_bookmarks_pinterest); ?>" title="<?php echo esc_attr( 'Pinterest','rj-bookmarks' );?>">
							<i class="fab fa-pinterest-p"></i>
						</a>
					<?php }
	        if(!empty($rj_bookmarks_tumblr) ){ ?>
						<a class="custom_tumblr" href="<?php echo esc_url($rj_bookmarks_tumblr); ?>"  title="<?php echo esc_attr( 'Tumblr','rj-bookmarks' );?>">
							<i class="fab fa-tumblr"></i>
						</a>
					<?php }
	        if(!empty($rj_bookmarks_instagram) ){ ?>
						<a class="custom_instagram" href="<?php echo esc_url($rj_bookmarks_instagram); ?>" title="<?php echo esc_attr( 'Instagram','rj-bookmarks' );?>">
							<i class="fab fa-instagram"></i>
						</a>
					<?php }
	        if(!empty($rj_bookmarks_youtube) ){ ?>
						<a class="custom_youtube" href="<?php echo esc_url($rj_bookmarks_youtube); ?>" title="<?php echo esc_attr( 'Youtube','rj-bookmarks' );?>">
							<i class="fab fa-youtube"></i>
						</a>
					<?php }
	        echo '</div>';
			?>
		</div>
		<?php
	}

	// Widget Backend
	public function form( $rj_bookmarks_instance ) {

		$rj_bookmarks_title= ''; $rj_bookmarks_facebook = ''; $rj_bookmarks_twitter = ''; $rj_bookmarks_linkedin = '';  $rj_bookmarks_pinterest = '';$rj_bookmarks_tumblr = ''; $rj_bookmarks_instagram = ''; $rj_bookmarks_youtube = '';
		$rj_bookmarks_title = isset( $rj_bookmarks_instance['title'] ) ? $rj_bookmarks_instance['title'] : '';
		$rj_bookmarks_facebook = isset( $rj_bookmarks_instance['facebook'] ) ? $rj_bookmarks_instance['facebook'] : '';
		$rj_bookmarks_instagram = isset( $rj_bookmarks_instance['instagram'] ) ? $rj_bookmarks_instance['instagram'] : '';
		$rj_bookmarks_twitter = isset( $rj_bookmarks_instance['twitter'] ) ? $rj_bookmarks_instance['twitter'] : '';
		$rj_bookmarks_linkedin = isset( $rj_bookmarks_instance['linkedin'] ) ? $rj_bookmarks_instance['linkedin'] : '';
		$rj_bookmarks_pinterest = isset( $rj_bookmarks_instance['pinterest'] ) ? $rj_bookmarks_instance['pinterest'] : '';
		$rj_bookmarks_tumblr = isset( $rj_bookmarks_instance['tumblr'] ) ? $rj_bookmarks_instance['tumblr'] : '';
		$rj_bookmarks_youtube = isset( $rj_bookmarks_instance['youtube'] ) ? $rj_bookmarks_instance['youtube'] : '';
		?>
		<p>
        <label for="<?php echo esc_attr($this->get_field_id('title')); ?>">
					<?php esc_html_e('Title:','rj-bookmarks'); ?>
				</label>
        <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($rj_bookmarks_title); ?>">
    	</p>
		<p>
		<label for="<?php echo esc_attr($this->get_field_id('facebook')); ?>">
			<?php esc_html_e('Facebook:','rj-bookmarks'); ?>
		</label>
		<input class="widefat" id="<?php echo esc_attr($this->get_field_id('facebook')); ?>" name="<?php echo esc_attr($this->get_field_name('facebook')); ?>" type="text" value="<?php echo esc_attr($rj_bookmarks_facebook); ?>">
		</p>
		<p>
		<label for="<?php echo esc_attr($this->get_field_id('twitter')); ?>">
			<?php esc_html_e('Twitter:','rj-bookmarks'); ?>
		</label>
		<input class="widefat" id="<?php echo esc_attr($this->get_field_id('twitter')); ?>" name="<?php echo esc_attr($this->get_field_name('twitter')); ?>" type="text" value="<?php echo esc_attr($rj_bookmarks_twitter); ?>">
		</p>
		<p>
		<label for="<?php echo esc_attr($this->get_field_id('linkedin')); ?>">
			<?php esc_html_e('Linkedin:','rj-bookmarks'); ?>
		</label>
		<input class="widefat" id="<?php echo esc_attr($this->get_field_id('linkedin')); ?>" name="<?php echo esc_attr($this->get_field_name('linkedin')); ?>" type="text" value="<?php echo esc_attr($rj_bookmarks_linkedin); ?>">
		</p>
		<p>
		<label for="<?php echo esc_attr($this->get_field_id('instagram')); ?>">
			<?php esc_html_e('Instagram:','rj-bookmarks'); ?>
		</label>
		<input class="widefat" id="<?php echo esc_attr($this->get_field_id('instagram')); ?>" name="<?php echo esc_attr($this->get_field_name('instagram')); ?>" type="text" value="<?php echo esc_attr($rj_bookmarks_instagram); ?>">
		</p>
		<p>
		<label for="<?php echo esc_attr($this->get_field_id('pinterest')); ?>">
			<?php esc_html_e('Pinterest:','rj-bookmarks'); ?>
		</label>
		<input class="widefat" id="<?php echo esc_attr($this->get_field_id('pinterest')); ?>" name="<?php echo esc_attr($this->get_field_name('pinterest')); ?>" type="text" value="<?php echo esc_attr($rj_bookmarks_pinterest); ?>">
		</p>
		<p>
		<label for="<?php echo esc_attr($this->get_field_id('tumblr')); ?>">
			<?php esc_html_e('Tumblr:','rj-bookmarks'); ?>
		</label>
		<input class="widefat" id="<?php echo esc_attr($this->get_field_id('tumblr')); ?>" name="<?php echo esc_attr($this->get_field_name('tumblr')); ?>" type="text" value="<?php echo esc_attr($rj_bookmarks_tumblr); ?>">
		</p>
		<p>
		<label for="<?php echo esc_attr($this->get_field_id('youtube')); ?>"><?php esc_html_e('Youtube:','rj-bookmarks'); ?></label>
		<input class="widefat" id="<?php echo esc_attr($this->get_field_id('youtube')); ?>" name="<?php echo esc_attr($this->get_field_name('youtube')); ?>" type="text" value="<?php echo esc_attr($rj_bookmarks_youtube); ?>">
		</p>
		<?php
	}

	public function update( $rj_bookmarks_new_instance, $rj_bookmarks_old_instance ) {
		$rj_bookmarks_instance = array();
		$rj_bookmarks_instance['title'] = (!empty($rj_bookmarks_new_instance['title']) ) ? strip_tags($rj_bookmarks_new_instance['title']) : '';
        $rj_bookmarks_instance['facebook'] = (!empty($rj_bookmarks_new_instance['facebook']) ) ? esc_url_raw($rj_bookmarks_new_instance['facebook']) : '';
        $rj_bookmarks_instance['twitter'] = (!empty($rj_bookmarks_new_instance['twitter']) ) ? esc_url_raw($rj_bookmarks_new_instance['twitter']) : '';
        $rj_bookmarks_instance['instagram'] = (!empty($rj_bookmarks_new_instance['instagram']) ) ? esc_url_raw($rj_bookmarks_new_instance['instagram']) : '';
        $rj_bookmarks_instance['linkedin'] = (!empty($rj_bookmarks_new_instance['linkedin']) ) ? esc_url_raw($rj_bookmarks_new_instance['linkedin']) : '';
        $rj_bookmarks_instance['pinterest'] = (!empty($rj_bookmarks_new_instance['pinterest']) ) ? esc_url_raw($rj_bookmarks_new_instance['pinterest']) : '';
        $rj_bookmarks_instance['tumblr'] = (!empty($rj_bookmarks_new_instance['tumblr']) ) ? esc_url_raw($rj_bookmarks_new_instance['tumblr']) : '';
     	$rj_bookmarks_instance['youtube'] = (!empty($rj_bookmarks_new_instance['youtube']) ) ? esc_url_raw($rj_bookmarks_new_instance['youtube']) : '';

		return $rj_bookmarks_instance;
	}
}

function rj_bookmarks_custom_load_widget() {
	register_widget( 'RJ_Bookmarks_Social_Icons' );
}
add_action( 'widgets_init', 'rj_bookmarks_custom_load_widget' );
