<?php
/**
 * Custom Widget for Posts, Tags, and Comments with Horizontal Tab View
 */

class RJ_Tabbed_Widget extends WP_Widget {
    function __construct() {
        parent::__construct(
            'rj_tabbed_widget',
            __('RJ Posts, Tags & Comments', 'rj-bookmarks'),
            array('description' => __('Widget with horizontal tabs for posts, tags, and comments', 'rj-bookmarks'))
        );
    }

    public function widget($args, $instance) {
        // Widget title
        $title = isset($instance['title']) ? $instance['title'] : __('Latest Content', 'rj-bookmarks');

        // Display the widget's outer structure
        echo $args['before_widget'];

        if (!empty($title)) {
            echo '<h3 class="section-main-head">' . esc_html($title) . '</h3>';
        }

        ?>
        <div class="rj-tabbed-widget">
            <ul class="rj-tabs">
                <li class="active">
                  <a href="#rj-news" title="<?php echo esc_attr('News'); ?>">
                    <svg width="16" height="17" viewBox="0 0 16 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                      <path fill-rule="evenodd" clip-rule="evenodd" d="M2.24855 1.90332L2.25 1.90332H10.75L10.7515 1.90332C11.0823 1.90428 11.3993 2.03613 11.6332 2.27007C11.8672 2.50401 11.999 2.82103 12 3.15187L12 3.15332V13.8989H11V3.15428C10.9997 3.08783 10.9731 3.02418 10.9261 2.97718C10.8792 2.93019 10.8155 2.90365 10.7491 2.90332H2.25092C2.18448 2.90365 2.12085 2.93019 2.07386 2.97718C2.02687 3.02417 2.00033 3.0878 2 3.15424V14.1525C2.00077 14.3514 2.08011 14.5419 2.22076 14.6826C2.36143 14.8232 2.552 14.9026 2.75093 14.9033H13V15.9033H2.75L2.74851 15.9033C2.2852 15.9019 1.84126 15.7173 1.51366 15.3897C1.18605 15.0621 1.00139 14.6181 1 14.1548L1 14.1533V3.15332L1 3.15187C1.00096 2.82103 1.13281 2.50401 1.36675 2.27007C1.60069 2.03613 1.91771 1.90428 2.24855 1.90332Z" fill="#21272A"/>
                      <path fill-rule="evenodd" clip-rule="evenodd" d="M11 4.90332C11 4.62718 11.2239 4.40332 11.5 4.40332H13.75C14.0815 4.40332 14.3995 4.53502 14.6339 4.76944C14.8683 5.00386 15 5.3218 15 5.65332V13.9033C15 14.4338 14.7893 14.9425 14.4142 15.3175C14.0391 15.6926 13.5304 15.9033 13 15.9033C12.4696 15.9033 11.9609 15.6926 11.5858 15.3175C11.2107 14.9425 11 14.4338 11 13.9033V4.90332ZM12 5.40332V13.9033C12 14.1685 12.1054 14.4229 12.2929 14.6104C12.4804 14.798 12.7348 14.9033 13 14.9033C13.2652 14.9033 13.5196 14.798 13.7071 14.6104C13.8946 14.4229 14 14.1685 14 13.9033V5.65332C14 5.58702 13.9737 5.52343 13.9268 5.47654C13.8799 5.42966 13.8163 5.40332 13.75 5.40332H12Z" fill="#21272A"/>
                      <path fill-rule="evenodd" clip-rule="evenodd" d="M7 4.90332C7 4.62718 7.22386 4.40332 7.5 4.40332H9.5C9.77614 4.40332 10 4.62718 10 4.90332C10 5.17946 9.77614 5.40332 9.5 5.40332H7.5C7.22386 5.40332 7 5.17946 7 4.90332Z" fill="#21272A"/>
                      <path fill-rule="evenodd" clip-rule="evenodd" d="M7 6.90332C7 6.62718 7.22386 6.40332 7.5 6.40332H9.5C9.77614 6.40332 10 6.62718 10 6.90332C10 7.17946 9.77614 7.40332 9.5 7.40332H7.5C7.22386 7.40332 7 7.17946 7 6.90332Z" fill="#21272A"/>
                      <path fill-rule="evenodd" clip-rule="evenodd" d="M3 8.90332C3 8.62718 3.22386 8.40332 3.5 8.40332H9.5C9.77614 8.40332 10 8.62718 10 8.90332C10 9.17946 9.77614 9.40332 9.5 9.40332H3.5C3.22386 9.40332 3 9.17946 3 8.90332Z" fill="#21272A"/>
                      <path fill-rule="evenodd" clip-rule="evenodd" d="M3 10.9033C3 10.6272 3.22386 10.4033 3.5 10.4033H9.5C9.77614 10.4033 10 10.6272 10 10.9033C10 11.1795 9.77614 11.4033 9.5 11.4033H3.5C3.22386 11.4033 3 11.1795 3 10.9033Z" fill="#21272A"/>
                      <path fill-rule="evenodd" clip-rule="evenodd" d="M3 12.9033C3 12.6272 3.22386 12.4033 3.5 12.4033H9.5C9.77614 12.4033 10 12.6272 10 12.9033C10 13.1795 9.77614 13.4033 9.5 13.4033H3.5C3.22386 13.4033 3 13.1795 3 12.9033Z" fill="#21272A"/>
                      <path d="M5.5 7.40332H3.5C3.36739 7.40332 3.24021 7.35064 3.14645 7.25687C3.05268 7.16311 3 7.03593 3 6.90332V4.90332C3 4.77071 3.05268 4.64354 3.14645 4.54977C3.24021 4.456 3.36739 4.40332 3.5 4.40332H5.5C5.63261 4.40332 5.75979 4.456 5.85355 4.54977C5.94732 4.64354 6 4.77071 6 4.90332V6.90332C6 7.03593 5.94732 7.16311 5.85355 7.25687C5.75979 7.35064 5.63261 7.40332 5.5 7.40332Z" fill="#21272A"/>
                    </svg>
                    <?php esc_html_e('News', 'rj-bookmarks'); ?>
                  </a>
                </li>
                <li>
                  <a href="#rj-tags" title="<?php echo esc_attr('Tags'); ?>">
                    <svg width="16" height="17" viewBox="0 0 16 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                      <g clip-path="url(#clip0_215_3607)">
                        <path d="M5.23933 4.90332H3.35C2.99196 4.90332 2.64858 5.04555 2.39541 5.29873C2.14223 5.5519 2 5.89528 2 6.25332V8.14265C2 8.50065 2.142 8.84399 2.39533 9.09732L6.47267 13.1747C6.59803 13.3 6.74686 13.3995 6.91066 13.4674C7.07447 13.5352 7.25003 13.5701 7.42733 13.5701C7.60464 13.5701 7.7802 13.5352 7.944 13.4674C8.10781 13.3995 8.25664 13.3 8.382 13.1747L10.2713 11.2853C10.3967 11.16 10.4962 11.0111 10.564 10.8473C10.6319 10.6835 10.6668 10.508 10.6668 10.3307C10.6668 10.1534 10.6319 9.97779 10.564 9.81398C10.4962 9.65018 10.3967 9.50135 10.2713 9.37599L6.19333 5.29865C5.94029 5.04565 5.59716 4.90346 5.23933 4.90332V4.90332Z" stroke="#21272A" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M11.7152 13.1745L13.6045 11.2852C13.7299 11.1598 13.8294 11.011 13.8972 10.8472C13.9651 10.6834 14 10.5078 14 10.3305C14 10.1532 13.9651 9.97764 13.8972 9.81383C13.8294 9.65003 13.7299 9.5012 13.6045 9.37584L8.85986 4.63184" stroke="#21272A" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M3.999 6.90332H3.99316" stroke="#21272A" stroke-linecap="round" stroke-linejoin="round"/>
                      </g>
                      <defs>
                        <clipPath id="clip0_215_3607">
                          <rect width="16" height="16" fill="white" transform="translate(0 0.90332)"/>
                        </clipPath>
                      </defs>
                    </svg>
                    <?php esc_html_e('Tags', 'rj-bookmarks'); ?>
                  </a>
                </li>
                <li>
                  <a href="#rj-comments" title="<?php echo esc_attr('Comments'); ?>">
                    <svg width="16" height="14" viewBox="0 0 16 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                      <path fill-rule="evenodd" clip-rule="evenodd" d="M0.666748 0.903483C0.666748 0.535293 0.965225 0.236816 1.33341 0.236816H12.0001C12.3683 0.236816 12.6667 0.535293 12.6667 0.903483V5.57015H14.6667C15.0349 5.57015 15.3334 5.86863 15.3334 6.23682V11.5702C15.3334 11.9383 15.0349 12.2368 14.6667 12.2368H13.2762L12.4715 13.0416C12.2111 13.3019 11.789 13.3019 11.5287 13.0416L10.7239 12.2368H7.33341C6.96522 12.2368 6.66675 11.9383 6.66675 11.5702V9.57015H5.94289L4.80482 10.7082C4.54447 10.9686 4.12236 10.9686 3.86201 10.7082L2.72394 9.57015H1.33341C0.965225 9.57015 0.666748 9.27167 0.666748 8.90348V0.903483ZM11.3334 8.23682V1.57015H2.00008V8.23682H3.00008C3.17689 8.23682 3.34646 8.30705 3.47149 8.43208L4.33341 9.29401L5.19534 8.43208C5.32037 8.30705 5.48994 8.23682 5.66675 8.23682H11.3334ZM12.0001 9.57015H8.00008V10.9035H11.0001C11.1769 10.9035 11.3465 10.9737 11.4715 11.0987L12.0001 11.6273L12.5287 11.0987C12.6537 10.9737 12.8233 10.9035 13.0001 10.9035H14.0001V6.90348H12.6667V8.90348C12.6667 9.27167 12.3683 9.57015 12.0001 9.57015ZM3.33341 3.57015C3.33341 3.20196 3.63189 2.90348 4.00008 2.90348H8.00008C8.36827 2.90348 8.66675 3.20196 8.66675 3.57015C8.66675 3.93834 8.36827 4.23682 8.00008 4.23682H4.00008C3.63189 4.23682 3.33341 3.93834 3.33341 3.57015ZM3.33341 6.23682C3.33341 5.86863 3.63189 5.57015 4.00008 5.57015H6.00008C6.36827 5.57015 6.66675 5.86863 6.66675 6.23682C6.66675 6.60501 6.36827 6.90348 6.00008 6.90348H4.00008C3.63189 6.90348 3.33341 6.60501 3.33341 6.23682Z" fill="black" fill-opacity="0.85"/>
                    </svg>

                    <?php esc_html_e('Comments', 'rj-bookmarks'); ?>
                  </a>
                </li>
            </ul>

            <div class="rj-tab-content" id="rj-news">
                <!-- Code for displaying posts -->


                <div class="rj-tab-contentt">
                    <?php
                    $latest_posts = new WP_Query(array(
                        'posts_per_page' => 5, // Number of posts to fetch
                        'post_status' => 'publish', // Only published posts
                        'orderby' => 'date', // Order by date
                        'order' => 'DESC' // Descending order
                    ));

                    if ($latest_posts->have_posts()) { // Check if there are any posts
                        while ($latest_posts->have_posts()) {
                            $latest_posts->the_post(); // Set up post data
                            ?>
                        <div class="rj-tab-post d-flex align-items-center gap-1">
                          <span class="d-block">
                            <svg width="24" height="25" viewBox="0 0 24 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M20.0501 11.5334L15.3801 9.52338L14.3401 9.08338C14.1801 9.00338 14.0401 8.79338 14.0401 8.61338V5.55338C14.0401 4.59338 13.3301 3.45338 12.4701 3.01338C12.1701 2.86338 11.8101 2.86338 11.5101 3.01338C10.6601 3.45338 9.95006 4.60338 9.95006 5.56338V8.62338C9.95006 8.80338 9.81006 9.01338 9.65006 9.09338L3.95006 11.5434C3.32006 11.8034 2.81006 12.5934 2.81006 13.2734V14.5934C2.81006 15.4434 3.45006 15.8634 4.24006 15.5234L9.25006 13.3634C9.64006 13.1934 9.96006 13.4034 9.96006 13.8334V14.9434V16.7434C9.96006 16.9734 9.83006 17.3034 9.67006 17.4634L7.35006 19.7934C7.11006 20.0334 7.00006 20.5034 7.11006 20.8434L7.56006 22.2034C7.74006 22.7934 8.41006 23.0734 8.96006 22.7934L11.3401 20.7934C11.7001 20.4834 12.2901 20.4834 12.6501 20.7934L15.0301 22.7934C15.5801 23.0634 16.2501 22.7934 16.4501 22.2034L16.9001 20.8434C17.0101 20.5134 16.9001 20.0334 16.6601 19.7934L14.3401 17.4634C14.1701 17.3034 14.0401 16.9734 14.0401 16.7434V13.8334C14.0401 13.4034 14.3501 13.2034 14.7501 13.3634L19.7601 15.5234C20.5501 15.8634 21.1901 15.4434 21.1901 14.5934V13.2734C21.1901 12.5934 20.6801 11.8034 20.0501 11.5334Z" fill="#FFCC00"/>
                            </svg>
                          </span>
                            <a href="<?php the_permalink(); ?>" class="rj-post-title" title="<?php the_title(); ?>"><?php the_title(); ?></a><br>
                        </div>
                        <?php
                        }
                        wp_reset_postdata(); // Reset post data after the loop
                    } else {
                        echo '<p>No recent posts found.</p>';
                    }
                    ?>
                </div>
            </div>

            <div class="rj-tab-content" id="rj-tags" style="display: none;">
                <!-- Code for displaying tags -->

                <div class="rj-tag-content">
                    <?php
                    $tags = get_tags(array(
                        'orderby' => 'count', // Order by the number of posts tagged
                        'order' => 'DESC',
                        'number' => 10 // Limit to 10 most popular tags
                    ));

                    if (!empty($tags)) {
                        foreach ($tags as $tag) {
                            ?>
                            <div class="rj-tag">
                                <a href="<?php echo esc_url(get_tag_link($tag->term_id)); ?>" title="<?php echo esc_attr($tag->name); ?>">
                                    <?php echo esc_html($tag->name); ?>
                                </a>
                            </div>
                            <?php
                        }
                    } else {
                        echo '<p>No tags found.</p>';
                    }
                    ?>
                </div>

            </div>

            <div class="rj-tab-content" id="rj-comments" style="display: none;">
                <div class="rj-tab-contentt">
                    <?php
                    $recent_comments = get_comments(array(
                        'number' => 5, // Number of comments to fetch
                        'status' => 'approve', // Only approved comments
                        'post_status' => 'publish' // Only comments from published posts
                    ));

                    if (!empty($recent_comments)) {
                        foreach ($recent_comments as $comment) {
                            ?>
                              <div class="rj-comment">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M512 240c0 114.9-114.6 208-256 208c-37.1 0-72.3-6.4-104.1-17.9c-11.9 8.7-31.3 20.6-54.3 30.6C73.6 471.1 44.7 480 16 480c-6.5 0-12.3-3.9-14.8-9.9c-2.5-6-1.1-12.8 3.4-17.4l0 0 0 0 0 0 0 0 .3-.3c.3-.3 .7-.7 1.3-1.4c1.1-1.2 2.8-3.1 4.9-5.7c4.1-5 9.6-12.4 15.2-21.6c10-16.6 19.5-38.4 21.4-62.9C17.7 326.8 0 285.1 0 240C0 125.1 114.6 32 256 32s256 93.1 256 208z"/></svg>
                                <a href="<?php echo esc_url(get_comment_link($comment->comment_ID)); ?>" title="<?php echo esc_attr($comment->comment_author); ?>">
                                    <?php echo esc_html($comment->comment_author); ?>
                                </a> on
                                <a href="<?php echo esc_url(get_permalink($comment->comment_post_ID)); ?>" title="<?php echo esc_attr(get_permalink($comment->comment_post_ID)); ?>">
                                    <?php echo get_the_title($comment->comment_post_ID); ?>
                                </a>
                              </div>
                            <?php
                        }
                    } else {
                        echo '<p>No recent comments found.</p>';
                    }
                    ?>
                </div>

            </div>
        </div>
        <?php

        echo $args['after_widget']; // After widget markup
    }

    public function form($instance) {
        // Widget title field
        $title = isset($instance['title']) ? $instance['title'] : __('Latest Content', 'rj-bookmarks');

        ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('title')); ?>">
                <?php esc_html_e('Widget Title:', 'rj-bookmarks'); ?>
            </label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>"
                   name="<?php echo esc_attr($this->get_field_name('title')); ?>"
                   type="text"
                   value="<?php echo esc_attr($title); ?>">
        </p>
        <?php
    }

    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['title'] = strip_tags($new_instance['title']);
        return $instance;
    }
}

// Register the widget
function rj_load_tabbed_widget() {
    register_widget('RJ_Tabbed_Widget');
}

// Hook into widgets_init
add_action('widgets_init', 'rj_load_tabbed_widget');
