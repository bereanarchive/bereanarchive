<?php

class Parse {
	// Read data from inside the given tagname.
	public static function getTagContent($text, $startTag, $endTag, &$end = 0) {
		$start = strpos($text, $startTag);
		if ($start === false)
			return '';
		$start += strlen($startTag);
		$end = strpos($text, $endTag, $start);
		if ($end === false)
			return '';
		$length = $end - $start;
		$end += strlen($endTag);
		return substr($text, $start, $length);
	}

}



/**
 * Parse a string into an associative array
 * @param string $string
 * @param string $splitOuter regex used to split the string into key/value pairs, defaults to ','
 * @param string $splitInner regex used to split each key/value, defaults to '='
 * @return array
 * @example
 * strToAA("a=b,c=d,e=f", ',', '='); // returns array('a'=>'b', 'c'=>'d', 'e'=>'f') */
function splitAA( $string, $splitOuter=';', $splitInner=':') {
	if (!strlen($string))
		return [];
	$kvpairs = preg_split('/'.$splitOuter.'/i', $string);
	$result = [];
	foreach ($kvpairs as $kv) {
		$kvsplit = preg_split('/'.$splitInner.'/i', $kv, 2);
		if (isset($kvsplit[1]))
			$result[$kvsplit[0]] = $kvsplit[1];
		else
			$result[$kvsplit[0]] = '';
	}
	return $result;
}

function cache($inputPath, $outputPath, $processor) {

	if (!file_exists($outputPath) || filemtime($inputPath) > filemtime($outputPath)) {
		$result = $processor(file_get_contents($inputPath));
		$cacheFolder = dirname($outputPath);
		if (!file_exists($cacheFolder))
			mkdir(dirname($outputPath), 0777, true);
		file_put_contents($outputPath, serialize($result));
		return $result;
	}
	return unserialize(file_get_contents($outputPath));


}

class Markdown {

	static function getParamsFromMarkdown($markdownContent) {

		// Convert front matter to params.
		$params = [];
		$frontMatterEnd = 0;
		$frontMatter = Parse::getTagContent($markdownContent, '---', '---', $frontMatterEnd);
		if (strlen($frontMatter))
			$params = splitAA(trim($frontMatter), "\s*\n\s*", ":\s*");

		// Content param is all the markdown.
		$params['content'] = substr($markdownContent, $frontMatterEnd);


		// Eval any php code within the params.
		foreach ($params as $name => &$value) {
			try {
				ob_start();
				eval('?>' . $value);
				$value = ob_get_clean();
			} catch (Exception $e) {
				var_dump($e);
				ob_end_flush();
				die;
			}
		}

		return $params;
	}

	static function markdownToHtml($markdown) {

		/* // new
		require_once 'common/php/Parsedown.php';
		require_once 'common/php/ParsedownExtra.php';
		require_once 'common/php/ParsedownExtended.php';

		$pd = new ParseDownExtended([
			'scripts' => true, // Superscript and subscript.
			"mark" => true,
			"insert" => true
		]);
		*/

		// Old:
		// Use ParseDown to convert from Markdown to Html.
		require_once 'common/php/ParsedownExtreme.php'; // It's big and adds to load time, so only require it if we use it.


		$pd = new ParseDownExtreme();
		$pd->superscript(true);
		$pd->insert(true);
		$pd->mark(true);
		//$pd->footnotes(false);


		$markdown = $pd->text($markdown);
		

		// Add attributes to the subsequent element with {:class="..."}
		// We use ^½ to match anything, since ½ is rarely used.
		$markdown = preg_replace_callback('/{:([^}]+)[^½]+?<\w[^½]*?>/m', function($matches) {

			$attribsAndTag = $matches[0];  // Matches text starting with {: up past } and until and including the first > that's part of an open tag.
			$attribs = $matches[1];        // Matches only what's inside the {: ... }.

			// Insert the new attribs before the last >
			$result = substr($attribsAndTag, 0, strlen($attribsAndTag)-1) . ' ' . $attribs . '>';

			// Remove the {: ... }
			return preg_replace('/{[^}]+?}/', '', $result);
		}, $markdown);


		// Replace invalid characters in <a id=""> attributes.
		// Otherwise javascript can't link to them.
		$markdown = preg_replace_callback('/id="(.*)"/', function($matches) {
			// Remove characatesr that have special meaning to css selectors.
			$id = preg_replace('/[.#+~*,>=^$|:()\[\]]/', '', $matches[1]);

			// Make sure id starts with a letter.
			if (preg_match('/^[^a-z]/', $id))
				$id = 'i' . $id;

			return ' id="'.$id.'"';
		}, $markdown);

		return $markdown;
	}

