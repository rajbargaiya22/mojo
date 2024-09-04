<?php
/*
* Template parts to topbar header
*
* @package rj-mojo
*/
?>

<div class="topbar-logo d-flex align-items-center justify-content-between px-5 py-3">
    <?php if (has_custom_logo() && get_theme_mod('rj_mojo_site_logo', true) != 0) { ?>
    <div class="rj-logo">
        <?php /*
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr(get_bloginfo( 'name' )); ?>">
          <?php
          $image_alt = get_bloginfo( 'name' );
          $custom_logo_id = get_theme_mod( 'custom_logo' );
          $logo_url = wp_get_attachment_image_src( $custom_logo_id , 'full' ); ?>
          <img src="<?php echo esc_url($logo_url[0]); ?>" alt="<?php echo esc_attr(($image_alt) ? $image_alt : get_the_title() ); ?>" title="<?php echo esc_attr(($image_alt) ? $image_alt : get_the_title() ); ?>" >
        </a> */?>

        <div class="">
          <?php if (get_theme_mod('rj_mojo_site_title', false) != 1){ ?>
            <h1 class="mb-0">
              <a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr(get_bloginfo( 'name' )); ?>">
                <?php $site_title = get_bloginfo( 'name' );
                echo $site_title; ?>
              </a>
            </h1>
          <?php } ?>

          <?php if (get_theme_mod('rj_mojo_site_description', false) != 0){ ?>
            <p class="mb-0">
                <?php $site_desc = get_bloginfo( 'description' );
                echo $site_desc;
                ?>
            </p>
          <?php } ?>
        </div>
      </div>
      <?php }else { ?>
      <?php if (get_theme_mod('rj_mojo_site_title', true) != 0 ){ ?>
        <h1 class="mb-0">
          <a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr(get_bloginfo( 'name' )); ?>">
            <?php $site_title = get_bloginfo( 'name' );
            echo $site_title;
            ?>
          </a>
        </h1>
      <?php } ?>

      <?php if (get_theme_mod('rj_mojo_site_description', true) != 0 ){ ?>
        <p class="mb-0">
            <?php $site_desc = get_bloginfo( 'description' );
            echo $site_desc;
            ?>
        </p>
      <?php }
    } ?>

    <!-- menus -->
    <div id="header">
        <div class="toggle-nav mobile-menu text-lg-end text-md-center text-center">
        <button role="tab" onclick="rj_mojo_menu_open_nav()" class="responsivetoggle"><i class="<?php echo esc_attr(get_theme_mod('rj_mojo_res_open_menu_icon','fas fa-bars')); ?>"></i><span class="screen-reader-text"><?php esc_html_e('Open Button','optical-lens-shop'); ?></span></button>
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
        <a href="javascript:void(0)" class="closebtn mobile-menu" onclick="rj_mojo_menu_close_nav()"><i class="<?php echo esc_attr(get_theme_mod('rj_mojo_res_close_menu_icon','fas fa-times')); ?>"></i><span class="screen-reader-text"><?php esc_html_e('Close Button','optical-lens-shop'); ?></span></a>
        </nav>
    </div>
    </div>
</div>