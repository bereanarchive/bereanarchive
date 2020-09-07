$(function() {
	
	// Open mobile toc when menu button is clicked.
	$('li#topNavMobile').on('click', function() {
		$(document.body).toggleClass('mobileMenu');
	});
	
	// Smooth scroll on hash click.	
	$(document).on('click', 'a[href^="#"]:not([href="#"])', function(e) {

		document.body.classList.remove('mobileMenu');
		var hash = '#' + this.href.split(/#/)[1];
		var el = $(hash.replace(/\./, '\.'))[0]; // querySelector won't easily work here bc selectors often have : and other special chars.
		if (el) {
			e.preventDefault();
			scrollHighlightEnabled = false;
			scrollToEl(el);
			setTimeout(function () {
				scrollHighlightEnabled = true;
			}, 450); //longer than scrollToEl animation length
			history.pushState(null, null, hash);
		}
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
	
	// Take every img.lightbox and wrap it in a link that opens the lightbox.
	// This saves a step when writing new articles.
	$('img.lightbox').each(function(i, img) {
		var a = document.createElement('a');
		a.href = img.src.split('?')[0];
		a.className = 'lightbox';

		// Add inverted class containing a element,
		// so that the magnific selector below can add it to the mainClass of magnific.
		if (img.classList.contains('inverted'))
			a.classList.add('inverted');
		img.parentNode.insertBefore(a, img);
		a.appendChild(img);
	});


	$('a.lightbox').magnificPopup({
		type:'image',
		gallery:{
		//	enabled: true // Messes up which lightboxes are inverted.
		},
		mainClass: 'mfp-with-zoom', // this class is for CSS animation below

		zoom: {
			enabled: true, // By default it's false, so don't forget to enable it
			duration: 200, // duration of the effect, in milliseconds
			easing: 'ease-in-out', // CSS transition easing function
		}
	});


	$('a.lightbox.inverted').magnificPopup({
		type:'image',
		gallery:{
		//	enabled: true // Messes up which lightboxes are inverted.
		},
		mainClass: 'mfp-with-zoom inverted', // this class is for CSS animation below

		zoom: {
			enabled: true, // By default it's false, so don't forget to enable it
			duration: 200, // duration of the effect, in milliseconds
			easing: 'ease-in-out', // CSS transition easing function
		}
	});




	// Hack to start loading on mouse enter.
	// Otherwise sometimes the zoom animation doesn't play.
	// https://github.com/dimsemenov/Magnific-Popup/issues/1035#issuecomment-480933392
	$('a.lightbox').on('mouseenter', (event) => {
		document.createElement('img').src = event.currentTarget.href
	});
	
});

function scrollToEl(el) {	
	if (el) {
		var fixedMenu = window.innerWidth < 950;
		$('html, body').animate({
			scrollTop: $(el).offset().top + (fixedMenu ? -45 : 0)
		}, 400);
	}
}


