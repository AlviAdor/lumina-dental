<?php
/** About: story, values, numbers, team, safety, preparing for a visit. Page text from the editor is shown as the lead. */
get_header();
the_post();
$name = lumina_opt( 'name' );
$city = lumina_opt( 'city' );
?>
<main id="main">
	<?php
	lumina_page_hero( array(
		'label'   => 'About us',
		'title'   => 'Dentistry that takes its <span>time</span>.',
		'lead'    => apply_filters( 'the_content', get_the_content() ),
		'image'   => lumina_vid( 'clinic-hero-poster.jpg' ),
		'alt'     => 'A dentist examining a patient in a bright treatment room.',
		'actions' => array( array( 'Book appointment', lumina_book_url(), true ), array( 'See our prices', lumina_page_url( 'prices' ), false ) ),
	) );
	?>

	<section class="split" aria-labelledby="story-title">
		<figure class="split-figure reveal-target">
			<img src="<?php echo esc_url( lumina_img( 'clinic-smile.jpg' ) ); ?>" width="1600" height="1066" loading="lazy" decoding="async" alt="A patient laughing with the dentist during a relaxed appointment.">
			<figcaption>Relaxed, unhurried appointments</figcaption>
		</figure>
		<div class="principles">
			<div class="section-intro reveal-target">
				<p class="section-label">Our story</p>
				<h2 id="story-title">A neighbourhood clinic built on being straight with people.</h2>
			</div>
			<div class="prose reveal-target">
				<p><?php echo esc_html( $name ); ?> opened in <?php echo esc_html( lumina_opt( 'established' ) ); ?> to offer the kind of dentistry we wanted for our own families: gentle, clearly explained and fairly priced. We are an independent clinic in <?php echo esc_html( lumina_place() ); ?>, so the people you meet at your first visit are the people who look after you at every visit after it.</p>
				<p>We have grown by word of mouth, one patient telling another, and we would like to keep it that way. That means taking the time to listen, explaining your options honestly, and never recommending treatment you do not need.</p>
			</div>
		</div>
	</section>

	<section class="zindex" aria-labelledby="values-title">
		<div class="section-intro reveal-target">
			<p class="section-label">What we stand for</p>
			<h2 id="values-title">Four values behind every appointment.</h2>
		</div>
		<?php lumina_render_features( lumina_content( 'values' ), 'cols-4' ); ?>
	</section>

	<?php get_template_part( 'template-parts/stats' ); ?>

	<?php get_template_part( 'template-parts/team' ); ?>

	<section class="vband vband-short" data-video-wrap aria-label="Inside the clinic">
		<video data-video muted loop playsinline preload="none" poster="<?php echo esc_url( lumina_vid( 'clinic-hero-poster.jpg' ) ); ?>" aria-hidden="true" tabindex="-1">
			<source src="<?php echo esc_url( lumina_vid( 'clinic-hero-360.mp4' ) ); ?>" media="(max-width: 720px)" type="video/mp4">
			<source src="<?php echo esc_url( lumina_vid( 'clinic-hero-720.mp4' ) ); ?>" type="video/mp4">
		</video>
		<button class="vctl" type="button" data-video-toggle aria-pressed="false">Play video</button>
		<div class="vband-copy">
			<p class="section-label">Inside the clinic</p>
			<h2>Calm rooms, <em>clear explanations.</em></h2>
		</div>
	</section>

	<section class="zindex" aria-labelledby="safety-title">
		<div class="section-intro reveal-target">
			<p class="section-label">Safety &amp; technology</p>
			<h2 id="safety-title">Modern equipment, strict hygiene.</h2>
		</div>
		<?php lumina_render_features( lumina_content( 'safety' ), 'cols-4' ); ?>
	</section>

	<section class="split split-reverse" aria-labelledby="prep-title">
		<div class="principles">
			<div class="section-intro reveal-target">
				<p class="section-label">Your first visit</p>
				<h2 id="prep-title">What to bring, and what to expect.</h2>
			</div>
			<div class="reveal-target"><?php lumina_render_checklist( lumina_content( 'bring' ) ); ?></div>
			<p class="side-note reveal-target">Nervous about your first visit? Tell us when you book and we will plan extra time and go at your pace.</p>
		</div>
		<figure class="split-figure reveal-target">
			<img src="<?php echo esc_url( lumina_img( 'clinic-exam.jpg' ) ); ?>" width="1600" height="1066" loading="lazy" decoding="async" alt="A dentist in a mask and gloves examining a patient in a reclined chair.">
			<figcaption>An examination with a clear explanation</figcaption>
		</figure>
	</section>

	<?php get_template_part( 'template-parts/cta', null, array( 'heading' => 'Come and meet the team' ) ); ?>
</main>
<?php get_footer();
