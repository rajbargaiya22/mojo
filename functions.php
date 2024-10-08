<?php
/**
 * RJ mojo functions and definitions.
 *
 *
 * @package rj-mojo
 */

 if ( ! defined('rj_mojo_path')) {
   define('rj_mojo_path', get_template_directory());
 }

 if ( ! defined('rj_mojo_theme_path')) {
   define('rj_mojo_theme_path', get_template_directory_uri());
 }

function rj_mojo_enqueue_scripts() {
	wp_enqueue_style( 'rj-style', get_stylesheet_uri());
	wp_enqueue_style( 'rj-poppins-font', 'https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap' );
	wp_enqueue_style( 'rj-bootstrap', get_template_directory_uri(). '/assets/css/bootstrap.css' );
	wp_enqueue_style( 'rj-slick-slider', get_template_directory_uri(). '/assets/css/slick-theme.css' );
	wp_enqueue_style( 'rj-owl-carousel', get_template_directory_uri(). '/assets/css/owl.carousel.css' );

	wp_enqueue_script('rj-bootstrap-js', get_template_directory_uri(). '/assets/js/bootstrap.js', false, false);
	wp_enqueue_script('rj-fontawesome-js', get_template_directory_uri(). '/assets/js/fontawesome-all-min.js', false, false);
	wp_enqueue_script('rj-jquery-js', get_template_directory_uri(). '/assets/js/jquery-min.js', false, false);
	wp_enqueue_script('rj-slick-slider-js', get_template_directory_uri(). '/assets/js/slick.min.js', array('jquery'), false, false);
	wp_enqueue_script('rj-owl-carousel-js', get_template_directory_uri(). '/assets/js/owl.carousel.js',  array('jquery'), false, false);
    wp_enqueue_script('rj-custom.js', get_template_directory_uri(). '/assets/js/rj-custom.js', false, false);
    wp_enqueue_script( 'wow-jquery', get_template_directory_uri() . '/assets/js/wow.js', array('jquery'),'' ,true );

	// wp_enqueue_script('jquery');


	 wp_localize_script('rj-custom-js', 'ajax_search_params', array(
			 'ajaxurl' => admin_url('admin-ajax.php'),
			 'nonce' => wp_create_nonce('ajax-search-nonce'),
	 ));

	 if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
	 	wp_enqueue_script( 'comment-reply' );
 	}

}
add_action( 'wp_enqueue_scripts', 'rj_mojo_enqueue_scripts' );

function rj_mojoload_admin_style() {
    wp_enqueue_style('rj-contact-list', get_template_directory_uri() . '/admin/assets/rj-contact-list.css');
    wp_enqueue_script('custom-time-slot-js', get_template_directory_uri() . '/admin/assets/rj-admin.js', array('jquery'), filemtime(get_template_directory() . '/admin/assets/rj-admin.js'), true);
}
add_action('admin_enqueue_scripts', 'rj_mojoload_admin_style');



if ( !function_exists( 'rj_mojo_theme_setup' )) {
  function rj_mojo_theme_setup(){

	load_theme_textdomain('rj-mojo', get_template_directory() . "/languages" );

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

		register_nav_menus( array(
            'primary' => __( 'Primary Menu', 'rj-mojo' ),
        ) );
        
	}
}
add_action( 'after_setup_theme', 'rj_mojo_theme_setup' );

require get_template_directory() . '/inc/widgets/widgets-area.php';
require get_template_directory() . '/inc/widgets/social-icons.php';
require get_template_directory() . '/inc/widgets/posts-categories.php';
require get_template_directory() . '/inc/rj-block-patterns/rj-block-pattern-register.php';
require get_template_directory() . '/admin/rj-contact-list.php';
require get_template_directory() . '/inc/posts-rj-exhibits-image.php';

// require get_template_directory() . '/theme-wizard/config.php';

//  customizer
function rj_mojo_customizer_sanitize_choices( $input, $setting ) {
    global $wp_customize;
    $control = $wp_customize->get_control( $setting->id );
    if ( array_key_exists( $input, $control->choices ) ) {
        return $input;
    } else {
        return $setting->default;
    }
}

