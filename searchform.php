<?php
/**
 * The template for displaying search forms in rj-bookmarks
 *
 * @package rj-bookmarks
 */
?>

<form role="search" method="get" id="searchform" action="<?php echo esc_url(home_url('/')); ?>">
  <input type="text" value="<?php echo get_search_query(); ?>" name="s" id="s"  placeholder="<?php echo esc_attr__('Search...', 'rj-bookmarks'); ?>" autocomplete="off" />

  <?php
  $categories = get_categories( ); ?>
  <select name="rj-category">
    <option value="">Choose Category</option>
    <?php foreach ($categories as $category) { ?>
      <option value="<?php echo esc_attr($category->slug); ?>"><?php echo esc_attr($category->name); ?></option>
    <?php } ?>
  </select>

  <?php
    $locations = get_terms('rj_location');
   ?>

   <select name="rj-location">
     <option value="">Choose Location</option>
     <?php foreach ($locations as $location) { ?>
       <option value="<?php echo esc_attr($location->slug); ?>"><?php echo esc_attr($location->name); ?></option>
     <?php } ?>
   </select>
    <label for="searchsubmit">
      <input type="submit" id="searchsubmit" value="<?php echo esc_attr__('Search', 'rj-bookmarks'); ?>" class="login-signup" />
      <span class="screen-reader-text">
        <?php echo esc_html(get_theme_mod('rj_bookmarks_search_button',__('Search','rj-bookmarks')));?>
      </span>
    </label>
</form>
