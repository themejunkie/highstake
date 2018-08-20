<?php
/**
 * Custom template tags for this theme.
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package    Highstake
 * @author     Theme Junkie
 * @copyright  Copyright (c) 2018, Theme Junkie
 * @license    http://www.gnu.org/licenses/gpl-2.0.html
 * @since      1.0.0
 */

if ( ! function_exists( 'highstake_lite_site_branding' ) ) :
/**
 * Site branding for the site.
 *
 * Display site title by default, but user can change it with their custom logo.
 * They can upload it on Customizer page.
 *
 * @since  1.0.0
 */
function highstake_lite_site_branding() {

	// Get the log.
	$logo_id  = get_theme_mod( 'custom_logo' );
	$logo_url = wp_get_attachment_image_src( $logo_id , 'full' );

	// Check if logo available, then display it.
	if ( $logo_id ) :
		echo '<div class="site-branding">'. "\n";
			echo '<div class="logo">';
				echo '<a href="' . esc_url( get_home_url() ) . '" rel="home">' . "\n";
					echo '<img src="' . esc_url( $logo_url[0] ) . '" alt="' . esc_attr( get_bloginfo( 'name' ) ) . '" />' . "\n";
				echo '</a>' . "\n";
			echo '</div>' . "\n";
		echo '</div>' . "\n";

	// If not, then display the Site Title and Site Description.
	else :
		echo '<div class="site-branding">'. "\n";
			echo '<h1 class="site-title"><a href="' . esc_url( get_home_url() ) . '" rel="home">' . esc_attr( get_bloginfo( 'name' ) ) . '</a></h1>'. "\n";
		echo '</div>'. "\n";
	endif;

}
endif;

if ( ! function_exists( 'highstake_lite_post_meta' ) ) :
/**
 * Prints HTML with meta information for the current post-date/time and author.
 */
function highstake_lite_post_meta() {
	?>

	<div class="entry-meta">

		<?php if ( is_single() ) : ?>
			<span class="entry-author">
				<?php $author_id = get_queried_object()->post_author; ?>
				<?php printf( esc_html__( 'by %s', 'highstake-lite' ), '<a class="url fn n" href="' . esc_url( get_author_posts_url( $author_id ) ) . '">' . esc_html( get_the_author_meta( 'display_name', $author_id ) ) . '</a>' ) ?>
			</span>
		<?php else : ?>
			<span class="entry-author">
				<?php printf( esc_html__( 'by %s', 'highstake-lite' ), '<a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a>' ) ?>
			</span>
		<?php endif; ?>

		<time class="entry-date published" datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time>

		<?php if ( ! post_password_required() && ( comments_open() || '0' != get_comments_number() ) ) : ?>
			<span class="entry-comment">
				<?php comments_popup_link( __( '0 Comment', 'highstake-lite' ), __( '1 Comment', 'highstake-lite' ), __( '% Comments', 'highstake-lite' ) ); ?>
			</span>
		<?php endif; ?>

	</div>

	<?php
}
endif;

if ( ! function_exists( 'highstake_lite_categorized_blog' ) ) :
/**
 * Returns true if a blog has more than 1 category.
 *
 * @since  1.0.0
 * @return bool
 */
function highstake_lite_categorized_blog() {
	if ( false === ( $all_the_cool_cats = get_transient( 'highstake_lite_categories' ) ) ) {
		// Create an array of all the categories that are attached to posts.
		$all_the_cool_cats = get_categories( array(
			'fields'     => 'ids',
			'hide_empty' => 1,

			// We only need to know if there is more than one category.
			'number'     => 2,
		) );

		// Count the number of categories that are attached to the posts.
		$all_the_cool_cats = count( $all_the_cool_cats );

		set_transient( 'highstake_lite_categories', $all_the_cool_cats );
	}

	if ( $all_the_cool_cats > 1 ) {
		// This blog has more than 1 category so highstake_lite_categorized_blog should return true.
		return true;
	} else {
		// This blog has only 1 category so highstake_lite_categorized_blog should return false.
		return false;
	}
}
endif;

