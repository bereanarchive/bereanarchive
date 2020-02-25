$(function() {
	
	// Open mobile toc when menu button is clicked.
	$('li#topNavMobile').on('click', function() {
		$(document.body).toggleClass('mobileMenu');
	});
	
	// Smooth scroll on hash click.	
	$(document).on('click', 'a[href^="#"]:not([href="#"])', function(e) {
		e.preventDefault();
		document.body.classList.remove('mobileMenu');
		var hash = '#' + this.href.split(/#/)[1];
		var el = $(hash)[0];
		scrollHighlightEnabled = false;
		scrollToEl(el);
		setTimeout(function() {
			scrollHighlightEnabled = true;
		}, 350); // 50ms longer than scrollToEl animation length
		history.pushState(null, null, hash);
	});

	// Have links the footnote tooltip popup open in a new window/tab.
	$(document).on('click', '.cleanTooltip a, div.footnotes a', function(e) {
		e.preventDefault();

		// Ignore links that go nowhere.
		var href = e.target.getAttribute('href');
		if (!href || href==='#' || href==='javascript:void(0)')
			return;

		var win = window.open(e.target.href, "_blank");
		win.focus(); // Force new tab: stackoverflow.com/a/11384018
	});
	
	
	
	// Fix scroll to anchor on page load in chrome
	// stackoverflow.com/a/38588927
	var isChrome = /Chrome/.test(navigator.userAgent) && /Google Inc/.test(navigator.vendor);
	if (window.location.hash && isChrome) {
		setTimeout(function () {
			var hash = window.location.hash;
			window.location.hash = "";
			window.location.hash = hash;
		}, 300);
	}

	
});

function scrollToEl(el) {	
	if (el) {
		var fixedMenu = window.innerWidth < 950;
		$('html, body').animate({
			scrollTop: $(el).offset().top + (fixedMenu ? -45 : 0)
		}, 400);
	}
}