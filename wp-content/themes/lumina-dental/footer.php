<?php
/** Site footer: NAP, hours, services, areas, mobile action bar, confirmation overlay. */
$wa = lumina_whatsapp_url();
?>
<footer class="site-footer">
	<div class="footer-grid">
		<div class="footer-brand">
			<a class="brand" href="<?php echo esc_url( home_url( '/' ) ); ?>">
				<span class="brand-mark"><?php echo lumina_icon( 'tooth', 22 ); ?></span>
				<span class="brand-text"><strong><?php echo esc_html( lumina_opt( 'name' ) ); ?></strong><small><?php echo esc_html( lumina_opt( 'tagline' ) ); ?></small></span>
			</a>
			<address>
				<?php echo esc_html( lumina_opt( 'street' ) ); ?><br>
				<?php echo esc_html( trim( lumina_opt( 'city' ) . ' ' . lumina_opt( 'postcode' ) ) ); ?><br>
				<a href="<?php echo esc_url( lumina_tel_url() ); ?>"><?php echo esc_html( lumina_opt( 'phone_display' ) ); ?></a><br>
				<a href="mailto:<?php echo esc_attr( lumina_opt( 'email' ) ); ?>"><?php echo esc_html( lumina_opt( 'email' ) ); ?></a>
			</address>
			<div class="footer-social">
				<?php if ( lumina_opt( 'facebook' ) ) : ?><a class="icon-button" href="<?php echo esc_url( lumina_opt( 'facebook' ) ); ?>" rel="noopener" target="_blank" aria-label="Facebook"><?php echo lumina_icon( 'facebook', 18 ); ?></a><?php endif; ?>
				<?php if ( lumina_opt( 'instagram' ) ) : ?><a class="icon-button" href="<?php echo esc_url( lumina_opt( 'instagram' ) ); ?>" rel="noopener" target="_blank" aria-label="Instagram"><?php echo lumina_icon( 'instagram', 18 ); ?></a><?php endif; ?>
				<?php if ( $wa ) : ?><a class="icon-button" href="<?php echo esc_url( $wa ); ?>" rel="noopener" target="_blank" aria-label="WhatsApp"><?php echo lumina_icon( 'whatsapp', 18 ); ?></a><?php endif; ?>
			</div>
		</div>

		<div>
			<h2 class="footer-title">Opening hours</h2>
			<dl class="hours-list">
				<?php foreach ( lumina_hours_groups() as $g ) : ?>
					<div><dt><?php echo esc_html( $g[0] ); ?></dt><dd><?php echo esc_html( $g[1] ); ?></dd></div>
				<?php endforeach; ?>
			</dl>
		</div>

		<div>
			<h2 class="footer-title">Services</h2>
			<ul class="footer-links">
				<?php foreach ( lumina_services() as $s ) : ?>
					<li><a href="<?php echo esc_url( get_permalink( $s ) ); ?>"><?php echo esc_html( get_the_title( $s ) ); ?></a></li>
				<?php endforeach; ?>
			</ul>
		</div>

		<div>
			<h2 class="footer-title">Areas we serve</h2>
			<ul class="footer-links">
				<?php foreach ( lumina_areas() as $a ) : ?>
					<li><a href="<?php echo esc_url( get_permalink( $a ) ); ?>">Dentist near <?php echo esc_html( get_the_title( $a ) ); ?></a></li>
				<?php endforeach; ?>
			</ul>
		</div>
	</div>
	<p class="footer-word" aria-hidden="true"><?php echo esc_html( strtok( lumina_opt( 'name' ), ' ' ) ); ?></p>
	<nav class="footer-quick" aria-label="Quick links">
		<?php foreach ( array( 'about' => 'About', 'prices' => 'Prices', 'faqs' => 'FAQs', 'reviews' => 'Reviews', 'contact' => 'Contact', 'book' => 'Book online', 'privacy-policy' => 'Privacy policy' ) as $slug => $label ) : ?>
			<a href="<?php echo esc_url( lumina_page_url( $slug ) ); ?>"><?php echo esc_html( $label ); ?></a>
		<?php endforeach; ?>
	</nav>
	<div class="footer-base">
		<p>© <?php echo esc_html( wp_date( 'Y' ) ); ?> <?php echo esc_html( lumina_opt( 'name' ) ); ?>. In an emergency with swelling, fever or trouble breathing or swallowing, go to your nearest emergency department.</p>
		<a href="#top" data-to-top>Back to top</a>
	</div>
</footer>

<div class="mobile-bar" role="group" aria-label="Quick contact">
	<a class="mb-call" href="<?php echo esc_url( lumina_tel_url() ); ?>"><?php echo lumina_icon( 'phone', 20 ); ?><span>Call</span></a>
	<?php if ( $wa ) : ?>
		<a class="mb-wa" href="<?php echo esc_url( $wa ); ?>" target="_blank" rel="noopener"><?php echo lumina_icon( 'whatsapp', 20 ); ?><span>WhatsApp</span></a>
	<?php endif; ?>
	<a class="mb-book" href="<?php echo esc_url( lumina_book_url() ); ?>"><?php echo lumina_icon( 'calendar', 20 ); ?><span>Book</span></a>
</div>

<div class="message-overlay" data-message-overlay role="dialog" aria-modal="true" aria-labelledby="message-title" hidden>
	<div class="message-box" data-message-box tabindex="-1">
		<div class="success-anim" aria-hidden="true">
			<svg viewBox="0 0 64 64" width="64" height="64" fill="none" stroke-linecap="round" stroke-linejoin="round">
				<circle class="success-ring" cx="32" cy="32" r="28" stroke-width="3"/>
				<path class="success-check" d="M20 33l8 8 16-17" stroke-width="4"/>
			</svg>
		</div>
		<h2 id="message-title" data-message-title>Request received</h2>
		<p data-message-text></p>
		<button class="button primary" type="button" data-message-close>Done</button>
	</div>
</div>

<?php wp_footer(); ?>
</body>
</html>
