<?php
/**
 * Boron 1.0 functions and definitions
 *
 * Set up the theme and provides some helper functions, which are used in the
 * theme as custom template tags. Others are attached to action and filter
 * hooks in WordPress to change core functionality.
 *
 * When using a child theme you can override certain functions (those wrapped
 * in a function_exists() call) by defining them first in your child theme's
 * functions.php file. The child theme's functions.php file is included before
 * the parent theme's file, so the child theme functions would be used.
 *
 * @link http://codex.wordpress.org/Theme_Development
 * @link http://codex.wordpress.org/Child_Themes
 *
 * Functions that are not pluggable (not wrapped in function_exists()) are
 * instead attached to a filter or action hook.
 *
 * For more information on hooks, actions, and filters,
 * @link http://codex.wordpress.org/Plugin_API
 *
 * @package WordPress
 * @subpackage Boron
 * @since Boron 1.0
 */

/**
 * Set up the content width value based on the theme's design.
 *
 * @see boron_content_width()
 *
 * @since Boron 1.0
 */
if ( ! isset( $content_width ) ) {
	$content_width = 800;
}

/**
 * Boron 1.0 only works in WordPress 3.6 or later.
 */
if ( version_compare( $GLOBALS['wp_version'], '3.6', '<' ) ) {
	require get_template_directory() . '/inc/back-compat.php';
}

if ( ! function_exists( 'boron_setup' ) ) :
	/**
	 * Boron 1.0 setup.
	 *
	 * Set up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support post thumbnails.
	 *
	 * @since Boron 1.0
	 */
	function boron_setup() {
		/**
		 * Required: include TGM.
		 */
		require_once( get_template_directory() . '/functions/tgm-activation/class-tgm-plugin-activation.php' );

		/*
		 * Make Boron 1.0 available for translation.
		 *
		 * Translations can be added to the /languages/ directory.
		 * If you're building a theme based on Boron 1.0, use a find and
		 * replace to change 'boron' to the name of your theme in all
		 * template files.
		 */
		load_theme_textdomain( 'boron', get_template_directory() . '/languages' );

		// This theme styles the visual editor to resemble the theme style.
		add_editor_style( array( 'css/editor-style.css' ) );

		// Add RSS feed links to <head> for posts and comments.
		add_theme_support( 'automatic-feed-links' );

		// Enable support for Post Thumbnails, and declare two sizes.
		add_theme_support( 'post-thumbnails' );
		set_post_thumbnail_size( 672, 372, true );
		add_image_size( 'boron-small-thumbnail', 70, 70, true );
		add_image_size( 'boron-full-width', 1170, 400, true );
		add_image_size( 'boron-thumbnail', 490, 318, true );
		add_image_size( 'boron-thumbnail-large', 650, 411, true );
		add_image_size( 'boron-medium-thumbnail', 350, 350, false );
		add_image_size( 'boron-related-thumbnail', 255, 170, true );

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support( 'html5', array(
			'search-form', 'comment-form', 'comment-list',
		) );

		/*
		 * Enable support for Post Formats.
		 * See http://codex.wordpress.org/Post_Formats
		 */
		add_theme_support( 'post-formats', array(
			'aside', 'image', 'video', 'audio', 'quote', 'link', 'gallery',
		) );

		// This theme allows users to set a custom background.
		add_theme_support( 'custom-background', apply_filters( 'boron_custom_background_args', array(
			'default-color' => 'fff',
		) ) );

		// This theme uses its own gallery styles.
		add_filter( 'use_default_gallery_style', '__return_false' );

		add_theme_support( 'title-tag' );
		add_theme_support( 'custom-header', array( 'width' => '128', 'height' => '128' ) );
	}
endif; // boron_setup
add_action( 'after_setup_theme', 'boron_setup' );

// Admin CSS
function vh_admin_css() {
	wp_enqueue_style( 'vh-admin-css', get_template_directory_uri() . '/css/wp-admin.css' );
}
add_action('admin_head','vh_admin_css');

function boron_tag_list( $post_id, $return = false ) {
	$entry_utility = '';
	$posttags = get_the_tags( $post_id );
	if ( $posttags ) {
		$entry_utility .= '
		<div class="tag-link">
			<span class="icon-tags"></span>';
				foreach( $posttags as $tag ) {
					$entry_utility .= $tag->name . ' '; 
				}
			$entry_utility .= '
		</div>';
	}

	if ( $return ) {
		return $entry_utility;
	} else {
		echo $entry_utility;
	}
}

