/* Home page: background-video controls (pause/play, off-screen pause, reduced motion,
   Save-Data) and count-up numbers. Plain JS, no dependencies. */
(function () {
	'use strict';

	var root = document.documentElement;
	var reduced = root.classList.contains('reduce-motion') ||
		(window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches);
	var saveData = !!(navigator.connection && navigator.connection.saveData);
	var hasIO = 'IntersectionObserver' in window;

	/* ---- Background videos ---------------------------------------------- */
	Array.prototype.forEach.call(document.querySelectorAll('[data-video]'), function (v) {
		var wrap = v.closest('[data-video-wrap]');
		var btn = wrap && wrap.querySelector('[data-video-toggle]');
		var userPaused = reduced || saveData;

		function label() {
			if (!btn) return;
			btn.textContent = v.paused ? 'Play video' : 'Pause video';
			btn.setAttribute('aria-pressed', String(!v.paused));
		}
		function play() { var p = v.play(); if (p && p.catch) p.catch(function () {}); }

		if (userPaused) { v.removeAttribute('autoplay'); v.pause(); }
		v.addEventListener('play', label);
		v.addEventListener('pause', label);
		if (btn) btn.addEventListener('click', function () {
			if (v.paused) { userPaused = false; play(); } else { userPaused = true; v.pause(); }
		});
		if (hasIO) {
			new IntersectionObserver(function (entries) {
				entries.forEach(function (e) {
					if (e.isIntersecting) { if (!userPaused) play(); } else { v.pause(); }
				});
			}, { threshold: 0.15 }).observe(v);
		} else if (!userPaused) {
			play();
		}
		label();
	});

	/* ---- Count-up numbers ------------------------------------------------ */
	var nums = document.querySelectorAll('[data-count]');
	function run(el) {
		var end = parseFloat(el.getAttribute('data-count'));
		var dec = parseInt(el.getAttribute('data-decimals') || '0', 10);
		var t0 = null, dur = 1400;
		function step(t) {
			if (t0 === null) t0 = t;
			var k = Math.min(1, (t - t0) / dur);
			k = 1 - Math.pow(1 - k, 3);
			el.textContent = (end * k).toFixed(dec);
			if (k < 1) requestAnimationFrame(step);
		}
		requestAnimationFrame(step);
	}
	if (hasIO && !reduced) {
		var io = new IntersectionObserver(function (entries) {
			entries.forEach(function (e) {
				if (e.isIntersecting) { io.unobserve(e.target); run(e.target); }
			});
		}, { threshold: 0.4 });
		Array.prototype.forEach.call(nums, function (el) { el.textContent = '0'; io.observe(el); });
	}
})();
