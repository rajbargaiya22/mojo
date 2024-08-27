
<?php 
/**
 * Register a contact list page.
 */
function wpdocs_register_my_custom_menu_page() {
	add_menu_page(
		__( 'Contact List', 'rj-bookmarks' ),
		'Contact List',
		'manage_options',
		'rj-contact-list',
		'rj_mojo_contact_list_html',
		'dashicons-list-view',
        9
	);

    add_submenu_page(
        'rj-contact-list',
        __( 'Books Time Slot', 'rj-bookmarks' ),
        __( 'Time Slot', 'rj-bookmarks' ),
        'manage_options',
        'time-slot',
        'rj_mojo_time_slot_html'
    );

    add_submenu_page(
        'rj-contact-list',
        __( 'Price', 'rj-bookmarks' ),
        __( 'Price', 'rj-bookmarks' ),
        'manage_options',
        'price',
        'rj_mojo_price_html'
    );
}
add_action( 'admin_menu', 'wpdocs_register_my_custom_menu_page' );

function rj_mojo_contact_list_html(){ ?>
    <h1>Contact List</h1>    
    <table class="contact-table">
        <thead>
            <tr>
                <th class="contact-heading">Sr. No</th>
                <th class="contact-heading">Name</th>
                <th class="contact-heading">Email</th>
                <th class="contact-heading">Contact No</th>
                <th class="contact-heading">Booking Date</th>
                <th class="contact-heading">Time Slot</th>
                <th class="contact-heading">Children</th> 
                <th class="contact-heading">Grown-ups</th> 
                <th class="contact-heading">Total cost</th> 
            </tr>
        </thead>
        <tbody>
        <?php
            global $wpdb;
            $table = 'wp_visit_bookings';

            $query = $wpdb->prepare("SELECT * FROM $table");
            $results = $wpdb->get_results($query, ARRAY_A);

            if ($results) {
                foreach ($results as $index => $row) {
                        // print_r($row);
                    ?>
                    <tr>
                        <td> <?php echo $index + 1; ?> </td>
                        <td> <?php echo $row['name']; ?> </td>
                        <td> <?php echo $row['email']; ?> </td>
                        <td> <?php echo $row['contact_no']; ?> </td>
                        <td> <?php echo $row['booking_date']; ?> </td>
                        <td> <?php echo $row['time_slot'] ?> </td>
                        <td> <?php echo $row['no_of_children'] . '*' . get_option('grown_up_rate', true); ?> </td>
                        <td> <?php echo $row['no_of_elders'] . '*' . get_option('children_rate', true);; ?> </td>
                        <td> <?php echo $row['total_cost']; ?> </td>
                        
                    </tr>
                <?php }
            }
            ?>

        </tbody>
    </table>
<?php }

// Time Slot
function rj_mojo_time_slot_html() {
    $saved_time_slots = get_option('rj_mojo_time_slots', array());
    ?>
    <h1>Time Slot</h1>
    <table id="time_slot_table">
        <thead>
            <tr>
                <th>Start time</th>
                <th>End time</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($saved_time_slots)): ?>
            <tr>
                <td><input type="time" name="start_time[]"></td>
                <td><input type="time" name="end_time[]"></td>
                <td><button class="remove-slot">Remove</button></td>
            </tr>
            <?php else: 
                foreach ($saved_time_slots as $slot): ?>
                <tr>
                    <td><input type="time" name="start_time[]" value="<?php echo esc_attr($slot['start']); ?>"></td>
                    <td><input type="time" name="end_time[]" value="<?php echo esc_attr($slot['end']); ?>"></td>
                    <td><button class="remove-slot">Remove</button></td>
                </tr>
                <?php endforeach;
            endif; ?>
        </tbody>
    </table>
    <button id="add_time_button">Add Time Slot</button>
    <button id="save_time_button">Save Time Slots</button>

    <script>
    jQuery(document).ready(function($) {
        $('#add_time_button').on('click', function() {
            var newRow = '<tr><td><input type="time" name="start_time[]"></td><td><input type="time" name="end_time[]"></td><td><button class="remove-slot">Remove</button></td></tr>';
            $('#time_slot_table tbody').append(newRow);
        });

        $(document).on('click', '.remove-slot', function() {
            $(this).closest('tr').remove();
        });

        $('#save_time_button').on('click', function() {
            var timeSlots = [];
            $('#time_slot_table tbody tr').each(function() {
                var startTime = $(this).find('input[name="start_time[]"]').val();
                var endTime = $(this).find('input[name="end_time[]"]').val();
                if (startTime && endTime) {
                    timeSlots.push({start: startTime, end: endTime});
                }
            });

            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'save_time_slots',
                    time_slots: JSON.stringify(timeSlots),
                    nonce: '<?php echo wp_create_nonce("save_time_slots_nonce"); ?>'
                },
                success: function(response) {
                    alert('Time slots saved successfully!');
                },
                error: function() {
                    alert('Error saving time slots. Please try again.');
                }
            });
        });
    });
    </script>
    <?php

    $saved_time_slots = get_option('rj_mojo_time_slots', array());
}

function save_time_slots_callback() {
    check_ajax_referer('save_time_slots_nonce', 'nonce');

    if (!current_user_can('manage_options')) {
        wp_die('Unauthorized access');
    }

    $time_slots = json_decode(stripslashes($_POST['time_slots']), true);
    
    update_option('rj_mojo_time_slots', $time_slots);

    wp_send_json_success('Time slots saved successfully');
}
add_action('wp_ajax_save_time_slots', 'save_time_slots_callback');

function enqueue_jquery_for_time_slots() {
    wp_enqueue_script('jquery');
}
add_action('wp_enqueue_scripts', 'enqueue_jquery_for_time_slots');
add_action('admin_enqueue_scripts', 'enqueue_jquery_for_time_slots');

// total cost
function rj_mojo_price_html(){ ?>

    <h1>Rate</h1>


    <form action="<?php echo esc_url($_SERVER['REQUEST_URI']); ?>" method="post">
        <?php wp_nonce_field('my_form_action', 'my_form_nonce');
            $children_rate = get_option('children_rate', true);
            $grownup_rate = get_option('grown_up_rate', true);
        ?>
        <div>
            <label for="children_rate">Children Rate : </label>
            <input type="text" name="children_rate" id="children_rate" value="<?php echo $children_rate; ?>">
        </div>
        <div>
            <label for="grown_up_rate">Grown Up Rate : </label>
            <input type="text" name="grown_up_rate" id="grown_up_rate" value="<?php echo $grownup_rate; ?>">
        </div>

        <input type="submit" value="submit">
    </form>
<?php }


function handle_form_submission() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Verify nonce for security (if you use it in your form)
        if (!isset($_POST['my_form_nonce']) || !wp_verify_nonce($_POST['my_form_nonce'], 'my_form_action')) {
            return;
        }

        // Sanitize and retrieve form inputs
        $children_rate = isset($_POST['children_rate']) ? sanitize_text_field($_POST['children_rate']) : '';
        $grown_up_rate = isset($_POST['grown_up_rate']) ? sanitize_text_field($_POST['grown_up_rate']) : '';

        // Update options in the wp_options table
        update_option('children_rate', $children_rate);
        update_option('grown_up_rate', $grown_up_rate);
    }
}
add_action('init', 'handle_form_submission');
