<?php
/**
 * RJ Mojo Custom Control
 *
 */

if ( class_exists( 'WP_Customize_Control' ) ) {
	class RJ_BOOKMARKS_TOGGLE_SWITCH_CUSTOM_CONTROL extends WP_Customize_Control {
		public $type = 'rj_bookmarks_toogle_switch';
		public function enqueue(){
			wp_enqueue_style( 'rj_bookmarks_custom_controls_css', trailingslashit( get_template_directory_uri() ) . 'inc/rj-customizer/assets/rj-customizer.css', array(), '1.0', 'all' );
		}
		public function render_content(){
		?>
			<div class="rj-customizer-toggle">
				<input type="checkbox" id="<?php echo esc_attr($this->id); ?>" name="<?php echo esc_attr($this->id); ?>" value="<?php echo esc_attr( $this->value() ); ?>" <?php $this->link(); checked( $this->value() ); ?>>

				<label class="rj-toggle-btn" for="<?php echo esc_attr( $this->id ); ?>">
					<span class="onoff"><?php echo esc_html('OFF'); ?></span>
					<span class="onoff"><?php echo esc_html('ON'); ?></span>
					<span class="rj-circle"></span>
				</label>
				<span class="rj-customizer-control-title"><?php echo esc_html( $this->label ); ?></span>
				<?php if( !empty( $this->description ) ) { ?>
					<span class="rj-customizer-control-description"><?php echo esc_html( $this->description ); ?></span>
				<?php } ?>
			</div>
		<?php
		}
	}
}

//separator
if ( class_exists( 'WP_Customize_Control' ) ) {
	class RJ_BOOKMARKS_SEPARATOR extends WP_Customize_Control {
		public function render_content(){
		?>
			<div class="rj-mojo-custom-separator">
				<?php echo esc_html( $this->label ); ?>
			</div>
		<?php
		}
	}
}
