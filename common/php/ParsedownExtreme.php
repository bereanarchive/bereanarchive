<?php
// TODO: This will remove parent footnotes if the

#
#
# Parsedown
# http://parsedown.org
#
# (c) Emanuil Rusev
# http://erusev.com
#
# For the full license information, view the LICENSE file that was distributed
# with this source code.
#
#

class Parsedown
{
	# ~

	const version = '1.8.0-beta-5';

	# ~

	function text($text)
	{
		$Elements = $this->textElements($text);

		# convert to markup
		$markup = $this->elements($Elements);

		# trim line breaks
		$markup = trim($markup, "\n");

		return $markup;
	}

	protected function textElements($text)
	{
		# make sure no definitions are set
		$this->DefinitionData = array();

		# standardize line breaks
		$text = str_replace(array("\r\n", "\r"), "\n", $text);

		# remove surrounding line breaks
		$text = trim($text, "\n");

		# split text into lines
		$lines = explode("\n", $text);

		# iterate through lines to identify blocks
		return $this->linesElements($lines);
	}

	#
	# Setters
	#

	function setBreaksEnabled($breaksEnabled)
	{
		$this->breaksEnabled = $breaksEnabled;

		return $this;
	}

	protected $breaksEnabled;

	function setMarkupEscaped($markupEscaped)
	{
		$this->markupEscaped = $markupEscaped;

		return $this;
	}

	protected $markupEscaped;

	function setUrlsLinked($urlsLinked)
	{
		$this->urlsLinked = $urlsLinked;

		return $this;
	}

	protected $urlsLinked = true;

	function setSafeMode($safeMode)
	{
		$this->safeMode = (bool) $safeMode;

		return $this;
	}

	protected $safeMode;

	function setStrictMode($strictMode)
	{
		$this->strictMode = (bool) $strictMode;

		return $this;
	}

	protected $strictMode;

	protected $safeLinksWhitelist = array(
		'http://',
		'https://',
		'ftp://',
		'ftps://',
		'mailto:',
		'tel:',
		'data:image/png;base64,',
		'data:image/gif;base64,',
		'data:image/jpeg;base64,',
		'irc:',
		'ircs:',
		'git:',
		'ssh:',
		'news:',
		'steam:',
	);

	#
	# Lines
	#

	protected $BlockTypes = array(
		'#' => array('Header'),
		'*' => array('Rule', 'List'),
		'+' => array('List'),
		'-' => array('SetextHeader', 'Table', 'Rule', 'List'),
		'0' => array('List'),
		'1' => array('List'),
		'2' => array('List'),
		'3' => array('List'),
		'4' => array('List'),
		'5' => array('List'),
		'6' => array('List'),
		'7' => array('List'),
		'8' => array('List'),
		'9' => array('List'),
		':' => array('Table'),
		'<' => array('Comment', 'Markup'),
		'=' => array('SetextHeader'),
		'>' => array('Quote'),
		'[' => array('Reference'),
		'_' => array('Rule'),
		'`' => array('FencedCode'),
		'|' => array('Table'),
		'~' => array('FencedCode'),
	);

	# ~

	protected $unmarkedBlockTypes = array(
		'Code',
	);

	#
	# Blocks
	#

	protected function lines(array $lines)
	{
		return $this->elements($this->linesElements($lines));
	}

	protected function linesElements(array $lines)
	{
		$Elements = array();
		$CurrentBlock = null;

		foreach ($lines as $line)
		{
			if (chop($line) === '')
			{
				if (isset($CurrentBlock))
				{
					$CurrentBlock['interrupted'] = (isset($CurrentBlock['interrupted'])
						? $CurrentBlock['interrupted'] + 1 : 1
					);
				}

				continue;
			}

			while (($beforeTab = strstr($line, "\t", true)) !== false)
			{
				$shortage = 4 - mb_strlen($beforeTab, 'utf-8') % 4;

				$line = $beforeTab
					. str_repeat(' ', $shortage)
					. substr($line, strlen($beforeTab) + 1)
				;
			}

			$indent = strspn($line, ' ');

			$text = $indent > 0 ? substr($line, $indent) : $line;

			# ~

			$Line = array('body' => $line, 'indent' => $indent, 'text' => $text);

			# ~

			if (isset($CurrentBlock['continuable']))
			{
				$methodName = 'block' . $CurrentBlock['type'] . 'Continue';
				$Block = $this->$methodName($Line, $CurrentBlock);

				if (isset($Block))
				{
					$CurrentBlock = $Block;

					continue;
				}
				else
				{
					if ($this->isBlockCompletable($CurrentBlock['type']))
					{
						$methodName = 'block' . $CurrentBlock['type'] . 'Complete';
						$CurrentBlock = $this->$methodName($CurrentBlock);
					}
				}
			}

			# ~

			$marker = $text[0];

			# ~

			$blockTypes = $this->unmarkedBlockTypes;

			if (isset($this->BlockTypes[$marker]))
			{
				foreach ($this->BlockTypes[$marker] as $blockType)
				{
					$blockTypes []= $blockType;
				}
			}

			#
			# ~

			foreach ($blockTypes as $blockType)
			{
				$Block = $this->{"block$blockType"}($Line, $CurrentBlock);

				if (isset($Block))
				{
					$Block['type'] = $blockType;

					if ( ! isset($Block['identified']))
					{
						if (isset($CurrentBlock))
						{
							$Elements[] = $this->extractElement($CurrentBlock);
						}

						$Block['identified'] = true;
					}

					if ($this->isBlockContinuable($blockType))
					{
						$Block['continuable'] = true;
					}

					$CurrentBlock = $Block;

					continue 2;
				}
			}

			# ~

			if (isset($CurrentBlock) and $CurrentBlock['type'] === 'Paragraph')
			{
				$Block = $this->paragraphContinue($Line, $CurrentBlock);
			}

			if (isset($Block))
			{
				$CurrentBlock = $Block;
			}
			else
			{
				if (isset($CurrentBlock))
				{
					$Elements[] = $this->extractElement($CurrentBlock);
				}

				$CurrentBlock = $this->paragraph($Line);

				$CurrentBlock['identified'] = true;
			}
		}

		# ~

		if (isset($CurrentBlock['continuable']) and $this->isBlockCompletable($CurrentBlock['type']))
		{
			$methodName = 'block' . $CurrentBlock['type'] . 'Complete';
			$CurrentBlock = $this->$methodName($CurrentBlock);
		}

		# ~

		if (isset($CurrentBlock))
		{
			$Elements[] = $this->extractElement($CurrentBlock);
		}

		# ~

		return $Elements;
	}

	protected function extractElement(array $Component)
	{
		if ( ! isset($Component['element']))
		{
			if (isset($Component['markup']))
			{
				$Component['element'] = array('rawHtml' => $Component['markup']);
			}
			elseif (isset($Component['hidden']))
			{
				$Component['element'] = array();
			}
		}

		return $Component['element'];
	}

	protected function isBlockContinuable($Type)
	{
		return method_exists($this, 'block' . $Type . 'Continue');
	}

	protected function isBlockCompletable($Type)
	{
		return method_exists($this, 'block' . $Type . 'Complete');
	}

	#
	# Code

	protected function blockCode($Line, $Block = null)
	{
		if (isset($Block) and $Block['type'] === 'Paragraph' and ! isset($Block['interrupted']))
		{
			return;
		}

		if ($Line['indent'] >= 4)
		{
			$text = substr($Line['body'], 4);

			$Block = array(
				'element' => array(
					'name' => 'pre',
					'element' => array(
						'name' => 'code',
						'text' => $text,
					),
				),
			);

			return $Block;
		}
	}

	protected function blockCodeContinue($Line, $Block)
	{
		if ($Line['indent'] >= 4)
		{
			if (isset($Block['interrupted']))
			{
				$Block['element']['element']['text'] .= str_repeat("\n", $Block['interrupted']);

				unset($Block['interrupted']);
			}

			$Block['element']['element']['text'] .= "\n";

			$text = substr($Line['body'], 4);

			$Block['element']['element']['text'] .= $text;

			return $Block;
		}
	}

	protected function blockCodeComplete($Block)
	{
		return $Block;
	}

	#
	# Comment

	protected function blockComment($Line)
	{
		if ($this->markupEscaped or $this->safeMode)
		{
			return;
		}

		if (strpos($Line['text'], '<!--') === 0)
		{
			$Block = array(
				'element' => array(
					'rawHtml' => $Line['body'],
					'autobreak' => true,
				),
			);

			if (strpos($Line['text'], '-->') !== false)
			{
				$Block['closed'] = true;
			}

			return $Block;
		}
	}

	protected function blockCommentContinue($Line, array $Block)
	{
		if (isset($Block['closed']))
		{
			return;
		}

		$Block['element']['rawHtml'] .= "\n" . $Line['body'];

		if (strpos($Line['text'], '-->') !== false)
		{
			$Block['closed'] = true;
		}

		return $Block;
	}

	#
	# Fenced Code

	protected function blockFencedCode($Line)
	{
		$marker = $Line['text'][0];

		$openerLength = strspn($Line['text'], $marker);

		if ($openerLength < 3)
		{
			return;
		}

		$infostring = trim(substr($Line['text'], $openerLength), "\t ");

		if (strpos($infostring, '`') !== false)
		{
			return;
		}

		$Element = array(
			'name' => 'code',
			'text' => '',
		);

		if ($infostring !== '')
		{
			$Element['attributes'] = array('class' => "language-$infostring");
		}

		$Block = array(
			'char' => $marker,
			'openerLength' => $openerLength,
			'element' => array(
				'name' => 'pre',
				'element' => $Element,
			),
		);

		return $Block;
	}

	protected function blockFencedCodeContinue($Line, $Block)
	{
		if (isset($Block['complete']))
		{
			return;
		}

		if (isset($Block['interrupted']))
		{
			$Block['element']['element']['text'] .= str_repeat("\n", $Block['interrupted']);

			unset($Block['interrupted']);
		}

		if (($len = strspn($Line['text'], $Block['char'])) >= $Block['openerLength']
			and chop(substr($Line['text'], $len), ' ') === ''
		) {
			$Block['element']['element']['text'] = substr($Block['element']['element']['text'], 1);

			$Block['complete'] = true;

			return $Block;
		}

		$Block['element']['element']['text'] .= "\n" . $Line['body'];

		return $Block;
	}

	protected function blockFencedCodeComplete($Block)
	{
		return $Block;
	}

	#
	# Header

	protected function blockHeader($Line)
	{
		$level = strspn($Line['text'], '#');

		if ($level > 6)
		{
			return;
		}

		$text = trim($Line['text'], '#');

		if ($this->strictMode and isset($text[0]) and $text[0] !== ' ')
		{
			return;
		}

		$text = trim($text, ' ');

		$Block = array(
			'element' => array(
				'name' => 'h' . $level,
				'handler' => array(
					'function' => 'lineElements',
					'argument' => $text,
					'destination' => 'elements',
				)
			),
		);

		return $Block;
	}

	#
	# List

	protected function blockList($Line, array $CurrentBlock = null)
	{
		list($name, $pattern) = $Line['text'][0] <= '-' ? array('ul', '[*+-]') : array('ol', '[0-9]{1,9}+[.\)]');

		if (preg_match('/^('.$pattern.'([ ]++|$))(.*+)/', $Line['text'], $matches))
		{
			$contentIndent = strlen($matches[2]);

			if ($contentIndent >= 5)
			{
				$contentIndent -= 1;
				$matches[1] = substr($matches[1], 0, -$contentIndent);
				$matches[3] = str_repeat(' ', $contentIndent) . $matches[3];
			}
			elseif ($contentIndent === 0)
			{
				$matches[1] .= ' ';
			}

			$markerWithoutWhitespace = strstr($matches[1], ' ', true);

			$Block = array(
				'indent' => $Line['indent'],
				'pattern' => $pattern,
				'data' => array(
					'type' => $name,
					'marker' => $matches[1],
					'markerType' => ($name === 'ul' ? $markerWithoutWhitespace : substr($markerWithoutWhitespace, -1)),
				),
				'element' => array(
					'name' => $name,
					'elements' => array(),
				),
			);
			$Block['data']['markerTypeRegex'] = preg_quote($Block['data']['markerType'], '/');

			if ($name === 'ol')
			{
				$listStart = ltrim(strstr($matches[1], $Block['data']['markerType'], true), '0') ?: '0';

				if ($listStart !== '1')
				{
					if (
						isset($CurrentBlock)
						and $CurrentBlock['type'] === 'Paragraph'
						and ! isset($CurrentBlock['interrupted'])
					) {
						return;
					}

					$Block['element']['attributes'] = array('start' => $listStart);
				}
			}

			$Block['li'] = array(
				'name' => 'li',
				'handler' => array(
					'function' => 'li',
					'argument' => !empty($matches[3]) ? array($matches[3]) : array(),
					'destination' => 'elements'
				)
			);

			$Block['element']['elements'] []= & $Block['li'];

			return $Block;
		}
	}

