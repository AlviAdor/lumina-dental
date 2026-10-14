<?php
/** Closing call-to-action band. Optional $args['service'] pre-selects a treatment. */
$service = $args['service'] ?? '';
$heading = $args['heading'] ?? 'Ready to look after your smile?';
?>
<section class="cta-band section-band reveal-target" aria-labelledby="cta-title">
	<div class="cta-inner">
		<div>
			<p class="section-label">Book a visit</p>
			<h2 id="cta-title"><?php echo esc_html( $heading ); ?></h2>
			<p>Send a request in under a minute. We will call or message to confirm your time.</p>
		</div>
		<div class="hero-actions">
			<a class="button primary" href="<?php echo esc_url( lumina_book_url( $service ) ); ?>"><?php echo lumina_icon( 'calendar', 18 ); ?> Book appointment</a>
			<a class="button secondary" href="<?php echo esc_url( lumina_tel_url() ); ?>"><?php echo lumina_icon( 'phone', 18 ); ?> <?php echo esc_html( lumina_opt( 'phone_display' ) ); ?></a>
		</div>
	</div>
</section>
