<?php
/**
 * Team block: the principal dentist (from Clinic → Clinic details) plus the supporting roles.
 * Roles are described, not named, so nothing here claims a person or qualification that does not exist.
 * Add the dentist's real qualifications and registration number in the About page text or here.
 */
$doctor = lumina_opt( 'doctor' );
?>
<section class="team" aria-labelledby="team-title">
	<div class="section-intro reveal-target">
		<p class="section-label">The team</p>
		<h2 id="team-title">The people who will look after you.</h2>
	</div>
	<div class="team-grid">
		<article class="team-lead reveal-target">
			<p class="team-role">Principal dentist</p>
			<h3><?php echo esc_html( $doctor ?: lumina_opt( 'name' ) ); ?></h3>
			<p>Leads our team and sees patients of all ages, from first check-ups to complex restorative care, with a particular focus on explaining every step so you can decide with confidence.</p>
			<ul class="chip-list">
				<?php foreach ( lumina_content( 'interests' ) as $i ) : ?><li><span class="chip"><?php echo esc_html( $i ); ?></span></li><?php endforeach; ?>
			</ul>
			<a class="tlink" href="<?php echo esc_url( lumina_book_url() ); ?>">Book with us <?php echo lumina_icon( 'arrow', 14 ); ?></a>
		</article>
		<ul class="team-roles">
			<?php foreach ( lumina_content( 'team_roles' ) as $r ) : ?>
				<li class="reveal-target"><h3><?php echo esc_html( $r[0] ); ?></h3><p><?php echo esc_html( $r[1] ); ?></p></li>
			<?php endforeach; ?>
		</ul>
	</div>
</section>
