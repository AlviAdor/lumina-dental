<?php
/**
 * Clinic details: one options page, one helper (lumina_opt) used by the theme,
 * the schema output and the booking handler. Everything NAP-related (name,
 * address, phone) lives here so it can never drift between pages.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

const LUMINA_DAYS = array(
	'sat' => 'Saturday',
	'sun' => 'Sunday',
	'mon' => 'Monday',
	'tue' => 'Tuesday',
	'wed' => 'Wednesday',
	'thu' => 'Thursday',
	'fri' => 'Friday',
);

function lumina_defaults() {
	$open   = array( 'open' => '09:00', 'close' => '21:00' );
	$closed = array( 'open' => '', 'close' => '' );
	return array(
		'name'          => 'Lumina Dental Care',
		'tagline'       => 'Gentle, modern family dentistry',
		'doctor'        => 'Dr. Nusrat Rahman',
		'established'   => '2014',
		'phone'         => '+8801700000000',
		'phone_display' => '+880 1700-000000',
		'whatsapp'      => '8801700000000',
		'email'         => 'hello@example.com',
		'street'        => 'House 12, Road 8/A, Dhanmondi',
		'area'          => 'Dhanmondi',
		'city'          => 'Dhaka',
		'region'        => 'Dhaka Division',
		'postcode'      => '1209',
		'country'       => 'BD',
		'lat'           => '23.7461',
		'lng'           => '90.3742',
		'map_query'     => '',
		'price_range'   => '৳৳',
		'google_review_url' => '',
		'facebook'      => '',
		'instagram'     => '',
		'notify_email'  => '',
		'hours'         => array(
			'sat' => $open, 'sun' => $open, 'mon' => $open, 'tue' => $open,
			'wed' => $open, 'thu' => $open, 'fri' => $closed,
		),
	);
}

/** Read one clinic option, falling back to the default. */
function lumina_opt( $key, $fallback = '' ) {
	static $all = null;
	if ( null === $all ) {
		$saved = get_option( 'lumina_clinic', array() );
		$all   = wp_parse_args( is_array( $saved ) ? $saved : array(), lumina_defaults() );
	}
	return isset( $all[ $key ] ) && '' !== $all[ $key ] ? $all[ $key ] : $fallback;
}

/** Full street address on one line, e.g. for the footer and map query. */
function lumina_address_line() {
	return implode( ', ', array_filter( array(
		lumina_opt( 'street' ),
		lumina_opt( 'city' ),
		lumina_opt( 'postcode' ),
	) ) );
}

/* ---- Admin page -------------------------------------------------------- */

add_action( 'admin_menu', function () {
	add_menu_page(
		'Clinic details',
		'Clinic',
		'manage_options',
		'lumina-clinic',
		'lumina_render_settings',
		'dashicons-heart',
		58
	);
} );

add_action( 'admin_init', function () {
	register_setting( 'lumina_clinic_group', 'lumina_clinic', array(
		'sanitize_callback' => 'lumina_sanitize_settings',
	) );
} );

function lumina_sanitize_settings( $in ) {
	$d   = lumina_defaults();
	$out = array();
	foreach ( array( 'name', 'tagline', 'doctor', 'established', 'phone_display', 'street', 'area', 'city', 'region', 'postcode', 'price_range', 'map_query' ) as $k ) {
		$out[ $k ] = isset( $in[ $k ] ) ? sanitize_text_field( $in[ $k ] ) : $d[ $k ];
	}
	// Phone numbers: digits and a leading + only, so tel:/wa.me links are valid.
	$out['phone']    = preg_replace( '/[^\d+]/', '', $in['phone'] ?? '' );
	$out['whatsapp'] = preg_replace( '/\D/', '', $in['whatsapp'] ?? '' );
	$out['email']        = sanitize_email( $in['email'] ?? '' );
	$out['notify_email'] = sanitize_email( $in['notify_email'] ?? '' );
	$out['country']  = strtoupper( substr( sanitize_text_field( $in['country'] ?? 'BD' ), 0, 2 ) );
	$out['lat']      = is_numeric( $in['lat'] ?? '' ) ? (string) (float) $in['lat'] : '';
	$out['lng']      = is_numeric( $in['lng'] ?? '' ) ? (string) (float) $in['lng'] : '';
	foreach ( array( 'google_review_url', 'facebook', 'instagram' ) as $k ) {
		$out[ $k ] = esc_url_raw( $in[ $k ] ?? '' );
	}
	$out['hours'] = array();
	foreach ( array_keys( LUMINA_DAYS ) as $day ) {
		$o = $in['hours'][ $day ]['open'] ?? '';
		$c = $in['hours'][ $day ]['close'] ?? '';
		$ok = preg_match( '/^\d{2}:\d{2}$/', $o ) && preg_match( '/^\d{2}:\d{2}$/', $c ) && $c > $o;
		$out['hours'][ $day ] = $ok ? array( 'open' => $o, 'close' => $c ) : array( 'open' => '', 'close' => '' );
	}
	return $out;
}

