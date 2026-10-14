<?php
/** Address, tap-to-call, hours and click-to-load map. */
$wa    = lumina_whatsapp_url();
$state = lumina_open_state();
?>
<div class="contact-card card">
	<h2>Visit us</h2>
	<p class="open-badge<?php echo $state['open'] ? ' is-open' : ''; ?>" data-open-badge><span class="dot" aria-hidden="true"></span><span data-open-text><?php echo esc_html( $state['label'] ); ?></span></p>
	<ul class="contact-list">
		<li><?php echo lumina_icon( 'pin', 20 ); ?><span><?php echo esc_html( lumina_address_line() ); ?></span></li>
		<li><?php echo lumina_icon( 'phone', 20 ); ?><a href="<?php echo esc_url( lumina_tel_url() ); ?>"><?php echo esc_html( lumina_opt( 'phone_display' ) ); ?></a></li>
		<?php if ( $wa ) : ?><li><?php echo lumina_icon( 'whatsapp', 20 ); ?><a href="<?php echo esc_url( $wa ); ?>" target="_blank" rel="noopener">Message on WhatsApp</a></li><?php endif; ?>
		<li><?php echo lumina_icon( 'mail', 20 ); ?><a href="mailto:<?php echo esc_attr( lumina_opt( 'email' ) ); ?>"><?php echo esc_html( lumina_opt( 'email' ) ); ?></a></li>
	</ul>
	<h3 class="mini-title"><?php echo lumina_icon( 'clock', 18 ); ?> Opening hours</h3>
	<dl class="hours-list">
		<?php foreach ( lumina_hours_groups() as $g ) : ?>
			<div><dt><?php echo esc_html( $g[0] ); ?></dt><dd><?php echo esc_html( $g[1] ); ?></dd></div>
		<?php endforeach; ?>
	</dl>
	<div class="map-wrap" data-map>
		<div class="map-placeholder">
			<?php echo lumina_icon( 'pin', 28 ); ?>
			<p><?php echo esc_html( lumina_address_line() ); ?></p>
			<button class="button secondary" type="button" data-map-load>Show map</button>
			<small>Loads Google Maps. Nothing is sent to Google until you tap.</small>
		</div>
	</div>
	<div class="hero-actions">
		<a class="button primary" href="<?php echo esc_url( lumina_directions_url() ); ?>" target="_blank" rel="noopener"><?php echo lumina_icon( 'pin', 18 ); ?> Get directions</a>
		<a class="button secondary" href="<?php echo esc_url( lumina_tel_url() ); ?>"><?php echo lumina_icon( 'phone', 18 ); ?> Call</a>
	</div>
</div>
