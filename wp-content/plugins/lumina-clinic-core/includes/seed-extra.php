<?php
/**
 * Extra starter pages (Prices, FAQs, Privacy policy). Runs once per version on a normal
 * request, so sites that were activated before these pages existed still get them.
 * Existing pages are never changed: every page is looked up by slug first.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function lumina_seed_extra_pages() {
	$name  = lumina_opt( 'name' );
	$email = lumina_opt( 'email' );
	$pages = array(
		'prices'         => array( 'Prices', '<p>Starting prices for our treatments. Your exact cost is confirmed in writing after your examination.</p>' ),
		'faqs'           => array( 'Frequently asked questions', '<p>Quick answers about booking, treatment, costs, children and emergencies.</p>' ),
		'privacy-policy' => array(
			'Privacy policy',
			"<p><strong>This is a starter policy. Have it reviewed and edit it to match how $name actually handles information before you publish.</strong></p>"
			. "<h2>What we collect</h2><p>When you send an appointment request we collect your name, phone number, the treatment you are interested in, your preferred date and time, and optionally your email address and a short message. We use it only to reply to you and to arrange your care.</p>"
			. "<h2>How we store it</h2><p>Requests are stored securely on our website and sent to our clinic team. We do not sell your information or use it for advertising.</p>"
			. "<h2>Map and third-party content</h2><p>The map on our contact page only loads after you choose to show it. When it loads, Google may set its own cookies and receive your IP address under Google's privacy policy.</p>"
			. "<h2>Your choices</h2><p>You can ask us what we hold about you, ask us to correct it, or ask us to delete it.</p>"
			. "<h2>Contact</h2><p>Questions about this policy: <a href=\"mailto:$email\">$email</a>.</p>",
		),
	);
	// WordPress ships an unpublished placeholder privacy page full of "Suggested text".
	// If it is still that untouched draft, replace it with the starter policy and publish it.
	$wp_privacy = get_post( (int) get_option( 'wp_page_for_privacy_policy' ) );
	if ( $wp_privacy && 'draft' === $wp_privacy->post_status && false !== strpos( $wp_privacy->post_content, 'Suggested text' ) ) {
		wp_update_post( array(
			'ID'           => $wp_privacy->ID,
			'post_status'  => 'publish',
			'post_title'   => $pages['privacy-policy'][0],
			'post_content' => $pages['privacy-policy'][1],
		) );
	}
	foreach ( $pages as $slug => $p ) {
		if ( lumina_seed_find( $slug, 'page' ) ) {
			continue;
		}
		wp_insert_post( array(
			'post_type'    => 'page',
			'post_status'  => 'publish',
			'post_name'    => $slug,
			'post_title'   => $p[0],
			'post_content' => $p[1],
		) );
	}
	update_option( 'lumina_extra_seeded', LUMINA_CORE_VERSION . '-2' );
}

add_action( 'init', function () {
	if ( get_option( 'lumina_seeded' ) && get_option( 'lumina_extra_seeded' ) !== LUMINA_CORE_VERSION . '-2' ) {
		lumina_seed_extra_pages();
	}
}, 99 );
