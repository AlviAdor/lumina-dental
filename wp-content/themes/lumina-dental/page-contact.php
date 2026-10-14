<?php
/** Contact: address, hours, map and the request form side by side, plus visiting FAQs. */
get_header();
the_post();
?>
<main id="main">
	<?php
	lumina_page_hero( array(
		'label'   => 'Contact',
		'title'   => 'Find <span>' . esc_html( lumina_opt( 'name' ) ) . '</span> in ' . esc_html( lumina_place() ),
		'lead'    => apply_filters( 'the_content', get_the_content() ),
		'actions' => array( array( 'Call ' . lumina_opt( 'phone_display' ), lumina_tel_url(), true ), array( 'Get directions', lumina_directions_url(), false ) ),
	) );
	?>
	<section class="section-screen tight-top">
		<div class="booking-grid">
			<?php get_template_part( 'template-parts/contact-info' ); ?>
			<?php get_template_part( 'template-parts/booking-form' ); ?>
		</div>
	</section>

	<section class="two-lists section-band" aria-label="Urgent care and your first visit">
		<div class="reveal-target">
			<p class="section-label">Urgent care</p>
			<h2>Toothache or an accident?</h2>
			<p class="prose">Do not wait for an online reply. Call <a href="<?php echo esc_url( lumina_tel_url() ); ?>"><strong><?php echo esc_html( lumina_opt( 'phone_display' ) ); ?></strong></a> and we will do our best to see you the same day. If you have swelling with a fever, or trouble breathing or swallowing, go to your nearest emergency department.</p>
			<?php $em = get_page_by_path( 'emergency-dental-care', OBJECT, 'service' ); ?>
			<a class="tlink" href="<?php echo esc_url( $em ? get_permalink( $em ) : get_post_type_archive_link( 'service' ) ); ?>">Emergency dental care <?php echo lumina_icon( 'arrow', 14 ); ?></a>
		</div>
		<div class="reveal-target">
			<p class="section-label">First visit</p>
			<h2>What to bring</h2>
			<?php lumina_render_checklist( lumina_content( 'bring' ) ); ?>
		</div>
	</section>

	<section class="section-screen" aria-labelledby="cf-title">
		<div class="section-intro reveal-target">
			<p class="section-label">Questions</p>
			<h2 id="cf-title">Before you visit.</h2>
		</div>
		<?php lumina_render_faq( lumina_content( 'faq_groups' )['Booking & visits'] ); ?>
		<p class="section-more"><a class="tlink" href="<?php echo esc_url( lumina_page_url( 'faqs' ) ); ?>">All questions <?php echo lumina_icon( 'arrow', 14 ); ?></a></p>
	</section>
</main>
<?php get_footer();
