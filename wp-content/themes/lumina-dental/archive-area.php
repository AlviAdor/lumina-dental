<?php
/** /areas-we-serve/: every neighbourhood, getting-here advice, hours and the form. */
get_header();
?>
<main id="main">
	<?php
	lumina_page_hero( array(
		'label'   => 'Areas we serve',
		'title'   => 'A dentist close to you in <span>' . esc_html( lumina_opt( 'city' ) ) . '</span>',
		'lead'    => '<p>Find the quickest way to reach ' . esc_html( lumina_opt( 'name' ) ) . ' from your neighbourhood.</p>',
		'image'   => 'clinic-smile.jpg',
		'alt'     => 'A patient smiling in the dental chair while talking with the dentist.',
		'actions' => array( array( 'Get directions', lumina_directions_url(), true ), array( 'Book appointment', lumina_book_url(), false ) ),
	) );
	?>

	<section class="zindex" aria-label="Neighbourhoods">
		<ol class="index-list">
			<?php $n = 0; while ( have_posts() ) : the_post(); $n++; ?>
				<li class="reveal-target">
					<a class="index-row" href="<?php the_permalink(); ?>">
						<span class="idx"><?php echo esc_html( str_pad( (string) $n, 2, '0', STR_PAD_LEFT ) ); ?></span>
						<span class="name">Dentist near <?php the_title(); ?></span>
						<span class="desc"><?php echo esc_html( get_the_excerpt() ); ?></span>
						<span class="meta"><?php echo esc_html( get_post_meta( get_the_ID(), '_lumina_distance', true ) ); ?></span>
						<span class="go"><?php echo lumina_icon( 'arrow', 20 ); ?></span>
					</a>
				</li>
			<?php endwhile; ?>
		</ol>
	</section>

	<?php get_template_part( 'template-parts/stats' ); ?>

	<section class="split split-reverse" aria-labelledby="getting-title">
		<div class="principles">
			<div class="section-intro reveal-target">
				<p class="section-label">Getting here</p>
				<h2 id="getting-title">Easy to reach, easy to plan around.</h2>
			</div>
			<ul>
				<li class="reveal-target"><span class="n">01</span><h3>Tell us where you are coming from</h3><p>When we confirm your appointment we are happy to suggest the easiest route from your neighbourhood.</p></li>
				<li class="reveal-target"><span class="n">02</span><h3>Plan around busy hours</h3><p>Parking is limited at busy times. If you are coming by car, a mid-morning or evening slot is usually easiest.</p></li>
				<li class="reveal-target"><span class="n">03</span><h3>Running late?</h3><p>Call us. We would always rather know, and we will do our best to keep your visit on track.</p></li>
			</ul>
		</div>
		<figure class="split-figure reveal-target">
			<img src="<?php echo esc_url( lumina_img( 'clinic-exam.jpg' ) ); ?>" width="1600" height="1066" loading="lazy" decoding="async" alt="A treatment room with a dentist and a patient.">
			<figcaption><?php echo esc_html( lumina_address_line() ); ?></figcaption>
		</figure>
	</section>

	<section class="section-band" aria-label="Map and contact">
		<div class="booking-grid">
			<?php get_template_part( 'template-parts/contact-info' ); ?>
			<?php get_template_part( 'template-parts/booking-form' ); ?>
		</div>
	</section>
</main>
<?php get_footer();
