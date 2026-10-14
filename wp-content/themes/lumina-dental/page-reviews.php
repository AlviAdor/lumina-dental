<?php
/** Reviews page: rating summary + every review, how reviews are handled, and next steps. */
get_header();
the_post();
$stats   = lumina_review_stats();
$reviews = lumina_reviews();
$lead    = apply_filters( 'the_content', get_the_content() );
if ( $stats['count'] ) {
	$lead .= '<p class="rating-summary"><strong>' . esc_html( $stats['average'] ) . '</strong> ' . lumina_stars( (int) round( $stats['average'] ) ) . ' <span>from ' . (int) $stats['count'] . ' reviews</span></p>';
}
$actions = array();
if ( lumina_opt( 'google_review_url' ) ) {
	$actions[] = array( 'Leave a Google review', lumina_opt( 'google_review_url' ), true );
}
$actions[] = array( 'Book appointment', lumina_book_url(), ! $actions );
?>
<main id="main">
	<?php
	lumina_page_hero( array(
		'label'   => 'Reviews',
		'title'   => 'What patients say about <span>' . esc_html( lumina_opt( 'name' ) ) . '</span>',
		'lead'    => $lead,
		'image'   => 'clinic-smile.jpg',
		'alt'     => 'A patient laughing with the dentist.',
		'actions' => $actions,
	) );
	?>
	<section class="section-screen tight-top" aria-label="Patient reviews">
		<div class="card-grid three">
			<?php foreach ( $reviews as $r ) : get_template_part( 'template-parts/review-card', null, array( 'review' => $r ) ); endforeach; ?>
		</div>
	</section>

	<section class="split" aria-labelledby="rh-title">
		<figure class="split-figure reveal-target">
			<img src="<?php echo esc_url( lumina_img( 'clinic-checkup.jpg' ) ); ?>" width="1066" height="1600" loading="lazy" decoding="async" alt="A patient during a check-up.">
			<figcaption>Honest feedback helps us improve</figcaption>
		</figure>
		<div class="principles">
			<div class="section-intro reveal-target">
				<p class="section-label">How we handle reviews</p>
				<h2 id="rh-title">Shared exactly as written.</h2>
			</div>
			<ul>
				<li class="reveal-target"><span class="n">01</span><h3>No editing</h3><p>We publish reviews in the patient's own words. We do not trim the critical ones.</p></li>
				<li class="reveal-target"><span class="n">02</span><h3>We read every one</h3><p>Feedback about waiting times, explanations or comfort goes straight to the team and changes how we work.</p></li>
				<li class="reveal-target"><span class="n">03</span><h3>Tell us directly, too</h3><p>If something was not right, call us. We would always rather put it right than read about it later.</p></li>
			</ul>
		</div>
	</section>

	<?php get_template_part( 'template-parts/stats' ); ?>

	<?php get_template_part( 'template-parts/cta' ); ?>
</main>
<?php get_footer();
