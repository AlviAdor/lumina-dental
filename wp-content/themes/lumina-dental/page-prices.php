<?php
/** Prices: starting prices pulled from each service, plus what affects cost. Prices live on the services, so they never drift. */
get_header();
the_post();
$services = lumina_services();
?>
<main id="main">
	<?php
	lumina_page_hero( array(
		'label'   => 'Prices',
		'title'   => 'Clear prices, <span>before</span> treatment.',
		'lead'    => apply_filters( 'the_content', get_the_content() ),
		'image'   => 'clinic-closeup.jpg',
		'alt'     => 'Close-up of a dentist polishing a patient\'s teeth.',
		'actions' => array( array( 'Book a consultation', lumina_book_url(), true ) ),
	) );
	?>

	<section class="zindex" aria-labelledby="price-title">
		<div class="section-intro reveal-target">
			<p class="section-label">Starting prices</p>
			<h2 id="price-title">What our treatments typically cost.</h2>
		</div>
		<div class="price-table" role="table" aria-label="Treatment prices">
			<div class="price-row price-head" role="row"><span role="columnheader">Treatment</span><span role="columnheader">Typical visit</span><span role="columnheader">Price</span></div>
			<?php foreach ( $services as $s ) : ?>
				<a class="price-row reveal-target" role="row" href="<?php echo esc_url( get_permalink( $s ) ); ?>">
					<span role="cell" class="p-name"><?php echo esc_html( get_the_title( $s ) ); ?></span>
					<span role="cell" class="p-time"><?php echo esc_html( get_post_meta( $s->ID, '_lumina_duration', true ) ); ?></span>
					<span role="cell" class="p-price"><?php echo esc_html( get_post_meta( $s->ID, '_lumina_price', true ) ?: 'Ask for a quote' ); ?></span>
				</a>
			<?php endforeach; ?>
		</div>
		<p class="side-note">Prices are a guide only. After your examination you receive an exact, itemised quote in writing, and we do not begin until you have agreed to it.</p>
	</section>

	<section class="split split-reverse" aria-labelledby="cost-title">
		<div class="principles">
			<div class="section-intro reveal-target">
				<p class="section-label">Good to know</p>
				<h2 id="cost-title">What affects the cost of treatment.</h2>
			</div>
			<ul>
				<li class="reveal-target"><span class="n">01</span><h3>The condition of your teeth</h3><p>A small filling and a tooth that needs a crown are different treatments with different costs. The examination tells us which you need.</p></li>
				<li class="reveal-target"><span class="n">02</span><h3>The materials</h3><p>Where there is a choice, for example for crowns, we explain the options and what each one means for appearance and lifespan.</p></li>
				<li class="reveal-target"><span class="n">03</span><h3>How many visits you need</h3><p>Some treatments are staged over several visits. The plan we give you shows each stage and the total.</p></li>
			</ul>
		</div>
		<figure class="split-figure reveal-target">
			<img src="<?php echo esc_url( lumina_img( 'clinic-detail.jpg' ) ); ?>" width="1066" height="1600" loading="lazy" decoding="async" alt="A dentist working carefully on a patient wearing braces.">
			<figcaption>Your plan, itemised, before we begin</figcaption>
		</figure>
	</section>

	<section class="section-screen" aria-labelledby="pf-title">
		<div class="section-intro reveal-target">
			<p class="section-label">Questions</p>
			<h2 id="pf-title">About costs and payment.</h2>
		</div>
		<?php lumina_render_faq( lumina_content( 'faq_groups' )['Costs & payment'] ); ?>
	</section>

	<?php get_template_part( 'template-parts/cta', null, array( 'heading' => 'Get a written quote for your treatment' ) ); ?>
</main>
<?php get_footer();
