<?php
class RJ_Bookmarks_Post_Tags extends WP_Widget {
	public function __construct() {
    parent::__construct(
			'rj-bookmarks-posts-tags',
			'RJ Posts Tags'
		);
		add_action( 'widgets_init', function() {
			register_widget( 'RJ_Bookmarks_Post_Tags' );
		});
	}

  public $args = array(
		'before_title'  => '<h4 class="widgettitle">',
		'after_title'   => '</h4>',
		'before_widget' => '<div class="widget-wrap">',
		'after_widget'  => '</div></div>',
	);

	public function widget( $args, $instance ) {
    echo $args['before_widget'];

		if ( ! empty( $instance['title'] ) ) {

			$before_title = $args['before_title'];
			$title = apply_filters( 'widget_title', $instance['title'] );
			$view_all_url = esc_attr( $instance['view_all_url'] );
			$view_all_text = esc_html( $instance['view_all_text'] );
			$after_title = $args['after_title'];

			printf(
		    __('%1$s %2$s <a href="%3$s" aria-label="Visit all tags listing page" title="Visit all tags listing page">%4$s </a> %5$s', 'rj-bookmarks' ),
				$before_title,
		    $title,
				$view_all_url,
				$view_all_text,
				$after_title
			);
		}

		$tag_num  = ! empty( $instance['tag_num'] ) ? $instance['tag_num'] : esc_html__( '5', 'rj-bookmarks' );

    $tags = get_tags(array(
      'hide_empty' => false,
			'number' => $tag_num
    ));
    echo '<ul class="rj-posts-tags">';
      $tags = get_tags($tags);
      foreach ( $tags as $tag ) :
        $tag_link = get_tag_link( $tag->term_id ); ?>
        <li>
          <a href='<?php echo $tag_link; ?>' title='<?php echo $tag->name; ?>' class='<?php echo $tag->slug ?>'>
            <?php echo $tag->name ?> (<?php echo $tag->count ?>)
          </a>
        </li>
    <?php
        endforeach;
        echo '</ul>';
		echo $args['after_widget'];
	}

	public function form( $instance ) {
    $title = ! empty( $instance['title'] ) ? $instance['title'] : esc_html__( '', 'rj-bookmarks' );
		$view_all_text  = ! empty( $instance['view_all_text'] ) ? $instance['view_all_text'] : esc_html__( '', 'rj-bookmarks' );
		$view_all_url  = ! empty( $instance['view_all_url'] ) ? $instance['view_all_url'] : esc_html__( '', 'rj-bookmarks' );
		$tag_num  = ! empty( $instance['tag_num'] ) ? $instance['tag_num'] : esc_html__( '', 'rj-bookmarks' );
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php echo esc_html__( 'Title:', 'rj-bookmarks' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'View All Button' ) ); ?>"><?php echo esc_html__( 'View All Button:', 'rj-bookmarks' ); ?></label>
			<input class="" id="<?php echo esc_attr( $this->get_field_id( 'view_all_text' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'view_all_text' ) ); ?>" value="<?php echo esc_attr( $view_all_text ); ?>" type="text">
			<input class="" id="<?php echo esc_attr( $this->get_field_id( 'view_all_url' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'view_all_url' ) ); ?>" value="<?php echo esc_attr( $view_all_url ); ?>" type="text">
			<span><?php echo esc_html__('Create the page and assign the template "RJ Post Tags" to display all the tags', 'rj-bookmarks'); ?></span>
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'Number of tags to show:' ) ); ?>"><?php echo esc_html__( 'Number of tags to show:', 'rj-bookmarks' ); ?></label>
      <input class="" id="<?php echo esc_attr( $this->get_field_id( 'tag_num' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'tag_num' ) ); ?>" value="<?php echo esc_attr( $tag_num ); ?>" type="number">
		</p>
		<?php
	}

	public function update( $new_instance, $old_instance ) {
    $instance          = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
    $instance['tag_num']  = ( ! empty( $new_instance['tag_num'] ) ) ? $new_instance['tag_num'] : '';
    $instance['view_all_text']  = ( ! empty( $new_instance['view_all_text'] ) ) ? $new_instance['view_all_text'] : '';
    $instance['view_all_url']  = ( ! empty( $new_instance['view_all_url'] ) ) ? $new_instance['view_all_url'] : '';
		return $instance;
	}
}
$my_widget = new RJ_Bookmarks_Post_Tags();
