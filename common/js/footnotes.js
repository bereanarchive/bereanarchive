var Footnotes = {

	blockElements: ['li', 'p', 'div', 'blockquote', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6'],
	isMobile: window.navigator.maxTouchPoints || 'ontouchstart' in document,  // stackoverflow.com/a/45113336


	/**
	 * Find the first parent of el that matches one of hte parentTagnames.
	 * @param el {HTMLElement|Node}
	 * @param parentTagNames {string|string[]}
	 * @returns {HTMLElement} */
	findParent: function (el, parentTagNames) {
		if (!Array.isArray(parentTagNames))
			parentTagNames = [parentTagNames];

		for (var i = 0; i < parentTagNames.length; i++)
			parentTagNames[i] = parentTagNames[i].toUpperCase();

		do {
			if (parentTagNames.indexOf(el.tagName) !== -1)
				return el;
		} while (el && (el = el.parentNode))
	},

	getParentId: function(id) {
		var firstColon = id.indexOf(':');
		if (firstColon !== -1) {
			var secondColon = id.substr(firstColon+1).indexOf(':');
			if (secondColon !== -1)
				return id.substr(0, secondColon + firstColon+1);
		}
		return null;
	},

	/**
	 * Show the tooltip with the given id.
	 * It will show as a popup on desktops, and it will slide in below the curret paragraph on mobile.
	 * @param e {Event}
	 * @param id {string} The id of the tooltip to show.  This is what's in the short tag.  E.g. [cite id].  */
	showTooltip: function (e, id) {
		e.preventDefault();
		e.stopPropagation();
		e.stopImmediatePropagation(); // prevent scrolling code.
		
		
		var $ = jQuery;

		var alreadyExists = $('[data-id="' + id + '"]').length > 0;
		Footnotes.hideTooltips(e);
		if (alreadyExists)
			return; // close but don't show again.


		// Create the tooltip
		var originalEl = document.getElementById(id);
		var content = originalEl.cloneNode(true);
		content.removeAttribute('id');

		// Create the tooltip:  Find a parent to also incldue if it exists.
		var parentId = Footnotes.getParentId(id);
		if (parentId) {
			var parent = document.getElementById(parentId);
			if (parent) {
				parent = parent.cloneNode(true);
				var ol = $(parent).find('ol')[0];
				if (!ol)
					parent.appendChild(ol = document.createElement('ol'));
				ol.innerHTML = ''; // Remove other children.
				ol.appendChild(content);

				// Make the child list item start at its index, instead of always being "a"
				var index = Array.prototype.indexOf.call(originalEl.parentNode.childNodes, originalEl)+1;
				ol.setAttribute('start', index);

				content = parent;
			}
		}

		// Remove sub-sources from the tooltip if we link directly to the main source.
		if (!parentId || !parent)
			$(content).find('ol').remove();


		// Remove stuff inserted by markdown processor
		$(content).find('.footnote-backref').remove();

		//$(content).find('p').unwrap();

		content = content.outerHTML;
		var source = e.target.innerText;
		var tooltipEl = $(
			'<div class="cleanTooltip" data-id="' + id + '">' +
			'<div>' + // Border and border radius is applied to this inner element, so it doesn't mess up the slideIn() animation.
			'<div class="cleanTooltipHeader"><a href="javascript:void(0)" class="cleanTooltipClose">x</a></div>' +
			'<div class="cleanTooltipBody"><ol start="' + source + '">' + content + '</ol>' +
			'<a href="#' + id + '" class="viewInFootnotes" onclick="Footnotes.viewFootnote(event)">View in footnotes.</a>' +
			'<div style="clear: both"></div></div></div>' +
			'</div>' +
			'</div>')[0];
		$(tooltipEl).find('a.returnToText').remove();

		// Add it after the current paragraph.
		var block = Footnotes.findParent(e.target, Footnotes.blockElements);
		block.parentNode.insertBefore(tooltipEl, block.nextSibling);


		if (Footnotes.isMobile) {
			$(tooltipEl).hide().slideDown(200);

			// Scroll to where the tooltip appeared.
			Footnotes.scrollToElement(tooltipEl);
		}
		else {
			var tether = new Tether({
				element: tooltipEl,
				target: e.target,
				attachment: 'top left',
				targetAttachment: 'bottom right',
				offset: '-12px -10px',
				constraints: [
					{
						to: 'scrollParent',
						attachment: 'together'
					}
				]
			});
			$(tooltipEl).hide().fadeIn(150);
			tether.position();
		}

		setTimeout(function () {
			$(document.body).on('click', Footnotes.hideTooltips);
		}, 1);
	},

	/**
	 * Hide all open tooltips.
	 * @param e {MouseEvent} */
	hideTooltips: function(e) {
		var $ = jQuery;
		if (!$(e.target).parents('.cleanTooltip').length || e.target.classList.contains('cleanTooltipClose')) { // if not clicking inside tooltip
			var $tooltip = $('.cleanTooltip');

			// Slide-up tooltips opened on modal.
			if (Footnotes.isMobile)
				$tooltip.slideUp(200, function () {
					$tooltip.remove();
				});

			// Fade out tooltips
			else {
				$tooltip.fadeOut(150, function () {
					$tooltip.remove();
				});
			}

			// Remove the event that triggers this function on click.
			$(document.body).off('click', Footnotes.hideTooltips);
		}
	},

	/**
	 * Hide a popup footnote opened from a [cite] shortcode and scroll to the [footnote] version.
	 * @param e {Event} */
	viewFootnote: function (e) {
		e.preventDefault();
		jQuery(e.target.parentNode.parentNode).remove();
		var id = e.target.getAttribute('href').substr(1);
		console.log(id);
		var el = document.getElementById(id);
		Footnotes.scrollToElement(el);
	},

	/**
	 * Scroll the page to the top position of an element with a 500ms animation.
	 * @param el {HTMLElement} */
	scrollToElement: function (el) {
		jQuery('html, body').animate({
			scrollTop: jQuery(el).offset().top - 50
		}, 500);
	},
	
	// These functions are used only when not in markdown mode:
	// Because md2html.php and parsedown already convert the markdown footnotes.


	/**
	 * Find all text nodes within el and merge them.
	 * This is necessary on IE because IE will often split one string of text into mutliple nodes.
	 * If this split occurs within a short tag, the javascript regex will not find it.
	 * @param el {HTMLElement} */
	mergeAdjacentTextNodes: function (el) {
		var prev = null;
		var node = el.firstChild;
		while (node) {

			// Recurse first, that way empty children will be removed and we can detect whether this node is empty.
			if (node.nodeType === 1)
				Footnotes.mergeAdjacentTextNodes(node);

			// Merge adjacent text nodes (in addition to the other conditions below
			if (prev && prev.nodeType === 3 && node.nodeType === 3) {
				prev.textContent += node.textContent;
				node.parentNode.removeChild(node);
				node = prev || el.firstChild;
			}

			// el has no children.
			if (!node)
				break;

			prev = node;
			node = node.nextSibling;
		}
	},
	

	/**
	 * Visit all nodes that are descendants of parent.
	 * @param parent {HTMLElement}
	 * @param callback {function(node:Node):boolean}
	 * @returns {boolean} */
	visitNodes: function (parent, callback) {
		if (parent.children)
			for (var i=0, node; node = parent.children[i]; i++) { // We iterate children and not childNodes
				if (callback(node) === false) // because the code that uses this function iterates the childNodes.
					return false;
				Footnotes.visitNodes(node, callback);
			}
	}
};


/**
 * Show tooltips when footnotes are clicked. */
$(function() {
	$(document).on('click', 'a.footnote-ref', function(e) {
		Footnotes.showTooltip(e, e.target.getAttribute('href').substr(1));
	});
});


/**
 * This code creates hyperlinked footnotes by matching [^tags] in the page text
 * with those inside <div class="footnotes">.
 * It's only used on php pages that don't go through the markdown processor. */
jQuery(function () {
	var $ = jQuery;

	var footnotes = []; // array of footnote id's.
	var $entries = $('.entry-content');
	if (!$entries.length)
		$entries = $(document.body); // Search the entry.  If there is no entry, search the whole body.

	for (var i=0, entry; entry = $entries[i]; i++) {

		// If is internet explorer, merge ajacent text nodes.  Because IE splits text nodes apart.
		if (window.navigator.userAgent.indexOf('Trident') !== -1)
			Footnotes.mergeAdjacentTextNodes(entry);

		// Replace [footnote id] shortcodes that we find and build a list of them.
		var footnotesEl = $('.footnotes')[0];
		if (!footnotesEl)
			return;

		Footnotes.visitNodes(footnotesEl, function (node) {
			for (var i = 0, child; child = node.childNodes[i]; i++) {
				if (child.nodeType === 3) { // text node
					var matchCount = 0;

					var newHtml = child.textContent.replace(/\[\^([^\]]+)\s*\]\:/g, function (match, id) {
						footnotes.push(id);
						matchCount++;

						var parent = Footnotes.findParent(child, Footnotes.blockElements);
						parent.id = 'fn:' + id;

						return '';
					});

					// If we found a footnote.
					if (matchCount) {
						var newNode = $('<span>' + newHtml + '</span>')[0];
						// Wrapping it in a span fixes a bug that causes some of the text to go missing.
						// The cause is unknown.
						node.insertBefore(newNode, child);
						node.removeChild(child);
					}
				}
			}
		});


		// Replace source [cite id] short codes in the rest of the text.
		//var entry = $('.text')[0];
		var entry = document.body; // so we get footnotes from the header.
		Footnotes.visitNodes(entry, function (node) {
			for (var i=0, child; child = node.childNodes[i]; i++) {
				if (child.nodeType === 3) { // text node
					var matchCount = 0;
					var newHtml = child.textContent.replace(/\[\^([^\]]+)\s*\]/g, function (match, subMatch) {
						matchCount++;

						// Find index in footnotes
						var index = footnotes.indexOf(subMatch);

						if (index !== -1)
							return '<sup class="fnref' + (index+1) + ':' + subMatch + '">' + 
								'<a class="footnote-ref" id="' + subMatch + '-inline" ' +
								'title="Show footnote." href="#fn:' + subMatch + '">' + (index + 1) + '</a></sup>';
						else
							console.log('Could not find footnote for "[cite ' + subMatch + ']"');
						return subMatch;
					});
					if (matchCount) {
						var newNode = $('<span>' + newHtml + '</span>')[0];
						node.insertBefore(newNode, child);
						node.removeChild(child);
					}
				}
			}
		});
	}
});

