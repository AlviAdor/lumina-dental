<?php
/** Review card. Expects $args['review'] (WP_Post). */
$r      = $args['review'];
$author = get_post_meta( $r->ID, '_lumina_author', true ) ?: 'Patient';
$treat  = get_post_meta( $r->ID, '_lumina_treatment', true );
$source = get_post_meta( $r->ID, '_lumina_source', true );
$sample = '1' === get_post_meta( $r->ID, '_lumina_sample', true );
?>
<figure class="card review-card reveal-target">
	<div class="review-top">
		<?php echo lumina_stars( (int) get_post_meta( $r->ID, '_lumina_rating', true ) ); ?>
		<?php if ( $sample ) : ?><span class="chip chip-sample" title="Placeholder review. Replace with a real patient review.">Sample</span><?php endif; ?>
	</div>
	<blockquote><p><?php echo esc_html( wp_strip_all_tags( $r->post_content ) ); ?></p></blockquote>
	<figcaption>
		<strong><?php echo esc_html( $author ); ?></strong>
		<span><?php echo esc_html( implode( ' · ', array_filter( array( $treat, $source, get_the_date( 'M Y', $r ) ) ) ) ); ?></span>
	</figcaption>
</figure>
