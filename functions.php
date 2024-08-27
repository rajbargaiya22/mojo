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
	// wp_enqueue_style( 'rj-fontawesome', get_template_directory_uri() . '/assets/css/fontawesome.min.css');
	// wp_enqueue_style( 'rj-fontawesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css');
	wp_enqueue_style( 'rj-poppins-font', 'https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap' );
	wp_enqueue_style( 'rj-bootstrap', get_template_directory_uri(). '/assets/css/bootstrap.css' );
	wp_enqueue_style( 'rj-slick-slider', get_template_directory_uri(). '/assets/css/slick-theme.css' );
	wp_enqueue_style( 'rj-owl-carousel', get_template_directory_uri(). '/assets/css/owl.carousel.css' );


	wp_enqueue_script('rj-bootstrap-js', get_template_directory_uri(). '/assets/js/bootstrap.js', false, false);
	wp_enqueue_script('rj-fontawesome-js', get_template_directory_uri(). '/assets/js/fontawesome-all-min.js', false, false);
	wp_enqueue_script('rj-jquery-js', get_template_directory_uri(). '/assets/js/jquery-min.js', false, false);
	wp_enqueue_script('rj-slick-slider-js', get_template_directory_uri(). '/assets/js/slick.min.js', array('jquery'), false, false);
	wp_enqueue_script('rj-owl-carousel-js', get_template_directory_uri(). '/assets/js/owl.carousel.js',  array('jquery'), false, false);
    wp_enqueue_script('rj-nsc-custom.js', get_template_directory_uri(). '/assets/js/nsc-custom.js', false, false);
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
add_action( 'wp_enqueue_scripts', 'rj_bookmarks_enqueue_scripts' );

// dashboard css enque
function load_admin_style() {
    wp_enqueue_style('rj-contact-list', get_template_directory_uri() . '/admin/assets/rj-contact-list.css');

    wp_enqueue_script('custom-time-slot-js', get_template_directory_uri() . '/admin/assets/rj-admin.js', array('jquery'), filemtime(get_template_directory() . '/admin/assets/rj-admin.js'), true);
    
   
}
add_action('admin_enqueue_scripts', 'load_admin_style');








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

		// register_nav_menus( array(
		// 'primary' => __( 'Primary Menu', 'rj-bookmarks' ),
		// 'topbar' => __( "Topbar Menu", 'rj-bookmarks' ),
		// ) );

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
require get_template_directory() . '/inc/posts-location-image.php';
require get_template_directory() . '/inc/rj-block-patterns/rj-block-pattern-register.php';
require get_template_directory() . '/admin/rj-contact-list.php';

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

//  ajax search
function ajax_search() {
    check_ajax_referer('ajax-search-nonce', 'nonce');

    $query = sanitize_text_field($_POST['query']);

    $args = array(
        'post_type' => 'post',
        's' => $query,
    );

    $post_suggestions  = new WP_Query($args);

		$category_args = array(
        'taxonomy' => 'category',
        'name__like' => $query,
    );

    $category_suggestions = get_categories($category_args);

    ob_start();

	if ($post_suggestions->have_posts() || !empty($category_suggestions)) {

    if ($post_suggestions ->have_posts()) {
			echo "<h2 class='search-head'>" . esc_html__('Posts', 'rj-bookmarks') . "</h2>";
			echo "<ul>";
        while ($post_suggestions ->have_posts()) {
            $post_suggestions ->the_post();
            echo '<li><i class="fa-regular fa-clock"></i><a href="' . esc_url(get_permalink()) . '" title="'. get_the_title() .'">' . get_the_title() . '</a></li>';
        }
			echo "<ul>";
        wp_reset_postdata();
    }

		if (!empty($category_suggestions)) {
			echo "<h2 class='search-head mt-3'>" . esc_html__('Category', 'rj-bookmarks'	) . "</h2>";
			echo "<ul>";
			foreach ($category_suggestions as $category) {
				echo '<li><i class="fa-solid fa-hashtag"></i><a href="' . esc_url(get_category_link($category->term_id)) . '">' . esc_html($category->name) . '</a></li>';
			}
			echo "</ul>";
		}
	}else {
		echo '<p>No posts found.</p>';
	}

    $output = ob_get_clean();

    wp_send_json($output);
}

add_action('wp_ajax_ajax_search', 'ajax_search');
add_action('wp_ajax_nopriv_ajax_search', 'ajax_search');


