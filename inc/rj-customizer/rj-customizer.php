<?php
function rj_mojo_add_custom_controls() {
	load_template( trailingslashit( get_template_directory() ) . '/inc/rj-customizer/rj-toggle-controls.php' );
}
add_action( 'customize_register', 'rj_mojo_add_custom_controls' );

function rj_mojo_customizer_register( $wp_customize ){
	//  site title and tagline
	$wp_customize->add_setting( 'rj_mojo_site_title',array(
		'default' => 0,
		'transport' => 'refresh',
		'sanitize_callback' => 'rj_mojo_toggle_sanitization'
	));
	$wp_customize->add_control( new rj_mojo_TOGGLE_SWITCH_CUSTOM_CONTROL( $wp_customize, 'rj_mojo_site_title',array(
		'label' => esc_html__( 'Show / Hide Title','rj-mojo' ),
		'section' => 'title_tagline'
	)));

	$wp_customize->add_setting( 'rj_mojo_site_description',array(
		'default' => 0,
		'transport' => 'refresh',
		'sanitize_callback' => 'rj_mojo_toggle_sanitization'
	));
	$wp_customize->add_control( new rj_mojo_TOGGLE_SWITCH_CUSTOM_CONTROL( $wp_customize, 'rj_mojo_site_description',array(
		'label' => esc_html__( 'Show / Hide Description','rj-mojo' ),
		'section' => 'title_tagline'
	)));

	$wp_customize->add_setting( 'rj_mojo_site_content_aside',array(
		'default' => 0,
		'transport' => 'refresh',
		'sanitize_callback' => 'rj_mojo_toggle_sanitization'
	));
	$wp_customize->add_control( new rj_mojo_TOGGLE_SWITCH_CUSTOM_CONTROL( $wp_customize, 'rj_mojo_site_content_aside',array(
		'label' => esc_html__( 'Show Title Beside the Logo ','rj-mojo' ),
		'section' => 'title_tagline'
	)));

	// add home page setting pannel
	$wp_customize->add_panel( 'rj_mojo_panel_id', array(
		'capability' => 'edit_theme_options',
		'theme_supports' => '',
		'title' => esc_html__( 'Homepage Settings', 'rj-mojo' ),
		'priority' => 10,
	));

	// banner setting
	$wp_customize->add_section( 'rj_mojo_banner_section' , array(
		'title'      => __( 'Banner Section', 'rj-mojo' ),
		'priority'   => null,
		'panel' => 'rj_mojo_panel_id'
	) );

	$girl_image_url = get_template_directory_uri() . '/assets/images/girl.png';

	$wp_customize->add_setting('rj_mojo_make_ideas_image',array(
		'default'	=> $girl_image_url,
		'sanitize_callback'	=> 'esc_url_raw',
	));
	$wp_customize->add_control( new WP_Customize_Image_Control($wp_customize,'rj_mojo_make_ideas_image',array(
        'label' => __('Girl Image','rj-mojo'),
        'section' => 'rj_mojo_banner_section'
	)));

	$wp_customize->add_setting('rj_mojo_make_ideas_text',array(
		'default'=> 'Make your ideas alive!',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('rj_mojo_make_ideas_text',array(
		'label'	=> __('Add Text','rj-mojo'),
		'input_attrs' => array(
			'placeholder' => __( 'Make your ideas alive!', 'rj-mojo' ),
		),
		'section'=> 'rj_mojo_banner_section',
		'type'=> 'text'
	));

	$star_image_url = get_template_directory_uri() . '/assets/images/star.png';

	$wp_customize->add_setting('rj_mojo_star_image',array(
		'default'	=> $star_image_url,
		'sanitize_callback'	=> 'esc_url_raw',
	));
	$wp_customize->add_control( new WP_Customize_Image_Control($wp_customize,'rj_mojo_star_image',array(
        'label' => __('Star Image','rj-mojo'),
        'section' => 'rj_mojo_banner_section'
	)));

	$wp_customize->add_setting('rj_mojo_star_text',array(
		'default'=> 'Let your imagination fly',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('rj_mojo_star_text',array(
		'label'	=> __('Add Text','rj-mojo'),
		'input_attrs' => array(
			'placeholder' => __( 'Let your imagination fly', 'rj-mojo' ),
		),
		'section'=> 'rj_mojo_banner_section',
		'type'=> 'text'
	));

	$facebook_image_url = get_template_directory_uri() . '/assets/images/facebook.png';

	$wp_customize->add_setting('rj_mojo_facebook_image',array(
		'default'	=> $facebook_image_url,
		'sanitize_callback'	=> 'esc_url_raw',
	));
	$wp_customize->add_control( new WP_Customize_Image_Control($wp_customize,'rj_mojo_facebook_image',array(
        'label' => __('Facebook Image','rj-mojo'),
        'section' => 'rj_mojo_banner_section'
	)));

	$wp_customize->add_setting('rj_mojo_product_facebook_url',array(
		'default'=> 'https://www.facebook.com/default',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('rj_mojo_product_facebook_url',array(
		'label'	=> esc_html__('Facebook Url','rj-mojo'),
		'section'=> 'rj_mojo_banner_section',
		'type'=> 'text'
	));

	$instagram_image_url = get_template_directory_uri() . '/assets/images/instagram.png';

	$wp_customize->add_setting('rj_mojo_instagram_image',array(
		'default'	=> $instagram_image_url,
		'sanitize_callback'	=> 'esc_url_raw',
	));
	$wp_customize->add_control( new WP_Customize_Image_Control($wp_customize,'rj_mojo_instagram_image',array(
        'label' => __('Instagram Image','rj-mojo'),
        'section' => 'rj_mojo_banner_section'
	)));

	$wp_customize->add_setting('rj_mojo_product_instagram_url',array(
		'default'=> '#',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('rj_mojo_product_instagram_url',array(
		'label'	=> esc_html__('Instagram Url','rj-mojo'),
		'section'=> 'rj_mojo_banner_section',
		'type'=> 'text'
	));

	$wp_customize->add_setting('rj_mojo_tree_text',array(
		'default'=> 'Play, Discover, Build and Grow',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('rj_mojo_tree_text',array(
		'label'	=> __('Add Text','rj-mojo'),
		'input_attrs' => array(
			'placeholder' => __( 'Play, Discover, Build and Grow', 'rj-mojo' ),
		),
		'section'=> 'rj_mojo_banner_section',
		'type'=> 'text'
	));

	$tree_image_url = get_template_directory_uri() . '/assets/images/tree.png';

	$wp_customize->add_setting('rj_mojo_tree_image',array(
		'default'	=> $tree_image_url,
		'sanitize_callback'	=> 'esc_url_raw',
	));
	$wp_customize->add_control( new WP_Customize_Image_Control($wp_customize,'rj_mojo_tree_image',array(
        'label' => __('Tree Image','rj-mojo'),
        'section' => 'rj_mojo_banner_section'
	)));

	$chemical_component_image_url = get_template_directory_uri() . '/assets/images/chemical-component.png';

	$wp_customize->add_setting('rj_mojo_chemical_component_image',array(
		'default'	=> $chemical_component_image_url,
		'sanitize_callback'	=> 'esc_url_raw',
	));
	$wp_customize->add_control( new WP_Customize_Image_Control($wp_customize,'rj_mojo_chemical_component_image',array(
        'label' => __('Chemical Component Image','rj-mojo'),
        'section' => 'rj_mojo_banner_section'
	)));

	$wp_customize->add_setting('rj_mojo_science_text',array(
		'default'=> 'Science',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('rj_mojo_science_text',array(
		'label'	=> __('Add Text','rj-mojo'),
		'input_attrs' => array(
			'placeholder' => __( 'Science', 'rj-mojo' ),
		),
		'section'=> 'rj_mojo_banner_section',
		'type'=> 'text'
	));

	$wp_customize->add_setting('rj_mojo_technology_text',array(
		'default'=> 'Technology',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('rj_mojo_technology_text',array(
		'label'	=> __('Add Text','rj-mojo'),
		'input_attrs' => array(
			'placeholder' => __( 'Technology', 'rj-mojo' ),
		),
		'section'=> 'rj_mojo_banner_section',
		'type'=> 'text'
	));

	$wp_customize->add_setting('rj_mojo_engineering_text',array(
		'default'=> 'Engineering',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('rj_mojo_engineering_text',array(
		'label'	=> __('Add Text','rj-mojo'),
		'input_attrs' => array(
			'placeholder' => __( 'Engineering', 'rj-mojo' ),
		),
		'section'=> 'rj_mojo_banner_section',
		'type'=> 'text'
	));

	$wp_customize->add_setting('rj_mojo_arts_text',array(
		'default'=> 'Arts',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('rj_mojo_arts_text',array(
		'label'	=> __('Add Text','rj-mojo'),
		'input_attrs' => array(
			'placeholder' => __( 'Arts', 'rj-mojo' ),
		),
		'section'=> 'rj_mojo_banner_section',
		'type'=> 'text'
	));

	$wp_customize->add_setting('rj_mojo_mathematics_text',array(
		'default'=> 'Mathematics',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('rj_mojo_mathematics_text',array(
		'label'	=> __('Add Text','rj-mojo'),
		'input_attrs' => array(
			'placeholder' => __( 'Mathematics', 'rj-mojo' ),
		),
		'section'=> 'rj_mojo_banner_section',
		'type'=> 'text'
	));

	$chemical_image_url = get_template_directory_uri() . '/assets/images/chemical.png';

	$wp_customize->add_setting('rj_mojo_chemical_image',array(
		'default'	=> $chemical_image_url,
		'sanitize_callback'	=> 'esc_url_raw',
	));
	$wp_customize->add_control( new WP_Customize_Image_Control($wp_customize,'rj_mojo_chemical_image',array(
        'label' => __('Chemical Image','rj-mojo'),
        'section' => 'rj_mojo_banner_section'
	)));

	$world_image_url = get_template_directory_uri() . '/assets/images/world.png';

	$wp_customize->add_setting('rj_mojo_world_image',array(
		'default'	=> $world_image_url,
		'sanitize_callback'	=> 'esc_url_raw',
	));
	$wp_customize->add_control( new WP_Customize_Image_Control($wp_customize,'rj_mojo_world_image',array(
        'label' => __('World Image','rj-mojo'),
        'section' => 'rj_mojo_banner_section'
	)));

	$wp_customize->add_setting('rj_mojo_world_text',array(
		'default'=> 'Changing the world is childs play',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('rj_mojo_world_text',array(
		'label'	=> __('Add Text','rj-mojo'),
		'input_attrs' => array(
			'placeholder' => __( 'Changing the world is childs play', 'rj-mojo' ),
		),
		'section'=> 'rj_mojo_banner_section',
		'type'=> 'text'
	));

	$formula_image_url = get_template_directory_uri() . '/assets/images/formula.png';

	$wp_customize->add_setting('rj_mojo_formula_image',array(
		'default'	=> $formula_image_url,
		'sanitize_callback'	=> 'esc_url_raw',
	));
	$wp_customize->add_control( new WP_Customize_Image_Control($wp_customize,'rj_mojo_formula_image',array(
        'label' => __('formula Image','rj-mojo'),
        'section' => 'rj_mojo_banner_section'
	)));

	$wp_customize->add_setting('rj_mojo_formula_text',array(
		'default'=> 'The Future belongs to the curious.',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('rj_mojo_formula_text',array(
		'label'	=> __('Add Text','rj-mojo'),
		'input_attrs' => array(
			'placeholder' => __( 'The Future belongs to the curious.', 'rj-mojo' ),
		),
		'section'=> 'rj_mojo_banner_section',
		'type'=> 'text'
	));

	$about_image_url = get_template_directory_uri() . '/assets/images/about.png';

	$wp_customize->add_setting('rj_mojo_about_image',array(
		'default'	=> $about_image_url,
		'sanitize_callback'	=> 'esc_url_raw',
	));
	$wp_customize->add_control( new WP_Customize_Image_Control($wp_customize,'rj_mojo_about_image',array(
        'label' => __('About Image','rj-mojo'),
        'section' => 'rj_mojo_banner_section'
	)));

	// About setting
	$wp_customize->add_section( 'rj_mojo_about_section' , array(
		'title'      => __( 'About Section', 'rj-mojo' ),
		'priority'   => null,
		'panel' => 'rj_mojo_panel_id'
	) );

	$wp_customize->add_setting('rj_mojo_about_us_text',array(
		'default'=> 'About Us',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('rj_mojo_about_us_text',array(
		'label'	=> __('Add Text','rj-mojo'),
		'input_attrs' => array(
			'placeholder' => __( 'About Us', 'rj-mojo' ),
		),
		'section'=> 'rj_mojo_about_section',
		'type'=> 'text'
	));

	$wp_customize->add_setting('rj_mojo_museum_heading',array(
		'default'=> 'Welcome to Nagpurs Pride and Joy — The Museum of Joy!',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	
	$wp_customize->add_control('rj_mojo_museum_heading',array(
		'label'	=> __('Add Text','rj-mojo'),
		'input_attrs' => array(
			'placeholder' => __( 'Welcome to Nagpurs Pride and Joy — The Museum of Joy!', 'rj-mojo' ),
		),
		'section'=> 'rj_mojo_about_section',
		'type'=> 'text'
	));

	$museum_text = 'At the Museum of Joy, affectionately known as MoJo, merriment and wonder await at every turn. Our one-of-a-kind interactive museum is a realm where imagination thrives and every idea finds a place to blossom. Here, young explorers are invited to embark on endless journeys of discovery, unlocking the magic of learning through play. MoJo serves as a haven for children eager to explore, create, and evolve. Through our dynamic exhibits and engaging activities, we inspire young minds to become curious, responsible, and compassionate global citizens. Each visit to MoJo is designed to kindle a love for learning and foster a sense of wonder about the world around them.';

	$wp_customize->add_setting('rj_mojo_museum_text',array(
		'default'=> $museum_text,
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('rj_mojo_museum_text',array(
		'label'	=> __('Add Text','rj-mojo'),
		'section'=> 'rj_mojo_about_section',
		'type'=> 'textarea'
	));
	
	$wp_customize->add_setting('rj_mojo_our_mission_heading',array(
		'default'=> 'Our Mission',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('rj_mojo_our_mission_heading',array(
		'label'	=> __('Add Text','rj-mojo'),
		'input_attrs' => array(
			'placeholder' => __( 'Our Mission', 'rj-mojo' ),
		),
		'section'=> 'rj_mojo_about_section',
		'type'=> 'text'
	));

	$mission_text="At MoJo, we are passionate believers in the transformative power of youth. Today’s children hold the keys to tomorrows success, and we see it as our duty to nourish and empower these young visionaries. Our commitment is to provide a nurturing environment where young hearts can bloom, minds can expand, and dreams can soar to new heights.";
	
	$wp_customize->add_setting('rj_mojo_our_mission_text',array(
		'default'=> $mission_text,
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('rj_mojo_our_mission_text',array(
		'label'	=> __('Add Text','rj-mojo'),
		'section'=> 'rj_mojo_about_section',
		'type'=> 'textarea'
	));

	$wp_customize->add_setting('rj_mojo_our_programs_heading',array(
		'default'=> 'Our Programs',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('rj_mojo_our_programs_heading',array(
		'label'	=> __('Add Text','rj-mojo'),
		'input_attrs' => array(
			'placeholder' => __( 'Our Programs', 'rj-mojo' ),
		),
		'section'=> 'rj_mojo_about_section',
		'type'=> 'text'
	));

	$our_programs="Our unique programs create a vibrant landscape where creativity, curiosity, and compassion take root. We encourage every child to embrace their individuality and reach their full potential. Through immersive exhibits, interactive experiences, and hands-on learning opportunities, we ignite the spark of innovation, foster collaboration, and equip our young adventurers with the skills to lead with confidence and kindness in an ever-changing world.";

	$wp_customize->add_setting('rj_mojo_our_programs_text',array(
		'default'=> $our_programs,
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('rj_mojo_our_programs_text',array(
		'label'	=> __('Add Text','rj-mojo'),
		'section'=> 'rj_mojo_about_section',
		'type'=> 'textarea'
	));

	$wp_customize->add_setting('rj_mojo_our_inspiration_heading',array(
		'default'=> 'Our Inspiration',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('rj_mojo_our_inspiration_heading',array(
		'label'	=> __('Add Text','rj-mojo'),
		'input_attrs' => array(
			'placeholder' => __( 'Our Inspiration', 'rj-mojo' ),
		),
		'section'=> 'rj_mojo_about_section',
		'type'=> 'text'
	));

	$inspiration="Inspired by the timeless wisdom of Albert Einstein — The mind that opens to a new idea never returns to its original size — we strive to open young minds to endless possibilities.";

	$wp_customize->add_setting('rj_mojo_our_inspiration_text',array(
		'default'=> $inspiration,
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('rj_mojo_our_inspiration_text',array(
		'label'	=> __('Add Text','rj-mojo'),
		'section'=> 'rj_mojo_about_section',
		'type'=> 'textarea'
	));

	$wp_customize->add_setting('rj_mojo_our_join_us_text',array(
		'default'=> 'Join us on this joyous journey where imagination comes to life!',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('rj_mojo_our_join_us_text',array(
		'label'	=> __('Add Text','rj-mojo'),
		'input_attrs' => array(
			'placeholder' => __( 'Join us on this joyous journey where imagination comes to life!', 'rj-mojo' ),
		),
		'section'=> 'rj_mojo_about_section',
		'type'=> 'text'
	));

	// Footer setting
	$wp_customize->add_section( 'rj_mojo_footer_section' , array(
		'title'      => __( 'Footer Section', 'rj-mojo' ),
		'priority'   => null,
		'panel' => 'rj_mojo_panel_id'
	) );

	$location_image_url = get_template_directory_uri() . '/assets/images/location.png';

	$wp_customize->add_setting('rj_mojo_location_image',array(
		'default'	=> $location_image_url,
		'sanitize_callback'	=> 'esc_url_raw',
	));
	$wp_customize->add_control( new WP_Customize_Image_Control($wp_customize,'rj_mojo_location_image',array(
        'label' => __('Location Image','rj-mojo'),
        'section' => 'rj_mojo_footer_section'
	)));

	$wp_customize->add_setting('rj_mojo_location_text',array(
		'default'=> 'Location',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('rj_mojo_location_text',array(
		'label'	=> __('Add Text','rj-mojo'),
		'input_attrs' => array(
			'placeholder' => __( 'Location', 'rj-mojo' ),
		),
		'section'=> 'rj_mojo_footer_section',
		'type'=> 'text'
	));

	$wp_customize->add_setting('rj_mojo_address_text',array(
		'default'=> 'Museum Of Joy Dhandhania House East High Court Civil Lines 440001 Nagpur',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('rj_mojo_address_text',array(
		'label'	=> __('Add Text','rj-mojo'),
		'input_attrs' => array(
			'placeholder' => __( 'Museum Of Joy Dhandhania House East High Court Civil Lines 440001 Nagpur', 'rj-mojo' ),
		),
		'section'=> 'rj_mojo_footer_section',
		'type'=> 'text'
	));

	$contact_image_url = get_template_directory_uri() . '/assets/images/contact.png';
	$wp_customize->add_setting('rj_mojo_contact_image',array(
		'default'	=> $contact_image_url,
		'sanitize_callback'	=> 'esc_url_raw',
	));
	$wp_customize->add_control( new WP_Customize_Image_Control($wp_customize,'rj_mojo_contact_image',array(
        'label' => __('contact Image','rj-mojo'),
        'section' => 'rj_mojo_footer_section'
	)));

	$wp_customize->add_setting('rj_mojo_contact_text',array(
		'default'=> 'Contact',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('rj_mojo_contact_text',array(
		'label'	=> __('Add Text','rj-mojo'),
		'input_attrs' => array(
			'placeholder' => __( 'Contact', 'rj-mojo' ),
		),
		'section'=> 'rj_mojo_footer_section',
		'type'=> 'text'
	));

	$wp_customize->add_setting('rj_mojo_email_address',array(
		'default'=> 'mojo.nagpur@gmail.com',
		'sanitize_callback'	=> 'sanitize_email'
	));
	$wp_customize->add_control('rj_mojo_email_address',array(
		'label'	=> __('Add Email Address','rj-mojo'),
		'input_attrs' => array(
            'placeholder' => __( 'mojo.nagpur@gmail.com', 'rj-mojo' ),
        ),
		'section'=> 'rj_mojo_footer_section',
		'type'=> 'text'
	));


	// Book Visit Setting
	$wp_customize->add_section( 'rj_mojo_book_visit_section' , array(
		'title'      => __( 'Book Visit Section', 'rj-mojo' ),
		'priority'   => null,
		'panel' => 'rj_mojo_panel_id'
	) );

	$wp_customize->add_setting('rj_mojo_book_visit_heading',array(
		'default'=> 'Book Your Visit',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('rj_mojo_book_visit_heading',array(
		'label'	=> __('Add Text','rj-mojo'),
		'input_attrs' => array(
			'placeholder' => __( 'Book Your Visit', 'rj-mojo' ),
		),
		'section'=> 'rj_mojo_book_visit_section',
		'type'=> 'text'
	));

	$book_visit_para="Welcome to our dreamworld for kids! Here, creativity and curiosity thrive. Every corner is an adventure, and every activity sparks wonder. Join us to let your child’s imagination run wild and see their dreams come to life!";
	$wp_customize->add_setting('rj_mojo_book_visit_text',array(
		'default'=> $book_visit_para,
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('rj_mojo_book_visit_text',array(
		'label'	=> __('Add Text','rj-mojo'),
		'input_attrs' => array(
			'placeholder' => __($book_visit_para, 'rj-mojo' ),
		),
		'section'=> 'rj_mojo_book_visit_section',
		'type'=> 'textarea'
	));


	// Air Play Setting
	$wp_customize->add_section( 'rj_mojo_air_play_section' , array(
		'title'      => __( 'Air Play Section', 'rj-mojo' ),
		'priority'   => null,
		'panel' => 'rj_mojo_panel_id'
	) );

	$wp_customize->add_setting('rj_mojo_air_play_heading',array(
		'default'=> 'Air Play',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('rj_mojo_air_play_heading',array(
		'label'	=> __('Add Text','rj-mojo'),
		'input_attrs' => array(
			'placeholder' => __( 'Air Play', 'rj-mojo' ),
		),
		'section'=> 'rj_mojo_air_play_section',
		'type'=> 'text'
	));

	$air_play_para="Ever wondered how planes stay up in the sky? Or how air can hold things up? Discover the exciting atmosphere of flight and physics!";
	$wp_customize->add_setting('rj_mojo_air_play_text',array(
		'default'=> $air_play_para,
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('rj_mojo_air_play_text',array(
		'label'	=> __('Add Text','rj-mojo'),
		'input_attrs' => array(
			'placeholder' => __($air_play_para, 'rj-mojo' ),
		),
		'section'=> 'rj_mojo_air_play_section',
		'type'=> 'textarea'
	));



  //  menu list
  $menus = wp_get_nav_menus();
  $menu_list = array();

  if ($menus) {
      foreach ($menus as $menu) {
          $menu_list[$menu->name] = esc_html($menu->name);
      }
  } else {
      echo 'No menus found.';
  }

  $wp_customize->add_panel( 'rj_mojo_add_panel', array(
    'capability' => 'edit_theme_options',
    'theme_supports' => '',
    'title' => esc_html__( 'Aviationist Theme Settings', 'rj-mojo' ),
    'priority' => 10,
  ));

  // Topbar START
  $wp_customize->add_section('rj_mojo_topabr' , array(
    'title' => __( 'Topbar', 'rj-mojo' ),
    'panel' => 'rj_mojo_add_panel'
  ) );

	$wp_customize->add_setting('rj_mojo_topbar_menu',array(
		'default' => 'topbar',
		'transport' => 'refresh',
		'sanitize_callback' => 'rj_mojo_customizer_sanitize_choices'
	));
	$wp_customize->add_control('rj_mojo_topbar_menu',array(
		'type' => 'select',
		'label' => __('Select the Menu','rj-mojo'),
		'section' => 'rj_mojo_topabr',
		'choices' 	=> $menu_list,
	));

	$wp_customize->add_setting('rj_mojo_topbar_icon_number',array(
		'default'=> '4',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('rj_mojo_topbar_icon_number',array(
		'label'	=> esc_html__('Number of icons to show','rj-mojo'),
		'section'=> 'rj_mojo_topabr',
		'type'=> 'number'
	));

	$topbar_icons = get_theme_mod('rj_mojo_topbar_icon_number');
	for ($i=0; $i < $topbar_icons ; $i++) {
		$wp_customize->add_setting( 'rj_mojo_topbar_icon_separator'.$i,array(
			'default' => '',
			'transport' => 'refresh',
			'sanitize_callback' => 'rj_mojo_toggle_sanitization'
	  ));

	// 	$wp_customize->add_setting( sprintf( 'rj_mojo_topbar_icon_separator%s', $i ), array(
	//     'default' => '',
	//     'transport' => 'refresh',
	//     'sanitize_callback' => 'rj_mojo_toggle_sanitization',
	// ));

	  $wp_customize->add_control( new rj_mojo_SEPARATOR( $wp_customize, 'rj_mojo_topbar_icon_separator'.$i,array(
			'label' => esc_html__( 'Icon '.($i + 1),'rj-mojo' ),
			'section' => 'rj_mojo_topabr'
	  )));

		$wp_customize->add_setting('rj_mojo_topbar_icon'.$i,array(
			'default'=> '',
			// 'sanitize_callback'	=> 'sanitize_text_field'
		));
		$wp_customize->add_control('rj_mojo_topbar_icon'.$i,array(
			'label'	=> esc_html__('Icon Svg Code','rj-mojo'),
			'description' => __( 'Add the svg code', 'rj-mojo' ),
			'section'=> 'rj_mojo_topabr',
			'type'=> 'textarea'
		));

		$wp_customize->add_setting('rj_mojo_topbar_icon_url'.$i,array(
			'default'=> '',
			'sanitize_callback'	=> 'sanitize_text_field'
		));
		$wp_customize->add_control('rj_mojo_topbar_icon_url'.$i,array(
			'label'	=> esc_html__('Url','rj-mojo'),
			'section'=> 'rj_mojo_topabr',
			'type'=> 'text'
		));

		$wp_customize->add_setting('rj_mojo_topbar_icon_title'.$i,array(
			'default'=> '',
			'sanitize_callback'	=> 'sanitize_text_field'
		));
		$wp_customize->add_control('rj_mojo_topbar_icon_title'.$i,array(
			'label'	=> esc_html__('Title','rj-mojo'),
			'description' => __( 'Add the title for the SEO purpose', 'rj-mojo' ),
			'section'=> 'rj_mojo_topabr',
			'type'=> 'text'
		));

	}
  // Topbar END

	//  news scroller
	$wp_customize->add_section('rj_mojo_news_scroller' , array(
    'title' => __( 'News Scroll Bar', 'rj-mojo' ),
    'panel' => 'rj_mojo_add_panel'
  ) );

	$wp_customize->add_setting('rj_mojo_news_ribbon_heading',array(
		'default'=> 'News Tickers',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('rj_mojo_news_ribbon_heading',array(
		'label'	=> esc_html__('Text','rj-mojo'),
		'section'=> 'rj_mojo_news_scroller',
		'type'=> 'text'
	));

	$wp_customize->add_setting('rj_mojo_news_ribbon_post_num',array(
		'default'=> '5',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('rj_mojo_news_ribbon_post_num',array(
		'label'	=> esc_html__('Number of post','rj-mojo'),
		'section'=> 'rj_mojo_news_scroller',
		'type'=> 'number'
	));

	//  slider
	$wp_customize->add_section('rj_mojo_news_slider' , array(
    'title' => __( 'Slider', 'rj-mojo' ),
    'panel' => 'rj_mojo_add_panel'
  ) );

	$wp_customize->add_setting('rj_mojo_slider_post_num',array(
		'default'=> '4',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('rj_mojo_slider_post_num',array(
		'label'	=> esc_html__('Number Of Slider To Show','rj-mojo'),
		'section'=> 'rj_mojo_news_slider',
		'type'=> 'number'
	));

	// Categories section
	$wp_customize->add_section('rj_mojo_post_categories' , array(
		'title' => __( 'Category', 'rj-mojo' ),
		'panel' => 'rj_mojo_add_panel'
	) );

	$wp_customize->add_setting('rj_mojo_category_heading',array(
		'default'=> 'CATEGORIES',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('rj_mojo_category_heading',array(
		'label'	=> esc_html__('Category Heading','rj-mojo'),
		'section'=> 'rj_mojo_post_categories',
		'type'=> 'text'
	));

	$wp_customize->add_setting('rj_mojo_category_see_more',array(
		'default'=> 'See More',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('rj_mojo_category_see_more',array(
		'label'	=> esc_html__('See More','rj-mojo'),
		'section'=> 'rj_mojo_post_categories',
		'type'=> 'text'
	));

	// $wp_customize->add_setting('rj_mojo_category_see_more_url',array(
	// 	'default'=> '#',
	// 	'sanitize_callback'	=> 'sanitize_text_field'
	// ));
	// $wp_customize->add_control('rj_mojo_category_see_more_url',array(
	// 	'label'	=> esc_html__('See More Url','rj-mojo'),
	// 	'section'=> 'rj_mojo_post_categories',
	// 	'type'=> 'text'
	// ));

	$wp_customize->add_setting('rj_mojo_category_cat_num',array(
		'default'=> '10',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('rj_mojo_category_cat_num',array(
		'label'	=> esc_html__('Number of tabs to show','rj-mojo'),
		'section'=> 'rj_mojo_post_categories',
		'type'=> 'number'
	));

	$wp_customize->add_setting('rj_mojo_category_view_more',array(
		'default'=> 'View More',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('rj_mojo_category_view_more',array(
		'label'	=> esc_html__('Number of tabs to show','rj-mojo'),
		'section'=> 'rj_mojo_post_categories',
		'type'=> 'text'
	));

	// other artilces
	$wp_customize->add_section('rj_mojo_other_articles' , array(
		'title' => __( 'Other Articles', 'rj-mojo' ),
		'panel' => 'rj_mojo_add_panel'
	) );

	$wp_customize->add_setting('rj_mojo_other_articles_heading',array(
		'default'=> 'OTHER ARTICLES',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('rj_mojo_other_articles_heading',array(
		'label'	=> esc_html__('Other Articles','rj-mojo'),
		'section'=> 'rj_mojo_other_articles',
		'type'=> 'text'
	));

	// 	comment policy
	$wp_customize->add_section('rj_mojo_comment_policy' , array(
		'title' => __( 'Comment Policy', 'rj-mojo' ),
		'panel' => 'rj_mojo_add_panel'
	) );

	$wp_customize->add_setting('rj_mojo_comments_policy_bgimage',array(
	    'default'   => '',
	    'sanitize_callback' => 'esc_url_raw',
	  ));
  $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize,'rj_mojo_comments_policy_bgimage',array(
      'label' => __('Background Image ','rj-mojo'),
      'description' => __('Dimension (1600px * 700px)','rj-mojo'),
      'section' => 'rj_mojo_comment_policy',
      'settings' => 'rj_mojo_comments_policy_bgimage'
  )));

	$wp_customize->add_setting('rj_mojo_comment_policy_heading',array(
		'default'=> 'The Aviationist Comment Policy',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('rj_mojo_comment_policy_heading',array(
		'label'	=> esc_html__('Heading','rj-mojo'),
		'section'=> 'rj_mojo_comment_policy',
		'type'=> 'text'
	));

	$wp_customize->add_setting('rj_mojo_comment_policy_para',array(
		'default'=> 'Comments on this site are moderated. Comment policy applies. Please read our Comment Policy before commenting.',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('rj_mojo_comment_policy_para',array(
		'label'	=> esc_html__('Description','rj-mojo'),
		'section'=> 'rj_mojo_comment_policy',
		'type'=> 'textarea'
	));

	$wp_customize->add_setting('rj_mojo_comment_policy_btn',array(
		'default'=> 'Got It',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('rj_mojo_comment_policy_btn',array(
		'label'	=> esc_html__('Button','rj-mojo'),
		'section'=> 'rj_mojo_comment_policy',
		'type'=> 'text'
	));

	$wp_customize->add_setting('rj_mojo_comment_policy_btn_url',array(
		'default'=> '#',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('rj_mojo_comment_policy_btn_url',array(
		'label'	=> esc_html__('Button Url','rj-mojo'),
		'section'=> 'rj_mojo_comment_policy',
		'type'=> 'text'
	));

	//  also on aviationist
	$wp_customize->add_section('rj_mojo_also_on_aviationist' , array(
		'title' => __( 'Also on aviationist', 'rj-mojo' ),
		'panel' => 'rj_mojo_add_panel'
	) );
	$wp_customize->add_setting('rj_mojo_also_on_aviationist_heading',array(
		'default'=> 'ALSO ON THE AVIATIONIST',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('rj_mojo_also_on_aviationist_heading',array(
		'label'	=> esc_html__('Heading','rj-mojo'),
		'section'=> 'rj_mojo_also_on_aviationist',
		'type'=> 'text'
	));
	$wp_customize->add_setting('rj_mojo_also_on_aviationist_post_num',array(
		'default'=> '-1',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('rj_mojo_also_on_aviationist_post_num',array(
		'label'	=> esc_html__('Number of post to show','rj-mojo'),
		'section'=> 'rj_mojo_also_on_aviationist',
		'type'=> 'text'
	));


	//  contact us page
	$wp_customize->add_section('rj_mojo_contact_us_page' , array(
		'title' => __( 'Contact Us Page', 'rj-mojo' ),
		'panel' => 'rj_mojo_add_panel'
	) );
	$wp_customize->add_setting('rj_mojo_contact_us_description',array(
		'default'=> 'If you want to tell me something or if you need to prepare books, articles, brochures, datasheets, documentaries, presentations, meetings, movies and so on, and need the world’s most authoritative aviation journalist and blogger, you can send me an email.',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('rj_mojo_contact_us_description',array(
		'label'	=> esc_html__('Page Description','rj-mojo'),
		'section'=> 'rj_mojo_contact_us_page',
		'type'=> 'textarea'
	));

	$wp_customize->add_setting('rj_mojo_contact_us_page_title',array(
		'default'=> 'Get in touch',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('rj_mojo_contact_us_page_title',array(
		'label'	=> esc_html__('Heading','rj-mojo'),
		'section'=> 'rj_mojo_contact_us_page',
		'type'=> 'text'
	));

	$wp_customize->add_setting('rj_mojo_contact_us_title_desc',array(
		'default'=> 'Reach out, and let\'s create a universe of possibilities together!',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('rj_mojo_contact_us_title_desc',array(
		'label'	=> esc_html__('Heading Description','rj-mojo'),
		'section'=> 'rj_mojo_contact_us_page',
		'type'=> 'textarea'
	));

	$wp_customize->add_setting('rj_mojo_contact_us_form_title',array(
		'default'=> 'Let’s connect constellations',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('rj_mojo_contact_us_form_title',array(
		'label'	=> esc_html__('Form Heading','rj-mojo'),
		'section'=> 'rj_mojo_contact_us_page',
		'type'=> 'text'
	));

	$wp_customize->add_setting('rj_mojo_contact_us_form_description',array(
		'default'=> 'Let\'s align our constellations! Reach out and let the magic of collaboration illuminate our skies.',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('rj_mojo_contact_us_form_description',array(
		'label'	=> esc_html__('Form Description','rj-mojo'),
		'section'=> 'rj_mojo_contact_us_page',
		'type'=> 'textarea'
	));

	$wp_customize->add_setting('rj_mojo_contact_us_form_shortcode',array(
		'default'=> '[wpforms id="60"]',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('rj_mojo_contact_us_form_shortcode',array(
		'label'	=> esc_html__('Form Shortcode','rj-mojo'),
		'section'=> 'rj_mojo_contact_us_page',
		'type'=> 'text'
	));

	$wp_customize->add_setting('rj_mojo_contact_us_image',array(
			'default'   => '',
			'sanitize_callback' => 'esc_url_raw',
		));
	$wp_customize->add_control(new WP_Customize_Image_Control($wp_customize,'rj_mojo_contact_us_image',array(
			'label' => __('Image ','rj-mojo'),
			'section' => 'rj_mojo_contact_us_page',
			'settings' => 'rj_mojo_contact_us_image'
	)));

	$wp_customize->add_setting('rj_mojo_contact_us_below_description',array(
		'default'=> 'If you were looking for one of world’s most read military aviation blogger, you have just found him. My bio can be read in the About page.',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('rj_mojo_contact_us_below_description',array(
		'label'	=> esc_html__('Text Below Form','rj-mojo'),
		'section'=> 'rj_mojo_contact_us_page',
		'type'=> 'text'
	));
	$wp_customize->add_setting('rj_mojo_contact_us_about_page_link',array(
		'default'=> '#',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('rj_mojo_contact_us_about_page_link',array(
		'label'	=> esc_html__('About page link','rj-mojo'),
		'section'=> 'rj_mojo_contact_us_page',
		'type'=> 'text'
	));
	$wp_customize->add_setting('rj_mojo_contact_us_about_page_text',array(
		'default'=> 'About page',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('rj_mojo_contact_us_about_page_text',array(
		'label'	=> esc_html__('About us page text','rj-mojo'),
		'section'=> 'rj_mojo_contact_us_page',
		'type'=> 'text'
	));
}
add_action( 'customize_register', 'rj_mojo_customizer_register' );
