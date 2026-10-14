<?php
/**
 * Content types:
 *  - service      → /services/<slug>/       one page per treatment (local-SEO money pages)
 *  - area         → /areas-we-serve/<slug>/ one page per neighbourhood
 *  - lumina_review→ patient reviews (not public pages; shown in sections)
 *  - lumina_appt  → appointment requests (admin only)
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function lumina_register_post_types() {
	register_post_type( 'service', array(
		'labels'       => array(
			'name'          => 'Services',
			'singular_name' => 'Service',
			'add_new_item'  => 'Add new service',
			'edit_item'     => 'Edit service',
		),
		'public'       => true,
		'has_archive'  => 'services',
		'rewrite'      => array( 'slug' => 'services', 'with_front' => false ),
		'menu_icon'    => 'dashicons-plus-alt',
		'menu_position'=> 56,
		'supports'     => array( 'title', 'editor', 'excerpt', 'thumbnail', 'page-attributes' ),
		'show_in_rest' => true,
	) );

	register_post_type( 'area', array(
		'labels'       => array(
			'name'          => 'Areas served',
			'singular_name' => 'Area',
			'add_new_item'  => 'Add new area',
			'edit_item'     => 'Edit area',
		),
		'public'       => true,
		'has_archive'  => 'areas-we-serve',
		'rewrite'      => array( 'slug' => 'areas-we-serve', 'with_front' => false ),
		'menu_icon'    => 'dashicons-location-alt',
		'menu_position'=> 57,
		'supports'     => array( 'title', 'editor', 'excerpt', 'page-attributes' ),
		'show_in_rest' => true,
	) );

	register_post_type( 'lumina_review', array(
		'labels'       => array(
			'name'          => 'Reviews',
			'singular_name' => 'Review',
			'add_new_item'  => 'Add review',
			'edit_item'     => 'Edit review',
		),
		'public'              => false,
		'show_ui'             => true,
		'exclude_from_search' => true,
		'menu_icon'           => 'dashicons-star-filled',
		'menu_position'       => 59,
		'supports'            => array( 'title', 'editor' ),
	) );

	register_post_type( 'lumina_appt', array(
		'labels'       => array(
			'name'          => 'Appointments',
			'singular_name' => 'Appointment request',
			'edit_item'     => 'Appointment request',
			'not_found'     => 'No appointment requests yet.',
		),
		'public'       => false,
		'show_ui'      => true,
		'menu_icon'    => 'dashicons-calendar-alt',
		'menu_position'=> 55,
		'supports'     => array( 'title' ),
		'capability_type' => 'post',
		'capabilities' => array( 'create_posts' => 'do_not_allow' ),
		'map_meta_cap' => true,
	) );
}
add_action( 'init', 'lumina_register_post_types' );

/* ---- Meta boxes -------------------------------------------------------- */

const LUMINA_ICONS = array( 'tooth', 'sparkle', 'braces', 'implant', 'root', 'child', 'shield', 'alert' );

add_action( 'add_meta_boxes', function () {
	add_meta_box( 'lumina_service', 'Service details', 'lumina_box_service', 'service', 'normal', 'high' );
	add_meta_box( 'lumina_area', 'Area details', 'lumina_box_area', 'area', 'normal', 'high' );
	add_meta_box( 'lumina_review', 'Review details', 'lumina_box_review', 'lumina_review', 'normal', 'high' );
	add_meta_box( 'lumina_appt', 'Request', 'lumina_box_appt', 'lumina_appt', 'normal', 'high' );
} );

function lumina_field( $label, $name, $value, $hint = '', $type = 'text' ) {
	printf(
		'<p><label><strong>%1$s</strong><br><input type="%5$s" class="widefat" name="%2$s" value="%3$s"></label>%4$s</p>',
		esc_html( $label ),
		esc_attr( $name ),
		esc_attr( $value ),
		$hint ? '<span class="description">' . esc_html( $hint ) . '</span>' : '',
		esc_attr( $type )
	);
}

function lumina_box_service( $post ) {
	wp_nonce_field( 'lumina_save', 'lumina_nonce' );
	$icon = get_post_meta( $post->ID, '_lumina_icon', true ) ?: 'tooth';
	echo '<p><label><strong>Icon</strong><br><select name="lumina_icon">';
	foreach ( LUMINA_ICONS as $i ) {
		printf( '<option value="%1$s"%2$s>%1$s</option>', esc_attr( $i ), selected( $icon, $i, false ) );
	}
	echo '</select></label></p>';
	lumina_field( 'Typical visit length', 'lumina_duration', get_post_meta( $post->ID, '_lumina_duration', true ), 'e.g. 45–60 minutes' );
	lumina_field( 'Starting price', 'lumina_price', get_post_meta( $post->ID, '_lumina_price', true ), 'e.g. From ৳1,500. Leave blank to show "Ask for a quote".' );
	printf(
		'<p><label><strong>What to expect (one step per line)</strong><br><textarea class="widefat" rows="5" name="lumina_steps">%s</textarea></label></p>',
		esc_textarea( get_post_meta( $post->ID, '_lumina_steps', true ) )
	);
	printf(
		'<p><label><strong>FAQs (one per line: Question | Answer)</strong><br><textarea class="widefat" rows="6" name="lumina_faqs">%s</textarea></label><span class="description">Shown on the page and sent to Google as FAQ markup.</span></p>',
		esc_textarea( get_post_meta( $post->ID, '_lumina_faqs', true ) )
	);
	echo '<p class="description">The excerpt is used as the short description on cards and as the meta description.</p>';
}

