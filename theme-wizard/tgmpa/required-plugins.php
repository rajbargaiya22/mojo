<?php

require get_template_directory() . '/theme-wizard/tgmpa/class-tgm-plugin-activation.php';

/**
 * Recommended plugins.
 */
function rb_farm_pro_register_recommended_plugins() {
	$plugins = array(
		array(
			'name'             => __( 'Contact Form 7', 'rj-bookmarks' ),
			'slug'             => 'contact-form-7',
			'required'         => true,
			'force_activation' => false,
		),



	);
	$rb_farm_pro_config = array();
	tgmpa( $plugins, $rb_farm_pro_config );
}
add_action( 'tgmpa_register', 'rb_farm_pro_register_recommended_plugins' );