function boron_tag_link_list( $post_id, $return = false ) {
	$entry_utility = '';
	$posttags = get_the_tags( $post_id );
	if ( $posttags ) {
		$entry_utility .= '
		<div class="tag-link">
				<span class="tag-text">' . __('Tags', 'boron') . '</span>';
				foreach( $posttags as $tag ) {
					$entry_utility .= '<a href="' . get_tag_link($tag->term_id) . '" class="open-tag">' . $tag->name . '</a> '; 
				}
			$entry_utility .= '
		</div>';
	}

	if ( $return ) {
		return $entry_utility;
	} else {
		echo $entry_utility;
	}
}

function boron_category_list( $post_id, $return = false ) {
	$category_list = get_the_category_list( ', ', '', $post_id );
	$entry_utility = '';
	if ( $category_list ) {
		$entry_utility .= '
		<div class="category-link">
			<span class="entypo_icon icon-folder-open"></span>' . $category_list . '
		</div>';
	}

	if ( $return ) {
		return $entry_utility;
	} else {
		echo $entry_utility;
	}
}

function boron_category_link_list( $post_id, $return = false ) {
	$category_list = get_the_category_list( ', ', '', $post_id );
	$entry_utility = '';
	if ( $category_list ) {
		$entry_utility .= '
		<div class="category-link">
			<span class="category-text">' . __('Categories', 'boron') . '</span>' . $category_list . '
		</div>';
	}

	if ( $return ) {
		return $entry_utility;
	} else {
		echo $entry_utility;
	}
}

function boron_comment_count( $post_id ) {
	$comments = wp_count_comments($post_id); 
	return $comments->approved;
}

/**
 * Adjust content_width value for image attachment template.
 *
 * @since Boron 1.0
 *
 * @return void
 */
function boron_content_width() {
	if ( is_attachment() && wp_attachment_is_image() ) {
		$GLOBALS['content_width'] = 810;
	}
}
add_action( 'template_redirect', 'boron_content_width' );

/**
 * Prevent page scroll when clicking the More link
 *
 * @since Boron 1.0
 *
 * @return void
 */
function remove_more_link_scroll( $link ) {
	$link = preg_replace( '|#more-[0-9]+|', '', $link );
	return $link;
}
add_filter( 'the_content_more_link', 'remove_more_link_scroll' );

/**
 * Register Lato Google font for Boron 1.0.
 *
 * @since Boron 1.0
 *
 * @return string
 */
function boron_font_url() {
	$font_url = '';
	/*
	 * Translators: If there are characters in your language that are not supported
	 * by Lato, translate this to 'off'. Do not translate into your own language.
	 */
	$font_url = add_query_arg( 'family', urlencode( 'Open+Sans:400,100,300' ), "//fonts.googleapis.com/css" );

	return $font_url;
}

function boron_excerpt_length( $length ) {
	return 20;
}
add_filter( 'excerpt_length', 'boron_excerpt_length', 999 );

/**
 * Enqueue scripts and styles for the front end.
 *
 * @since Boron 1.0
 *
 * @return void
 */
function boron_scripts() {

	wp_enqueue_style( 'bootstrap', get_template_directory_uri() . '/css/bootstrap.css', array() );

	// Add Google fonts
	wp_register_style('googleFonts', '//fonts.googleapis.com/css?family=Proxima+Nova:300,400,600,700&subset=latin');
	wp_enqueue_style( 'googleFonts');

	// Add Genericons font, used in the main stylesheet.
	wp_enqueue_style( 'genericons', get_template_directory_uri() . '/genericons/genericons.css', array(), '3.0.2' );

	// Load our main stylesheet.
	wp_enqueue_style( 'boron-style', get_stylesheet_uri(), array( 'genericons' ) );

	// Load the Internet Explorer specific stylesheet.
	wp_enqueue_style( 'boron-ie', get_template_directory_uri() . '/css/ie.css', array( 'boron-style', 'genericons' ), '20131205' );
	wp_style_add_data( 'boron-ie', 'conditional', 'lt IE 9' );

	wp_enqueue_script( 'comment-reply' );

	wp_enqueue_script( 'boron-script', get_template_directory_uri() . '/js/functions.js', array( 'jquery' ), '20131209', true );
	wp_enqueue_script( 'bootstrap', get_template_directory_uri() . '/js/bootstrap.js', array( 'jquery' ), '20131209', true );

	wp_enqueue_style( 'animate', get_template_directory_uri() . '/css/animate.min.css', array() );

	wp_enqueue_script( 'jquery.isotope', get_template_directory_uri() . '/js/jquery.isotope.min.js', array( 'jquery' ), '', true );
	wp_enqueue_script( 'jquery.imagesloaded', get_template_directory_uri() . '/js/imagesloaded.pkgd.min.js', array( 'jquery' ), '', true );
	

	wp_enqueue_script( 'jquery-ui-draggable' );

	wp_localize_script(
		'boron-script',
		'WP_API_Settings',
		array(
			'root'           => esc_url_raw( rest_url() ),
			'nonce'          => wp_create_nonce( 'wp_rest' ),
			'posts_per_page' => get_option('posts_per_page'),
			'post_comments'  => get_theme_mod('boron_comment_location', 'side'),
			'home_url'       => home_url(),
			'dates'          => boron_get_archive(),
			'post_tax'       => boron_get_post_tax()
		)
	);

	wp_add_inline_style( 'boron-style', boron_set_grid_size() );
}
add_action( 'wp_enqueue_scripts', 'boron_scripts' );

