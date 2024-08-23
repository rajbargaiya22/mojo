<?php
/**
 * RJ Bookmarks functions and definitions.
 *
 *
 * @package rj-bookmarks
 */

 if ( ! defined('rj_bookmark_path')) {
   define('rj_bookmark_path', get_template_directory());
 }

 if ( ! defined('rj_bookmark_theme_path')) {
   define('rj_bookmark_theme_path', get_template_directory_uri());
 }

function rj_bookmarks_enqueue_scripts() {
	wp_enqueue_style( 'rj-style', get_stylesheet_uri());
    wp_enqueue_style( 'rj-slick-slider', get_template_directory_uri(). '/assets/css/slick-theme.css' );
	wp_enqueue_style( 'rj-owl-carousel', get_template_directory_uri(). '/assets/css/owl.carousel.css' );
	// wp_enqueue_style( 'rj-fontawesome', get_template_directory_uri() . '/assets/css/fontawesome.min.css');
	// wp_enqueue_style( 'rj-fontawesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css');
	wp_enqueue_style( 'rj-poppins-font', 'https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap' );
	wp_enqueue_style( 'rj-bootstrap', get_template_directory_uri(). '/assets/css/bootstrap.css' );
	wp_enqueue_style( 'rj-slick-slider', get_template_directory_uri(). '/assets/css/slick-theme.css' );
	wp_enqueue_style( 'rj-owl-carousel', get_template_directory_uri(). '/assets/css/owl.carousel.css' );


    wp_enqueue_script('rj-owl-carousel-js', get_template_directory_uri(). '/assets/js/owl.carousel.js',  array('jquery'), false, false);
    wp_enqueue_script('rj-nsc-custom.js', get_template_directory_uri(). '/assets/js/nsc-custom.js', false, false);
	wp_enqueue_script('rj-bootstrap-js', get_template_directory_uri(). '/assets/js/bootstrap.js', false, false);
	wp_enqueue_script('rj-fontawesome-js', get_template_directory_uri(). '/assets/js/fontawesome-all-min.js', false, false);
	wp_enqueue_script('rj-jquery-js', get_template_directory_uri(). '/assets/js/jquery-min.js', false, false);
	// wp_enqueue_script('rj-slick-slider-js', get_template_directory_uri(). '/assets/js/slick.min.js', array('jquery'), false, false);
    wp_enqueue_script( 'slick-js', get_template_directory_uri(). '/assets/js/slick.js', array('jquery'), null, true);

	// wp_enqueue_script('jquery');


	 wp_localize_script('rj-custom-js', 'ajax_search_params', array(
			 'ajaxurl' => admin_url('admin-ajax.php'),
			 'nonce' => wp_create_nonce('ajax-search-nonce'),
	 ));

	 if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
	 	wp_enqueue_script( 'comment-reply' );
 	}

}
add_action( 'wp_enqueue_scripts', 'rj_bookmarks_enqueue_scripts' );

if ( !function_exists( 'rj_bookmarks_theme_setup' )) {
  function rj_bookmarks_theme_setup(){

		load_theme_textdomain('rj-bookmarks', get_template_directory() . "/languages" );

    add_theme_support( 'automatic-feed-links' );
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support('html5', array( 'navigation-widgets', 'search-form', 'gallery', 'caption', 'style', 'script', 'comment-list', 'search-form', 'comment-form', ) );
    // add_theme_support( 'post-formats', array( 'gallery', 'image', 'link', 'quote', 'video', 'audio', 'status', 'aside', ) );

    add_theme_support(
  				'custom-logo',
  				array(
  					'width'       => 180,
  					'height'      => 60,
  					'flex-width'  => true,
  					'flex-height' => true,
  				)
  			);

    add_theme_support( 'customize-selective-refresh-widgets' );
		add_theme_support( 'wp-block-styles' );
		add_theme_support( 'responsive-embeds' );
		add_theme_support( 'custom-header' );
		add_theme_support( 'custom-background', array(
		'default-color' => '#111827'
		) );
		add_theme_support( 'align-wide' );

		add_editor_style( array( 'css/rj-editor-style.css' ) );

	}
}
add_action( 'after_setup_theme', 'rj_bookmarks_theme_setup' );