	protected function blockListContinue($Line, array $Block)
	{
		if (isset($Block['interrupted']) and empty($Block['li']['handler']['argument']))
		{
			return null;
		}

		$requiredIndent = ($Block['indent'] + strlen($Block['data']['marker']));

		if ($Line['indent'] < $requiredIndent
			and (
				(
					$Block['data']['type'] === 'ol'
					and preg_match('/^[0-9]++'.$Block['data']['markerTypeRegex'].'(?:[ ]++(.*)|$)/', $Line['text'], $matches)
				) or (
					$Block['data']['type'] === 'ul'
					and preg_match('/^'.$Block['data']['markerTypeRegex'].'(?:[ ]++(.*)|$)/', $Line['text'], $matches)
				)
			)
		) {
			if (isset($Block['interrupted']))
			{
				$Block['li']['handler']['argument'] []= '';

				$Block['loose'] = true;

				unset($Block['interrupted']);
			}

			unset($Block['li']);

			$text = isset($matches[1]) ? $matches[1] : '';

			$Block['indent'] = $Line['indent'];

			$Block['li'] = array(
				'name' => 'li',
				'handler' => array(
					'function' => 'li',
					'argument' => array($text),
					'destination' => 'elements'
				)
			);

			$Block['element']['elements'] []= & $Block['li'];

			return $Block;
		}
		elseif ($Line['indent'] < $requiredIndent and $this->blockList($Line))
		{
			return null;
		}

		if ($Line['text'][0] === '[' and $this->blockReference($Line))
		{
			return $Block;
		}

		if ($Line['indent'] >= $requiredIndent)
		{
			if (isset($Block['interrupted']))
			{
				$Block['li']['handler']['argument'] []= '';

				$Block['loose'] = true;

				unset($Block['interrupted']);
			}

			$text = substr($Line['body'], $requiredIndent);

			$Block['li']['handler']['argument'] []= $text;

			return $Block;
		}

		if ( ! isset($Block['interrupted']))
		{
			$text = preg_replace('/^[ ]{0,'.$requiredIndent.'}+/', '', $Line['body']);

			$Block['li']['handler']['argument'] []= $text;

			return $Block;
		}
	}

	protected function blockListComplete(array $Block)
	{
		if (isset($Block['loose']))
		{
			foreach ($Block['element']['elements'] as &$li)
			{
				if (end($li['handler']['argument']) !== '')
				{
					$li['handler']['argument'] []= '';
				}
			}
		}

		return $Block;
	}

	#
	# Quote

	protected function blockQuote($Line)
	{
		if (preg_match('/^>[ ]?+(.*+)/', $Line['text'], $matches))
		{
			$Block = array(
				'element' => array(
					'name' => 'blockquote',
					'handler' => array(
						'function' => 'linesElements',
						'argument' => (array) $matches[1],
						'destination' => 'elements',
					)
				),
			);

			return $Block;
		}
	}

	protected function blockQuoteContinue($Line, array $Block)
	{
		if (isset($Block['interrupted']))
		{
			return;
		}

		if ($Line['text'][0] === '>' and preg_match('/^>[ ]?+(.*+)/', $Line['text'], $matches))
		{
			$Block['element']['handler']['argument'] []= $matches[1];

			return $Block;
		}

		if ( ! isset($Block['interrupted']))
		{
			$Block['element']['handler']['argument'] []= $Line['text'];

			return $Block;
		}
	}

	#
	# Rule

	protected function blockRule($Line)
	{
		$marker = $Line['text'][0];

		if (substr_count($Line['text'], $marker) >= 3 and chop($Line['text'], " $marker") === '')
		{
			$Block = array(
				'element' => array(
					'name' => 'hr',
				),
			);

			return $Block;
		}
	}

	#
	# Setext

	protected function blockSetextHeader($Line, array $Block = null)
	{
		if ( ! isset($Block) or $Block['type'] !== 'Paragraph' or isset($Block['interrupted']))
		{
			return;
		}

		if ($Line['indent'] < 4 and chop(chop($Line['text'], ' '), $Line['text'][0]) === '')
		{
			$Block['element']['name'] = $Line['text'][0] === '=' ? 'h1' : 'h2';

			return $Block;
		}
	}

	#
	# Markup

	protected function blockMarkup($Line)
	{
		if ($this->markupEscaped or $this->safeMode)
		{
			return;
		}

		if (preg_match('/^<[\/]?+(\w*)(?:[ ]*+'.$this->regexHtmlAttribute.')*+[ ]*+(\/)?>/', $Line['text'], $matches))
		{
			$element = strtolower($matches[1]);

			if (in_array($element, $this->textLevelElements))
			{
				return;
			}

			$Block = array(
				'name' => $matches[1],
				'element' => array(
					'rawHtml' => $Line['text'],
					'autobreak' => true,
				),
			);

			return $Block;
		}
	}

	protected function blockMarkupContinue($Line, array $Block)
	{
		if (isset($Block['closed']) or isset($Block['interrupted']))
		{
			return;
		}

		$Block['element']['rawHtml'] .= "\n" . $Line['body'];

		return $Block;
	}

	#
	# Reference

	protected function blockReference($Line)
	{
		if (strpos($Line['text'], ']') !== false
			and preg_match('/^\[(.+?)\]:[ ]*+<?(\S+?)>?(?:[ ]+["\'(](.+)["\')])?[ ]*+$/', $Line['text'], $matches)
		) {
			$id = strtolower($matches[1]);

			$Data = array(
				'url' => $matches[2],
				'title' => isset($matches[3]) ? $matches[3] : null,
			);

			$this->DefinitionData['Reference'][$id] = $Data;

			$Block = array(
				'element' => array(),
			);

			return $Block;
		}
	}

	#
	# Table

	protected function blockTable($Line, array $Block = null)
	{
		if ( ! isset($Block) or $Block['type'] !== 'Paragraph' or isset($Block['interrupted']))
		{
			return;
		}

		if (
			strpos($Block['element']['handler']['argument'], '|') === false
			and strpos($Line['text'], '|') === false
			and strpos($Line['text'], ':') === false
			or strpos($Block['element']['handler']['argument'], "\n") !== false
		) {
			return;
		}

		if (chop($Line['text'], ' -:|') !== '')
		{
			return;
		}

		$alignments = array();

		$divider = $Line['text'];

		$divider = trim($divider);
		$divider = trim($divider, '|');

		$dividerCells = explode('|', $divider);

		foreach ($dividerCells as $dividerCell)
		{
			$dividerCell = trim($dividerCell);

			if ($dividerCell === '')
			{
				return;
			}

			$alignment = null;

			if ($dividerCell[0] === ':')
			{
				$alignment = 'left';
			}

			if (substr($dividerCell, - 1) === ':')
			{
				$alignment = $alignment === 'left' ? 'center' : 'right';
			}

			$alignments []= $alignment;
		}

		# ~

		$HeaderElements = array();

		$header = $Block['element']['handler']['argument'];

		$header = trim($header);
		$header = trim($header, '|');

		$headerCells = explode('|', $header);

		if (count($headerCells) !== count($alignments))
		{
			return;
		}

		foreach ($headerCells as $index => $headerCell)
		{
			$headerCell = trim($headerCell);

			$HeaderElement = array(
				'name' => 'th',
				'handler' => array(
					'function' => 'lineElements',
					'argument' => $headerCell,
					'destination' => 'elements',
				)
			);

			if (isset($alignments[$index]))
			{
				$alignment = $alignments[$index];

				$HeaderElement['attributes'] = array(
					'style' => "text-align: $alignment;",
				);
			}

			$HeaderElements []= $HeaderElement;
		}

		# ~

		$Block = array(
			'alignments' => $alignments,
			'identified' => true,
			'element' => array(
				'name' => 'table',
				'elements' => array(),
			),
		);

		$Block['element']['elements'] []= array(
			'name' => 'thead',
		);

		$Block['element']['elements'] []= array(
			'name' => 'tbody',
			'elements' => array(),
		);

		$Block['element']['elements'][0]['elements'] []= array(
			'name' => 'tr',
			'elements' => $HeaderElements,
		);

		return $Block;
	}

	protected function blockTableContinue($Line, array $Block)
	{
		if (isset($Block['interrupted']))
		{
			return;
		}

		if (count($Block['alignments']) === 1 or $Line['text'][0] === '|' or strpos($Line['text'], '|'))
		{
			$Elements = array();

			$row = $Line['text'];

			$row = trim($row);
			$row = trim($row, '|');

			preg_match_all('/(?:(\\\\[|])|[^|`]|`[^`]++`|`)++/', $row, $matches);

			$cells = array_slice($matches[0], 0, count($Block['alignments']));

			foreach ($cells as $index => $cell)
			{
				$cell = trim($cell);

				$Element = array(
					'name' => 'td',
					'handler' => array(
						'function' => 'lineElements',
						'argument' => $cell,
						'destination' => 'elements',
					)
				);

				if (isset($Block['alignments'][$index]))
				{
					$Element['attributes'] = array(
						'style' => 'text-align: ' . $Block['alignments'][$index] . ';',
					);
				}

				$Elements []= $Element;
			}

			$Element = array(
				'name' => 'tr',
				'elements' => $Elements,
			);

			$Block['element']['elements'][1]['elements'] []= $Element;

			return $Block;
		}
	}

	#
	# ~
	#

	protected function paragraph($Line)
	{
		return array(
			'type' => 'Paragraph',
			'element' => array(
				'name' => 'p',
				'handler' => array(
					'function' => 'lineElements',
					'argument' => $Line['text'],
					'destination' => 'elements',
				),
			),
		);
	}

	protected function paragraphContinue($Line, array $Block)
	{
		if (isset($Block['interrupted']))
		{
			return;
		}

		$Block['element']['handler']['argument'] .= "\n".$Line['text'];

		return $Block;
	}

	#
	# Inline Elements
	#

	protected $InlineTypes = array(
		'!' => array('Image'),
		'&' => array('SpecialCharacter'),
		'*' => array('Emphasis'),
		':' => array('Url'),
		'<' => array('UrlTag', 'EmailTag', 'Markup'),
		'[' => array('Link'),
		'_' => array('Emphasis'),
		'`' => array('Code'),
		'~' => array('Strikethrough'),
		'\\' => array('EscapeSequence'),
	);

	# ~

	protected $inlineMarkerList = '!*_&[:<`~\\';

	#
	# ~
	#

	public function line($text, $nonNestables = array())
	{
		return $this->elements($this->lineElements($text, $nonNestables));
	}

