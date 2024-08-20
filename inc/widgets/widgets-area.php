<?php
/**
 * Add a sidebar.
 */
function rj_bookmarks_widgets_init() {
	$widgets_area =
		array(
			'Home Page' => 'Home page sidebar',
			'Left Sidebar' => 'Widgets in this area will be shown on all posts and pages.',
			'Right Sidebar' => 'Widgets in this area will be shown on all posts and pages.',
			'Footer One' => 'Footer column one',
			'Footer Two' => 'Footer column second',
			'Footer Three' => 'Footer column third',
			'Footer Four' => 'Footer column fourth',
			'Footer Fifth' => 'Footer column fifth',
			'Copyright Text' => 'Copyright Text',
		);

	foreach ($widgets_area as $name => $description) {
		register_sidebar( array(
			'name'          => $name,
			'id'            => strtolower(str_replace(' ', '-', $name)),
			'description' =>  $name,
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3 class="section-main-head">',
			'after_title'   => '</h3>',
		) );
	}
}
add_action( 'widgets_init', 'rj_bookmarks_widgets_init' );

 ?>