function boron_set_grid_size() {
	$column_count = get_theme_mod( 'boron_grid_columns', '4' );

	$column_width = 100/(int)$column_count;

	return '.main-content article { width: ' . $column_width . '%; }';
}

add_action( 'rest_api_init', 'boron_register_extra_filters' );
function boron_register_extra_filters() {
    register_api_field( 'post',
        'boron_extra',
        array(
            'get_callback'    => 'boron_get_extra_fields',
            'update_callback' => null,
            'schema'          => null,
        )
    );
}

function boron_get_extra_fields( $object, $field_name, $request ) {
	if ( $object['featured_image'] ) {
		$image_id = (int)$object['featured_image'];
		$img = wp_get_attachment_image_src( $object['featured_image'], 'boron-medium-thumbnail' );
		$image_src = $img['0'];
	} else {
		$image_src = null;
	}

	$extra = array();

	$extra['image_src'] = $image_src;
	$extra['tag_list'] = boron_tag_list( $object['id'], true );
	$extra['date_ago'] = human_time_diff(get_the_time('U', $object['id']), current_time('timestamp')) .  ' '.__('ago', 'boron');
	$extra['comments'] = boron_comment_count( $object['id'] );
	$extra['post_template'] = boron_get_single_post( $object['id'] );
	$extra['post_side_template'] = boron_get_single_post_side( $object['id'] );
	$extra['post_classes'] = implode( ' ', get_post_class('', $object['id'] ) );

    return $extra;
}

function boron_get_single_post( $post_id ) {
	$output = '';

	// Check if thumbnail exists
	$img = wp_get_attachment_image_src( get_post_thumbnail_id( $post_id ), 'boron-full-width' );
	if ( !empty($img) ) {
		$output .= '<div class="open-post-image"><img src="'.$img['0'].'" class="single-open-post-image" alt="Post with image"></div>';
	}

	$output .= '<h1>' . get_the_title( $post_id ) . '</h1>';

	$content_post = get_post( $post_id );
	$content = $content_post->post_content;
	$content = apply_filters('the_content', $content);
	$content = str_replace(']]>', ']]&gt;', $content);
	$content = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', "", $content);

	// Sets the post content
	if ( post_password_required( $post_id ) ) {
		$output .= get_the_password_form( $post_id );
	} else {
		$output .= $content;
	}

	global $withcomments;
	$withcomments = 1;

	ob_start();
	comments_template();
	$comment_form = ob_get_clean();
	$comment_form = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', "", $comment_form);

	$output .= '<div class="clearfix"></div><div class="comments-container">' . $comment_form . '</div>';

	return $output;
}

function boron_get_single_post_side( $post_id ) {
	$output = '';
	$date = human_time_diff(get_the_time('U', $post_id), current_time('timestamp')) .  ' '.__('ago', 'boron');
	$comments = boron_comment_count( $post_id );

	$output .= '<span class="single-open-posted">' . __('Posted', 'boron') . '</span>';
	$output .= '<span class="single-open-posted-date">' . $date . '</span>';

	$output .= boron_tag_link_list( $post_id, true );

	$output .= boron_category_link_list( $post_id, true );

	if ( comments_open( $post_id ) ) {
		$output .= '<div class="side-comments"><span class="single-open-comment">' . __('Comments', 'boron') . '</span>';
		$output .= '<span class="single-open-comment-count">' . $comments . '</span></div>';
	}

	$output .= '<div class="single-post-share">
					<a href="http://twitter.com/share?url=' . get_the_permalink( $post_id ) . '&amp;text=' . urlencode( get_the_title( $post_id ) ) . '" class="social-icon icon-twitter" target="_blank"></a>
					<a href="http://www.facebook.com/sharer.php?u=' . get_the_permalink( $post_id ) . '" class="social-icon icon-facebook" target="_blank"></a>
					<a href="https://plus.google.com/share?url=' . get_the_permalink( $post_id ) . '" class="social-icon icon-gplus" target="_blank"></a>
				</div>';

	return $output;
}

