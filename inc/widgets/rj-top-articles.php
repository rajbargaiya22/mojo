<?php
class RJ_Bookmarks_Top_Articles_Sites extends WP_Widget {
    public function __construct() {
	parent::__construct(
   		 'rj-bookmarks-top-articles-sites',
   		 'RJ Top Articles Sites'
   	 );
   	 add_action( 'widgets_init', function() {
   		 register_widget( 'RJ_Bookmarks_Top_Articles_Sites' );
   	 });
    }

  public $args = array(
   	 'before_site_image'  => '<h4 class="widgetsite_image">',
   	 'after_site_image'   => '</h4>',
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
   	 	__('%1$s %2$s %3$s', 'rj-bookmarks' ),
   			 $before_title,
   	 	   $title,
   			 $after_title
   		 );
   	 }

   	 $posts_per_page  = ! empty( $instance['posts_per_page'] ) ? $instance['posts_per_page'] : esc_html__( '5', 'rj-bookmarks' );

   // the query
   $the_query = new WP_Query( array(
   	   'post_type'    	=> 'rj-top-sites',
  	    'posts_per_page' => $posts_per_page
   ));

     if ( $the_query->have_posts() ) : ?>
         <table>
       		 <thead>
       			 <tr>
       				 <th>Site</th>
       				 <th>Url</th>
       			 </tr>
       		 </thead>
   	      <?php
   		while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
   		      <tr>
   				 <?php
       	     	$image_id = get_post_thumbnail_id();
       	     	$image_alt = get_post_meta($image_id, '_wp_attachment_image_alt', TRUE);
       	     	$image_title = get_the_title($image_id);
   					  $image_url = get_the_post_thumbnail_url( get_the_ID(), 'medium' );
         	?>

   				  <td>
   				  <?php if ($image_url){ ?>
   					  <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr(($image_alt) ? $image_alt : get_the_title() ); ?>" title="<?php echo esc_attr(($image_title) ? $image_title : get_the_title() ); ?>">
   				  <?php }else{ ?>
   					  <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/dummy-post-image.webp'); ?>" alt="<?php echo esc_attr(($image_alt) ? $image_alt : get_the_title() ); ?>" title="<?php echo esc_attr(($image_title) ? $image_title : get_the_title() ); ?>">
   				  <?php } ?>
   			  </td>
   			  <td>
   				  <h3 class="post-title"><a href="<?php echo get_the_title(); ?>" title="<?php echo get_the_title();?>"><?php echo get_the_title(); ?></a></h3>
   			  </td>
   		      </tr>
   		  <?php
   		endwhile;
   		wp_reset_postdata();
   		  ?>
   	 </table>
   	  <?php
     endif;
    }

  public function form( $instance ) {
	$title = ! empty( $instance['title'] ) ? $instance['title'] : esc_html__( '', 'rj-bookmarks' );

	$posts_per_page  = ! empty( $instance['posts_per_page'] ) ? $instance['posts_per_page'] : esc_html__( '', 'rj-bookmarks' );
	?>

  <p>
  	<label for="<?php echo esc_attr( $this->get_field_id( 'Title' ) ); ?>"><?php echo esc_html__( 'Title', 'rj-bookmarks' ); ?></label>
  	<input class="" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" value="<?php echo esc_attr( $title ); ?>" type="text">
	</p>

	<p>
  	<label for="<?php echo esc_attr( $this->get_field_id( 'Number of posts to show:' ) ); ?>"><?php echo esc_html__( 'Number of posts to show:', 'rj-bookmarks' ); ?></label>
  	<input class="" id="<?php echo esc_attr( $this->get_field_id( 'posts_per_page' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'posts_per_page' ) ); ?>" value="<?php echo esc_attr( $posts_per_page ); ?>" type="number">
	</p>
	<?php
  }

  public function update( $new_instance, $old_instance ) {
	$instance      	= array();
	$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
	$instance['posts_per_page']  = ( ! empty( $new_instance['posts_per_page'] ) ) ? $new_instance['posts_per_page'] : '';
	return $instance;
  }
}
$my_widget = new RJ_Bookmarks_Top_Articles_Sites();
