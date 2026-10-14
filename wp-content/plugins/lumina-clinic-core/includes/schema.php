<?php
/**
 * Structured data (JSON-LD). One @graph per page:
 *   Dentist (LocalBusiness)  – every page
 *   Service + FAQPage        – single service pages
 *   BreadcrumbList           – everything except the home page
 *
 * Star ratings are only emitted from real reviews. Sample reviews never reach
 * the markup (Google's review-snippet policy forbids fabricated ratings).
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/** Breadcrumb trail for the current request: array of [ label, url ]. */
function lumina_breadcrumb_items() {
	$items = array( array( 'Home', home_url( '/' ) ) );
	if ( is_front_page() ) {
		return $items;
	}
	if ( is_singular( 'service' ) || is_post_type_archive( 'service' ) ) {
		$items[] = array( 'Services', get_post_type_archive_link( 'service' ) );
	}
	if ( is_singular( 'area' ) || is_post_type_archive( 'area' ) ) {
		$items[] = array( 'Areas we serve', get_post_type_archive_link( 'area' ) );
	}
	if ( is_singular() ) {
		$items[] = array( get_the_title(), get_permalink() );
	} elseif ( is_404() ) {
		$items[] = array( 'Page not found', '' );
	} elseif ( is_search() ) {
		$items[] = array( 'Search', '' );
	}
	return $items;
}

function lumina_schema_business() {
	$id   = home_url( '/#clinic' );
	$days = array(
		'sat' => 'Saturday', 'sun' => 'Sunday', 'mon' => 'Monday', 'tue' => 'Tuesday',
		'wed' => 'Wednesday', 'thu' => 'Thursday', 'fri' => 'Friday',
	);

	$hours = array();
	foreach ( (array) lumina_opt( 'hours' ) as $k => $h ) {
		if ( ! empty( $h['open'] ) && ! empty( $h['close'] ) && isset( $days[ $k ] ) ) {
			$hours[] = array(
				'@type'     => 'OpeningHoursSpecification',
				'dayOfWeek' => $days[ $k ],
				'opens'     => $h['open'],
				'closes'    => $h['close'],
			);
		}
	}

	$areas = array();
	foreach ( get_posts( array( 'post_type' => 'area', 'posts_per_page' => 30, 'orderby' => 'menu_order title', 'order' => 'ASC' ) ) as $a ) {
		$areas[] = array( '@type' => 'Place', 'name' => get_the_title( $a ) );
	}

	$same = array_values( array_filter( array( lumina_opt( 'facebook' ), lumina_opt( 'instagram' ), lumina_opt( 'google_review_url' ) ) ) );

	$node = array(
		'@type'     => array( 'Dentist', 'LocalBusiness' ),
		'@id'       => $id,
		'name'      => lumina_opt( 'name' ),
		'url'       => home_url( '/' ),
		'telephone' => lumina_opt( 'phone' ),
		'email'     => lumina_opt( 'email' ),
		'priceRange'=> lumina_opt( 'price_range' ),
		'address'   => array(
			'@type'           => 'PostalAddress',
			'streetAddress'   => lumina_opt( 'street' ),
			'addressLocality' => lumina_opt( 'city' ),
			'addressRegion'   => lumina_opt( 'region' ),
			'postalCode'      => lumina_opt( 'postcode' ),
			'addressCountry'  => lumina_opt( 'country' ),
		),
		'openingHoursSpecification' => $hours,
		'areaServed'=> $areas,
		'hasMap'    => 'https://www.google.com/maps/search/?api=1&query=' . rawurlencode( lumina_opt( 'map_query' ) ?: lumina_address_line() ),
	);
	if ( lumina_opt( 'lat' ) && lumina_opt( 'lng' ) ) {
		$node['geo'] = array(
			'@type'     => 'GeoCoordinates',
			'latitude'  => (float) lumina_opt( 'lat' ),
			'longitude' => (float) lumina_opt( 'lng' ),
		);
	}
	if ( $same ) {
		$node['sameAs'] = $same;
	}
	if ( has_site_icon() ) {
		$node['image'] = get_site_icon_url( 512 );
	}

	$stats = lumina_review_stats();
	if ( $stats['count'] > 0 ) {
		$node['aggregateRating'] = array(
			'@type'       => 'AggregateRating',
			'ratingValue' => $stats['average'],
			'reviewCount' => $stats['count'],
			'bestRating'  => 5,
		);
		$reviews = get_posts( array(
			'post_type'      => 'lumina_review',
			'posts_per_page' => 5,
			'meta_query'     => array(
				'relation' => 'OR',
				array( 'key' => '_lumina_sample', 'compare' => 'NOT EXISTS' ),
				array( 'key' => '_lumina_sample', 'value' => '1', 'compare' => '!=' ),
			),
		) );
		foreach ( $reviews as $r ) {
			$node['review'][] = array(
				'@type'         => 'Review',
				'author'        => array( '@type' => 'Person', 'name' => get_post_meta( $r->ID, '_lumina_author', true ) ?: 'Patient' ),
				'datePublished' => get_the_date( 'c', $r ),
				'reviewBody'    => wp_strip_all_tags( $r->post_content ),
				'reviewRating'  => array(
					'@type'       => 'Rating',
					'ratingValue' => (int) get_post_meta( $r->ID, '_lumina_rating', true ),
					'bestRating'  => 5,
				),
			);
		}
	}
	return $node;
}

add_action( 'wp_head', function () {
	$graph = array( lumina_schema_business() );

	if ( is_singular( 'service' ) ) {
		$id     = get_the_ID();
		$graph[] = array(
			'@type'       => 'Service',
			'@id'         => get_permalink( $id ) . '#service',
			'name'        => get_the_title( $id ),
			'description' => wp_strip_all_tags( get_the_excerpt( $id ) ),
			'url'         => get_permalink( $id ),
			'serviceType' => get_the_title( $id ),
			'provider'    => array( '@id' => home_url( '/#clinic' ) ),
			'areaServed'  => array( '@type' => 'City', 'name' => lumina_opt( 'city' ) ),
		);
		$faqs = lumina_parse_faqs( $id );
		if ( $faqs ) {
			$entities = array();
			foreach ( $faqs as $f ) {
				$entities[] = array(
					'@type'          => 'Question',
					'name'           => $f['q'],
					'acceptedAnswer' => array( '@type' => 'Answer', 'text' => $f['a'] ),
				);
			}
			$graph[] = array( '@type' => 'FAQPage', 'mainEntity' => $entities );
		}
	}

	$crumbs = lumina_breadcrumb_items();
	if ( count( $crumbs ) > 1 ) {
		$list = array();
		foreach ( $crumbs as $i => $c ) {
			$row = array( '@type' => 'ListItem', 'position' => $i + 1, 'name' => $c[0] );
			if ( $c[1] ) {
				$row['item'] = $c[1];
			}
			$list[] = $row;
		}
		$graph[] = array( '@type' => 'BreadcrumbList', 'itemListElement' => $list );
	}

	echo '<script type="application/ld+json">' . wp_json_encode(
		array( '@context' => 'https://schema.org', '@graph' => $graph ),
		JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
	) . "</script>\n";
}, 5 );
