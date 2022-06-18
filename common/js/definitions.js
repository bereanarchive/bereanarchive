/**
 * If this file is included, it adds a title attribute with a definition for any word inside <dfn></dfn> tags,
 * pulling the definitions from common/includes/definitions.md */



function stem(word) {
	return word.replace(/(s|ed|ing|ion)$/, '');
}

/**
 * Markdown puts definitions in the format:
 *
 * term 1
 * : Definition of term 1.
 *
 * term 2, synonym of term 2
 * : Definition of term 2.
 *
 * This function parses such markdown and returns a map from each term to the definition.
 *
 * @param text {string}
 * @returns {object<string, string>} Map from term to definition. */
function parseMarkdownDefinitions(text) {
	let result = {};
	let items = text.split(/\r?\n[ \t]*\r?\n/g); // split on two line returns with optional whitespace in between.
	for (let item of items) {
		let pieces = item.split(/\r?\n:\s*/); // Split on line return followed by colon.
		let terms =  pieces[0].split(/\s*,\s*/g);
		let definition = pieces[1].trim();
		for (let term of terms)
			result[term.trim().toLowerCase()] = definition;
	}
	return result;
}

function addTooltips(definitions) {
	var dfns = document.querySelectorAll('dfn');
	for (let el of dfns) {

		// Clean up term text.
		var term = el.innerHTML.toLowerCase();
		term = term.replace(/\s+/g, ' ').trim();
		var stemmed = stem(term);

		// Set the title attribute if the definition exists.
		var def = definitions[term] || definitions[stemmed];
		if (def)
			el.setAttribute('title', def);


		// If no definition for term, remove the dfn tag and log that it's missing.
		else {
			el.parentNode.insertBefore(el.firstChild, el);
			el.parentNode.removeChild(el);
			console.log('No defintion for ' + term);
		}
	}

}

// Download and apply teh definitions.
fetch('/common/includes/definitions.md')
.then(response => response.text())
.then(text => {
	let definitions = parseMarkdownDefinitions(text);
	addTooltips(definitions);

});
