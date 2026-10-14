<?php
/** Home page: hero → trust strip → services → how it works → reviews → areas → FAQ → booking. */
get_header();

$state    = lumina_open_state();
$stats    = lumina_review_stats();
$services = lumina_services();
$areas    = lumina_areas();
$reviews  = lumina_reviews( 3 );
$years    = max( 1, (int) wp_date( 'Y' ) - (int) lumina_opt( 'established' ) );
$groups   = lumina_hours_groups();
?>
<main id="main">
	<?php
	$vid       = get_template_directory_uri() . '/assets/video/';
	$img       = get_template_directory_uri() . '/assets/img/';
	$svc_url   = get_post_type_archive_link( 'service' );
	$whiten    = get_page_by_path( 'teeth-whitening', OBJECT, 'service' );
	$whiten_to = $whiten ? get_permalink( $whiten ) : $svc_url;
	$open_days = max( 1, 7 - count( (array) lumina_closed_days() ) );
	$ticker    = array_map( 'get_the_title', $services ) ?: array( 'Check-ups', 'Whitening', 'Braces', 'Implants', 'Emergency care' );
	?>

	<section class="zhero" aria-labelledby="hero-title">
		<div class="zhero-media" data-video-wrap>
			<video data-video autoplay muted loop playsinline preload="metadata" poster="<?php echo esc_url( $vid . 'clinic-hero-poster.jpg' ); ?>" aria-hidden="true" tabindex="-1">
				<source src="<?php echo esc_url( $vid . 'clinic-hero-360.mp4' ); ?>" media="(max-width: 720px)" type="video/mp4">
				<source src="<?php echo esc_url( $vid . 'clinic-hero-720.mp4' ); ?>" type="video/mp4">
			</video>
			<button class="vctl" type="button" data-video-toggle aria-pressed="true">Pause video</button>
			<div class="zhero-copy">
				<p class="open-badge<?php echo $state['open'] ? ' is-open' : ''; ?>" data-open-badge><span class="dot" aria-hidden="true"></span><span data-open-text><?php echo esc_html( $state['label'] ); ?></span></p>
				<h1 id="hero-title">Gentle, modern dentistry in <span><?php echo esc_html( lumina_place() ); ?></span></h1>
			</div>
		</div>
		<div class="zhero-bar">
			<p class="hero-subtitle"><?php echo esc_html( lumina_opt( 'name' ) ); ?> looks after the whole family: check-ups, whitening, braces, implants and same-day emergency care, explained clearly and priced up front.</p>
			<div class="hero-actions">
				<a class="button primary" href="<?php echo esc_url( lumina_book_url() ); ?>">Book appointment</a>
				<a class="tlink" href="<?php echo esc_url( lumina_tel_url() ); ?>"><?php echo esc_html( lumina_opt( 'phone_display' ) ); ?></a>
			</div>
			<ul class="hero-facts">
				<?php if ( $stats['count'] ) : ?>
					<li><?php echo lumina_stars( 5 ); ?> <strong><?php echo esc_html( $stats['average'] ); ?></strong> from <?php echo (int) $stats['count']; ?> reviews</li>
				<?php endif; ?>
				<li><?php echo lumina_icon( 'shield', 16 ); ?> <?php echo (int) $years; ?>+ years in <?php echo esc_html( lumina_opt( 'area' ) ?: lumina_opt( 'city' ) ); ?></li>
				<li><?php echo lumina_icon( 'clock', 16 ); ?> <?php echo esc_html( $groups[0][0] . ' ' . $groups[0][1] ); ?></li>
			</ul>
		</div>
	</section>

	<div class="marquee" aria-hidden="true">
		<div class="marquee-track">
			<?php for ( $r = 0; $r < 2; $r++ ) : foreach ( $ticker as $t ) : ?><span><?php echo esc_html( $t ); ?></span><?php endforeach; endfor; ?>
		</div>
	</div>

	<?php get_template_part( 'template-parts/scroll-statement' ); ?>

	<section class="zindex" id="services" aria-labelledby="services-title">
		<div class="section-intro reveal-target">
			<p class="section-label">Treatments</p>
			<h2 id="services-title">Dental care for every age, from first check-ups to complex treatment.</h2>
		</div>
		<ol class="index-list">
			<?php foreach ( $services as $n => $s ) :
				$meta = array_filter( array( get_post_meta( $s->ID, '_lumina_duration', true ), get_post_meta( $s->ID, '_lumina_price', true ) ) );
				?>
				<li class="reveal-target">
					<a class="index-row" href="<?php echo esc_url( get_permalink( $s ) ); ?>">
						<span class="idx"><?php echo esc_html( str_pad( (string) ( $n + 1 ), 2, '0', STR_PAD_LEFT ) ); ?></span>
						<span class="name"><?php echo esc_html( get_the_title( $s ) ); ?></span>
						<span class="desc"><?php echo esc_html( get_the_excerpt( $s ) ); ?></span>
						<span class="meta"><?php echo esc_html( implode( ' · ', $meta ) ); ?></span>
						<span class="go"><?php echo lumina_icon( 'arrow', 20 ); ?></span>
					</a>
				</li>
			<?php endforeach; ?>
		</ol>
		<p class="section-more"><a class="tlink" href="<?php echo esc_url( $svc_url ); ?>">View all services <?php echo lumina_icon( 'arrow', 14 ); ?></a></p>
	</section>

	<?php get_template_part( 'template-parts/stats' ); ?>

	<section class="split" aria-labelledby="standard-title">
		<figure class="split-figure reveal-target">
			<img src="<?php echo esc_url( $img . 'clinic-checkup.jpg' ); ?>" width="1066" height="1600" loading="lazy" decoding="async" alt="A patient looking up during a dental check-up, a dentist's gloved hands holding a mirror and a handpiece.">
			<figcaption>Every visit begins with a conversation</figcaption>
		</figure>
		<div class="principles">
			<div class="section-intro reveal-target">
				<p class="section-label">The Lumina standard</p>
				<h2 id="standard-title">Three promises we make to every patient.</h2>
			</div>
			<ul>
				<li class="reveal-target"><span class="n">01</span><h3>Comfort first</h3><p>Effective numbing, a gentle technique and breaks whenever you ask. If you are anxious, tell us. We will go at your pace.</p></li>
				<li class="reveal-target"><span class="n">02</span><h3>Clarity always</h3><p>You get a diagnosis, a written plan and an itemised cost before any treatment starts, and we explain it in plain words.</p></li>
				<li class="reveal-target"><span class="n">03</span><h3>Care that continues</h3><p>Aftercare advice, reminders for your next check-up, and a number to call if anything worries you after you leave.</p></li>
			</ul>
		</div>
	</section>

	<section class="spread" aria-label="Treatment spotlights">
		<figure>
			<a href="<?php echo esc_url( $svc_url ); ?>">
				<img src="<?php echo esc_url( $img . 'clinic-closeup.jpg' ); ?>" width="1600" height="1066" loading="lazy" decoding="async" alt="Close-up of a dentist polishing a patient's teeth with a handpiece.">
				<figcaption><span>Restorative &amp; cosmetic care</span><span>01</span></figcaption>
			</a>
		</figure>
		<figure>
			<a href="<?php echo esc_url( $svc_url ); ?>">
				<img src="<?php echo esc_url( $img . 'clinic-detail.jpg' ); ?>" width="1066" height="1600" loading="lazy" decoding="async" alt="A dentist adjusting a patient's braces with a fine instrument.">
				<figcaption><span>Braces &amp; alignment</span><span>02</span></figcaption>
			</a>
		</figure>
	</section>

	<section class="vband" data-video-wrap aria-labelledby="whiten-title">
		<video data-video muted loop playsinline preload="none" poster="<?php echo esc_url( $vid . 'whitening-poster.jpg' ); ?>" aria-hidden="true" tabindex="-1">
			<source src="<?php echo esc_url( $vid . 'whitening-360.mp4' ); ?>" media="(max-width: 720px)" type="video/mp4">
			<source src="<?php echo esc_url( $vid . 'whitening-720.mp4' ); ?>" type="video/mp4">
		</video>
		<button class="vctl" type="button" data-video-toggle aria-pressed="false">Play video</button>
		<div class="vband-copy">
			<p class="section-label">Spotlight</p>
			<h2 id="whiten-title">A brighter smile, <em>done properly.</em></h2>
			<p>Professional whitening is planned around your teeth and your sensitivity. We check your teeth first, explain the options, and give you the price before we begin.</p>
			<a class="button" href="<?php echo esc_url( $whiten_to ); ?>">Explore whitening</a>
		</div>
	</section>

	<section class="zindex" aria-labelledby="safety-title">
		<div class="section-intro reveal-target">
			<p class="section-label">Safety &amp; technology</p>
			<h2 id="safety-title">Modern equipment, strict hygiene, no shortcuts.</h2>
		</div>
		<?php lumina_render_features( lumina_content( 'safety' ), 'cols-4' ); ?>
	</section>

	<section class="method section-band" aria-labelledby="method-title">
		<div class="section-intro reveal-target">
			<p class="section-label">Your visit</p>
			<h2 id="method-title">What to expect, from the first message to your follow-up.</h2>
		</div>
		<div class="method-grid">
			<article class="reveal-target"><span>1 · Request</span><p>Send an appointment request online, call, or message us on WhatsApp. It takes under a minute.</p></article>
			<article class="reveal-target"><span>2 · Confirm</span><p>We contact you to confirm a time that works, and tell you what to bring.</p></article>
			<article class="reveal-target"><span>3 · Treat</span><p>Examination, a clear explanation and a written plan with costs. You decide before we start.</p></article>
			<article class="reveal-target"><span>4 · Follow up</span><p>Aftercare advice, reminders for your next check-up, and a number to call if anything worries you.</p></article>
		</div>
	</section>

	<?php get_template_part( 'template-parts/team' ); ?>

	<section class="section-screen" id="reviews" aria-labelledby="reviews-title">
		<div class="section-intro reveal-target">
			<p class="section-label">Reviews</p>
			<h2 id="reviews-title"><?php echo $stats['count'] ? esc_html( sprintf( 'Rated %s out of 5 by %d patients.', $stats['average'], $stats['count'] ) ) : 'What patients say about their visits.'; ?></h2>
		</div>
		<div class="card-grid three">
			<?php foreach ( $reviews as $r ) : get_template_part( 'template-parts/review-card', null, array( 'review' => $r ) ); endforeach; ?>
		</div>
		<p class="section-more">
			<a class="button secondary" href="<?php echo esc_url( lumina_page_url( 'reviews' ) ); ?>">Read more reviews <?php echo lumina_icon( 'arrow', 16 ); ?></a>
			<?php if ( lumina_opt( 'google_review_url' ) ) : ?><a class="button secondary" href="<?php echo esc_url( lumina_opt( 'google_review_url' ) ); ?>" target="_blank" rel="noopener">Leave a Google review</a><?php endif; ?>
		</p>
	</section>

	<section class="split split-reverse" id="care-guide" aria-labelledby="tips-title">
		<div class="principles">
			<div class="section-intro reveal-target">
				<p class="section-label">Care guide</p>
				<h2 id="tips-title">Six habits for a healthier mouth.</h2>
			</div>
			<ul>
				<?php foreach ( lumina_content( 'tips' ) as $i => $t ) : ?>
					<li class="reveal-target"><span class="n"><?php echo esc_html( str_pad( (string) ( $i + 1 ), 2, '0', STR_PAD_LEFT ) ); ?></span><h3><?php echo esc_html( $t[0] ); ?></h3><p><?php echo esc_html( $t[1] ); ?></p></li>
				<?php endforeach; ?>
			</ul>
		</div>
		<figure class="split-figure reveal-target">
			<img src="<?php echo esc_url( $img . 'clinic-exam.jpg' ); ?>" width="1600" height="1066" loading="lazy" decoding="async" alt="A dentist examining a patient in a reclined chair.">
			<figcaption>Small habits, done every day</figcaption>
		</figure>
	</section>

	<section class="section-band" id="areas" aria-labelledby="areas-title">
		<div class="section-intro reveal-target">
			<p class="section-label">Where we are</p>
			<h2 id="areas-title">Easy to reach from across <?php echo esc_html( lumina_opt( 'city' ) ); ?>.</h2>
			<p><?php echo esc_html( lumina_opt( 'name' ) ); ?> is on <?php echo esc_html( lumina_opt( 'street' ) ); ?>. Patients regularly travel to us from these neighbourhoods:</p>
		</div>
		<ul class="chip-list reveal-target">
			<?php foreach ( $areas as $a ) : ?>
				<li><a class="chip chip-link" href="<?php echo esc_url( get_permalink( $a ) ); ?>"><?php echo lumina_icon( 'pin', 16 ); ?> Dentist near <?php echo esc_html( get_the_title( $a ) ); ?></a></li>
			<?php endforeach; ?>
		</ul>
	</section>

	<section class="section-screen" id="faq" aria-labelledby="faq-title">
		<div class="section-intro reveal-target">
			<p class="section-label">Questions</p>
			<h2 id="faq-title">Quick answers before you book.</h2>
		</div>
		<?php
		$faq_all = lumina_content( 'faq_groups' );
		lumina_render_faq( array_merge( array_slice( $faq_all['Booking & visits'], 0, 2 ), array_slice( $faq_all['Treatment & comfort'], 0, 2 ), array_slice( $faq_all['Costs & payment'], 0, 1 ), array_slice( $faq_all['Children & families'], 0, 1 ) ) );
		?>
		<p class="section-more"><a class="tlink" href="<?php echo esc_url( lumina_page_url( 'faqs' ) ); ?>">All questions <?php echo lumina_icon( 'arrow', 14 ); ?></a></p>
	</section>

	<a class="mega" href="<?php echo esc_url( lumina_book_url() ); ?>">
		<span>Book a visit</span>
		<svg viewBox="0 0 48 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><path d="M0 12h46M36 2l10 10-10 10"/></svg>
	</a>

	<section class="section-band booking-section" aria-label="Book an appointment">
		<div class="booking-grid">
			<?php get_template_part( 'template-parts/booking-form' ); ?>
			<?php get_template_part( 'template-parts/contact-info' ); ?>
		</div>
	</section>
</main>
<?php
get_footer();