function boron_get_archive() {
	$dates = array( 'year' => '', 'monthnum' => '', 'day' => '' );
	if ( !is_date() ) {
		return $dates;
	}

	$dates['year'] = get_query_var('year');
	$dates['monthnum'] = get_query_var('monthnum');
	$dates['day'] = get_query_var('day');

	return $dates;
}

function boron_get_post_tax() {
	if ( isset( $GLOBALS['wp_query']->queried_object->term_id ) ) {
		$term_id = $GLOBALS['wp_query']->queried_object->term_id;
	} else {
		$term_id = '';
	}

	$tax_arrays = array(
		array(
			'field'=>'term_id',
			'taxonomy'=>'post_format',
			'terms'=> $term_id
		)
	);

	return serialize($tax_arrays);
}

// Admin Javascript
add_action( 'admin_enqueue_scripts', 'boron_admin_scripts' );
function boron_admin_scripts() {
	wp_register_script('master', get_template_directory_uri() . '/inc/js/admin-master.js', array('jquery'));
	wp_enqueue_script('master');
}

if ( ! function_exists( 'boron_the_attached_image' ) ) :
	/**
	 * Print the attached image with a link to the next attached image.
	 *
	 * @since Boron 1.0
	 *
	 * @return void
	 */
	function boron_the_attached_image() {
		$post                = get_post();
		/**
		 * Filter the default Boron 1.0 attachment size.
		 *
		 * @since Boron 1.0
		 *
		 * @param array $dimensions {
		 *     An array of height and width dimensions.
		 *
		 *     @type int $height Height of the image in pixels. Default 810.
		 *     @type int $width  Width of the image in pixels. Default 810.
		 * }
		 */
		$attachment_size     = apply_filters( 'boron_attachment_size', array( 810, 810 ) );
		$next_attachment_url = wp_get_attachment_url();

		/*
		 * Grab the IDs of all the image attachments in a gallery so we can get the URL
		 * of the next adjacent image in a gallery, or the first image (if we're
		 * looking at the last image in a gallery), or, in a gallery of one, just the
		 * link to that image file.
		 */
		$attachment_ids = get_posts( array(
			'post_parent'    => $post->post_parent,
			'fields'         => 'ids',
			'numberposts'    => -1,
			'post_status'    => 'inherit',
			'post_type'      => 'attachment',
			'post_mime_type' => 'image',
			'order'          => 'ASC',
			'orderby'        => 'menu_order ID',
		) );

		// If there is more than 1 attachment in a gallery...
		if ( count( $attachment_ids ) > 1 ) {
			foreach ( $attachment_ids as $attachment_id ) {
				if ( $attachment_id == $post->ID ) {
					$next_id = current( $attachment_ids );
					break;
				}
			}

			// get the URL of the next image attachment...
			if ( $next_id ) {
				$next_attachment_url = get_attachment_link( $next_id );
			}

			// or get the URL of the first image attachment.
			else {
				$next_attachment_url = get_attachment_link( array_shift( $attachment_ids ) );
			}
		}

		printf( '<a href="%1$s" rel="attachment">%2$s</a>',
			esc_url( $next_attachment_url ),
			wp_get_attachment_image( $post->ID, $attachment_size )
		);
	}
endif;

/**
 * Extend the default WordPress body classes.
 *
 * Adds body classes to denote:
 * 1. Single or multiple authors.
 * 2. Presence of header image.
 * 3. Index views.
 * 5. Presence of footer widgets.
 * 6. Single views.
 * 7. Featured content layout.
 *
 * @since Boron 1.0
 *
 * @param array $classes A list of existing body class values.
 * @return array The filtered body class list.
 */
function boron_body_classes( $classes ) {
	global $post;
	$boron_layout = '';

	$post_comments = get_theme_mod('boron_comment_location');
	if ( $post_comments == 'side' ) {
		$classes[] = 'post-side-comments';
	} elseif ( $post_comments == 'bottom' ) {
		$classes[] = 'post-bottom-comments';
	}

	if ( is_single() || is_page() ) {
		$classes[] = 'customize-support pull-content-to-side pull-content-to-side-ended';
	}

	return $classes;
}
add_filter( 'body_class', 'boron_body_classes' );

