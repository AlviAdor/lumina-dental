/* Lumina Dental front-end. No dependencies, no build step.
   Features: theme toggle, slide-in nav, Display panel, scroll reveal, "open now" badge,
   closed-day date check, click-to-load map, booking form (fetch + graceful fallback). */
(function () {
	'use strict';

	var root = document.documentElement;
	var cfg = window.LUMINA || {};
	var $ = function (sel, ctx) { return (ctx || document).querySelector(sel); };
	var $$ = function (sel, ctx) { return Array.prototype.slice.call((ctx || document).querySelectorAll(sel)); };

	function store(key, value) {
		try {
			if (value === undefined) return localStorage.getItem(key);
			localStorage.setItem(key, value);
		} catch (e) { return null; }
	}

	/* ---- Theme ---------------------------------------------------------- */
	$$('[data-theme-toggle]').forEach(function (btn) {
		btn.addEventListener('click', function () {
			var next = root.dataset.theme === 'dark' ? 'light' : 'dark';
			root.dataset.theme = next;
			store('lumina_theme', next);
		});
	});

	/* ---- Slide-in mobile nav ------------------------------------------- */
	var nav = $('[data-nav]');
	var navToggle = $('[data-nav-toggle]');

	function setNav(open) {
		if (!nav || !navToggle) return;
		navToggle.setAttribute('aria-expanded', String(open));
		navToggle.classList.toggle('is-open', open);
		navToggle.setAttribute('aria-label', open ? 'Close menu' : 'Open menu');
		document.body.style.overflow = open ? 'hidden' : '';
		if (open) {
			nav.hidden = false;
			requestAnimationFrame(function () { nav.classList.add('is-open'); });
		} else {
			nav.classList.remove('is-open');
			setTimeout(function () { if (!nav.classList.contains('is-open')) nav.hidden = true; }, 340);
		}
	}
	if (navToggle) {
		navToggle.addEventListener('click', function () {
			setNav(navToggle.getAttribute('aria-expanded') !== 'true');
		});
		$$('[data-nav-link]').forEach(function (a) { a.addEventListener('click', function () { setNav(false); }); });
		window.matchMedia('(min-width: 960px)').addEventListener('change', function (e) { if (e.matches) setNav(false); });
	}

	/* ---- Display (accessibility) panel --------------------------------- */
	var panel = $('[data-access-panel]');
	var panelToggles = $$('[data-panel-toggle]');
	var readable = $('[data-readable-font]');
	var motion = $('[data-reduced-motion]');
	var contrast = $('[data-high-contrast]');
	var size = $('[data-text-size]');
	var SCALES = [1, 1.125, 1.25];

	function loadPrefs() {
		try { return JSON.parse(store('lumina_display') || '{}'); } catch (e) { return {}; }
	}
	function applyPrefs(p) {
		root.classList.toggle('readable', !!p.readable);
		root.classList.toggle('reduce-motion', !!p.motion);
		root.classList.toggle('high-contrast', !!p.contrast);
		root.style.setProperty('--scale', SCALES[p.size || 0] || 1);
		if (readable) readable.checked = !!p.readable;
		if (motion) motion.checked = !!p.motion;
		if (contrast) contrast.checked = !!p.contrast;
		if (size) size.value = String(p.size || 0);
	}
	function savePrefs() {
		var p = {
			readable: readable && readable.checked,
			motion: motion && motion.checked,
			contrast: contrast && contrast.checked,
			size: size ? parseInt(size.value, 10) : 0
		};
		store('lumina_display', JSON.stringify(p));
		applyPrefs(p);
	}
	function setPanel(open) {
		if (!panel) return;
		panel.classList.toggle('is-open', open);
		panelToggles.forEach(function (b) { b.setAttribute('aria-expanded', String(open)); });
		if (open) { setNav(false); }
	}
	if (panel) {
		applyPrefs(loadPrefs());
		[readable, motion, contrast, size].forEach(function (el) { if (el) el.addEventListener('change', savePrefs); });
		panelToggles.forEach(function (b) {
			b.addEventListener('click', function () { setPanel(!panel.classList.contains('is-open')); });
		});
		var close = $('[data-panel-close]');
		if (close) close.addEventListener('click', function () { setPanel(false); });
		var reset = $('[data-reset-access]');
		if (reset) reset.addEventListener('click', function () { store('lumina_display', '{}'); applyPrefs({}); });
		document.addEventListener('click', function (e) {
			if (panel.classList.contains('is-open') && !panel.contains(e.target) && !e.target.closest('[data-panel-toggle]')) setPanel(false);
		});
	}

	document.addEventListener('keydown', function (e) {
		if (e.key !== 'Escape') return;
		setPanel(false);
		if (navToggle && navToggle.getAttribute('aria-expanded') === 'true') { setNav(false); navToggle.focus(); }
		closeOverlay();
	});

	/* ---- Scroll reveal -------------------------------------------------- */
	var targets = $$('.reveal-target');
	if ('IntersectionObserver' in window && !root.classList.contains('reduce-motion')) {
		var io = new IntersectionObserver(function (entries) {
			entries.forEach(function (en) {
				if (en.isIntersecting) { en.target.classList.add('is-revealed'); io.unobserve(en.target); }
			});
		}, { rootMargin: '0px 0px -8% 0px', threshold: 0.08 });
		targets.forEach(function (t, i) {
			t.style.transitionDelay = Math.min(i % 4, 3) * 60 + 'ms';
			io.observe(t);
		});
	} else {
		targets.forEach(function (t) { t.classList.add('is-revealed'); });
	}

	/* ---- Back to top ---------------------------------------------------- */
	$$('[data-to-top]').forEach(function (a) {
		a.addEventListener('click', function (e) { e.preventDefault(); window.scrollTo({ top: 0 }); });
	});

	/* ---- "Open now" badge (clinic time zone) ----------------------------- */
	function fmt(hhmm) {
		var p = hhmm.split(':'), h = parseInt(p[0], 10), m = p[1];
		var s = ((h + 11) % 12 + 1) + (m === '00' ? '' : ':' + m);
		return s + (h < 12 ? 'am' : 'pm');
	}
	function clinicNow() {
		try {
			var parts = new Intl.DateTimeFormat('en-GB', {
				timeZone: cfg.tz || undefined, weekday: 'short', hour: '2-digit', minute: '2-digit', hour12: false
			}).formatToParts(new Date());
			var o = {};
			parts.forEach(function (p) { o[p.type] = p.value; });
			return { day: o.weekday.toLowerCase().slice(0, 3), time: (o.hour === '24' ? '00' : o.hour) + ':' + o.minute };
		} catch (e) { return null; }
	}
	function refreshOpen() {
		var now = clinicNow();
		if (!now || !cfg.hours) return;
		var h = cfg.hours[now.day] || ['', ''];
		var label, open = false;
		if (!h[0] || !h[1]) label = 'Closed today';
		else if (now.time >= h[0] && now.time < h[1]) { open = true; label = 'Open now · until ' + fmt(h[1]); }
		else if (now.time < h[0]) label = 'Opens today at ' + fmt(h[0]);
		else label = 'Closed now';
		$$('[data-open-badge]').forEach(function (b) {
			b.classList.toggle('is-open', open);
			var t = $('[data-open-text]', b);
			if (t) t.textContent = label;
		});
	}
	refreshOpen();
	setInterval(refreshOpen, 60000);

	/* ---- Map: only talks to Google after a tap -------------------------- */
	$$('[data-map]').forEach(function (wrap) {
		var btn = $('[data-map-load]', wrap);
		if (!btn) return;
		btn.addEventListener('click', function () {
			var f = document.createElement('iframe');
			f.src = 'https://www.google.com/maps?q=' + encodeURIComponent(cfg.mapQuery || '') + '&output=embed';
			f.title = 'Map showing the clinic location';
			f.loading = 'lazy';
			f.referrerPolicy = 'no-referrer-when-downgrade';
			wrap.innerHTML = '';
			wrap.appendChild(f);
		});
	});

	/* ---- Booking form ---------------------------------------------------- */
	var overlay = $('[data-message-overlay]');
	var lastFocus = null;

	function openOverlay(title, text) {
		if (!overlay) return;
		lastFocus = document.activeElement;
		$('[data-message-title]', overlay).textContent = title;
		$('[data-message-text]', overlay).textContent = text;
		overlay.hidden = false;
		var box = $('[data-message-box]', overlay);
		// Restart the check-mark animation each time.
		var check = $('.success-check', overlay);
		if (check) { check.style.animation = 'none'; void check.getBoundingClientRect(); check.style.animation = ''; }
		box.focus();
	}
	function closeOverlay() {
		if (!overlay || overlay.hidden) return;
		overlay.hidden = true;
		if (lastFocus && lastFocus.focus) lastFocus.focus();
	}
	if (overlay) {
		$('[data-message-close]', overlay).addEventListener('click', closeOverlay);
		overlay.addEventListener('click', function (e) { if (e.target === overlay) closeOverlay(); });
	}

	var DAY_KEYS = ['sun', 'mon', 'tue', 'wed', 'thu', 'fri', 'sat'];
	function isClosedDate(value) {
		var m = /^(\d{4})-(\d{2})-(\d{2})$/.exec(value || '');
		if (!m) return false;
		var d = new Date(parseInt(m[1], 10), parseInt(m[2], 10) - 1, parseInt(m[3], 10));
		return (cfg.closed || []).indexOf(DAY_KEYS[d.getDay()]) !== -1;
	}

	$$('[data-booking-form]').forEach(function (form) {
		var status = $('[data-form-status]', form);
		var submit = $('[data-submit]', form);
		var label = $('[data-submit-label]', form);
		var date = $('[data-date]', form);
		var hint = $('[data-date-hint]', form);

		function setError(name, msg) {
			var out = $('[data-error-for="' + name + '"]', form);
			var input = form.elements[name];
			var field = out && out.closest('.field');
			if (out) out.textContent = msg || '';
			if (field) field.classList.toggle('has-error', !!msg);
			var el = input && (input.length ? input[0] : input);
			if (el && el.setAttribute) { msg ? el.setAttribute('aria-invalid', 'true') : el.removeAttribute('aria-invalid'); }
		}
		function clearErrors() {
			$$('[data-error-for]', form).forEach(function (o) { setError(o.getAttribute('data-error-for'), ''); });
			if (status) { status.textContent = ''; status.className = 'form-note'; }
		}

		if (date) {
			date.addEventListener('change', function () {
				var closed = isClosedDate(date.value);
				hint.classList.toggle('is-warn', closed);
				hint.textContent = closed ? 'We are closed that day. Please pick another date.' : 'Closed days are not available.';
				date.setCustomValidity(closed ? 'Closed that day' : '');
			});
		}

		function validate() {
			var errors = {};
			var v = function (n) { return (form.elements[n].value || '').trim(); };
			if (v('name').length < 2) errors.name = 'Please enter your name.';
			var digits = v('phone').replace(/\D/g, '');
			if (digits.length < 7 || digits.length > 15) errors.phone = 'Please enter a phone number we can reach you on.';
			if (v('email') && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v('email'))) errors.email = 'That email address does not look right.';
			if (!v('service')) errors.service = 'Please choose a service.';
			if (!v('date')) errors.date = 'Please pick a date.';
			else if (isClosedDate(v('date'))) errors.date = 'We are closed that day. Please pick another date.';
			if (!v('slot')) errors.slot = 'Please choose a time of day.';
			if (!form.elements.consent.checked) errors.consent = 'Please agree so we can contact you about this request.';
			return errors;
		}

		form.addEventListener('submit', function (e) {
			clearErrors();
			var errors = validate();
			var names = Object.keys(errors);
			if (names.length) {
				e.preventDefault();
				names.forEach(function (n) { setError(n, errors[n]); });
				var first = form.elements[names[0]];
				if (first) (first.length ? first[0] : first).focus();
				return;
			}
			if (!window.fetch || !window.FormData) return; // plain POST fallback

			e.preventDefault();
			submit.disabled = true;
			label.textContent = 'Sending…';

			fetch(cfg.endpoint || form.action, {
				method: 'POST',
				body: new FormData(form),
				headers: { 'Accept': 'application/json' },
				credentials: 'same-origin'
			}).then(function (r) {
				return r.json().catch(function () { return { ok: false, message: 'Something went wrong. Please call us.' }; });
			}).then(function (data) {
				if (data.ok) {
					form.reset();
					if (hint) { hint.classList.remove('is-warn'); hint.textContent = 'Closed days are not available.'; }
					openOverlay('Request received', data.message);
				} else {
					if (data.errors) Object.keys(data.errors).forEach(function (n) { setError(n, data.errors[n]); });
					status.textContent = data.message || 'Please check the form and try again.';
					status.className = 'form-note is-error';
				}
			}).catch(function () {
				status.textContent = 'We could not send your request. Please check your connection or call ' + (cfg.phone || 'us') + '.';
				status.className = 'form-note is-error';
			}).then(function () {
				submit.disabled = false;
				label.textContent = 'Send request';
			});
		});
	});
})();
