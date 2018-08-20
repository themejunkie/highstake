<?php
/**
 * Highstake Theme Customizer
 */

// Loads custom control
require trailingslashit( get_template_directory() ) . 'inc/customizer/controls/custom-text.php';

// Loads the customizer settings
require trailingslashit( get_template_directory() ) . 'inc/customizer/general.php';
require trailingslashit( get_template_directory() ) . 'inc/customizer/post.php';
require trailingslashit( get_template_directory() ) . 'inc/customizer/page.php';
require trailingslashit( get_template_directory() ) . 'inc/customizer/footer.php';
require trailingslashit( get_template_directory() ) . 'inc/customizer/featured.php';
require trailingslashit( get_template_directory() ) . 'inc/customizer/callout.php';
require trailingslashit( get_template_directory() ) . 'inc/customizer/fonts.php';
require trailingslashit( get_template_directory() ) . 'inc/customizer/colors.php';
require trailingslashit( get_template_directory() ) . 'inc/customizer/layouts.php';

/**
 * Custom customizer functions.
 */
function highstake_lite_customize_functions( $wp_customize ) {

	// Register new panel: Design
	$wp_customize->add_panel( 'highstake_lite_design', array(
		'title'       => esc_html__( 'Theme Design', 'highstake-lite' ),
		'description' => esc_html__( 'This panel is used for customizing the design of your site.', 'highstake-lite' ),
		'priority'    => 125,
	) );

	// Register new panel: Theme Options
	$wp_customize->add_panel( 'highstake_lite_options', array(
		'title'       => esc_html__( 'Theme Options', 'highstake-lite' ),
		'description' => esc_html__( 'This panel is used for customizing the Highstake theme.', 'highstake-lite' ),
		'priority'    => 130,
	) );

	// Live preview of Site Title
	$wp_customize->get_setting( 'blogname' )->transport = 'postMessage';

	// Enable selective refresh to the Site Title
	if ( isset( $wp_customize->selective_refresh ) ) {
		$wp_customize->selective_refresh->add_partial( 'blogname', array(
			'selector'            => '.site-title a',
			'settings'         => array( 'blogname' ),
			'render_callback'  => function() {
				return get_bloginfo( 'name', 'display' );
			}
		) );
	}

	// Move the Colors section.
	$wp_customize->get_section( 'colors' )->panel    = 'highstake_lite_design';
	$wp_customize->get_section( 'colors' )->priority = 1;

	// Move the Background Image section.
	$wp_customize->get_section( 'background_image' )->panel    = 'highstake_lite_design';
	$wp_customize->get_section( 'background_image' )->priority = 7;

	// Move the Additional CSS section.
	$wp_customize->get_section( 'custom_css' )->panel    = 'highstake_lite_design';
	$wp_customize->get_section( 'custom_css' )->priority = 9;

	// Move background color to background image section.
	$wp_customize->get_section( 'background_image' )->title = esc_html__( 'Background', 'highstake-lite' );
	$wp_customize->get_control( 'background_color' )->section = 'background_image';


}
add_action( 'customize_register', 'highstake_lite_customize_functions', 99 );

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function highstake_lite_customize_preview_js() {
	wp_enqueue_script( 'highstake-customizer', get_template_directory_uri() . '/assets/js/customizer/customizer.js', array( 'customize-preview', 'jquery' ) );
}
add_action( 'customize_preview_init', 'highstake_lite_customize_preview_js' );

/**
 * Display theme documentation on customizer page.
 */
function highstake_lite_documentation_link() {

	// Enqueue the script
	wp_enqueue_script( 'highstake-doc', get_template_directory_uri() . '/assets/js/customizer/doc.js', array(), '1.0.0', true );

	// Localize the script
	wp_localize_script( 'highstake-doc', 'prefixL10n',
		array(
			'prefixURL'   => esc_url( 'http://docs.theme-junkie.com/highstake/' ),
			'prefixLabel' => esc_html__( 'Documentation', 'highstake-lite' ),
		)
	);

}
add_action( 'customize_controls_enqueue_scripts', 'highstake_lite_documentation_link' );

/**
 * Custom styles.
 */