/**
 * Create a nicely formatted and more specific title element text for output
 * in head of document, based on current view.
 *
 * @since Boron 1.0
 *
 * @param string $title Default title text for current view.
 * @param string $sep Optional separator.
 * @return string The filtered title.
 */
function boron_wp_title( $title, $sep ) {
	global $paged, $page;

	if ( is_feed() ) {
		return $title;
	}

	// Add the site name.
	$title .= get_bloginfo( 'name' );

	// Add the site description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) ) {
		$title = "$title $sep $site_description";
	}

	// Add a page number if necessary.
	if ( $paged >= 2 || $page >= 2 ) {
		$title = "$title $sep " . sprintf( __( 'Page %s', 'boron' ), max( $paged, $page ) );
	}

	return $title;
}
add_filter( 'wp_title', 'boron_wp_title', 10, 2 );

// Custom template tags for this theme.
require get_template_directory() . '/inc/template-tags.php';

// Add Theme Customizer functionality.
require get_template_directory() . '/inc/customizer.php';

function get_depth($postid) {
	$depth = ($postid==get_option('page_on_front')) ? -1 : 0;
	while ($postid > 0) {
	$postid = get_post_ancestors($postid);
	$postid = $postid[0];
	$depth++;
	}
	return $depth;
}

function boron_navigation_link( $text, $url ) {
	if ( !$text && !$url ) {
		return false;
	}

	return '<a href="' . $url . '" class="social-button" target="_blank">' . $text . '</a>';
}

function boron_navigation_social() {
	$button1 = boron_navigation_link( get_theme_mod('boron_nav_button1_text'), get_theme_mod('boron_nav_button1_link') );
	$button2 = boron_navigation_link( get_theme_mod('boron_nav_button2_text'), get_theme_mod('boron_nav_button2_link') );
	$button3 = boron_navigation_link( get_theme_mod('boron_nav_button3_text'), get_theme_mod('boron_nav_button3_link') );
	$button4 = boron_navigation_link( get_theme_mod('boron_nav_button4_text'), get_theme_mod('boron_nav_button4_link') );
	$social = '';

	if ( $button1 || $button2 || $button3 || $button4 ) {
		$social .= '<div class="navigation-social"><h6>'.__('Follow us', 'boron') . ':</h6>';
			if ( $button1 ) {
				$social .= $button1;
			}
			if ( $button2 ) {
				$social .= $button2;
			}
			if ( $button3 ) {
				$social .= $button3;
			}
			if ( $button4 ) {
				$social .= $button4;
			}
		$social .= '</div>';
	}

	return $social;
}

/**
 * Register the required plugins for this theme.
 *
 * In this example, we register two plugins - one included with the TGMPA library
 * and one from the .org repo.
 *
 * The variable passed to tgmpa_register_plugins() should be an array of plugin
 * arrays.
 *
 * This function is hooked into tgmpa_init, which is fired within the
 * TGM_Plugin_Activation class constructor.
 */
