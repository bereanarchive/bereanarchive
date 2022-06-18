/**
 * Javascript for the side navigation. */
$(function() {

	// Make nav scroll with page.
	var $nav = $('#nav');
	$(document).on('scroll', function() { 
		$(document.body).toggleClass('scrolled', window.pageYOffset > $nav.offset().top);

		// Adjust height of navSlideContainer to match available height.
		//var $navSlideContainer = $('#navSlideContainer');
		//if ($navSlideContainer.length) {
		//	var height = window.innerHeight - ($('#navSlideContainer').offset().top - window.pageYOffset);
		//	var fromBottom = $(document).height() - ($(window).scrollTop() + window.innerHeight);
		//	var footerHeight = $('#footer').outerHeight()
		//	if (fromBottom < footerHeight)
		//		height -= (footerHeight - fromBottom);		
		//	$('#navSlideContainer > div').css({maxHeight: height});
		//}
	});
	
	// Enable swipe to close mobile menu
	var mc = new Hammer.Manager($('#nav')[0]);
	mc.add( new Hammer.Swipe({ threshold: 150 }) );
	mc.on("swipeleft", function() {
		$(document.body).removeClass('mobileMenu');
	});
});