if ( ! function_exists( 'rj_mojo_toggle_sanitization' ) ) {
	function rj_mojo_toggle_sanitization( $input ) {
		if ( true === $input ) {
			return 1;
		} else {
			return 0;
		}
	}
}

require get_template_directory() . '/inc/rj-customizer/rj-customizer.php';

// Function to add nonce to the form
function rj_mojo_add_custom_meta_box_nonce() {
    wp_nonce_field('custom_meta_box_nonce', 'custom_meta_box_nonce');
}
add_action('post_submitbox_misc_actions', 'rj_mojo_add_custom_meta_box_nonce');

//  register post type Exhibits
function rj_mojo_register_top_articles_post_type() {
    register_post_type('rj-exhibit',
   	 array(
   		 'labels'  	=> array(
   			 'name'      	=> __('Exhibits', 'rj-mojo'),
   			 'singular_name' => __('Exhibit', 'rj-mojo'),
   		 ),
   			 'public'  	=> true,
   			 'has_archive' => true,
   			 'capability_type' => 'post',
   			 'supports' => array( 'title', 'editor', 'thumbnail'),
   	 )
    );
}
add_action('init', 'rj_mojo_register_top_articles_post_type');

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

<<<<<<< HEAD
?>
=======
// dashboard booking table
function rj_mojo_create_booking_custom_table() {
    global $wpdb;
    
    $table_name = $wpdb->prefix . 'visit_bookings'; // Name of the table
    $charset_collate = $wpdb->get_charset_collate();

    // Corrected SQL statement for 'visit_bookings' table
    $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        name varchar(255) NOT NULL,
        email varchar(255) NOT NULL,
        contact_no varchar(20) NOT NULL,
        booking_date date NOT NULL,
        time_slot varchar(255) NOT NULL,
        no_of_children int(11) NOT NULL,
        no_of_elders int(11) NOT NULL,
        total_cost varchar(255) NOT NULL,
        PRIMARY KEY  (id)
    ) $charset_collate;";
}

add_action('init', 'rj_mojo_create_booking_custom_table');