require get_template_directory() . '/inc/widgets/widgets-area.php';
require get_template_directory() . '/inc/widgets/posts-tags.php';
require get_template_directory() . '/inc/widgets/social-icons.php';
require get_template_directory() . '/inc/widgets/posts-categories.php';
require get_template_directory() . '/inc/widgets/rj-popular-bookmarks.php';
require get_template_directory() . '/inc/widgets/rj-top-articles.php';
require get_template_directory() . '/inc/widgets/tab.php';
require get_template_directory() . '/inc/widgets/latest-posts.php';
require get_template_directory() . '/inc/posts-category-image.php';
require get_template_directory() . '/inc/posts-rj-exhibits-image.php';
require get_template_directory() . '/inc/rj-block-patterns/rj-block-pattern-register.php';

// require get_template_directory() . '/theme-wizard/config.php';

//  customizer
function rj_bookmarks_customizer_sanitize_choices( $input, $setting ) {
    global $wp_customize;
    $control = $wp_customize->get_control( $setting->id );
    if ( array_key_exists( $input, $control->choices ) ) {
        return $input;
    } else {
        return $setting->default;
    }
}

if ( ! function_exists( 'rj_bookmarks_toggle_sanitization' ) ) {
	function rj_bookmarks_toggle_sanitization( $input ) {
		if ( true === $input ) {
			return 1;
		} else {
			return 0;
		}
	}
}

require get_template_directory() . '/inc/rj-customizer/rj-customizer.php';

//  create the meta box
function rj_bookmarks_custom_meta_box() {
    add_meta_box(
        'rj-website-details',
        'Website Details',
        'rj_bookmarks_website_details_meta_box_html',
        'post',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'rj_bookmarks_custom_meta_box');

function rj_bookmarks_website_details_meta_box_html($post) {
	$website_details = array(
				'Website Url' => 'rj_website_url',
				'Contact No' => 'rj_website_contact',
				'Email' => 'rj_website_mail',
				'Address' => 'rj_website_address',
				'Country' => 'rj_website_country',
	); ?>
	<table>
		<?php foreach ($website_details as $label => $details) { ?>
			<tr>
				<th style="text-align: start">
					<label for="<?php echo esc_attr($details); ?>"><?php echo esc_html($label); ?></label>
				</th>
				<td>
					:-
				</td>
				<td>
					<input type="text" id="<?php echo esc_attr($details); ?>" name="<?php echo esc_attr($details); ?>" value="<?php echo esc_attr(get_post_meta($post->ID, $details, true)); ?>">
				</td>
			</tr>
		<?php } ?>
	</table>
  <?php }

// Function to save meta box data
function rj_bookmarks_save_custom_meta_box($post_id) {

	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
			return;
	}
	if (!current_user_can('edit_post', $post_id)) {
			return;
	}

	$website_details = array(
			'rj_website_url',
			'rj_website_contact',
			'rj_website_mail',
			'rj_website_address',
			'rj_website_country'
	);

	foreach ($website_details as $key => $value) {
		if (isset($_POST[$value])) {
        update_post_meta($post_id, $value, sanitize_text_field($_POST[$value]));
    }
	}
}
add_action('save_post', 'rj_bookmarks_save_custom_meta_box');

// Function to add nonce to the form
function rj_bookmarks_add_custom_meta_box_nonce() {
    wp_nonce_field('custom_meta_box_nonce', 'custom_meta_box_nonce');
}
add_action('post_submitbox_misc_actions', 'rj_bookmarks_add_custom_meta_box_nonce');