if ( ! function_exists( 'highstake_lite_category_transient_flusher' ) ) :
/**
 * Flush out the transients used in highstake_lite_categorized_blog.
 *
 * @since 1.0.0
 */
function highstake_lite_category_transient_flusher() {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	// Like, beat it. Dig?
	delete_transient( 'highstake_lite_categories' );
}
endif;
add_action( 'edit_category', 'highstake_lite_category_transient_flusher' );
add_action( 'save_post',     'highstake_lite_category_transient_flusher' );

if ( ! function_exists( 'highstake_lite_post_author_box' ) ) :
/**
 * Author post informations.
 *
 * @since  1.0.0
 */
function highstake_lite_post_author_box() {

	// Get the data set in customizer
	$enable = get_theme_mod( 'highstake_lite_author_box', 1 );

	// Disable if user choose it.
	if ( $enable === 0 ) {
		return;
	}

	// Bail if not on the single post.
	if ( ! is_single() ) {
		return;
	}

	// Bail if user hasn't fill the Biographical Info field.
	if ( ! get_the_author_meta( 'description' ) ) {
		return;
	}

	// Get the author social information.
	$twitter   = get_the_author_meta( 'twitter' );
	$facebook  = get_the_author_meta( 'facebook' );
	$gplus     = get_the_author_meta( 'gplus' );
	$instagram = get_the_author_meta( 'instagram' );
	$pinterest = get_the_author_meta( 'pinterest' );
	$linkedin  = get_the_author_meta( 'linkedin' );
?>

	<div class="author-bio clearfix">
		<?php echo get_avatar( is_email( get_the_author_meta( 'user_email' ) ), apply_filters( 'highstake_lite_author_bio_avatar_size', 120 ), '', strip_tags( get_the_author() ) ); ?>
		<div class="description">

			<h3 class="author-title name">
				<a class="author-name url fn n" href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>" rel="author"><?php echo strip_tags( get_the_author() ); ?></a>
			</h3>

			<p class="bio"><?php echo stripslashes( get_the_author_meta( 'description' ) ); ?></p>

		</div>

		<?php if ( $twitter || $facebook || $gplus || $instagram || $pinterest || $linkedin ) : ?>
			<div class="author-social-links">
				<?php if ( $twitter ) { ?>
					<a href="<?php echo esc_url( $twitter ); ?>"><i class="fa fa-twitter"></i></a>
				<?php } ?>
				<?php if ( $facebook ) { ?>
					<a href="<?php echo esc_url( $facebook ); ?>"><i class="fa fa-facebook"></i></a>
				<?php } ?>
				<?php if ( $gplus ) { ?>
					<a href="<?php echo esc_url( $gplus ); ?>"><i class="fa fa-google-plus"></i></a>
				<?php } ?>
				<?php if ( $instagram ) { ?>
					<a href="<?php echo esc_url( $instagram ); ?>"><i class="fa fa-instagram"></i></a>
				<?php } ?>
				<?php if ( $pinterest ) { ?>
					<a href="<?php echo esc_url( $pinterest ); ?>"><i class="fa fa-pinterest"></i></a>
				<?php } ?>
				<?php if ( $linkedin ) { ?>
					<a href="<?php echo esc_url( $linkedin ); ?>"><i class="fa fa-linkedin"></i></a>
				<?php } ?>
			</div>
		<?php endif; ?>
	</div><!-- .author-bio -->

<?php
}
endif;

if ( ! function_exists( 'highstake_lite_related_posts' ) ) :
/**
 * Related posts.
 *
 * @since  1.0.0
 */
