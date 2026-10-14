<?php
/** Site header: topbar, slide-in mobile nav, Display panel. */
$links = lumina_menu_links();
$wa    = lumina_whatsapp_url();
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="skip-link" href="#main">Skip to content</a>

<header class="topbar" aria-label="Site header">
	<a class="brand" href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="<?php echo esc_attr( lumina_opt( 'name' ) ); ?> home">
		<span class="brand-mark"><?php echo lumina_icon( 'tooth', 22 ); ?></span>
		<span class="brand-text">
			<strong><?php echo esc_html( lumina_opt( 'name' ) ); ?></strong>
			<small>Dentist · <?php echo esc_html( lumina_opt( 'area' ) ?: lumina_opt( 'city' ) ); ?></small>
		</span>
	</a>

	<nav class="desktop-nav" aria-label="Primary navigation">
		<?php foreach ( $links as $l ) : ?>
			<a href="<?php echo esc_url( $l[1] ); ?>"<?php echo $l[2] ? ' aria-current="page"' : ''; ?>><?php echo esc_html( $l[0] ); ?></a>
		<?php endforeach; ?>
		<a class="nav-login" href="<?php echo esc_url( lumina_book_url() ); ?>">Book online</a>
	</nav>

	<div class="topbar-actions">
		<a class="icon-button call-button" href="<?php echo esc_url( lumina_tel_url() ); ?>" aria-label="Call <?php echo esc_attr( lumina_opt( 'phone_display' ) ); ?>"><?php echo lumina_icon( 'phone', 18 ); ?></a>
		<button class="icon-button" type="button" data-theme-toggle aria-label="Toggle light and dark theme"><?php echo lumina_icon( 'moon', 18, true ); ?></button>
		<button class="icon-button" type="button" data-panel-toggle aria-label="Open display settings" aria-expanded="false" aria-controls="display-panel"><?php echo lumina_icon( 'settings', 20 ); ?></button>
		<button class="menu-button" type="button" data-nav-toggle aria-expanded="false" aria-controls="site-nav" aria-label="Open menu"><span></span><span></span></button>
	</div>
</header>

<nav class="side-nav" id="site-nav" aria-label="Mobile navigation" data-nav hidden>
	<?php foreach ( $links as $l ) : ?>
		<a href="<?php echo esc_url( $l[1] ); ?>" data-nav-link<?php echo $l[2] ? ' aria-current="page"' : ''; ?>><?php echo esc_html( $l[0] ); ?></a>
	<?php endforeach; ?>
	<a href="<?php echo esc_url( lumina_book_url() ); ?>" data-nav-link class="side-nav-book">Book an appointment</a>
	<div class="nav-tools" aria-label="Quick settings">
		<button class="nav-tool" type="button" data-theme-toggle><span>Theme</span><?php echo lumina_icon( 'moon', 16, true ); ?></button>
		<button class="nav-tool" type="button" data-panel-toggle><span>Display</span><?php echo lumina_icon( 'settings', 16 ); ?></button>
	</div>
	<p class="side-nav-contact">
		<a href="<?php echo esc_url( lumina_tel_url() ); ?>"><?php echo esc_html( lumina_opt( 'phone_display' ) ); ?></a>
		<span><?php echo esc_html( lumina_address_line() ); ?></span>
	</p>
</nav>

<aside class="access-panel" id="display-panel" data-access-panel aria-label="Display settings">
	<div class="panel-header">
		<strong>Display</strong>
		<button type="button" data-panel-close>Close</button>
	</div>
	<label><input type="checkbox" data-readable-font> Readable font</label>
	<label><input type="checkbox" data-reduced-motion> Reduced motion</label>
	<label><input type="checkbox" data-high-contrast> High contrast</label>
	<label>Text size <input type="range" min="0" max="2" value="0" step="1" data-text-size></label>
	<button class="reset-button" type="button" data-reset-access>Reset</button>
</aside>
