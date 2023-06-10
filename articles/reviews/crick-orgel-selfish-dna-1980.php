<?php

require_once $_SERVER['DOCUMENT_ROOT'].'/init.php';





$page['title'] = 'Comment on:  Ohno, So Much Junk in our Genome, 1972 | Berean Archive';
$page['theme'] = 'common/includes/theme-comments.php';
$page['head'] = '';

//$page_content
ob_start()?>
<div class="authorship">by John Berea
	<br>Published: Jan 2017
	<br>Updated:
	<?=date('F j, Y', filemtime(__FILE__))?>
</div>
<p>Comment on:</p>
<h1>Selfish genes, the phenotype, paradigm and genome evolution</h1>
<p>Francis Crick and Leslie Orgel, Nature, 1980
	<br><a href="http://profiles.nlm.nih.gov/ps/access/SCBCDG.pdf">Source</a> |
	<a href="https://web.archive.org/web/20150919160849/http://profiles.nlm.nih.gov/ps/access/SCBCDG.pdf">Archive.org</a>
</p>
<p>Francis Crick was a molecular biologist famous for discovering DNA.&nbsp; In 1980
	he and chemist Leslie Orgel argued for large amounts of junk DNA based on selfish
	DNA, the C-value paradox, and because it just didn't seem to be used:</p>
<blockquote>
	<p>In summary, then, there is a large amount of evidence which suggests, but does
		not prove, that <b>much DNA in higher organisms is little better than junk</b>...&nbsp;
		<br>
		<br>Although it is an old idea that much DNA in higher organisms has no specific
		function, and although it has been suggested before that this nonspecific
		DNA may rise to levels which are acceptable or even advantageous to an organism,
		depending on certain features of its life style, we feel that to regard much
		of this nonspecific DNA as selfish DNA is genuinely different from most
		earlier proposals... While proper care should be exercised both in labeling
		as selfish DNA every piece of DNA whose function is not immediately apparent
		and in invoking plausible but unproven hypotheses concerning the details of
		natural selection, the idea seems a useful one to bear in mind when exploring
		the complexities of the genomes of higher organisms. It could well make sense
		of many of the puzzles and paradoxes which have arisen over the last 10 or
		15 years.</p>
</blockquote>
<p>In the paper they also estimate a useless sequence of 1000 bases would have a selective
	disadvantage of 10<sup>-6</sup> and it would take 10<sup>6</sup> to 10<sup>8</sup> years
	for selection to remove it, while duplications arise and fix much more quickly.
	Moreso, some sequences (which they label selfish DNA) are predisposed to duplicate
	more often and selection favors them over sequences which are not. Therefore they
	predict large amounts of junk DNA.</p>
<p>They also argue that most DNA must be junk because so little of it codes for proteins:</p>
<blockquote
>
	<p>the majority of DNA sequences in most higher organisms do not code for protein since
		they do not occur at all in messenger RNA.&nbsp; Nor is it very plausible that
		all this extra DNA is needed for gene control, although some portion of it surely
		must be.</p>
	</blockquote>
	<p>As well as repeating the C-Value paradox:</p>
	<blockquote>
		<p>There is a striking connection between DNA content per cell and the minimum generation
			time of the plant.&nbsp; In brief, if such an angiosperm has more than 10 pg of
			DNA per cell, it is unlikely to be an ephemeral (that is, a plant with a short
			generation time).&nbsp; If it is a diploid and has more than 30pg of DNA, it is
			highly likely to be an obligate perennial rather than an annual or ephemeral.
			&nbsp;The converse, however, is not true, there being a fair number of perennials
			with DNA content of less than 30pg and a few with less than 10pg.&nbsp; A clear
			picture emerges that if a herbaceous plant has too much DNA it cannot have a short
			generation time.... [in salamanders] species with the longer developmental times
			often have higher C values.... genome size sets a limit beyond which development
			cannot be accelerated...</p>
		<p>We also have to account for the vast amount of DNA found in certain species, such
			as lilies and salamanders, which may amount to as much as 20 times that found
			in the human genome.&nbsp; It seems totally implausible that the number of radically
			different genes needed in a salamander is 20 times that in a man.</p>
	</blockquote>
<?php $page_content = ob_get_clean();



require_once 'common/includes/theme-comments.php';