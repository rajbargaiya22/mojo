<?php
/**
 * The template part for menu
 *
 * @package rj-bookmarks
 */
?>
<div id="header">
    <div class="toggle-nav mobile-menu text-lg-end text-md-center text-center">
      <button role="tab" onclick="rj_bookmarks_menu_open_nav()" class="responsivetoggle"><i class="<?php echo esc_attr(get_theme_mod('rj_bookmarks_res_open_menu_icon','fas fa-bars')); ?>"></i><span class="screen-reader-text"><?php esc_html_e('Open Button','optical-lens-shop'); ?></span></button>
    </div>
  <div id="mySidenav" class="sidenav">
    <nav id="site-navigation" class="main-navigation" role="navigation" aria-label="<?php esc_attr_e( 'Top Menu', 'optical-lens-shop' ); ?>">
      <?php
        wp_nav_menu( array( 
          'theme_location' => 'primary',
          'container_class' => 'main-menu clearfix' ,
          'menu_class' => 'clearfix',
          'items_wrap' => '<ul id="%1$s" class="%2$s mobile_nav">%3$s</ul>',
          'fallback_cb' => 'wp_page_menu',
        ) );
         ?>
      <a href="javascript:void(0)" class="closebtn mobile-menu" onclick="rj_bookmarks_menu_close_nav()"><i class="<?php echo esc_attr(get_theme_mod('rj_bookmarks_res_close_menu_icon','fas fa-times')); ?>"></i><span class="screen-reader-text"><?php esc_html_e('Close Button','optical-lens-shop'); ?></span></a>
    </nav>
  </div>
</div>