function highstake_lite_related_posts() {

	// Get the data set in customizer
	$enable  = get_theme_mod( 'highstake_lite_related_posts', 1 );

	// Disable if user choose it.
	if ( $enable === 0 ) {
		return;
	}

	// Get the taxonomy terms of the current page for the specified taxonomy.
	$terms = wp_get_post_terms( get_the_ID(), 'category', array( 'fields' => 'ids' ) );

	// Bail if the term empty.
	if ( empty( $terms ) ) {
		return;
	}

	// Posts query arguments.
	$query = array(
		'post__not_in' => array( get_the_ID() ),
		'tax_query'    => array(
			array(
				'taxonomy' => 'category',
				'field'    => 'id',
				'terms'    => $terms,
				'operator' => 'IN'
			)
		),
		'posts_per_page' => 4,
		'post_type'      => 'post',
	);

	// Allow dev to filter the query.
	$args = apply_filters( 'highstake_lite_related_posts_args', $query );

	// The post query
	$related = new WP_Query( $args );

	if ( $related->have_posts() ) : ?>

		<div class="related-posts posts-in-grid">
			<h3 class="related-title posts-in-grid-title"><?php esc_html_e( 'You might also like', 'highstake-lite' ); ?></h3>
			<ul>
				<?php while ( $related->have_posts() ) : $related->the_post(); ?>
					<li>
						<?php if ( has_post_thumbnail() ) : ?>
							<a class="thumbnail-link" href="<?php the_permalink(); ?>"><?php the_post_thumbnail( 'highstake-posts-in-grid', array( 'class' => 'entry-thumbnail', 'alt' => esc_attr( get_the_title() ) ) ); ?></a>
						<?php endif; ?>
						<?php if ( 'post' == get_post_type() ) : ?>
							<?php
								$category = get_the_category( get_the_ID() );
								if ( $category ) :
							?>
							<span class="cat-link">
								<a href="<?php echo esc_url( get_category_link( $category[0]->term_id ) ); ?>"><?php echo esc_attr( $category[0]->name ); ?></a>
							</span>
							<?php endif; // End if categories ?>
						<?php endif; ?>
						<?php the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
					</li>
				<?php endwhile; ?>
			</ul>
		</div>

	<?php endif;

	// Restore original Post Data.
	wp_reset_postdata();

}
endif;

if ( ! function_exists( 'highstake_lite_comment' ) ) :
/**
 * Template for comments and pingbacks.
 *
 * Used as a callback by wp_list_comments() for displaying the comments.
 *
 * @since  1.0.0
 */
