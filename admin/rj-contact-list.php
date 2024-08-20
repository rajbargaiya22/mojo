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
                <!-- <th class="contact-heading">Booking Date</th>
                <th class="contact-heading">Number Of Childs</th> 
                <th class="contact-heading">Number Of Elders</th>  -->
                <th class="contact-heading">Message</th> 
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
                        <?php /*<td> <?php echo $row['booking_date']; ?> </td>
                        <td> <?php echo $row['no_of_children']; ?> </td>
                        <td> <?php echo $row['no_of_elders']; ?> </td>*/?>
                        <td> <?php echo $row['message']; ?> </td>
                        
                    </tr>
                <?php }
            }
            ?>

        </tbody>
    </table>
<?php }