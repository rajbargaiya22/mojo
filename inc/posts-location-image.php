<?php

if ( ! class_exists( 'RJ_BOOKMARKS_POSTS_LOCATION_IMAGE' ) ) {

class RJ_BOOKMARKS_POSTS_LOCATION_IMAGE {

  public function __construct() {
    //
  }

 /*
  * Initialize the class and start calling our hooks and filters
  * @since 1.0.0
 */
 public function init() {
   add_action( 'rj_location_add_form_fields', array ( $this, 'rj_bookmarks_add_rj_location_image' ), 10, 2 );
   add_action( 'created_rj_location', array ( $this, 'rj_bookmarks_save_rj_location_image' ), 10, 2 );
   add_action( 'rj_location_edit_form_fields', array ( $this, 'rj_bookmarks_update_rj_location_image' ), 10, 2 );
   add_action( 'edited_rj_location', array ( $this, 'rj_bookmarks_updated_rj_location_image' ), 10, 2 );
   add_action( 'admin_enqueue_scripts', array( $this, 'rj_bookmarks_load_media' ) );
   add_action( 'admin_footer', array ( $this, 'rj_bookmarks_add_script' ) );
 }

public function rj_bookmarks_load_media() {
 wp_enqueue_media();
}

 /*
  * Add a form field in the new rj_location page
  * @since 1.0.0
 */
 public function rj_bookmarks_add_rj_location_image ( $taxonomy ) { ?>
   <div class="form-field term-group">
     <label for="rj_location-image-id"><?php _e('Image', 'rj-bookmarks'); ?></label>
     <input type="hidden" id="rj_location-image-id" name="rj_location-image-id" class="custom_media_url" value="">
     <div id="rj_location-image-wrapper"></div>
     <p>
       <input type="button" class="button button-secondary ct_tax_media_button" id="ct_tax_media_button" name="ct_tax_media_button" value="<?php _e( 'Add Image', 'rj-bookmarks' ); ?>" />
       <input type="button" class="button button-secondary ct_tax_media_remove" id="ct_tax_media_remove" name="ct_tax_media_remove" value="<?php _e( 'Remove Image', 'rj-bookmarks' ); ?>" />
    </p>
   </div>
 <?php
 }

 /*
  * Save the form field
  * @since 1.0.0
 */
 public function rj_bookmarks_save_rj_location_image ( $term_id, $tt_id ) {
   if( isset( $_POST['rj_location-image-id'] ) && '' !== $_POST['rj_location-image-id'] ){
     $image = $_POST['rj_location-image-id'];
     add_term_meta( $term_id, 'rj_location-image-id', $image, true );
   }
 }

 /*
  * Edit the form field
  * @since 1.0.0
 */
 public function rj_bookmarks_update_rj_location_image ( $term, $taxonomy ) { ?>
   <tr class="form-field term-group-wrap">
     <th scope="row">
       <label for="rj_location-image-id"><?php _e( 'Image', 'rj-bookmarks' ); ?></label>
     </th>
     <td>
       <?php $image_id = get_term_meta( $term -> term_id, 'rj_location-image-id', true ); ?>
       <input type="hidden" id="rj_location-image-id" name="rj_location-image-id" value="<?php echo $image_id; ?>">
       <div id="rj_location-image-wrapper">
         <?php if ( $image_id ) { ?>
           <?php echo wp_get_attachment_image ( $image_id, 'thumbnail' ); ?>
         <?php } ?>
       </div>
       <p>
         <input type="button" class="button button-secondary ct_tax_media_button" id="ct_tax_media_button" name="ct_tax_media_button" value="<?php _e( 'Add Image', 'rj-bookmarks' ); ?>" />
         <input type="button" class="button button-secondary ct_tax_media_remove" id="ct_tax_media_remove" name="ct_tax_media_remove" value="<?php _e( 'Remove Image', 'rj-bookmarks' ); ?>" />
       </p>
     </td>
   </tr>
 <?php
 }

/*
 * Update the form field value
 * @since 1.0.0
 */
 public function rj_bookmarks_updated_rj_location_image ( $term_id, $tt_id ) {
   if( isset( $_POST['rj_location-image-id'] ) && '' !== $_POST['rj_location-image-id'] ){
     $image = $_POST['rj_location-image-id'];
     update_term_meta ( $term_id, 'rj_location-image-id', $image );
   } else {
     update_term_meta ( $term_id, 'rj_location-image-id', '' );
   }
 }

/*
 * Add script
 * @since 1.0.0
 */
 public function rj_bookmarks_add_script() { ?>
   <script>
     jQuery(document).ready( function($) {
       function ct_media_upload(button_class) {
         var _custom_media = true,
         _orig_send_attachment = wp.media.editor.send.attachment;
         $('body').on('click', button_class, function(e) {
           var button_id = '#'+$(this).attr('id');
           var send_attachment_bkp = wp.media.editor.send.attachment;
           var button = $(button_id);
           _custom_media = true;
           wp.media.editor.send.attachment = function(props, attachment){
             if ( _custom_media ) {
               $('#rj_location-image-id').val(attachment.id);
               $('#rj_location-image-wrapper').html('<img class="custom_media_image" src="" style="margin:0;padding:0;max-height:100px;float:none;" />');
               $('#rj_location-image-wrapper .custom_media_image').attr('src',attachment.url).css('display','block');
             } else {
               return _orig_send_attachment.apply( button_id, [props, attachment] );
             }
            }
         wp.media.editor.open(button);
         return false;
       });
     }
     ct_media_upload('.ct_tax_media_button.button');
     $('body').on('click','.ct_tax_media_remove',function(){
       $('#rj_location-image-id').val('');
       $('#rj_location-image-wrapper').html('<img class="custom_media_image" src="" style="margin:0;padding:0;max-height:100px;float:none;" />');
     });
     // Thanks: http://stackoverflow.com/questions/15281995/wordpress-create-rj_location-ajax-response
     $(document).ajaxComplete(function(event, xhr, settings) {
       var queryStringArr = settings.data.split('&');
       if( $.inArray('action=add-tag', queryStringArr) !== -1 ){
         var xml = xhr.responseXML;
         $response = $(xml).find('term_id').text();
         if($response!=""){
           // Clear the thumb image
           $('#rj_location-image-wrapper').html('');
         }
       }
     });
   });
 </script>
 <?php }

  }

$RJ_BOOKMARKS_POSTS_LOCATION_IMAGE = new RJ_BOOKMARKS_POSTS_LOCATION_IMAGE();
$RJ_BOOKMARKS_POSTS_LOCATION_IMAGE -> init();

}

/*
// Hook to add a custom column to the rj_location table
function rj_bookmarks_post_cat_image($columns) {
    $columns['post_cat_image'] = 'Image'; // Add your column name and label here
    return $columns;
}

add_filter('manage_edit-rj_location_columns', 'rj_bookmarks_post_cat_image');

// Display content in the custom column
function rj_bookmarks_post_cat_image_content($deprecated, $column_name, $term_id) {
    if ($column_name === 'post_cat_image') {
        // Get and display your custom data here
        $custom_data = get_term_meta($term_id, 'rj_location-image-id', true);
        echo $custom_data;
        echo '<img src="' . esc_url($custom_data) . '" style="max-width: 50px; height: auto;" alt="rj_location Image">';
    }
}

add_action('manage_rj_location_post_cat_image', 'rj_bookmarks_post_cat_image_content', 10, 3); */
