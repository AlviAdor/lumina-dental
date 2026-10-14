<?php
/** Service card. Expects $args['service'] (WP_Post). */
$s     = $args['service'];
$icon  = get_post_meta( $s->ID, '_lumina_icon', true ) ?: 'tooth';
$meta  = array_filter( array( get_post_meta( $s->ID, '_lumina_duration', true ), get_post_meta( $s->ID, '_lumina_price', true ) ) );
?>
<a class="card service-card reveal-target" href="<?php echo esc_url( get_permalink( $s ) ); ?>">
	<span class="icon-tile"><?php echo lumina_icon( $icon, 26 ); ?></span>
	<h3><?php echo esc_html( get_the_title( $s ) ); ?></h3>
	<p><?php echo esc_html( get_the_excerpt( $s ) ); ?></p>
	<?php if ( $meta ) : ?><span class="card-meta"><?php echo esc_html( implode( ' · ', $meta ) ); ?></span><?php endif; ?>
	<span class="card-more">Learn more <?php echo lumina_icon( 'arrow', 16 ); ?></span>
</a>
