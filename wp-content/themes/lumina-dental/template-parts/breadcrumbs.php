<?php
/** Breadcrumb trail (matches the BreadcrumbList schema). */
$crumbs = lumina_breadcrumb_items();
if ( count( $crumbs ) < 2 ) {
	return;
}
?>
<nav class="breadcrumbs" aria-label="Breadcrumb">
	<ol>
		<?php foreach ( $crumbs as $i => $c ) : $last = $i === count( $crumbs ) - 1; ?>
			<li>
				<?php if ( $last || ! $c[1] ) : ?>
					<span aria-current="page"><?php echo esc_html( $c[0] ); ?></span>
				<?php else : ?>
					<a href="<?php echo esc_url( $c[1] ); ?>"><?php echo esc_html( $c[0] ); ?></a>
				<?php endif; ?>
			</li>
		<?php endforeach; ?>
	</ol>
</nav>
