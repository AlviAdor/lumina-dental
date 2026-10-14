<?php
/**
 * Scroll-lit statement: a large headline whose words light up as the section scrolls past,
 * a giant outlined word that fills in, and two photos that open up.
 *
 * Words are split here in PHP, not in JS, so the text is in the HTML for search engines and
 * screen readers and nothing reflows on load. Without JS (or with reduced motion) it renders fully lit.
 *
 * Wrap a word in *asterisks* to give it the accent colour and glow.
 * Override copy from a child theme or plugin with the `lumina_statement` filter.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$statement = apply_filters( 'lumina_statement', array(
	'label'    => 'Our approach',
	'text'     => 'We believe a healthy smile starts with *feeling calm*, being *listened to* and always knowing *what happens next*.',
	'big_word' => 'Smile',
	'caption'  => 'Care that is explained before it is done.',
) );

// Split on *: odd segments are accent phrases. Count words first so --n is exact.
$segments = explode( '*', $statement['text'] );
$words    = array();
foreach ( $segments as $k => $seg ) {
	$tokens = preg_split( '/\s+/', trim( $seg ), -1, PREG_SPLIT_NO_EMPTY );
	// "*calm*," : punctuation glued to the closing * belongs to the previous word.
	if ( $tokens && $k > 0 && $words && ! preg_match( '/^\s/', $seg ) && ! preg_match( '/\s$/', $segments[ $k - 1 ] ) ) {
		$words[ count( $words ) - 1 ][0] .= array_shift( $tokens );
	}
	foreach ( $tokens as $w ) {
		$words[] = array( $w, 1 === $k % 2 );
	}
}
$count = count( $words );
$img   = get_template_directory_uri() . '/assets/img/';
?>
<section class="lit" id="approach" data-lit aria-labelledby="lit-title">
	<div class="lit-pin">
		<div class="lit-inner">
			<p class="section-label"><?php echo esc_html( $statement['label'] ); ?></p>

			<h2 class="lit-title" id="lit-title" style="--n:<?php echo (int) $count; ?>">
				<?php
				foreach ( $words as $i => $w ) {
					printf(
						'<span class="lit-word%s" style="--i:%d">%s</span> ',
						$w[1] ? ' is-accent' : '',
						(int) $i,
						esc_html( $w[0] )
					);
				}
				?>
			</h2>

			<p class="lit-big" aria-hidden="true"><?php echo esc_html( $statement['big_word'] ); ?></p>
		</div>

		<div class="lit-media" aria-hidden="false">
			<figure class="lit-photo lit-photo-a">
				<img src="<?php echo esc_url( $img . 'clinic-smile.jpg' ); ?>" width="1600" height="1066" loading="lazy" decoding="async"
					alt="A patient laughing with their dentist in a bright, modern treatment room.">
			</figure>
			<figure class="lit-photo lit-photo-b">
				<img src="<?php echo esc_url( $img . 'clinic-exam.jpg' ); ?>" width="1600" height="1066" loading="lazy" decoding="async"
					alt="A dentist in a mask and gloves examining a patient in a reclined chair.">
				<figcaption><?php echo esc_html( $statement['caption'] ); ?></figcaption>
			</figure>
		</div>
	</div>
</section>