// Booking form
function rj_mojo_booking_form() {

    if (isset($_POST['submit_booking']) && isset($_POST['booking_form_nonce'])) {
        if (!wp_verify_nonce($_POST['booking_form_nonce'], 'submit_booking_form')) {
            wp_die('Security check failed');
        }

        global $wpdb;
        
        // Table name
        $table_name = $wpdb->prefix . 'visit_bookings';
        
        // Sanitize input
        $name = sanitize_text_field($_POST['name']);
        $email = sanitize_email($_POST['email']);
        $contact_no = sanitize_text_field($_POST['contact_no']);
        $booking_date = sanitize_text_field($_POST['booking_date']);
        $time_slot = sanitize_textarea_field($_POST['time_slot']);
        $no_of_children = intval($_POST['no_of_children']);
        $no_of_elders = intval($_POST['no_of_elders']);
        $total_cost = sanitize_textarea_field($_POST['total_cost']);

        // Insert data into the database
        $wpdb->insert(
            $table_name,
            array(
                'name' => $name,
                'email' => $email,
                'contact_no' => $contact_no,
                'time_slot' => $time_slot,
                'booking_date' => $booking_date,
                'no_of_children' => $no_of_children,
                'no_of_elders' => $no_of_elders,
                'total_cost' => $total_cost,
            )
        );

        // Email details
        $to = $email; // Send confirmation to the submitter
        $subject = 'Booking Confirmation';
        $body = "Thank you for booking with us!\n\nHere are the details of your booking:\n\n".
                "Name: $name\n".
                "Email: $email\n".
                "Contact Number: $contact_no\n".
                "Time Slot: $time_slot\n".
                "Booking Date: $booking_date\n".
                "Number of Children: $no_of_children\n".
                "Number of Elders: $no_of_elders\n\n".
                "Total Cost: $total_cost\n".
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
                      "Time Slot: $time_slot\n".
                      "Booking Date: $booking_date\n".
                      "Number of Children: $no_of_children\n".
                      "Number of Elders: $no_of_elders";
                      "Total Cost: $total_cost\n".
        
        wp_mail('kgorle@dhaninfo.biz', $admin_subject, $admin_body, $headers);
        // wp_mail('nehall.goyal@gmail.com', $admin_subject, $admin_body, $headers);
        //wp_mail('mojo.nagpur@gmail.com', $admin_subject, $admin_body, $headers);


        $order = wc_create_order();
        $product_id = 70;
        $order->add_product(wc_get_product($product_id), 2); 

        $name_parts = explode(' ', $name);
        $first_name = isset($name_parts[0]) ? $name_parts[0] : '';
        $last_name = isset($name_parts[1]) ? $name_parts[1] : '';

	// Set the billing address.
	$billing_address = array(
		'first_name' => $first_name,
		'last_name'  => $last_name,
		'company'    => '',
		'email'      => $email,
		'phone'      => $contact_no,
		'address_1'  => '',
		'address_2'  => '',
		'city'       => 'Nagpur',
		'state'      => 'MH',
		'postcode'   => '',
		'country'    => 'IN',
	);

	$order->set_address($billing_address, 'billing');


	$shipping_address = array(
		'first_name' => $first_name,
		'last_name'  => $last_name,
		'company'    => '',
		'email'      => $email,
		'phone'      => $contact_no,
		'address_1'  => '',
		'address_2'  => '',
		'city'       => 'Nagpur',
		'state'      => 'MH',
		'postcode'   => '',
		'country'    => 'IN',
	);
	
	$order->set_address($shipping_address, 'shipping');
	$order->calculate_totals();
    $order->set_total($total_cost);
    $order->update_status('pending', 'Order created for booking visit');
    $order->save();
    wc_reduce_stock_levels($order->get_id());
    WC()->mailer()->emails['WC_Email_Customer_Processing_Order']->trigger($order->get_id());
    WC()->mailer()->emails['WC_Email_New_Order']->trigger($order->get_id());

        // wp_redirect(home_url());
        exit;

    }
}
add_action('wp', 'rj_mojo_booking_form');









// submit table
function rj_mojo_create_contact_us_table() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'contact_us';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        name varchar(255) NOT NULL,
        email varchar(255) NOT NULL,
        mobile varchar(20) NOT NULL,
        message text NOT NULL,
        PRIMARY KEY  (id)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}
add_action('init', 'rj_mojo_create_contact_us_table');

function rj_mojo_contact_us_form() {

    if (isset($_POST['submit_booking']) && isset($_POST['contact_form_nonce'])) {
        if (!wp_verify_nonce($_POST['contact_form_nonce'], 'submit_contact_form')) {
            wp_die('Security check failed');
        }


        

        global $wpdb;
        $table_name = $wpdb->prefix . 'contact_us';
        // Sanitize input
        $name = sanitize_text_field($_POST['name']);
        $email = sanitize_email($_POST['email']);
        $mobile = sanitize_text_field($_POST['mobile']);
        $message = sanitize_text_field($_POST['message']);

        // Insert data into the database
        $wpdb->insert(
            $table_name,
            array(
                'name' => $name,
                'email' => $email,
                'mobile' => $mobile,
                'message' => $message,
            ),
            array(
                '%s', // 'name' field
                '%s', // 'email' field
                '%s', // 'mobile' field
                '%s'  // 'message' field
            )
        );

        // Email details
        $to = $email; // Send confirmation to the submitter
        $subject = 'Submit Confirmation';
        $body = "Thank you for booking with us!\n\nHere are the details of your booking:\n\n".
                "Name: $name\n".
                "Email: $email\n".
                "Mobile: $mobile\n".
                "Message: $message\n".
                "We will get back to you soon.";
        

        // Send email
        // $admin_email = get_option('admin_email'); 
        $admin_subject = 'Contact Us Inquiry';
        $admin_body = "A new booking request has been submitted:\n\n".
        "Name: $name\n".
        "Email: $email\n".
        "Mobile: $mobile\n".
        "Message: $message\n";

        $headers = array('Content-Type: text/plain; charset=UTF-8');
        wp_mail($to, $subject, $body, $headers);
        wp_mail("kgorle@dhaninfo.biz", $admin_subject, $admin_body, $headers);
        // wp_mail('nehall.goyal@gmail.com', $admin_subject, $admin_body, $headers);
        // wp_mail('mojo.nagpur@gmail.com', $admin_subject, $admin_body, $headers);

        wp_redirect(home_url());
        exit;

    }
}
add_action('init', 'rj_mojo_contact_us_form');

