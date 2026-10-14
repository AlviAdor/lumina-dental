<?php
/** Counter strip. Every number comes from the clinic data, never hard-coded. */
$years     = max( 1, (int) wp_date( 'Y' ) - (int) lumina_opt( 'established' ) );
$open_days = max( 1, 7 - count( (array) lumina_closed_days() ) );
$cells     = array(
	array( $years, 'Years in practice' ),
	array( count( lumina_services() ), 'Treatments offered' ),
	array( count( lumina_areas() ), 'Neighbourhoods served' ),
	array( $open_days, 'Days open each week' ),
);
?>
<section class="stats" aria-label="The clinic in numbers">
	<?php foreach ( $cells as $c ) : ?>
		<div class="stat reveal-target"><strong data-count="<?php echo (int) $c[0]; ?>"><?php echo (int) $c[0]; ?></strong><span><?php echo esc_html( $c[1] ); ?></span></div>
	<?php endforeach; ?>
</section>