	protected function lineElements($text, $nonNestables = array())
	{
		# standardize line breaks
		$text = str_replace(array("\r\n", "\r"), "\n", $text);

		$Elements = array();

		$nonNestables = (empty($nonNestables)
			? array()
			: array_combine($nonNestables, $nonNestables)
		);

		# $excerpt is based on the first occurrence of a marker

		while ($excerpt = strpbrk($text, $this->inlineMarkerList))
		{
			$marker = $excerpt[0];

			$markerPosition = strlen($text) - strlen($excerpt);

			$Excerpt = array('text' => $excerpt, 'context' => $text);

			foreach ($this->InlineTypes[$marker] as $inlineType)
			{
				# check to see if the current inline type is nestable in the current context

				if (isset($nonNestables[$inlineType]))
				{
					continue;
				}

				$Inline = $this->{"inline$inlineType"}($Excerpt);

				if ( ! isset($Inline))
				{
					continue;
				}

				# makes sure that the inline belongs to "our" marker

				if (isset($Inline['position']) and $Inline['position'] > $markerPosition)
				{
					continue;
				}

				# sets a default inline position

				if ( ! isset($Inline['position']))
				{
					$Inline['position'] = $markerPosition;
				}

				# cause the new element to 'inherit' our non nestables


				$Inline['element']['nonNestables'] = isset($Inline['element']['nonNestables'])
					? array_merge($Inline['element']['nonNestables'], $nonNestables)
					: $nonNestables
				;

				# the text that comes before the inline
				$unmarkedText = substr($text, 0, $Inline['position']);

				# compile the unmarked text
				$InlineText = $this->inlineText($unmarkedText);
				$Elements[] = $InlineText['element'];

				# compile the inline
				$Elements[] = $this->extractElement($Inline);

				# remove the examined text
				$text = substr($text, $Inline['position'] + $Inline['extent']);

				continue 2;
			}

			# the marker does not belong to an inline

			$unmarkedText = substr($text, 0, $markerPosition + 1);

			$InlineText = $this->inlineText($unmarkedText);
			$Elements[] = $InlineText['element'];

			$text = substr($text, $markerPosition + 1);
		}

		$InlineText = $this->inlineText($text);
		$Elements[] = $InlineText['element'];

		foreach ($Elements as &$Element)
		{
			if ( ! isset($Element['autobreak']))
			{
				$Element['autobreak'] = false;
			}
		}

		return $Elements;
	}

	#
	# ~
	#

	protected function inlineText($text)
	{
		$Inline = array(
			'extent' => strlen($text),
			'element' => array(),
		);

		$Inline['element']['elements'] = self::pregReplaceElements(
			$this->breaksEnabled ? '/[ ]*+\n/' : '/(?:[ ]*+\\\\|[ ]{2,}+)\n/',
			array(
				array('name' => 'br'),
				array('text' => "\n"),
			),
			$text
		);

		return $Inline;
	}

	protected function inlineCode($Excerpt)
	{
		$marker = $Excerpt['text'][0];

		if (preg_match('/^(['.$marker.']++)[ ]*+(.+?)[ ]*+(?<!['.$marker.'])\1(?!'.$marker.')/s', $Excerpt['text'], $matches))
		{
			$text = $matches[2];
			$text = preg_replace('/[ ]*+\n/', ' ', $text);

			return array(
				'extent' => strlen($matches[0]),
				'element' => array(
					'name' => 'code',
					'text' => $text,
				),
			);
		}
	}

	protected function inlineEmailTag($Excerpt)
	{
		$hostnameLabel = '[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?';

		$commonMarkEmail = '[a-zA-Z0-9.!#$%&\'*+\/=?^_`{|}~-]++@'
			. $hostnameLabel . '(?:\.' . $hostnameLabel . ')*';

		if (strpos($Excerpt['text'], '>') !== false
			and preg_match("/^<((mailto:)?$commonMarkEmail)>/i", $Excerpt['text'], $matches)
		){
			$url = $matches[1];

			if ( ! isset($matches[2]))
			{
				$url = "mailto:$url";
			}

			return array(
				'extent' => strlen($matches[0]),
				'element' => array(
					'name' => 'a',
					'text' => $matches[1],
					'attributes' => array(
						'href' => $url,
					),
				),
			);
		}
	}

	protected function inlineEmphasis($Excerpt)
	{
		if ( ! isset($Excerpt['text'][1]))
		{
			return;
		}

		$marker = $Excerpt['text'][0];

		if ($Excerpt['text'][1] === $marker and preg_match($this->StrongRegex[$marker], $Excerpt['text'], $matches))
		{
			$emphasis = 'strong';
		}
		elseif (preg_match($this->EmRegex[$marker], $Excerpt['text'], $matches))
		{
			$emphasis = 'em';
		}
		else
		{
			return;
		}

		return array(
			'extent' => strlen($matches[0]),
			'element' => array(
				'name' => $emphasis,
				'handler' => array(
					'function' => 'lineElements',
					'argument' => $matches[1],
					'destination' => 'elements',
				)
			),
		);
	}

	protected function inlineEscapeSequence($Excerpt)
	{
		if (isset($Excerpt['text'][1]) and in_array($Excerpt['text'][1], $this->specialCharacters))
		{
			return array(
				'element' => array('rawHtml' => $Excerpt['text'][1]),
				'extent' => 2,
			);
		}
	}

	protected function inlineImage($Excerpt)
	{
		if ( ! isset($Excerpt['text'][1]) or $Excerpt['text'][1] !== '[')
		{
			return;
		}

		$Excerpt['text']= substr($Excerpt['text'], 1);

		$Link = $this->inlineLink($Excerpt);

		if ($Link === null)
		{
			return;
		}

		$Inline = array(
			'extent' => $Link['extent'] + 1,
			'element' => array(
				'name' => 'img',
				'attributes' => array(
					'src' => $Link['element']['attributes']['href'],
					'alt' => $Link['element']['handler']['argument'],
				),
				'autobreak' => true,
			),
		);

		$Inline['element']['attributes'] += $Link['element']['attributes'];

		unset($Inline['element']['attributes']['href']);

		return $Inline;
	}

	protected function inlineLink($Excerpt)
	{
		$Element = array(
			'name' => 'a',
			'handler' => array(
				'function' => 'lineElements',
				'argument' => null,
				'destination' => 'elements',
			),
			'nonNestables' => array('Url', 'Link'),
			'attributes' => array(
				'href' => null,
				'title' => null,
			),
		);

		$extent = 0;

		$remainder = $Excerpt['text'];

		if (preg_match('/\[((?:[^][]++|(?R))*+)\]/', $remainder, $matches))
		{
			$Element['handler']['argument'] = $matches[1];

			$extent += strlen($matches[0]);

			$remainder = substr($remainder, $extent);
		}
		else
		{
			return;
		}

		if (preg_match('/^[(]\s*+((?:[^ ()]++|[(][^ )]+[)])++)(?:[ ]+("[^"]*+"|\'[^\']*+\'))?\s*+[)]/', $remainder, $matches))
		{
			$Element['attributes']['href'] = $matches[1];

			if (isset($matches[2]))
			{
				$Element['attributes']['title'] = substr($matches[2], 1, - 1);
			}

			$extent += strlen($matches[0]);
		}
		else
		{
			if (preg_match('/^\s*\[(.*?)\]/', $remainder, $matches))
			{
				$definition = strlen($matches[1]) ? $matches[1] : $Element['handler']['argument'];
				$definition = strtolower($definition);

				$extent += strlen($matches[0]);
			}
			else
			{
				$definition = strtolower($Element['handler']['argument']);
			}

			if ( ! isset($this->DefinitionData['Reference'][$definition]))
			{
				return;
			}

			$Definition = $this->DefinitionData['Reference'][$definition];

			$Element['attributes']['href'] = $Definition['url'];
			$Element['attributes']['title'] = $Definition['title'];
		}

		return array(
			'extent' => $extent,
			'element' => $Element,
		);
	}

	protected function inlineMarkup($Excerpt)
	{
		if ($this->markupEscaped or $this->safeMode or strpos($Excerpt['text'], '>') === false)
		{
			return;
		}

		if ($Excerpt['text'][1] === '/' and preg_match('/^<\/\w[\w-]*+[ ]*+>/s', $Excerpt['text'], $matches))
		{
			return array(
				'element' => array('rawHtml' => $matches[0]),
				'extent' => strlen($matches[0]),
			);
		}

		if ($Excerpt['text'][1] === '!' and preg_match('/^<!---?[^>-](?:-?+[^-])*-->/s', $Excerpt['text'], $matches))
		{
			return array(
				'element' => array('rawHtml' => $matches[0]),
				'extent' => strlen($matches[0]),
			);
		}

		if ($Excerpt['text'][1] !== ' ' and preg_match('/^<\w[\w-]*+(?:[ ]*+'.$this->regexHtmlAttribute.')*+[ ]*+\/?>/s', $Excerpt['text'], $matches))
		{
			return array(
				'element' => array('rawHtml' => $matches[0]),
				'extent' => strlen($matches[0]),
			);
		}
	}

	protected function inlineSpecialCharacter($Excerpt)
	{
		if (substr($Excerpt['text'], 1, 1) !== ' ' and strpos($Excerpt['text'], ';') !== false
			and preg_match('/^&(#?+[0-9a-zA-Z]++);/', $Excerpt['text'], $matches)
		) {
			return array(
				'element' => array('rawHtml' => '&' . $matches[1] . ';'),
				'extent' => strlen($matches[0]),
			);
		}

		return;
	}

	protected function inlineStrikethrough($Excerpt)
	{
		if ( ! isset($Excerpt['text'][1]))
		{
			return;
		}

		if ($Excerpt['text'][1] === '~' and preg_match('/^~~(?=\S)(.+?)(?<=\S)~~/', $Excerpt['text'], $matches))
		{
			return array(
				'extent' => strlen($matches[0]),
				'element' => array(
					'name' => 'del',
					'handler' => array(
						'function' => 'lineElements',
						'argument' => $matches[1],
						'destination' => 'elements',
					)
				),
			);
		}
	}

	protected function inlineUrl($Excerpt)
	{
		if ($this->urlsLinked !== true or ! isset($Excerpt['text'][2]) or $Excerpt['text'][2] !== '/')
		{
			return;
		}

		if (strpos($Excerpt['context'], 'http') !== false
			and preg_match('/\bhttps?+:[\/]{2}[^\s<]+\b\/*+/ui', $Excerpt['context'], $matches, PREG_OFFSET_CAPTURE)
		) {
			$url = $matches[0][0];

			$Inline = array(
				'extent' => strlen($matches[0][0]),
				'position' => $matches[0][1],
				'element' => array(
					'name' => 'a',
					'text' => $url,
					'attributes' => array(
						'href' => $url,
					),
				),
			);

			return $Inline;
		}
	}

	protected function inlineUrlTag($Excerpt)
	{
		if (strpos($Excerpt['text'], '>') !== false and preg_match('/^<(\w++:\/{2}[^ >]++)>/i', $Excerpt['text'], $matches))
		{
			$url = $matches[1];

			return array(
				'extent' => strlen($matches[0]),
				'element' => array(
					'name' => 'a',
					'text' => $url,
					'attributes' => array(
						'href' => $url,
					),
				),
			);
		}
	}

	# ~

	protected function unmarkedText($text)
	{
		$Inline = $this->inlineText($text);
		return $this->element($Inline['element']);
	}

	#
	# Handlers
	#

	protected function handle(array $Element)
	{
		if (isset($Element['handler']))
		{
			if (!isset($Element['nonNestables']))
			{
				$Element['nonNestables'] = array();
			}

			if (is_string($Element['handler']))
			{
				$function = $Element['handler'];
				$argument = $Element['text'];
				unset($Element['text']);
				$destination = 'rawHtml';
			}
			else
			{
				$function = $Element['handler']['function'];
				$argument = $Element['handler']['argument'];
				$destination = $Element['handler']['destination'];
			}

			$Element[$destination] = $this->{$function}($argument, $Element['nonNestables']);

			if ($destination === 'handler')
			{
				$Element = $this->handle($Element);
			}

			unset($Element['handler']);
		}

		return $Element;
	}

	protected function handleElementRecursive(array $Element)
	{
		return $this->elementApplyRecursive(array($this, 'handle'), $Element);
	}

	protected function handleElementsRecursive(array $Elements)
	{
		return $this->elementsApplyRecursive(array($this, 'handle'), $Elements);
	}

	protected function elementApplyRecursive($closure, array $Element)
	{
		$Element = call_user_func($closure, $Element);

		if (isset($Element['elements']))
		{
			$Element['elements'] = $this->elementsApplyRecursive($closure, $Element['elements']);
		}
		elseif (isset($Element['element']))
		{
			$Element['element'] = $this->elementApplyRecursive($closure, $Element['element']);
		}

		return $Element;
	}