function lumina_box_area( $post ) {
	wp_nonce_field( 'lumina_save', 'lumina_nonce' );
	lumina_field( 'Getting here', 'lumina_distance', get_post_meta( $post->ID, '_lumina_distance', true ), 'e.g. 10 minutes from Mohammadpur by car' );
	lumina_field( 'Nearby landmarks', 'lumina_landmarks', get_post_meta( $post->ID, '_lumina_landmarks', true ), 'Comma separated, e.g. Star Mosque, Dhanmondi Lake' );
}

function lumina_box_review( $post ) {
	wp_nonce_field( 'lumina_save', 'lumina_nonce' );
	$rating = (int) ( get_post_meta( $post->ID, '_lumina_rating', true ) ?: 5 );
	echo '<p><label><strong>Rating</strong><br><select name="lumina_rating">';
	for ( $i = 5; $i >= 1; $i-- ) {
		printf( '<option value="%1$d"%2$s>%1$d star%3$s</option>', $i, selected( $rating, $i, false ), 1 === $i ? '' : 's' );
	}
	echo '</select></label></p>';
	lumina_field( 'Reviewer name', 'lumina_author', get_post_meta( $post->ID, '_lumina_author', true ) );
	lumina_field( 'Treatment', 'lumina_treatment', get_post_meta( $post->ID, '_lumina_treatment', true ), 'e.g. Teeth whitening' );
	lumina_field( 'Source', 'lumina_source', get_post_meta( $post->ID, '_lumina_source', true ) ?: 'Google', 'Where the review was originally posted.' );
	$sample = get_post_meta( $post->ID, '_lumina_sample', true );
	printf(
		'<p><label><input type="checkbox" name="lumina_sample" value="1"%s> <strong>Sample review</strong></label><br><span class="description">Placeholder text. Sample reviews are labelled on the site and excluded from star-rating markup. Untick once this is a real patient review.</span></p>',
		checked( $sample, '1', false )
	);
	echo '<p class="description">Write the review text in the main editor above. The title is for your reference only.</p>';
}

function lumina_box_appt( $post ) {
	wp_nonce_field( 'lumina_save', 'lumina_nonce' );
	$m = function ( $k ) use ( $post ) {
		return get_post_meta( $post->ID, '_lumina_' . $k, true );
	};
	$status = $m( 'status' ) ?: 'pending';
	echo '<table class="form-table" role="presentation">';
	foreach ( array(
		'name' => 'Name', 'phone' => 'Phone', 'email' => 'Email', 'service' => 'Service',
		'date' => 'Preferred date', 'slot' => 'Preferred time', 'patient' => 'Patient type',
	) as $k => $label ) {
		printf( '<tr><th>%s</th><td>%s</td></tr>', esc_html( $label ), esc_html( $m( $k ) ?: '—' ) );
	}
	printf( '<tr><th>Message</th><td>%s</td></tr>', nl2br( esc_html( $m( 'message' ) ?: '—' ) ) );
	echo '</table><p><label><strong>Status</strong> <select name="lumina_status">';
	foreach ( array( 'pending' => 'Pending', 'confirmed' => 'Confirmed', 'done' => 'Completed', 'cancelled' => 'Cancelled' ) as $v => $l ) {
		printf( '<option value="%s"%s>%s</option>', esc_attr( $v ), selected( $status, $v, false ), esc_html( $l ) );
	}
	echo '</select></label></p>';
}

