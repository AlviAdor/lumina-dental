/* Scroll-lit statement. One passive scroll listener, one style write per frame,
   and it only runs while the section is near the viewport. */
(function () {
	'use strict';

	var root = document.documentElement;
	var el = document.querySelector('[data-lit]');
	if (!el) return;

	var reduced = root.classList.contains('reduce-motion') ||
		(window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches);
	if (reduced) { el.style.setProperty('--p', '1'); return; }

	var track = 0;      // scrollable distance inside the section
	var top = 0;        // section's document-space top
	var ticking = false;
	var last = -1;

	function measure() {
		var r = el.getBoundingClientRect();
		top = r.top + window.pageYOffset;
		var pin = el.firstElementChild;
		track = Math.max(1, r.height - (pin ? pin.offsetHeight : window.innerHeight));
		update();
	}

	function update() {
		ticking = false;
		var offset = parseFloat(getComputedStyle(root).getPropertyValue('--topbar-height')) || 0;
		var p = (window.pageYOffset + offset - top) / track;
		p = p < 0 ? 0 : p > 1 ? 1 : p;
		p = Math.round(p * 1000) / 1000;
		if (p !== last) { last = p; el.style.setProperty('--p', p); }
	}

	function onScroll() {
		if (!ticking) { ticking = true; requestAnimationFrame(update); }
	}

	function start() { window.addEventListener('scroll', onScroll, { passive: true }); measure(); }
	function stop() { window.removeEventListener('scroll', onScroll); }

	if ('IntersectionObserver' in window) {
		new IntersectionObserver(function (entries) {
			entries[0].isIntersecting ? start() : stop();
		}, { rootMargin: '25% 0px' }).observe(el);
	} else {
		start();
	}

	window.addEventListener('resize', measure);
	window.addEventListener('load', measure);
})();
