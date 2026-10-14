<?php
/**
 * Starter content, created once on activation (and safe to re-run: every item
 * is looked up by slug first). All of it is placeholder copy for a fictional
 * clinic: replace the text, prices and reviews with the real thing.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function lumina_seed_find( $slug, $type ) {
	$found = get_posts( array(
		'name'           => $slug,
		'post_type'      => $type,
		'post_status'    => 'any',
		'posts_per_page' => 1,
		'fields'         => 'ids',
	) );
	return $found ? (int) $found[0] : 0;
}

function lumina_seed_content() {
	$city = lumina_opt( 'city' );
	$area = lumina_opt( 'area' );

	// Site basics for a Dhaka clinic: week starts Saturday, local time, clean URLs.
	update_option( 'timezone_string', 'Asia/Dhaka' );
	update_option( 'date_format', 'j F Y' );
	update_option( 'time_format', 'g:i a' );
	update_option( 'start_of_week', 6 );
	update_option( 'blogname', lumina_opt( 'name' ) );
	update_option( 'blogdescription', lumina_opt( 'tagline' ) );
	update_option( 'default_comment_status', 'closed' );
	update_option( 'default_ping_status', 'closed' );
	global $wp_rewrite;
	$wp_rewrite->set_permalink_structure( '/%postname%/' );

	// Remove WordPress's own placeholder post/page if untouched.
	foreach ( array( array( 'hello-world', 'post' ), array( 'sample-page', 'page' ) ) as $d ) {
		if ( $id = lumina_seed_find( $d[0], $d[1] ) ) {
			wp_delete_post( $id, true );
		}
	}

	/* ---- Services ------------------------------------------------------ */
	$services = array(
		array(
			'slug' => 'check-ups-and-cleaning', 'title' => 'Check-ups & professional cleaning', 'icon' => 'tooth',
			'excerpt' => "Routine dental exams, scaling and polishing in $area, $city. Catch problems early and keep your teeth and gums healthy.",
			'duration' => '30–45 minutes', 'price' => 'From ৳800',
			'content' => "<p>A regular check-up is the simplest way to avoid painful, expensive dental problems. We examine your teeth, gums and bite, take X-rays only when they are clinically useful, and talk you through what we find in plain language.</p><h2>Who it is for</h2><p>Everyone. Most adults benefit from a check-up and clean every six months; if you have gum disease, braces or a history of decay we may suggest visiting more often.</p><p>Professional scaling removes the hardened plaque (tartar) that brushing cannot, and polishing leaves the surface smooth so it is harder for stains to cling.</p>",
			'steps' => "Quick medical and dental history\nExamination of teeth, gums and bite\nScaling and polishing\nPersonal advice on brushing, flossing and diet",
			'faqs' => "How often should I have a check-up? | Every six months suits most people. We will tell you if you need to come more or less often.\nDoes scaling damage my teeth? | No. Scaling removes tartar from the surface; it does not wear down healthy enamel. Mild sensitivity for a day or two is normal.\nDo I need X-rays every visit? | No. We only recommend X-rays when they will help us find or monitor something we cannot see.",
		),
		array(
			'slug' => 'teeth-whitening', 'title' => 'Teeth whitening', 'icon' => 'sparkle',
			'excerpt' => "Safe, dentist-supervised teeth whitening in $city. Brighten stained teeth by several shades in a single visit.",
			'duration' => '60–75 minutes', 'price' => 'From ৳6,000',
			'content' => "<p>Tea, coffee, tobacco and ageing all darken teeth over time. In-clinic whitening uses a professional-strength gel, applied after we protect your gums, to lift those stains in one sitting.</p><h2>Is it right for you?</h2><p>Whitening works best on natural teeth stained on the surface. It does not change the colour of crowns, veneers or fillings, so we check your teeth first and tell you honestly what result to expect.</p>",
			'steps' => "Shade check and gum health assessment\nProtective barrier placed over gums\nWhitening gel applied and activated\nAftercare advice and a final shade comparison",
			'faqs' => "Does whitening hurt? | Some people feel temporary sensitivity. We use desensitising products and can adjust the strength to keep you comfortable.\nHow long do results last? | Typically one to two years, depending on diet, smoking and how well you look after your teeth.\nIs it safe for enamel? | Professionally supervised whitening is considered safe for healthy teeth. We will not whiten teeth with untreated decay or active gum disease.",
		),
		array(
			'slug' => 'braces-and-aligners', 'title' => 'Braces & clear aligners', 'icon' => 'braces',
			'excerpt' => "Metal braces, ceramic braces and clear aligners in $area, $city. Straighter teeth and a better bite for teens and adults.",
			'duration' => 'Consultation 30 minutes', 'price' => 'Consultation from ৳500',
			'content' => "<p>Crooked or crowded teeth are harder to clean and can wear unevenly. Orthodontic treatment moves teeth gradually into a healthier, more balanced position, and it is never too late to start.</p><h2>Your options</h2><p>Metal braces are the most versatile and affordable. Ceramic braces blend with your teeth. Clear aligners are removable and nearly invisible, suited to mild to moderate alignment problems. After a proper assessment we explain which suits you and why.</p>",
			'steps' => "Consultation, photos and digital records\nTreatment plan with timeline and full cost\nFitting of braces or first set of aligners\nRegular adjustment visits and a retainer afterwards",
			'faqs' => "How long does treatment take? | Usually 12 to 24 months, depending on how much movement is needed. We give you an estimate after your assessment.\nCan adults have braces? | Yes. A healthy mouth is what matters, not age.\nDo I have to wear a retainer? | Yes. Teeth drift back without one, so a retainer is part of the treatment, not an optional extra.",
		),
		array(
			'slug' => 'dental-implants', 'title' => 'Dental implants', 'icon' => 'implant',
			'excerpt' => "Replace missing teeth with natural-looking dental implants in $city. Planning, placement and crowns under one roof.",
			'duration' => 'Staged over 3–6 months', 'price' => 'Ask for a quote',
			'content' => "<p>A dental implant is a small titanium post placed in the jawbone that acts as an artificial root for a crown, bridge or denture. Unlike a bridge, it does not rely on neighbouring teeth for support.</p><h2>What we assess first</h2><p>Good implant results depend on healthy gums and enough bone. We examine your mouth, review scans and discuss your general health before recommending anything, and we will tell you if another option would serve you better.</p>",
			'steps' => "Examination and 3D scan\nWritten plan and itemised quote\nImplant placement under local anaesthetic\nHealing period, then crown fitting and review",
			'faqs' => "Is the procedure painful? | Placement is done under local anaesthetic. Most people describe the aftermath as similar to a tooth extraction and manage with simple pain relief.\nAm I too old for an implant? | Age alone is not a barrier. Healthy gums, enough bone and good general health matter more.\nHow long do implants last? | With good hygiene and regular check-ups, many last decades.",
		),
		array(
			'slug' => 'root-canal-treatment', 'title' => 'Root canal treatment', 'icon' => 'root',
			'excerpt' => "Pain-free root canal treatment in $area, $city. Save an infected tooth instead of removing it.",
			'duration' => '60–90 minutes', 'price' => 'From ৳4,500',
			'content' => "<p>When the soft tissue inside a tooth becomes infected or inflamed, usually from deep decay or a crack, a root canal removes it, cleans the space and seals it. The aim is to relieve pain and keep your natural tooth.</p><h2>Signs you may need one</h2><p>Lingering pain with hot or cold, a throbbing toothache, pain when biting, a darkening tooth or a swelling on the gum. If you have any of these, do not wait: infections rarely clear up by themselves.</p>",
			'steps' => "X-ray and diagnosis\nLocal anaesthetic so you feel no pain\nInfected tissue removed and canals cleaned\nCanals sealed, then a filling or crown to protect the tooth",
			'faqs' => "Is a root canal painful? | With modern anaesthetic the treatment itself should feel much like having a filling. The pain people fear usually comes from the infection, not the treatment.\nWill I need more than one visit? | Often one, sometimes two, depending on the tooth and the level of infection.\nWhy do I need a crown afterwards? | A treated back tooth is more brittle. A crown protects it from fracturing.",
		),
		array(
			'slug' => 'childrens-dentistry', 'title' => "Children's dentistry", 'icon' => 'child',
			'excerpt' => "Gentle dental care for children in $area, $city. Friendly check-ups, fluoride and sealants to build lifelong habits.",
			'duration' => '20–30 minutes', 'price' => 'From ৳500',
			'content' => "<p>A child's first dental visits set the tone for how they feel about dentists for life. We keep appointments short, explain everything in simple words and never rush a nervous child.</p><h2>What we do</h2><p>Check-ups, gentle cleaning, fluoride treatment, fissure sealants to protect back teeth, and advice for parents on brushing, diet and thumb-sucking. We recommend a first visit around the time the first teeth appear, or by their first birthday.</p>",
			'steps' => "Meet the dentist and look around, no treatment pressure\nGentle examination, counting and \"tickling\" teeth\nCleaning and fluoride if suitable\nA quick, friendly chat with parents about home care",
			'faqs' => "When should my child first see a dentist? | Around their first birthday, or when their first tooth appears.\nMy child is scared. Can you help? | Yes. We go at their pace and often start with just a look and a chat.\nAre baby teeth worth treating? | Yes. Decay in baby teeth causes pain and can affect the adult teeth coming through.",
		),
		array(
			'slug' => 'fillings-and-crowns', 'title' => 'Fillings & crowns', 'icon' => 'shield',
			'excerpt' => "Tooth-coloured fillings and durable crowns in $city. Repair decayed, cracked or worn teeth so they look and feel natural.",
			'duration' => '30–60 minutes', 'price' => 'From ৳1,500',
			'content' => "<p>Decay that is caught early can be repaired with a simple filling. When a tooth is badly broken down, cracked or has had a root canal, a crown covers and strengthens it.</p><h2>Natural-looking materials</h2><p>We use tooth-coloured materials that blend with your smile. Crowns are made to match the shape, shade and bite of your own teeth.</p>",
			'steps' => "Examination and X-ray if needed\nLocal anaesthetic and removal of decay\nFilling placed and shaped, or tooth prepared for a crown\nBite check and care advice",
			'faqs' => "How long does a filling last? | Many last for years with good care. We check them at every routine visit.\nWhat is a crown made of? | Options include porcelain and ceramic. We will recommend the best one for the tooth's position and your bite.\nWill it feel strange afterwards? | It can take a day or two to get used to. Tell us if your bite feels high; a quick adjustment fixes it.",
		),
		array(
			'slug' => 'emergency-dental-care', 'title' => 'Emergency dental care', 'icon' => 'alert',
			'excerpt' => "Toothache, broken tooth or swelling? Urgent dental care in $area, $city. Call us and we will see you as soon as possible.",
			'duration' => 'Same-day where possible', 'price' => 'Ask for a quote',
			'content' => "<p>Dental emergencies rarely happen at a convenient time. If you have severe toothache, a knocked-out or broken tooth, a lost filling, bleeding that will not stop or facial swelling, call us straight away and we will fit you in as soon as we can.</p><h2>While you wait</h2><p>Rinse gently with warm water, take your usual pain relief as directed, and avoid placing aspirin directly on the gum. If a tooth has been knocked out, keep it in milk, hold it by the crown not the root, and come in immediately. Swelling with a fever or difficulty swallowing or breathing needs urgent medical care, so go to your nearest emergency department.</p>",
			'steps' => "Call us and describe the problem\nWe give first-aid advice and the earliest available time\nExamination and pain relief first\nA clear plan for the definitive treatment",
			'faqs' => "Do you take walk-ins? | We always try. Calling ahead lets us prepare and tell you the quickest way to be seen.\nWhat counts as a dental emergency? | Severe pain, swelling, a knocked-out or broken tooth, or bleeding that does not stop.\nCan a toothache wait until tomorrow? | Sometimes, but swelling or a fever means you should be seen the same day.",
		),
	);

	foreach ( $services as $i => $s ) {
		if ( lumina_seed_find( $s['slug'], 'service' ) ) {
			continue;
		}
		$id = wp_insert_post( array(
			'post_type'    => 'service',
			'post_status'  => 'publish',
			'post_name'    => $s['slug'],
			'post_title'   => $s['title'],
			'post_excerpt' => $s['excerpt'],
			'post_content' => $s['content'],
			'menu_order'   => $i,
		) );
		if ( $id && ! is_wp_error( $id ) ) {
			update_post_meta( $id, '_lumina_icon', $s['icon'] );
			update_post_meta( $id, '_lumina_duration', $s['duration'] );
			update_post_meta( $id, '_lumina_price', $s['price'] );
			update_post_meta( $id, '_lumina_steps', $s['steps'] );
			update_post_meta( $id, '_lumina_faqs', $s['faqs'] );
		}
	}

	/* ---- Areas ---------------------------------------------------------- */
	$areas = array(
		array( 'dhanmondi', 'Dhanmondi', 'In the heart of Dhanmondi', 'Dhanmondi Lake, Star Mosque, Rabindra Sarobar' ),
		array( 'mohammadpur', 'Mohammadpur', 'About 10 minutes from Mohammadpur Bus Stand', 'Town Hall, Tajmahal Road, Mohammadpur Bus Stand' ),
		array( 'dhaka-university-area', 'Dhaka University area', 'About 15 minutes from Shahbag', 'Shahbag, TSC, Curzon Hall' ),
		array( 'lalmatia', 'Lalmatia', 'About 10 minutes from Lalmatia Block D', 'Lalmatia Housing, Asad Gate, Sobhanbag' ),
		array( 'new-market-and-elephant-road', 'New Market & Elephant Road', 'About 10 minutes by rickshaw from New Market', 'New Market, Elephant Road, Science Lab' ),
	);
	foreach ( $areas as $i => $a ) {
		if ( lumina_seed_find( $a[0], 'area' ) ) {
			continue;
		}
		$clinic = lumina_opt( 'name' );
		$id     = wp_insert_post( array(
			'post_type'    => 'area',
			'post_status'  => 'publish',
			'post_name'    => $a[0],
			'post_title'   => $a[1],
			'menu_order'   => $i,
			'post_excerpt' => "Looking for a dentist near {$a[1]}? $clinic offers check-ups, whitening, braces, implants and emergency care, easy to reach from {$a[1]}.",
			'post_content' => "<p>$clinic is a short trip from {$a[1]}, with {$a[2]} as a handy reference point. Patients from {$a[1]} come to us for routine check-ups and cleaning, orthodontics, root canal treatment and urgent care.</p><p>Parking is limited on busy days, so if you are coming by car we suggest booking a mid-morning or evening slot. We are open six days a week; Fridays we are closed.</p>",
		) );
		if ( $id && ! is_wp_error( $id ) ) {
			update_post_meta( $id, '_lumina_distance', $a[2] );
			update_post_meta( $id, '_lumina_landmarks', $a[3] );
		}
	}

	/* ---- Sample reviews (flagged, never used in schema) ------------------ */
	$reviews = array(
		array( 'Farhana A.', 5, 'Teeth whitening', 'I was nervous about whitening but the doctor explained every step and checked my comfort throughout. My teeth are noticeably brighter and there was no pain.', 12 ),
		array( 'Mahmudul H.', 5, 'Root canal treatment', 'Had a terrible toothache on a Saturday and they saw me the same day. The root canal was far less scary than I expected. Clean clinic and very patient staff.', 25 ),
		array( 'Tasnim R.', 5, "Children's dentistry", 'My 6-year-old usually panics at the dentist, but here she asked to come back. They let her hold the mirror and count her own teeth.', 40 ),
		array( 'Imran K.', 4, 'Braces', 'Straightforward pricing explained up front, no surprises. Appointments are quick. The only reason for four stars is the waiting room gets busy on evenings.', 58 ),
		array( 'Sadia N.', 5, 'Check-up & cleaning', 'Booked online in two minutes and they called to confirm the same hour. Honest advice, nothing pushed on me.', 71 ),
		array( 'Rafiq U.', 5, 'Dental implants', 'The plan and cost were written down before anything started, and every step happened exactly as described. Very happy with the result.', 90 ),
	);
	if ( ! get_posts( array( 'post_type' => 'lumina_review', 'post_status' => 'any', 'posts_per_page' => 1, 'fields' => 'ids' ) ) ) {
		foreach ( $reviews as $r ) {
			$id = wp_insert_post( array(
				'post_type'    => 'lumina_review',
				'post_status'  => 'publish',
				'post_title'   => $r[0] . ' – ' . $r[2],
				'post_content' => $r[3],
				'post_date'    => wp_date( 'Y-m-d H:i:s', time() - $r[4] * DAY_IN_SECONDS ),
			) );
			if ( $id && ! is_wp_error( $id ) ) {
				update_post_meta( $id, '_lumina_rating', $r[1] );
				update_post_meta( $id, '_lumina_author', $r[0] );
				update_post_meta( $id, '_lumina_treatment', $r[2] );
				update_post_meta( $id, '_lumina_source', 'Google' );
				update_post_meta( $id, '_lumina_sample', '1' );
			}
		}
	}

	/* ---- Pages ---------------------------------------------------------- */
	$name  = lumina_opt( 'name' );
	$pages = array(
		'home'    => array( 'Home', '' ),
		'about'   => array( 'About us', "<p>$name is an independent family dental clinic in $area, $city. We started in " . lumina_opt( 'established' ) . " with a simple idea: dentistry should be gentle, clearly explained and fairly priced.</p><h2>How we work</h2><p>You always get a clear diagnosis, a written plan and a price before treatment begins. We sterilise every instrument between patients, use modern digital imaging, and never rush an appointment.</p><h2>Meet your dentist</h2><p>" . lumina_opt( 'doctor' ) . " leads our team and sees patients of all ages, from first check-ups to complex restorative care. Replace this paragraph with the dentist's qualifications, registration number and special interests.</p>" ),
		'reviews' => array( 'Patient reviews', '<p>What our patients say about their visits. We share reviews exactly as they were written.</p>' ),
		'contact' => array( 'Contact & directions', '<p>Call, message us on WhatsApp, or send an appointment request and we will get back to you.</p>' ),
		'book'    => array( 'Book an appointment', '<p>Tell us what you need and when suits you. We will call or message to confirm your time.</p>' ),
	);
	$ids = array();
	foreach ( $pages as $slug => $p ) {
		$ids[ $slug ] = lumina_seed_find( $slug, 'page' );
		if ( ! $ids[ $slug ] ) {
			$ids[ $slug ] = wp_insert_post( array(
				'post_type'    => 'page',
				'post_status'  => 'publish',
				'post_name'    => $slug,
				'post_title'   => $p[0],
				'post_content' => $p[1],
			) );
		}
	}
	update_option( 'show_on_front', 'page' );
	update_option( 'page_on_front', $ids['home'] );

	/* ---- Primary menu --------------------------------------------------- */
	$menu = wp_get_nav_menu_object( 'Primary' );
	if ( ! $menu || ! wp_get_nav_menu_items( $menu->term_id ) ) {
		$menu_id = $menu ? $menu->term_id : wp_create_nav_menu( 'Primary' );
		$links   = array(
			array( 'Home', home_url( '/' ) ),
			array( 'Services', home_url( '/services/' ) ),
			array( 'Areas', home_url( '/areas-we-serve/' ) ),
			array( 'Reviews', get_permalink( $ids['reviews'] ) ),
			array( 'About', get_permalink( $ids['about'] ) ),
			array( 'Contact', get_permalink( $ids['contact'] ) ),
		);
		foreach ( $links as $l ) {
			wp_update_nav_menu_item( $menu_id, 0, array(
				'menu-item-title'  => $l[0],
				'menu-item-url'    => $l[1],
				'menu-item-type'   => 'custom',
				'menu-item-status' => 'publish',
			) );
		}
		$locations            = get_theme_mod( 'nav_menu_locations', array() );
		$locations['primary'] = $menu_id;
		set_theme_mod( 'nav_menu_locations', $locations );
	}

	update_option( 'lumina_seeded', LUMINA_CORE_VERSION );
}