function lumina_render_settings() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	$f = function ( $key, $label, $type = 'text', $hint = '' ) {
		printf(
			'<tr><th scope="row"><label for="lc-%1$s">%2$s</label></th><td><input class="regular-text" id="lc-%1$s" type="%3$s" name="lumina_clinic[%1$s]" value="%4$s">%5$s</td></tr>',
			esc_attr( $key ),
			esc_html( $label ),
			esc_attr( $type ),
			esc_attr( lumina_opt( $key ) ),
			$hint ? '<p class="description">' . esc_html( $hint ) . '</p>' : ''
		);
	};
	?>
	<div class="wrap">
		<h1>Clinic details</h1>
		<p>These details feed the header, footer, contact page, map, booking emails and the structured data Google reads. Keep them identical to your Google Business Profile.</p>
		<form method="post" action="options.php">
			<?php settings_fields( 'lumina_clinic_group' ); ?>
			<h2 class="title">Identity</h2>
			<table class="form-table" role="presentation">
				<?php
				$f( 'name', 'Clinic name' );
				$f( 'tagline', 'Tagline' );
				$f( 'doctor', 'Lead dentist' );
				$f( 'established', 'Year established', 'text', 'Shown as years of experience.' );
				?>
			</table>
			<h2 class="title">Contact (NAP)</h2>
			<table class="form-table" role="presentation">
				<?php
				$f( 'phone_display', 'Phone (as shown)', 'text', 'e.g. +880 1700-000000' );
				$f( 'phone', 'Phone (for tap-to-call)', 'text', 'Digits and + only, e.g. +8801700000000' );
				$f( 'whatsapp', 'WhatsApp number', 'text', 'Country code + number, no + or spaces.' );
				$f( 'email', 'Public email', 'email' );
				$f( 'notify_email', 'Send booking requests to', 'email', 'Defaults to the site admin email.' );
				$f( 'street', 'Street address' );
				$f( 'area', 'Neighbourhood / area' );
				$f( 'city', 'City' );
				$f( 'region', 'Region / state' );
				$f( 'postcode', 'Postcode' );
				$f( 'country', 'Country code (2 letters)' );
				?>
			</table>
			<h2 class="title">Map &amp; links</h2>
			<table class="form-table" role="presentation">
				<?php
				$f( 'lat', 'Latitude' );
				$f( 'lng', 'Longitude' );
				$f( 'map_query', 'Map search text', 'text', 'Optional. Leave blank to pin the latitude/longitude above. Or type your Google Maps place name (e.g. "Clinic name, Dhanmondi, Dhaka") for the most accurate pin.' );
				$f( 'google_review_url', 'Google review link', 'url', 'Powers the "Leave a review" button.' );
				$f( 'facebook', 'Facebook page', 'url' );
				$f( 'instagram', 'Instagram profile', 'url' );
				$f( 'price_range', 'Price range symbol' );
				?>
			</table>
			<h2 class="title">Opening hours</h2>
			<p class="description">Leave both fields empty for a closed day. These hours drive the "Open now" badge, the booking form and the Google schema.</p>
			<table class="form-table" role="presentation">
				<?php foreach ( LUMINA_DAYS as $k => $label ) :
					$h = lumina_opt( 'hours' )[ $k ] ?? array( 'open' => '', 'close' => '' );
					?>
					<tr>
						<th scope="row"><?php echo esc_html( $label ); ?></th>
						<td>
							<input type="time" name="lumina_clinic[hours][<?php echo esc_attr( $k ); ?>][open]" value="<?php echo esc_attr( $h['open'] ); ?>">
							to
							<input type="time" name="lumina_clinic[hours][<?php echo esc_attr( $k ); ?>][close]" value="<?php echo esc_attr( $h['close'] ); ?>">
						</td>
					</tr>
				<?php endforeach; ?>
			</table>
			<?php submit_button(); ?>
		</form>
	</div>
	<?php
}

/** Nag until the starter reviews are replaced; fake ratings break Google's rules. */
add_action( 'admin_notices', function () {
	if ( ! current_user_can( 'edit_posts' ) ) {
		return;
	}
	$sample = get_posts( array(
		'post_type'      => 'lumina_review',
		'post_status'    => 'publish',
		'meta_key'       => '_lumina_sample',
		'meta_value'     => '1',
		'fields'         => 'ids',
		'posts_per_page' => 1,
	) );
	if ( $sample ) {
		echo '<div class="notice notice-warning"><p><strong>Lumina:</strong> the site is showing <em>sample</em> reviews and sample clinic details. Replace them with real patient reviews (Reviews → untick "Sample review") and update <a href="' . esc_url( admin_url( 'admin.php?page=lumina-clinic' ) ) . '">Clinic details</a> before launch. Sample reviews are never included in Google rich-result markup.</p></div>';
	}
} );
