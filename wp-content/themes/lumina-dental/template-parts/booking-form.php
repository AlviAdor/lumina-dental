<?php
/**
 * Appointment request form (posts to admin-post.php, enhanced by app.js).
 * Optional $args['service'] pre-selects a treatment; ?service= in the URL does the same.
 */
$pre = $args['service'] ?? ( isset( $_GET['service'] ) ? sanitize_text_field( wp_unslash( $_GET['service'] ) ) : '' );
$status = isset( $_GET['booking'] ) ? sanitize_key( $_GET['booking'] ) : '';
$msg    = isset( $_GET['msg'] ) ? sanitize_text_field( wp_unslash( $_GET['msg'] ) ) : '';
$today  = wp_date( 'Y-m-d' );
$max    = wp_date( 'Y-m-d', time() + 90 * DAY_IN_SECONDS );
?>
<form class="booking-form card" id="book" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" data-booking-form novalidate>
	<h2>Request an appointment</h2>
	<p class="form-lead">We will confirm by phone or message, usually within a few hours during opening times.</p>

	<?php if ( 'ok' === $status ) : ?>
		<p class="form-note is-ok" role="status">Thank you. Your request has been received and we will be in touch to confirm.</p>
	<?php elseif ( 'error' === $status ) : ?>
		<p class="form-note is-error" role="alert"><?php echo esc_html( $msg ?: 'Please check the form and try again.' ); ?></p>
	<?php endif; ?>

	<input type="hidden" name="action" value="lumina_book">
	<input type="hidden" name="lt" value="<?php echo esc_attr( lumina_booking_token() ); ?>">
	<div class="hp" aria-hidden="true"><label>Leave this empty <input type="text" name="website" tabindex="-1" autocomplete="off"></label></div>

	<div class="field-grid">
		<div class="field">
			<label for="bk-name">Full name</label>
			<input id="bk-name" name="name" type="text" autocomplete="name" required minlength="2" maxlength="80">
			<small class="field-error" data-error-for="name"></small>
		</div>
		<div class="field">
			<label for="bk-phone">Phone</label>
			<input id="bk-phone" name="phone" type="tel" inputmode="tel" autocomplete="tel" required placeholder="01XXX-XXXXXX">
			<small class="field-error" data-error-for="phone"></small>
		</div>
		<div class="field">
			<label for="bk-email">Email <span class="optional">(optional)</span></label>
			<input id="bk-email" name="email" type="email" autocomplete="email">
			<small class="field-error" data-error-for="email"></small>
		</div>
		<div class="field">
			<label for="bk-service">Treatment</label>
			<select id="bk-service" name="service" required>
				<option value="">Choose a service</option>
				<?php foreach ( lumina_booking_services() as $title ) : ?>
					<option value="<?php echo esc_attr( $title ); ?>"<?php selected( $pre, $title ); ?>><?php echo esc_html( $title ); ?></option>
				<?php endforeach; ?>
			</select>
			<small class="field-error" data-error-for="service"></small>
		</div>
		<div class="field">
			<label for="bk-date">Preferred date</label>
			<input id="bk-date" name="date" type="date" required min="<?php echo esc_attr( $today ); ?>" max="<?php echo esc_attr( $max ); ?>" data-date>
			<small class="field-hint" data-date-hint>Closed days are not available.</small>
			<small class="field-error" data-error-for="date"></small>
		</div>
		<div class="field">
			<label for="bk-slot">Time of day</label>
			<select id="bk-slot" name="slot" required>
				<option value="">Choose a time</option>
				<?php foreach ( LUMINA_SLOTS as $k => $label ) : ?>
					<option value="<?php echo esc_attr( $k ); ?>"><?php echo esc_html( $label ); ?></option>
				<?php endforeach; ?>
			</select>
			<small class="field-error" data-error-for="slot"></small>
		</div>
	</div>

	<fieldset class="field segmented">
		<legend>Have you visited us before?</legend>
		<?php foreach ( LUMINA_PATIENTS as $k => $label ) : ?>
			<label><input type="radio" name="patient" value="<?php echo esc_attr( $k ); ?>"<?php echo 'new' === $k ? ' checked' : ''; ?>><span><?php echo esc_html( $label ); ?></span></label>
		<?php endforeach; ?>
	</fieldset>

	<div class="field">
		<label for="bk-message">Anything we should know? <span class="optional">(optional)</span></label>
		<textarea id="bk-message" name="message" rows="3" maxlength="1000" placeholder="Pain, allergies, worries, or questions"></textarea>
		<small class="field-error" data-error-for="message"></small>
	</div>

	<div class="field consent">
		<label><input type="checkbox" name="consent" value="1" required> <span>I agree that <?php echo esc_html( lumina_opt( 'name' ) ); ?> may contact me about this request. My details are used only for this.</span></label>
		<small class="field-error" data-error-for="consent"></small>
	</div>

	<button class="button primary submit" type="submit" data-submit><span data-submit-label>Send request</span></button>
	<p class="form-note" data-form-status role="status" aria-live="polite"></p>
	<p class="form-alt">Prefer to talk? Call <a href="<?php echo esc_url( lumina_tel_url() ); ?>"><?php echo esc_html( lumina_opt( 'phone_display' ) ); ?></a>.</p>
</form>
