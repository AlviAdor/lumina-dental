<?php
/**
 * On-page SEO: city-qualified titles, meta descriptions and Open Graph tags.
 * Stands down if a dedicated SEO plugin is active so tags are never doubled.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function lumina_seo_plugin_active() {
	return defined( 'WPSEO_VERSION' ) || defined( 'RANK_MATH_VERSION' ) || defined( 'AIOSEO_VERSION' );
}

add_filter( 'pre_get_document_title', function ( $title ) {
	if ( lumina_seo_plugin_active() ) {
		return $title;
	}
	$clinic = lumina_opt( 'name' );
	$city   = lumina_opt( 'city' );
	if ( is_front_page() ) {
		return sprintf( '%s | Dentist in %s', $clinic, lumina_place() );
	}
	if ( is_singular( 'service' ) ) {
		return sprintf( '%s in %s | %s', get_the_title(), $city, $clinic );
	}
	if ( is_singular( 'area' ) ) {
		return sprintf( 'Dentist near %s, %s | %s', get_the_title(), $city, $clinic );
	}
	if ( is_post_type_archive( 'service' ) ) {
		return sprintf( 'Dental services in %s | %s', $city, $clinic );
	}
	if ( is_post_type_archive( 'area' ) ) {
		return sprintf( 'Areas we serve in %s | %s', $city, $clinic );
	}
	return $title;
} );

function lumina_meta_description() {
	$clinic = lumina_opt( 'name' );
	if ( is_front_page() ) {
		return sprintf(
			'%1$s: family dentist in %2$s. Check-ups, whitening, braces, implants, emergency care. Book online or call %3$s.',
			$clinic,
			lumina_place(),
			lumina_opt( 'phone_display' )
		);
	}
	if ( is_post_type_archive( 'service' ) ) {
		return sprintf( 'All dental treatments at %s in %s: check-ups, whitening, braces, implants, root canals, children\'s dentistry and emergency care.', $clinic, lumina_place() );
	}
	if ( is_post_type_archive( 'area' ) ) {
		return sprintf( '%s welcomes patients from across %s. See how to reach our dental clinic from your neighbourhood.', $clinic, lumina_opt( 'city' ) );
	}
	if ( is_singular() ) {
		$post = get_post();
		$text = $post->post_excerpt ?: wp_strip_all_tags( strip_shortcodes( $post->post_content ) );
		return wp_trim_words( $text, 28, '…' );
	}
	return '';
}

add_action( 'wp_head', function () {
	if ( lumina_seo_plugin_active() ) {
		return;
	}
	$desc = lumina_meta_description();
	if ( $desc ) {
		echo '<meta name="description" content="' . esc_attr( $desc ) . '">' . "\n";
		echo '<meta property="og:description" content="' . esc_attr( $desc ) . '">' . "\n";
	}
	$title = wp_get_document_title();
	echo '<meta property="og:title" content="' . esc_attr( $title ) . '">' . "\n";
	echo '<meta property="og:type" content="website">' . "\n";
	echo '<meta property="og:site_name" content="' . esc_attr( lumina_opt( 'name' ) ) . '">' . "\n";
	echo '<meta property="og:locale" content="' . esc_attr( get_locale() ) . '">' . "\n";
	if ( is_singular() ) {
		echo '<meta property="og:url" content="' . esc_url( get_permalink() ) . '">' . "\n";
	}
	echo '<meta name="theme-color" content="#0f766e">' . "\n";
}, 2 );
