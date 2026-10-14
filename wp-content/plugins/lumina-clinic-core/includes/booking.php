<?php
/**
 * Appointment requests.
 *
 * POST /wp-admin/admin-post.php?action=lumina_book
 *  - Works with JS (fetch → JSON) and without it (redirect back with a status).
 *  - Spam: hidden honeypot, signed "form opened at" token (too-fast and stale
 *    submissions are rejected) and a per-IP hourly rate limit.
 *  - Every request is stored as a private "Appointment" and emailed to the clinic.
 *
 * This is a *request*, not a confirmed slot; the clinic calls or messages to confirm.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

const LUMINA_SLOTS    = array(
	'morning'   => 'Morning (9am–12pm)',
	'afternoon' => 'Afternoon (12pm–4pm)',
	'evening'   => 'Evening (4pm–9pm)',
);
const LUMINA_PATIENTS = array(
	'new'      => 'New patient',
	'existing' => 'Existing patient',
);
const LUMINA_GENERAL_SERVICE = 'Not sure / general check-up';

function lumina_booking_token() {
	$t = time();
	return $t . '.' . hash_hmac( 'sha256', (string) $t, wp_salt( 'nonce' ) );
}

function lumina_booking_token_age( $token ) {
	$parts = explode( '.', (string) $token );
	if ( 2 !== count( $parts ) || ! ctype_digit( $parts[0] ) ) {
		return false;
	}
	if ( ! hash_equals( hash_hmac( 'sha256', $parts[0], wp_salt( 'nonce' ) ), $parts[1] ) ) {
		return false;
	}
	return time() - (int) $parts[0];
}

/** Titles of published services, used for the select and for validation. */
function lumina_booking_services() {
	$titles = array();
	foreach ( get_posts( array( 'post_type' => 'service', 'posts_per_page' => 50, 'orderby' => 'menu_order title', 'order' => 'ASC' ) ) as $s ) {
		$titles[] = $s->post_title;
	}
	$titles[] = LUMINA_GENERAL_SERVICE;
	return $titles;
}

/** Day keys (sat..fri) on which the clinic is closed; the form uses this to block dates. */
function lumina_closed_days() {
	$closed = array();
	foreach ( (array) lumina_opt( 'hours' ) as $k => $h ) {
		if ( empty( $h['open'] ) || empty( $h['close'] ) ) {
			$closed[] = $k;
		}
	}
	return $closed;
}

function lumina_booking_respond( $ok, $payload, $status = 200 ) {
	$wants_json = false !== stripos( $_SERVER['HTTP_ACCEPT'] ?? '', 'application/json' );
	if ( $wants_json ) {
		wp_send_json( array_merge( array( 'ok' => $ok ), $payload ), $status );
	}
	$back = wp_get_referer() ?: home_url( '/book/' );
	$back = remove_query_arg( array( 'booking', 'msg' ), $back );
	$args = $ok ? array( 'booking' => 'ok' ) : array( 'booking' => 'error', 'msg' => $payload['message'] ?? 'Please check the form and try again.' );
	wp_safe_redirect( add_query_arg( $args, $back ) . '#book' );
	exit;
}