	protected function elementApplyRecursiveDepthFirst($closure, array $Element)
	{
		if (isset($Element['elements']))
		{
			$Element['elements'] = $this->elementsApplyRecursiveDepthFirst($closure, $Element['elements']);
		}
		elseif (isset($Element['element']))
		{
			$Element['element'] = $this->elementsApplyRecursiveDepthFirst($closure, $Element['element']);
		}

		$Element = call_user_func($closure, $Element);

		return $Element;
	}

	protected function elementsApplyRecursive($closure, array $Elements)
	{
		foreach ($Elements as &$Element)
		{
			$Element = $this->elementApplyRecursive($closure, $Element);
		}

		return $Elements;
	}

	protected function elementsApplyRecursiveDepthFirst($closure, array $Elements)
	{
		foreach ($Elements as &$Element)
		{
			$Element = $this->elementApplyRecursiveDepthFirst($closure, $Element);
		}

		return $Elements;
	}

	protected function element(array $Element)
	{
		if ($this->safeMode)
		{
			$Element = $this->sanitiseElement($Element);
		}

		# identity map if element has no handler
		$Element = $this->handle($Element);

		$hasName = isset($Element['name']);

		$markup = '';

		if ($hasName)
		{
			$markup .= '<' . $Element['name'];

			if (isset($Element['attributes']))
			{
				foreach ($Element['attributes'] as $name => $value)
				{
					if ($value === null)
					{
						continue;
					}

					$markup .= " $name=\"".self::escape($value).'"';
				}
			}
		}

		$permitRawHtml = false;

		if (isset($Element['text']))
		{
			$text = $Element['text'];
		}
		// very strongly consider an alternative if you're writing an
		// extension
		elseif (isset($Element['rawHtml']))
		{
			$text = $Element['rawHtml'];

			$allowRawHtmlInSafeMode = isset($Element['allowRawHtmlInSafeMode']) && $Element['allowRawHtmlInSafeMode'];
			$permitRawHtml = !$this->safeMode || $allowRawHtmlInSafeMode;
		}

		$hasContent = isset($text) || isset($Element['element']) || isset($Element['elements']);

		if ($hasContent)
		{
			$markup .= $hasName ? '>' : '';

			if (isset($Element['elements']))
			{
				$markup .= $this->elements($Element['elements']);
			}
			elseif (isset($Element['element']))
			{
				$markup .= $this->element($Element['element']);
			}
			else
			{
				if (!$permitRawHtml)
				{
					$markup .= self::escape($text, true);
				}
				else
				{
					$markup .= $text;
				}
			}

			$markup .= $hasName ? '</' . $Element['name'] . '>' : '';
		}
		elseif ($hasName)
		{
			$markup .= ' />';
		}

		return $markup;
	}

	protected function elements(array $Elements)
	{
		$markup = '';

		$autoBreak = true;

		foreach ($Elements as $Element)
		{
			if (empty($Element))
			{
				continue;
			}

			$autoBreakNext = (isset($Element['autobreak'])
				? $Element['autobreak'] : isset($Element['name'])
			);
			// (autobreak === false) covers both sides of an element
			$autoBreak = !$autoBreak ? $autoBreak : $autoBreakNext;

			$markup .= ($autoBreak ? "\n" : '') . $this->element($Element);
			$autoBreak = $autoBreakNext;
		}

		$markup .= $autoBreak ? "\n" : '';

		return $markup;
	}

	# ~

	protected function li($lines)
	{
		$Elements = $this->linesElements($lines);

		if ( ! in_array('', $lines)
			and isset($Elements[0]) and isset($Elements[0]['name'])
			and $Elements[0]['name'] === 'p'
		) {
			unset($Elements[0]['name']);
		}

		return $Elements;
	}

	#
	# AST Convenience
	#

	/**
	 * Replace occurrences $regexp with $Elements in $text. Return an array of
	 * elements representing the replacement.
	 */
	protected static function pregReplaceElements($regexp, $Elements, $text)
	{
		$newElements = array();

		while (preg_match($regexp, $text, $matches, PREG_OFFSET_CAPTURE))
		{
			$offset = $matches[0][1];
			$before = substr($text, 0, $offset);
			$after = substr($text, $offset + strlen($matches[0][0]));

			$newElements[] = array('text' => $before);

			foreach ($Elements as $Element)
			{
				$newElements[] = $Element;
			}

			$text = $after;
		}

		$newElements[] = array('text' => $text);

		return $newElements;
	}

	#
	# Deprecated Methods
	#

	function parse($text)
	{
		$markup = $this->text($text);

		return $markup;
	}

	protected function sanitiseElement(array $Element)
	{
		static $goodAttribute = '/^[a-zA-Z0-9][a-zA-Z0-9-_]*+$/';
		static $safeUrlNameToAtt  = array(
			'a'   => 'href',
			'img' => 'src',
		);

		if ( ! isset($Element['name']))
		{
			unset($Element['attributes']);
			return $Element;
		}

		if (isset($safeUrlNameToAtt[$Element['name']]))
		{
			$Element = $this->filterUnsafeUrlInAttribute($Element, $safeUrlNameToAtt[$Element['name']]);
		}

		if ( ! empty($Element['attributes']))
		{
			foreach ($Element['attributes'] as $att => $val)
			{
				# filter out badly parsed attribute
				if ( ! preg_match($goodAttribute, $att))
				{
					unset($Element['attributes'][$att]);
				}
				# dump onevent attribute
				elseif (self::striAtStart($att, 'on'))
				{
					unset($Element['attributes'][$att]);
				}
			}
		}

		return $Element;
	}

	protected function filterUnsafeUrlInAttribute(array $Element, $attribute)
	{
		foreach ($this->safeLinksWhitelist as $scheme)
		{
			if (self::striAtStart($Element['attributes'][$attribute], $scheme))
			{
				return $Element;
			}
		}

		$Element['attributes'][$attribute] = str_replace(':', '%3A', $Element['attributes'][$attribute]);

		return $Element;
	}

	#
	# Static Methods
	#

	protected static function escape($text, $allowQuotes = false)
	{
		return htmlspecialchars($text, $allowQuotes ? ENT_NOQUOTES : ENT_QUOTES, 'UTF-8');
	}

	protected static function striAtStart($string, $needle)
	{
		$len = strlen($needle);

		if ($len > strlen($string))
		{
			return false;
		}
		else
		{
			return strtolower(substr($string, 0, $len)) === strtolower($needle);
		}
	}

	static function instance($name = 'default')
	{
		if (isset(self::$instances[$name]))
		{
			return self::$instances[$name];
		}

		$instance = new static();

		self::$instances[$name] = $instance;

		return $instance;
	}

	private static $instances = array();

	#
	# Fields
	#

	protected $DefinitionData;

	#
	# Read-Only

	protected $specialCharacters = array(
		'\\', '`', '*', '_', '{', '}', '[', ']', '(', ')', '>', '#', '+', '-', '.', '!', '|', '~'
	);

	protected $StrongRegex = array(
		'*' => '/^[*]{2}((?:\\\\\*|[^*]|[*][^*]*+[*])+?)[*]{2}(?![*])/s',
		'_' => '/^__((?:\\\\_|[^_]|_[^_]*+_)+?)__(?!_)/us',
	);

	protected $EmRegex = array(
		'*' => '/^[*]((?:\\\\\*|[^*]|[*][*][^*]+?[*][*])+?)[*](?![*])/s',
		'_' => '/^_((?:\\\\_|[^_]|__[^_]*__)+?)_(?!_)\b/us',
	);

	protected $regexHtmlAttribute = '[a-zA-Z_:][\w:.-]*+(?:\s*+=\s*+(?:[^"\'=<>`\s]+|"[^"]*+"|\'[^\']*+\'))?+';

	protected $voidElements = array(
		'area', 'base', 'br', 'col', 'command', 'embed', 'hr', 'img', 'input', 'link', 'meta', 'param', 'source',
	);

	protected $textLevelElements = array(
		'a', 'br', 'bdo', 'abbr', 'blink', 'nextid', 'acronym', 'basefont',
		'b', 'em', 'big', 'cite', 'small', 'spacer', 'listing',
		'i', 'rp', 'del', 'code',          'strike', 'marquee',
		'q', 'rt', 'ins', 'font',          'strong',
		's', 'tt', 'kbd', 'mark',
		'u', 'xm', 'sub', 'nobr',
		'sup', 'ruby',
		'var', 'span',
		'wbr', 'time',
	);
}






#
#
# Parsedown Extra
# https://github.com/erusev/parsedown-extra
#
# (c) Emanuil Rusev
# http://erusev.com
#
# For the full license information, view the LICENSE file that was distributed
# with this source code.
#
#

class ParsedownExtra extends Parsedown
{
	# ~

	const version = '0.8.0';

	# ~

	function __construct()
	{
		if (version_compare(parent::version, '1.7.1') < 0)
		{
			throw new Exception('ParsedownExtra requires a later version of Parsedown');
		}

		$this->BlockTypes[':'] []= 'DefinitionList';
		$this->BlockTypes['*'] []= 'Abbreviation';

		# identify footnote definitions before reference definitions
		array_unshift($this->BlockTypes['['], 'Footnote');

		# identify footnote markers before before links
		array_unshift($this->InlineTypes['['], 'FootnoteMarker');
	}

	#
	# ~

	function text($text)
	{
		$Elements = $this->textElements($text);

		# convert to markup
		$markup = $this->elements($Elements);

		# trim line breaks
		$markup = trim($markup, "\n");

		# merge consecutive dl elements

		$markup = preg_replace('/<\/dl>\s+<dl>\s+/', '', $markup);

		# add footnotes

		if (isset($this->DefinitionData['Footnote']))
		{
			$Element = $this->buildFootnoteElement();

			$markup .= "\n" . $this->element($Element);
		}

		return $markup;
	}

	#
	# Blocks
	#

	#
	# Abbreviation

	protected function blockAbbreviation($Line)
	{
		if (preg_match('/^\*\[(.+?)\]:[ ]*(.+?)[ ]*$/', $Line['text'], $matches))
		{
			$this->DefinitionData['Abbreviation'][$matches[1]] = $matches[2];

			$Block = array(
				'hidden' => true,
			);

			return $Block;
		}
	}

	#
	# Footnote

	protected function blockFootnote($Line)
	{
		if (preg_match('/^\[\^(.+?)\]:[ ]?(.*)$/', $Line['text'], $matches))
		{
			$Block = array(
				'label' => $matches[1],
				'text' => $matches[2],
				'hidden' => true,
			);

			return $Block;
		}
	}

	protected function blockFootnoteContinue($Line, $Block)
	{
		if ($Line['text'][0] === '[' and preg_match('/^\[\^(.+?)\]:/', $Line['text']))
		{
			return;
		}

		if (isset($Block['interrupted']))
		{
			if ($Line['indent'] >= 4)
			{
				$Block['text'] .= "\n\n" . $Line['text'];

				return $Block;
			}
		}
		else
		{
			$Block['text'] .= "\n" . $Line['text'];

			return $Block;
		}
	}

	protected function blockFootnoteComplete($Block)
	{
		$this->DefinitionData['Footnote'][$Block['label']] = array(
			'text' => $Block['text'],
			'count' => null,
			'number' => null,
		);

		return $Block;
	}

	#
	# Definition List

	protected function blockDefinitionList($Line, $Block)
	{
		if ( ! isset($Block) or $Block['type'] !== 'Paragraph')
		{
			return;
		}

		$Element = array(
			'name' => 'dl',
			'elements' => array(),
		);

		$terms = explode("\n", $Block['element']['handler']['argument']);

		foreach ($terms as $term)
		{
			$Element['elements'] []= array(
				'name' => 'dt',
				'handler' => array(
					'function' => 'lineElements',
					'argument' => $term,
					'destination' => 'elements'
				),
			);
		}

		$Block['element'] = $Element;

		$Block = $this->addDdElement($Line, $Block);

		return $Block;
	}

	protected function blockDefinitionListContinue($Line, array $Block)
	{
		if ($Line['text'][0] === ':')
		{
			$Block = $this->addDdElement($Line, $Block);

			return $Block;
		}
		else
		{
			if (isset($Block['interrupted']) and $Line['indent'] === 0)
			{
				return;
			}

			if (isset($Block['interrupted']))
			{
				$Block['dd']['handler']['function'] = 'textElements';
				$Block['dd']['handler']['argument'] .= "\n\n";

				$Block['dd']['handler']['destination'] = 'elements';

				unset($Block['interrupted']);
			}

			$text = substr($Line['body'], min($Line['indent'], 4));

			$Block['dd']['handler']['argument'] .= "\n" . $text;

			return $Block;
		}
	}

