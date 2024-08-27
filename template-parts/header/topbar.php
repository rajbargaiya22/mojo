<?php
/*
* Template parts to topbar
*
* @package rj-mojo
*/
?>

<div class="topbar py-2">
  <div class="container">

      <div class="d-flex align-items-center flex-md-nowrap flex-wrap justify-content-between">
      <div class="d-flex align-items-center topbar-icons gap-2">
        <?php 
        $topbar_icons = array('Facebook', 'Twitter', 'Instagram', 'Pintrest', 'Youtube', 'Siteurl', 'Linkedin');

        foreach ($topbar_icons as $title){ ?>
          <a
              href="<?php echo esc_url(get_theme_mod('rj_bookmarks_topbar_'.strtolower($title).'_url')); ?>" 
              data-color="<?php echo esc_attr(get_theme_mod('rj_bookmarks_topbar_'.strtolower($title).'_color')); ?>" 
              title="<?php echo esc_attr(get_theme_mod('rj_bookmarks_topbar_'.strtolower($title).'_text')); ?>">
              <?php echo get_theme_mod('rj_bookmarks_topbar_'.strtolower($title).'_icon'); ?>
          </a>
        <?php } ?>
      </div>

      <?php if (is_user_logged_in()){ ?>
        <div class="rj-logged-in">
          <span class="login-signup">
            <?php echo esc_html("My Account"); ?>
          </span>
          <div class="rj-logged-in-menu">
            <ul>
              <li>
                <a href="<?php echo esc_html(get_theme_mod('rj_bookmarks_topbar_submit_bookmark_url')); ?>" class="login-signup">
                  <?php echo esc_html(get_theme_mod('rj_bookmarks_topbar_submit_bookmark')); ?>
                </a>
              </li>
              <li>
                <a href="<?php echo esc_html(get_theme_mod('rj_bookmarks_topbar_my_bookmark_url')); ?>" class="login-signup">
                  <?php echo esc_html(get_theme_mod('rj_bookmarks_topbar_my_bookmark')); ?>
                </a>
              </li>
              <li>
                <a href="<?php echo esc_html(get_theme_mod('rj_bookmarks_topbar_my_account_url')); ?>" class="login-signup">
                  <?php echo esc_html(get_theme_mod('rj_bookmarks_topbar_my_account')); ?>
                </a>
              </li>
              <li>
                <a href="<?php echo site_url() . '/wp-login.php?action=logout&redirect_to=' . site_url(); ?>" class="login-signup">Logout</a>
              </li>
            </ul>
          </div>
        </div>
      <?php }else { ?>
        <a href="#" class="login-signup">
          <svg xmlns="http://www.w3.org/2000/svg" height="11" width="11" viewBox="0 0 512 512"><path d="M336 352c97.2 0 176-78.8 176-176S433.2 0 336 0S160 78.8 160 176c0 18.7 2.9 36.8 8.3 53.7L7 391c-4.5 4.5-7 10.6-7 17v80c0 13.3 10.7 24 24 24h80c13.3 0 24-10.7 24-24V448h40c13.3 0 24-10.7 24-24V384h40c6.4 0 12.5-2.5 17-7l33.3-33.3c16.9 5.4 35 8.3 53.7 8.3zM376 96a40 40 0 1 1 0 80 40 40 0 1 1 0-80z"/></svg>
          <?php echo esc_html("Login/Resigter"); ?>
        </a>

        <a href="<?php echo esc_html(get_theme_mod('rj_bookmarks_topbar_login_signup_url')); ?>" class="login-signup">
          <svg xmlns="http://www.w3.org/2000/svg" height="11" width="11" viewBox="0 0 512 512"><path d="M336 352c97.2 0 176-78.8 176-176S433.2 0 336 0S160 78.8 160 176c0 18.7 2.9 36.8 8.3 53.7L7 391c-4.5 4.5-7 10.6-7 17v80c0 13.3 10.7 24 24 24h80c13.3 0 24-10.7 24-24V448h40c13.3 0 24-10.7 24-24V384h40c6.4 0 12.5-2.5 17-7l33.3-33.3c16.9 5.4 35 8.3 53.7 8.3zM376 96a40 40 0 1 1 0 80 40 40 0 1 1 0-80z"/></svg>
          <?php echo esc_html(get_theme_mod('rj_bookmarks_topbar_login_signup')); ?>
        </a>
      <?php } ?>



    </div>
  </div>
</div>
