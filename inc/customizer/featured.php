<?php
/**
 * Featured Customizer
 */

/**
 * Register the customizer.
 */
function highstake_lite_featured_customize_register( $wp_customize ) {

	// Register new section: Featured
	$wp_customize->add_section( 'highstake_lite_featured' , array(
		'title'       => esc_html__( 'Featured', 'highstake-lite' ),
		'description' => esc_html__( 'This area called Featured, it appears at the top of the home page.', 'highstake-lite' ),
		'panel'       => 'highstake_lite_options',
		'priority'    => 9,
		'active_callback' => function() {
			return is_home() || is_front_page();
		}
	) );

	// Register featured type setting
	$wp_customize->add_setting( 'highstake_lite_featured_type', array(
		'default'           => 'default',
		'sanitize_callback' => 'highstake_lite_sanitize_featured_type',
	) );
	$wp_customize->add_control( 'highstake_lite_featured_type', array(
		'label'             => esc_html__( 'Type', 'highstake-lite' ),
		'section'           => 'highstake_lite_featured',
		'priority'          => 1,
		'type'              => 'radio',
		'choices'           => array(
			'disable' => esc_html__( 'Disable', 'highstake-lite' ),
			'default' => esc_html__( 'Default', 'highstake-lite' ),
			'posts'   => esc_html__( 'Posts Slider', 'highstake-lite' ),
			'custom'  => esc_html__( 'Custom Slider', 'highstake-lite' ),
		)
	) );

	// Register featured image setting
	$wp_customize->add_setting( 'highstake_lite_featured_default_img', array(
		'default'           => '',
		'sanitize_callback' => 'absint'
	) );
	$wp_customize->add_control( new WP_Customize_Cropped_Image_Control( $wp_customize, 'highstake_lite_featured_default_img', array(
		'label'             => esc_html__( 'Image', 'highstake-lite' ),
		'section'           => 'highstake_lite_featured',
		'priority'          => 3,
		'flex_width'        => true,
		'flex_height'       => true,
		'width'             => 1500,
		'height'            => 450,
		'active_callback'   => 'highstake_lite_is_featured_default'
	) ) );

	// Register featured text setting
	$wp_customize->add_setting( 'highstake_lite_featured_default_text', array(
		'default'           => '<small>Helping Small Business</small><h3>Grow</h3><p>It doesn\'t take a genius to start and build a successful business</p><a class="button button-primary" href="#">Start Here</a>',
		'sanitize_callback' => 'highstake_lite_sanitize_textarea',
	) );
	$wp_customize->add_control( 'highstake_lite_featured_default_text', array(
		'label'             => esc_html__( 'Text', 'highstake-lite' ),
		'section'           => 'highstake_lite_featured',
		'priority'          => 5,
		'type'              => 'textarea',
		'active_callback'   => 'highstake_lite_is_featured_default'
	) );

	// Register featured image setting
	$wp_customize->add_setting( 'highstake_lite_featured_posts_info', array(
		'default'           => '',
		'sanitize_callback' => 'esc_attr'
	) );
	$wp_customize->add_control( new Highstake_Custom_Text( $wp_customize, 'highstake_lite_featured_posts_info', array(
		'label'             => esc_html__( 'Featured Posts', 'highstake-lite' ),
		'description'       => sprintf( __( 'Use a <a href="%1$s">tag</a> to feature your posts. If no posts match the tag, <a href="%2$s">sticky posts</a> will be displayed instead.', 'highstake-lite' ),
				esc_url( add_query_arg( 'tag', _x( 'featured', 'featured content default tag slug', 'highstake-lite' ), admin_url( 'edit.php' ) ) ),
				admin_url( 'edit.php?show_sticky=1' )
			),
		'section'           => 'highstake_lite_featured',
		'priority'          => 7,
		'active_callback'   => 'highstake_lite_is_featured_posts'
	) ) );

	// Register featured posts tag name setting
	$wp_customize->add_setting( 'highstake_lite_featured_posts_tag', array(
		'default'           => 'featured',
		'sanitize_callback' => 'esc_attr',
	) );
	$wp_customize->add_control( 'highstake_lite_featured_posts_tag', array(
		'label'             => esc_html__( 'Tag name', 'highstake-lite' ),
		'section'           => 'highstake_lite_featured',
		'priority'          => 9,
		'type'              => 'text',
		'active_callback'   => 'highstake_lite_is_featured_posts'
	) );

	// Register number of posts setting
	$wp_customize->add_setting( 'highstake_lite_featured_posts_number', array(
		'default'           => 3,
		'sanitize_callback' => 'absint',
	) );
	$wp_customize->add_control( 'highstake_lite_featured_posts_number', array(
		'label'             => esc_html__( 'Number of posts', 'highstake-lite' ),
		'section'           => 'highstake_lite_featured',
		'priority'          => 11,
		'type'              => 'number',
		'input_attrs'       => array(
			'min'  => 0,
			'step' => 1
		),
		'active_callback'   => 'highstake_lite_is_featured_posts'
	) );

	// Register featured custom slider shortcode setting
	$wp_customize->add_setting( 'highstake_lite_featured_custom', array(
		'default'           => '',
		'sanitize_callback' => 'esc_attr',
	) );
	$wp_customize->add_control( 'highstake_lite_featured_custom', array(
		'label'             => esc_html__( 'Shortcode', 'highstake-lite' ),
		'description'       => esc_html__( 'You can use any 3rd party slider that support shortcode, place the shortcode in the setting below.', 'highstake-lite' ),
		'section'           => 'highstake_lite_featured',
		'priority'          => 13,
		'type'              => 'text',
		'active_callback'   => 'highstake_lite_is_featured_custom'
	) );

}
add_action( 'customize_register', 'highstake_lite_featured_customize_register' );

/**
 * Active callback option if featured is default
 */
function highstake_lite_is_featured_default() {

	$option = get_theme_mod( 'highstake_lite_featured_type', 'default' );

	if ( $option == 'default' ) {
		return true;
	} else {
		return false;
	}

}

/**
 * Active callback option if featured is posts
 */
function highstake_lite_is_featured_posts() {

	$option = get_theme_mod( 'highstake_lite_featured_type', 'default' );

	if ( $option == 'posts' ) {
		return true;
	} else {
		return false;
	}

}

/**
 * Active callback option if featured is custom
 */
function highstake_lite_is_featured_custom() {

	$option = get_theme_mod( 'highstake_lite_featured_type', 'default' );

	if ( $option == 'custom' ) {
		return true;
	} else {
		return false;
	}

}