	#
	# Header

	protected function blockHeader($Line)
	{
		$Block = parent::blockHeader($Line);

		if ($Block !== null && preg_match('/[ #]*{('.$this->regexAttribute.'+)}[ ]*$/', $Block['element']['handler']['argument'], $matches, PREG_OFFSET_CAPTURE))
		{
			$attributeString = $matches[1][0];

			$Block['element']['attributes'] = $this->parseAttributeData($attributeString);

			$Block['element']['handler']['argument'] = substr($Block['element']['handler']['argument'], 0, $matches[0][1]);
		}

		return $Block;
	}

	#
	# Markup

	protected function blockMarkup($Line)
	{
		if ($this->markupEscaped or $this->safeMode)
		{
			return;
		}

		if (preg_match('/^<(\w[\w-]*)(?:[ ]*'.$this->regexHtmlAttribute.')*[ ]*(\/)?>/', $Line['text'], $matches))
		{
			$element = strtolower($matches[1]);

			if (in_array($element, $this->textLevelElements))
			{
				return;
			}

			$Block = array(
				'name' => $matches[1],
				'depth' => 0,
				'element' => array(
					'rawHtml' => $Line['text'],
					'autobreak' => true,
				),
			);

			$length = strlen($matches[0]);
			$remainder = substr($Line['text'], $length);

			if (trim($remainder) === '')
			{
				if (isset($matches[2]) or in_array($matches[1], $this->voidElements))
				{
					$Block['closed'] = true;
					$Block['void'] = true;
				}
			}
			else
			{
				if (isset($matches[2]) or in_array($matches[1], $this->voidElements))
				{
					return;
				}
				if (preg_match('/<\/'.$matches[1].'>[ ]*$/i', $remainder))
				{
					$Block['closed'] = true;
				}
			}

			return $Block;
		}
	}

	protected function blockMarkupContinue($Line, array $Block)
	{
		if (isset($Block['closed']))
		{
			return;
		}

		if (preg_match('/^<'.$Block['name'].'(?:[ ]*'.$this->regexHtmlAttribute.')*[ ]*>/i', $Line['text'])) # open
		{
			$Block['depth'] ++;
		}

		if (preg_match('/(.*?)<\/'.$Block['name'].'>[ ]*$/i', $Line['text'], $matches)) # close
		{
			if ($Block['depth'] > 0)
			{
				$Block['depth'] --;
			}
			else
			{
				$Block['closed'] = true;
			}
		}

		if (isset($Block['interrupted']))
		{
			$Block['element']['rawHtml'] .= "\n";
			unset($Block['interrupted']);
		}

		$Block['element']['rawHtml'] .= "\n".$Line['body'];

		return $Block;
	}

	protected function blockMarkupComplete($Block)
	{
		if ( ! isset($Block['void']))
		{
			$Block['element']['rawHtml'] = $this->processTag($Block['element']['rawHtml']);
		}

		return $Block;
	}

	#
	# Setext

	protected function blockSetextHeader($Line, array $Block = null)
	{
		$Block = parent::blockSetextHeader($Line, $Block);

		if ($Block !== null && preg_match('/[ ]*{('.$this->regexAttribute.'+)}[ ]*$/', $Block['element']['handler']['argument'], $matches, PREG_OFFSET_CAPTURE))
		{
			$attributeString = $matches[1][0];

			$Block['element']['attributes'] = $this->parseAttributeData($attributeString);

			$Block['element']['handler']['argument'] = substr($Block['element']['handler']['argument'], 0, $matches[0][1]);
		}

		return $Block;
	}

	#
	# Inline Elements
	#

	#
	# Footnote Marker

	protected function inlineFootnoteMarker($Excerpt)
	{
		if (preg_match('/^\[\^(.+?)\]/', $Excerpt['text'], $matches))
		{
			$name = $matches[1];

			if ( ! isset($this->DefinitionData['Footnote'][$name]))
			{
				return;
			}

			$this->DefinitionData['Footnote'][$name]['count'] ++;

			if ( ! isset($this->DefinitionData['Footnote'][$name]['number']))
			{
				$this->DefinitionData['Footnote'][$name]['number'] = ++ $this->footnoteCount; # » &
			}

			$Element = array(
				'name' => 'sup',
				'attributes' => array('id' => 'fnref'.$this->DefinitionData['Footnote'][$name]['count'].':'.$name),
				'element' => array(
					'name' => 'a',
					'attributes' => array('href' => '#fn:'.$name, 'class' => 'footnote-ref'),
					'text' => $this->DefinitionData['Footnote'][$name]['number'],
				),
			);

			return array(
				'extent' => strlen($matches[0]),
				'element' => $Element,
			);
		}
	}

	private $footnoteCount = 0;

	#
	# Link

	protected function inlineLink($Excerpt)
	{
		$Link = parent::inlineLink($Excerpt);

		$remainder = $Link !== null ? substr($Excerpt['text'], $Link['extent']) : '';

		if (preg_match('/^[ ]*{('.$this->regexAttribute.'+)}/', $remainder, $matches))
		{
			$Link['element']['attributes'] += $this->parseAttributeData($matches[1]);

			$Link['extent'] += strlen($matches[0]);
		}

		return $Link;
	}

	#
	# ~
	#

	private $currentAbreviation;
	private $currentMeaning;

	protected function insertAbreviation(array $Element)
	{
		if (isset($Element['text']))
		{
			$Element['elements'] = self::pregReplaceElements(
				'/\b'.preg_quote($this->currentAbreviation, '/').'\b/',
				array(
					array(
						'name' => 'abbr',
						'attributes' => array(
							'title' => $this->currentMeaning,
						),
						'text' => $this->currentAbreviation,
					)
				),
				$Element['text']
			);

			unset($Element['text']);
		}

		return $Element;
	}

	protected function inlineText($text)
	{
		$Inline = parent::inlineText($text);

		if (isset($this->DefinitionData['Abbreviation']))
		{
			foreach ($this->DefinitionData['Abbreviation'] as $abbreviation => $meaning)
			{
				$this->currentAbreviation = $abbreviation;
				$this->currentMeaning = $meaning;

				$Inline['element'] = $this->elementApplyRecursiveDepthFirst(
					array($this, 'insertAbreviation'),
					$Inline['element']
				);
			}
		}

		return $Inline;
	}

	#
	# Util Methods
	#

	protected function addDdElement(array $Line, array $Block)
	{
		$text = substr($Line['text'], 1);
		$text = trim($text);

		unset($Block['dd']);

		$Block['dd'] = array(
			'name' => 'dd',
			'handler' => array(
				'function' => 'lineElements',
				'argument' => $text,
				'destination' => 'elements'
			),
		);

		if (isset($Block['interrupted']))
		{
			$Block['dd']['handler']['function'] = 'textElements';

			unset($Block['interrupted']);
		}

		$Block['element']['elements'] []= & $Block['dd'];

		return $Block;
	}

	protected function buildFootnoteElement()
	{
		$Element = array(
			'name' => 'div',
			'attributes' => array('class' => 'footnotes'),
			'elements' => array(
				array('name' => 'hr'),
				array(
					'name' => 'ol',
					'elements' => array(),
				),
			),
		);

		//uasort($this->DefinitionData['Footnote'], 'self::sortFootnotes'); // Commented out by JohnBerea, because with our change below this puts footnotes in a random order.

		foreach ($this->DefinitionData['Footnote'] as $definitionId => $DefinitionData)
		{
			if ( ! isset($DefinitionData['number']))
			{
			//	continue; // Commented out by JohnBera so that we leave footnotes that are never cited.
				// This is necessary because sometimes we only cite a child of a footnote.
				// However this causes footnotes to be put in a random order, which we fix in the reOrderFootnotes() pass.
			}

			$text = $DefinitionData['text'];

			$textElements = parent::textElements($text);

			$numbers = range(1, $DefinitionData['count']);

			$backLinkElements = array();

			foreach ($numbers as $number)
			{
				$backLinkElements[] = array('text' => ' ');
				$backLinkElements[] = array(
					'name' => 'a',
					'attributes' => array(
						'href' => "#fnref$number:$definitionId",
						'rev' => 'footnote',
						'class' => 'footnote-backref',
					),
					'rawHtml' => '&#8617;',
					'allowRawHtmlInSafeMode' => true,
					'autobreak' => false,
				);
			}

			unset($backLinkElements[0]);

			$n = count($textElements) -1;

			if ($textElements[$n]['name'] === 'p')
			{
				$backLinkElements = array_merge(
					array(
						array(
							// 'rawHtml' => '&#160;',
							'rawHtml' => ' ', // modified by John Berea to use literal char 160..
							'allowRawHtmlInSafeMode' => true,
						),
					),
					$backLinkElements
				);

				unset($textElements[$n]['name']);

				$textElements[$n] = array(
					'name' => 'p',
					'elements' => array_merge(
						array($textElements[$n]),
						$backLinkElements
					),
				);
			}
			else
			{
				$textElements[] = array(
					'name' => 'p',
					'elements' => $backLinkElements
				);
			}

			$Element['elements'][1]['elements'] []= array(
				'name' => 'li',
				'attributes' => array('id' => 'fn:'.$definitionId),
				'elements' => array_merge(
					$textElements
				),
			);
		}

		return $Element;
	}

	# ~

	protected function parseAttributeData($attributeString)
	{
		$Data = array();

		$attributes = preg_split('/[ ]+/', $attributeString, - 1, PREG_SPLIT_NO_EMPTY);

		foreach ($attributes as $attribute)
		{
			if ($attribute[0] === '#')
			{
				$Data['id'] = substr($attribute, 1);
			}
			else # "."
			{
				$classes []= substr($attribute, 1);
			}
		}

		if (isset($classes))
		{
			$Data['class'] = implode(' ', $classes);
		}

		return $Data;
	}

	# ~

	protected function processTag($elementMarkup) # recursive
	{
		# http://stackoverflow.com/q/1148928/200145
		libxml_use_internal_errors(true);

		$DOMDocument = new DOMDocument;

		# http://stackoverflow.com/q/11309194/200145
		$elementMarkup = mb_convert_encoding($elementMarkup, 'HTML-ENTITIES', 'UTF-8');

		# http://stackoverflow.com/q/4879946/200145
		$DOMDocument->loadHTML($elementMarkup);
		$DOMDocument->removeChild($DOMDocument->doctype);
		$DOMDocument->replaceChild($DOMDocument->firstChild->firstChild->firstChild, $DOMDocument->firstChild);

		$elementText = '';

		if ($DOMDocument->documentElement->getAttribute('markdown') === '1')
		{
			foreach ($DOMDocument->documentElement->childNodes as $Node)
			{
				$elementText .= $DOMDocument->saveHTML($Node);
			}

			$DOMDocument->documentElement->removeAttribute('markdown');

			$elementText = "\n".$this->text($elementText)."\n";
		}
		else
		{
			foreach ($DOMDocument->documentElement->childNodes as $Node)
			{
				$nodeMarkup = $DOMDocument->saveHTML($Node);

				if ($Node instanceof DOMElement and ! in_array($Node->nodeName, $this->textLevelElements))
				{
					$elementText .= $this->processTag($nodeMarkup);
				}
				else
				{
					$elementText .= $nodeMarkup;
				}
			}
		}

		# because we don't want for markup to get encoded
		$DOMDocument->documentElement->nodeValue = 'placeholder\x1A';

		$markup = $DOMDocument->saveHTML($DOMDocument->documentElement);
		$markup = str_replace('placeholder\x1A', $elementText, $markup);

		return $markup;
	}

	# ~

	protected function sortFootnotes($A, $B) # callback
	{
		return $A['number'] - $B['number'];
	}

	#
	# Fields
	#

	protected $regexAttribute = '(?:[#.][-\w]+[ ]*)';
}


























/**
 * ParsedownExtreme.
 * https://github.com/BenjaminHoegh/parsedown-extreme */
