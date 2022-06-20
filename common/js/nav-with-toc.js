
var scrollHighlightEnabled = true;
	
/**
 * Javascript for the table of contents that is included in nav-with-toc.php. */
$(function() {
	
	// Build the table of contents
	var toc = createToc($('#main')[0]); // returns an array of a tags.
	var navToc = $('#navToc')[0];
	$(navToc).html('<div style="position: relative"></div>').find('div').append(toc);

	var tocHighlight = $('<div class="tocHighlight"></div>');
	$(navToc).find('>div').append(tocHighlight);
	
	// Enable site map and article buttons
	var $navSlide = $('#navSlideContainer');
	$('#navSiteMapTab a').on('click', function() {
		$navSlide.css({transform: 'translate(0, 0)'}); 
		$(this).addClass('selected').parent().siblings().find('a').removeClass('selected');
	});
	$('#navTocTab a').on('click', function() {
		$navSlide.css({transform: 'translate(-50%, 0)'});
		$(this).addClass('selected').parent().siblings().find('a').removeClass('selected');
	});


	// TOC item clicks
	$(navToc).on('click', 'a', function(e) {
		e.preventDefault();
		highlightToc(this, tocHighlight);
	});

	// Highlight TOC entries on scroll
	$(document).on('scroll', function() { 
		if (!scrollHighlightEnabled)
			return;
		highlightTocBasedOnScroll(tocHighlight);		
	});

	setTimeout(function() {
		highlightTocBasedOnScroll(tocHighlight);
	}, 100); // Timeout is needed to ensure elements have correct position after loading.  Not sure why.
	
});
  
/**
 * Build and return an array of <a> tags from the h2 through h6 headings inside container.
 * @param container {HTMLElement}
 * @return {HTMLElement[]} */
function createToc(container) {
	$(container).find('h2,h3,h4,h5,h6').addClass('__heading'); // Giving them a temporary class makes sure we find them in order.
	var headings = $(container).find('.__heading').removeClass('__heading');

	var result = [];
	for (let i=0, h; h=headings[i]; i++) {
		var text = h.innerText;
		var anchor = h.id || h.innerText.replace(/ /g, '-').replace(/[^0-9a-z\-]/gi, '');
		if (!h.id)
			h.id = anchor;		
		result.push($('<a href="#' + anchor + '" class="' + h.tagName.toLowerCase() + '">' + text + '</a>')[0]);	
	}
	return result;	
}



/**
 * Put the toc highlighter next to el */
function highlightToc(el, tocHighlight) {
	var offset = $(el).position();
	offset.top += parseInt($(el).css('margin-top'));
	offset.left += parseInt($(el).css('margin-left')) - 10;
	offset.height = $(el).outerHeight();	
	$(tocHighlight).css(offset);
	$(el).addClass('selected').siblings().removeClass('selected');

	var navToc = $('#navToc')[0];
	var diff = offset.top - $(navToc).innerHeight();
	if (diff - navToc.scrollTop > 0)
		$(navToc).stop().animate({scrollTop: diff + offset.height + 50}, 300);
	else if (offset.top < navToc.scrollTop)		
		$(navToc).stop().animate({scrollTop: offset.top}, 300);
}

/**
 * Move the green bar to the current item. 
 * @tocHighlight {div} */
function highlightTocBasedOnScroll(tocHighlight) {
	var heading = null;
	var scrollTop = $(window).scrollTop();
	var windowHeight = window.innerHeight;
	$('#main h2, #main h3').addClass('__heading'); // Giving them a temporary class makes sure we find them in order.
	$('.__heading').removeClass('__heading').each(function() {
		var top = $(this).offset().top;
		if (!heading || top + 10 < scrollTop + windowHeight) { // If it's above the bottom of the scroll
			heading = this;
			//return false;
		}
		if (scrollTop <= top) { // If it's below the top of the scroll
			
			return false; // stops the iteration after the first one on screen
		}
		
		//heading = this;	
	});
	if (heading) {
		var navToc = $('#navToc')[0];
		var tocEl = $(navToc).find('a[href="#' + heading.id + '"]')[0];
		if (tocEl)
			highlightToc(tocEl, tocHighlight);
	}
}