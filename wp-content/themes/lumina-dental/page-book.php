<?php
/** Dedicated booking page: the form plus reassurance. Shared by every "Book" button. */
get_header();
the_post();
?>
<main id="main">
	<?php
	lumina_page_hero( array(
		'label' => 'Book online',
		'title' => 'Book your visit at <span>' . esc_html( lumina_opt( 'name' ) ) . '</span>',
		'lead'  => apply_filters( 'the_content', get_the_content() ),
	) );
	?>
	<section class="section-screen tight-top">
		<div class="booking-grid">
			<?php get_template_part( 'template-parts/booking-form' ); ?>
			<aside class="side-card card" aria-label="What happens next">
				<h2>What happens next</h2>
				<ol class="next-steps">
					<li><strong>We receive your request.</strong> You will see a confirmation straight away.</li>
					<li><strong>We contact you.</strong> A call or message to confirm the exact time.</li>
					<li><strong>You visit.</strong> Arrive five minutes early and bring any previous dental records.</li>
				</ol>
				<p class="side-note">Toothache, swelling or a broken tooth? Do not wait for an online reply, <a href="<?php echo esc_url( lumina_tel_url() ); ?>">call <?php echo esc_html( lumina_opt( 'phone_display' ) ); ?></a>.</p>
			</aside>
		</div>
	</section>

	<section class="two-lists section-band" aria-label="Preparing for your visit">
		<div class="reveal-target">
			<p class="section-label">Before you come</p>
			<h2>What to bring</h2>
			<?php lumina_render_checklist( lumina_content( 'bring' ) ); ?>
		</div>
		<div class="reveal-target">
			<p class="section-label">Opening hours</p>
			<h2>When to find us</h2>
			<dl class="hours-list">
				<?php foreach ( lumina_hours_groups() as $g ) : ?>
					<div><dt><?php echo esc_html( $g[0] ); ?></dt><dd><?php echo esc_html( $g[1] ); ?></dd></div>
				<?php endforeach; ?>
			</dl>
		</div>
	</section>

	<section class="section-screen" aria-labelledby="bf-title">
		<div class="section-intro reveal-target">
			<p class="section-label">Questions</p>
			<h2 id="bf-title">Booking and your first visit.</h2>
		</div>
		<?php lumina_render_faq( array_merge( lumina_content( 'faq_groups' )['Booking & visits'], array_slice( lumina_content( 'faq_groups' )['Costs & payment'], 0, 1 ) ) ); ?>
	</section>
</main>
<?php get_footer();
