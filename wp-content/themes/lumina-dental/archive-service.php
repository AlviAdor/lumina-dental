<?php
/** /services/: all treatments as an index, then how we plan care, the first-visit checklist and FAQs. */
get_header();
$services = lumina_services();
?>
<main id="main">
	<?php
	lumina_page_hero( array(
		'label'   => 'Services',
		'title'   => 'Dental services in <span>' . esc_html( lumina_place() ) . '</span>',
		'lead'    => '<p>Every treatment starts with a proper examination and a clear explanation. Choose a service to see what it involves, how long it takes and what it costs.</p>',
		'image'   => 'clinic-exam.jpg',
		'alt'     => 'A dentist examining a patient in a modern treatment room.',
		'actions' => array( array( 'Book appointment', lumina_book_url(), true ), array( 'See prices', lumina_page_url( 'prices' ), false ) ),
	) );
	?>

	<section class="zindex" aria-label="All services">
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
	</section>

	<section class="split" aria-labelledby="plan-title">
		<figure class="split-figure reveal-target">
			<img src="<?php echo esc_url( lumina_img( 'clinic-checkup.jpg' ) ); ?>" width="1066" height="1600" loading="lazy" decoding="async" alt="A patient during a check-up, a dentist's gloved hands holding a mirror.">
			<figcaption>Every plan starts with an examination</figcaption>
		</figure>
		<div class="principles">
			<div class="section-intro reveal-target">
				<p class="section-label">How we plan your care</p>
				<h2 id="plan-title">You decide, with all the facts.</h2>
			</div>
			<ul>
				<?php foreach ( lumina_content( 'principles' ) as $i => $p ) : ?>
					<li class="reveal-target"><span class="n"><?php echo esc_html( str_pad( (string) ( $i + 1 ), 2, '0', STR_PAD_LEFT ) ); ?></span><h3><?php echo esc_html( $p[0] ); ?></h3><p><?php echo esc_html( $p[1] ); ?></p></li>
				<?php endforeach; ?>
			</ul>
		</div>
	</section>

	<section class="zindex" aria-labelledby="safe-title">
		<div class="section-intro reveal-target">
			<p class="section-label">Safety &amp; technology</p>
			<h2 id="safe-title">The same standard in every treatment room.</h2>
		</div>
		<?php lumina_render_features( lumina_content( 'safety' ), 'cols-4' ); ?>
	</section>

	<section class="section-screen" aria-labelledby="sf-title">
		<div class="section-intro reveal-target">
			<p class="section-label">Questions</p>
			<h2 id="sf-title">Before you choose a treatment.</h2>
		</div>
		<?php lumina_render_faq( array_merge( lumina_content( 'faq_groups' )['Treatment & comfort'], array_slice( lumina_content( 'faq_groups' )['Costs & payment'], 0, 2 ) ) ); ?>
	</section>

	<?php get_template_part( 'template-parts/cta' ); ?>
</main>
<?php get_footer();