function rj_bookmarks_breadcrumb() {
    $separator = ' <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M470.6 278.6c12.5-12.5 12.5-32.8 0-45.3l-160-160c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L402.7 256 265.4 393.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0l160-160zm-352 160l160-160c12.5-12.5 12.5-32.8 0-45.3l-160-160c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L210.7 256 73.4 393.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0z"/></svg> ';
    $home_title = '
		<svg viewBox="0 0 576 512"><path d="M543.8 287.6c17 0 32-14 32-32.1c1-9-3-17-11-24L512 185V64c0-17.7-14.3-32-32-32H448c-17.7 0-32 14.3-32 32v36.7L309.5 7c-6-5-14-7-21-7s-15 1-22 8L10 231.5c-7 7-10 15-10 24c0 18 14 32.1 32 32.1h32v69.7c-.1 .9-.1 1.8-.1 2.8V472c0 22.1 17.9 40 40 40h16c1.2 0 2.4-.1 3.6-.2c1.5 .1 3 .2 4.5 .2H160h24c22.1 0 40-17.9 40-40V448 384c0-17.7 14.3-32 32-32h64c17.7 0 32 14.3 32 32v64 24c0 22.1 17.9 40 40 40h24 32.5c1.4 0 2.8 0 4.2-.1c1.1 .1 2.2 .1 3.3 .1h16c22.1 0 40-17.9 40-40V455.8c.3-2.6 .5-5.3 .5-8.1l-.7-160.2h32z"/></svg>
		Home';
    echo '<nav class="rj-breadcrumb">';
    echo '<a href="' . get_home_url() . '">' . $home_title . '</a>' . $separator;
    if (is_category() || is_single()) {
			$post_categories = get_the_category();
			if ( ! empty( $post_categories ) ) {
			    $first_category = $post_categories[0];
			    echo "<span>" . esc_html( $first_category->name ) . "</span>";
			}
        if (is_single()) {
            echo $separator;
            the_title();
        }
    }elseif (is_page()) {
      the_title();
    }
		echo '</nav>';
}

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


//  add new taxonomy location
function rj_add_new_taxonomy_location() {
    // Labels for the taxonomy
    $labels = array(
        'name'              => _x('Locations', 'taxonomy general name', 'rj-bookmarks'),
        'singular_name'     => _x('Location', 'taxonomy singular name', 'rj-bookmarks'),
        'search_items'      => __('Search Locations', 'rj-bookmarks'),
        'all_items'         => __('All Locations', 'rj-bookmarks'),
        'parent_item'       => __('Parent Location', 'rj-bookmarks'),
        'parent_item_colon' => __('Parent Location:', 'rj-bookmarks'),
        'edit_item'         => __('Edit Location', 'rj-bookmarks'),
        'update_item'       => __('Update Location', 'rj-bookmarks'),
        'add_new_item'      => __('Add New Location', 'rj-bookmarks'),
        'new_item_name'     => __('New Location Name', 'rj-bookmarks'),
        'menu_name'         => __('Location', 'rj-bookmarks'),
    );

    // Arguments for the taxonomy
    $args = array(
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'rj_location'),
    );

    // Register the taxonomy
    register_taxonomy('rj_location', array('post'), $args);
}

// Hook into the init action
add_action('init', 'rj_add_new_taxonomy_location', 0);


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

// Function to add post capabilities to subscribers
function rj_bookmarks_add_post_capability_to_subscribers() {
    // Get the subscriber role
    $role = get_role('subscriber');

    // Check if the role exists and add capabilities
    if ($role) {
        // These capabilities allow editing, publishing, deleting, and reading their own posts
        $role->add_cap('edit_posts');
        $role->add_cap('publish_posts');
        $role->add_cap('delete_posts');
        $role->add_cap('upload_files');

        // These capabilities are required to read the posts
        $role->add_cap('read');
        $role->add_cap('read_post');
    }
}
add_action('init', 'rj_bookmarks_add_post_capability_to_subscribers');

// Function to filter capabilities for post type only
// function filter_map_meta_cap($caps, $cap, $user_id, $args) {
//     // Define the caps to filter
//     $post_caps = array('edit_post', 'delete_post', 'read_post');
//
//     // If the capability being filtered is one of the post caps
//     if (in_array($cap, $post_caps)) {
//         $post = get_post($args[0]);
//         $post_type = get_post_type_object($post->post_type);
//
//         // Check if the user is trying to manage a post of type 'post'
//         if ($post->post_type === 'post') {
//             $caps = array();
//             switch ($cap) {
//                 case 'edit_post':
//                     $caps[] = ($user_id === $post->post_author) ? 'edit_posts' : 'edit_others_posts';
//                     break;
//                 case 'delete_post':
//                     $caps[] = ($user_id === $post->post_author) ? 'delete_posts' : 'delete_others_posts';
//                     break;
//                 case 'read_post':
//                     $caps[] = ($post->post_status === 'private') ? 'read_private_posts' : 'read';
//                     break;
//             }
//         }
//     }
//     return $caps;
// }
// add_filter('map_meta_cap', 'filter_map_meta_cap', 10, 4);


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
// add_action('admin_bar_menu', 'rj_bookmarks_remove_admin_bar_items_for_subscribers', 999);
add_action('init', 'rj_bookmarks_remove_admin_bar_items_for_subscribers', 999);

function create_custom_table() {
    global $wpdb;
    
    $table_name = $wpdb->prefix . 'visit_bookings'; // Name of the table
    $charset_collate = $wpdb->get_charset_collate();

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
        wp_mail('nehall.goyal@gmail.com', $admin_subject, $admin_body, $headers);
        wp_mail('mojo.nagpur@gmail.com', $admin_subject, $admin_body, $headers);

        wp_redirect(home_url());
        exit;

    }
}

add_action('wp', 'handle_booking_form');



/* Theme Font URL */
function rj_bookmarks_font_url() {
	$font_family   = array(
		'Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900'
	 );
	
	$query_args = array(
		'family'	=> rawurlencode(implode('|',$font_family)),
	);
	$font_url = add_query_arg($query_args,'//fonts.googleapis.com/css');
	return $font_url;
	$contents = wptt_get_webfont_url( esc_url_raw( $fonts_url ) );
}

register_nav_menus( array(
    'primary' => __( 'Primary Menu', 'rj-bookmarks' ),
) );

?>
