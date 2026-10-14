<?php
/** FAQs: every group of questions on one page, with a jump list. */
get_header();
the_post();
$groups = lumina_content( 'faq_groups' );
?>
<main id="main">
	<?php
	lumina_page_hero( array(
		'label'   => 'FAQs',
		'title'   => 'Questions, <span>answered</span>.',
		'lead'    => apply_filters( 'the_content', get_the_content() ),
		'actions' => array( array( 'Ask us a question', lumina_page_url( 'contact' ), true ) ),
	) );
	?>

	<section class="faq-page">
		<nav class="faq-jump" aria-label="Jump to a topic">
			<?php foreach ( array_keys( $groups ) as $i => $g ) : ?>
				<a href="#faq-<?php echo (int) $i; ?>"><?php echo esc_html( $g ); ?></a>
			<?php endforeach; ?>
		</nav>
		<div class="faq-groups">
			<?php foreach ( $groups as $g => $faqs ) : $i = array_search( $g, array_keys( $groups ), true ); ?>
				<section id="faq-<?php echo (int) $i; ?>" class="faq-group" aria-labelledby="faq-h-<?php echo (int) $i; ?>">
					<h2 id="faq-h-<?php echo (int) $i; ?>"><?php echo esc_html( $g ); ?></h2>
					<?php lumina_render_faq( $faqs ); ?>
				</section>
			<?php endforeach; ?>
		</div>
	</section>

	<?php get_template_part( 'template-parts/cta', null, array( 'heading' => 'Still have a question? Just ask' ) ); ?>
</main>
<?php get_footer();