function lumina_handle_booking() {
	$post = wp_unslash( $_POST );

	// Bots fill the invisible field; answer politely and do nothing.
	if ( ! empty( $post['website'] ) ) {
		lumina_booking_respond( true, array( 'message' => 'Thank you.' ) );
	}

	$age = lumina_booking_token_age( $post['lt'] ?? '' );
	if ( false === $age || $age < 3 || $age > DAY_IN_SECONDS ) {
		lumina_booking_respond( false, array( 'message' => 'The form expired. Please reload the page and try again.' ), 400 );
	}

	$ip  = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
	$key = 'lumina_rl_' . md5( $ip );
	$hit = (int) get_transient( $key );
	if ( $hit >= 5 ) {
		lumina_booking_respond( false, array( 'message' => 'Too many requests from your connection. Please call us instead.' ), 429 );
	}

	$name    = sanitize_text_field( $post['name'] ?? '' );
	$phone   = sanitize_text_field( $post['phone'] ?? '' );
	$email   = sanitize_email( $post['email'] ?? '' );
	$service = sanitize_text_field( $post['service'] ?? '' );
	$date    = sanitize_text_field( $post['date'] ?? '' );
	$slot    = sanitize_key( $post['slot'] ?? '' );
	$patient = sanitize_key( $post['patient'] ?? '' );
	$message = sanitize_textarea_field( $post['message'] ?? '' );

	$errors = array();
	if ( mb_strlen( $name ) < 2 || mb_strlen( $name ) > 80 ) {
		$errors['name'] = 'Please enter your name.';
	}
	$digits = preg_replace( '/\D/', '', $phone );
	if ( strlen( $digits ) < 7 || strlen( $digits ) > 15 ) {
		$errors['phone'] = 'Please enter a phone number we can reach you on.';
	}
	if ( ! empty( $post['email'] ) && ! is_email( $email ) ) {
		$errors['email'] = 'That email address does not look right.';
	}
	if ( ! in_array( $service, lumina_booking_services(), true ) ) {
		$errors['service'] = 'Please choose a service.';
	}
	if ( ! preg_match( '/^\d{4}-\d{2}-\d{2}$/', $date ) ) {
		$errors['date'] = 'Please pick a date.';
	} else {
		$today = wp_date( 'Y-m-d' );
		$max   = wp_date( 'Y-m-d', time() + 90 * DAY_IN_SECONDS );
		if ( $date < $today || $date > $max ) {
			$errors['date'] = 'Please pick a date within the next 90 days.';
		} else {
			$dow = strtolower( ( new DateTime( $date, wp_timezone() ) )->format( 'D' ) );
			if ( in_array( $dow, lumina_closed_days(), true ) ) {
				$errors['date'] = 'We are closed that day. Please pick another date.';
			}
		}
	}
	if ( ! isset( LUMINA_SLOTS[ $slot ] ) ) {
		$errors['slot'] = 'Please choose a time of day.';
	}
	if ( ! isset( LUMINA_PATIENTS[ $patient ] ) ) {
		$errors['patient'] = 'Please tell us if you have visited before.';
	}
	if ( mb_strlen( $message ) > 1000 ) {
		$errors['message'] = 'Please keep your note under 1,000 characters.';
	}
	if ( empty( $post['consent'] ) ) {
		$errors['consent'] = 'Please agree so we can contact you about this request.';
	}

	if ( $errors ) {
		lumina_booking_respond( false, array( 'message' => reset( $errors ), 'errors' => $errors ), 422 );
	}

	set_transient( $key, $hit + 1, HOUR_IN_SECONDS );

	$id = wp_insert_post( array(
		'post_type'   => 'lumina_appt',
		'post_status' => 'publish',
		'post_title'  => sprintf( '%s — %s, %s', $name, $date, LUMINA_SLOTS[ $slot ] ),
	), true );

	if ( is_wp_error( $id ) ) {
		lumina_booking_respond( false, array( 'message' => 'Sorry, we could not save your request. Please call us.' ), 500 );
	}

	foreach ( compact( 'name', 'phone', 'email', 'service', 'date', 'message' ) as $k => $v ) {
		update_post_meta( $id, '_lumina_' . $k, $v );
	}
	update_post_meta( $id, '_lumina_slot', LUMINA_SLOTS[ $slot ] );
	update_post_meta( $id, '_lumina_patient', LUMINA_PATIENTS[ $patient ] );
	update_post_meta( $id, '_lumina_status', 'pending' );

	$to      = lumina_opt( 'notify_email' ) ?: get_option( 'admin_email' );
	$headers = array( 'Content-Type: text/plain; charset=UTF-8' );
	if ( $email ) {
		$headers[] = 'Reply-To: ' . $name . ' <' . $email . '>';
	}
	$body = "New appointment request\n\n"
		. "Name:     $name\n"
		. "Phone:    $phone\n"
		. 'Email:    ' . ( $email ?: '—' ) . "\n"
		. "Service:  $service\n"
		. "Date:     $date\n"
		. 'Time:     ' . LUMINA_SLOTS[ $slot ] . "\n"
		. 'Patient:  ' . LUMINA_PATIENTS[ $patient ] . "\n\n"
		. ( $message ? "Note:\n$message\n\n" : '' )
		. 'Manage: ' . admin_url( 'post.php?post=' . $id . '&action=edit' ) . "\n";
	wp_mail( $to, 'New appointment request: ' . $name, $body, $headers );

	lumina_booking_respond( true, array(
		'message' => 'Request received. We will call or message you on ' . $phone . ' to confirm your time.',
		'ref'     => $id,
	) );
}
add_action( 'admin_post_nopriv_lumina_book', 'lumina_handle_booking' );
add_action( 'admin_post_lumina_book', 'lumina_handle_booking' );
