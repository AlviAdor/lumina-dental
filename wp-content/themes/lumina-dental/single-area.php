<?php
/** One neighbourhood page: "dentist near X". Unique copy + directions + services + map. */
get_header();
the_post();
$id        = get_the_ID();
$title     = get_the_title();
$distance  = get_post_meta( $id, '_lumina_distance', true );
$landmarks = array_filter( array_map( 'trim', explode( ',', (string) get_post_meta( $id, '_lumina_landmarks', true ) ) ) );
$others    = lumina_areas( array( 'post__not_in' => array( $id ) ) );
?>
<main id="main">
	<?php
	lumina_page_hero( array(
		'label'   => 'Areas we serve',
		'title'   => 'Dentist near <span>' . esc_html( $title ) . '</span>, ' . esc_html( lumina_opt( 'city' ) ),
		'lead'    => '<p>' . esc_html( get_the_excerpt() ) . '</p>',
		'image'   => 'clinic-smile.jpg',
		'alt'     => 'A patient smiling with the dentist in a bright treatment room.',
		'actions' => array( array( 'Book appointment', lumina_book_url(), true ), array( 'Get directions', lumina_directions_url(), false ) ),
	) );
	?>

	<section class="section-screen tight-top two-col">
		<article class="prose"><?php the_content(); ?></article>
		<aside class="side-card card" aria-label="Getting here">
			<span class="icon-tile"><?php echo lumina_icon( 'pin', 26 ); ?></span>
			<h2>Getting here from <?php echo esc_html( $title ); ?></h2>
			<?php if ( $distance ) : ?><p><?php echo esc_html( $distance ); ?>.</p><?php endif; ?>
			<p><strong>Our address:</strong><br><?php echo esc_html( lumina_address_line() ); ?></p>
			<?php if ( $landmarks ) : ?>
				<p><strong>Nearby landmarks:</strong></p>
				<ul class="chip-list">
					<?php foreach ( $landmarks as $l ) : ?><li><span class="chip"><?php echo esc_html( $l ); ?></span></li><?php endforeach; ?>
				</ul>
			<?php endif; ?>
		</aside>
	</section>

	<section class="zindex" aria-labelledby="why-title">
		<div class="section-intro reveal-target">
			<p class="section-label">Why patients choose us</p>
			<h2 id="why-title">Worth the trip from <?php echo esc_html( $title ); ?>.</h2>
		</div>
		<?php lumina_render_features( lumina_content( 'principles' ), 'cols-3' ); ?>
	</section>

	<section class="zindex" aria-labelledby="svc-title">
		<div class="section-intro reveal-target">
			<p class="section-label">Treatments</p>
			<h2 id="svc-title">Dental care available to patients from <?php echo esc_html( $title ); ?>.</h2>
		</div>
		<ol class="index-list">
			<?php foreach ( lumina_services() as $n => $s ) : ?>
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

	<section class="section-screen" aria-labelledby="af-title">
		<div class="section-intro reveal-target">
			<p class="section-label">Questions</p>
			<h2 id="af-title">Planning your visit from <?php echo esc_html( $title ); ?>.</h2>
		</div>
		<?php
		lumina_render_faq( array(
			array( 'How do I get to the clinic?', 'Our address is ' . lumina_address_line() . '. Tap Get directions above for a route from where you are, or tell us your starting point when we confirm your appointment and we will suggest the easiest way.' ),
			array( 'When are you open?', 'We are open ' . implode( ', ', array_map( function ( $g ) { return $g[0] . ' ' . $g[1]; }, lumina_hours_groups() ) ) . '.' ),
			array( 'What if I have a dental emergency?', 'Call ' . lumina_opt( 'phone_display' ) . ' and we will do our best to see you the same day.' ),
		) );
		?>
	</section>

	<section class="section-band" aria-label="Map and booking">
		<div class="booking-grid">
			<?php get_template_part( 'template-parts/contact-info' ); ?>
			<?php get_template_part( 'template-parts/booking-form' ); ?>
		</div>
	</section>

	<?php if ( $others ) : ?>
	<section class="section-screen" aria-label="Other areas">
		<div class="section-intro reveal-target"><p class="section-label">Also serving</p></div>
		<ul class="chip-list reveal-target">
			<?php foreach ( $others as $a ) : ?><li><a class="chip chip-link" href="<?php echo esc_url( get_permalink( $a ) ); ?>">Dentist near <?php echo esc_html( get_the_title( $a ) ); ?></a></li><?php endforeach; ?>
		</ul>
	</section>
	<?php endif; ?>
</main>
<?php get_footer();
