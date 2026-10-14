<?php
/**
 * Shared page copy and small renderers, so the home page, service pages, area pages,
 * About, FAQ and Prices all draw on one source. It is placeholder copy for a demo clinic:
 * edit it here (or filter `lumina_content_{key}`) and every page updates.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function lumina_content( $key ) {
	$name = lumina_opt( 'name' );
	$all  = array(

		'safety' => array(
			array( 'Sterilised, every time', 'Instruments are cleaned, sterilised and sealed between patients, and single-use items are opened in front of you.' ),
			array( 'Digital imaging', 'Low-dose digital X-rays and close-up photos let us show you exactly what we see, and we only take them when they will help.' ),
			array( 'A comfort-first technique', 'Numbing gel before injections, a slow pace, and a hand signal to pause whenever you need a break.' ),
			array( 'Written plans, clear prices', 'A diagnosis, your options and an itemised cost on paper before any treatment begins.' ),
		),

		'principles' => array(
			array( 'Comfort first', 'Effective numbing, a gentle technique and breaks whenever you ask. If you are anxious, tell us. We will go at your pace.' ),
			array( 'Clarity always', 'You get a diagnosis, a written plan and an itemised cost before any treatment starts, and we explain it in plain words.' ),
			array( 'Care that continues', 'Aftercare advice, reminders for your next check-up, and a number to call if anything worries you after you leave.' ),
		),

		'tips' => array(
			array( 'Brush for two minutes, twice a day', 'Use a fluoride toothpaste and a soft brush, angled towards the gumline. Spit, but do not rinse straight away, so the fluoride keeps working.' ),
			array( 'Clean between your teeth daily', 'Floss or interdental brushes reach the surfaces a toothbrush cannot, which is where most gum disease and decay begin.' ),
			array( 'Keep sugar to mealtimes', 'It is how often teeth meet sugar, not only how much. Sweet drinks sipped through the day are the biggest culprit.' ),
			array( 'Replace your toothbrush every three months', 'Worn bristles clean less well. Replace it sooner after an illness.' ),
			array( 'Do not ignore bleeding gums', 'Gums that bleed when you brush are usually inflamed, not fragile. Cleaning gently and thoroughly helps, and we can check for early gum disease.' ),
			array( 'Visit every six months', 'Most people do well with a check-up twice a year. We will tell you if you need to come more or less often.' ),
		),

		'bring' => array(
			'Any previous dental X-rays or records',
			'A list of the medicines you take, and any allergies',
			'A form of ID',
			'Your questions. Write them down, we are glad to answer them all',
			'Five minutes to spare, so we can take your history without rushing',
		),

		'aftercare' => array(
			'Avoid eating or drinking hot things until the numbness has worn off, to protect your lips and tongue',
			'Take any pain relief exactly as we advise, and keep our number handy',
			'Brush gently around the treated area and keep it clean',
			'Call us if pain, swelling or bleeding is getting worse instead of better',
		),

		'faq_groups' => array(
			'Booking & visits' => array(
				array( 'Do I need an appointment, or can I walk in?', 'Appointments keep waiting times short, so we recommend booking. For toothache or a broken tooth, call us and we will do our best to see you the same day.' ),
				array( 'How do I book?', 'Use the online form, call, or message us on WhatsApp. We always confirm your time by phone or message, so a request is not a booking until we reply.' ),
				array( 'What should I bring to my first visit?', 'Any previous dental X-rays or records, a list of your medicines, and a form of ID. Arrive five minutes early so we can take your history.' ),
				array( 'Can I change or cancel my appointment?', 'Yes. Please let us know as early as you can so we can offer the time to someone else.' ),
			),
			'Treatment & comfort' => array(
				array( 'Will treatment hurt?', 'We use effective local anaesthetic and a gentle technique, and you can ask us to pause at any time. Tell us if you are anxious and we will go at your pace.' ),
				array( 'I am afraid of the dentist. Can you help?', 'Many of our patients are. We explain each step before we do it, never rush, and are happy to start with just a look and a chat.' ),
				array( 'Do you take X-rays at every visit?', 'No. We only recommend X-rays when they will help us find or monitor something we cannot see.' ),
				array( 'How often should I have a check-up?', 'Every six months suits most people. We will tell you if you need to come more or less often.' ),
			),
			'Costs & payment' => array(
				array( 'How much will my treatment cost?', 'After your examination you receive a written plan with itemised costs before anything begins. Starting prices are shown on each service page and on our prices page.' ),
				array( 'Will I be charged for things I did not agree to?', 'No. We do not begin treatment until you have agreed to the plan and the cost, and we tell you first if anything changes.' ),
				array( 'What payment options do you offer?', 'Ask us when you book. We will explain the options available for your treatment before it starts.' ),
			),
			'Children & families' => array(
				array( 'Do you treat children?', 'Yes. We see children from their first teeth, with short, friendly visits designed to build confidence.' ),
				array( 'Can a parent stay in the room?', 'Of course. Most young children feel calmer with a parent close by.' ),
				array( 'When should my child first see a dentist?', 'Around their first birthday, or when their first tooth appears.' ),
			),
			'Emergencies' => array(
				array( 'What counts as a dental emergency?', 'Severe pain, swelling, a knocked-out or broken tooth, or bleeding that does not stop. Call us straight away.' ),
				array( 'What do I do if a tooth is knocked out?', 'Hold it by the crown, not the root, keep it in milk, and come in immediately. Do not scrub it.' ),
				array( 'When is it not a dental problem?', 'Swelling with a fever, or any difficulty breathing or swallowing, needs urgent medical care. Go to your nearest emergency department.' ),
			),
		),

		'team_roles' => array(
			array( 'Dental nurses', 'Chairside support who prepare your room, hand the dentist what is needed, and make sure you are comfortable.' ),
			array( 'Reception & care coordination', 'The first voice you hear. They confirm appointments, answer questions and help you plan your visits.' ),
			array( 'Sterilisation & hygiene', 'Every instrument is processed to a strict routine, and every room is cleaned between patients.' ),
		),

		'interests' => array( 'Check-ups & prevention', 'Cosmetic dentistry', 'Restorative care', 'Nervous patients' ),

		'values' => array(
			array( 'Honest advice', 'We tell you what you need and what you do not, even if that means a smaller bill for us.' ),
			array( 'Plain language', 'You should leave every visit understanding what we found and what happens next.' ),
			array( 'Unhurried care', 'Appointments are booked with enough time that nobody has to be rushed.' ),
			array( 'Fair, clear pricing', 'Itemised quotes before treatment, and no surprises on the day.' ),
		),
	);
	return apply_filters( 'lumina_content_' . $key, $all[ $key ] ?? array() );
}

/** Numbered grid of {title, text} items. */
function lumina_render_features( $items, $class = '' ) {
	echo '<ol class="features ' . esc_attr( $class ) . '">';
	foreach ( $items as $i => $it ) {
		printf(
			'<li class="reveal-target"><span class="n">%s</span><h3>%s</h3><p>%s</p></li>',
			esc_html( str_pad( (string) ( $i + 1 ), 2, '0', STR_PAD_LEFT ) ),
			esc_html( $it[0] ),
			esc_html( $it[1] )
		);
	}
	echo '</ol>';
}

/** Accordion of {q, a} or [q, a] pairs. */
function lumina_render_faq( $faqs ) {
	echo '<div class="faq-list reveal-target">';
	foreach ( $faqs as $f ) {
		$q = $f['q'] ?? $f[0];
		$a = $f['a'] ?? $f[1];
		printf( '<details><summary>%s</summary><p>%s</p></details>', esc_html( $q ), esc_html( $a ) );
	}
	echo '</div>';
}

/** Simple ticked list. */
function lumina_render_checklist( $items ) {
	echo '<ul class="checklist">';
	foreach ( $items as $it ) {
		echo '<li>' . lumina_icon( 'check', 18 ) . '<span>' . esc_html( $it ) . '</span></li>';
	}
	echo '</ul>';
}

/** Theme image URL. */
function lumina_img( $file ) {
	return get_template_directory_uri() . '/assets/img/' . $file;
}
function lumina_vid( $file ) {
	return get_template_directory_uri() . '/assets/video/' . $file;
}

/** Page-top editorial header. Args: label, title (limited HTML), lead, image, alt, actions[[label,url,primary]]. */
function lumina_page_hero( $args ) {
	get_template_part( 'template-parts/page-hero', null, $args );
}