class ParsedownExtreme extends ParsedownExtra
{
	const VERSION = '0.1.3-Alpha';

	public function __construct()
	{
		parent::__construct();


		if (version_compare(parent::version, '0.8.0-beta-1') < 0) {
			throw new Exception('ParsedownExtreme requires a later version of Parsedown');
		}

		$this->BlockTypes['$'][] = 'Katex';
		$this->BlockTypes['%'][] = 'Mermaid';

		$this->InlineTypes['='][] = 'MarkText';
		$this->inlineMarkerList .= '=';

		$this->InlineTypes['+'][] = 'InsertText';
		$this->inlineMarkerList .= '+';

		$this->InlineTypes['^'][] = 'SuperText';
		$this->inlineMarkerList .= '^';

		$this->InlineTypes['~'][] = 'SubText';
		$this->inlineMarkerList .= '~';

		$this->InlineTypes['['][] = 'Embeding';
		$this->inlineMarkerList .= '[';
	}


	#
	# Setters
	#
	protected $katexMode = false;

	public function katex(bool $mode = true)
	{
		$this->katexMode = $mode;

		return $this;
	}

	protected $mermaidMode = false;

	public function mermaid(bool $mode = true)
	{
		$this->mermaidMode = $mode;

		return $this;
	}

	protected $typographyMode = false;

	public function typography(bool $mode = true)
	{
		$this->typographyMode = $mode;

		return $this;
	}

	protected $superMode = false;


	public function superscript(bool $mode = true)
	{
		$this->superMode = $mode;

		return $this;
	}

	protected $markMode = true;

	public function mark(bool $mode = true)
	{
		$this->markMode = $mode;

		return $this;
	}

	protected $insertMode = true;

	public function insert(bool $insertMode = true)
	{
		$this->insertMode = $insertMode;

		return $this;
	}

	protected $embedingMode = true;

	public function embeding(bool $embedingMode = true)
	{
		$this->embedingMode = $embedingMode;

		return $this;
	}

	#
	# Header

	protected function blockHeader($Line)
	{
		$Block = parent::blockHeader($Line);

		if (preg_match('/[ #]*{('.$this->regexAttribute.'+)}[ ]*$/', $Block['element']['handler']['argument'], $matches, PREG_OFFSET_CAPTURE)) {
			$attributeString = $matches[1][0];

			$Block['element']['attributes'] = $this->parseAttributeData($attributeString);

			$Block['element']['handler']['argument'] = substr($Block['element']['handler']['argument'], 0, $matches[0][1]);
		}

		if (!isset($Block['element']['attributes']['id']) && isset($Block['element']['handler']['argument'])) {
			$Block['element']['attributes']['id'] = preg_replace('/\s+/', '-', $this->hyphenize($Block['element']['handler']['argument']));
		}

		$link = "#".$Block['element']['attributes']['id'];

		$Block['element']['handler']['argument'] = $Block['element']['handler']['argument']."<a class='heading-link' href='{$link}'><i class='fal fa-link'></i></a>";

		return $Block;
	}


	#
	# Setext

	protected function blockSetextHeader($Line, array $Block = null)
	{
		$Block = parent::blockSetextHeader($Line, $Block);

		if (preg_match('/[ ]*{('.$this->regexAttribute.'+)}[ ]*$/', $Block['element']['handler']['argument'], $matches, PREG_OFFSET_CAPTURE)) {
			$attributeString = $matches[1][0];

			$Block['element']['attributes'] = $this->parseAttributeData($attributeString);

			$Block['element']['handler']['argument'] = substr($Block['element']['handler']['argument'], 0, $matches[0][1]);
		}

		if (!isset($Block['element']['attributes']['id']) && isset($Block['element']['handler']['argument'])) {
			$Block['element']['attributes']['id'] = preg_replace('/\s+/', '-', $this->hyphenize($Block['element']['handler']['argument']));
		}

		return $Block;
	}


	private function hyphenize($string)
	{
		$dict = array(
			"I'm"      => "I am",
			"thier"    => "their",
			// Add your own replacements here
		);
		return strtolower(
			preg_replace(
				array( '#[\\s-]+#', '#[^A-Za-z0-9\. -]+#' ),
				array( '-', '' ),
				// the full cleanString() can be downloaded from http://www.unexpectedit.com/php/php-clean-string-of-utf8-chars-convert-to-similar-ascii-char
				$this->cleanString(
					str_replace( // preg_replace can be used to support more complicated replacements
						array_keys($dict),
						array_values($dict),
						urldecode($string)
					)
				)
			)
		);
	}

	private function cleanString($text)
	{
		$utf8 = array(
			'/[Ã¡Ã Ã¢Ã£ÂªÃ¤]/u'   =>   'a',
			'/[ÃÃ€Ã‚ÃƒÃ„]/u'    =>   'A',
			'/[ÃÃŒÃŽÃ]/u'     =>   'I',
			'/[Ã­Ã¬Ã®Ã¯]/u'     =>   'i',
			'/[Ã©Ã¨ÃªÃ«]/u'     =>   'e',
			'/[Ã‰ÃˆÃŠÃ‹]/u'     =>   'E',
			'/[Ã³Ã²Ã´ÃµÂºÃ¶]/u'   =>   'o',
			'/[Ã“Ã’Ã”Ã•Ã–]/u'    =>   'O',
			'/[ÃºÃ¹Ã»Ã¼]/u'     =>   'u',
			'/[ÃšÃ™Ã›Ãœ]/u'     =>   'U',
			'/Ã§/'           =>   'c',
			'/Ã‡/'           =>   'C',
			'/Ã±/'           =>   'n',
			'/Ã‘/'           =>   'N',
			'/â€“/'           =>   '-', // UTF-8 hyphen to "normal" hyphen
			'/[â€™â€˜â€¹â€ºâ€š]/u'    =>   ' ', // Literally a single quote
			'/[â€œâ€Â«Â»â€ž]/u'    =>   ' ', // Double quote
			'/ /'           =>   ' ', // nonbreaking space (equiv. to 0x160)
		);
		return preg_replace(array_keys($utf8), array_values($utf8), $text);
	}


	#
	# Typography Replacer
	# --------------------------------------------------------------------------

	protected function linesElements(array $lines)
	{
		$Elements = array();
		$CurrentBlock = null;

		foreach ($lines as $line) {
			if (chop($line) === '') {
				if (isset($CurrentBlock)) {
					$CurrentBlock['interrupted'] = (
					isset($CurrentBlock['interrupted'])
						? $CurrentBlock['interrupted'] + 1 : 1
					);
				}

				continue;
			}

			while (($beforeTab = strstr($line, "\t", true)) !== false) {
				$shortage = 4 - mb_strlen($beforeTab, 'utf-8') % 4;

				$line = $beforeTab.str_repeat(' ', $shortage).substr($line, strlen($beforeTab) + 1);
			}

			$indent = strspn($line, ' ');

			$text = $indent > 0 ? substr($line, $indent) : $line;

			# ~

			$Line = array('body' => $line, 'indent' => $indent, 'text' => $text);

			# ~

			if (isset($CurrentBlock['continuable'])) {
				$methodName = 'block' . $CurrentBlock['type'] . 'Continue';
				$Block = $this->$methodName($Line, $CurrentBlock);

				if (isset($Block)) {
					$CurrentBlock = $Block;

					continue;
				} else {
					if ($this->isBlockCompletable($CurrentBlock['type'])) {
						$methodName = 'block' . $CurrentBlock['type'] . 'Complete';
						$CurrentBlock = $this->$methodName($CurrentBlock);
					}
				}
			}

			# ~

			$marker = $text[0];

			# ~

			$blockTypes = $this->unmarkedBlockTypes;

			if (isset($this->BlockTypes[$marker])) {
				foreach ($this->BlockTypes[$marker] as $blockType) {
					$blockTypes []= $blockType;
				}
			}

			#
			# ~

			foreach ($blockTypes as $blockType) {
				$Block = $this->{"block$blockType"}($Line, $CurrentBlock);

				if (isset($Block)) {
					$Block['type'] = $blockType;

					if (! isset($Block['identified'])) {
						if (isset($CurrentBlock)) {
							$Elements[] = $this->extractElement($CurrentBlock);
						}

						$Block['identified'] = true;
					}

					if ($this->isBlockContinuable($blockType)) {
						$Block['continuable'] = true;
					}

					$CurrentBlock = $Block;

					continue 2;
				}
			}

			# ~
			if (isset($CurrentBlock) and $CurrentBlock['type'] === 'Paragraph') {
				$Block = $this->paragraphContinue($Line, $CurrentBlock);
			}

			if (isset($Block)) {
				$CurrentBlock = $Block;
			} else {
				if (isset($CurrentBlock)) {
					$Elements[] = $this->extractElement($CurrentBlock);
				}

				if ($this->typographyMode) {
					$typographicReplace = array(
						'(c)' => '&copy;',
						'(C)' => '&copy;',
						'(r)' => '&reg;',
						'(R)' => '&reg;',
						'(tm)' => '&trade;',
						'(TM)' => '&trade;'
					);
					$Line = $this->strReplaceAssoc($typographicReplace, $Line);
				}

				$CurrentBlock = $this->paragraph($Line);

				$CurrentBlock['identified'] = true;
			}
		}

		# ~

		if (isset($CurrentBlock['continuable']) and $this->isBlockCompletable($CurrentBlock['type'])) {
			$methodName = 'block' . $CurrentBlock['type'] . 'Complete';
			$CurrentBlock = $this->$methodName($CurrentBlock);
		}

		# ~

		if (isset($CurrentBlock)) {
			$Elements[] = $this->extractElement($CurrentBlock);
		}

		# ~

		return $Elements;
	}

	#
	# Mark
	# --------------------------------------------------------------------------

	protected function inlineMarkText($excerpt)
	{
		if (!$this->markMode) {
			return;
		}

		if (preg_match('/^(==)([\s\S]*?)(==)/', $excerpt['text'], $matches)) {
			return array(
				// How many characters to advance the Parsedown's
				// cursor after being done processing this tag.
				'extent' => strlen($matches[0]),
				'element' => array(
					'name' => 'mark',
					'text' => $matches[2]
				),
			);
		}
	}


	//
	// Embeding
	protected function inlineEmbeding($excerpt)
	{
		if (!$this->embedingMode) {
			return;
		}

		if (preg_match('/\[video.*src="([^"]*)".*\]/', $excerpt['text'], $matches)) {
			$url = $matches[1];
			$type = '';

			$needles = array('youtube','vimeo','dailymotion');
			foreach ($needles as $needle) {
				if (strpos($url, $needle) !== false) {
					$type = $needle;
				}
			}

			switch ($type) {
				case 'youtube':
					$element = 'iframe';
					$attributes = array(
						'src' => preg_replace('/.*\?v=([^\&\]]*).*/', 'https://www.youtube.com/embed/$1', $url),
						'frameborder' => '0',
						'allow' => 'autoplay',
						'allowfullscreen' => '',
						'sandbox' => 'allow-same-origin allow-scripts allow-forms'
					);
					$regxr = '';
					break;
				case 'vimeo':
					$element = 'iframe';
					$attributes = array(
						'src' => preg_replace('/(?:https?:\/\/(?:[\w]{3}\.|player\.)*vimeo\.com(?:[\/\w:]*(?:\/videos)?)?\/([0-9]+)[^\s]*)/', 'https://player.vimeo.com/video/$1', $url),
						'frameborder' => '0',
						'allow' => 'autoplay',
						'allowfullscreen' => '',
						'sandbox' => 'allow-same-origin allow-scripts allow-forms'
					);
					$regxr = '';
					break;
				case 'dailymotion':
					$element = 'iframe';
					$attributes = array(
						'src' => $url,
						'frameborder' => '0',
						'allow' => 'autoplay',
						'allowfullscreen' => '',
						'sandbox' => 'allow-same-origin allow-scripts allow-forms'
					);
					$regxr = '';
					break;
				default:
					$element = 'video';
			}

			return array(
				'extent' => strlen($matches[0]),
				'element' => array(
					'name' => $element,
					'text' => $matches[1],
					'attributes' => $attributes
				),
			);
		}



		// NOTE:
		//
		// [codepen_embed height="179" theme_id="dark" slug_hash="LBPJGV" default_tab="js,result" user="GeorgePark" preview="true" data-preview="true" data-editable="true"]See the Pen <a href='https://codepen.io/GeorgePark/pen/LBPJGV/'>Responsive Video Knockout Text (Pure CSS)</a> by George W. Park (<a href='https://codepen.io/GeorgePark'>@GeorgePark</a>) on <a href='https://codepen.io'>CodePen</a>.[/codepen_embed]
	}

