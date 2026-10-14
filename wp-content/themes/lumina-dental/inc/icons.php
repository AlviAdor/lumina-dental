<?php
/**
 * Inline SVG icons (24×24, stroke = currentColor) so they follow the theme and
 * high-contrast mode without extra requests.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function lumina_icon_paths() {
	return array(
		'tooth'    => '<path d="M7.5 3.5c-2.3 0-4 1.8-4 4.4 0 2 .8 3.4 1.4 5.2.7 2 .9 4.4 1.7 6.6.4 1 1.1 1.3 1.7 1.3 1.1 0 1.4-1.1 1.7-2.4.3-1.4.5-3 1.5-3s1.2 1.6 1.5 3c.3 1.3.6 2.4 1.7 2.4.6 0 1.3-.3 1.7-1.3.8-2.2 1-4.6 1.7-6.6.6-1.8 1.4-3.2 1.4-5.2 0-2.6-1.7-4.4-4-4.4-1.4 0-2.2.6-3.2.6S8.9 3.5 7.5 3.5Z"/>',
		'sparkle'  => '<path d="M12 3l1.8 5.2L19 10l-5.2 1.8L12 17l-1.8-5.2L5 10l5.2-1.8L12 3Z"/><path d="M19 15l.8 2.2L22 18l-2.2.8L19 21l-.8-2.2L16 18l2.2-.8L19 15Z"/>',
		'braces'   => '<rect x="3" y="8" width="18" height="8" rx="3"/><path d="M8 8v8M12 8v8M16 8v8M3 12h18"/>',
		'implant'  => '<path d="M6 4h12c0 2.5-1.5 4-3 4H9C7.5 8 6 6.5 6 4Z"/><path d="M9 8v1.5M15 8v1.5M8.5 11h7M9 14h6M10 17h4M11 20h2"/>',
		'root'     => '<path d="M7 3.5c-2 0-3.5 1.5-3.5 3.8 0 1.6.6 2.7 1.1 4.2.5 1.7.6 3.4 1.2 5 .4 1 .9 1.1 1.3 1.1.9 0 1.1-.9 1.4-2 .2-1 .4-2.1 1-2.1" /><path d="M17 3.5c2 0 3.5 1.5 3.5 3.8 0 1.6-.6 2.7-1.1 4.2-.5 1.7-.6 3.4-1.2 5-.4 1-.9 1.1-1.3 1.1-.9 0-1.1-.9-1.4-2-.2-1-.4-2.1-1-2.1"/><path d="M9 7.5h6M9.5 11h5"/>',
		'child'    => '<circle cx="12" cy="8" r="4"/><path d="M5 20c.7-3.5 3.4-5.5 7-5.5s6.3 2 7 5.5"/><path d="M10.5 8.4c.4.5 1 .8 1.5.8s1.1-.3 1.5-.8"/>',
		'shield'   => '<path d="M12 3l7 2.8v5.4c0 4.3-2.8 8-7 9.8-4.2-1.8-7-5.5-7-9.8V5.8L12 3Z"/><path d="M9 12l2.2 2.2L15.5 10"/>',
		'alert'    => '<path d="M12 3.5l9 15.5H3L12 3.5Z"/><path d="M12 10v4M12 16.8v.2"/>',
		'phone'    => '<path d="M5 4h3.5l1.8 4.5-2.3 1.4a11 11 0 0 0 5.1 5.1l1.4-2.3L19 14.5V18a2 2 0 0 1-2 2A14 14 0 0 1 3 6a2 2 0 0 1 2-2Z"/>',
		'whatsapp' => '<path d="M4 20l1.3-4.2A8 8 0 1 1 8.4 18.8L4 20Z"/><path d="M9.2 8.8c-.2.6 0 1.5.9 2.7s2 2 3 2.3c.7.2 1.4-.1 1.7-.7l.1-.5-1.5-.8-.7.6c-.8-.3-1.5-1-2-1.9l.5-.7-.7-1.6-.5-.1c-.4 0-.7.3-.8.7Z"/>',
		'calendar' => '<rect x="3.5" y="5" width="17" height="15.5" rx="2.5"/><path d="M3.5 10h17M8 3v4M16 3v4"/>',
		'clock'    => '<circle cx="12" cy="12" r="8.5"/><path d="M12 7.5V12l3 2"/>',
		'pin'      => '<path d="M12 21s7-5.7 7-11.2A7 7 0 0 0 5 9.8C5 15.3 12 21 12 21Z"/><circle cx="12" cy="9.8" r="2.5"/>',
		'mail'     => '<rect x="3" y="5.5" width="18" height="13" rx="2.5"/><path d="m4 7.5 8 6 8-6"/>',
		'check'    => '<path d="M5 12.5l4.2 4.2L19 7"/>',
		'arrow'    => '<path d="M5 12h14M13 6l6 6-6 6"/>',
		'moon'     => '<path d="M20.5 14.2A8.5 8.5 0 1 1 9.8 3.5a6.8 6.8 0 0 0 10.7 10.7Z"/>',
		'settings' => '<path d="M4 7h9M17 7h3M4 17h3M11 17h9"/><circle cx="15" cy="7" r="2"/><circle cx="9" cy="17" r="2"/>',
		'facebook' => '<path d="M14 8.5V7c0-.9.4-1.5 1.5-1.5H17V3h-2.2C12.2 3 11 4.6 11 6.8v1.7H8.8V11H11v10h3V11h2.3l.4-2.5H14Z"/>',
		'instagram'=> '<rect x="3.5" y="3.5" width="17" height="17" rx="5"/><circle cx="12" cy="12" r="4"/><path d="M17 7h.01"/>',
		'star'     => '<path d="M12 3.5l2.6 5.4 5.9.8-4.3 4.1 1 5.9L12 16.9l-5.2 2.8 1-5.9L3.5 9.7l5.9-.8L12 3.5Z"/>',
	);
}

/**
 * Echo-ready SVG string for an icon. Stars use a filled variant.
 *
 * @param string $name  Icon key.
 * @param int    $size  Pixel width/height.
 * @param bool   $fill  Fill instead of stroke (used for stars).
 */
function lumina_icon( $name, $size = 20, $fill = false ) {
	$paths = lumina_icon_paths();
	if ( ! isset( $paths[ $name ] ) ) {
		return '';
	}
	return sprintf(
		'<svg class="icon icon-%1$s" width="%2$d" height="%2$d" viewBox="0 0 24 24" fill="%3$s" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">%4$s</svg>',
		esc_attr( $name ),
		(int) $size,
		$fill ? 'currentColor' : 'none',
		$paths[ $name ] // Static markup defined above.
	);
}

/** Five stars with an accessible label. */
function lumina_stars( $rating ) {
	$rating = max( 0, min( 5, (int) $rating ) );
	$out    = '<span class="stars" role="img" aria-label="' . esc_attr( $rating . ' out of 5 stars' ) . '">';
	for ( $i = 1; $i <= 5; $i++ ) {
		$out .= '<span class="star' . ( $i <= $rating ? ' is-on' : '' ) . '">' . lumina_icon( 'star', 16, $i <= $rating ) . '</span>';
	}
	return $out . '</span>';
}