function highstake_lite_comment( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
	switch ( $comment->comment_type ) :
		case 'pingback' :
		case 'trackback' :
		// Display trackbacks differently than normal comments.
	?>
	<li <?php comment_class(); ?> id="comment-<?php comment_ID(); ?>">
		<article id="comment-<?php comment_ID(); ?>" class="comment-container">
			<p><?php esc_html_e( 'Pingback:', 'highstake-lite' ); ?> <span><?php comment_author_link(); ?></span> <?php edit_comment_link( esc_html__( '(Edit)', 'highstake-lite' ), '<span class="edit-link">', '</span>' ); ?></p>
		</article>
	<?php
			break;
		default :
		// Proceed with normal comments.
	?>
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
		<article id="comment-<?php comment_ID(); ?>" class="comment-container">

			<div class="comment-avatar">
				<?php echo get_avatar( $comment, apply_filters( 'highstake_lite_comment_avatar_size', 80 ) ); ?>
				<span class="name"><span itemprop="name"><?php echo get_comment_author_link(); ?></span></span>
				<?php echo highstake_lite_comment_author_badge(); ?>
			</div>

			<div class="comment-body">
				<div class="comment-wrapper">

					<div class="comment-head">
						<?php
							$edit_comment_link = '';
							if ( get_edit_comment_link() )
								$edit_comment_link = sprintf( esc_html__( '&middot; %1$sEdit%2$s', 'highstake-lite' ), '<a href="' . get_edit_comment_link() . '" title="' . esc_attr__( 'Edit Comment', 'highstake-lite' ) . '">', '</a>' );

							printf( '<span class="date"><a href="%1$s"><time datetime="%2$s">%3$s</time></a> %4$s</span>',
								esc_url( get_comment_link( $comment->comment_ID ) ),
								get_comment_time( 'c' ),
								/* translators: 1: date, 2: time */
								sprintf( esc_html__( '%1$s at %2$s', 'highstake-lite' ), get_comment_date(), get_comment_time() ),
								$edit_comment_link
							);
						?>
					</div><!-- comment-head -->

					<div class="comment-content comment-entry">
						<?php if ( '0' == $comment->comment_approved ) : ?>
							<p class="comment-awaiting-moderation"><?php esc_html_e( 'Your comment is awaiting moderation.', 'highstake-lite' ); ?></p>
						<?php endif; ?>
						<?php comment_text(); ?>
						<span class="reply">
							<?php comment_reply_link( array_merge( $args, array( 'reply_text' => wp_kses_post( __( '<i class="fa fa-reply"></i> Reply', 'highstake-lite' ) ), 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
						</span><!-- .reply -->
					</div><!-- .comment-content -->

				</div>
			</div>

		</article><!-- #comment-## -->
	<?php
		break;
	endswitch; // end comment_type check
}
endif;

if ( ! function_exists( 'highstake_lite_comment_author_badge' ) ) :
/**
 * Custom badge for post author comment
 *
 * @since  1.0.0
 */
function highstake_lite_comment_author_badge() {

	// Set up empty variable
	$output = '';

	// Get comment classes
	$classes = get_comment_class();

	if ( in_array( 'bypostauthor', $classes ) ) {
		$output = '<span class="author-badge">' . esc_html__( 'Author', 'highstake-lite' ) . '</span>';
	}

	// Display the badge
	return apply_filters( 'highstake_lite_comment_author_badge', $output );
}
endif;

if ( ! function_exists( 'highstake_lite_get_format_gallery' ) ) :
/**
 * Get the [gallery] shortcode from the post content and display it on index page. It require
 * gallery ids [gallery ids=1,2,3,4] to display it as thumbnail slideshow. If no ids exist it
 * just display it as highstake [gallery] markup.
 *
 * If no [gallery] shortcode found in the post content, get the attached images to the post and
 * display it as slideshow.
 *
 * @since  1.0.0
 * @uses   get_post_gallery() to get the gallery in the post content.
 * @uses   wp_get_attachment_image() to get the HTML image.
 * @uses   get_children() to get the attached images if no [gallery] found in the post content.
 * @return string
 */
function highstake_lite_get_format_gallery() {

	// Set up placeholders
	$slider = '';

	// for has_sidebar
	$size = 'highstake-post-featured-image';

	// for no sidebar
	if ( current_theme_supports( 'theme-layouts' ) && ! is_admin() ) {
		if ( 'layout-1c' == theme_layouts_get_layout() ) {
			$size = 'large';
		}
	}

	// Check the [gallery] shortcode in post content.
	$gallery = get_post_gallery( get_the_ID(), false );

	// Check if the [gallery] exist.
	if ( $gallery ) {

		// Check if the gallery ids exist.
		if ( isset( $gallery['ids'] ) ) {

			// Get the gallery ids and convert it into array.
			$ids = explode( ',', $gallery['ids'] );

			// Display the gallery in a cool slideshow on index page.
			foreach( $ids as $id ) {
				$slider .= '<div>';
				$slider .= '<a href="' . wp_get_attachment_url( $id ) . '">';
				$slider .= wp_get_attachment_image( $id, $size, false, array( 'class' => 'entry-thumbnail' ) );
				$slider .= '</a>';
				$slider .= '</div>';
			}

		} else {

			// If gallery ids not exist, display the highstake gallery markup.
			// avoid this, since it'll look bad
			// $html = get_post_gallery( get_the_ID() );

			// if gallery based on images attached to post (only [gallery] in post content)
			// note: in the post content, better to use: [gallery size="large"] or [gallery size="full"]
			$srcs = $gallery['src'];

			// Display the gallery in a cool slideshow on index page.
			foreach( $srcs as $src ) {
				$slider .= '<div>';
				$slider .= '<a href="' . esc_url( $src ) . '">';
				$slider .= '<img src="' . esc_url( $src ) . '" />';
				$slider .= '</a>';
				$slider .= '</div>';
			}

		}

	// If no [gallery] in post content, get attached images to the post.
	} else {

		// Set up default arguments.
		$defaults = array(
			'order'          => 'ASC',
			'post_type'      => 'attachment',
			'post_parent'    => get_the_ID(),
			'post_mime_type' => 'image',
			'numberposts'    => -1
		);

		// Retrieves attachments from the post.
		$attachments = get_children( apply_filters( 'highstake_lite_gallery_format_args', $defaults ) );

		// Check if attachments exist.
		if ( $attachments ) {

			// Display the attachment images in a cool slideshow on index page.
			foreach ( $attachments as $attachment ) {
				$slider .= '<div>';
				$slider .= '<a href="' . wp_get_attachment_url( $attachment->ID ) . '">';
				$slider .= wp_get_attachment_image( $attachment->ID, $size, false, array( 'class' => 'entry-thumbnail' ) );
				$slider .= '</a>';
				$slider .= '</div>';
			}

		} else {

			// if no [gallery] shortcode and has no attachments, bail them out
			return;

		}

	}
	?>
	<div class="entry-gallery">
		<div class="owl-carousel owl-theme format-gallery-slider">
			<?php echo $slider; ?>
		</div>
		<i class="fa fa-expand" aria-hidden="true"></i>
	</div><!-- .entry-image -->
	<?php
}
endif;

if ( ! function_exists( 'highstake_lite_get_post_format_link_url' ) ) :
/**
 * Forked from hybrid_get_the_post_format_url.
 * Filters 'get_the_post_format_url' to make for a more robust and back-compatible function.  If WP did
 * not find a URL, check the post content for one.  If nothing is found, return the post permalink.
 * Used in Post Format Link
 *
 * @since 1.0.0
 */
function highstake_lite_get_post_format_link_url( $url = '', $post = null ) {

	if ( empty( $url ) ) {

		$post = is_null( $post ) ? get_post() : $post;

		/* Catch links that are not wrapped in an '<a>' tag. */
		$content_url = preg_match( '/<a\s[^>]*?href=[\'"](.+?)[\'"]/is', make_clickable( $post->post_content ), $matches );

		$content_url = ! empty( $matches[1] ) ? esc_url_raw( $matches[1] ) : '';

		$url = ! empty( $content_url ) ? $content_url : get_permalink( get_the_ID() );
	}

	if ( $url ) {
	?>
		<h2 class="entry-title">
			<a href="<?php echo esc_url( $url ); ?>">
				<?php if ( get_the_title() && ( esc_html__( '(Untitled)', 'highstake-lite' ) != get_the_title() ) ) { ?>
					<?php the_title(); ?>
				<?php } else { ?>
					<?php echo esc_attr( $url ); ?>
				<?php }	?>
			</a>
		</h2>
	<?php
	}

}
endif;

if ( ! function_exists( 'highstake_lite_footer_text' ) ) :
/**
 * Footer Text
 *
 * @since  1.0.0
 */
function highstake_lite_footer_text() {

	// Get the customizer data
	$footer_text = '&copy; Copyright ' . date( 'Y' ) . ' - <a href="' . esc_url( home_url() ) . '">' . esc_attr( get_bloginfo( 'name' ) ) . '</a>. All Rights Reserved. <br /> Designed and Developed by <a href="http://www.theme-junkie.com/">Theme Junkie</a>';

	// Display the data
	echo '<p class="copyright">' . wp_kses_post( $footer_text ) . '</p>';

}
endif;
