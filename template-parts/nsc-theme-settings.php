<?php
/**
 * The template part for theme settings
 *
 * @package rj-bookmarks
 */
?>

<div class="rj-feature-settings">

  <button type="button" class="d-block ms-auto rj-setting-btn" id="rj-setting-btn" aria-label="rj-setting-content">
    <i class="fa-solid fa-gear"></i>
    <span class="screen-reader-text">
      <?php echo esc_html(get_theme_mod('rj_bookmarks_theme_settings_button',__('Open setting','rj-bookmarks')));?>
    </span>
  </button>

  <div class="rj-setting-content" id="rj-setting-content">
    <?php if(get_theme_mod('rj_bookmarks_theme_settings_main_heading', true) != false){ ?>
      <h2 class="customier-title">
          <?php echo esc_html(get_theme_mod('rj_bookmarks_theme_settings_main_heading')); ?>
      </h2>
    <?php } ?>

    <?php if(get_theme_mod('rj_bookmarks_theme_settings_direction_heading', true) != false){ ?>
      <h3 class="">
          <?php echo esc_html(get_theme_mod('rj_bookmarks_theme_settings_direction_heading')); ?>
      </h3>
    <?php } ?>
    <div class="">
      <label for="rj-dir-rtl">
        <?php echo esc_html(get_theme_mod('rj_bookmarks_theme_settings_direction_rtl')); ?>
        <input type="radio" name="rj-theme-dir" id="rj-dir-rtl" value="rtl">
      </label>
      <label for="rj-dir-ltr">
        <?php echo esc_html(get_theme_mod('rj_bookmarks_theme_settings_header_style_ltr')); ?>
        <input type="radio" name="rj-theme-dir" id="rj-dir-ltr" value="ltr" checked>
      </label>
    </div>

    <label for="rj-theme-modes" class="d-flex align-items-center gap-2">
      <?php echo esc_html(get_theme_mod('rj_bookmarks_theme_settings_theme_modes')); ?>
      <input type="checkbox" name="rj-theme-mod" value="" id="rj-theme-modes">
      <span></span>
    </label>
  </div>

</div>
