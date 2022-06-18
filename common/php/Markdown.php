<?php

class Markdown {


	static function phpify(string $markdownContent, &$hasTheme = false) {
		if (substr(ltrim($markdownContent), 0, 3) === '---') {
			$frontMatter = Parse::getTagContent($markdownContent, '---', '---', $frontMatterEnd);

			// Split  the front matter into an associative array of names and values.
			// TODO: Use actual yaml parser to allow comments?
			$frontMatterParams = splitAA($frontMatter, '\s*\n\s*', '\s*:\s*');
			unset($frontMatterParams['']);
			$frontMatterPhp = "<php markdown=\"1\">php\r\n";
			foreach ($frontMatterParams as $name=>$value) {
				$value = addslashes($value);
				$frontMatterPhp .= "\$$name = '$value';\r\n";
				if ($name === 'theme')
					$hasTheme = true;
			}
			if ($hasTheme)
				$frontMatterPhp .= "ob_start()?>\r\n";
			else
				$frontMatterPhp .= "</php>\r\n";

			return $frontMatterPhp . substr($markdownContent, $frontMatterEnd);
		}
	}

	static function markdownToHtml(string $markdown) : string {
		$markdown = self::phpify($markdown, $hasTheme);


		// These are big and adds to load time, so only require it if we're not cached and need to use it.
		require_once __DIR__ . '/Parsedown.php';
		require_once __DIR__ . '/ParsedownExtra.php';
		require_once __DIR__ . '/ParsedownExtended.php';

		$pd = new ParseDownExtended([
			'scripts' => true, // Superscript and subscript.
			"mark" => true,
			"insert" => true
		]);

		$markdown = str_replace('<?', '<php>', $markdown);
		$markdown = str_replace('?>', '</php>', $markdown);


		$markdown = $pd->text($markdown);
		$markdown = self::reOrderFootnotes($markdown); // will break php tags if they're not encoded.

		$markdown = preg_replace('#<php>\s*<p>|<php>|&lt;php&gt;#', '<?', $markdown);
		$markdown = preg_replace('#</p>\s*</php>|</php>|&lt;/php&gt;#', '?>', $markdown);

		// Remove <mark></mark> tags b/c that's used for TO DO's
		$markdown = preg_replace('#<mark>(.*)?</mark>#', '', $markdown);


		if ($hasTheme)
			$markdown .= "<?php\r\n\$content = ob_get_clean();\r\ninclude \$theme;?>";

		// Add attributes to the subsequent element with {[attribute="..."]}
		// We use ^½ to match anything, since ½ is rarely used.
		$markdown = preg_replace_callback('/{\[([^]]+)[^½]+?<\w[^½]*?>/m', function($matches) {

			$attribsAndTag = $matches[0];  // Matches text starting with {[ up past } and until and including the first > that's part of an open tag.
			$attribs = $matches[1];        // Matches only what's inside the {[ ... ]}.

			// Insert the new attribs before the last >
			$result = substr($attribsAndTag, 0, strlen($attribsAndTag)-1) . ' ' . $attribs . '>';

			// Remove the {[ ... }
			return preg_replace('/{[^}]+?}/', '', $result);
		}, $markdown);


		// Replace invalid characters in <a id=""> attributes.
		// Otherwise javascript can't link to them.
		// But this completely broke the footnotes system with its "fn:source:a" syntax, so it's commented out.
//		$markdown = preg_replace_callback('/id="(.*?)"/', function($matches) {
//			// Remove characatesr that have special meaning to css selectors.
//			$id = preg_replace('/[.#+~*,>=^$|:()\[\]]/', '', $matches[1]);
//
//			// Make sure id starts with a letter.
//			if (preg_match('/^[^a-z]/', $id))
//				$id = 'i' . $id;
//
//			return ' id="'.$id.'"';
//		}, $markdown);



		return $markdown;
	}

	/**
	 * Given a string of html containing <div class="footnotes"><ol><li id="fn:sourceName">... ,
	 * Find the li's with id's indicating they should be children, such as <li id="fn:sourceName:a">
	 * and make them children of the parents with the same sourceName.
	 * The format of html we use as input is what's output by parsdown.*/
	static function reOrderFootnotes(string $html) : string {

		$doc = new DomDocument();
		$utf8Prefix = '<?xml encoding="utf-8" ?>';
		$html = '<html><body>' . $html . '</body></html>';
		$doc->loadHtml(mb_convert_encoding($utf8Prefix . $html, 'HTML-ENTITIES', 'UTF-8')); // "@" to prevent it from complaining about invalid html.

		$xpath = new DOMXpath($doc);
		$footnoteLis = $xpath->query("//div[@class='footnotes']/ol/li");



		$lis = [];
		/** @var DOMElement $child */
		foreach ($footnoteLis as $child) {
			$id = $child->getAttribute('id');
			$lis[$id] = $child;
		}

		// Build a list of all the parent footnotes
		$parents = [];
		foreach ($lis as $id => $li)
			if (substr_count($id, ':') === 1) { // "fn:sourceName"
				$id = substr($id, 3); // trim the "fn:" prefix.
				$parents[$id] = $li;
			}

		// Add all the child footnotes to the parents.
		foreach ($lis as $id => $li) {
			$id = substr($id, 3); // trim the "fn:" prefix.
			if (substr_count($id, ':') >= 1) {// "fn:sourceName:a"

				$split = strpos($id, ':');
				$id = substr($id, 0, $split);

				// If this is false then the child has no parent.
				if (isset($parents[$id])) {

					$parent = $parents[$id];
					if ($parent->lastChild->nodeName !== 'ol')
						$parent->appendChild($doc->createElement('ol'));

					$parent->lastChild->appendChild($li);
				}
			}
		}

		// Update footnote names within the text to match the new order.
		$footnoteLis = $xpath->query("//div[@class='footnotes']/ol/li");
		/** @var DOMElement $li */
		foreach ($footnoteLis as $i=>$li) {
			$id = str_replace('fn:', '', $li->getAttribute('id'));

			// Find the corresponding footnote within the text and update its printed name..
			$refAs = $xpath->query("//a[@href='#fn:$id']");
			foreach ($refAs as $refA)
				$refA->nodeValue = $i+1;


			// Loop through all children of this footnote
			$childLis = $xpath->query("ol/li", $li);
			/** @var DOMElement $li2 */
			foreach ($childLis as $j=>$li2) {

				$id2 = str_replace('fn:', '', $li2->getAttribute('id'));

				// And update thier printed names.
				$refAs = $xpath->query("//a[@href='#fn:$id2']");
				foreach ($refAs as $refA)
					$refA->nodeValue = ($i+1) . chr($j + 1 + 96); // e.g. "2a"
			}
		}

		// TODO: This has nothing to do with footnotes.
		// Preserve multiple spaces only inside p tags.
		$ps = $xpath->query("//p/text()");
		foreach ($ps as $p) {
			$value = $p->nodeValue;

			// Preserve triple and double spaces.
			$value = str_replace('   ', " \xc2\xa0 ", $value); // \xc2\xa0 is utf-8 for&nbsp;
			$value = str_replace('  ', "\xc2\xa0 ", $value);

			$p->nodeValue = $value;
		}

		$innerHtml = function($node) {
			$result= [];
			foreach ($node->childNodes as $child)
				$result []= $child->ownerDocument->saveHtml($child);
			return implode('', $result);
		};

		$body = $doc->childNodes[2]->childNodes[0];
		$html = $innerHtml($body);
		$html = str_replace($utf8Prefix, '', $html);
		return $html;
	}
}