// exhibits metabox
function my_custom_meta_box() {
    add_meta_box(
        'my_image_selector',         
        'Select Multiple Images',    
        'my_image_selector_callback',
        'rj-exhibit',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'my_custom_meta_box');


function my_image_selector_callback($post) {
    // Retrieve existing images
    $images = get_post_meta($post->ID, 'my_selected_images', true);
    wp_nonce_field('my_image_selector_nonce', 'my_image_selector_nonce_field');
    ?>

    <div id="my-image-uploader">
        <button type="button" class="button" id="upload_images_button">Select Images</button>
        <ul id="image-gallery">
            <?php
            if ($images) {
                foreach ($images as $image_id) {
                    echo '<li>
                            <img src="' . esc_url(wp_get_attachment_url($image_id)) . '" />
                            <input type="hidden" name="my_selected_images[]" value="' . esc_attr($image_id) . '" />
                            <button type="button" class="remove-image-button">&times;</button>
                          </li>';
                }
            }
            ?>
        </ul>
    </div>

    <script>
    jQuery(document).ready(function($) {
        var frame;

        // Handle the image selection
        $('#upload_images_button').on('click', function(e) {
            e.preventDefault();
            if (frame) {
                frame.open();
                return;
            }
            frame = wp.media({
                title: 'Select or Upload Images',
                button: {
                    text: 'Use these images'
                },
                multiple: true // Set to true to allow multiple files to be selected
            });

            frame.on('select', function() {
                var selection = frame.state().get('selection');
                selection.map(function(attachment) {
                    attachment = attachment.toJSON();
                    $('#image-gallery').append(
                        '<li style="display: inline-block; margin: 10px; position: relative;">' +
                        '<img src="' + attachment.url + '" style="max-width:100px;" />' +
                        '<input type="hidden" name="my_selected_images[]" value="' + attachment.id + '" />' +
                        '<button type="button" class="remove-image-button" style="position: absolute; top: 0; right: 0; background: red; color: white; border: none; cursor: pointer;">&times;</button>' +
                        '</li>'
                    );
                });
            });

            frame.open();
        });

        // Handle the image removal
        $('#image-gallery').on('click', '.remove-image-button', function() {
            $(this).closest('li').remove(); // Remove the image item from the DOM
        });
    });
    </script>

    <?php
}


function my_save_image_selector($post_id) {
    // Check for nonce security
    if (!isset($_POST['my_image_selector_nonce_field']) || !wp_verify_nonce($_POST['my_image_selector_nonce_field'], 'my_image_selector_nonce')) {
        return;
    }
    // Save images
    if (isset($_POST['my_selected_images'])) {
        update_post_meta($post_id, 'my_selected_images', array_map('intval', $_POST['my_selected_images']));
    } else {
        delete_post_meta($post_id, 'my_selected_images');
    }
}
add_action('save_post', 'my_save_image_selector');





// function redirect_to_checkout_from_product_page() {
//     if (is_product()) {
//         global $product;
//         if ($product) {
//             $cart_url = wc_get_cart_url();
//             $checkout_url = wc_get_checkout_url();
//             // $product_id = $product->get_id();
//             $product_id = 32;

//             // Add product to cart
//             WC()->cart->add_to_cart($product_id);

//             // Redirect to checkout
//             wp_redirect($checkout_url);
//             exit;
//         }
//     }
// }




>>>>>>> e7bed4a95b39b482b4baaafecafe71073ad2a34a