	/**
	 * Given a string of html containing <div class="footnotes"><ol><li id="fn:sourceName">... ,
	 * Find the li's with id's indicating they should be children, such as <li id="fn:sourceName:a">
	 * and make them children of the parents with the same sourceName.
	 * The format of html we use as input is what's output by parsdown.
	 * @param $html
	 * @return string */
	static function reOrderFootnotes(string $html) {

		$doc = new DomDocument();
		$utf8Prefix = '<?xml encoding="utf-8" ?>';
		$doc->loadHtml(mb_convert_encoding($utf8Prefix . $html, 'HTML-ENTITIES', 'UTF-8')); // "@" to prevent it from complaining about invalid html.

		$xpath = new DOMXpath($doc);
		$footnoteLis = $xpath->query("//div[@class='footnotes']/ol/li");

		$parents = [];
		$lis = [];


		/** @var DOMElement $child */
		foreach ($footnoteLis as $child) {
			$id = $child->getAttribute('id');
			$lis[$id] = $child;
		}

		// Build a list of all the parent footnotes
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
				//$childId = substr($id, $split + 1);

				// If this is false then the child has no parent.
				if (isset($parents[$id])) {
				
					$parent = $parents[$id];
					if ($parent->lastChild->nodeName !== 'ol')
						$parent->appendChild($doc->createElement('ol'));

					/** @var DOMElement $ol */
					$ol = $parent->lastChild;

					$status = $ol->appendChild($li);
				}
			}
		}

		// Update footnote names within the text to match the new order.
		$footnoteLis = $xpath->query("//div[@class='footnotes']/ol/li");
		foreach ($footnoteLis as $i=>$li) {
			$id = str_replace('fn:', '', $li->getAttribute('id'));

			// Find the corresponding footnote within the text and update its printed name..
			$refAs = $xpath->query("//a[@href='#fn:$id']");
			foreach ($refAs as $refA)
				$refA->nodeValue = $i+1;


			// Loop through all children of this footnote
			$childLis = $xpath->query("ol/li", $li);
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



		$html = $doc->saveHtml();
		$html = str_replace($utf8Prefix, '', $html);
		return $html;
	}


	static function buildTheme($params) {
		extract($params);
		if (isset($params['theme'])) {
			ob_start();
			require_once trim($params['theme'], '/\\');
			return ob_get_clean();
		}
		else // TODO: Print html/head/body so we can at least still set title and a few other attributes.
			return $params['content'];
	}


	static function render($mdFilePath, $cachePath=null) {
	

		$process = function($content) {

			// Split out variables from markdown front matter and the content.
			$params = self::getParamsFromMarkdown($content);

			//  If we have a caption, temporarily add it to the content so we can markdownify it all together.
			// This allows citations in the caption to cite footnotes in the content.
			$hasCaption = isset($params['caption']);
			if ($hasCaption)
				$params['content'] = $params['caption'] . '@@@CaptionContent@@@' .$params['content'];


			$params['content'] = self::markdownToHtml($params['content']);

			// Split it back apart again.
			if ($hasCaption)
				list($params['caption'], $params['content']) = explode('@@@CaptionContent@@@', $params['content'], 2);

			
			$params['content'] = self::reOrderFootnotes($params['content']);

			return $params;
		};


		if ($cachePath) {
			$cacheFile = $cachePath . preg_replace('/\\//', '_', $mdFilePath) . '.mdCache';

			$params = cache($mdFilePath, $cacheFile, $process);
		}
		else { // no cache
			$params = $process(file_get_contents($mdFilePath));
		}


		$html = self::buildTheme($params);

		return $html;
	}
}