add_action( 'save_post', function ( $post_id ) {
	if ( ! isset( $_POST['lumina_nonce'] ) || ! wp_verify_nonce( $_POST['lumina_nonce'], 'lumina_save' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}
	$text = array(
		'lumina_icon'      => '_lumina_icon',
		'lumina_duration'  => '_lumina_duration',
		'lumina_price'     => '_lumina_price',
		'lumina_distance'  => '_lumina_distance',
		'lumina_landmarks' => '_lumina_landmarks',
		'lumina_author'    => '_lumina_author',
		'lumina_treatment' => '_lumina_treatment',
		'lumina_source'    => '_lumina_source',
		'lumina_status'    => '_lumina_status',
	);
	foreach ( $text as $field => $meta ) {
		if ( isset( $_POST[ $field ] ) ) {
			$val = sanitize_text_field( wp_unslash( $_POST[ $field ] ) );
			if ( '_lumina_icon' === $meta && ! in_array( $val, LUMINA_ICONS, true ) ) {
				$val = 'tooth';
			}
			update_post_meta( $post_id, $meta, $val );
		}
	}
	foreach ( array( 'lumina_steps' => '_lumina_steps', 'lumina_faqs' => '_lumina_faqs' ) as $field => $meta ) {
		if ( isset( $_POST[ $field ] ) ) {
			update_post_meta( $post_id, $meta, sanitize_textarea_field( wp_unslash( $_POST[ $field ] ) ) );
		}
	}
	if ( isset( $_POST['lumina_rating'] ) ) {
		update_post_meta( $post_id, '_lumina_rating', max( 1, min( 5, (int) $_POST['lumina_rating'] ) ) );
	}
	if ( 'lumina_review' === get_post_type( $post_id ) ) {
		update_post_meta( $post_id, '_lumina_sample', isset( $_POST['lumina_sample'] ) ? '1' : '0' );
	}
} );

/* ---- Admin list columns ----------------------------------------------- */

add_filter( 'manage_lumina_appt_posts_columns', function () {
	return array(
		'cb' => '<input type="checkbox">', 'title' => 'Patient', 'lphone' => 'Phone', 'lservice' => 'Service',
		'lwhen' => 'Preferred time', 'lstatus' => 'Status', 'date' => 'Received',
	);
} );

add_action( 'manage_lumina_appt_posts_custom_column', function ( $col, $id ) {
	$map = array( 'lphone' => 'phone', 'lservice' => 'service', 'lstatus' => 'status' );
	if ( isset( $map[ $col ] ) ) {
		$v = get_post_meta( $id, '_lumina_' . $map[ $col ], true );
		echo esc_html( 'lstatus' === $col ? ucfirst( $v ?: 'pending' ) : $v );
	} elseif ( 'lwhen' === $col ) {
		echo esc_html( trim( get_post_meta( $id, '_lumina_date', true ) . ' · ' . get_post_meta( $id, '_lumina_slot', true ), ' ·' ) );
	}
}, 10, 2 );

add_filter( 'manage_lumina_review_posts_columns', function () {
	return array( 'cb' => '<input type="checkbox">', 'title' => 'Review', 'lrating' => 'Rating', 'lsample' => 'Sample?', 'date' => 'Date' );
} );

add_action( 'manage_lumina_review_posts_custom_column', function ( $col, $id ) {
	if ( 'lrating' === $col ) {
		echo esc_html( str_repeat( '★', (int) get_post_meta( $id, '_lumina_rating', true ) ) );
	} elseif ( 'lsample' === $col ) {
		echo '1' === get_post_meta( $id, '_lumina_sample', true ) ? 'Yes' : '—';
	}
}, 10, 2 );

/** Pending-request count bubble on the Appointments menu. */
add_action( 'admin_menu', function () {
	global $menu;
	$pending = new WP_Query( array(
		'post_type'      => 'lumina_appt',
		'post_status'    => 'publish',
		'meta_query'     => array( array( 'key' => '_lumina_status', 'value' => 'pending' ) ),
		'fields'         => 'ids',
		'posts_per_page' => 50,
		'no_found_rows'  => true,
	) );
	$n = count( $pending->posts );
	if ( $n && is_array( $menu ) ) {
		foreach ( $menu as $i => $item ) {
			if ( isset( $item[2] ) && 'edit.php?post_type=lumina_appt' === $item[2] ) {
				$menu[ $i ][0] .= ' <span class="awaiting-mod">' . (int) $n . '</span>';
			}
		}
	}
}, 99 );

/* ---- Parsing helpers used by the theme and schema --------------------- */

function lumina_parse_faqs( $post_id ) {
	$out = array();
	foreach ( preg_split( '/\r\n|\r|\n/', (string) get_post_meta( $post_id, '_lumina_faqs', true ) ) as $line ) {
		$parts = array_map( 'trim', explode( '|', $line, 2 ) );
		if ( 2 === count( $parts ) && $parts[0] && $parts[1] ) {
			$out[] = array( 'q' => $parts[0], 'a' => $parts[1] );
		}
	}
	return $out;
}

function lumina_parse_steps( $post_id ) {
	return array_values( array_filter( array_map( 'trim', preg_split( '/\r\n|\r|\n/', (string) get_post_meta( $post_id, '_lumina_steps', true ) ) ) ) );
}

/** Real (non-sample) review stats, used for the rating badge and schema. */
function lumina_review_stats() {
	static $stats = null;
	if ( null !== $stats ) {
		return $stats;
	}
	$ids = get_posts( array(
		'post_type'      => 'lumina_review',
		'post_status'    => 'publish',
		'posts_per_page' => -1,
		'fields'         => 'ids',
		'meta_query'     => array(
			'relation' => 'OR',
			array( 'key' => '_lumina_sample', 'compare' => 'NOT EXISTS' ),
			array( 'key' => '_lumina_sample', 'value' => '1', 'compare' => '!=' ),
		),
	) );
	$sum = 0;
	foreach ( $ids as $id ) {
		$sum += (int) get_post_meta( $id, '_lumina_rating', true );
	}
	$stats = array(
		'count'   => count( $ids ),
		'average' => $ids ? round( $sum / count( $ids ), 1 ) : 0,
	);
	return $stats;
}
