<?php
/** Template helpers: contact links, opening hours, map URLs, menu links. */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function lumina_tel_url() {
	return 'tel:' . lumina_opt( 'phone' );
}

function lumina_whatsapp_url( $text = '' ) {
	$num = lumina_opt( 'whatsapp' );
	if ( ! $num ) {
		return '';
	}
	$text = $text ?: 'Hello ' . lumina_opt( 'name' ) . ', I would like to ask about an appointment.';
	return 'https://wa.me/' . $num . '?text=' . rawurlencode( $text );
}

/** "9:00" → "9am", "17:30" → "5:30pm". */
function lumina_fmt_time( $hhmm ) {
	$dt = DateTime::createFromFormat( 'H:i', $hhmm );
	if ( ! $dt ) {
		return '';
	}
	return str_replace( ':00', '', strtolower( $dt->format( 'g:ia' ) ) );
}

/** Text Google Maps should search for: explicit query, else coordinates, else the address. */
function lumina_map_query() {
	if ( lumina_opt( 'map_query' ) ) {
		return lumina_opt( 'map_query' );
	}
	if ( lumina_opt( 'lat' ) && lumina_opt( 'lng' ) ) {
		return lumina_opt( 'lat' ) . ',' . lumina_opt( 'lng' );
	}
	return lumina_address_line();
}

function lumina_directions_url() {
	return 'https://www.google.com/maps/dir/?api=1&destination=' . rawurlencode( lumina_map_query() );
}

function lumina_maps_url() {
	return 'https://www.google.com/maps/search/?api=1&query=' . rawurlencode( lumina_map_query() );
}

/**
 * Opening hours grouped into ranges, e.g.
 *   [ ['Saturday – Thursday', '9am – 9pm'], ['Friday', 'Closed'] ]
 */
function lumina_hours_groups() {
	$hours  = (array) lumina_opt( 'hours' );
	$groups = array();
	foreach ( LUMINA_DAYS as $key => $label ) {
		$h     = $hours[ $key ] ?? array();
		$text  = ( ! empty( $h['open'] ) && ! empty( $h['close'] ) )
			? lumina_fmt_time( $h['open'] ) . ' – ' . lumina_fmt_time( $h['close'] )
			: 'Closed';
		$last  = count( $groups ) - 1;
		if ( $last >= 0 && $groups[ $last ]['text'] === $text ) {
			$groups[ $last ]['to'] = $label;
		} else {
			$groups[] = array( 'from' => $label, 'to' => $label, 'text' => $text );
		}
	}
	return array_map( function ( $g ) {
		return array( $g['from'] === $g['to'] ? $g['from'] : $g['from'] . ' – ' . $g['to'], $g['text'] );
	}, $groups );
}

/** Today's hours and whether we are open right now (clinic time zone). */
function lumina_open_state() {
	$now   = new DateTime( 'now', wp_timezone() );
	$key   = strtolower( $now->format( 'D' ) );
	$h     = ( (array) lumina_opt( 'hours' ) )[ $key ] ?? array();
	if ( empty( $h['open'] ) || empty( $h['close'] ) ) {
		return array( 'open' => false, 'label' => 'Closed today' );
	}
	$time = $now->format( 'H:i' );
	if ( $time >= $h['open'] && $time < $h['close'] ) {
		return array( 'open' => true, 'label' => 'Open now · until ' . lumina_fmt_time( $h['close'] ) );
	}
	if ( $time < $h['open'] ) {
		return array( 'open' => false, 'label' => 'Opens today at ' . lumina_fmt_time( $h['open'] ) );
	}
	return array( 'open' => false, 'label' => 'Closed now' );
}

/** Menu items for the primary location as [ title, url, is_current ]. Falls back to sensible defaults. */
function lumina_menu_links() {
	global $wp;
	$here  = trailingslashit( home_url( $wp->request ?? '' ) );
	$items = array();
	$locs  = get_nav_menu_locations();
	if ( ! empty( $locs['primary'] ) ) {
		foreach ( (array) wp_get_nav_menu_items( $locs['primary'] ) as $m ) {
			if ( (int) $m->menu_item_parent === 0 ) {
				$items[] = array( $m->title, $m->url );
			}
		}
	}
	if ( ! $items ) {
		$items = array(
			array( 'Home', home_url( '/' ) ),
			array( 'Services', home_url( '/services/' ) ),
			array( 'Areas', home_url( '/areas-we-serve/' ) ),
			array( 'Contact', home_url( '/contact/' ) ),
		);
	}
	$home = trailingslashit( home_url( '/' ) );
	foreach ( $items as &$i ) {
		$u    = trailingslashit( $i[1] );
		$i[2] = ( $u === $here ) || ( $u !== $home && 0 === strpos( $here, $u ) );
	}
	return $items;
}

function lumina_book_url( $service = '' ) {
	$page = get_page_by_path( 'book' );
	$url  = $page ? get_permalink( $page ) : home_url( '/book/' );
	return $service ? add_query_arg( 'service', $service, $url ) . '#book' : $url . '#book';
}

/** Services in admin order. */
function lumina_services( $args = array() ) {
	return get_posts( wp_parse_args( $args, array(
		'post_type'      => 'service',
		'posts_per_page' => -1,
		'orderby'        => 'menu_order title',
		'order'          => 'ASC',
	) ) );
}

function lumina_areas( $args = array() ) {
	return get_posts( wp_parse_args( $args, array(
		'post_type'      => 'area',
		'posts_per_page' => -1,
		'orderby'        => 'menu_order title',
		'order'          => 'ASC',
	) ) );
}

/** Review posts. $featured limits the count; newest first. */
function lumina_reviews( $limit = -1 ) {
	return get_posts( array(
		'post_type'      => 'lumina_review',
		'posts_per_page' => $limit,
		'orderby'        => 'date',
		'order'          => 'DESC',
	) );
}

/** Short city-qualified location string, e.g. "Dhanmondi, Dhaka". */
function lumina_place() {
	return lumina_opt( 'area' ) ? lumina_opt( 'area' ) . ', ' . lumina_opt( 'city' ) : lumina_opt( 'city' );
}

/** Permalink of a page by slug, or the home URL fallback (never the current page). */
function lumina_page_url( $slug ) {
	$page = get_page_by_path( $slug );
	return $page ? get_permalink( $page ) : home_url( '/' . $slug . '/' );
}