//  submit the post
function rj_bookmarks_handle_frontend_post_submission() {
    if (isset($_POST['submit-post']) && wp_verify_nonce($_POST['frontend_post_nonce'], 'frontend_post')) {
        // Check terms and conditions
        if (!isset($_POST['terms-conditions'])) {
            wp_die('You must agree to the terms and conditions.');
        }

        $current_user = wp_get_current_user();
        $post_data = array(
            'post_title'    => sanitize_text_field($_POST['post-title']),
            'post_content'  => sanitize_textarea_field($_POST['post-content']),
            'post_status'   => 'pending',
            'post_author'   => $current_user->ID,
            'post_category' => array(intval($_POST['post-category'])),
        );
        $post_id = wp_insert_post($post_data);

        if (is_wp_error($post_id)) {
            wp_die('Error creating post: ' . $post_id->get_error_message());
        }

        if ($post_id) {
            // Handle tags
            if (!empty($_POST['post-tags'])) {
                $tags = explode(',', sanitize_text_field($_POST['post-tags']));
                $tag_ids = array();

                foreach ($tags as $tag) {
                    $tag = trim($tag);
                    if (!empty($tag)) {
                        $existing_tag = get_term_by('name', $tag, 'post_tag');
                        if ($existing_tag) {
                            $tag_ids[] = $existing_tag->term_id;
                        } else {
                            $new_tag = wp_insert_term($tag, 'post_tag');
                            if (!is_wp_error($new_tag)) {
                                $tag_ids[] = $new_tag['term_id'];
                            }
                        }
                    }
                }

                wp_set_post_tags($post_id, $tag_ids, false);
            }

            // Add post meta
            $meta_fields = [
                'rj_website_country',
                'rj_website_mail',
                'rj_website_contact',
                'rj_website_address'
            ];

            foreach ($meta_fields as $field) {
                if (!empty($_POST[$field])) {
                    add_post_meta($post_id, $field, sanitize_text_field($_POST[$field]), true);
                }
            }

            // Redirect or display a success message
            wp_redirect(home_url());
            exit;
        }
    }
}
add_action('template_redirect', 'rj_bookmarks_handle_frontend_post_submission');

// Restrict posts list in admin to only show the current user's posts
function rj_bookmarks_restrict_posts_to_current_user($query) {
    if (is_admin() && $query->is_main_query() && !current_user_can('edit_others_posts')) {
        global $pagenow;
        if ($pagenow === 'edit.php') {
            $query->set('author', get_current_user_id());
        }
    }
}
add_action('pre_get_posts', 'rj_bookmarks_restrict_posts_to_current_user');

// Remove menu items for subscribers
function rj_bookmarks_remove_menus_for_subscribers() {
    if (current_user_can('subscriber')) {
        // Remove everything except the "Posts" menu
        remove_menu_page('index.php');
        remove_menu_page('upload.php');
        remove_menu_page('edit-comments.php');
        remove_menu_page('themes.php');
        remove_menu_page('plugins.php');
        remove_menu_page('users.php');
        remove_menu_page('tools.php');
        remove_menu_page('profile.php');
        remove_menu_page('options-general.php');
        remove_menu_page('edit.php?post_type=page');
        remove_menu_page('edit.php?post_type=rj-top-sites');
    }
}
add_action('admin_menu', 'rj_bookmarks_remove_menus_for_subscribers');

// Remove the admin bar items for subscribers
function rj_bookmarks_remove_admin_bar_items_for_subscribers($wp_admin_bar) {
    if (current_user_can('subscriber')) {
        // Remove unnecessary items from the admin bar
        $wp_admin_bar->remove_node('wp-logo');
        $wp_admin_bar->remove_node('site-name');
        $wp_admin_bar->remove_node('updates');
        $wp_admin_bar->remove_node('comments');
        $wp_admin_bar->remove_node('new-content');
        $wp_admin_bar->remove_node('my-account');
    }
}
add_action('admin_bar_menu', 'rj_bookmarks_remove_admin_bar_items_for_subscribers', 999);


