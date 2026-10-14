<?php
/**
 * Lumina Dental theme setup.
 * Content types, clinic settings, schema and the booking handler live in the
 * Lumina Clinic Core plugin; this theme is the presentation layer.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'LUMINA_THEME_VERSION', '1.0.0' );

if ( ! defined( 'LUMINA_CORE_VERSION' ) ) {
	add_action( 'admin_notices', function () {
		echo '<div class="notice notice-error"><p><strong>Lumina Dental</strong> needs the <em>Lumina Clinic Core</em> plugin. Activate it under Plugins.</p></div>';
	} );
	add_action( 'template_redirect', function () {
		wp_die( 'This site needs the Lumina Clinic Core plugin to be activated.', 'Setup required', array( 'response' => 503 ) );
	} );
	return;
}

require_once get_template_directory() . '/inc/icons.php';
require_once get_template_directory() . '/inc/helpers.php';
require_once get_template_directory() . '/inc/seo.php';
require_once get_template_directory() . '/inc/content.php';

add_action( 'after_setup_theme', function () {
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'html5', array( 'search-form', 'gallery', 'caption', 'style', 'script' ) );
	add_theme_support( 'responsive-embeds' );
	register_nav_menus( array( 'primary' => 'Primary menu' ) );
} );

/* ---- Assets ------------------------------------------------------------ */

/** Pick light/dark and restore Display preferences before first paint (no flash). */
add_action( 'wp_head', function () {
	?>
<script>
(function () {
	var d = document.documentElement;
	d.classList.add('js');
	try {
		var t = localStorage.getItem('lumina_theme');
		var h = new Date().getHours();
		d.dataset.theme = t || (h >= 7 && h < 18 ? 'light' : 'dark');
		var p = JSON.parse(localStorage.getItem('lumina_display') || '{}');
		if (p.readable) d.classList.add('readable');
		if (p.motion) d.classList.add('reduce-motion');
		if (p.contrast) d.classList.add('high-contrast');
		if (p.size) d.style.setProperty('--scale', [1, 1.125, 1.25][p.size] || 1);
	} catch (e) {
		d.dataset.theme = 'light';
	}
})();
</script>
	<?php
}, 1 );

add_action( 'wp_enqueue_scripts', function () {
	$dir = get_template_directory();
	$uri = get_template_directory_uri();
	wp_enqueue_style( 'lumina', $uri . '/assets/css/main.css', array(), filemtime( $dir . '/assets/css/main.css' ) );
	wp_enqueue_script( 'lumina', $uri . '/assets/js/app.js', array(), filemtime( $dir . '/assets/js/app.js' ), array( 'strategy' => 'defer', 'in_footer' => true ) );

	// Editorial skin: restyles main.css on every page.
	wp_enqueue_style( 'lumina-editorial', $uri . '/assets/css/editorial.css', array( 'lumina' ), filemtime( $dir . '/assets/css/editorial.css' ) );

	// Editorial sections (index, stats, features, video band, inner-page hero…) are shared by many pages.
	wp_enqueue_style( 'lumina-home', $uri . '/assets/css/home.css', array( 'lumina-editorial' ), filemtime( $dir . '/assets/css/home.css' ) );
	wp_enqueue_script( 'lumina-home', $uri . '/assets/js/home.js', array(), filemtime( $dir . '/assets/js/home.js' ), array( 'strategy' => 'defer', 'in_footer' => true ) );
	// The scroll-lit statement only exists on the home page.
	if ( is_front_page() ) {
		wp_enqueue_script( 'lumina-statement', $uri . '/assets/js/statement.js', array(), filemtime( $dir . '/assets/js/statement.js' ), array( 'strategy' => 'defer', 'in_footer' => true ) );
	}

	$hours = array();
	foreach ( (array) lumina_opt( 'hours' ) as $k => $h ) {
		$hours[ $k ] = array( $h['open'] ?? '', $h['close'] ?? '' );
	}
	wp_add_inline_script( 'lumina', 'window.LUMINA=' . wp_json_encode( array(
		'endpoint' => admin_url( 'admin-post.php' ),
		'tz'       => wp_timezone_string(),
		'hours'    => $hours,
		'closed'   => lumina_closed_days(),
		'phone'    => lumina_opt( 'phone_display' ),
		'mapQuery' => lumina_map_query(),
	) ) . ';', 'before' );
} );

// Leaner head: no emoji loader, generator tag or shortlink.
remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'wp_print_styles', 'print_emoji_styles' );
remove_action( 'wp_head', 'wp_generator' );
remove_action( 'wp_head', 'wp_shortlink_wp_head' );
remove_action( 'wp_head', 'wlwmanifest_link' );
remove_action( 'wp_head', 'rsd_link' );

/** Archive pages show every service/area on one page, in the order set in the admin. */
add_action( 'pre_get_posts', function ( $q ) {
	if ( ! is_admin() && $q->is_main_query() && ( $q->is_post_type_archive( 'service' ) || $q->is_post_type_archive( 'area' ) ) ) {
		$q->set( 'posts_per_page', -1 );
		$q->set( 'orderby', 'menu_order title' );
		$q->set( 'order', 'ASC' );
	}
} );
