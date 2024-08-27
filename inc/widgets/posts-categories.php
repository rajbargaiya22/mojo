<?php
class RJ_Bookmarks_Post_Category extends WP_Widget {
	public function __construct() {
    parent::__construct(
			'rj-mojo-posts-cats',
			'RJ Posts Category'
		);
		add_action( 'widgets_init', function() {
			register_widget( 'RJ_Bookmarks_Post_Category' );
		});
	}

  public $args = array(
		'before_title'  => '<h3 class="section-main-head">',
		'after_title'   => '</h3>',
		'before_widget' => '<div class="widget-wrap">',
		'after_widget'  => '</div></div>',
	);

	public function widget( $args, $instance ) {
    echo $args['before_widget'];
		if ( ! empty( $instance['title'] ) ) {

			$before_title = $args['before_title'];
			$title = apply_filters( 'widget_title', $instance['title'] );
			$after_title = $args['after_title'];

			printf(
		    __('%1$s %2$s %3$s', 'rj-mojo' ),
				$before_title,
		    $title,
				$after_title
			);
		}
		$cats_num  = ! empty( $instance['cats_num'] ) ? $instance['cats_num'] : esc_html__( '3', 'rj-mojo' );
    $cat_args = array(
           'orderby' => 'slug',
           'parent' => 0,
					 'number' => $cats_num
       );
    echo '<ul class="rj-posts-cats">';
    $categories = get_categories( $cat_args );
		foreach ($categories as $category) {
				echo '<li class="rj-posts-cat-item">
								<a href="' . esc_url(get_category_link($category->term_id)) . '" rel="bookmark" aria-label="'.esc_attr('Visit the ' . $category->name . ' category page').'" title="'. $category->name .'">' . esc_html($category->name) . '</a>
						</li>';
			}
			echo "</ul>";

		if ( ! empty( $instance['view_all_cat_text'] ) ) {
			$view_all_cat_url = esc_attr( $instance['view_all_cat_url'] );
			$view_all_cat_text = esc_html( $instance['view_all_cat_text'] );
			printf(
		    __('<a href="%1$s" aria-label="Visit all category listing page" class="view-all-category-btn" title="%1$s">%2$s </a>', 'rj-mojo' ),
				$view_all_cat_url,
				$view_all_cat_text
			);
		}

		echo $args['after_widget'];
	}

	public function form( $instance ) {
    $title = ! empty( $instance['title'] ) ? $instance['title'] : esc_html__( '', 'rj-mojo' );
		$view_all_cat_text  = ! empty( $instance['view_all_cat_text'] ) ? $instance['view_all_cat_text'] : esc_html__( '', 'rj-mojo' );
		$view_all_cat_url  = ! empty( $instance['view_all_cat_url'] ) ? $instance['view_all_cat_url'] : esc_html__( '', 'rj-mojo' );
		$cats_num  = ! empty( $instance['cats_num'] ) ? $instance['cats_num'] : esc_html__( '', 'rj-mojo' );
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php echo esc_html__( 'Title:', 'rj-mojo' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'cats_num' ) ); ?>"><?php echo esc_html__( 'Number of category to show:', 'rj-mojo' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'cats_num' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'cats_num' ) ); ?>" type="text" value="<?php echo esc_attr( $cats_num ); ?>">
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'View All Button' ) ); ?>" style="display:block;">
				<?php echo esc_html__( 'View All Button:', 'rj-mojo' ); ?>
			</label>
			<input class="" id="<?php echo esc_attr( $this->get_field_id( 'view_all_cat_text' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'view_all_cat_text' ) ); ?>" value="<?php echo esc_attr( $view_all_cat_text ); ?>" type="text" style="width: 100%;">
			<label for="<?php echo esc_attr( $this->get_field_id( 'view_all_cat_url' ) ); ?>" style="display:block;"><?php echo esc_html__( 'View All Url:', 'rj-mojo' ); ?></label>
			<input class="" id="<?php echo esc_attr( $this->get_field_id( 'view_all_cat_url' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'view_all_cat_url' ) ); ?>" value="<?php echo esc_attr( $view_all_cat_url ); ?>" type="text" style="width: 100%;">
			<span style="display:block;"><?php echo esc_html__('Create the page and assign the template "RJ Post Categories" to display all the categories', 'rj-mojo'); ?></span>
		</p>

		<?php
	}

	public function update( $new_instance, $old_instance ) {
    $instance          = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['view_all_cat_text']  = ( ! empty( $new_instance['view_all_cat_text'] ) ) ? $new_instance['view_all_cat_text'] : '';
		$instance['view_all_cat_url']  = ( ! empty( $new_instance['view_all_cat_url'] ) ) ? $new_instance['view_all_cat_url'] : '';
    $instance['cats_num']  = ( ! empty( $new_instance['cats_num'] ) ) ? $new_instance['cats_num'] : '';
		return $instance;
	}
}
$my_widget = new RJ_Bookmarks_Post_Category();