function create_custom_table() {
    global $wpdb;
    
    $table_name = $wpdb->prefix . 'visit_bookings'; // Name of the table
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        name varchar(255) NOT NULL,
        email varchar(255) NOT NULL,
        contact_no varchar(20) NOT NULL,
        message text NOT NULL,
        booking_date date NOT NULL,
        no_of_children int(11) NOT NULL,
        no_of_elders int(11) NOT NULL,
        PRIMARY KEY  (id)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}

add_action('init', 'create_custom_table'); 

function handle_booking_form() {
    if (isset($_POST['submit_booking'])) {
        global $wpdb;
        
        // Table name
        $table_name = $wpdb->prefix . 'visit_bookings';
        
        // Sanitize input
        $name = sanitize_text_field($_POST['name']);
        $email = sanitize_email($_POST['email']);
        $contact_no = sanitize_text_field($_POST['contact_no']);
        $message = sanitize_textarea_field($_POST['message']);
        $booking_date = sanitize_text_field($_POST['booking_date']);
        $no_of_children = intval($_POST['no_of_children']);
        $no_of_elders = intval($_POST['no_of_elders']);

        // Insert data into the database
        $wpdb->insert(
            $table_name,
            array(
                'name' => $name,
                'email' => $email,
                'contact_no' => $contact_no,
                'message' => $message,
                'booking_date' => $booking_date,
                'no_of_children' => $no_of_children,
                'no_of_elders' => $no_of_elders
            )
        );

        // Email details
        $to = $email; // Send confirmation to the submitter
        $subject = 'Booking Confirmation';
        $body = "Thank you for booking with us!\n\nHere are the details of your booking:\n\n".
                "Name: $name\n".
                "Email: $email\n".
                "Contact Number: $contact_no\n".
                "Message: $message\n".
                "Booking Date: $booking_date\n".
                "Number of Children: $no_of_children\n".
                "Number of Elders: $no_of_elders\n\n".
                "We will get back to you soon.";
        $headers = array('Content-Type: text/plain; charset=UTF-8');

        // Send email
        wp_mail($to, $subject, $body, $headers);

        // Optionally, send an email to the admin or another recipient
        $admin_email = get_option('admin_email'); // Admin email address
        $admin_subject = 'New Booking Request';
        $admin_body = "A new booking request has been submitted:\n\n".
                      "Name: $name\n".
                      "Email: $email\n".
                      "Contact Number: $contact_no\n".
                      "Message: $message\n".
                      "Booking Date: $booking_date\n".
                      "Number of Children: $no_of_children\n".
                      "Number of Elders: $no_of_elders";
        
        wp_mail('kgorle@dhaninfo.biz', $admin_subject, $admin_body, $headers);

        wp_redirect(home_url());
        exit;

    }
}

add_action('wp', 'handle_booking_form');

register_nav_menus( array(
    'primary' => __( 'Primary Menu', 'rj-bookmarks' ),
) );


//  register post type Exhibits
function rj_bookmarks_register_top_articles_post_type() {
    register_post_type('rj-exhibit',
   	 array(
   		 'labels'  	=> array(
   			 'name'      	=> __('Exhibits', 'rj-bookmarks'),
   			 'singular_name' => __('Exhibit', 'rj-bookmarks'),
   		 ),
   			 'public'  	=> true,
   			 'has_archive' => true,
   			 'capability_type' => 'post',
   			 'supports' => array( 'title', 'editor', 'thumbnail'),
   	 )
    );
}
add_action('init', 'rj_bookmarks_register_top_articles_post_type');

// Register custom taxonomy 'exhibit_category' for the custom post type
function create_exhibit_category_taxonomy() {
    register_taxonomy(
        'exhibit_category',
        'rj-exhibit',
        array(
            'label' => __('Categories'),
            'rewrite' => array('slug' => 'exhibit-category'),
            'hierarchical' => true,
        )
    );
}
add_action('init', 'create_exhibit_category_taxonomy');

