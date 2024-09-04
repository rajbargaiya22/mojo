<?php

if ( ! class_exists( 'rj_mojo_POSTS_LOCATION_IMAGE' ) ) {

class rj_mojo_POSTS_LOCATION_IMAGE {

  public function __construct() {
    //
  }

  /*
  * Initialize the class and start calling our hooks and filters
  * @since 1.0.0
  */
  public function init() {
    add_action( 'exhibit_category_add_form_fields', array ( $this, 'rj_mojo_add_exhibit_category_image' ), 10, 2 );
    add_action( 'created_exhibit_category', array ( $this, 'rj_mojo_save_exhibit_category_image' ), 10, 2 );
    add_action( 'exhibit_category_edit_form_fields', array ( $this, 'rj_mojo_update_exhibit_category_image' ), 10, 2 );
    add_action( 'edited_exhibit_category', array ( $this, 'rj_mojo_updated_exhibit_category_image' ), 10, 2 );
    add_action( 'admin_enqueue_scripts', array( $this, 'rj_mojo_load_media' ) );
    add_action( 'admin_footer', array ( $this, 'rj_mojo_add_script' ) );
  }

  public function rj_mojo_load_media() {
    wp_enqueue_media();
  }

  /*
  * Add form fields in the new exhibit_category page
  * @since 1.0.0
  */
  public function rj_mojo_add_exhibit_category_image( $taxonomy ) { ?>
    <div class="form-field term-group">
      <?php for ($i = 1; $i <= 3; $i++): ?>
        <label for="exhibit_category-image-id-<?php echo $i; ?>"><?php _e("Image $i", 'rj-mojo'); ?></label>
        <input type="hidden" id="exhibit_category-image-id-<?php echo $i; ?>" name="exhibit_category-image-id-<?php echo $i; ?>" class="custom_media_url" value="">
        <div id="exhibit_category-image-wrapper-<?php echo $i; ?>"></div>
        <p>
          <input type="button" class="button button-secondary ct_tax_media_button" data-target="<?php echo $i; ?>" value="<?php _e( 'Add Image', 'rj-mojo' ); ?>" />
          <input type="button" class="button button-secondary ct_tax_media_remove" data-target="<?php echo $i; ?>" value="<?php _e( 'Remove Image', 'rj-mojo' ); ?>" />
        </p>
      <?php endfor; ?>
      <label for="exhibit_category-color"><?php _e('Color', 'rj-mojo'); ?></label>
      <input type="color" name="exhibit_category-color" value="" id="exhibit_category-color">
    </div>
  <?php }

  /*
  * Save the form fields
  * @since 1.0.0
  */
  public function rj_mojo_save_exhibit_category_image( $term_id, $tt_id ) {
    for ($i = 1; $i <= 3; $i++) {
      if (isset($_POST["exhibit_category-image-id-$i"]) && '' !== $_POST["exhibit_category-image-id-$i"]) {
        $image = $_POST["exhibit_category-image-id-$i"];
        add_term_meta($term_id, "exhibit_category-image-id-$i", $image, true);
      }
    }

    if (isset($_POST['exhibit_category-color'])) {
      $color = sanitize_hex_color($_POST['exhibit_category-color']);
      update_term_meta($term_id, 'exhibit_category-color', $color);
    }
  }

  /*
  * Edit the form fields
  * @since 1.0.0
  */
  public function rj_mojo_update_exhibit_category_image( $term, $taxonomy ) { ?>
      <?php for ($i = 1; $i <= 3; $i++): ?>
        <tr class="form-field term-group-wrap">
        <th>
          <label for="exhibit_category-image-id-<?php echo $i; ?>"><?php _e("Image $i", 'rj-mojo'); ?></label>
        </th>
        <td>
          <?php $image_id = get_term_meta($term->term_id, "exhibit_category-image-id-$i", true); ?>
          <input type="hidden" id="exhibit_category-image-id-<?php echo $i; ?>" name="exhibit_category-image-id-<?php echo $i; ?>" value="<?php echo esc_attr($image_id); ?>">
          <div id="exhibit_category-image-wrapper-<?php echo $i; ?>">
            <?php if ($image_id) { ?>
              <?php echo wp_get_attachment_image($image_id, 'thumbnail'); ?>
            <?php } ?>
          </div>
          <p>
            <input type="button" class="button button-secondary ct_tax_media_button" data-target="<?php echo $i; ?>" value="<?php _e('Add Image', 'rj-mojo'); ?>" />
            <input type="button" class="button button-secondary ct_tax_media_remove" data-target="<?php echo $i; ?>" value="<?php _e('Remove Image', 'rj-mojo'); ?>" />
          </p>
        </td>
        </tr>
      <?php endfor; ?>
      <tr>
      <th>
        <label for="exhibit_category-color"><?php _e('Color', 'rj-mojo'); ?></label>
      </th>
      <td>
        <?php $color = get_term_meta($term->term_id, 'exhibit_category-color', true); ?>
        <input type="color" name="exhibit_category-color" value="<?php echo esc_attr($color); ?>" id="exhibit_category-color">
      </td>
    </tr>
  <?php }

  /*
  * Update the form fields values
  * @since 1.0.0
  */
  public function rj_mojo_updated_exhibit_category_image( $term_id, $tt_id ) {
    for ($i = 1; $i <= 3; $i++) {
      if (isset($_POST["exhibit_category-image-id-$i"]) && '' !== $_POST["exhibit_category-image-id-$i"]) {
        $image = $_POST["exhibit_category-image-id-$i"];
        update_term_meta($term_id, "exhibit_category-image-id-$i", $image);
      } else {
        update_term_meta($term_id, "exhibit_category-image-id-$i", '');
      }
    }

    if (isset($_POST['exhibit_category-color']) && '' !== $_POST['exhibit_category-color']) {
      $color = sanitize_hex_color($_POST['exhibit_category-color']);
      update_term_meta($term_id, 'exhibit_category-color', $color);
    } else {
      update_term_meta($term_id, 'exhibit_category-color', '');
    }
  }

  /*
  * Add script
  * @since 1.0.0
  */
  public function rj_mojo_add_script() { ?>
    <script>
      jQuery(document).ready(function($) {
        function ct_media_upload(button_class) {
          var _custom_media = true,
              _orig_send_attachment = wp.media.editor.send.attachment;

          $('body').on('click', button_class, function(e) {
            e.preventDefault();
            var button = $(this);
            var target = button.data('target');
            var imageField = '#exhibit_category-image-id-' + target;
            var imageWrapper = '#exhibit_category-image-wrapper-' + target;

            wp.media.editor.send.attachment = function(props, attachment) {
              if (_custom_media) {
                $(imageField).val(attachment.id);
                $(imageWrapper).html('<img class="custom_media_image" src="' + attachment.url + '" style="max-height:100px;"/>');
              } else {
                return _orig_send_attachment.apply(button_class, [props, attachment]);
              }
            };

            wp.media.editor.open(button);
            return false;
          });
        }

        ct_media_upload('.ct_tax_media_button');

        $('body').on('click', '.ct_tax_media_remove', function() {
          var target = $(this).data('target');
          $('#exhibit_category-image-id-' + target).val('');
          $('#exhibit_category-image-wrapper-' + target).html('');
        });

        $('#exhibit_category-color').change(function(e) {
          $(this).attr('value', e.target.value);
        });
      });
    </script>
  <?php }

}

$rj_mojo_POSTS_LOCATION_IMAGE = new rj_mojo_POSTS_LOCATION_IMAGE();
$rj_mojo_POSTS_LOCATION_IMAGE->init();

}
?>
