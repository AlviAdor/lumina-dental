<?php
/**
 * Editorial header for inner pages.
 * Args: label, title (may contain <span>/<em>), lead, image (file in assets/img or assets/video poster URL), alt, actions.
 */
$a       = wp_parse_args( $args, array( 'label' => '', 'title' => '', 'lead' => '', 'image' => '', 'alt' => '', 'actions' => array() ) );
$has_img = (bool) $a['image'];
?>
<section class="phero<?php echo $has_img ? ' has-media' : ''; ?>" aria-labelledby="page-title">
	<div class="phero-copy">
		<?php get_template_part( 'template-parts/breadcrumbs' ); ?>
		<?php if ( $a['label'] ) : ?><p class="section-label"><?php echo esc_html( $a['label'] ); ?></p><?php endif; ?>
		<h1 id="page-title"><?php echo wp_kses( $a['title'], array( 'span' => array(), 'em' => array() ) ); ?></h1>
		<?php if ( $a['lead'] ) : ?><div class="phero-lead"><?php echo wp_kses_post( $a['lead'] ); ?></div><?php endif; ?>
		<?php if ( $a['actions'] ) : ?>
			<div class="hero-actions">
				<?php foreach ( $a['actions'] as $b ) : ?>
					<a class="button<?php echo ! empty( $b[2] ) ? ' primary' : ''; ?>" href="<?php echo esc_url( $b[1] ); ?>"><?php echo esc_html( $b[0] ); ?></a>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	</div>
	<?php if ( $has_img ) : ?>
		<figure class="phero-media">
			<img src="<?php echo esc_url( 0 === strpos( $a['image'], 'http' ) ? $a['image'] : lumina_img( $a['image'] ) ); ?>" width="1600" height="1066" decoding="async" alt="<?php echo esc_attr( $a['alt'] ); ?>">
		</figure>
	<?php endif; ?>
</section>
