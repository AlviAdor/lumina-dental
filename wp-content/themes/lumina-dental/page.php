<?php
/** Generic page (Privacy, anything else). */
get_header();
the_post();
?>
<main id="main">
	<?php lumina_page_hero( array( 'title' => esc_html( get_the_title() ) ) ); ?>
	<section class="section-screen tight-top">
		<article class="prose prose-wide"><?php the_content(); ?></article>
	</section>
	<?php get_template_part( 'template-parts/cta' ); ?>
</main>
<?php get_footer();