function vh_register_required_plugins() {

	/**
	 * Array of plugin arrays. Required keys are name and slug.
	 * If the source is NOT from the .org repo, then source is also required.
	 */
	$plugins = array(
		array(
			'name'     				=> 'Bootstrap 3 Shortcodes', // The plugin name
			'slug'     				=> 'bootstrap-3-shortcodes', // The plugin slug (typically the folder name)
			'required' 				=> false, // If false, the plugin is only 'recommended' instead of required
			'version' 				=> '3.3.6', // E.g. 1.0.0. If set, the active plugin must be this version or higher, otherwise a notice is presented
			'force_activation' 		=> false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch
			'force_deactivation' 	=> false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins
			'external_url' 			=> '', // If set, overrides default API URL and points to an external URL
		),
		array(
			'name'     				=> 'WordPress REST API (Version 2)', // The plugin name
			'slug'     				=> 'rest-api', // The plugin slug (typically the folder name)
			'required' 				=> false, // If false, the plugin is only 'recommended' instead of required
			'version' 				=> '2.0-beta7', // E.g. 1.0.0. If set, the active plugin must be this version or higher, otherwise a notice is presented
			'force_activation' 		=> false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch
			'force_deactivation' 	=> false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins
			'external_url' 			=> '', // If set, overrides default API URL and points to an external URL
		)
	);

	/**
	 * Array of configuration settings. Amend each line as needed.
	 * If you want the default strings to be available under your own theme domain,
	 * leave the strings uncommented.
	 * Some of the strings are added into a sprintf, so see the comments at the
	 * end of each line for what each argument will be.
	 */
	$config = array(
		'domain'       		=> 'boron',         	// Text domain - likely want to be the same as your theme.
		'default_path' 		=> '',                         	// Default absolute path to pre-packaged plugins
		'parent_menu_slug' 	=> 'themes.php', 				// Default parent menu slug
		'parent_url_slug' 	=> 'themes.php', 				// Default parent URL slug
		'menu'         		=> 'install-required-plugins', 	// Menu slug
		'has_notices'      	=> true,                       	// Show admin notices or not
		'is_automatic'    	=> true,					   	// Automatically activate plugins after installation or not
		'message' 			=> '',							// Message to output right before the plugins table
		'strings'      		=> array(
			'page_title'                       			=> __( 'Install Required Plugins', 'boron' ),
			'menu_title'                       			=> __( 'Install Plugins', 'boron' ),
			'installing'                       			=> __( 'Installing Plugin: %s', 'boron' ), // %1$s = plugin name
			'oops'                             			=> __( 'Something went wrong with the plugin API.', 'boron' ),
			'notice_can_install_required'     			=> _n_noop( 'This theme requires the following plugin: %1$s.', 'This theme requires the following plugins: %1$s.', 'boron' ), // %1$s = plugin name(s)
			'notice_can_install_recommended'			=> _n_noop( 'This theme recommends the following plugin: %1$s.', 'This theme recommends the following plugins: %1$s.', 'boron' ), // %1$s = plugin name(s)
			'notice_cannot_install'  					=> _n_noop( 'Sorry, but you do not have the correct permissions to install the %s plugin. Contact the administrator of this site for help on getting the plugin installed.', 'Sorry, but you do not have the correct permissions to install the %s plugins. Contact the administrator of this site for help on getting the plugins installed.', 'boron' ), // %1$s = plugin name(s)
			'notice_can_activate_required'    			=> _n_noop( 'The following required plugin is currently inactive: %1$s.', 'The following required plugins are currently inactive: %1$s.', 'boron' ), // %1$s = plugin name(s)
			'notice_can_activate_recommended'			=> _n_noop( 'The following recommended plugin is currently inactive: %1$s.', 'The following recommended plugins are currently inactive: %1$s.', 'boron' ), // %1$s = plugin name(s)
			'notice_cannot_activate' 					=> _n_noop( 'Sorry, but you do not have the correct permissions to activate the %s plugin. Contact the administrator of this site for help on getting the plugin activated.', 'Sorry, but you do not have the correct permissions to activate the %s plugins. Contact the administrator of this site for help on getting the plugins activated.', 'boron' ), // %1$s = plugin name(s)
			'notice_ask_to_update' 						=> _n_noop( 'The following plugin needs to be updated to its latest version to ensure maximum compatibility with this theme: %1$s.', 'The following plugins need to be updated to their latest version to ensure maximum compatibility with this theme: %1$s.', 'boron' ), // %1$s = plugin name(s)
			'notice_cannot_update' 						=> _n_noop( 'Sorry, but you do not have the correct permissions to update the %s plugin. Contact the administrator of this site for help on getting the plugin updated.', 'Sorry, but you do not have the correct permissions to update the %s plugins. Contact the administrator of this site for help on getting the plugins updated.', 'boron' ), // %1$s = plugin name(s)
			'install_link' 					  			=> _n_noop( 'Begin installing plugin', 'Begin installing plugins', 'boron' ),
			'activate_link' 				  			=> _n_noop( 'Activate installed plugin', 'Activate installed plugins', 'boron' ),
			'return'                           			=> __( 'Return to Required Plugins Installer', 'boron' ),
			'plugin_activated'                 			=> __( 'Plugin activated successfully.', 'boron' ),
			'complete' 									=> __( 'All plugins installed and activated successfully. %s', 'boron' ), // %1$s = dashboard link
			'nag_type'									=> 'updated' // Determines admin notice type - can only be 'updated' or 'error'
		)
	);

	tgmpa( $plugins, $config );
}
add_action( 'tgmpa_register', 'vh_register_required_plugins' );

function boron_allowed_tags() {
	global $allowedposttags;
	$allowedposttags['script'] = array(
		'type' => true,
		'src' => true
	);
}
add_action( 'init', 'boron_allowed_tags' );