<?php get_header(); ?>
<main id="main">
	<section class="hero hero-compact section-screen" aria-labelledby="page-title">
		<div class="hero-copy">
			<?php get_template_part( 'template-parts/breadcrumbs' ); ?>
			<p class="section-label">404</p>
			<h1 id="page-title">That page <span>doesn't exist</span></h1>
			<p class="hero-subtitle">The link may be old or mistyped. Try one of these instead:</p>
			<div class="hero-actions">
				<a class="button primary" href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a>
				<a class="button secondary" href="<?php echo esc_url( get_post_type_archive_link( 'service' ) ); ?>">Our services</a>
				<a class="button secondary" href="<?php echo esc_url( lumina_book_url() ); ?>">Book appointment</a>
			</div>
		</div>
	</section>
</main>
<?php get_footer();