function highstake_lite_custom_css() {

	// Set up empty variable.
	$css = '';

	// Get the customizer value.
	$color           = get_theme_mod( 'highstake_lite_accent_color', '#54e5b0' );
	$heading_font    = get_theme_mod( 'highstake_lite_heading_font_family', '\'Montserrat\', sans-serif' );
	$body_font       = get_theme_mod( 'highstake_lite_body_font_family', '\'Karla\', sans-serif' );

	if ( $color != '#54e5b0' ) {
		$css .= '
		.button-primary, .menu-primary-items .sub-menu, .author-badge, .tag-links a:hover, .search-toggle:hover, .widget_tag_cloud a, .social-links, .subscribe-box, .page-header {
			background-color: ' . sanitize_hex_color( $color ) . ';
		}

		a, a:visited, a:hover, .menu-primary-items a:hover, .site-branding a:hover, h2.entry-title a:hover, .page-title a:hover, .post-pagination .post-detail span, .entry-meta a:hover, .pagination .page-numbers.current, .pagination .page-numbers:hover, .info-right .to-top:hover, .page-header .cat-link a:hover, .page-header .entry-meta a:hover, .author-bio .name a:hover, .comment-avatar .name a:hover, .home .page-header .page-title a:hover, .posts-thumbnail-widget .post-title:hover, .slicknav_menu .slicknav_nav a:hover {
			color: ' . sanitize_hex_color( $color ) . ';
		}

		 .widget_tabs .tabs-nav li.active a, blockquote, .menu-primary-items li:hover {
			border-color: ' . sanitize_hex_color( $color ) . ';
		}
		';
	}

	if ( $heading_font != '\'Montserrat\', sans-serif' ) {
		$css .= '
			h1, h2, h3, h4, h5, h6, .entry-author {
				font-family: ' . esc_attr( $heading_font ) . ';
			}
		';
	}

	if ( $body_font != '\'Karla\', sans-serif' ) {
		$css .= '
			body {
				font-family: ' . esc_attr( $body_font ) . ';
			}
		';
	}

	// Print the custom style
	wp_add_inline_style( 'highstake-style', $css );

}
add_action( 'wp_enqueue_scripts', 'highstake_lite_custom_css' );

/**
 * Sanitize the checkbox.
 *
 * @param boolean $input.
 * @return boolean (true|false).
 */
function highstake_lite_sanitize_checkbox( $input ) {
	if ( 1 == $input ) {
		return true;
	} else {
		return false;
	}
}

/**
 * Sanitize the Footer Credits
 */
function highstake_lite_sanitize_textarea( $text ) {
	if ( current_user_can( 'unfiltered_html' ) ) {
		$text = $text;
	} else {
		$text = wp_kses_post( $text );
	}
	return $text;
}

/**
 * Sanitize the container style value.
 */
function highstake_lite_sanitize_container_style( $style ) {
	if ( ! in_array( $style, array( 'fullwidth', 'boxed', 'framed' ) ) ) {
		$style = 'fullwidth';
	}
	return $style;
}

/**
 * Sanitize the header style value.
 */
function highstake_lite_sanitize_header_style( $style ) {
	if ( ! in_array( $style, array( 'left', 'center', 'right' ) ) ) {
		$style = 'left';
	}
	return $style;
}

/**
 * Sanitize the pagination type value.
 */
function highstake_lite_sanitize_posts_pagination( $type ) {
	if ( ! in_array( $type, array( 'number', 'traditional' ) ) ) {
		$type = 'number';
	}
	return $type;
}

/**
 * Sanitize the footer widget columns value.
 */
function highstake_lite_sanitize_footer_widget_column( $col ) {
	if ( ! in_array( $col, array( '3', '4', '6' ) ) ) {
		$col = '6';
	}
	return $col;
}

/**
 * Sanitize the featured type value.
 */
function highstake_lite_sanitize_featured_type( $type ) {
	if ( ! in_array( $type, array( 'disable', 'default', 'posts', 'custom' ) ) ) {
		$type = 'default';
	}
	return $type;
}

/**
 * Sanitize the footer content value.
 */
function highstake_lite_sanitize_footer_content( $type ) {
	if ( ! in_array( $type, array( 'disable', 'logo', 'custom' ) ) ) {
		$type = 'logo';
	}
	return $type;
}

/**
 * Sanitize blog layouts value.
 */
function highstake_lite_sanitize_blog_layouts( $layout ) {
	if ( ! in_array( $layout,
			array(
				'2c-l',
				'2c-r',
				'1c-n',
				'2c-l-l',
				'2c-r-l',
				'1c-l',
				'1c-n-l'
			)
		)
	) {
		$layout = 'default';
	}
	return $layout;
}

/**
 * Sanitize the callout type value.
 */
function highstake_lite_sanitize_callout_type( $type ) {
	if ( ! in_array( $type, array( 'subscribe', 'posts' ) ) ) {
		$type = 'subscribe';
	}
	return $type;
}
