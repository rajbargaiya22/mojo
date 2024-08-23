<?php
/**
 * The template part for displaying page content
 *
 * @package RJ Mojo
 */
?>

<section class="rj-users">
  <div class="custom-cotainer">
      <?php
      global $wp_roles;
       $roles = $wp_roles->roles;
       $roles_names = wp_list_pluck($roles, 'name');
       $assigned_roles = array();

       foreach ($roles_names as $role => $name) {
           if ($role === 'administrator') {
               continue;
           }

           $users = get_users(array('role' => $role));
           if (!empty($users)) {
               $assigned_roles[$role] = $name;
           }
       }

       if (!empty($assigned_roles)) {
           foreach ($assigned_roles as $role => $name) {

               $args = array(
                   'role'    => $role,
                   // 'orderby' => 'user_nicename',
                   // 'order'   => 'ASC'
               );
               $users = get_users($args);
               ?>
               <h3 class="section-main-head mt-5">
                 <?php
                  echo $name; ?>
               </h3>
               <div class="rj-author-container">
               <?php
                 foreach ($users as $user) { ?>
                   <div class="text-center">

                     <?php
                     $avatar_html = get_avatar(get_the_author_meta('ID'));
                     $avatar_url = '';

                     if (!empty($avatar_html)) {
                       $dom = new DOMDocument;
                       $dom->loadHTML($avatar_html);

                       $img_tags = $dom->getElementsByTagName('img');

                       if ($img_tags->length > 0) {
                         $avatar_url = $img_tags->item(0)->getAttribute('src');
                       }
                     }

                     if (!empty($avatar_url)) : ?>
                       <div class="rj-autor-image">
                         <img  src="<?php echo esc_url($avatar_url); ?>" alt="<?php echo get_the_author(); ?>" title="<?php echo get_the_author(); ?>">
                       </div>
                     <?php else : ?>
                       <div class="rj-autor-image">
                         <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/default-user.png'); ?>" alt="<?php echo get_the_author(); ?>" title="<?php echo get_the_author(); ?>">
                       </div>
                     <?php endif; ?>

                     <p class="rj-authorname mb-0 mt-4"><?php echo esc_html($user->display_name) ?></p>

                     <?php if (get_user_meta($user->ID, 'rj_bookmarks_user_location', true) != ''): ?>
                       <span class="rj-author-location"><?php echo get_user_meta($user->ID, 'rj_bookmarks_user_location', true); ?></span>
                     <?php endif; ?>

                     <div class="d-flex justify-content-center gap-2 mt-3">
                       <?php $social_links = array(
                           'rj_bookmarks_user_facebook' =>
                                                 '<svg width="21" height="20" viewBox="0 0 21 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                 <path d="M13.1 4.43344H14.6666V1.78344C13.9081 1.70456 13.1459 1.66562 12.3833 1.66677C10.1166 1.66677 8.56663 3.05011 8.56663 5.58344V7.76677H6.0083V10.7334H8.56663V18.3334H11.6333V10.7334H14.1833L14.5666 7.76677H11.6333V5.87511C11.6333 5.00011 11.8666 4.43344 13.1 4.43344Z" fill="#4C4C4C"/>
                                                 </svg>',

                           'rj_bookmarks_user_twitter' =>
                                                   '<svg width="21" height="20" viewBox="0 0 21 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                   <path d="M18.8333 4.83326C18.2069 5.10501 17.5445 5.28465 16.8666 5.3666C17.5818 4.93936 18.1177 4.26725 18.375 3.47493C17.7029 3.87498 16.9673 4.15684 16.2 4.30826C15.6871 3.75207 15.0041 3.38184 14.2581 3.25564C13.5122 3.12944 12.7454 3.2544 12.0781 3.61092C11.4108 3.96745 10.8807 4.53537 10.571 5.22563C10.2612 5.91588 10.1894 6.68942 10.3666 7.42493C9.00782 7.3562 7.67866 7.00239 6.46551 6.38648C5.25235 5.77057 4.18233 4.90634 3.32496 3.84993C3.02424 4.37507 2.86623 4.96978 2.86663 5.57493C2.86556 6.13692 3.00347 6.69044 3.26809 7.18623C3.5327 7.68202 3.91581 8.10469 4.38329 8.4166C3.83994 8.40181 3.3082 8.25601 2.83329 7.9916V8.03326C2.83737 8.82067 3.11329 9.58251 3.61439 10.1899C4.11549 10.7973 4.81101 11.213 5.58329 11.3666C5.28601 11.4571 4.97736 11.5048 4.66663 11.5083C4.45153 11.5058 4.23698 11.4862 4.02496 11.4499C4.24488 12.1273 4.67048 12.7192 5.24252 13.1434C5.81457 13.5676 6.50461 13.8029 7.21663 13.8166C6.0143 14.7627 4.52986 15.279 2.99996 15.2833C2.72141 15.2842 2.44307 15.2675 2.16663 15.2333C3.72865 16.2418 5.54897 16.7772 7.40829 16.7749C8.69137 16.7883 9.96424 16.5458 11.1526 16.0617C12.3409 15.5775 13.4208 14.8615 14.3293 13.9553C15.2378 13.0492 15.9566 11.9711 16.4438 10.784C16.9309 9.59695 17.1767 8.3247 17.1666 7.0416C17.1666 6.89993 17.1666 6.74993 17.1666 6.59993C17.8205 6.11227 18.3845 5.51445 18.8333 4.83326Z" fill="#4C4C4C"/>
                                                   </svg>
                                                   ',

                           'rj_bookmarks_user_linkedin' =>
                                                   '<svg width="21" height="20" viewBox="0 0 21 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                   <g clip-path="url(#clip0_412_11675)">
                                                   <path d="M19.0195 0H1.97656C1.16016 0 0.5 0.644531 0.5 1.44141V18.5547C0.5 19.3516 1.16016 20 1.97656 20H19.0195C19.8359 20 20.5 19.3516 20.5 18.5586V1.44141C20.5 0.644531 19.8359 0 19.0195 0ZM6.43359 17.043H3.46484V7.49609H6.43359V17.043ZM4.94922 6.19531C3.99609 6.19531 3.22656 5.42578 3.22656 4.47656C3.22656 3.52734 3.99609 2.75781 4.94922 2.75781C5.89844 2.75781 6.66797 3.52734 6.66797 4.47656C6.66797 5.42188 5.89844 6.19531 4.94922 6.19531ZM17.543 17.043H14.5781V12.4023C14.5781 11.2969 14.5586 9.87109 13.0352 9.87109C11.4922 9.87109 11.2578 11.0781 11.2578 12.3242V17.043H8.29688V7.49609H11.1406V8.80078H11.1797C11.5742 8.05078 12.543 7.25781 13.9844 7.25781C16.9883 7.25781 17.543 9.23438 17.543 11.8047V17.043Z" fill="#4C4C4C"/>
                                                   </g>
                                                   <defs>
                                                   <clipPath id="clip0_412_11675">
                                                   <rect width="20" height="20" fill="white" transform="translate(0.5)"/>
                                                   </clipPath>
                                                   </defs>
                                                   </svg>'
                         );
                         foreach ($social_links as $input => $icon) {
                           if(get_user_meta($user->ID, $input, true) ){
                               echo "<a href=" . get_user_meta($user->ID, $input, true) .">$icon</a>";
                           }
                         } ?>
                     </div>
                   </div>
                  <?php
               } ?>
               </div>
            <?php
           }
       }
       ?>


  </div>
</section>
