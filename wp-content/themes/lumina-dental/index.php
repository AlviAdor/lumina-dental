<?php
/** Fallback template (blog posts, search). The clinic site itself rarely needs it. */
get_header();
?>
<main id="main">
	<section class="hero hero-compact section-screen">
		<div class="hero-copy">
			<h1><?php echo is_search() ? 'Search results' : esc_html( get_the_title( get_option( 'page_for_posts' ) ) ?: 'News' ); ?></h1>
		</div>
	</section>
	<section class="section-screen tight-top">
		<div class="prose prose-wide">
			<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
				<article>
					<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
					<?php the_excerpt(); ?>
				</article>
			<?php endwhile; the_posts_pagination(); else : ?>
				<p>Nothing found.</p>
			<?php endif; ?>
		</div>
	</section>
</main>
<?php get_footer();
