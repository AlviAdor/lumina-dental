<?php
/** One treatment: the page people land on from Google when they search "<treatment> in <city>". */
get_header();
the_post();
$id       = get_the_ID();
$title    = get_the_title();
$duration = get_post_meta( $id, '_lumina_duration', true );
$price    = get_post_meta( $id, '_lumina_price', true ) ?: 'Ask for a quote';
$steps    = lumina_parse_steps( $id );
$faqs     = lumina_parse_faqs( $id );
$icon     = get_post_meta( $id, '_lumina_icon', true ) ?: 'tooth';
$others   = lumina_services( array( 'post__not_in' => array( $id ), 'posts_per_page' => 3 ) );

// Pick a hero photo by position so neighbouring services look different.
$photos = array( 'clinic-exam.jpg', 'clinic-closeup.jpg', 'clinic-detail.jpg', 'clinic-checkup.jpg', 'clinic-smile.jpg' );
$photo  = $photos[ (int) get_post_field( 'menu_order', $id ) % count( $photos ) ];

// Reviews that mention this treatment first, then the newest.
$matched = array();
$rest    = array();
foreach ( lumina_reviews() as $r ) {
	$word = strtolower( (string) strtok( (string) get_post_meta( $r->ID, '_lumina_treatment', true ), ' ' ) );
	if ( strlen( $word ) > 3 && false !== stripos( $title, $word ) ) {
		$matched[] = $r;
	} else {
		$rest[] = $r;
	}
}
$reviews = array_slice( array_merge( $matched, $rest ), 0, 3 );

$chips  = '<ul class="fact-chips">';
$chips .= $duration ? '<li>' . esc_html( $duration ) . '</li>' : '';
$chips .= '<li>' . esc_html( $price ) . '</li></ul>';
?>
<main id="main">
	<?php
	lumina_page_hero( array(
		'label'   => 'Service',
		'title'   => esc_html( $title ) . ' in <span>' . esc_html( lumina_place() ) . '</span>',
		'lead'    => '<p>' . esc_html( get_the_excerpt() ) . '</p>' . $chips,
		'image'   => $photo,
		'alt'     => 'A dentist and patient in a clinic treatment room.',
		'actions' => array( array( 'Book this treatment', lumina_book_url( $title ), true ), array( 'Call us', lumina_tel_url(), false ) ),
	) );
	?>

	<section class="section-screen tight-top two-col">
		<article class="prose">
			<?php the_content(); ?>
		</article>
		<aside class="side-card card" aria-label="Quick facts">
			<span class="icon-tile"><?php echo lumina_icon( $icon, 26 ); ?></span>
			<h2>At a glance</h2>
			<dl class="facts">
				<?php if ( $duration ) : ?><div><dt>Typical visit</dt><dd><?php echo esc_html( $duration ); ?></dd></div><?php endif; ?>
				<div><dt>Price</dt><dd><?php echo esc_html( $price ); ?></dd></div>
				<div><dt>Where</dt><dd><?php echo esc_html( lumina_opt( 'street' ) ); ?></dd></div>
				<div><dt>Open</dt><dd><?php $g = lumina_hours_groups(); echo esc_html( $g[0][0] . ', ' . $g[0][1] ); ?></dd></div>
			</dl>
			<a class="button primary wide" href="<?php echo esc_url( lumina_book_url( $title ) ); ?>">Book now</a>
			<p class="side-note">Prices are a guide. You receive an exact written quote after your examination. <a href="<?php echo esc_url( lumina_page_url( 'prices' ) ); ?>">See all prices</a>.</p>
		</aside>
	</section>

	<?php if ( $steps ) : ?>
	<section class="method section-band" aria-labelledby="steps-title">
		<div class="section-intro reveal-target">
			<p class="section-label">What to expect</p>
			<h2 id="steps-title">How <?php echo esc_html( strtolower( $title ) ); ?> works, step by step.</h2>
		</div>
		<ol class="method-grid steps">
			<?php foreach ( $steps as $i => $step ) : ?>
				<li class="reveal-target"><span>Step <?php echo (int) $i + 1; ?></span><p><?php echo esc_html( $step ); ?></p></li>
			<?php endforeach; ?>
		</ol>
	</section>
	<?php endif; ?>

	<section class="zindex" aria-labelledby="why-title">
		<div class="section-intro reveal-target">
			<p class="section-label">Why <?php echo esc_html( lumina_opt( 'name' ) ); ?></p>
			<h2 id="why-title">The same careful standard for every patient.</h2>
		</div>
		<?php lumina_render_features( lumina_content( 'safety' ), 'cols-4' ); ?>
	</section>

	<section class="two-lists section-band" aria-label="Preparing and aftercare">
		<div class="reveal-target">
			<p class="section-label">Before your visit</p>
			<h2>What to bring</h2>
			<?php lumina_render_checklist( lumina_content( 'bring' ) ); ?>
		</div>
		<div class="reveal-target">
			<p class="section-label">After your visit</p>
			<h2>Looking after the result</h2>
			<?php lumina_render_checklist( lumina_content( 'aftercare' ) ); ?>
		</div>
	</section>

	<?php if ( $reviews ) : ?>
	<section class="section-screen" aria-labelledby="rv-title">
		<div class="section-intro reveal-target">
			<p class="section-label">Reviews</p>
			<h2 id="rv-title">What patients say about their visits.</h2>
		</div>
		<div class="card-grid three">
			<?php foreach ( $reviews as $r ) : get_template_part( 'template-parts/review-card', null, array( 'review' => $r ) ); endforeach; ?>
		</div>
	</section>
	<?php endif; ?>

	<?php if ( $faqs ) : ?>
	<section class="section-screen" aria-labelledby="faq-title">
		<div class="section-intro reveal-target">
			<p class="section-label">Questions</p>
			<h2 id="faq-title">Common questions about <?php echo esc_html( strtolower( $title ) ); ?>.</h2>
		</div>
		<?php lumina_render_faq( $faqs ); ?>
	</section>
	<?php endif; ?>

	<?php if ( $others ) : ?>
	<section class="zindex" aria-labelledby="more-title">
		<div class="section-intro reveal-target">
			<p class="section-label">More care</p>
			<h2 id="more-title">Other treatments you may need.</h2>
		</div>
		<ol class="index-list">
			<?php foreach ( $others as $n => $s ) : ?>
				<li class="reveal-target">
					<a class="index-row" href="<?php echo esc_url( get_permalink( $s ) ); ?>">
						<span class="idx"><?php echo esc_html( str_pad( (string) ( $n + 1 ), 2, '0', STR_PAD_LEFT ) ); ?></span>
						<span class="name"><?php echo esc_html( get_the_title( $s ) ); ?></span>
						<span class="desc"><?php echo esc_html( get_the_excerpt( $s ) ); ?></span>
						<span class="meta"><?php echo esc_html( get_post_meta( $s->ID, '_lumina_price', true ) ); ?></span>
						<span class="go"><?php echo lumina_icon( 'arrow', 20 ); ?></span>
					</a>
				</li>
			<?php endforeach; ?>
		</ol>
	</section>
	<?php endif; ?>

	<?php get_template_part( 'template-parts/cta', null, array( 'service' => $title, 'heading' => 'Book your ' . strtolower( $title ) . ' appointment' ) ); ?>
</main>
<?php get_footer();