	#
	# Superscript
	# --------------------------------------------------------------------------

	protected function inlineSuperText($excerpt)
	{
		if (!$this->superMode) {
			return;
		}

		if (preg_match('/(?:\^(?!\^)([^\^ ]*)\^(?!\^))/', $excerpt['text'], $matches)) {
			return array(

				// How many characters to advance the Parsedown's
				// cursor after being done processing this tag.
				'extent' => strlen($matches[0]),
				'element' => array(
					'name' => 'sup',
					'text' => $matches[1],
					'function' => 'lineElements'
				),

			);
		}
	}

	#
	# Subscript
	# --------------------------------------------------------------------------

	protected function inlineSubText($excerpt)
	{
		if (!$this->superMode) {
			return;
		}

		if (preg_match('/(?:~(?!~)([^~ ]*)~(?!~))/', $excerpt['text'], $matches)) {
			return array(

				// How many characters to advance the Parsedown's
				// cursor after being done processing this tag.
				'extent' => strlen($matches[0]),
				'element' => array(
					'name' => 'sub',
					'text' => $matches[1],
					'function' => 'lineElements'
				),

			);
		}
	}

	#
	# Insert
	# --------------------------------------------------------------------------

	protected function inlineInsertText($excerpt)
	{
		if (!$this->insertMode) {
			return;
		}

		if (preg_match('/^(\+\+)([\s\S]*?)(\+\+)/', $excerpt['text'], $matches)) {
			return array(

				// How many characters to advance the Parsedown's
				// cursor after being done processing this tag.
				'extent' => strlen($matches[0]),
				'element' => array(
					'name' => 'dfn', // changed by John Berea.
					'text' => $matches[2]
				),

			);
		}
	}



	#
	# Block Katex
	# --------------------------------------------------------------------------

	protected function blockKatex($Line)
	{
		if (!$this->katexMode) {
			return;
		}

		$marker = $Line['text'][0];

		$openerLength = strspn($Line['text'], $marker);

		if ($openerLength < 2) {
			return;
		}

		$infostring = trim(substr($Line['text'], $openerLength), "\t ");

		if (strpos($infostring, '$') !== false) {
			return;
		}

		$Element = array(
			'text' => ''
		);

		$Block = array(
			'char' => $marker,
			'openerLength' => $openerLength,
			'element' => array(
				'element' => $Element
			)
		);

		return $Block;
	}

	protected function blockKatexContinue($Line, $Block)
	{
		if (!$this->katexMode) {
			return;
		}

		if (isset($Block['complete'])) {
			return;
		}

		// A blank newline has occurred.
		if (isset($Block['interrupted'])) {
			$Block['element']['text'] .= "\n";
			unset($Block['interrupted']);
		}

		if (($len = strspn($Line['text'], $Block['char'])) >= $Block['openerLength'] and chop(substr($Line['text'], $len), ' ') === '') {
			$Block['element']['element']['text'] = "$$" . substr($Block['element']['element']['text'] . "$$", 1);

			$Block['complete'] = true;

			return $Block;
		}

		$Block['element']['element']['text'] .= "\n" . $Line['body'];

		return $Block;
	}

	protected function blockKatexComplete($block)
	{
		return $block;
	}


	#
	# Block Mermaid
	# --------------------------------------------------------------------------
	protected function blockMermaid($Line)
	{
		if (!$this->mermaidMode) {
			return;
		}

		$marker = $Line['text'][0];

		$openerLength = strspn($Line['text'], $marker);

		if ($openerLength < 2) {
			return;
		}

		$this->infostring = strtolower(trim(substr($Line['text'], $openerLength), "\t "));

		if (strpos($this->infostring, '%') !== false) {
			return;
		}


		$Element = array(
			'text' => ''
		);

		$Block = array(
			'char' => $marker,
			'openerLength' => $openerLength,
			'element' => array(
				'element' => $Element,
				'name' => 'div',
				'attributes' => array(
					'class' => 'mermaid'
				),
			)
		);

		return $Block;
	}

	protected function blockMermaidContinue($Line, $Block)
	{
		//if ($this->infostring == 'mermaid') {
		if (!$this->mermaidMode) {
			return;
		}

		if (isset($Block['complete'])) {
			return;
		}

		// A blank newline has occurred.
		if (isset($Block['interrupted'])) {
			$Block['element']['text'] .= "\n";
			unset($Block['interrupted']);
		}

		// Check for end of the block.
		if (($len = strspn($Line['text'], $Block['char'])) >= $Block['openerLength'] and chop(substr($Line['text'], $len), ' ') === '') {
			$Block['element']['element']['text'] = substr($Block['element']['element']['text'], 1);

			$Block['complete'] = true;

			return $Block;
		}

		$Block['element']['element']['text'] .= "\n" . $Line['body'];

		return $Block;
	}

	protected function blockMermaidComplete($block)
	{
		return $block;
	}


	#
	# List with support for checkbox
	# --------------------------------------------------------------------------

	protected function blockList($Line, array $CurrentBlock = null)
	{
		list($name, $pattern) = $Line['text'][0] <= '-' ? array('ul', '[*+-]') : array('ol', '[0-9]{1,9}+[.\)]');

		if (preg_match('/^('.$pattern.'([ ]++|$))(.*+)/', $Line['text'], $matches)) {
			$contentIndent = strlen($matches[2]);

			if ($contentIndent >= 5) {
				$contentIndent -= 1;
				$matches[1] = substr($matches[1], 0, -$contentIndent);
				$matches[3] = str_repeat(' ', $contentIndent) . $matches[3];
			} elseif ($contentIndent === 0) {
				$matches[1] .= ' ';
			}

			$markerWithoutWhitespace = strstr($matches[1], ' ', true);

			$Block = array(
				'indent' => $Line['indent'],
				'pattern' => $pattern,
				'data' => array(
					'type' => $name,
					'marker' => $matches[1],
					'markerType' => ($name === 'ul' ? $markerWithoutWhitespace : substr($markerWithoutWhitespace, -1)),
				),
				'element' => array(
					'name' => $name,
					'elements' => array(),
				),
			);
			$Block['data']['markerTypeRegex'] = preg_quote($Block['data']['markerType'], '/');

			if ($name === 'ol') {
				$listStart = ltrim(strstr($matches[1], $Block['data']['markerType'], true), '0') ?: '0';

				if ($listStart !== '1') {
					if (
						isset($CurrentBlock)
						and $CurrentBlock['type'] === 'Paragraph'
						and ! isset($CurrentBlock['interrupted'])
					) {
						return;
					}

					$Block['element']['attributes'] = array('start' => $listStart);
				}
			}

			$this->checkbox($matches[3], $attributes);

			$Block['li'] = array(
				'name' => 'li',
				'handler' => array(
					'function' => 'li',
					'argument' => !empty($matches[3]) ? array($matches[3]) : array(),
					'destination' => 'elements'
				)
			);

			$attributes && $Block['li']['attributes'] = $attributes;

			$Block['element']['elements'] []= & $Block['li'];

			return $Block;
		}
	}



	protected function blockListContinue($Line, array $Block)
	{
		if (isset($Block['interrupted']) and empty($Block['li']['handler']['argument'])) {
			return null;
		}

		$requiredIndent = ($Block['indent'] + strlen($Block['data']['marker']));

		if ($Line['indent'] < $requiredIndent
			and (
				(
					$Block['data']['type'] === 'ol'
					and preg_match('/^[0-9]++'.$Block['data']['markerTypeRegex'].'(?:[ ]++(.*)|$)/', $Line['text'], $matches)
				) or (
					$Block['data']['type'] === 'ul'
					and preg_match('/^'.$Block['data']['markerTypeRegex'].'(?:[ ]++(.*)|$)/', $Line['text'], $matches)
				)
			)
		) {
			if (isset($Block['interrupted'])) {
				$Block['li']['handler']['argument'] []= '';

				$Block['loose'] = true;

				unset($Block['interrupted']);
			}

			unset($Block['li']);

			$text = isset($matches[1]) ? $matches[1] : '';

			$this->checkbox($text, $attributes);


			$Block['indent'] = $Line['indent'];

			$Block['li'] = array(
				'name' => 'li',
				'handler' => array(
					'function' => 'li',
					'argument' => array($text),
					'destination' => 'elements'
				)
			);
			$attributes && $Block['li']['attributes'] = $attributes;
			$Block['element']['elements'] []= & $Block['li'];

			return $Block;
		} elseif ($Line['indent'] < $requiredIndent and $this->blockList($Line)) {
			return null;
		}

		if ($Line['text'][0] === '[' and $this->blockReference($Line)) {
			return $Block;
		}

		if ($Line['indent'] >= $requiredIndent) {
			if (isset($Block['interrupted'])) {
				$Block['li']['handler']['argument'] []= '';

				$Block['loose'] = true;

				unset($Block['interrupted']);
			}

			$text = substr($Line['body'], $requiredIndent);

			$Block['li']['handler']['argument'] []= $text;

			return $Block;
		}

		if (! isset($Block['interrupted'])) {
			$text = preg_replace('/^[ ]{0,'.$requiredIndent.'}+/', '', $Line['body']);

			$Block['li']['handler']['argument'] []= $text;

			return $Block;
		}
	}

	protected function blockListComplete(array $Block)
	{
		if (isset($Block['loose'])) {
			foreach ($Block['element']['elements'] as &$li) {
				if (end($li['handler']['argument']) !== '') {
					$li['handler']['argument'] []= '';
				}
			}
		}

		return $Block;
	}





	// -------------------------------------------------------------------------
	// -----------------------      Expermentels      --------------------------
	// -------------------------------------------------------------------------




	// -------------------------------------------------------------------------
	// -------------------------------------------------------------------------
	// -------------------------------------------------------------------------

	// Checkbox
	protected function checkbox(&$text, &$attributes)
	{
		if (strpos($text, '[x]') !== false || strpos($text, '[ ]') !== false) {
			$attributes = array("style" => "list-style: none;");
			$text = str_replace(array('[x]', '[ ]'), array(
				'<input type="checkbox" checked="true" disabled="true">',
				'<input type="checkbox" disabled="true">',
			), $text);
		}
	}

	// ReplaceAssoc
	protected function strReplaceAssoc(array $replace, $subject)
	{
		return str_replace(array_keys($replace), array_values($replace), $subject);
	}


