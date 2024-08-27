<?php
/**
 * The template for displaying Comments.
 *
 * The area of the page that contains both current comments
 * and the comment form. The actual display of comments is
 * handled by a callback to rj_bookmarks_comment() which is
 * located in the inc/template-tags.php file.
 *
 * @package rj-mojo
 */

/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
if ( post_password_required() ) {
	return;
}
?>

<div id="comments" class="comments-area">
	<?php if ( have_comments() ) : ?>
		<h3 class="rj-comment-title">
			<?php
			$rj_bookmarks_comments_number = get_comments_number();
					// echo esc_html__('Responses (' . $rj_bookmarks_comments_number . ')' , 'rj-mojo');
					printf(
			    	esc_html__( 'Responses (%d)', 'rj-mojo' ),
			    	$rj_bookmarks_comments_number
					);
			?>
		</h3>

		<?php the_comments_navigation(); ?>

		<ol class="comment-list">
		<?php
			$args = array(
			    'callback' => 'rj_bookmarks_custom_comment_list',
			);
			wp_list_comments($args);
		?>
		</ol>

		<?php the_comments_navigation(); ?>

	<?php endif; ?>

	<?php
		if ( ! comments_open() && get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) :
	?>
		<p class="no-comments"><?php esc_html_e( 'Comments are closed.', 'rj-mojo' ); ?></p>
	<?php endif; ?>

	<?php
		comment_form( array(
			'title_reply_before' => '<h2 id="reply-title" class="comment-reply-title">',
			'title_reply_after'  => '</h2>',
			'title_reply' => esc_html(get_theme_mod('rj_bookmarks_single_blog_comment_title',__('','rj-mojo' )) ),
			'label_submit' => esc_html(get_theme_mod('rj_bookmarks_single_blog_comment_button_text',__('Submit','rj-mojo' )) ),
		) );
	?>
</div>