	// TODO: Make use of user location
	protected function remove_accents($string)
	{
		if (!preg_match('/[\x80-\xff]/', $string)) {
			return $string;
		}

		if ($this->seems_utf8($string)) {
			$chars = array(
				// Decompositions for Latin-1 Supplement
				'Âª' => 'a', 'Âº' => 'o',
				'Ã€' => 'A', 'Ã' => 'A',
				'Ã‚' => 'A', 'Ãƒ' => 'A',
				'Ã„' => 'Ae', 'Ã…' => 'Aa',
				'Ã†' => 'AE','Ã‡' => 'C',
				'Ãˆ' => 'E', 'Ã‰' => 'E',
				'ÃŠ' => 'E', 'Ã‹' => 'E',
				'ÃŒ' => 'I', 'Ã' => 'I',
				'ÃŽ' => 'I', 'Ã' => 'I',
				'Ã' => 'D', 'Ã‘' => 'N',
				'Ã’' => 'O', 'Ã“' => 'O',
				'Ã”' => 'O', 'Ã•' => 'O',
				'Ã–' => 'Oe', 'Ã™' => 'U',
				'Ãš' => 'U', 'Ã›' => 'U',
				'Ãœ' => 'Ue', 'Ã' => 'Y',
				'Ãž' => 'TH','ÃŸ' => 'ss',
				'Ã ' => 'a', 'Ã¡' => 'a',
				'Ã¢' => 'a', 'Ã£' => 'a',
				'Ã¤' => 'ae', 'Ã¥' => 'aa',
				'Ã¦' => 'ae','Ã§' => 'c',
				'Ã¨' => 'e', 'Ã©' => 'e',
				'Ãª' => 'e', 'Ã«' => 'e',
				'Ã¬' => 'i', 'Ã­' => 'i',
				'Ã®' => 'i', 'Ã¯' => 'i',
				'Ã°' => 'd', 'Ã±' => 'n',
				'Ã²' => 'o', 'Ã³' => 'o',
				'Ã´' => 'o', 'Ãµ' => 'o',
				'Ã¶' => 'oe', 'Ã¸' => 'oe',
				'Ã¹' => 'u', 'Ãº' => 'u',
				'Ã»' => 'u', 'Ã¼' => 'ue',
				'Ã½' => 'y', 'Ã¾' => 'th',
				'Ã¿' => 'y', 'Ã˜' => 'Oe',
				// Decompositions for Latin Extended-A
				'Ä€' => 'A', 'Ä' => 'a',
				'Ä‚' => 'A', 'Äƒ' => 'a',
				'Ä„' => 'A', 'Ä…' => 'a',
				'Ä†' => 'C', 'Ä‡' => 'c',
				'Äˆ' => 'C', 'Ä‰' => 'c',
				'ÄŠ' => 'C', 'Ä‹' => 'c',
				'ÄŒ' => 'C', 'Ä' => 'c',
				'ÄŽ' => 'D', 'Ä' => 'd',
				'Ä' => 'DJ', 'Ä‘' => 'dj',
				'Ä’' => 'E', 'Ä“' => 'e',
				'Ä”' => 'E', 'Ä•' => 'e',
				'Ä–' => 'E', 'Ä—' => 'e',
				'Ä˜' => 'E', 'Ä™' => 'e',
				'Äš' => 'E', 'Ä›' => 'e',
				'Äœ' => 'G', 'Ä' => 'g',
				'Äž' => 'G', 'ÄŸ' => 'g',
				'Ä ' => 'G', 'Ä¡' => 'g',
				'Ä¢' => 'G', 'Ä£' => 'g',
				'Ä¤' => 'H', 'Ä¥' => 'h',
				'Ä¦' => 'H', 'Ä§' => 'h',
				'Ä¨' => 'I', 'Ä©' => 'i',
				'Äª' => 'I', 'Ä«' => 'i',
				'Ä¬' => 'I', 'Ä­' => 'i',
				'Ä®' => 'I', 'Ä¯' => 'i',
				'Ä°' => 'I', 'Ä±' => 'i',
				'Ä²' => 'IJ','Ä³' => 'ij',
				'Ä´' => 'J', 'Äµ' => 'j',
				'Ä¶' => 'K', 'Ä·' => 'k',
				'Ä¸' => 'k', 'Ä¹' => 'L',
				'Äº' => 'l', 'Ä»' => 'L',
				'Ä¼' => 'l', 'Ä½' => 'L',
				'Ä¾' => 'l', 'Ä¿' => 'L',
				'Å€' => 'l', 'Å' => 'L',
				'Å‚' => 'l', 'Åƒ' => 'N',
				'Å„' => 'n', 'Å…' => 'N',
				'Å†' => 'n', 'Å‡' => 'N',
				'Åˆ' => 'n', 'Å‰' => 'n',
				'ÅŠ' => 'N', 'Å‹' => 'n',
				'ÅŒ' => 'O', 'Å' => 'o',
				'ÅŽ' => 'O', 'Å' => 'o',
				'Å' => 'O', 'Å‘' => 'o',
				'Å’' => 'OE','Å“' => 'oe',
				'Å”' => 'R','Å•' => 'r',
				'Å–' => 'R','Å—' => 'r',
				'Å˜' => 'R','Å™' => 'r',
				'Åš' => 'S','Å›' => 's',
				'Åœ' => 'S','Å' => 's',
				'Åž' => 'S','ÅŸ' => 's',
				'Å ' => 'S', 'Å¡' => 's',
				'Å¢' => 'T', 'Å£' => 't',
				'Å¤' => 'T', 'Å¥' => 't',
				'Å¦' => 'T', 'Å§' => 't',
				'Å¨' => 'U', 'Å©' => 'u',
				'Åª' => 'U', 'Å«' => 'u',
				'Å¬' => 'U', 'Å­' => 'u',
				'Å®' => 'U', 'Å¯' => 'u',
				'Å°' => 'U', 'Å±' => 'u',
				'Å²' => 'U', 'Å³' => 'u',
				'Å´' => 'W', 'Åµ' => 'w',
				'Å¶' => 'Y', 'Å·' => 'y',
				'Å¸' => 'Y', 'Å¹' => 'Z',
				'Åº' => 'z', 'Å»' => 'Z',
				'Å¼' => 'z', 'Å½' => 'Z',
				'Å¾' => 'z', 'Å¿' => 's',
				// Decompositions for Latin Extended-B
				'È˜' => 'S', 'È™' => 's',
				'Èš' => 'T', 'È›' => 't',
				// Euro Sign
				'â‚¬' => 'E',
				// GBP (Pound) Sign
				'Â£' => '',
				// Vowels with diacritic (Vietnamese)
				// unmarked
				'Æ ' => 'O', 'Æ¡' => 'o',
				'Æ¯' => 'U', 'Æ°' => 'u',
				// grave accent
				'áº¦' => 'A', 'áº§' => 'a',
				'áº°' => 'A', 'áº±' => 'a',
				'á»€' => 'E', 'á»' => 'e',
				'á»’' => 'O', 'á»“' => 'o',
				'á»œ' => 'O', 'á»' => 'o',
				'á»ª' => 'U', 'á»«' => 'u',
				'á»²' => 'Y', 'á»³' => 'y',
				// hook
				'áº¢' => 'A', 'áº£' => 'a',
				'áº¨' => 'A', 'áº©' => 'a',
				'áº²' => 'A', 'áº³' => 'a',
				'áºº' => 'E', 'áº»' => 'e',
				'á»‚' => 'E', 'á»ƒ' => 'e',
				'á»ˆ' => 'I', 'á»‰' => 'i',
				'á»Ž' => 'O', 'á»' => 'o',
				'á»”' => 'O', 'á»•' => 'o',
				'á»ž' => 'O', 'á»Ÿ' => 'o',
				'á»¦' => 'U', 'á»§' => 'u',
				'á»¬' => 'U', 'á»­' => 'u',
				'á»¶' => 'Y', 'á»·' => 'y',
				// tilde
				'áºª' => 'A', 'áº«' => 'a',
				'áº´' => 'A', 'áºµ' => 'a',
				'áº¼' => 'E', 'áº½' => 'e',
				'á»„' => 'E', 'á»…' => 'e',
				'á»–' => 'O', 'á»—' => 'o',
				'á» ' => 'O', 'á»¡' => 'o',
				'á»®' => 'U', 'á»¯' => 'u',
				'á»¸' => 'Y', 'á»¹' => 'y',
				// acute accent
				'áº¤' => 'A', 'áº¥' => 'a',
				'áº®' => 'A', 'áº¯' => 'a',
				'áº¾' => 'E', 'áº¿' => 'e',
				'á»' => 'O', 'á»‘' => 'o',
				'á»š' => 'O', 'á»›' => 'o',
				'á»¨' => 'U', 'á»©' => 'u',
				// dot below
				'áº ' => 'A', 'áº¡' => 'a',
				'áº¬' => 'A', 'áº­' => 'a',
				'áº¶' => 'A', 'áº·' => 'a',
				'áº¸' => 'E', 'áº¹' => 'e',
				'á»†' => 'E', 'á»‡' => 'e',
				'á»Š' => 'I', 'á»‹' => 'i',
				'á»Œ' => 'O', 'á»' => 'o',
				'á»˜' => 'O', 'á»™' => 'o',
				'á»¢' => 'O', 'á»£' => 'o',
				'á»¤' => 'U', 'á»¥' => 'u',
				'á»°' => 'U', 'á»±' => 'u',
				'á»´' => 'Y', 'á»µ' => 'y',
				// Vowels with diacritic (Chinese, Hanyu Pinyin)
				'É‘' => 'a',
				// macron
				'Ç•' => 'U', 'Ç–' => 'u',
				// acute accent
				'Ç—' => 'U', 'Ç˜' => 'u',
				// caron
				'Ç' => 'A', 'ÇŽ' => 'a',
				'Ç' => 'I', 'Ç' => 'i',
				'Ç‘' => 'O', 'Ç’' => 'o',
				'Ç“' => 'U', 'Ç”' => 'u',
				'Ç™' => 'U', 'Çš' => 'u',
				// grave accent
				'Ç›' => 'U', 'Çœ' => 'u',
			);

			$string = strtr($string, $chars);
		} else {
			$chars = array();
			// Assume ISO-8859-1 if not UTF-8
			$chars['in'] = "\x80\x83\x8a\x8e\x9a\x9e"
				."\x9f\xa2\xa5\xb5\xc0\xc1\xc2"
				."\xc3\xc4\xc5\xc7\xc8\xc9\xca"
				."\xcb\xcc\xcd\xce\xcf\xd1\xd2"
				."\xd3\xd4\xd5\xd6\xd8\xd9\xda"
				."\xdb\xdc\xdd\xe0\xe1\xe2\xe3"
				."\xe4\xe5\xe7\xe8\xe9\xea\xeb"
				."\xec\xed\xee\xef\xf1\xf2\xf3"
				."\xf4\xf5\xf6\xf8\xf9\xfa\xfb"
				."\xfc\xfd\xff";

			$chars['out'] = "EfSZszYcYuAAAAAACEEEEIIIINOOOOOOUUUUYaaaaaaceeeeiiiinoooooouuuuyy";

			$string = strtr($string, $chars['in'], $chars['out']);
			$double_chars = array();
			$double_chars['in'] = array("\x8c", "\x9c", "\xc6", "\xd0", "\xde", "\xdf", "\xe6", "\xf0", "\xfe");
			$double_chars['out'] = array('OE', 'oe', 'AE', 'DH', 'TH', 'ss', 'ae', 'dh', 'th');
			$string = str_replace($double_chars['in'], $double_chars['out'], $string);
		}

		return $string;
	}




	// -------------------------------------------------------------------------

	protected function seems_utf8($str)
	{
		$this->mbstring_binary_safe_encoding();
		$length = strlen($str);
		$this->reset_mbstring_encoding();
		for ($i=0; $i < $length; $i++) {
			$c = ord($str[$i]);
			if ($c < 0x80) {
				$n = 0;
			} // 0bbbbbbb
			elseif (($c & 0xE0) == 0xC0) {
				$n=1;
			} // 110bbbbb
			elseif (($c & 0xF0) == 0xE0) {
				$n=2;
			} // 1110bbbb
			elseif (($c & 0xF8) == 0xF0) {
				$n=3;
			} // 11110bbb
			elseif (($c & 0xFC) == 0xF8) {
				$n=4;
			} // 111110bb
			elseif (($c & 0xFE) == 0xFC) {
				$n=5;
			} // 1111110b
			else {
				return false;
			} // Does not match any model
			for ($j=0; $j<$n; $j++) { // n bytes matching 10bbbbbb follow ?
				if ((++$i == $length) || ((ord($str[$i]) & 0xC0) != 0x80)) {
					return false;
				}
			}
		}
		return true;
	}

	protected function reset_mbstring_encoding()
	{
		$this->mbstring_binary_safe_encoding(true);
	}

	protected function mbstring_binary_safe_encoding($reset = false)
	{
		static $encodings = array();
		static $overloaded = null;

		if (is_null($overloaded)) {
			$overloaded = function_exists('mb_internal_encoding') && (ini_get('mbstring.func_overload') & 2);
		}

		if (false === $overloaded) {
			return;
		}

		if (! $reset) {
			$encoding = mb_internal_encoding();
			array_push($encodings, $encoding);
			mb_internal_encoding('ISO-8859-1');
		}

		if ($reset && $encodings) {
			$encoding = array_pop($encodings);
			mb_internal_encoding($encoding);
		}
	}
}